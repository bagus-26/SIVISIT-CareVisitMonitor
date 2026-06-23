<?php
require_once '../config.php';

if (!isset($_SESSION['api_token'])) {
    header("Location: login.php");
    exit;
}

$patientsRes  = callAPI('GET', '/patients');
$patients     = ($patientsRes['status_code'] === 200 && isset($patientsRes['response']['data'])) ? $patientsRes['response']['data'] : [];

$monitoringsRes = callAPI('GET', '/monitorings');
$monitorings    = ($monitoringsRes['status_code'] === 200 && isset($monitoringsRes['response']['data'])) ? $monitoringsRes['response']['data'] : [];

$totalPatients  = count($patients);
$todayDate      = date('Y-m-d');
$todayVisits    = 0;
$needControl    = 0;
$todayAgenda    = [];

foreach ($monitorings as $m) {
    $status = strtolower($m['status'] ?? '');
    if (str_contains($status, 'control') || str_contains($status, 'kontrol')) $needControl++;
    if (($m['monitoring_date'] ?? '') === $todayDate) {
        $todayVisits++;
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
        return '<span class="sv-badge sv-badge-stable"> Stabil</span>';
    } elseif (str_contains($s, 'referral') || str_contains($s, 'rujukan')) {
        return '<span class="sv-badge sv-badge-referral"> Perlu Rujukan</span>';
    } else {
        return '<span class="sv-badge sv-badge-control"> Perlu Kontrol</span>';
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
</head>
<body>
<div class="sv-layout">

    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <div class="sv-topbar">
            <div class="sv-topbar-search">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    placeholder="Cari pasien, NIK, atau kode..."
                    id="globalSearch"
                    autocomplete="off"
                >
            </div>
            <div class="sv-topbar-right">
                <div class="sv-user-info">
                    <div class="user-text">
                        <div class="user-name"><?= $userName ?></div>
                        <div class="user-role"><?= $userEmail ?></div>
                    </div>
                    <div class="sv-avatar"><?= $userInitial ?></div>
                </div>
            </div>
        </div>

        <div class="sv-content">
            <div class="sv-page-header">
                <div>
                    <h1>Selamat Datang, <?= $userName ?> 👋</h1>
                    <p>Berikut ringkasan kondisi pasien home care Anda hari ini, <?= date('d F Y') ?>.</p>
                </div>
                <a href="tambah-pasien.php" class="btn btn-primary">
                     Tambah Pasien
                </a>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
                <div class="sv-stat-card" style="--accent-color:var(--sv-blue);">
                    <div class="stat-label">Total Pasien</div>
                    <div class="stat-value"><?= $totalPatients ?></div>
                    <div class="stat-sub">Pasien terdaftar</div>
                    <div class="stat-icon">👥</div>
                </div>
                <div class="sv-stat-card" style="--accent-color:var(--sv-yellow);">
                    <div class="stat-label">Kunjungan Hari Ini</div>
                    <div class="stat-value"><?= $todayVisits ?></div>
                    <div class="stat-sub">Agenda monitoring</div>
                    <div class="stat-icon">📅</div>
                </div>
                <div class="sv-stat-card" style="--accent-color:var(--sv-red);">
                    <div class="stat-label">Perlu Kontrol</div>
                    <div class="stat-value"><?= $needControl ?></div>
                    <div class="stat-sub">Butuh perhatian</div>
                    <div class="stat-icon">⚠️</div>
                </div>
                <div class="sv-stat-card" style="--accent-color:var(--sv-green);">
                    <div class="stat-label">Total Monitoring</div>
                    <div class="stat-value"><?= count($monitorings) ?></div>
                    <div class="stat-sub">Semua catatan</div>
                    <div class="stat-icon">📋</div>
                </div>
            </div>

            <!-- Emergency Alert Card: Kritis Hipertensi -->
            <div class="sv-card" style="border: 2px solid var(--sv-red); margin-bottom: 24px; position: relative; overflow: hidden; background: var(--sv-red-light);">
                <div style="position: absolute; top: -10px; right: 10px; font-size: 80px; opacity: 0.05; color: var(--sv-red); pointer-events: none;">⚠️</div>
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <h5 style="color: var(--sv-red); font-weight: 700; margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px; font-size: 15px;">
                            <span>⚠️</span> NOTIFIKASI RUJUKAN — KRITIS HIPERTENSI
                        </h5>
                        <p style="font-size: 13.5px; color: #5C1D1D; margin: 0;">
                            Sistem mendeteksi lonjakan tekanan darah signifikan pada pasien <strong>Bambang Kasuma</strong> (Darah: <strong style="color: var(--sv-red);">185/110 mmHg</strong>).
                        </p>
                    </div>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rujukanDaruratModal" style="background-color: var(--sv-red); border-color: var(--sv-red); font-weight: 600; padding: 8px 20px; font-size: 13.5px; border-radius: 8px; color: white;">
                        👁️ Tinjau Rujukan
                    </button>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
                <div class="sv-card" style="padding:0;">
                    <div class="sv-section-header">
                        <h5>📋 Agenda Kunjungan Hari Ini</h5>
                        <a href="monitoring.php" class="btn btn-outline-primary btn-sm" style="text-decoration:none;">Lihat Semua</a>
                    </div>
                    <div class="sv-table-wrap" style="border:none;border-radius:0;box-shadow:none;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Nama Pasien</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($todayAgenda)): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="sv-empty-state">
                                                <div class="empty-icon">📅</div>
                                                <p>Tidak ada agenda kunjungan hari ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($todayAgenda as $ag): ?>
                                        <tr>
                                            <td style="font-weight:600;">
                                                <?= isset($ag['monitoring_time']) ? date('H:i', strtotime($ag['monitoring_time'])) : '--:--' ?> WIB
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($ag['patient']['patient_name'] ?? '-') ?>
                                                <br><small style="color:var(--sv-text-muted);"><?= htmlspecialchars($ag['patient']['address'] ?? '') ?></small>
                                            </td>
                                            <td><?= getStatusBadge($ag['status'] ?? '') ?></td>
                                            <td><a href="detail-monitoring.php?id=<?= $ag['id'] ?>" class="btn btn-outline-primary btn-sm" style="text-decoration:none;">Detail</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="sv-card" style="display:flex;flex-direction:column;gap:16px;">
                    <h5 style="font-size:15px;font-weight:600;margin:0;">🔍 Cari Cepat</h5>
                    <p style="font-size:13px;color:var(--sv-text-muted);margin:0;">Masukkan kode pasien atau NIK untuk riwayat.</p>
                    <form action="cari-pasien.php" method="GET" style="display:flex;flex-direction:column;gap:8px;">
                        <input type="text" name="q" class="form-control" placeholder="Kode pasien / NIK dummy...">
                        <button type="submit" class="btn btn-primary">Cari Data</button>
                    </form>
                    <hr style="border:none;border-top:1px solid var(--sv-border);margin:8px 0;">
                    <h5 style="font-size:13px;font-weight:600;text-transform:uppercase;color:var(--sv-text-muted);margin:0;">Aksi Cepat</h5>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <a href="tambah-pasien.php" class="btn btn-outline-primary" style="text-decoration:none;text-align:left;"> Tambah Pasien Baru</a>
                        <a href="tambah-monitoring.php" class="btn btn-outline-primary" style="text-decoration:none;text-align:left;">🩺 Catat Monitoring</a>
                        <a href="monitoring.php" class="btn btn-outline-primary" style="text-decoration:none;text-align:left;">📋 Semua Monitoring</a>
                    </div>
                </div>
            </div>
        </div>

        <footer style="padding:16px 24px;border-top:1px solid var(--sv-border);text-align:center;color:var(--sv-text-muted);font-size:13px;background:var(--sv-surface);">
            © 2026 SIVISIT CareVisit Monitor. Seluruh data bersifat simulasi.
        </footer>
    </div>
</div>

<!-- Modal Tinjau Rujukan -->
<div class="modal fade" id="rujukanDaruratModal" tabindex="-1" aria-labelledby="rujukanDaruratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden; box-shadow: var(--sv-shadow-lg);">
            <div class="modal-header" style="background-color: white; color: var(--sv-text-main); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--sv-border); display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <h5 class="modal-title" id="rujukanDaruratModalLabel" style="font-size: 1.125rem; font-weight: 700; margin: 0; color: var(--sv-text-main);">Tinjau Rujukan Darurat</h5>
                    <span style="background-color: var(--sv-red); color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.625rem; font-weight: 700; letter-spacing: 0.05em;">KRITIS</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.875rem;"></button>
            </div>
            
            <div class="modal-body" style="padding: 1.5rem; background-color: white;">
                <!-- Alert Box -->
                <div style="background-color: var(--sv-red-light); border: 1px solid #fecaca; border-radius: 8px; padding: 0.875rem 1rem; display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <span style="font-size: 1.25rem; color: var(--sv-red); line-height: 1;">⚠️</span>
                    <div>
                        <span style="color: var(--sv-red); font-weight: 700; font-size: 0.875rem;">Kritis Hipertensi: </span>
                        <span style="color: #7f1d1d; font-size: 0.875rem;">Sistem mendeteksi lonjakan tekanan darah signifikan.</span>
                    </div>
                </div>

                <!-- 2 Column Info -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- Left Column -->
                    <div>
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.625rem; color: var(--sv-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">NAMA PASIEN</div>
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--sv-text-main);">Bambang Kasuma (L)</div>
                        </div>
                        <div>
                            <div style="font-size: 0.625rem; color: var(--sv-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">HASIL PEMERIKSAAN</div>
                            <div style="color: var(--sv-red); font-size: 1.5rem; font-weight: 800; line-height: 1;">185/110 <span style="font-size: 0.875rem; font-weight: 600; color: var(--sv-text-main);">mmHg</span></div>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div>
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.625rem; color: var(--sv-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">NO. REKAM MEDIS</div>
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--sv-text-main);">3578***********0023</div>
                        </div>
                        <div>
                            <div style="font-size: 0.625rem; color: var(--sv-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">WAKTU DETEKSI</div>
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--sv-text-main);">Kamis, 24 Oktober 2024<br>09:12 WIB</div>
                        </div>
                    </div>
                </div>

                <!-- Dropdowns -->
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.75rem; color: var(--sv-text-main); font-weight: 600; display: block; margin-bottom: 0.5rem;">Pilih Rumah Sakit Rujukan Tujuan</label>
                    <select class="form-select" style="font-size: 0.875rem; border: 1px solid var(--sv-border); border-radius: 6px; padding: 0.625rem; width: 100%; color: var(--sv-text-muted);">
                        <option>Pilih RSUD / Rumah Sakit Terdekat...</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--sv-text-main); font-weight: 600; display: block; margin-bottom: 0.5rem;">Spesialisasi / Poli Tujuan</label>
                    <select class="form-select" style="font-size: 0.875rem; border: 1px solid var(--sv-border); border-radius: 6px; padding: 0.625rem; width: 100%; color: var(--sv-text-main);">
                        <option>❤️ Poli Jantung & Pembuluh Darah</option>
                    </select>
                </div>

                <!-- Textarea -->
                <div>
                    <label style="font-size: 0.75rem; color: var(--sv-text-main); font-weight: 600; display: block; margin-bottom: 0.5rem;">Catatan Tambahan / Diagnosis Sementara Admin</label>
                    <textarea class="form-control" rows="3" placeholder="Tuliskan instruksi tambahan atau catatan kondisi pasien di sini..." style="font-size: 0.875rem; border: 1px solid var(--sv-border); border-radius: 6px; padding: 0.625rem; width: 100%; resize: vertical;"></textarea>
                </div>
            </div>
            
            <!-- Footer Buttons -->
            <div class="modal-footer" style="padding: 1rem 1.5rem; background-color: white; border-top: 1px solid var(--sv-border); display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal" style="background: white; border: 1px solid var(--sv-border); padding: 0.625rem 1.5rem; border-radius: 6px; font-weight: 600; color: var(--sv-text-muted); font-size: 0.875rem;">Kembali</button>
                <button type="button" class="btn" style="background-color: #f97316; color: white; border: none; padding: 0.625rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">Proses Rujukan Sekarang >></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('globalSearch')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && this.value.trim()) {
            window.location.href = 'cari-pasien.php?q=' + encodeURIComponent(this.value.trim());
        }
    });
</script>
</body>
</html>
