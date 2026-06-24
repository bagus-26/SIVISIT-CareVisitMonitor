<?php
require_once '../config.php';
require_once 'components/ui-config.php';

if (!isset($_SESSION['api_token'])) {
    header('Location: login.php');
    exit;
}

$patientsRes  = callAPI('GET', '/patients');
$patients     = ($patientsRes['status_code'] === 200 && isset($patientsRes['response']['data'])) ? $patientsRes['response']['data'] : [];

$monitoringsRes = callAPI('GET', '/monitorings');
$monitorings    = ($monitoringsRes['status_code'] === 200 && isset($monitoringsRes['response']['data'])) ? $monitoringsRes['response']['data'] : [];

$totalPatients = count($patients) ?: 24;
$todayVisits   = 8;
$needControl   = 5;
$needReferral  = 1;
$stableCount   = max(0, $totalPatients - $needControl - $needReferral);

$user     = $_SESSION['user'] ?? [];
$userName = svAdminDisplayName($user);

$weeklyVisits = [42, 55, 48, 62, 58, 51, 47];
$maxWeekly    = max($weeklyVisits);
$dayLabels    = ['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'MIN'];

$monitorPatients = [
    ['initials' => 'AJ', 'name' => 'Anisa Julia',   'status' => 'STABIL',   'class' => 'stabil',  'color' => '#34C759'],
    ['initials' => 'SH', 'name' => 'Siti Halimah',  'status' => 'STABIL',   'class' => 'stabil',  'color' => '#34C759'],
    ['initials' => 'BK', 'name' => 'Bambang K.',    'status' => 'KRITIS',   'class' => 'kritis',  'color' => '#FF3B30'],
    ['initials' => 'RN', 'name' => 'Rudi Nur',      'status' => 'BERESIKO', 'class' => 'kontrol', 'color' => '#FF9500'],
];

$searchPlaceholder = 'Cari data pasien atau laporan...';
$greetingHour = (int) date('G');
$greeting = $greetingHour < 12 ? 'Selamat Pagi' : ($greetingHour < 15 ? 'Selamat Siang' : ($greetingHour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
$monthsId = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$daysId = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$dateLabel = $daysId[(int)date('w')] . ', ' . date('d') . ' ' . $monthsId[(int)date('n')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — <?= SV_BRAND_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="admin-page.css" rel="stylesheet">
    <link href="modal.css" rel="stylesheet">
</head>
<body>
<div class="sv-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <?php require_once 'components/topbar.php'; ?>

        <div class="sv-content">
            <div class="sv-dash-greeting sv-animate-in">
                <div>
                    <h1><?= $greeting ?>, <?= $userName ?></h1>
                    <p>Berikut adalah ringkasan operasional klinis hari ini.</p>
                </div>
                <div class="sv-dash-date-box">📅 <?= ucfirst($dateLabel) ?></div>
            </div>

            <div class="sv-stats-row sv-animate-in">
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#eff6ff;color:#2563EB;">👥</div>
                    </div>
                    <div class="stat-label-sm">Total Pasien Binaan</div>
                    <div class="stat-num"><?= $totalPatients ?></div>
                    <div class="stat-sub-sm">+2 baru hari ini</div>
                </div>
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#f0fdf4;color:#16a34a;">✓</div>
                    </div>
                    <div class="stat-label-sm">Monitoring Hari Ini</div>
                    <div class="stat-num">8/12</div>
                    <div class="sv-progress-bar"><span style="width:66%;"></span></div>
                </div>
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#fff7ed;color:#f97316;">🔔</div>
                    </div>
                    <div class="stat-label-sm">Perlu Kontrol</div>
                    <div class="stat-num"><?= $needControl ?></div>
                    <div class="stat-sub-sm">Follow up immediately</div>
                </div>
                <div class="sv-stat-card-rich">
                    <div class="stat-top">
                        <div class="stat-icon-box" style="background:#fef2f2;color:#dc2626;">!</div>
                    </div>
                    <div class="stat-label-sm">Perlu Rujukan</div>
                    <div class="stat-num"><?= $needReferral ?></div>
                    <div class="stat-sub-sm">Emergency action required</div>
                </div>
            </div>

            <div class="sv-dash-3col sv-animate-in">
                <div class="sv-card" style="padding:0;">
                    <div class="sv-section-header"><h5>Pemantauan Pasien</h5></div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <?php foreach ($monitorPatients as $mp): ?>
                                <tr>
                                    <td style="width:44px;">
                                        <div class="sv-patient-row-avatar" style="background:<?= $mp['color'] ?>;"><?= $mp['initials'] ?></div>
                                    </td>
                                    <td style="font-weight:600;"><?= $mp['name'] ?></td>
                                    <td style="text-align:right;"><span class="sv-status-pill <?= $mp['class'] ?>"><?= $mp['status'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="sv-crisis-card">
                    <h6>Notifikasi Rujukan</h6>
                    <p style="font-size:13px;font-weight:600;margin:0 0 8px;color:var(--sv-text-main);">KRISIS HIPERTENSI</p>
                    <p style="font-size:12.5px;color:var(--sv-text-sub);margin:0 0 12px;">Sistem mendeteksi lonjakan tekanan darah signifikan.</p>
                    <p style="font-size:12px;color:var(--sv-text-muted);margin:0;">Pasien: <strong>Bambang Kusuma</strong></p>
                    <div class="bp-reading">185/110 mmHg</div>
                    <button type="button" class="btn btn-warning btn-sm" style="font-weight:600;" data-bs-toggle="modal" data-bs-target="#modalRujukan">
                        👁 Tinjau Rujukan
                    </button>
                </div>

                <div class="sv-card sv-chat-panel">
                    <div class="sv-section-header" style="border:none;padding-bottom:0;">
                        <h5>Chat Langsung</h5>
                        <span style="font-size:11px;font-weight:700;color:#16a34a;">● 4 ONLINE</span>
                    </div>
                    <div style="flex:1;padding:0 16px;">
                        <div class="sv-chat-bubble in"><strong>AU</strong><br>Bp, dosis obat pagi ini apakah sama?</div>
                        <div class="sv-chat-bubble out">Ya, tetap 1 tablet setelah makan pagi.</div>
                    </div>
                    <div style="padding:12px 16px;border-top:1px solid var(--sv-border);display:flex;gap:8px;">
                        <input type="text" class="form-control form-control-sm" placeholder="Tulis pesan...">
                        <button type="button" class="btn btn-primary btn-sm">➤</button>
                    </div>
                </div>
            </div>

            <div class="sv-dashboard-grid sv-animate-in">
                <div class="sv-dashboard-main">
                    <div class="sv-chart-card">
                        <h5 style="font-size:15px;font-weight:600;margin:0 0 4px;">Tren Kunjungan Harian</h5>
                        <p style="font-size:12px;color:var(--sv-text-muted);margin:0 0 12px;">Rekapitulasi 7 hari terakhir</p>
                        <div class="sv-chart-bars">
                            <?php foreach ($weeklyVisits as $i => $v): ?>
                                <div class="sv-chart-bar" style="height:<?= round(($v / $maxWeekly) * 100) ?>%;" title="<?= $v ?> kunjungan"></div>
                            <?php endforeach; ?>
                        </div>
                        <div class="sv-chart-labels">
                            <?php foreach ($dayLabels as $lbl): ?><span><?= $lbl ?></span><?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="sv-dashboard-side">
                    <div class="sv-card">
                        <h5 style="font-size:15px;font-weight:600;margin:0 0 12px;">Aktivitas Lokasi Terbaru</h5>
                        <div class="sv-map-placeholder">
                            <div class="sv-map-overlay">
                                <span>ACTIVE TASKS: 12</span>
                                <span>RESP TIME: 14m</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer style="padding:16px 24px;border-top:1px solid var(--sv-border);text-align:center;color:var(--sv-text-muted);font-size:12px;background:var(--sv-surface);">
            © 2026 <?= SV_BRAND_NAME ?>. Semua hak dilindungi. · (Data Dummy / Simulasi) · Syarat & Ketentuan · Kebijakan Privasi
        </footer>
    </div>
</div>

<!-- Modal Tinjau Rujukan -->
<div class="modal fade" id="modalRujukan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid var(--sv-border);">
                <h5 class="modal-title" style="font-weight:700;">Tinjau Rujukan Darurat</h5>
                <span class="sv-status-pill kritis">KRITIS</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div style="background:var(--sv-red-light);border-radius:10px;padding:16px;margin-bottom:20px;">
                    <p style="font-weight:700;color:var(--sv-red);margin:0 0 8px;">⚠ Kritis Hipertensi</p>
                    <p style="font-size:13px;margin:0 0 12px;">Sistem mendeteksi lonjakan tekanan darah signifikan.</p>
                    <div class="row g-2" style="font-size:13px;">
                        <div class="col-6"><strong>Nama Pasien:</strong> Bambang Kusuma (L)</div>
                        <div class="col-6"><strong>NIK / RM:</strong> 3578*********0003</div>
                        <div class="col-12"><strong>Vital Sign:</strong> <span style="font-size:22px;font-weight:800;color:var(--sv-red);">185/110 mmHg</span></div>
                        <div class="col-12"><strong>Waktu Deteksi:</strong> Kamis, 24 Oktober 2024, 08:12 WIB</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Rumah Sakit Rujukan Tujuan</label>
                    <select class="form-select"><option>Pilih RSUD / Rumah Sakit Terdekat...</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Spesialisasi / Poli Tujuan</label>
                    <select class="form-select"><option>❤ Poli Jantung & Pembuluh Darah</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan Tambahan / Diagnosis Sementara Admin</label>
                    <textarea class="form-control" rows="3" placeholder="Tuliskan instruksi ambulans atau catatan kondisi pasien di sini..."></textarea>
                </div>
            </div>
            <div class="modal-footer" style="background:var(--sv-bg);">
                <button type="button" class="sv-filter-btn" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-warning" style="font-weight:600;">Proses Rujukan Sekarang ➤</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
