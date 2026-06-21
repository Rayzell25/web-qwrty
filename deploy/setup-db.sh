#!/usr/bin/env bash
#
# ============================================================================
#  RPD — Standalone PostgreSQL setup (OPSIONAL)
#  Pakai ini HANYA jika ingin install/reset database terpisah dari install.sh.
#  install.sh sudah menjalankan langkah ini secara otomatis.
#
#  Cara pakai:
#     sudo ./setup-db.sh
# ============================================================================

set -euo pipefail

# ---- Konfigurasi (samakan dengan install.sh) ----
DB_NAME="rpd"
DB_USER="rpd_user"
DB_PASS="GantiPasswordIniYa123"

log()  { echo -e "\n\033[1;32m==> $*\033[0m"; }
die()  { echo -e "\033[1;31m[x] $*\033[0m" >&2; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Jalankan sebagai root (sudo ./setup-db.sh)"

export DEBIAN_FRONTEND=noninteractive

log "Pastikan PostgreSQL terpasang"
if ! command -v psql >/dev/null 2>&1; then
    apt-get update -y
    apt-get install -y postgresql postgresql-contrib
fi
systemctl enable --now postgresql

log "Setup database ${DB_NAME} (user ${DB_USER})"

# Role/user
if ! sudo -u postgres psql -tAc "SELECT 1 FROM pg_roles WHERE rolname='${DB_USER}'" | grep -q 1; then
    sudo -u postgres psql -c "CREATE ROLE ${DB_USER} LOGIN PASSWORD '${DB_PASS}';"
else
    sudo -u postgres psql -c "ALTER ROLE ${DB_USER} WITH PASSWORD '${DB_PASS}';"
fi

# Database
if ! sudo -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='${DB_NAME}'" | grep -q 1; then
    sudo -u postgres psql -c "CREATE DATABASE ${DB_NAME} OWNER ${DB_USER};"
fi

# Hak akses (penting untuk PostgreSQL 15+)
sudo -u postgres psql -c "ALTER DATABASE ${DB_NAME} OWNER TO ${DB_USER};"
sudo -u postgres psql -d "${DB_NAME}" -c "GRANT ALL ON SCHEMA public TO ${DB_USER};"

cat <<DONE

============================================================
  DATABASE SIAP ✅
  Nama  : ${DB_NAME}
  User  : ${DB_USER}
  Host  : 127.0.0.1:5432

  Pastikan .env aplikasi berisi:
     DB_CONNECTION=pgsql
     DB_HOST=127.0.0.1
     DB_PORT=5432
     DB_DATABASE=${DB_NAME}
     DB_USERNAME=${DB_USER}
     DB_PASSWORD=${DB_PASS}

  Lalu jalankan di folder app:
     php artisan migrate --seed --force
============================================================
DONE
