# Care Visit Monitor 🩺

**Care Visit Monitor** adalah aplikasi berbasis web dengan arsitektur terpisah (*decoupled*) yang dirancang untuk memfasilitasi manajemen pasien binaan, pencatatan kunjungan *home care*, dan pemantauan riwayat kesehatan sederhana. 

Sistem ini dikembangkan khusus untuk memenuhi standar spesifikasi proyek Pemrograman Web secara komprehensif, mengintegrasikan antarmuka yang responsif dengan *backend* yang tangguh.

---

## 🛠️ Teknologi yang Digunakan

Proyek ini menerapkan pola komunikasi API (*Application Programming Interface*) antara *Client* dan *Server*:

* **Backend & API:** Laravel 12 (PHP 8.2+)
* **Database:** MySQL / MariaDB (`care_visit_monitor`)
* **Frontend:** PHP Native, HTML5, CSS3
* **UI/UX Framework:** Bootstrap 5, Phosphor Icons, SweetAlert2, Chart.js

---

## 🚀 Fitur Utama

Sistem ini membagi fungsionalitas berdasarkan tiga aktor utama:

1. **Administrator (Akses via Web Laravel)**
   * *Dashboard* analitik kunjungan pasien harian.
   * Manajemen data Pasien Binaan (CRUD).
   * Manajemen hak akses Petugas Kesehatan (CRUD).
2. **Petugas Kesehatan (Akses via Frontend PHP Native)**
   * Pencatatan hasil *monitoring* (Tekanan Darah, Suhu Tubuh, Keluhan).
   * Validasi klinis sederhana pada *input* metrik kesehatan.
   * Meninjau status riwayat kunjungan pasien sebelumnya.
3. **Pasien / Keluarga (Akses via Frontend PHP Native)**
   * Pencarian riwayat kesehatan mandiri menggunakan NIK Fiktif / Kode Pasien.
   * Cetak ringkasan *monitoring* kunjungan dalam format sederhana.

4. **tunggu tanggal maennya lur**
---

## ⚙️ Panduan Instalasi & Konfigurasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan sistem secara lokal di perangkat Anda (XAMPP/Laragon/Herd).

### 1. Setup Backend (Laravel)
1. *Clone* repositori ini dan masuk ke direktori *backend*:
   ```bash
   git clone  https://github.com/bagus-26/CareVisitMonitor-Kel-9.git
   cd CareVisitMonitor
