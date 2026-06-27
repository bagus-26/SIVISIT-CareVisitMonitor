<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'SIVISIT'); ?> — SIVISIT-CareVisitMonitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/sivisit.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('extra-styles'); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>

<body>
    <div class="sv-layout">

        
        <div class="sv-overlay" id="svOverlay" onclick="closeSidebar()"></div>

        
        <div class="sv-sidebar" id="svSidebar">
            <div class="sv-sidebar-brand">
                <div class="sv-sidebar-logo">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="sv-sidebar-brand-name">
                    <strong>SIVISIT</strong>
                    <span>SIVISIT-CareVisitMonitor</span>
                </div>
                
                <button class="sv-sidebar-close d-lg-none" onclick="closeSidebar()" aria-label="Tutup menu">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <?php
                $isAdmin = Auth::user()->role === 'admin';
            ?>

            <nav class="sv-sidebar-nav">
                <?php if($isAdmin): ?>
                <span class="sv-nav-section-label">Menu Utama</span>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
                </a>
                <a href="<?php echo e(route('admin.patients.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.patients.*') ? 'active' : ''); ?>">
                    <i class="bi bi-people nav-icon"></i> Pasien
                </a>
                <a href="<?php echo e(route('admin.staff.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.staff.*') ? 'active' : ''); ?>">
                    <i class="bi bi-person-badge nav-icon"></i> Petugas
                </a>
                <a href="<?php echo e(route('admin.monitorings.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.monitorings.*') ? 'active' : ''); ?>">
                    <i class="bi bi-clipboard2-pulse nav-icon"></i> Kunjungan
                </a>
                <a href="<?php echo e(route('admin.reports.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>">
                    <i class="bi bi-bar-chart nav-icon"></i> Laporan
                </a>
                <a href="<?php echo e(route('admin.settings.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                    <i class="bi bi-gear nav-icon"></i> Pengaturan
                </a>

                <span class="sv-nav-section-label" style="margin-top:12px;">Lainnya</span>
                <a href="<?php echo e(route('admin.rekam-medis.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.rekam-medis.*') ? 'active' : ''); ?>">
                    <i class="bi bi-folder2-open nav-icon"></i> Rekam Medis
                </a>
                <a href="<?php echo e(route('admin.patients.search')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.patients.search') ? 'active' : ''); ?>">
                    <i class="bi bi-search nav-icon"></i> Cari Pasien
                </a>
                <a href="<?php echo e(route('admin.location.map')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.location.map') ? 'active' : ''); ?>">
                    <i class="bi bi-geo-alt nav-icon"></i> Monitoring Lokasi
                </a>
                <?php else: ?>
                <span class="sv-nav-section-label">Menu Petugas</span>
                <a href="<?php echo e(route('admin.monitorings.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.monitorings.*') ? 'active' : ''); ?>">
                    <i class="bi bi-clipboard2-pulse nav-icon"></i> Kunjungan
                </a>
                <a href="<?php echo e(route('admin.monitorings.create')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.monitorings.create') ? 'active' : ''); ?>">
                    <i class="bi bi-pencil-square nav-icon"></i> Catat Monitoring
                </a>
                <a href="<?php echo e(route('admin.patients.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.patients.*') ? 'active' : ''); ?>">
                    <i class="bi bi-people nav-icon"></i> Pasien
                </a>
                <a href="<?php echo e(route('admin.rekam-medis.index')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.rekam-medis.*') ? 'active' : ''); ?>">
                    <i class="bi bi-folder2-open nav-icon"></i> Rekam Medis
                </a>
                <a href="<?php echo e(route('admin.patients.search')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.patients.search') ? 'active' : ''); ?>">
                    <i class="bi bi-search nav-icon"></i> Cari Pasien
                </a>
                <a href="<?php echo e(route('admin.location.saya')); ?>"
                    class="sv-nav-link <?php echo e(request()->routeIs('admin.location.saya') ? 'active' : ''); ?>">
                    <i class="bi bi-geo-alt nav-icon"></i> Lokasi Saya
                </a>
                <?php endif; ?>
            </nav>
            <div class="sv-sidebar-footer">
                <a href="<?php echo e(route('admin.profil')); ?>"
                    class="sv-sidebar-profile text-decoration-none <?php echo e(request()->routeIs('admin.profil') ? 'active' : ''); ?>">
                    <div class="sv-avatar" style="width:32px;height:32px;font-size:12px;">
                        <?php echo e(strtoupper(substr(Auth::user()->name ?? 'P', 0, 1))); ?>

                    </div>
                    <div style="overflow:hidden;flex:1;">
                        <div style="font-size:12px;font-weight:600;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            <?php echo e(Auth::user()->name ?? 'Petugas'); ?>

                        </div>
                        <div style="font-size:10px;color:var(--sv-sidebar-txt);opacity:.7;">
                            <?php echo e(Auth::user()->email ?? ''); ?>

                        </div>
                        <div style="font-size:9px;color:var(--sv-sidebar-txt);opacity:.5;margin-top:1px;">
                            <?php echo e($isAdmin ? 'Administrator' : 'Petugas'); ?>

                        </div>
                    </div>
                    <i class="bi bi-pencil" style="font-size:10px;color:var(--sv-sidebar-txt);opacity:.5;"></i>
                </a>
                <a href="<?php echo e(route('logout')); ?>" class="sv-logout-btn">
                    <i class="bi bi-box-arrow-right nav-icon"></i> Keluar
                </a>
            </div>
        </div>

        
        <div class="sv-main">
            
            <div class="sv-topbar">
                
                <button class="sv-hamburger d-lg-none" id="svHamburger" onclick="openSidebar()" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="sv-topbar-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="globalSearchInput" placeholder="Cari pasien, NIK, atau kode pasien..."
                        autocomplete="off" value="<?php echo e(request('q')); ?>">
                </div>
                <div class="sv-topbar-right">
                    <a href="<?php echo e(route('admin.profil')); ?>" class="sv-user-info text-decoration-none">
                        <div class="user-text d-none d-sm-block">
                            <div class="user-name"><?php echo e(Auth::user()->name ?? 'Petugas'); ?></div>
                            <div class="user-role"><?php echo e($isAdmin ? 'Administrator' : 'Petugas'); ?></div>
                        </div>
                        <div class="sv-avatar"><?php echo e(strtoupper(substr(Auth::user()->name ?? 'P', 0, 1))); ?></div>
                    </a>
                </div>
            </div>

            
            <div class="sv-content">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            
            <footer class="sv-footer">
                <span>Sivisit-CareVisitMonitor Kelompok 9 Pemrograman Web S1 Informatika ITSK Soepraoen Malang</span>
                <span style="font-style:italic;color:#8E8E93;">Data bersifat simulasi/dummy. Bukan diagnosis medis.</span>
            </footer>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global search redirect
        const gsi = document.getElementById('globalSearchInput');
        if (gsi) {
            gsi.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && this.value.trim()) {
                    window.location.href = '<?php echo e(route("admin.patients.search")); ?>?q=' + encodeURIComponent(this.value.trim());
                }
            });
        }

        // Sidebar mobile toggle
        function openSidebar() {
            document.getElementById('svSidebar').classList.add('open');
            document.getElementById('svOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('svSidebar').classList.remove('open');
            document.getElementById('svOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        // Auto-close sidebar on nav link click (mobile)
        document.querySelectorAll('.sv-nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 991) {
                    closeSidebar();
                }
            });
        });
        // Close sidebar on window resize past breakpoint
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                closeSidebar();
            }
        });
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\layouts\app.blade.php ENDPATH**/ ?>