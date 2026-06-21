#!/usr/bin/env bash
#
# ============================================================================
#  RPD (web-qwrty) — One-shot VPS Installer
#  Target: Ubuntu 22.04 / 24.04 (fresh server)
#  Stack : PHP 8.3 + Composer + PostgreSQL + Nginx + Laravel 11 + Filament v3
#
#  Cara pakai (jalankan sebagai root / sudo):
#     wget https://raw.githubusercontent.com/Rayzell25/web-qwrty/main/deploy/install.sh
#     chmod +x install.sh
#     sudo ./install.sh
#
#  ATAU edit dulu variabel di bawah (DB_PASS, DOMAIN) lalu jalankan.
# ============================================================================

set -euo pipefail

# ----------------------------------------------------------------------------
# 1) KONFIGURASI — UBAH SESUAI KEBUTUHAN
# ----------------------------------------------------------------------------
APP_NAME="RPD"
APP_DIR="/var/www/web-qwrty"            # lokasi instalasi
REPO_URL="https://github.com/Rayzell25/web-qwrty.git"
BRANCH="main"

PHP_VERSION="8.3"

# Domain: isi domain Anda (mis. rpd.example.com) ATAU biarkan "_" untuk akses via IP
DOMAIN="_"

# URL aplikasi yang akan ditulis ke .env (sesuaikan dengan domain/IP)
APP_URL="http://localhost"

# Kredensial database PostgreSQL (GANTI DB_PASS!)
DB_NAME="rpd"
DB_USER="rpd_user"
DB_PASS="GantiPasswordIniYa123"

# (internal) diisi otomatis jika script dijalankan dari dalam repo yang sudah di-clone
USE_EXISTING_DIR=0

# ----------------------------------------------------------------------------
# Helper output
# ----------------------------------------------------------------------------
log()  { echo -e "\n\033[1;32m==> $*\033[0m"; }
warn() { echo -e "\033[1;33m[!] $*\033[0m"; }
die()  { echo -e "\033[1;31m[x] $*\033[0m" >&2; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Script harus dijalankan sebagai root (gunakan: sudo ./install.sh)"

export DEBIAN_FRONTEND=noninteractive
export COMPOSER_ALLOW_SUPERUSER=1

# Auto-detect: jika script dijalankan dari DALAM repo yang sudah di-clone
# (mis. /root/web-qwrty), install di tempat itu — jangan clone ulang.
SCRIPT_PATH="$(readlink -f "$0")"
REPO_ROOT="$(dirname "$(dirname "${SCRIPT_PATH}")")"
if [ -f "${REPO_ROOT}/artisan" ] && [ -f "${REPO_ROOT}/composer.json" ]; then
    APP_DIR="${REPO_ROOT}"
    USE_EXISTING_DIR=1
fi

# ----------------------------------------------------------------------------
# 2) UPDATE SISTEM & DEPENDENSI DASAR
# ----------------------------------------------------------------------------
log "Update sistem & install paket dasar"
apt-get update -y
apt-get upgrade -y
apt-get install -y \
    software-properties-common curl wget git unzip zip \
    ca-certificates gnupg lsb-release acl

# ----------------------------------------------------------------------------
# 3) INSTALL PHP 8.3 + EKSTENSI (via PPA ondrej/php)
# ----------------------------------------------------------------------------
log "Install PHP ${PHP_VERSION} + ekstensi"
if ! grep -q "ondrej/php" /etc/apt/sources.list.d/* 2>/dev/null; then
    add-apt-repository -y ppa:ondrej/php
    apt-get update -y
fi

apt-get install -y \
    php${PHP_VERSION} \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-pgsql \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-intl

# PENTING: jadikan PHP 8.3 sebagai default CLI, supaya 'composer' & 'php artisan'
# memakai 8.3 (bukan PHP lama 8.1/8.2 yang mungkin sudah ada di VPS).
update-alternatives --set php "/usr/bin/php${PHP_VERSION}" 2>/dev/null || true
log "PHP CLI aktif: $(php -v | head -1)"

# ----------------------------------------------------------------------------
# 4) INSTALL COMPOSER
# ----------------------------------------------------------------------------
if ! command -v composer >/dev/null 2>&1; then
    log "Install Composer"
    EXPECTED_SIG="$(curl -fsSL https://composer.github.io/installer.sig)"
    php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
    ACTUAL_SIG="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
    [ "$EXPECTED_SIG" = "$ACTUAL_SIG" ] || die "Signature Composer tidak cocok, batal."
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm -f /tmp/composer-setup.php
else
    log "Composer sudah terpasang: $(composer --version)"
fi

# ----------------------------------------------------------------------------
# 5) INSTALL & SETUP POSTGRESQL  (blok DB — bisa dijalankan terpisah)
# ----------------------------------------------------------------------------
log "Install PostgreSQL"
apt-get install -y postgresql postgresql-contrib
systemctl enable --now postgresql

setup_database() {
    log "Setup database: ${DB_NAME} (user: ${DB_USER})"

    # Buat role/user jika belum ada
    if ! sudo -u postgres psql -tAc "SELECT 1 FROM pg_roles WHERE rolname='${DB_USER}'" | grep -q 1; then
        sudo -u postgres psql -c "CREATE ROLE ${DB_USER} LOGIN PASSWORD '${DB_PASS}';"
    else
        sudo -u postgres psql -c "ALTER ROLE ${DB_USER} WITH PASSWORD '${DB_PASS}';"
    fi

    # Buat database jika belum ada
    if ! sudo -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='${DB_NAME}'" | grep -q 1; then
        sudo -u postgres psql -c "CREATE DATABASE ${DB_NAME} OWNER ${DB_USER};"
    fi

    # Pastikan hak akses (penting untuk PostgreSQL 15+)
    sudo -u postgres psql -c "ALTER DATABASE ${DB_NAME} OWNER TO ${DB_USER};"
    sudo -u postgres psql -d "${DB_NAME}" -c "GRANT ALL ON SCHEMA public TO ${DB_USER};"
}
setup_database

# ----------------------------------------------------------------------------
# 6) INSTALL NGINX
# ----------------------------------------------------------------------------
log "Install Nginx"
apt-get install -y nginx
systemctl enable --now nginx

# ----------------------------------------------------------------------------
# 7) DEPLOY APLIKASI (clone / update)
# ----------------------------------------------------------------------------
if [ "${USE_EXISTING_DIR}" -eq 1 ]; then
    log "Pakai folder repo yang sudah ada: ${APP_DIR} (skip clone/pull)"
elif [ -d "${APP_DIR}/.git" ]; then
    log "Repo sudah ada, melakukan git pull"
    git config --global --add safe.directory "${APP_DIR}" || true
    git -C "${APP_DIR}" fetch origin "${BRANCH}"
    git -C "${APP_DIR}" checkout "${BRANCH}"
    git -C "${APP_DIR}" pull origin "${BRANCH}"
else
    log "Clone repo ke ${APP_DIR}"
    mkdir -p "$(dirname "${APP_DIR}")"
    git clone -b "${BRANCH}" "${REPO_URL}" "${APP_DIR}"
fi

cd "${APP_DIR}"

# ----------------------------------------------------------------------------
# 8) COMPOSER INSTALL (production)
# ----------------------------------------------------------------------------
log "composer install (tanpa dev, optimized)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ----------------------------------------------------------------------------
# 9) KONFIGURASI .env
# ----------------------------------------------------------------------------
log "Menyiapkan file .env"
if [ ! -f .env ]; then
    cp .env.example .env
fi

sed -i "s|^APP_NAME=.*|APP_NAME=${APP_NAME}|"            .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|"               .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|"                .env
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|"               .env
sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=pgsql|"        .env
sed -i "s|^DB_HOST=.*|DB_HOST=127.0.0.1|"                .env
sed -i "s|^DB_PORT=.*|DB_PORT=5432|"                     .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|"       .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USER}|"       .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|"       .env

# APP_KEY (generate jika kosong)
if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# ----------------------------------------------------------------------------
# 10) MIGRATE + SEED, STORAGE LINK, OPTIMIZE
# ----------------------------------------------------------------------------
log "Migrasi database + seeder"
php artisan migrate --seed --force

log "Symlink storage publik"
php artisan storage:link || true

log "Cache konfigurasi (optimize produksi)"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ----------------------------------------------------------------------------
# 11) PERMISSION
# ----------------------------------------------------------------------------
log "Set permission folder"
chown -R www-data:www-data "${APP_DIR}"
find "${APP_DIR}" -type d -exec chmod 755 {} \;
find "${APP_DIR}" -type f -exec chmod 644 {} \;
chmod -R 775 "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"
chown -R www-data:www-data "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"

# ----------------------------------------------------------------------------
# 12) KONFIGURASI NGINX
# ----------------------------------------------------------------------------
log "Menulis konfigurasi Nginx"
NGINX_CONF="/etc/nginx/sites-available/web-qwrty"
cat > "${NGINX_CONF}" <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};
    root ${APP_DIR}/public;

    index index.php;
    charset utf-8;

    client_max_body_size 20M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\$ {
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX

ln -sf "${NGINX_CONF}" /etc/nginx/sites-enabled/web-qwrty
rm -f /etc/nginx/sites-enabled/default

log "Test & reload Nginx + PHP-FPM"
nginx -t
systemctl restart "php${PHP_VERSION}-fpm"
systemctl reload nginx

# ----------------------------------------------------------------------------
# 13) SELESAI
# ----------------------------------------------------------------------------
SERVER_IP="$(curl -fsSL https://api.ipify.org 2>/dev/null || hostname -I | awk '{print $1}')"

cat <<DONE

============================================================
  INSTALASI ${APP_NAME} SELESAI 🎉
============================================================
  Lokasi app   : ${APP_DIR}
  Web (publik) : http://${SERVER_IP}/        (atau http://${DOMAIN})
  Admin panel  : http://${SERVER_IP}/admin

  Login admin default (dari seeder):
     Email    : admin@rpd.local
     Password : password123

  Database:
     Nama  : ${DB_NAME}
     User  : ${DB_USER}

  CATATAN:
   - Ganti password admin & DB_PASS untuk produksi.
   - Jika pakai domain, set DNS A record ke ${SERVER_IP},
     lalu pasang HTTPS:  apt install certbot python3-certbot-nginx
                         certbot --nginx -d ${DOMAIN}
   - OTP WhatsApp default mode "log" (lihat storage/logs/laravel.log).
     Untuk aktifkan gateway nyata, edit .env: WHATSAPP_OTP_ENABLED=true
============================================================
DONE
