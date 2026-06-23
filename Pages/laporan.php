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
$laporan = [
    [
        'kode' => 'LAP-2024-10', 'periode' => 'Oktober 2024', 
        'kunjungan' => '240 Kunjungan', 'selesai' => '225 Selesai',
        'petugas' => '12 Perawat', 'status' => 'Siap Unduh'
    ],
    [
        'kode' => 'LAP-2024-09', 'periode' => 'September 2024', 
        'kunjungan' => '310 Kunjungan', 'selesai' => '290 Selesai',
        'petugas' => '14 Perawat', 'status' => 'Siap Unduh'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan — SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/global.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/table.css" rel="stylesheet">
    <style>
        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .stat-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .stat-title {
            font-size: 11px;
            font-weight: 700;
            color: #8E8E93;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1;
        }
        .stat-value span {
            font-size: 16px;
            font-weight: 600;
            color: #8E8E93;
        }
        .btn-outline {
            background: white;
            border: 1px solid var(--border);
            color: var(--text-dark);
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-filled {
            background: var(--primary);
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 16px;
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
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table td {
            padding: 24px;
            font-size: 13.5px;
            color: var(--text-dark);
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .status-badge {
            background-color: #f0fdf4;
            color: #16a34a;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .download-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13.5px;
        }
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
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
            
            <!-- Page Header -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark); margin: 0 0 0.25rem 0;">Laporan</h1>
                    <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0;">Pantau ringkasan performa kunjungan, dan efisiensi petugas.</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Rentang Waktu
                    </button>
                    <button class="btn-filled">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Ekspor Laporan
                    </button>
                </div>
            </div>
            
            <!-- Stats Row -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 2rem;">
                <!-- Stat 1 -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </div>
                        <div class="stat-badge" style="background: #f0fdf4; color: #16a34a;">↑ +12%</div>
                    </div>
                    <div>
                        <div class="stat-title">TOTAL KUNJUNGAN BULAN INI</div>
                        <div class="stat-value">1,240</div>
                    </div>
                </div>
                <!-- Stat 2 -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <div class="stat-badge" style="background: #eff6ff; color: #3b82f6;">STABIL</div>
                    </div>
                    <div>
                        <div class="stat-title">TOTAL PASIEN BINAAN AKTIF</div>
                        <div class="stat-value">348 <span>Pasien</span></div>
                    </div>
                </div>
                <!-- Stat 3 -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon" style="background: #fff7ed; color: #f97316;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div class="stat-badge" style="background: white; color: #f97316;">Target 95%</div>
                    </div>
                    <div>
                        <div class="stat-title">PERSENTASE KUNJUNGAN SELESAI</div>
                        <div class="stat-value">94.2%</div>
                    </div>
                </div>
            </div>

            <div class="sv-table-wrap">
                <!-- Header -->
                <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: white;">
                    <h2 style="font-size: 16px; font-weight: 700; margin: 0; color: var(--text-dark);">Riwayat Laporan Bulanan</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8E8E93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor: pointer;"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table mb-0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>KODE<br>LAPORAN</th>
                                <th>PERIODE</th>
                                <th>TOTAL<br>KUNJUNGAN</th>
                                <th>KUNJUNGAN<br>SELESAI</th>
                                <th>PETUGAS<br>AKTIF</th>
                                <th>STATUS<br>LAPORAN</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporan as $l): ?>
                            <tr>
                                <td style="font-weight: 600; color: #636366;"><?= $l['kode'] ?></td>
                                <td style="color: var(--text-dark);"><?= $l['periode'] ?></td>
                                <td style="color: #636366;"><?= $l['kunjungan'] ?></td>
                                <td style="color: #636366;"><?= $l['selesai'] ?></td>
                                <td style="color: #636366;"><?= $l['petugas'] ?></td>
                                <td>
                                    <span class="status-badge"><?= $l['status'] ?></span>
                                </td>
                                <td>
                                    <a href="#" class="download-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        Unduh PDF
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination-text">Menampilkan 3 dari 24 laporan bulanan</div>
                    <div class="pagination-nav">
                        <span class="page-item" style="color: #C7C7CC;">&lt;</span>
                        <a href="#" class="page-item active">1</a>
                        <a href="#" class="page-item">2</a>
                        <a href="#" class="page-item">3</a>
                        <a href="#" class="page-item">&gt;</a>
                    </div>
                </div>
            </div>
            
            <!-- Global Footer text -->
            <div style="text-align: center; margin-top: 3rem; font-size: 0.625rem; font-weight: 700; color: #8E8E93; letter-spacing: 1px;">
                [Data Dummy / Simulasi] © 2024 MediAdmin CareVisit Monitor
            </div>
        </div>
    </div>
</div>
</body>
</html>
