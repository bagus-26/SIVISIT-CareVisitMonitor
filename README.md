# SIVISIT — CareVisit Monitor

Sistem monitoring pasien binaan dan kunjungan home care. Aplikasi web untuk mencatat kunjungan, kondisi pasien, dan rekomendasi tindak lanjut.

## Fitur

### Backend (Laravel 12)
- Login admin/petugas (session based)
- Dashboard jumlah pasien, monitoring hari ini, pasien perlu kontrol
- CRUD pasien binaan
- CRUD monitoring kesehatan
- Pencarian pasien (kode/NIK)
- Validasi tekanan darah (format 120/80, range 60-250/40-150)
- Validasi suhu tubuh (35.0-42.0°C)
- API JSON untuk frontend
- Filter status monitoring

### Frontend (PHP Native + Bootstrap 5)
- Halaman landing (informasi layanan home care)
- Login admin/petugas
- Dashboard monitoring
- CRUD pasien & monitoring
- Pencarian pasien via kode/NIK
- Riwayat monitoring per pasien
- Cetak ringkasan monitoring
- Jadwal kunjungan
- Responsive (mobile/desktop)

## Struktur Project

```
sivisit_CareVisitMonitor/
├── public/
│   ├── index.php           ← Laravel entry point
│   ├── app/                ← Frontend PHP native
│   │   ├── index.php       ← Landing page
│   │   ├── config.php      ← API config
│   │   ├── pages/          ← CSS files
│   │   └── Pages/          ← PHP pages
│   │       ├── login.php
│   │       ├── dashboard.php
│   │       ├── components/
│   │       └── ...
│   └── build/              ← Vite compiled assets
├── app/                    ← Laravel app
├── routes/api.php          ← API endpoints
└── ...
```

**Akses Frontend:** `http://domain.com/app/`
**Akses API:** `http://domain.com/api/`

## API Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/patients` | Semua pasien |
| GET | `/api/pasien` | Alias bahasa Indonesia |
| GET | `/api/patients/{id}` | Detail pasien |
| GET | `/api/patients/{id}/monitoring` | Riwayat monitoring pasien |
| POST | `/api/patients` | Tambah pasien |
| PUT/PATCH | `/api/patients/{id}` | Update pasien |
| DELETE | `/api/patients/{id}` | Hapus pasien |
| GET | `/api/monitorings` | Semua monitoring |
| POST | `/api/monitorings` | Tambah monitoring |
| GET | `/api/monitorings/{id}` | Detail monitoring |
| GET | `/api/monitoring/status/{status}` | Filter by status |
| DELETE | `/api/monitorings/{id}` | Hapus monitoring |
| POST | `/api/login` | Login API |
| POST | `/api/register` | Register API |

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | `admincarevisit@2026.dev` | `password` |
| Petugas | `test@example.com` | `password` |

## Panduan Deploy

### 1. Persiapan Server
```bash
# Clone project
git clone <repo-url>
cd sivisit_CareVisitMonitor

# Install dependencies
composer install --no-dev
npm install && npm run build
```

### 2. Konfigurasi
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env`: isi `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

### 3. Database
```bash
php artisan migrate --seed
php artisan storage:link
```

### 4. Server
Arahkan document root ke `public/` folder.

### 5. Verifikasi
- `http://domain.com/app/` — Landing page
- `http://domain.com/app/Pages/login.php` — Login
- `http://domain.com/api/patients` — API test

## Teknologi

- **Backend:** Laravel 12, PHP 8.2+
- **Database:** MySQL / MariaDB
- **Frontend:** PHP Native, Bootstrap 5.3.3
- **API Auth:** Laravel Sanctum
- **Build:** Vite 6 + Tailwind CSS 4

## Catatan

- Seluruh data bersifat dummy/simulasi
- Aplikasi tidak memberikan diagnosis medis
- Rekomendasi hanya bersifat administratif
