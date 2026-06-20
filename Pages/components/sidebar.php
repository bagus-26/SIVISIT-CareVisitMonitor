
<?php
// Auto-detect active page for nav highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
$user = $_SESSION['user'] ?? [];
$userName = htmlspecialchars($user['name'] ?? 'Petugas');
$userRole = htmlspecialchars($user['role'] ?? 'Petugas Kesehatan');
$userInitial = strtoupper(substr($user['name'] ?? 'P', 0, 1));

// Nav items: [href, icon, label, page(s)]
$navItems = [
    ['dashboard.php',         '🏠', 'Dashboard',          ['dashboard.php']],
    ['pasien.php',            '👥', 'Daftar Pasien',       ['pasien.php']],
    ['tambah-pasien.php',     '➕', 'Tambah Pasien',       ['tambah-pasien.php']],
    ['monitoring.php',        '📋', 'Data Monitoring',     ['monitoring.php']],
    ['tambah-monitoring.php', '🩺', 'Catat Monitoring',    ['tambah-monitoring.php']],
    ['cari-pasien.php',       '🔍', 'Cari Pasien',         ['cari-pasien.php']],
];
?>
<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-header" style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">SV</div>
        <div>
            <div style="font-size: 1rem; line-height: 1.2;"><strong>MediAdmin</strong></div>
            <div style="font-size: 0.75rem; font-weight: normal; color: rgba(255,255,255,0.7);">CareVisit Monitor</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-menu">
        <div style="padding: 0.5rem 1.5rem; font-size: 0.75rem; text-transform: uppercase; color: rgba(255,255,255,0.5); letter-spacing: 1px; margin-top: 0.5rem;">Menu Utama</div>
        <?php foreach ($navItems as [$href, $icon, $label, $pages]): ?>
            <?php $isActive = in_array($currentPage, $pages); ?>
            <a href="<?= $href ?>" class="sidebar-item <?= $isActive ? 'active' : '' ?>">
                <span><?= $icon ?></span>
                <?= $label ?>
            </a>
        <?php endforeach; ?>

        <div style="padding: 0.5rem 1.5rem; font-size: 0.75rem; text-transform: uppercase; color: rgba(255,255,255,0.5); letter-spacing: 1px; margin-top: 1.5rem;">Akses Publik</div>
        <a href="../index.php" class="sidebar-item <?= $currentPage === 'index.php' ? 'active' : '' ?>">
            <span>🌐</span>
            Halaman Utama
        </a>
    </nav>

    <!-- Footer / Logout -->
    <div style="padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                <?= $userInitial ?>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 0.875rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: white;"><?= $userName ?></div>
                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.6);"><?= $userRole ?></div>
            </div>
        </div>
        <a href="logout.php" class="sidebar-item" style="padding: 0.5rem; justify-content: center; border-radius: 6px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-left: none;">
            <span>🚪</span> Keluar
        </a>
    </div>
</div>
