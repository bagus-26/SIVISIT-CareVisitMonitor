<?php
require_once __DIR__ . '/ui-config.php';

$currentPage = basename($_SERVER['PHP_SELF']);

$navItems = [
    ['dashboard.php',  '⊞', 'Dashboard',  ['dashboard.php']],
    ['pasien.php',     '👤', 'Pasien',     ['pasien.php', 'edit-pasien.php', 'tambah-pasien.php']],
    ['petugas.php',    '🪪', 'Petugas',    ['petugas.php']],
    ['kunjungan.php',  '📅', 'Kunjungan',  ['kunjungan.php', 'monitoring.php', 'detail-monitoring.php', 'tambah-monitoring.php']],
    ['laporan.php',    '📊', 'Laporan',    ['laporan.php']],
    ['pengaturan.php', '⚙️', 'Pengaturan', ['pengaturan.php']],
];
?>
<div class="sv-sidebar" id="sidebar">
    <div class="sv-sidebar-brand">
        <button class="sv-sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <span></span><span></span><span></span>
        </button>
        <div class="sv-sidebar-logo">SV</div>
        <div class="sv-sidebar-brand-name">
            <strong>sivisit</strong>
            <span>Care Visit Monitor</span>
        </div>
    </div>

    <nav class="sv-sidebar-nav">
        <?php foreach ($navItems as [$href, $icon, $label, $pages]): ?>
            <?php $isActive = in_array($currentPage, $pages, true); ?>
            <a href="<?= $href ?>" class="sv-nav-link <?= $isActive ? 'active' : '' ?>">
                <span class="nav-icon"><?= $icon ?></span>
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sv-sidebar-footer">
        <a href="logout.php" class="sv-logout-btn">
            <span class="nav-icon">⎋</span>
            Keluar
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    toggle?.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('open');
        this.classList.toggle('active');
    });
});
</script>
