#!/usr/bin/env bash
#
# ============================================================================
#  RPD — Installer Gateway WhatsApp OTP (terpisah)
#  Berbasis Baileys (pairing via QR, tanpa Chromium).
#
#  JALANKAN SETELAH install.sh (web + db) sudah selesai.
#
#  Cara pakai (dari dalam folder repo):
#     cd /root/web-qwrty          # atau /var/www/web-qwrty
#     sudo bash deploy/install-wa.sh
#
#  Setelah itu: scan QR (lihat instruksi di akhir), selesai.
# ============================================================================

set -euo pipefail

NODE_MAJOR=20
PM2_APP="rpd-wa-gateway"
WA_PORT=3000

log()  { echo -e "\n\033[1;32m==> $*\033[0m"; }
warn() { echo -e "\033[1;33m[!] $*\033[0m"; }
die()  { echo -e "\033[1;31m[x] $*\033[0m" >&2; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Jalankan sebagai root (sudo bash deploy/install-wa.sh)"

export DEBIAN_FRONTEND=noninteractive
unset NODE_OPTIONS 2>/dev/null || true

# ---- Deteksi lokasi repo (Laravel) & folder gateway ----
SCRIPT_PATH="$(readlink -f "$0")"
REPO_ROOT="$(dirname "$(dirname "${SCRIPT_PATH}")")"
LARAVEL_DIR="${REPO_ROOT}"
GATEWAY_DIR="${REPO_ROOT}/wa-gateway"

[ -f "${LARAVEL_DIR}/artisan" ]      || die "Tidak menemukan artisan di ${LARAVEL_DIR}. Jalankan dari dalam repo."
[ -f "${GATEWAY_DIR}/index.js" ]     || die "Tidak menemukan ${GATEWAY_DIR}/index.js. Pastikan repo terbaru (git pull)."

# ----------------------------------------------------------------------------
# 1) INSTALL Node.js + pm2
# ----------------------------------------------------------------------------
if ! command -v node >/dev/null 2>&1 || [ "$(node -v | sed 's/v\([0-9]*\).*/\1/')" -lt 18 ]; then
    log "Install Node.js ${NODE_MAJOR}.x"
    curl -fsSL "https://deb.nodesource.com/setup_${NODE_MAJOR}.x" | bash -
    apt-get install -y nodejs
fi
log "Node: $(node -v) | npm: $(npm -v)"

if ! command -v pm2 >/dev/null 2>&1; then
    log "Install pm2 (process manager)"
    npm install -g pm2
fi

# ----------------------------------------------------------------------------
# 2) TOKEN RAHASIA (dibuat sekali, dipakai bersama Laravel)
# ----------------------------------------------------------------------------
if [ -f "${GATEWAY_DIR}/.env" ] && grep -q '^API_TOKEN=.\+' "${GATEWAY_DIR}/.env"; then
    API_TOKEN="$(grep '^API_TOKEN=' "${GATEWAY_DIR}/.env" | head -1 | cut -d= -f2-)"
    log "Memakai API_TOKEN yang sudah ada"
else
    API_TOKEN="$(openssl rand -hex 24)"
    log "Membuat API_TOKEN baru"
fi

# ----------------------------------------------------------------------------
# 3) Konfigurasi .env gateway + npm install
# ----------------------------------------------------------------------------
log "Menulis ${GATEWAY_DIR}/.env"
cat > "${GATEWAY_DIR}/.env" <<ENV
PORT=${WA_PORT}
HOST=127.0.0.1
API_TOKEN=${API_TOKEN}
AUTH_DIR=./auth
ENV

log "npm install dependencies gateway (Baileys, dll)"
cd "${GATEWAY_DIR}"
npm install --omit=dev --no-audit --no-fund

# ----------------------------------------------------------------------------
# 4) Sambungkan ke .env Laravel (WHATSAPP_*) + refresh config cache
# ----------------------------------------------------------------------------
set_env() { # file key value
    local file="$1" key="$2" val="$3"
    if grep -q "^${key}=" "${file}"; then
        sed -i "s|^${key}=.*|${key}=${val}|" "${file}"
    else
        echo "${key}=${val}" >> "${file}"
    fi
}

log "Mengupdate .env Laravel agar memakai gateway"
LARAVEL_ENV="${LARAVEL_DIR}/.env"
[ -f "${LARAVEL_ENV}" ] || die "File ${LARAVEL_ENV} belum ada. Jalankan install.sh dulu."

set_env "${LARAVEL_ENV}" "WHATSAPP_OTP_ENABLED" "true"
set_env "${LARAVEL_ENV}" "WHATSAPP_OTP_PROVIDER" "http"
set_env "${LARAVEL_ENV}" "WHATSAPP_API_URL" "http://127.0.0.1:${WA_PORT}/send"
set_env "${LARAVEL_ENV}" "WHATSAPP_API_TOKEN" "${API_TOKEN}"

log "Refresh config cache Laravel"
cd "${LARAVEL_DIR}"
php artisan config:clear || true
php artisan config:cache || true

# ----------------------------------------------------------------------------
# 5) Jalankan gateway dengan pm2 (autostart saat reboot)
# ----------------------------------------------------------------------------
log "Menjalankan gateway via pm2"
cd "${GATEWAY_DIR}"
pm2 delete "${PM2_APP}" >/dev/null 2>&1 || true
pm2 start index.js --name "${PM2_APP}"
pm2 save
pm2 startup systemd -u root --hp /root >/dev/null 2>&1 || pm2 startup >/dev/null 2>&1 || true
pm2 save

# ----------------------------------------------------------------------------
# 6) SELESAI — instruksi scan QR
# ----------------------------------------------------------------------------
cat <<DONE

============================================================
  GATEWAY WHATSAPP TERPASANG 🎉  —  TINGGAL SCAN QR
============================================================
  Langkah terakhir (sekali saja):

  1) Tampilkan QR di terminal:
        pm2 logs ${PM2_APP}

  2) Di HP, buka WhatsApp:
        Setelan > Perangkat Tertaut > Tautkan Perangkat
     lalu scan QR yang muncul di terminal.

  3) Tunggu sampai muncul:  "[WA] Tersambung & siap mengirim pesan."
     Tekan Ctrl+C untuk keluar dari log (gateway tetap jalan).

  Tes kirim manual (opsional):
     curl -X POST http://127.0.0.1:${WA_PORT}/send \\
       -H "Authorization: Bearer ${API_TOKEN}" \\
       -H "Content-Type: application/json" \\
       -d '{"number":"08xxxxxxxxxx","message":"Tes OTP RPD"}'

  Status koneksi:
     curl http://127.0.0.1:${WA_PORT}/health

  Perintah berguna:
     pm2 restart ${PM2_APP}      # restart gateway
     pm2 logs ${PM2_APP}         # lihat log / QR
     rm -rf ${GATEWAY_DIR}/auth && pm2 restart ${PM2_APP}   # logout & scan ulang
============================================================

  CATATAN PENTING:
   - Gunakan nomor WhatsApp KHUSUS (bukan nomor pribadi utama).
     Ini gateway tidak resmi (seperti WhatsApp Web), jadi ada risiko
     pembatasan jika dipakai mengirim spam. Untuk OTP normal aman.
   - Token & gateway hanya bisa diakses lokal (127.0.0.1), tidak terbuka
     ke internet -> aman.
============================================================
DONE
