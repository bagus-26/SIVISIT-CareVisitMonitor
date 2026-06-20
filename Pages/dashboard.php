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
            <div style="position: relative; width: 300px;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);">🔍</span>
                <input
                    type="text"
                    placeholder="Cari pasien, NIK, atau kode..."
                    id="globalSearch"
                    autocomplete="off"
                    class="form-control"
                    style="padding: 0.5rem 1rem 0.5rem 2.5rem; width: 100%; border: 1px solid var(--border); border-radius: 6px;"
                >
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right;">
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-dark);"><?= $userName ?></div>
                    <div style="color: var(--text-muted); font-size: 0.75rem;"><?= $userEmail ?></div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="dashboard-content">

            <!-- Page Header -->
            <div class="dashboard-header">
                <div>
                    <h1>Selamat Datang, <?= $userName ?> 👋</h1>
                    <p style="color: var(--text-muted); margin-top: 0.25rem;">Berikut ringkasan kondisi pasien home care Anda hari ini, <?= date('d F Y') ?>.</p>
                </div>
                <a href="tambah-pasien.php" class="btn btn-primary" style="text-decoration: none;">
                    ➕ Tambah Pasien
                </a>
            </div>

            <!-- Stat Cards -->
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-icon primary">👥</div>
                    <div class="stat-info">
                        <h3><?= $totalPatients ?></h3>
                        <p>Total Pasien</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning">📅</div>
                    <div class="stat-info">
                        <h3><?= $todayVisits ?></h3>
                        <p>Kunjungan Hari Ini</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon danger">⚠️</div>
                    <div class="stat-info">
                        <h3><?= $needControl ?></h3>
                        <p>Perlu Kontrol</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">✅</div>
                    <div class="stat-info">
                        <h3><?= $todayFinished ?></h3>
                        <p>Status Stabil</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">

                <!-- Main Panel: Today's Agenda -->
                <div class="dashboard-panel">
                    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3>📋 Agenda Kunjungan Hari Ini</h3>
                        <a href="monitoring.php" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; text-decoration: none;">Lihat Semua</a>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($todayAgenda)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 2rem;">
                                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📅</div>
                                            <p style="color: var(--text-muted);">Tidak ada agenda kunjungan hari ini.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($todayAgenda as $ag): ?>
                                        <tr>
                                            <td style="font-weight: 600;">
                                                <?= isset($ag['monitoring_time']) ? date('H:i', strtotime($ag['monitoring_time'])) : '--:--' ?> WIB
                                            </td>
                                            <td style="font-weight: 500;">
                                                <?= htmlspecialchars($ag['patient']['patient_name'] ?? '-') ?>
                                            </td>
                                            <td style="color: var(--text-muted);">
                                                <?= htmlspecialchars($ag['patient']['address'] ?? '-') ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $s = strtolower($ag['status'] ?? '');
                                                    if (str_contains($s, 'stable') || str_contains($s, 'stabil')) echo '<span class="badge badge-success">✅ Stabil</span>';
                                                    elseif (str_contains($s, 'referral') || str_contains($s, 'rujukan')) echo '<span class="badge badge-danger">🚨 Perlu Rujukan</span>';
                                                    else echo '<span class="badge badge-warning">⚠️ Perlu Kontrol</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <a href="pasien.php" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; text-decoration: none;">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Side Panel: Quick Actions -->
                <div class="dashboard-panel">
                    <div class="panel-header">
                        <h3>🔍 Cari Cepat</h3>
                    </div>
                    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem;">Masukkan kode pasien atau NIK untuk riwayat.</p>
                    <form action="cari-pasien.php" method="GET">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Kode pasien / NIK dummy..."
                            style="width: 100%; margin-bottom: 1rem; border: 1px solid var(--border); border-radius: 6px; padding: 0.5rem 1rem;"
                        >
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Cari Data</button>
                    </form>

                    <hr style="border: none; border-top: 1px solid var(--border); margin: 1.5rem 0;">

                    <h3 style="font-size: 0.875rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem;">Aksi Cepat</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="tambah-pasien.php" class="btn btn-outline" style="text-align: left; text-decoration: none;">➕ Tambah Pasien Baru</a>
                        <a href="tambah-monitoring.php" class="btn btn-outline" style="text-align: left; text-decoration: none;">🩺 Catat Monitoring</a>
                        <a href="monitoring.php" class="btn btn-outline" style="text-align: left; text-decoration: none;">📋 Semua Monitoring</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer style="padding: 1.5rem; border-top: 1px solid var(--border); text-align: center; color: var(--text-muted); font-size: 0.875rem; background: #fff; margin-top: auto;">
            © 2026 MediAdmin CareVisit Monitor. Informatika Kesehatan.<br>
            <span style="font-size: 0.75rem; font-style: italic;">⚠️ Seluruh data bersifat simulasi/dummy. Bukan diagnosis medis.</span>
        </footer>
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