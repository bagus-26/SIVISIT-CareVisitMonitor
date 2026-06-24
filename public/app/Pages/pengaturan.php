<?php
require_once '../config.php';
require_once 'components/ui-config.php';

if (!isset($_SESSION['api_token'])) {
    header('Location: login.php');
    exit;
}

$searchPlaceholder = 'Cari data pasien, rekam medis, atau log...';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem — <?= SV_BRAND_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="admin-page.css" rel="stylesheet">
    <style>
        .settings-card {
            background: #fff;
            border: 1px solid var(--sv-border);
            border-radius: 12px;
            padding: 32px;
            box-shadow: var(--sv-shadow-sm);
        }
        .settings-section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--sv-text-main);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--sv-border);
        }
        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--sv-border);
            gap: 16px;
        }
        .setting-row:last-of-type { border-bottom: none; }
        .setting-label { font-size: 14px; font-weight: 500; color: var(--sv-text-main); }
        .toggle-switch { position: relative; display: inline-block; width: 50px; height: 26px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-switch .slider {
            position: absolute; cursor: pointer; inset: 0;
            background: #e5e7eb; transition: .4s; border-radius: 34px;
        }
        .toggle-switch .slider:before {
            position: absolute; content: ""; height: 20px; width: 20px;
            left: 3px; bottom: 3px; background: #fff; transition: .4s;
            border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .toggle-switch input:checked + .slider { background: #10b981; }
        .toggle-switch input:checked + .slider:before { transform: translateX(24px); }
    </style>
</head>
<body>
<div class="sv-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <?php require_once 'components/topbar.php'; ?>

        <div class="sv-content" style="max-width:900px;">
            <div class="sv-page-header sv-animate-in">
                <div>
                    <h1>Pengaturan Sistem</h1>
                    <p>Konfigurasi profil klinik, akun administrator, dan hak akses petugas lapangan.</p>
                </div>
            </div>

            <div class="settings-card sv-animate-in">
                <div class="settings-section-title">Profil Klinik</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Klinik / Instansi</label>
                        <input type="text" class="form-control" value="Klinik Sentra Utama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon Kontak</label>
                        <input type="text" class="form-control" value="0341-555123">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Operasional</label>
                        <textarea class="form-control" rows="3">Jl. Raya Keperawatan No. 45, Malang</textarea>
                    </div>
                </div>

                <div class="settings-section-title">Pengaturan Hak Akses</div>
                <div class="setting-row">
                    <div class="setting-label">Izinkan Perawat Mengubah Jadwal Kunjungan secara Mandiri</div>
                    <label class="toggle-switch"><input type="checkbox" checked><span class="slider"></span></label>
                </div>
                <div class="setting-row">
                    <div class="setting-label">Wajibkan Unggah Foto Validasi Lokasi via GPS saat Kunjungan Selesai</div>
                    <label class="toggle-switch"><input type="checkbox" checked><span class="slider"></span></label>
                </div>
                <div class="setting-row mb-4">
                    <div class="setting-label">Sinkronisasi Otomatis Data Rekam Medis ke Server Pusat</div>
                    <label class="toggle-switch"><input type="checkbox"><span class="slider"></span></label>
                </div>

                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                    <button type="button" class="sv-filter-btn">Batal</button>
                    <button type="button" class="btn btn-primary" style="font-weight:600;padding:10px 24px;">Simpan Perubahan</button>
                </div>
            </div>

            <?php require_once 'components/admin-footer.php'; ?>
        </div>
    </div>
</div>
</body>
</html>
