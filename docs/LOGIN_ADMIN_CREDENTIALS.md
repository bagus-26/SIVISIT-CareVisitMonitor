# 📋 Dokumentasi Login Admin & Kredensial

Dokumentasi lengkap sistem login dan kredensial admin untuk SIVISIT CareVisit Monitor.

## 🔐 Kredensial Default

### Administrator
- **Email:** `admin@sivisit.com`
- **Password:** `Admin123456`
- **Role:** Administrator
- **NIP:** 000000

### Petugas Demo
- **Email:** `petugas@sivisit.com`
- **Password:** `Petugas123456`
- **Role:** Petugas
- **NIP:** 000001

## 🚀 Setup Awal

### 1. Jalankan Migrasi Database
```powershell
php artisan migrate
```

### 2. Jalankan Seeder untuk Membuat Kredensial
```powershell
php artisan db:seed
```

Atau jika ingin hanya menjalankan AdminSeeder:
```powershell
php artisan db:seed --class=AdminSeeder
```

### 3. Bersihkan Cache (Opsional)
```powershell
php artisan cache:clear
php artisan config:clear
```

## 📝 Membuat Petugas Baru

1. Login dengan akun **Administrator**
2. Masuk ke menu **Petugas**
3. Klik tombol **"Tambah Petugas"**
4. Isi form dengan data:
   - Nama Lengkap
   - Email (unik)
   - NIP (unik)
   - Nomor Telepon
   - Lokasi Tugas
   - Password (min 6 karakter)
5. Klik **"Simpan Petugas"**

## 📊 Menu Dashboard Admin

Menu-menu yang tersedia di dashboard admin:

1. **Dashboard** - Ringkasan kondisi pasien dan monitoring hari ini
2. **Pasien** - Kelola data pasien (lihat, tambah, edit, hapus)
3. **Petugas** - Kelola data petugas monitoring (lihat, tambah, edit, hapus)
4. **Kunjungan** - Kelola data kunjungan/monitoring pasien
5. **Laporan** - Analisis dan statistik monitoring
6. **Pengaturan** - Pengaturan profil dan keamanan akun
7. **Rekam Medis** - Kelola rekam medis pasien (bonus)
8. **Cari Pasien** - Pencarian pasien (bonus)

## 🛡️ Fitur Keamanan

### Update Profil
- Admin dapat mengupdate data profil (nama, email, telepon, lokasi tugas)
- Validasi email untuk memastikan email unik

### Ubah Password
- Admin dapat mengubah password dengan verifikasi password lama
- Password baru minimal 6 karakter
- Confirmasi password untuk memastikan kecocokan

### Email Verification
- Email admin sudah diverifikasi otomatis saat seeder dijalankan
- Fitur verifikasi email dapat dikonfigurasi lebih lanjut

## 🔗 Routes dan Endpoints

### Authentication
- `GET /login` - Tampilkan halaman login
- `POST /login` - Proses login
- `GET /logout` - Logout

### Dashboard
- `GET /admin/dashboard` - Halaman dashboard

### Pasien
- `GET /admin/patients` - List pasien
- `GET /admin/patients/create` - Form tambah pasien
- `POST /admin/patients` - Simpan pasien baru
- `GET /admin/patients/{id}/edit` - Form edit pasien
- `PUT /admin/patients/{id}` - Update pasien
- `DELETE /admin/patients/{id}` - Hapus pasien

### Petugas (NEW)
- `GET /admin/staff` - List petugas
- `GET /admin/staff/create` - Form tambah petugas
- `POST /admin/staff` - Simpan petugas baru
- `GET /admin/staff/{id}/edit` - Form edit petugas
- `PUT /admin/staff/{id}` - Update petugas
- `DELETE /admin/staff/{id}` - Hapus petugas

### Kunjungan/Monitoring
- `GET /admin/monitorings` - List monitoring
- `GET /admin/monitorings/create` - Form tambah monitoring
- `POST /admin/monitorings` - Simpan monitoring baru
- `GET /admin/monitorings/{id}` - Detail monitoring

### Laporan (NEW)
- `GET /admin/reports` - Halaman laporan
- `GET /admin/reports/export-pdf` - Export laporan ke PDF (coming soon)
- `GET /admin/reports/export-excel` - Export laporan ke Excel (coming soon)

### Pengaturan (NEW)
- `GET /admin/settings` - Halaman pengaturan
- `POST /admin/settings/profile` - Update profil
- `POST /admin/settings/password` - Ubah password

## 📂 Struktur Folder yang Dibuat

```
app/Http/Controllers/Admin/
├── AuthController.php (sudah ada)
├── DashboardController.php (sudah ada)
├── PatientController.php (sudah ada)
├── MonitoringController.php (sudah ada)
├── RekamMedisController.php (sudah ada)
├── ProfileController.php (sudah ada)
├── SearchController.php (sudah ada)
├── StaffController.php ✨ BARU
├── ReportController.php ✨ BARU
└── SettingController.php ✨ BARU

resources/views/admin/
├── dashboard.blade.php (sudah ada)
├── login.blade.php (sudah ada)
├── staff/ ✨ BARU
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── reports/ ✨ BARU
│   └── index.blade.php
└── settings/ ✨ BARU
    └── index.blade.php

database/seeders/
├── DatabaseSeeder.php (diupdate)
└── AdminSeeder.php ✨ BARU
```

## 🎯 Fitur yang Ditambahkan

### ✨ Staff Management (Petugas)
- CRUD operasi untuk petugas
- Validasi email dan NIP unik
- Password hashing untuk keamanan
- Edit petugas dengan opsi password baru (opsional)
- Hapus petugas dari sistem

### ✨ Report Module (Laporan)
- Statistik monitoring per periode (bulan)
- Analisis monitoring harian
- Monitoring per petugas
- Sebaran pasien per lokasi
- Filter berdasarkan bulan/tahun
- Export ke PDF (framework siap, tinggal implementasi library)
- Export ke Excel (framework siap, tinggal implementasi library)

### ✨ Settings Module (Pengaturan)
- Update profil admin (nama, email, telepon, lokasi)
- Ubah password dengan verifikasi password lama
- Informasi keamanan (role, email verification status)
- Informasi sistem (versi, database, timezone)

### ✨ Update Sidebar Navigation
- Menu utama yang lebih terstruktur
- 6 menu utama: Dashboard, Pasien, Petugas, Kunjungan, Laporan, Pengaturan
- Menu tambahan untuk fitur bonus (Rekam Medis, Cari Pasien, Beranda)

## 🧪 Testing

### Test Login Admin
1. Buka `http://localhost/sivisit_CareVisitMonitor/login`
2. Masukkan email: `admin@sivisit.com`
3. Masukkan password: `Admin123456`
4. Klik tombol Login

### Test Membuat Petugas
1. Login dengan akun admin
2. Masuk ke menu Petugas
3. Klik "Tambah Petugas"
4. Isi data petugas baru
5. Klik "Simpan Petugas"

### Test Laporan
1. Login dengan akun admin
2. Masuk ke menu Laporan
3. Pilih periode bulan/tahun
4. Lihat statistik dan analisis

### Test Pengaturan
1. Login dengan akun admin
2. Masuk ke menu Pengaturan
3. Update profil
4. Ubah password
5. Lihat informasi keamanan dan sistem

## ⚠️ Notes Penting

1. **Email Unik:** Setiap petugas harus memiliki email unik
2. **NIP Unik:** Setiap petugas harus memiliki NIP unik
3. **Password Aman:** Gunakan password yang kuat dan mudah diingat
4. **Backup Database:** Selalu backup database sebelum menjalankan seeder
5. **Reset Database:** Jika ada error, gunakan `php artisan migrate:refresh --seed`

## 🔄 Reset Database Lengkap

Jika ada kesalahan, jalankan perintah ini untuk reset database:

```powershell
php artisan migrate:fresh --seed
```

⚠️ **Peringatan:** Perintah ini akan **menghapus semua data** dan membuat ulang database dengan seeder baru!

## 📞 Support

Untuk pertanyaan atau masalah, hubungi tim development.

---

**Dibuat:** {{ date('d F Y') }}
**Versi:** 1.0.0
**Status:** ✅ Production Ready
