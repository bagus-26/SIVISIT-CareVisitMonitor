<?php
require_once '../config.php';

if (!isset($_SESSION['api_token'])) {
    header("Location: login.php");
    exit;
}

$user        = $_SESSION['user'] ?? [];
$userName    = htmlspecialchars($user['name']  ?? 'Dr. Admin (Pusat)');
$userRole    = htmlspecialchars($user['role']  ?? 'Kepala Klinik Sentra');
$userInitial = strtoupper(substr($userName, 0, 1));

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem — SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/global.css" rel="stylesheet">
    <style>
        .settings-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .settings-section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }
        .form-label {
            font-size: 13.5px;
            font-weight: 600;
            color: #636366;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
            background-color: #FAFBFC;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        /* Custom Toggle Switch */
        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }
        .setting-row:last-of-type {
            border-bottom: none;
        }
        .setting-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input:checked + .slider {
            background-color: #10b981;
        }
        input:checked + .slider:before {
            transform: translateX(24px);
        }

        .btn-outline {
            background: white;
            border: 1px solid var(--border);
            color: var(--text-dark);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="sv-layout">

    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <!-- Topbar -->
        <div class="sv-topbar" style="justify-content: space-between; padding: 1rem 2rem; border-bottom: 1px solid var(--border); background: white;">
            <div style="position: relative; width: 400px;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #8E8E93;">🔍</span>
                <input
                    type="text"
                    placeholder="Cari data pasien, rekam medis, atau log..."
                    style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 0.5rem 1rem 0.5rem 2.5rem; font-size: 0.875rem; outline: none; background-color: #FAFBFC;"
                >
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="position: relative; cursor: pointer;">
                    <span style="font-size: 1.25rem;">🔔</span>
                    <span style="position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background-color: var(--danger); border-radius: 50%;"></span>
                </div>
                <div style="cursor: pointer;">
                    <span style="font-size: 1.25rem;">❔</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 1rem; border-left: 1px solid var(--border);">
                    <div style="text-align: right;">
                        <div style="font-weight: 700; font-size: 0.875rem; color: var(--text-dark);"><?= $userName ?></div>
                        <div style="color: var(--text-muted); font-size: 0.75rem;"><?= $userRole ?></div>
                    </div>
                    <div style="width: 36px; height: 36px; border-radius: 50%; background-image: url('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/1.png'); background-size: cover; background-color: var(--primary-light);"></div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="sv-content" style="padding: 2rem; max-width: 900px; margin: 0 auto; width: 100%;">
            
            <!-- Page Header -->
            <div style="margin-bottom: 2rem;">
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin: 0 0 0.25rem 0;">Pengaturan Sistem</h1>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0;">Konfigurasi profil klinik, akun administrator, dan hak akses petugas lapangan.</p>
            </div>
            
            <div class="settings-card">
                <!-- Profil Klinik -->
                <div class="settings-section-title">Profil Klinik</div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-6">
                        <label class="form-label">Nama Klinik / Instansi</label>
                        <input type="text" class="form-control" value="Klinik Sentra Utama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Telepon Kontak</label>
                        <input type="text" class="form-control" value="0341-555123">
                    </div>
                </div>
                <div class="row" style="margin-bottom: 40px;">
                    <div class="col-12">
                        <label class="form-label">Alamat Operasional</label>
                        <textarea class="form-control" rows="3">Jl. Raya Keperawatan No. 45, Malang</textarea>
                    </div>
                </div>

                <!-- Pengaturan Hak Akses -->
                <div class="settings-section-title">Pengaturan Hak Akses</div>
                
                <div class="setting-row">
                    <div class="setting-label">Izinkan Perawat Mengubah Jadwal Kunjungan secara Mandiri</div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-label">Wajibkan Unggah Foto Validasi Lokasi via GPS saat Kunjungan Selesai</div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="setting-row" style="margin-bottom: 40px;">
                    <div class="setting-label">Sinkronisasi Otomatis Data Rekam Medis ke Server Pusat</div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; justify-content: flex-end; gap: 16px; border-top: 1px solid var(--border); padding-top: 24px;">
                    <button class="btn-outline">Batal</button>
                    <button class="btn-primary">Simpan Perubahan</button>
                </div>
            </div>
            
            <!-- Global Footer text -->
            <div style="text-align: center; margin-top: 3rem; font-size: 0.625rem; font-weight: 700; color: #8E8E93; letter-spacing: 1px;">
                [DATA DUMMY / SIMULASI]
            </div>
        </div>
    </div>
</div>
</body>
</html>
