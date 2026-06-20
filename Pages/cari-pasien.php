<?php
require_once '../config.php';

if (!isset($_SESSION['api_token'])) {
    header("Location: login.php");
    exit;
}

$query   = trim($_GET['q'] ?? '');
$results = [];
$found   = null;
$patientMonitorings = [];

if ($query !== '') {
    // Search all patients by name, NIK, or patient_id
    $patientsRes = callAPI('GET', '/patients');
    $allPatients = ($patientsRes['status_code'] === 200 && isset($patientsRes['response']['data']))
        ? $patientsRes['response']['data'] : [];

    $q = strtolower($query);
    foreach ($allPatients as $p) {
        $matchName = str_contains(strtolower($p['patient_name'] ?? ''), $q);
        $matchNik  = str_contains($p['nik_dummy'] ?? '', $q);
        $matchId   = str_contains(strtolower($p['patient_id'] ?? ''), $q);
        if ($matchName || $matchNik || $matchId) {
            $results[] = $p;
        }
    }

    // If exactly one result, auto-fetch their monitorings
    if (count($results) === 1) {
        $found = $results[0];
        $pid = $found['patient_id'];
        $monRes = callAPI('GET', '/patients/' . urlencode($pid) . '/monitorings');
        if ($monRes['status_code'] === 200 && isset($monRes['response']['data']['monitorings'])) {
            $patientMonitorings = $monRes['response']['data']['monitorings'];
        }
        // Fallback: get from all monitorings
        if (empty($patientMonitorings)) {
            $allMonRes = callAPI('GET', '/monitorings');
            $allMons   = ($allMonRes['status_code'] === 200 && isset($allMonRes['response']['data']))
                ? $allMonRes['response']['data'] : [];
            foreach ($allMons as $m) {
                if (($m['patient_id'] ?? '') === $pid) {
                    $patientMonitorings[] = $m;
                }
            }
        }
        usort($patientMonitorings, fn($a, $b) =>
            strtotime($b['monitoring_date'] ?? '') <=> strtotime($a['monitoring_date'] ?? ''));
    }
}

function getStatusClass($s) {
    $s = strtolower($s ?? '');
    if (str_contains($s, 'stable') || str_contains($s, 'stabil')) return 'stable';
    if (str_contains($s, 'referral') || str_contains($s, 'rujukan')) return 'referral';
    return 'control';
}
function getStatusBadge($status) {
    $cls = getStatusClass($status);
    $map = [
        'stable'   => ['background:var(--sv-green-light);color:#1A7A35;', '✅ Stabil'],
        'control'  => ['background:var(--sv-yellow-light);color:#8A4E00;', '⚠️ Perlu Kontrol'],
        'referral' => ['background:var(--sv-red-light);color:#C0291F;', '🚨 Perlu Rujukan'],
    ];
    [$style, $label] = $map[$cls];
    return '<span class="sv-badge" style="' . $style . '">' . htmlspecialchars($label) . '</span>';
}
function calculateAge($dob) {
    if (empty($dob)) return '-';
    $d = new DateTime($dob);
    return (new DateTime())->diff($d)->y . ' Thn';
}

$user        = $_SESSION['user'] ?? [];
$userName    = htmlspecialchars($user['name']  ?? 'Petugas');
$userInitial = strtoupper(substr($user['name'] ?? 'P', 0, 1));
$userEmail   = htmlspecialchars($user['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Pasien — SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <style>
        .search-hero {
            background: linear-gradient(135deg, var(--sv-navy) 0%, var(--sv-navy-mid) 100%);
            border-radius: var(--sv-radius-lg);
            padding: 32px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        .search-hero::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 180px; height: 180px;
            background: rgba(0,122,255,0.15);
            border-radius: 50%;
        }
        .search-hero h2 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin-bottom: 6px;
            position: relative;
        }
        .search-hero p {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin-bottom: 20px;
            position: relative;
        }
        .search-hero-input {
            position: relative;
        }
        .search-hero-input input {
            width: 100%;
            padding: 14px 18px 14px 48px;
            border-radius: 12px;
            border: 2px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.2s;
            backdrop-filter: blur(8px);
        }
        .search-hero-input input::placeholder { color: rgba(255,255,255,0.45); }
        .search-hero-input input:focus {
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.15);
        }
        .search-hero-input .search-ico {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            pointer-events: none;
        }
        .search-hero-input button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--sv-blue);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }
        .search-hero-input button:hover {
            background: var(--sv-blue-dark);
        }
        /* Multi result list */
        .result-list-item {
            background: var(--sv-surface);
            border: 1px solid var(--sv-border);
            border-radius: var(--sv-radius);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: var(--sv-transition);
            margin-bottom: 8px;
        }
        .result-list-item:hover {
            border-color: var(--sv-blue);
            box-shadow: var(--sv-shadow);
            transform: translateX(3px);
        }
        .result-avatar {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: var(--sv-blue-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
<div class="sv-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <!-- Topbar -->
        <div class="sv-topbar">
            <div class="sv-topbar-search">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Cari pasien, NIK, atau kode pasien..." id="globalSearch"
                       value="<?= htmlspecialchars($query) ?>" autocomplete="off">
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

        <!-- Content -->
        <div class="sv-content">

            <!-- Page Header -->
            <div class="sv-page-header sv-animate-in">
                <div>
                    <h1>Pencarian Pasien</h1>
                    <p>Temukan pasien berdasarkan nama, kode RM, atau NIK dummy.</p>
                </div>
            </div>

            <!-- Search Hero -->
            <div class="search-hero sv-animate-in">
                <h2>🔍 Cari Data Pasien</h2>
                <p>Masukkan nama pasien, kode RM (contoh: RM-2026-0001), atau NIK dummy 16 digit.</p>
                <form action="" method="GET">
                    <div class="search-hero-input" style="position:relative;">
                        <span class="search-ico">🔍</span>
                        <input type="text" name="q"
                               value="<?= htmlspecialchars($query) ?>"
                               placeholder="Nama, kode RM, atau NIK pasien..."
                               autofocus>
                        <button type="submit">Cari</button>
                    </div>
                </form>
            </div>

            <!-- Results -->
            <?php if ($query !== ''): ?>

                <?php if (empty($results)): ?>
                <!-- No result -->
                <div class="sv-card sv-animate-in text-center" style="padding:48px 24px;">
                    <div style="font-size:48px;margin-bottom:12px;">🔍</div>
                    <h5 style="font-weight:700;color:var(--sv-text-main);">Pasien tidak ditemukan</h5>
                    <p style="color:var(--sv-text-muted);font-size:13.5px;margin-bottom:20px;">
                        Tidak ada pasien dengan kata kunci <strong>"<?= htmlspecialchars($query) ?>"</strong>.
                        Pastikan nama, kode RM, atau NIK yang dimasukkan benar.
                    </p>
                    <a href="tambah-pasien.php" class="btn btn-primary btn-sm">➕ Tambah Pasien Baru</a>
                </div>

                <?php elseif (count($results) > 1): ?>
                <!-- Multiple results -->
                <div class="sv-animate-in">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <h5 style="font-size:15px;font-weight:700;margin:0;">
                            Ditemukan <span style="color:var(--sv-blue);"><?= count($results) ?></span> pasien
                        </h5>
                        <span style="font-size:12px;color:var(--sv-text-muted);">Klik untuk lihat riwayat monitoring</span>
                    </div>
                    <?php foreach ($results as $p):
                        $gender = ($p['gender'] ?? '') === 'Male' ? '👨' : '👩';
                        $monCount = count($p['monitorings'] ?? []);
                        $latestMon = $p['monitorings'][0] ?? null;
                    ?>
                    <a href="cari-pasien.php?q=<?= urlencode($p['patient_id']) ?>" class="result-list-item">
                        <div class="result-avatar"><?= $gender ?></div>
                        <div style="flex:1;">
                            <div style="font-weight:700;font-size:14px;color:var(--sv-text-main);">
                                <?= htmlspecialchars($p['patient_name'] ?? '-') ?>
                            </div>
                            <div style="font-size:12px;color:var(--sv-text-muted);margin-top:2px;">
                                <?= htmlspecialchars($p['patient_id'] ?? '') ?>
                                · <?= calculateAge($p['datebirth'] ?? '') ?>
                                · <?= htmlspecialchars($p['patient_category'] ?? '') ?>
                            </div>
                            <div style="font-size:11.5px;color:var(--sv-text-muted);margin-top:2px;">
                                📍 <?= htmlspecialchars(mb_strimwidth($p['address'] ?? '-', 0, 50, '…')) ?>
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <?php if ($latestMon): ?>
                            <?= getStatusBadge($latestMon['status'] ?? '') ?>
                            <?php endif; ?>
                            <div style="font-size:11px;color:var(--sv-text-muted);margin-top:4px;">
                                <?= $monCount ?> kunjungan
                            </div>
                        </div>
                        <span style="color:var(--sv-blue);font-size:16px;">›</span>
                    </a>
                    <?php endforeach; ?>
                </div>

                <?php else: ?>
                <!-- Single result — show full details -->
                <?php
                    $p = $found;
                    $gender = ($p['gender'] ?? '') === 'Male' ? '👨' : '👩';
                    $latestStatus = $patientMonitorings[0]['status'] ?? '';
                    $latestStatusClass = getStatusClass($latestStatus);
                ?>
                <div class="sv-search-result sv-animate-in">
                    <!-- Header -->
                    <div class="sv-search-result-header">
                        <div class="sv-search-avatar"><?= $gender ?></div>
                        <div style="flex:1;">
                            <div style="font-size:18px;font-weight:800;color:white;letter-spacing:-0.3px;">
                                <?= htmlspecialchars($p['patient_name'] ?? '-') ?>
                            </div>
                            <div style="font-size:12.5px;color:rgba(255,255,255,0.6);margin-top:3px;">
                                <?= htmlspecialchars($p['patient_id'] ?? '') ?>
                                &nbsp;·&nbsp; NIK: <?= htmlspecialchars($p['nik_dummy'] ?? '') ?>
                                &nbsp;·&nbsp; <?= calculateAge($p['datebirth'] ?? '') ?>
                            </div>
                        </div>
                        <div>
                            <?php if ($latestStatus): ?>
                            <div class="sv-status-pill <?= $latestStatusClass ?> mb-2">
                                <?= htmlspecialchars(getStatusBadge($latestStatus)) ?>
                            </div>
                            <?php endif; ?>
                            <a href="rekam-medis.php?patient_id=<?= urlencode($p['patient_id']) ?>"
                               style="font-size:12px;color:rgba(255,255,255,0.6);">
                                📂 Rekam Medis →
                            </a>
                        </div>
                    </div>

                    <!-- Patient Details -->
                    <div class="p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Kategori</div>
                                <div style="font-size:14px;font-weight:600;margin-top:3px;"><?= htmlspecialchars($p['patient_category'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-3">
                                <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Jenis Kelamin</div>
                                <div style="font-size:14px;font-weight:600;margin-top:3px;"><?= ($p['gender'] ?? '') === 'Male' ? '👨 Laki-laki' : '👩 Perempuan' ?></div>
                            </div>
                            <div class="col-md-3">
                                <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">No. HP Keluarga</div>
                                <div style="font-size:14px;font-weight:600;margin-top:3px;"><?= htmlspecialchars($p['family_phone'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-3">
                                <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Total Kunjungan</div>
                                <div style="font-size:22px;font-weight:800;color:var(--sv-blue);margin-top:2px;"><?= count($patientMonitorings) ?></div>
                            </div>
                            <div class="col-12">
                                <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Alamat</div>
                                <div style="font-size:13.5px;color:var(--sv-text-sub);margin-top:3px;">📍 <?= htmlspecialchars($p['address'] ?? '-') ?></div>
                            </div>
                        </div>

                        <!-- Monitoring History -->
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                            <h6 style="font-size:12px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:var(--sv-text-muted);margin:0;">
                                Riwayat Monitoring Kesehatan
                            </h6>
                            <a href="tambah-monitoring.php?patient_id=<?= urlencode($p['patient_id']) ?>"
                               class="btn btn-primary btn-sm" style="font-size:12px;">
                                🩺 Catat Monitoring Baru
                            </a>
                        </div>

                        <?php if (empty($patientMonitorings)): ?>
                        <div class="sv-empty-state" style="padding:32px 0;">
                            <div class="empty-icon">🩺</div>
                            <p>Belum ada catatan monitoring untuk pasien ini.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" style="font-size:13px;">
                                <thead style="background:#F8F9FA;">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tekanan Darah</th>
                                        <th>Suhu (°C)</th>
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($patientMonitorings, 0, 10) as $mon): ?>
                                    <tr>
                                        <td style="white-space:nowrap;">
                                            <div style="font-weight:600;">
                                                <?= isset($mon['monitoring_date']) ? date('d M Y', strtotime($mon['monitoring_date'])) : '-' ?>
                                            </div>
                                            <div style="font-size:11px;color:#8E8E93;">
                                                <?= isset($mon['monitoring_time']) ? date('H:i', strtotime($mon['monitoring_time'])) . ' WIB' : '' ?>
                                            </div>
                                        </td>
                                        <td style="font-weight:700;"><?= htmlspecialchars($mon['blood_pressure'] ?? '-') ?> <span style="font-size:10px;color:#8E8E93;">mmHg</span></td>
                                        <td><?= htmlspecialchars($mon['body_temperature'] ?? '-') ?>°</td>
                                        <td style="max-width:180px;white-space:normal;color:var(--sv-text-sub);">
                                            <?= htmlspecialchars(mb_strimwidth($mon['symptoms'] ?? '-', 0, 60, '…')) ?>
                                        </td>
                                        <td><?= getStatusBadge($mon['status'] ?? '') ?></td>
                                        <td>
                                            <a href="detail-monitoring.php?id=<?= (int)($mon['id'] ?? 0) ?>"
                                               class="btn btn-sm btn-outline-primary py-0"
                                               style="font-size:11px;">Detail</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($patientMonitorings) > 10): ?>
                        <div class="text-center mt-3">
                            <a href="rekam-medis.php?patient_id=<?= urlencode($p['patient_id']) ?>"
                               class="btn btn-sm btn-outline-primary">
                                Lihat Semua <?= count($patientMonitorings) ?> Kunjungan →
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            <?php else: ?>
            <!-- Initial state — no query yet -->
            <div class="row g-3">
                <div class="col-md-6 sv-animate-in sv-animate-in-1">
                    <div class="sv-card" style="height:100%;">
                        <div style="font-size:24px;margin-bottom:12px;">🔍</div>
                        <h6 style="font-weight:700;font-size:14px;">Cari Berdasarkan Nama</h6>
                        <p style="font-size:13px;color:var(--sv-text-muted);line-height:1.6;">
                            Masukkan nama pasien (sebagian atau lengkap). Contoh: "Slamet", "Bpk", "Aminah".
                        </p>
                    </div>
                </div>
                <div class="col-md-6 sv-animate-in sv-animate-in-2">
                    <div class="sv-card" style="height:100%;">
                        <div style="font-size:24px;margin-bottom:12px;">🪪</div>
                        <h6 style="font-weight:700;font-size:14px;">Cari Berdasarkan Kode RM / NIK</h6>
                        <p style="font-size:13px;color:var(--sv-text-muted);line-height:1.6;">
                            Masukkan kode RM (contoh: <code>RM-2026-0001</code>) atau NIK dummy 16 digit.
                        </p>
                    </div>
                </div>
                <div class="col-12 sv-animate-in sv-animate-in-3">
                    <div class="sv-card text-center" style="background:#FFFBEC;border-color:#FDEAB0;">
                        <span style="font-size:20px;">⚠️</span>
                        <p style="font-size:12.5px;color:#8A6200;margin:8px 0 0;">
                            Seluruh data pasien adalah data simulasi dummy untuk keperluan akademik. Bukan data pasien nyata.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /.sv-content -->

        <footer style="padding:20px 24px;border-top:1px solid #E8ECF0;background:#FAFBFC;">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <span style="font-size:12px;color:#8E8E93;">© 2026 SIVISIT — CareVisit Monitor.</span>
                <span style="font-size:11px;color:#8E8E93;font-style:italic;">⚠️ Data simulasi/dummy. Bukan diagnosis medis.</span>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('globalSearch').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && this.value.trim())
            window.location.href = 'cari-pasien.php?q=' + encodeURIComponent(this.value.trim());
    });
</script>
</body>
</html>
