# Panduan Deploy ke InfinityFree

Panduan lengkap deploy **SIVISIT CareVisit Monitor** (Laravel 12) ke InfinityFree hosting gratis.

---

## Persyaratan

- Akun [InfinityFree](https://infinityfree.com/)
- Domain (bisa pakai subdomain gratis `*.infinityfreeapp.com`)
- FTP Client (FileZilla / WinSCP)
- Composer & Node.js terinstall di komputer lokal

---

## 1. Persiapan Lokal

Jalankan semua perintah berikut di folder project lokal:

```bash
# 1. Install dependencies production (hapus dev dependencies)
composer install --no-dev --optimize-autoloader

# 2. Bangun frontend assets
npm install
npm run build

# 3. Generate APP_KEY & simpan
php artisan key:generate --show
# Output: base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
# CATAT key ini, akan dipakai nanti
```

---

## 2. Konfigurasi Environment

Buat file `.env` untuk production:

```bash
cp .env.example .env
```

Isi dengan konfigurasi berikut:

```env
APP_NAME="SIVISIT CareVisit Monitor"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://namafolder.infinityfreeapp.com

APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx   <- isi dari langkah 1

DB_CONNECTION=mysql
DB_HOST=sql123.infinityfree.com                    <- dari control panel InfinityFree
DB_PORT=3306
DB_DATABASE=if0_12345678_db_sivisit                <- dari control panel
DB_USERNAME=if0_12345678                            <- dari control panel
DB_PASSWORD=password_database_anda                  <- dari control panel

SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=log                                    <- email nonaktif, pakai log

FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=file
```

> **Catatan:** Data database (host, nama, user) bisa didapat dari **InfinityFree Control Panel → MySQL Database**.

---

## 3. Persiapan Database

1. Login ke [InfinityFree Control Panel](https://cp.infinityfree.com/)
2. Masuk menu **MySQL Database**
3. Buat database baru (misal: `if0_12345678_db_sivisit`)
4. Catat **Host**, **Database Name**, **Username**, **Password**
5. Isikan data tersebut ke `.env` di atas

### Import Struktur Database

Karena InfinityFree **tidak ada SSH/Artisan**, kita migrasi manual:

**Opsi A — Via PHPMyAdmin (termudah):**
1. Di komputer lokal, jalankan:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
2. Ekspor database lokal ke file `.sql`:
   ```bash
   mysqldump -u root health_monitoring_db > sivisit_db.sql
   ```
3. Upload `sivisit_db.sql` ke PHPMyAdmin InfinityFree dan import

**Opsi B — Via migration SQL saja:**
1. Generate migration SQL dari Laravel:
   ```bash
   php artisan migrate --pretend > migrations.sql
   ```
2. Upload dan jalankan `migrations.sql` via PHPMyAdmin
3. Jalankan seeder manual via PHPMyAdmin

---

## 4. Upload File ke InfinityFree

### Upload via FTP

| Tool | Koneksi |
|------|---------|
| **Host** | `ftp.infinityfree.com` |
| **Username** | `if0_12345678` (dari control panel) |
| **Password** | password FTP anda |
| **Port** | 21 |

### Struktur Upload

Upload **SEMUA file & folder** project ke folder `/htdocs/`:

```
htdocs/
├── .htaccess              ← sudah include rewrite ke /public/
├── .env                   ← konfigurasi production
├── app/                   ← Laravel core
├── bootstrap/
├── config/
├── database/
├── public/                ← Laravel public (document root via rewrite)
│   ├── index.php
│   ├── .htaccess
│   ├── app/               ← Frontend PHP Native
│   ├── build/             ← Vite compiled assets
│   └── css/
├── resources/
├── routes/
├── storage/               ← pastikan writable
├── vendor/                ← Composer dependencies
└── ...file lainnya
```

> **PENTING:** Folder `storage/` harus **writable** oleh web server.
> Di InfinityFree, set permission folder `storage/framework/`, `storage/logs/`, `storage/app/` ke **755** atau **777** via FTP.

---

## 5. Set Permission Storage

Via FTP client:
1. Klik kanan folder `storage/` → **File Permissions**
2. Set ke `755` (centang **Recurse into subdirectories**)
3. Lakukan hal yang sama untuk folder `bootstrap/cache/`

Atau jika pakai command (tidak semua FTP support):
```
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 6. Verifikasi Deployment

Setelah upload selesai, akses:

| URL | Fungsi |
|-----|--------|
| `https://namafolder.infinityfreeapp.com/` | Redirect ke Laravel (harusnya 404 → normal) |
| `https://namafolder.infinityfreeapp.com/app/` | **Landing page** SIVISIT |
| `https://namafolder.infinityfreeapp.com/app/Pages/login.php` | **Halaman Login** |
| `https://namafolder.infinityfreeapp.com/login` | **Login Admin Laravel Blade** |
| `https://namafolder.infinityfreeapp.com/api/patients` | **API Test** (harus return JSON) |

### Troubleshooting

**404 Not Found:**
- Pastikan `.htaccess` sudah terupload dengan benar
- Cek apakah mod_rewrite aktif di InfinityFree (biasanya aktif default)

**Error 500 / Blank Page:**
- Cek error log di InfinityFree Control Panel → **Error Log**
- Pastikan `storage/logs/` writable

**Database Connection Error:**
- Verifikasi data `.env` (host, nama db, user, password)
- Cek apakah database sudah dibuat di InfinityFree

**APP_KEY error:**
- Pastikan `APP_KEY` terisi di `.env`
- Generate ulang: `php artisan key:generate` dan copy key

---

## 7. Update Konfigurasi Frontend (PHP Native)

Edit `public/app/config.php` dan pastikan `API_BASE_URL` mengarah ke domain baru:

```php
define('API_BASE_URL', 'https://namafolder.infinityfreeapp.com/api');
```

---

## 8. Catatan Penting

| Fitur | Keterangan |
|-------|-----------|
| **Cron Jobs** | Tidak tersedia di free tier. Jadwal kunjungan tidak otomatis terkirim |
| **SSL** | Sudotomatis aktif (Cloudflare SSL) |
| **PHP Version** | Pilih PHP 8.2+ di Control Panel → **PHP Configuration** |
| **File Upload** | Maks 20MB per file |
| **Storage Kuota** | 1GB (cukup untuk aplikasi ini) |
| **Email** | Tidak disarankan untuk production, gunakan layekirim eksternal |

---

## 9. Redeploy (Update Aplikasi)

Saat ada update kode:

```bash
# 1. Di lokal
git pull
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Upload ulang file yang berubah via FTP
#    (biasanya: app/, config/, routes/, resources/views/, public/build/, vendor/)
```

> **Tips:** Hanya upload file yang berubah untuk mempercepat proses.
> Folder `vendor/` hanya perlu diupload ulang jika ada perubahan dependency.

---

## 10. Referensi

- [InfinityFree Official Site](https://infinityfree.com/)
- [InfinityFree Knowledge Base](https://forum.infinityfree.com/)
- [Laravel Deployment Documentation](https://laravel.com/docs/12.x/deployment)
