<?php
require_once '../config.php';
require_once 'components/ui-config.php';

if (!isset($_SESSION['api_token'])) {
    header('Location: login.php');
    exit;
}

$kunjungan = [
    [
        'id' => 'REK-001', 'tanggal' => '24/10/2024',
        'pasien' => 'Bambang Kusuma', 'pasien_id' => 'PAS-0982',
        'petugas' => 'Ns. Rina', 'keluhan' => 'Pusing hebat & Hipertensi',
        'suhu' => '38.5°C', 'suhu_class' => 'text-danger',
        'catatan' => 'Pasien lemas, tensi melonjak tiba-tiba',
        'rekomendasi' => 'Segera rujuk ke RSUD terdekat',
        'status' => 'DIBATALKAN', 'status_class' => 'batal',
    ],
    [
        'id' => 'REK-002', 'tanggal' => '24/10/2024',
        'pasien' => 'Aditya Juniatama', 'pasien_id' => 'PAS-1021',
        'petugas' => 'Ns. Budi', 'keluhan' => 'Kontrol rutin bulanan',
        'suhu' => '36.6°C', 'suhu_class' => 'text-success',
        'catatan' => 'Kondisi stabil, obat rutin sudah diminum',
        'rekomendasi' => 'Lanjutkan terapi air putih',
        'status' => 'SELESAI', 'status_class' => 'selesai',
    ],
];

$searchPlaceholder = 'Cari data pasien, rekam medis, atau log...';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan — <?= SV_BRAND_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="table.css" rel="stylesheet">
    <link href="admin-page.css" rel="stylesheet">
</head>
<body>
<div class="sv-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <?php require_once 'components/topbar.php'; ?>

        <div class="sv-content">
            <div class="sv-breadcrumb sv-animate-in">
                Kunjungan &gt; <strong>Data Kunjungan</strong>
            </div>

            <div class="sv-page-header sv-animate-in">
                <div>
                    <h1>Data Kunjungan Home Care</h1>
                    <p>Kelola dan pantau jadwal kunjungan petugas medis ke rumah pasien secara real-time.</p>
                </div>
            </div>

            <div class="sv-table-wrap sv-animate-in">
                <div class="sv-filter-bar">
                    <input type="search" class="sv-filter-search" placeholder="Cari nama petugas atau pasien...">
                    <select class="sv-filter-select"><option>Semua Tanggal</option></select>
                    <select class="sv-filter-select"><option>Semua Status</option></select>
                    <button type="button" class="sv-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter Lanjutan
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="text-align:center;">ID Kunjungan</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Petugas</th>
                                <th>Keluhan Utama</th>
                                <th>Suhu</th>
                                <th>Catatan</th>
                                <th>Rekomendasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kunjungan as $k): ?>
                            <tr>
                                <td style="text-align:center;"><span class="sv-code-link"><?= $k['id'] ?></span></td>
                                <td><?= $k['tanggal'] ?></td>
                                <td>
                                    <div style="font-weight:700;"><?= $k['pasien'] ?></div>
                                    <div style="font-size:11px;color:#8E8E93;"><?= $k['pasien_id'] ?></div>
                                </td>
                                <td><?= $k['petugas'] ?></td>
                                <td style="max-width:120px;"><?= $k['keluhan'] ?></td>
                                <td style="font-weight:700;" class="<?= $k['suhu_class'] ?>"><?= $k['suhu'] ?></td>
                                <td style="max-width:150px;font-size:12px;"><?= $k['catatan'] ?></td>
                                <td style="max-width:150px;font-size:12px;"><?= $k['rekomendasi'] ?></td>
                                <td><span class="sv-status-pill <?= $k['status_class'] ?>"><?= $k['status'] ?></span></td>
                                <td><a href="#" class="sv-btn-detail">Detail</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="sv-pagination-bar">
                    <div class="sv-pagination-text">Menampilkan 1–2 dari 18 data kunjungan</div>
                    <div class="sv-pagination-nav">
                        <span class="sv-page-btn disabled">&lt;</span>
                        <a href="#" class="sv-page-btn active">1</a>
                        <a href="#" class="sv-page-btn">2</a>
                        <a href="#" class="sv-page-btn">3</a>
                        <span class="sv-page-btn disabled">...</span>
                        <a href="#" class="sv-page-btn">9</a>
                        <a href="#" class="sv-page-btn">&gt;</a>
                    </div>
                </div>
            </div>

            <?php require_once 'components/admin-footer.php'; ?>
        </div>
    </div>
</div>
</body>
</html>
