<?php
require_once '../config.php';
require_once 'components/ui-config.php';

if (!isset($_SESSION['api_token'])) {
    header("Location: login.php");
    exit;
}

$patientsRes = callAPI('GET', '/patients');
$patients    = ($patientsRes['status_code'] === 200 && isset($patientsRes['response']['data'])) ? $patientsRes['response']['data'] : [];

function calculateAge($dob) {
    if (empty($dob)) return '-';
    $birthDate = new DateTime($dob);
    $today     = new DateTime();
    return $today->diff($birthDate)->y . ' Thn';
}

function getStatusBadge($status) {
    $s = strtolower($status ?? '');
    if (str_contains($s, 'stable') || str_contains($s, 'stabil')) {
        return '<span class="sv-status-pill stabil">● STABIL</span>';
    } elseif (str_contains($s, 'referral') || str_contains($s, 'rujukan') || str_contains($s, 'kritis')) {
        return '<span class="sv-status-pill kritis">● KRITIS</span>';
    } elseif (str_contains($s, 'control') || str_contains($s, 'kontrol')) {
        return '<span class="sv-status-pill kontrol">● PERLU KONTROL</span>';
    }
    return '<span class="sv-status-pill stabil">● STABIL</span>';
}

function maskNik($nik) {
    if (strlen($nik) < 8) return $nik;
    return substr($nik, 0, 4) . str_repeat('*', max(0, strlen($nik) - 8)) . substr($nik, -4);
}

function getCategoryBadge($cat) {
    $badges = [
        'lansia'      => ['#FFF4E5','#8A4E00','🧓'],
        'hipertensi'  => ['#FFF0EF','#C0291F','❤️'],
        'diabetes'    => ['#F5EEFF','#7B35A0','🩸'],
        'pasca rawat' => ['#E8F1FF','#0058D0','🏥'],
        'lainnya'     => ['#F2F4F7','#636366','📋'],
    ];
    $key = strtolower($cat ?? '');
    foreach ($badges as $k => [$bg, $color, $icon]) {
        if (str_contains($key, $k)) {
            return "<span class='sv-badge' style='background:{$bg};color:{$color};'>{$icon} {$cat}</span>";
        }
    }
    return "<span class='sv-badge' style='background:#F2F4F7;color:#636366;'>{$cat}</span>";
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
    <title>Data Pasien — <?= SV_BRAND_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="table.css" rel="stylesheet">
    <link href="admin-page.css" rel="stylesheet">
    <link href="modal.css" rel="stylesheet">
    <style>
        .patient-avatar {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: #F2F4F7;
            flex-shrink: 0;
        }
        .search-filter-bar input { flex: 1; min-width: 200px; }

        .detail-row { display: flex; flex-direction: column; gap: 2px; padding: 10px 0; border-bottom: 1px solid #F2F4F7; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: #8E8E93; }
        .detail-value { font-size: 14px; font-weight: 500; color: #1C1C1E; }

        .monitoring-mini-table th { font-size: 11px; }
        .monitoring-mini-table td { font-size: 12.5px; }
    </style>
</head>
<body>
<div class="sv-layout">

    <?php require_once 'components/sidebar.php'; ?>

    <div class="sv-main">
        <?php $searchPlaceholder = 'Cari nama pasien, kode, atau NIK...'; require_once 'components/topbar.php'; ?>

        <!-- Content -->
        <div class="sv-content">
            <?php if (isset($_GET['success'])): ?>
                <?php if ($_GET['success'] === 'deleted'): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
                        <span>🎉</span><span>Data pasien berhasil dihapus secara permanen.</span>
                    </div>
                <?php elseif ($_GET['success'] === 'updated'): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
                        <span>🎉</span><span>Data pasien berhasil diperbarui.</span>
                    </div>
                <?php endif; ?>
            <?php elseif (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'delete_failed'): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
                        <span>⚠️</span><span>Gagal menghapus data pasien. Silakan coba lagi.</span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="sv-page-header sv-animate-in">
                <div>
                    <h1>Data Pasien</h1>
                    <p>Kelola seluruh data pasien home care yang terdaftar di sistem.</p>
                </div>
            </div>

            <div class="mb-4 sv-animate-in">
                <button type="button" class="btn btn-primary" style="font-weight:600;" data-bs-toggle="modal" data-bs-target="#modalTambahPasien">
                    + Tambah Pasien Baru
                </button>
            </div>

            <div class="sv-table-wrap sv-animate-in">
                <div class="sv-filter-bar">
                    <input type="search" class="sv-filter-search" id="searchInput" placeholder="Cari Kode atau Nama...">
                    <select class="sv-filter-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        <option value="lansia">Lansia</option>
                        <option value="hipertensi">Hipertensi</option>
                        <option value="diabetes">Diabetes</option>
                        <option value="pasca rawat">Pasca Rawat</option>
                    </select>
                    <select class="sv-filter-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="stable">Stabil</option>
                        <option value="control">Perlu Kontrol</option>
                        <option value="referral">Kritis</option>
                    </select>
                    <button type="button" class="sv-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter Lanjutan
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0" id="patientTable">
                        <thead>
                            <tr>
                                <th>Kode Pasien</th>
                                <th>Nama Pasien</th>
                                <th>NIK</th>
                                <th>Tgl Lahir</th>
                                <th>JK</th>
                                <th>Alamat</th>
                                <th>HP Keluarga</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="patientBody">
                            <?php if (empty($patients)): ?>
                                <tr>
                                    <td colspan="10">
                                        <div class="sv-empty-state">
                                            <div class="empty-icon">👥</div>
                                            <p>Belum ada data pasien. <a href="#" data-bs-toggle="modal" data-bs-target="#modalTambahPasien">Tambah pasien pertama →</a></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($patients as $p):
                                    $latestStatus = '';
                                    if (!empty($p['monitorings'])) {
                                        $mons = $p['monitorings'];
                                        usort($mons, fn($a,$b) => strtotime($b['monitoring_date'] ?? '') <=> strtotime($a['monitoring_date'] ?? ''));
                                        $latestStatus = $mons[0]['status'] ?? '';
                                    }
                                    $jk = ($p['gender'] ?? '') === 'Male' ? 'L' : 'P';
                                ?>
                                <tr
                                    data-name="<?= strtolower($p['patient_name'] ?? '') ?>"
                                    data-nik="<?= $p['nik_dummy'] ?? '' ?>"
                                    data-id="<?= strtolower($p['patient_id'] ?? '') ?>"
                                    data-category="<?= strtolower($p['patient_category'] ?? '') ?>"
                                    data-status="<?= strtolower($latestStatus) ?>"
                                >
                                    <td><span class="sv-code-link"><?= htmlspecialchars($p['patient_id'] ?? '-') ?></span></td>
                                    <td style="font-weight:600;"><?= htmlspecialchars($p['patient_name'] ?? '-') ?></td>
                                    <td style="color:#636366;font-size:12.5px;"><?= maskNik($p['nik_dummy'] ?? '-') ?></td>
                                    <td style="font-size:12.5px;"><?= isset($p['datebirth']) ? date('d/m/Y', strtotime($p['datebirth'])) : '-' ?></td>
                                    <td><?= $jk ?></td>
                                    <td style="font-size:12px;max-width:160px;"><?= htmlspecialchars($p['address'] ?? '-') ?></td>
                                    <td style="font-size:12.5px;"><?= htmlspecialchars($p['family_phone'] ?? '-') ?></td>
                                    <td><?= getCategoryBadge($p['patient_category'] ?? '-') ?></td>
                                    <td><?= getStatusBadge($latestStatus) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" style="font-size:11px;margin-right:4px;" data-bs-toggle="modal" data-bs-target="#modalPasien<?= htmlspecialchars($p['patient_id']) ?>">Edit</button>
                                        <a href="edit-pasien.php?id=<?= urlencode($p['patient_id']) ?>" class="btn btn-sm btn-outline-danger" style="font-size:11px;">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="sv-pagination-bar">
                    <div class="sv-pagination-text">Menampilkan 1–<?= count($patients) ?> dari <?= max(count($patients), 24) ?> data · <?= svDummyFooter() ?></div>
                    <div class="sv-pagination-nav">
                        <span class="sv-page-btn disabled">&lt;</span>
                        <a href="#" class="sv-page-btn active">1</a>
                        <a href="#" class="sv-page-btn">2</a>
                        <a href="#" class="sv-page-btn">3</a>
                        <a href="#" class="sv-page-btn">&gt;</a>
                    </div>
                </div>
            </div>

            <?php require_once 'components/admin-footer.php'; ?>
        </div>
    </div>
</div>

<!-- Modal Tambah Pasien Baru -->
<div class="modal fade" id="modalTambahPasien" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" style="font-weight:800;">👤+ Tambah Pasien Baru</h5>
                    <p style="font-size:12px;color:var(--sv-text-muted);margin:4px 0 0;">Pendaftaran pasien baru sistem <?= SV_BRAND_NAME ?></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2 px-3" style="font-size:12.5px;border-radius:10px;">
                    ℹ Pastikan seluruh data yang dimasukkan adalah data dummy/simulasi untuk keperluan pelatihan dan monitoring.
                </div>
                <form action="tambah-pasien.php" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Kode Pasien</label>
                        <input type="text" class="form-control" value="CVP-005" readonly style="background:#F2F4F7;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label">NIK Dummy</label>
                            <span style="font-size:11px;color:var(--sv-text-muted);">16 DIGIT</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Contoh: 3275000000000001" maxlength="16">
                        <small style="color:var(--sv-text-muted);">Gunakan format 16 digit angka simulasi untuk verifikasi sistem.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Kelamin</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="jkL" value="Male" checked>
                            <label class="form-check-label" for="jkL">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="jkP" value="Female">
                            <label class="form-check-label" for="jkP">Perempuan</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="sv-filter-btn" data-bs-dismiss="modal">Batal</button>
                        <a href="tambah-pasien.php" class="btn btn-primary" style="font-weight:600;">💾 Simpan Pasien</a>
                    </div>
                </form>
            </div>
            <div class="text-center pb-3" style="font-size:10px;font-weight:700;color:var(--sv-text-muted);letter-spacing:1px;">
                ( DATA DUMMY / SIMULASI )
            </div>
        </div>
    </div>
</div>

<!-- ── MODALS ── -->
<?php foreach ($patients as $p):
    $mons = $p['monitorings'] ?? [];
    if (!empty($mons)) {
        usort($mons, fn($a,$b) => strtotime($b['monitoring_date'] ?? '') <=> strtotime($a['monitoring_date'] ?? ''));
    }
    $latestStatus = $mons[0]['status'] ?? '';
?>
<div class="modal fade" id="modalPasien<?= htmlspecialchars($p['patient_id']) ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="patient-avatar" style="width:44px;height:44px;font-size:22px;background:#E8F1FF;">
                        <?= ($p['gender'] ?? '') === 'Male' ? '👨' : '👩' ?>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-size:16px;font-weight:700;"><?= htmlspecialchars($p['patient_name'] ?? '') ?></h5>
                        <div style="font-size:12px;color:#8E8E93;"><?= htmlspecialchars($p['patient_id'] ?? '') ?> • <?= getCategoryBadge($p['patient_category'] ?? '') ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- Patient Info Grid -->
                <h6 style="font-size:12px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#8E8E93;margin-bottom:12px;">INFORMASI PASIEN</h6>
                <div class="row g-0" style="border:1px solid #F0F2F5;border-radius:10px;overflow:hidden;margin-bottom:24px;">
                    <div class="col-6">
                        <div class="detail-row px-3">
                            <span class="detail-label">Kode Pasien / No. RM</span>
                            <span class="detail-value" style="color:#007AFF;"><?= htmlspecialchars($p['patient_id'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-row px-3">
                            <span class="detail-label">NIK Dummy</span>
                            <span class="detail-value"><?= htmlspecialchars($p['nik_dummy'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-row px-3">
                            <span class="detail-label">Jenis Kelamin</span>
                            <span class="detail-value"><?= ($p['gender'] ?? '') === 'Male' ? '👨 Laki-laki' : '👩 Perempuan' ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-row px-3">
                            <span class="detail-label">Tanggal Lahir</span>
                            <span class="detail-value"><?= isset($p['datebirth']) ? date('d M Y', strtotime($p['datebirth'])) : '-' ?> (<?= calculateAge($p['datebirth'] ?? '') ?>)</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-row px-3">
                            <span class="detail-label">Kategori Pasien</span>
                            <span class="detail-value"><?= htmlspecialchars($p['patient_category'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-row px-3">
                            <span class="detail-label">No. HP Keluarga</span>
                            <span class="detail-value"><?= htmlspecialchars($p['family_phone'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-row px-3">
                            <span class="detail-label">Alamat Lengkap</span>
                            <span class="detail-value"><?= htmlspecialchars($p['address'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Monitoring History -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 style="font-size:12px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:#8E8E93;margin:0;">RIWAYAT MONITORING KESEHATAN</h6>
                    <a href="tambah-monitoring.php?patient_id=<?= urlencode($p['patient_id']) ?>" class="btn btn-sm btn-primary" style="font-size:12px;">🩺 Catat Monitoring</a>
                </div>

                <?php if (empty($mons)): ?>
                    <div class="sv-empty-state" style="padding:24px;">
                        <div class="empty-icon">🩺</div>
                        <p>Belum ada catatan monitoring untuk pasien ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm monitoring-mini-table">
                            <thead style="background:#F8F9FA;">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tensi Darah</th>
                                    <th>Suhu (°C)</th>
                                    <th>Keluhan</th>
                                    <th>Rekomendasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mons as $mon): ?>
                                <tr>
                                    <td style="white-space:nowrap;">
                                        <?= isset($mon['monitoring_date']) ? date('d M Y', strtotime($mon['monitoring_date'])) : '-' ?>
                                        <div style="font-size:10px;color:#8E8E93;">
                                            <?= isset($mon['monitoring_time']) ? date('H:i', strtotime($mon['monitoring_time'])) . ' WIB' : '' ?>
                                        </div>
                                    </td>
                                    <td style="font-weight:600;"><?= htmlspecialchars($mon['blood_pressure'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($mon['body_temperature'] ?? '-') ?></td>
                                    <td style="max-width:150px;white-space:normal;"><?= htmlspecialchars($mon['symptoms'] ?? '-') ?></td>
                                    <td style="max-width:150px;white-space:normal;"><?= htmlspecialchars($mon['notes'] ?? '-') ?></td>
                                    <td><?= getStatusBadge($mon['status'] ?? '') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="tambah-monitoring.php?patient_id=<?= urlencode($p['patient_id']) ?>" class="btn btn-sm btn-primary">🩺 Catat Monitoring Baru</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live search & filter
    const searchInput    = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter   = document.getElementById('statusFilter');
    const rows           = document.querySelectorAll('#patientBody tr[data-name]');
    const rowCount       = document.getElementById('rowCount');

    function filterTable() {
        const q   = searchInput.value.toLowerCase();
        const cat = categoryFilter.value.toLowerCase();
        const sts = statusFilter.value.toLowerCase();
        let visible = 0;

        rows.forEach(row => {
            const name     = row.dataset.name || '';
            const nik      = row.dataset.nik  || '';
            const id       = row.dataset.id   || '';
            const category = row.dataset.category || '';
            const status   = row.dataset.status   || '';

            const matchQ   = !q   || name.includes(q) || nik.includes(q) || id.includes(q);
            const matchCat = !cat || category.includes(cat);
            const matchSts = !sts || status.includes(sts);

            const show = matchQ && matchCat && matchSts;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        rowCount?.textContent && (rowCount.textContent = visible + ' pasien');
        const fc = document.getElementById('filterCount');
        if (fc) fc.textContent = visible + ' dari ' + rows.length + ' ditampilkan';
    }

    filterTable();
    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
</script>
</body>
</html>
