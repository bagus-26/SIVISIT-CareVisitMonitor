
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
    <div class="sidebar-header">
        <div style="width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">🛡️</div>
        <div style="line-height: 1.2;">
            <div style="font-size: 1rem; color: white;"><strong>MediAdmin</strong></div>
            <div style="font-size: 0.75rem; font-weight: 400; color: #cbd5e1;">CareVisit Monitor</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-menu">
        <a href="dashboard.php" class="sidebar-item <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            <span>🔲</span> Dashboard
        </a>
        <a href="pasien.php" class="sidebar-item <?= $currentPage === 'pasien.php' ? 'active' : '' ?>">
            <span>👤</span> Pasien
        </a>
        <a href="petugas.php" class="sidebar-item <?= $currentPage === 'petugas.php' ? 'active' : '' ?>">
            <span>👨‍⚕️</span> Petugas
        </a>
        <a href="kunjungan.php" class="sidebar-item <?= $currentPage === 'kunjungan.php' ? 'active' : '' ?>">
            <span>📅</span> Kunjungan
        </a>
        <a href="laporan.php" class="sidebar-item <?= $currentPage === 'laporan.php' ? 'active' : '' ?>">
            <span>📊</span> Laporan
        </a>
        <a href="pengaturan.php" class="sidebar-item <?= $currentPage === 'pengaturan.php' ? 'active' : '' ?>">
            <span>⚙️</span> Pengaturan
        </a>
    </nav>

    <!-- Footer / Logout -->
    <div style="padding: 1.5rem; margin-top: auto;">
        <a href="logout.php" class="sidebar-item" style="padding: 0.75rem 1rem; color: #cbd5e1;">
            <span>🚪</span> Keluar
        </a>
    </div>
</div>
