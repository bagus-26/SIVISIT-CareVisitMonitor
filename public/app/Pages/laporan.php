<?php
require_once '../config.php';
require_once 'components/ui-config.php';

if (!isset($_SESSION['api_token'])) {
    header('Location: login.php');
    exit;
}

$laporan = [
    ['kode' => 'LAP-2024-10', 'periode' => 'Oktober 2024', 'kunjungan' => '240 Kunjungan', 'selesai' => '225 Selesai', 'petugas' => '12 Perawat', 'status' => 'Siap Unduh'],
    ['kode' => 'LAP-2024-09', 'periode' => 'September 2024', 'kunjungan' => '310 Kunjungan', 'selesai' => '290 Selesai', 'petugas' => '14 Perawat', 'status' => 'Siap Unduh'],
];

$searchPlaceholder = 'Cari data pasien, rekam medis, atau log...';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan — <?= SV_BRAND_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="table.css" rel="stylesheet">
    <link href="admin-page.css" rel="stylesheet">
    <style>
        .sv-stat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-bottom:2rem; }
        @media (max-width:992px) { .sv-stat-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="sv-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <?php require_once 'components/topbar.php'; ?>

        <div class="sv-content">
            <div class="sv-page-header sv-animate-in">
                <div>
                    <h1>Laporan</h1>
                    <p>Pantau ringkasan performa kunjungan, dan efisiensi petugas.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="sv-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Rentang Waktu
                    </button>
                    <button type="button" class="btn btn-primary" style="font-weight:600;display:inline-flex;align-items:center;gap:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Ekspor Laporan
                    </button>
                </div>
            </div>

            <div class="sv-stat-grid sv-animate-in">
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#f0fdf4;color:#16a34a;">📈</div>
                        <span class="sv-status-pill selesai">↑ +12%</span>
                    </div>
                    <div class="stat-label-sm">Total Kunjungan Bulan Ini</div>
                    <div class="stat-num">1,240</div>
                </div>
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#eff6ff;color:#3b82f6;">👥</div>
                        <span class="sv-status-pill" style="background:#eff6ff;color:#3b82f6;">STABIL</span>
                    </div>
                    <div class="stat-label-sm">Total Pasien Binaan Aktif</div>
                    <div class="stat-num">348 <span style="font-size:16px;font-weight:600;color:#8E8E93;">Pasien</span></div>
                </div>
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#fff7ed;color:#f97316;">✓</div>
                        <span class="sv-status-pill kontrol">Target 95%</span>
                    </div>
                    <div class="stat-label-sm">Persentase Kunjungan Selesai</div>
                    <div class="stat-num">94.2%</div>
                </div>
            </div>

            <div class="sv-table-wrap sv-animate-in">
                <div class="sv-section-header">
                    <h5>Riwayat Laporan Bulanan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Kode Laporan</th>
                                <th>Periode</th>
                                <th>Total Kunjungan</th>
                                <th>Kunjungan Selesai</th>
                                <th>Petugas Aktif</th>
                                <th>Status Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporan as $l): ?>
                            <tr>
                                <td style="font-weight:600;color:#636366;"><?= $l['kode'] ?></td>
                                <td><?= $l['periode'] ?></td>
                                <td style="color:#636366;"><?= $l['kunjungan'] ?></td>
                                <td style="color:#636366;"><?= $l['selesai'] ?></td>
                                <td style="color:#636366;"><?= $l['petugas'] ?></td>
                                <td><span class="sv-status-pill siap"><?= $l['status'] ?></span></td>
                                <td>
                                    <a href="#" class="sv-code-link" style="display:inline-flex;align-items:center;gap:6px;font-size:13.5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Unduh PDF
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="sv-pagination-bar">
                    <div class="sv-pagination-text">Menampilkan 3 dari 24 laporan bulanan</div>
                    <div class="sv-pagination-nav">
                        <span class="sv-page-btn disabled">&lt;</span>
                        <a href="#" class="sv-page-btn active">1</a>
                        <a href="#" class="sv-page-btn">2</a>
                        <a href="#" class="sv-page-btn">3</a>
                        <a href="#" class="sv-page-btn">&gt;</a>
                    </div>
                </div>
            </div>

            <div class="sv-admin-dummy-footer">[Data Dummy / Simulasi] © 2026 <?= SV_BRAND_NAME ?></div>
        </div>
    </div>
</div>
</body>
</html>
