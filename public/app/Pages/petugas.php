<?php
require_once '../config.php';
require_once 'components/ui-config.php';

if (!isset($_SESSION['api_token'])) {
    header('Location: login.php');
    exit;
}

$petugas = [
    ['kode' => 'CVP-001', 'nama' => 'Aditya Juniatama', 'hp' => '3578********0001', 'jadwal' => '15/03/1990', 'spesialis' => 'Hipertensi'],
    ['kode' => 'CVP-002', 'nama' => 'Siti Hawa',        'hp' => '3578********0002', 'jadwal' => '07/08/1962', 'spesialis' => 'Lansia'],
    ['kode' => 'CVP-003', 'nama' => 'Bambang Kusuma',   'hp' => '3578********0003', 'jadwal' => '22/11/1976', 'spesialis' => 'Diabetes'],
    ['kode' => 'CVP-004', 'nama' => 'Rina Nurhayati',   'hp' => '3578********0004', 'jadwal' => '05/06/1995', 'spesialis' => 'Pasca Rawat'],
];

$searchPlaceholder = 'Cari nama petugas, kode, atau jadwal...';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas — <?= SV_BRAND_NAME ?></title>
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
            <div class="sv-page-header sv-animate-in">
                <div>
                    <h1>Data Petugas</h1>
                    <p>Kelola seluruh data petugas home care yang terdaftar di sistem.</p>
                </div>
            </div>

            <div class="mb-4 sv-animate-in">
                <button type="button" class="btn btn-primary" style="font-weight:600;padding:10px 20px;">
                    + Tambah Petugas Baru
                </button>
            </div>

            <div class="sv-table-wrap sv-animate-in">
                <div class="sv-filter-bar">
                    <input type="search" class="sv-filter-search" placeholder="Cari Kode atau Nama...">
                    <button type="button" class="sv-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter Lanjutan
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="text-align:center;width:120px;">Kode Petugas</th>
                                <th>Nama Petugas</th>
                                <th>Nomor Handphone</th>
                                <th>Jadwal Shift</th>
                                <th>Spesialis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($petugas as $p): ?>
                            <tr>
                                <td style="text-align:center;"><span class="sv-code-link"><?= $p['kode'] ?></span></td>
                                <td style="font-weight:600;"><?= $p['nama'] ?></td>
                                <td style="color:#636366;"><?= $p['hp'] ?></td>
                                <td style="color:#636366;"><?= $p['jadwal'] ?></td>
                                <td style="color:#636366;"><?= $p['spesialis'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="sv-pagination-bar">
                    <div class="sv-pagination-text">Menampilkan 1–4 dari 24 data</div>
                    <div class="sv-pagination-nav">
                        <span class="sv-page-btn disabled">&lt;</span>
                        <a href="#" class="sv-page-btn active">1</a>
                        <a href="#" class="sv-page-btn">2</a>
                        <a href="#" class="sv-page-btn">3</a>
                        <span class="sv-page-btn disabled">...</span>
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
