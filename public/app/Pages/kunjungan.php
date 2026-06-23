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

// Dummy data
$kunjungan = [
    [
        'id' => 'REK-001', 'tanggal' => '24/10/2024', 
        'pasien' => 'Bambang Kusuma', 'pasien_id' => 'PAS-9082',
        'petugas' => 'Ns. Rina', 'keluhan' => 'Pusing hebat & Hipertensi',
        'suhu' => '38.5°C', 'suhu_color' => '#dc2626',
        'catatan' => 'Pasien lemas, tensi melonjak tiba-tiba',
        'rekomendasi' => 'Segera rujuk ke RSUD terdekat',
        'status' => 'DIBATALKAN', 'status_color' => '#fef2f2', 'status_text' => '#dc2626'
    ],
    [
        'id' => 'REK-002', 'tanggal' => '24/10/2024', 
        'pasien' => 'Aditya Juniatama', 'pasien_id' => 'PAS-1021',
        'petugas' => 'Ns. Budi', 'keluhan' => 'Kontrol rutin bulanan',
        'suhu' => '36.6°C', 'suhu_color' => '#16a34a',
        'catatan' => 'Kondisi stabil, obat rutin sudah diminum',
        'rekomendasi' => 'Lanjutkan terapi air putih',
        'status' => 'SELESAI', 'status_color' => '#f0fdf4', 'status_text' => '#16a34a'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kunjungan — SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/global.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/table.css" rel="stylesheet">
    <style>
        .search-filter-bar {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .search-filter-bar input {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px 8px 36px;
            font-size: 13.5px;
            outline: none;
            flex: 1;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="%238E8E93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>') no-repeat 12px center;
            background-color: #FAFBFC;
            transition: all 0.2s;
        }
        .search-filter-bar input:focus {
            border-color: var(--primary);
            background-color: white;
        }
        .form-select {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 32px 8px 12px;
            font-size: 13.5px;
            color: var(--text-dark);
            width: auto;
            min-width: 150px;
        }
        .filter-btn {
            background: white;
            border: 1px solid var(--border);
            color: var(--text-dark);
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sv-table-wrap {
            border-radius: 12px;
            border: 1px solid var(--border);
            background: white;
            overflow: hidden;
        }
        .table th {
            font-size: 10px;
            font-weight: 700;
            color: #8E8E93;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table td {
            padding: 16px;
            font-size: 13px;
            color: var(--text-dark);
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .btn-detail {
            border: 1px solid var(--primary);
            color: var(--primary);
            background: white;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-detail:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-top: 1px solid var(--border);
            background: white;
        }
        .pagination-text {
            font-size: 12px;
            color: #8E8E93;
        }
        .pagination-nav {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        .page-item {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border-radius: 6px;
            color: #636366;
            cursor: pointer;
            text-decoration: none;
        }
        .page-item.active {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }
        .page-item:hover:not(.active) {
            background: #F2F4F7;
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
        <div class="sv-content" style="padding: 2rem; max-width: 1200px; margin: 0 auto; width: 100%;">
            <!-- Breadcrumb -->
            <div style="font-size: 12px; color: #8E8E93; margin-bottom: 0.5rem;">
                Kunjungan <span style="margin: 0 4px;">&gt;</span> <span style="color: var(--text-dark); font-weight: 600;">Data Kunjungan</span>
            </div>

            <!-- Page Header -->
            <div style="margin-bottom: 2rem;">
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin: 0 0 0.25rem 0;">Data Kunjungan Home Care</h1>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0;">Kelola dan pantau jadwal kunjungan petugas medis ke rumah pasien secara real-time.</p>
            </div>
            
            <div class="sv-table-wrap">
                <!-- Search & Filter -->
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: white; gap: 1rem;">
                    <input type="text" placeholder="Cari nama petugas atau pasien..." style="border: 1px solid var(--border); border-radius: 8px; padding: 0.5rem 1rem 0.5rem 2.5rem; font-size: 0.875rem; flex: 1; background: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%238E8E93\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><circle cx=\"11\" cy=\"11\" r=\"8\"></circle><line x1=\"21\" y1=\"21\" x2=\"16.65\" y2=\"16.65\"></line></svg>') no-repeat 12px center; outline: none; background-color: #FAFBFC;">
                    <select class="form-select" style="width: auto; min-width: 150px; font-size: 0.875rem;">
                        <option>Semua Tanggal</option>
                    </select>
                    <select class="form-select" style="width: auto; min-width: 150px; font-size: 0.875rem;">
                        <option>Semua Status</option>
                    </select>
                    <button class="filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                        Filter Lanjutan
                    </button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 100px;">ID<br>KUNJUNGAN</th>
                                <th>TANGGAL</th>
                                <th>NAMA<br>PASIEN</th>
                                <th>PETUGAS</th>
                                <th>KELUHAN<br>UTAMA</th>
                                <th>SUHU</th>
                                <th>CATATAN</th>
                                <th>REKOMENDASI</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kunjungan as $k): ?>
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: var(--primary);"><?= $k['id'] ?></td>
                                <td><?= $k['tanggal'] ?></td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-dark);"><?= $k['pasien'] ?></div>
                                    <div style="font-size: 11px; color: #8E8E93;"><?= $k['pasien_id'] ?></div>
                                </td>
                                <td><?= $k['petugas'] ?></td>
                                <td style="max-width: 120px;"><?= $k['keluhan'] ?></td>
                                <td style="font-weight: 700; color: <?= $k['suhu_color'] ?>;"><?= $k['suhu'] ?></td>
                                <td style="max-width: 150px; font-size: 12px;"><?= $k['catatan'] ?></td>
                                <td style="max-width: 150px; font-size: 12px;"><?= $k['rekomendasi'] ?></td>
                                <td>
                                    <span class="status-badge" style="background-color: <?= $k['status_color'] ?>; color: <?= $k['status_text'] ?>;">
                                        <?= $k['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn-detail">Detail</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination-text">Menampilkan 1–2 dari 18 data kunjungan</div>
                    <div class="pagination-nav">
                        <span class="page-item" style="color: #C7C7CC;">&lt;</span>
                        <a href="#" class="page-item active">1</a>
                        <a href="#" class="page-item">2</a>
                        <a href="#" class="page-item">3</a>
                        <span class="page-item" style="pointer-events: none;">...</span>
                        <a href="#" class="page-item">9</a>
                        <a href="#" class="page-item">&gt;</a>
                    </div>
                </div>
            </div>
            
            <!-- Global Footer text -->
            <div style="text-align: center; margin-top: 3rem; font-size: 0.625rem; font-weight: 700; color: #8E8E93; letter-spacing: 1px;">
                (DATA DUMMY / SIMULASI)
            </div>
        </div>
    </div>
</div>
</body>
</html>
