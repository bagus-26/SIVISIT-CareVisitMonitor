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
$petugas = [
    ['kode' => 'CVP-001', 'nama' => 'Aditya Juniatama', 'hp' => '3578********0001', 'jadwal' => '15/03/1990', 'spesialis' => 'Hipertensi'],
    ['kode' => 'CVP-002', 'nama' => 'Siti Hawa',        'hp' => '3578********0002', 'jadwal' => '07/08/1962', 'spesialis' => 'Lansia'],
    ['kode' => 'CVP-003', 'nama' => 'Bambang Kusuma',   'hp' => '3578********0003', 'jadwal' => '22/11/1976', 'spesialis' => 'Diabetes'],
    ['kode' => 'CVP-004', 'nama' => 'Rina Nurhayati',   'hp' => '3578********0004', 'jadwal' => '05/06/1995', 'spesialis' => 'Pasca Rawat'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas — SIVISIT</title>
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
            justify-content: space-between;
            align-items: center;
        }
        .search-filter-bar input {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px 8px 36px;
            font-size: 13.5px;
            outline: none;
            width: 300px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="%238E8E93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>') no-repeat 12px center;
            background-color: #FAFBFC;
            transition: all 0.2s;
        }
        .search-filter-bar input:focus {
            border-color: var(--primary);
            background-color: white;
        }
        .filter-btn {
            background: white;
            border: 1px solid var(--border);
            color: var(--primary);
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
            font-size: 11px;
            font-weight: 700;
            color: #8E8E93;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }
        .table td {
            padding: 16px;
            font-size: 13.5px;
            color: var(--text-dark);
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }
        .table tr:last-child td {
            border-bottom: none;
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
                    placeholder="Cari nama petugas, kode, atau jadwal..."
                    style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 0.5rem 1rem 0.5rem 2.5rem; font-size: 0.875rem; outline: none;"
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
            <!-- Page Header -->
            <div style="margin-bottom: 1.5rem;">
                <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin: 0 0 0.25rem 0;">Data Petugas</h1>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0;">Kelola seluruh data petugas home care yang terdaftar di sistem.</p>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <button class="btn btn-primary" style="font-weight: 600; padding: 0.625rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 8px;">
                    👤+ Tambah Petugas Baru
                </button>
            </div>

            <div class="sv-table-wrap">
                <!-- Search & Filter -->
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: white;">
                    <input type="text" placeholder="Cari Kode atau Nama..." style="border: 1px solid var(--border); border-radius: 8px; padding: 0.5rem 1rem 0.5rem 2.5rem; font-size: 0.875rem; width: 300px; background: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%238E8E93\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><circle cx=\"11\" cy=\"11\" r=\"8\"></circle><line x1=\"21\" y1=\"21\" x2=\"16.65\" y2=\"16.65\"></line></svg>') no-repeat 12px center; outline: none; background-color: #FAFBFC;">
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
                                <th style="text-align: center; width: 120px;">KODE PETUGAS</th>
                                <th>NAMA PETUGAS</th>
                                <th>NOMOR HANDPHONE</th>
                                <th>JADWAL SHIFT</th>
                                <th>SPESIALIS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($petugas as $p): ?>
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: var(--primary);"><?= $p['kode'] ?></td>
                                <td style="font-weight: 600; color: var(--text-dark);"><?= $p['nama'] ?></td>
                                <td style="color: #636366;"><?= $p['hp'] ?></td>
                                <td style="color: #636366;"><?= $p['jadwal'] ?></td>
                                <td style="color: #636366;"><?= $p['spesialis'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination-text">Menampilkan 1–4 dari 24 data</div>
                    <div class="pagination-nav">
                        <span class="page-item" style="color: #C7C7CC;">&lt;</span>
                        <a href="#" class="page-item active">1</a>
                        <a href="#" class="page-item">2</a>
                        <a href="#" class="page-item">3</a>
                        <span class="page-item" style="pointer-events: none;">...</span>
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
