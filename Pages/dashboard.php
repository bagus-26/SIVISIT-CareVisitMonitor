<?php
require_once '../config.php';

if (!isset($_SESSION['api_token'])) {
    header("Location: login.php");
    exit;
}

// Fetch data
$patientsRes  = callAPI('GET', '/patients');
$patients     = ($patientsRes['status_code'] === 200 && isset($patientsRes['response']['data'])) ? $patientsRes['response']['data'] : [];

$monitoringsRes = callAPI('GET', '/monitorings');
$monitorings    = ($monitoringsRes['status_code'] === 200 && isset($monitoringsRes['response']['data'])) ? $monitoringsRes['response']['data'] : [];

// Statistics
$totalPatients  = count($patients);
$todayDate      = date('Y-m-d');
$todayVisits    = 0;
$todayFinished  = 0;
$needControl    = 0;
$needReferral   = 0;
$todayAgenda    = [];

foreach ($monitorings as $m) {
    $status = strtolower($m['status'] ?? '');
    if (str_contains($status, 'control') || str_contains($status, 'kontrol')) $needControl++;
    if (str_contains($status, 'referral') || str_contains($status, 'rujukan')) $needReferral++;

    if (($m['monitoring_date'] ?? '') === $todayDate) {
        $todayVisits++;
        if ($status === 'stable' || $status === 'stabil') $todayFinished++;
        $todayAgenda[] = $m;
    }
}

$user = $_SESSION['user'] ?? [];
$userName    = htmlspecialchars($user['name']  ?? 'Petugas');
$userInitial = strtoupper(substr($user['name'] ?? 'P', 0, 1));
$userEmail   = htmlspecialchars($user['email'] ?? '');

function getStatusBadge($status) {
    $s = strtolower($status ?? '');
    if (str_contains($s, 'stable') || str_contains($s, 'stabil')) {
        return '<span class="sv-badge sv-badge-stable">✅ Stabil</span>';
    } elseif (str_contains($s, 'referral') || str_contains($s, 'rujukan')) {
        return '<span class="sv-badge sv-badge-referral">🚨 Perlu Rujukan</span>';
    } else {
        return '<span class="sv-badge sv-badge-control">⚠️ Perlu Kontrol</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/global.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/dashboard.css" rel="stylesheet">
</head>
<body>
<div class="app-container">

    <?php require_once 'components/sidebar.php'; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="top-header">
            <div style="position: relative; width: 350px;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">🔍</span>
                <input
                    type="text"
                    placeholder="Cari data pasien atau laporan..."
                    id="globalSearch"
                    autocomplete="off"
                    class="form-control"
                    style="padding: 0.5rem 1rem 0.5rem 2.5rem; width: 100%; border: 1px solid var(--border); border-radius: 6px; background-color: #f8fafc;"
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
                        <div style="font-weight: 700; font-size: 0.875rem; color: var(--text-dark);">Dr. Admin (Pusat)</div>
                        <div style="color: var(--text-muted); font-size: 0.75rem;">Kepala Klinik Sentra</div>
                    </div>
                    <div style="width: 36px; height: 36px; border-radius: 50%; background-image: url('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/1.png'); background-size: cover; background-color: var(--primary-light);"></div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="dashboard-content">

            <!-- Page Header -->
            <div class="dashboard-header">
                <div>
                    <h1>Selamat Pagi, Dr. Admin</h1>
                    <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Berikut adalah ringkasan operasional klinis hari ini.</p>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background-color: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; font-weight: 500; font-size: 0.875rem;">
                    <span style="color: var(--primary);">📅</span> Kamis, 24 Oktober 2024
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-label">Total Pasien Binaan</span>
                        <div class="stat-icon primary">👥</div>
                    </div>
                    <div class="stat-info">
                        <h3>24</h3>
                        <p style="color: var(--primary);">↗ +2 baru hari ini</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-label">Monitoring Hari Ini</span>
                        <div class="stat-icon success">📋</div>
                    </div>
                    <div class="stat-info">
                        <h3>8/12</h3>
                        <div style="height: 4px; background-color: #e2e8f0; border-radius: 2px; margin-top: 0.5rem; overflow: hidden;">
                            <div style="width: 66%; height: 100%; background-color: var(--success);"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom: 4px solid var(--warning);">
                    <div class="stat-card-header">
                        <span class="stat-label">Perlu Kontrol</span>
                        <div class="stat-icon warning">🔔</div>
                    </div>
                    <div class="stat-info">
                        <h3>5</h3>
                        <p class="warning-text">Follow up immediately</p>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom: 4px solid var(--danger);">
                    <div class="stat-card-header">
                        <span class="stat-label">Perlu Rujukan</span>
                        <div class="stat-icon danger">⚠️</div>
                    </div>
                    <div class="stat-info">
                        <h3>1</h3>
                        <p class="danger-text">Emergency action required</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">

                <!-- Main Panel: Pemantauan Pasien -->
                <div class="dashboard-panel">
                    <div class="panel-header">
                        <h3>Pemantauan Pasien</h3>
                        <a href="monitoring.php" style="font-size: 0.875rem; color: var(--primary); font-weight: 500;">Lihat Semua</a>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="patient-row">
                                            <div class="patient-avatar">AU</div>
                                            <span class="patient-name">Anisa Julia</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-success">STABIL</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="patient-row">
                                            <div class="patient-avatar">SH</div>
                                            <span class="patient-name">Siti Halimah</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-success">STABIL</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="patient-row">
                                            <div class="patient-avatar" style="background-color: var(--danger); color: white;">BK</div>
                                            <span class="patient-name">Bambang K.</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-danger">KRITIS</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="patient-row">
                                            <div class="patient-avatar" style="background-color: var(--warning); color: white;">RN</div>
                                            <span class="patient-name">Rudi Nur</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-warning">BERESIKO</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Middle Panel: Notifikasi Rujukan -->
                <div class="dashboard-panel" style="border: 2px solid var(--danger); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: 10px; font-size: 120px; opacity: 0.05; color: var(--danger);">⚠️</div>
                    <div class="panel-header" style="border-bottom: none; padding-bottom: 0;">
                        <h3 style="color: var(--danger); display: flex; align-items: center; gap: 0.5rem;"><span style="font-size: 1.25rem;">⚠️</span> NOTIFIKASI RUJUKAN</h3>
                    </div>
                    <div class="panel-body">
                        <h4 style="color: var(--danger); margin-bottom: 0.25rem; font-size: 1.125rem; font-weight: 800;">KRISIS HIPERTENSI</h4>
                        <p style="color: var(--danger); font-size: 0.875rem; margin-bottom: 1.5rem; font-weight: 500;">Sistem mendeteksi lonjakan tekanan darah signifikan.</p>
                        
                        <div style="background-color: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.25rem;">Pasien:</div>
                            <div style="font-weight: 700; margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-dark);">Bambang Kasuma</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-bottom: 0.25rem;">Darah:</div>
                            <div style="color: var(--danger); font-size: 1.5rem; font-weight: 800; line-height: 1;">185/110 <span style="font-size: 1rem; font-weight: 600; color: var(--text-dark);">mmHg</span></div>
                        </div>
                        
                        <button class="btn" onclick="window.location.href='#rujukanDaruratModal'" data-bs-toggle="modal" data-bs-target="#rujukanDaruratModal" style="width: 100%; background-color: #f97316; border: none; color: white; padding: 0.75rem; font-weight: 700; border-radius: 6px; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            👁️ Tinjau Rujukan
                        </button>
                    </div>
                </div>

                <!-- Right Panel: Chat Langsung -->
                <div class="dashboard-panel">
                    <div class="panel-header">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <h3>Chat Langsung</h3>
                            <span style="font-size: 0.625rem; background-color: var(--success-bg); color: var(--success); padding: 0.125rem 0.5rem; border-radius: 12px; font-weight: 700;">4 ONLINE</span>
                        </div>
                        <span style="color: var(--text-muted); cursor: pointer;">⋮</span>
                    </div>
                    <div class="panel-body" style="display: flex; flex-direction: column; justify-content: flex-end; padding: 1rem;">
                        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
                            <div class="patient-avatar" style="width: 24px; height: 24px; font-size: 0.625rem; flex-shrink: 0;">AU</div>
                            <div style="background-color: #f1f5f9; padding: 0.75rem; border-radius: 8px 8px 8px 0; font-size: 0.875rem; position: relative;">
                                Dok, apakah obat tensi saya perlu ditambah dosisnya?
                                <div style="font-size: 0.625rem; color: var(--text-muted); text-align: right; margin-top: 0.25rem;">09:12</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; justify-content: flex-end;">
                            <div style="background-color: var(--primary); color: white; padding: 0.75rem; border-radius: 8px 8px 0 8px; font-size: 0.875rem; position: relative;">
                                Silakan teruskan dosis yang ada, saya akan tinjau laporan sore nanti.
                                <div style="font-size: 0.625rem; color: rgba(255,255,255,0.7); text-align: right; margin-top: 0.25rem;">09:15</div>
                            </div>
                            <div class="patient-avatar" style="width: 24px; height: 24px; font-size: 0.625rem; flex-shrink: 0; background-color: var(--text-muted); color: white;">Dr</div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.5rem; border: 1px solid var(--border); border-radius: 24px; padding: 0.5rem 1rem;">
                            <input type="text" placeholder="Tulis pesan..." style="border: none; outline: none; flex: 1; font-size: 0.875rem; background: transparent;">
                            <span style="color: var(--primary); cursor: pointer;">➤</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Dashboard Grid Bottom -->
            <div class="dashboard-grid-bottom">
                <!-- Tren Kunjungan -->
                <div class="dashboard-panel">
                    <div class="panel-header" style="border-bottom: none;">
                        <h3>Tren Kunjungan Harian</h3>
                        <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: var(--text-muted);">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--primary);"></div> Terjadwal
                        </div>
                    </div>
                    <div class="panel-body" style="padding-top: 0;">
                        <!-- Placeholder for chart -->
                        <div style="height: 250px; display: flex; flex-direction: column; justify-content: flex-end; position: relative;">
                            <!-- Fake chart bars -->
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; height: 200px; padding: 0 1rem;">
                                <div style="width: 40px; height: 40%; background-color: var(--primary-light); border-radius: 4px 4px 0 0;"></div>
                                <div style="width: 40px; height: 60%; background-color: var(--primary-light); border-radius: 4px 4px 0 0;"></div>
                                <div style="width: 40px; height: 30%; background-color: var(--primary-light); border-radius: 4px 4px 0 0;"></div>
                                <div style="width: 40px; height: 90%; background-color: var(--primary); border-radius: 4px 4px 0 0; position: relative;">
                                    <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); background: var(--text-dark); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.625rem; white-space: nowrap;">62 kunjungan</div>
                                </div>
                                <div style="width: 40px; height: 0%; background-color: var(--primary-light); border-radius: 4px 4px 0 0;"></div>
                                <div style="width: 40px; height: 0%; background-color: var(--primary-light); border-radius: 4px 4px 0 0;"></div>
                                <div style="width: 40px; height: 0%; background-color: var(--primary-light); border-radius: 4px 4px 0 0;"></div>
                            </div>
                            <!-- X Axis -->
                            <div style="display: flex; justify-content: space-between; margin-top: 1rem; color: var(--text-muted); font-size: 0.75rem; font-weight: 600; padding: 0 1.25rem;">
                                <span>SEN</span>
                                <span>SEL</span>
                                <span>RAB</span>
                                <span style="color: var(--text-dark);">KAM</span>
                                <span>JUM</span>
                                <span>SAB</span>
                                <span>MIN</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Lokasi Terbaru -->
                <div class="dashboard-panel">
                    <div class="panel-header" style="border-bottom: none;">
                        <h3 style="display: flex; align-items: center; gap: 0.5rem;"><div style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--danger);"></div> Aktivitas Lokasi Terbaru</h3>
                        <div style="font-size: 0.875rem; color: var(--text-muted);">Live Monitor</div>
                    </div>
                    <div class="panel-body" style="padding-top: 0;">
                        <div class="map-container" style="background-color: #e2e8f0; background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">
                            <!-- Fake Map markers -->
                            <div style="position: absolute; top: 40%; left: 30%; width: 12px; height: 12px; background-color: var(--primary); border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 4px var(--primary-light);"></div>
                            <div style="position: absolute; top: 60%; left: 60%; width: 12px; height: 12px; background-color: var(--primary); border: 2px solid white; border-radius: 50%;"></div>
                            <div style="position: absolute; top: 75%; left: 80%; width: 12px; height: 12px; background-color: var(--danger); border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 4px var(--danger-bg);"></div>
                            <div style="position: absolute; top: 30%; left: 70%; width: 12px; height: 12px; background-color: #94a3b8; border: 2px solid white; border-radius: 50%;"></div>
                            
                            <!-- Overlay Cards -->
                            <div style="position: absolute; bottom: 1rem; left: 1rem; right: 1rem; display: flex; gap: 1rem;">
                                <div style="background: white; padding: 0.75rem; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <div style="font-size: 0.625rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">ACTIVE TASKS</div>
                                    <div style="font-size: 1.25rem; font-weight: 800;">12</div>
                                </div>
                                <div style="background: white; padding: 0.75rem; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <div style="font-size: 0.625rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">RESP TIME</div>
                                    <div style="font-size: 1.25rem; font-weight: 800;">14m</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer style="padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.875rem; border-top: 1px solid var(--border); margin-top: auto;">
            <div>© 2024 MediAdmin CareVisit Monitor. Semua hak dilindungi.</div>
            <div style="display: flex; gap: 1rem; font-size: 0.75rem; font-weight: 500;">
                <span style="cursor: pointer;">| Data Dummy / Simulasi |</span>
                <span style="cursor: pointer;">Syarat & Ketentuan |</span>
                <span style="cursor: pointer;">Kebijakan Privasi</span>
            </div>
        </footer>
    </div>
<!-- Modal Tinjau Rujukan -->
<div class="modal fade" id="rujukanDaruratModal" tabindex="-1" aria-labelledby="rujukanDaruratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background-color: white; color: var(--text-dark); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <h5 class="modal-title" id="rujukanDaruratModalLabel" style="font-size: 1.125rem; font-weight: 700; margin: 0; color: var(--text-dark);">Tinjau Rujukan Darurat</h5>
                    <span style="background-color: var(--danger); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.625rem; font-weight: 700; letter-spacing: 0.05em;">KRITIS</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.875rem;"></button>
            </div>
            
            <div class="modal-body" style="padding: 1.5rem; background-color: white;">
                <!-- Alert Box -->
                <div style="background-color: var(--danger-bg); border: 1px solid #fecaca; border-radius: 8px; padding: 0.875rem 1rem; display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.25rem; color: var(--danger); line-height: 1;">⚠️</span>
                    <div>
                        <span style="color: var(--danger); font-weight: 700; font-size: 0.875rem;">Kritis Hipertensi: </span>
                        <span style="color: #7f1d1d; font-size: 0.875rem;">Sistem mendeteksi lonjakan tekanan darah signifikan.</span>
                    </div>
                </div>

                <!-- 2 Column Info -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- Left Column -->
                    <div>
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">NAMA PASIEN</div>
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Bambang Kasuma (L)</div>
                        </div>
                        <div>
                            <div style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">HASIL PEMERIKSAAN</div>
                            <div style="color: var(--danger); font-size: 1.5rem; font-weight: 800; line-height: 1;">185/110 <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-dark);">mmHg</span></div>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div>
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">NO. REKAM MEDIS</div>
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">3578***********0023</div>
                        </div>
                        <div>
                            <div style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">WAKTU DETEKSI</div>
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);">Kamis, 24 Oktober 2024<br>09:12 WIB</div>
                        </div>
                    </div>
                </div>

                <!-- Dropdowns -->
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; color: var(--text-dark); font-weight: 600; display: block; margin-bottom: 0.5rem;">Pilih Rumah Sakit Rujukan Tujuan</label>
                    <select class="form-select" style="font-size: 0.875rem; border: 1px solid var(--border); border-radius: 6px; padding: 0.625rem; width: 100%; color: var(--text-muted);">
                        <option>Pilih RSUD / Rumah Sakit Terdekat...</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-dark); font-weight: 600; display: block; margin-bottom: 0.5rem;">Spesialisasi / Poli Tujuan</label>
                    <select class="form-select" style="font-size: 0.875rem; border: 1px solid var(--border); border-radius: 6px; padding: 0.625rem; width: 100%; color: var(--text-dark);">
                        <option>❤️ Poli Jantung & Pembuluh Darah</option>
                    </select>
                </div>

                <!-- Textarea -->
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-dark); font-weight: 600; display: block; margin-bottom: 0.5rem;">Catatan Tambahan / Diagnosis Sementara Admin</label>
                    <textarea class="form-control" rows="3" placeholder="Tuliskan instruksi tambahan atau catatan kondisi pasien di sini..." style="font-size: 0.875rem; border: 1px solid var(--border); border-radius: 6px; padding: 0.625rem; width: 100%; resize: vertical;"></textarea>
                </div>
            </div>
            
            <!-- Footer Buttons -->
            <div class="modal-footer" style="padding: 1rem 1.5rem; background-color: white; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal" style="background: white; border: 1px solid var(--border); padding: 0.625rem 1.5rem; border-radius: 6px; font-weight: 600; color: var(--text-muted); font-size: 0.875rem;">Kembali</button>
                <button type="button" class="btn" style="background-color: #f97316; color: white; border: none; padding: 0.625rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">Proses Rujukan Sekarang >></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global search redirect to cari-pasien
    document.getElementById('globalSearch').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            window.location.href = 'cari-pasien.php?q=' + encodeURIComponent(this.value.trim());
        }
    });
</script>
</body>
</html>