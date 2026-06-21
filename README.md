# RPD

Aplikasi web production-ready berbasis **Laravel 11 + PHP 8.3+ + PostgreSQL + Filament v3 + Blade + Bootstrap 5**.
RPD memiliki dua area utama: **frontend publik** dan **admin panel** (Filament).

## Fitur Utama

### Frontend Publik
- Homepage (hero/banner, produk unggulan, produk terbaru, preview FAQ, CTA)
- Katalog produk + pencarian + filter kategori + detail produk
- Leaderboard
- FAQ
- Kontak (form tersimpan ke database)
- Cek invoice
- Klaim garansi (dengan upload lampiran)
- Autentikasi user (register, login, logout)
- Verifikasi OTP WhatsApp + resend OTP

### Admin Panel (Filament v3, path `/admin`)
- CRUD: Users, Categories, Products, Banners, FAQs, Leaderboard Entries, Site Settings, Invoice Records, Warranty Claims, Contact Messages
- Upload gambar produk + galeri
- Pengelolaan status klaim garansi + catatan admin
- Pengaturan situs berbasis key-value

## Stack
- Laravel 11
- PHP 8.3+
- PostgreSQL
- Filament v3
- Blade + Bootstrap 5 (via CDN)
- Eloquent ORM, Form Request validation
- Service layer untuk OTP + abstraksi provider WhatsApp
- Middleware: verifikasi OTP & akses admin

## Persyaratan
- PHP 8.3 atau lebih baru (ekstensi: `pdo_pgsql`, `mbstring`, `openssl`, `fileinfo`, `gd`/`intl` opsional)
- Composer 2.x
- PostgreSQL 13+
- (Opsional) Node.js untuk build aset — tidak wajib karena Bootstrap memakai CDN

## Instalasi

```bash
# 1. Install dependency PHP
composer install

# 2. Salin file environment
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Atur koneksi database PostgreSQL di .env
#    DB_CONNECTION=pgsql
#    DB_HOST=127.0.0.1
#    DB_PORT=5432
#    DB_DATABASE=rpd
#    DB_USERNAME=postgres
#    DB_PASSWORD=postgres
#    (Buat database 'rpd' terlebih dahulu di PostgreSQL)

# 5. Jalankan migrasi + seeder
php artisan migrate --seed

# 6. Buat symbolic link storage publik (untuk upload gambar/lampiran)
php artisan storage:link

# 7. Jalankan server
php artisan serve
```

Akses:
- Frontend: http://localhost:8000
- Admin panel: http://localhost:8000/admin

## Akun Default (dari seeder)

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@rpd.local` | `password123` |
| User  | `user@rpd.local`  | `password123` |

Kedua akun sudah berstatus OTP-verified.

## Konfigurasi OTP WhatsApp

OTP dikirim melalui abstraksi provider WhatsApp. Konfigurasi di `.env`:

```env
WHATSAPP_OTP_ENABLED=false      # true untuk benar-benar mengirim
WHATSAPP_OTP_PROVIDER=log       # log | http
WHATSAPP_API_URL=               # endpoint gateway (untuk provider http)
WHATSAPP_API_TOKEN=             # token gateway
WHATSAPP_SENDER=                # nomor/sender id
WHATSAPP_OTP_TTL=5              # masa berlaku OTP (menit)
```

- Saat `WHATSAPP_OTP_ENABLED=false` atau `WHATSAPP_OTP_PROVIDER=log`, kode OTP **tidak benar-benar dikirim** melainkan ditulis ke `storage/logs/laravel.log`. Ini membuat aplikasi tetap bisa dijalankan & diuji tanpa gateway nyata.
- Untuk memakai gateway nyata, set `WHATSAPP_OTP_ENABLED=true`, `WHATSAPP_OTP_PROVIDER=http`, dan isi `WHATSAPP_API_URL`/`WHATSAPP_API_TOKEN`/`WHATSAPP_SENDER`.

### Cara melihat OTP saat mode log
Setelah register/login (user belum verified), buka file log:

```bash
tail -f storage/logs/laravel.log
```

Cari baris `[OTP] Delivery disabled, code generated.` yang memuat `code`.

## Alur OTP
1. **Register** → user dibuat, OTP digenerate & dikirim, user otomatis login, lalu diarahkan ke `/otp/verify`.
2. **Login** (jika belum verified) → OTP baru digenerate & dikirim, diarahkan ke `/otp/verify`.
3. **Verify** → memasukkan 6 digit OTP. Jika benar & belum kedaluwarsa, `otp_verified_at` diisi dan `otp_code` dibersihkan.
4. **Resend** → menghasilkan OTP baru.
5. Middleware `otp.verified` mengarahkan user belum-verified ke halaman verifikasi (tanpa loop, karena route OTP & logout dikecualikan).

## Struktur Folder Inti

```
app/
  Filament/Resources/      # 10 resource admin + Pages
  Http/Controllers/        # controller frontend + Auth/
  Http/Middleware/         # EnsureOtpVerified, EnsureAdmin
  Http/Requests/           # Form Request validation
  Models/                  # Eloquent models
  Providers/               # AppServiceProvider, Filament/AdminPanelProvider
  Services/Otp/            # OtpService
  Services/WhatsApp/       # WhatsAppProvider + Log/Http impl
  Support/helpers.php      # setting(), setting_asset()
bootstrap/                 # app.php (middleware alias), providers.php
config/                    # konfigurasi Laravel (+ services.whatsapp)
database/migrations/       # 10 tabel domain + tabel framework
database/seeders/          # admin, settings, kategori, produk, dll
resources/views/           # Blade frontend (Bootstrap 5)
routes/web.php             # semua route publik + auth + otp
```

## Catatan Pengembangan
- Slug Category & Product dibuat otomatis dari `name` jika dikosongkan.
- Query frontend hanya menampilkan data dengan `is_active = true`.
- Upload disimpan pada disk `public` (`storage/app/public`), diakses via `storage:link`.
- Site settings di-cache (`Cache::rememberForever('site_settings')`) dan otomatis di-flush saat data berubah.
