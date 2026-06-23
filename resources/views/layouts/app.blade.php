<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIVISIT') — SIVISIT-CareVisitMonitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sivisit.css') }}" rel="stylesheet">
    @yield('extra-styles')
    @yield('head')
</head>

<body>
    <div class="sv-layout">

        {{-- OVERLAY (mobile) --}}
        <div class="sv-overlay" id="svOverlay" onclick="closeSidebar()"></div>

        {{-- SIDEBAR --}}
        <div class="sv-sidebar" id="svSidebar">
            <div class="sv-sidebar-brand">
                <div class="sv-sidebar-logo">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="sv-sidebar-brand-name">
                    <strong>SIVISIT</strong>
                    <span>SIVISIT-CareVisitMonitor</span>
                </div>
                {{-- close button on mobile --}}
                <button class="sv-sidebar-close d-lg-none" onclick="closeSidebar()" aria-label="Tutup menu">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <nav class="sv-sidebar-nav">
                <span class="sv-nav-section-label">Menu Utama</span>
                <a href="{{ route('admin.dashboard') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
                </a>
                <a href="{{ route('admin.patients.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.index') ? 'active' : '' }}">
                    <i class="bi bi-people nav-icon"></i> Daftar Pasien
                </a>
                <a href="{{ route('admin.patients.create') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.create') ? 'active' : '' }}">
                    <i class="bi bi-person-plus nav-icon"></i> Tambah Pasien
                </a>
                <a href="{{ route('admin.monitorings.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.index') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse nav-icon"></i> Data Monitoring
                </a>
                <a href="{{ route('admin.monitorings.create') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.create') || request()->routeIs('admin.monitorings.show') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square nav-icon"></i> Catat Monitoring
                </a>
                <a href="{{ route('admin.rekam-medis.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.rekam-medis.*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open nav-icon"></i> Rekam Medis
                </a>
                <a href="{{ route('admin.patients.search') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.search') ? 'active' : '' }}">
                    <i class="bi bi-search nav-icon"></i> Cari Pasien
                </a>

                <span class="sv-nav-section-label" style="margin-top:12px;">Sistem</span>
                <a href="{{ url('/') }}" class="sv-nav-link">
                    <i class="bi bi-globe2 nav-icon"></i> Beranda
                </a>
            </nav>
            <div class="sv-sidebar-footer">
                <a href="{{ route('admin.profil') }}"
                    class="sv-sidebar-profile text-decoration-none {{ request()->routeIs('admin.profil') ? 'active' : '' }}">
                    <div class="sv-avatar" style="width:32px;height:32px;font-size:12px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                    <div style="overflow:hidden;flex:1;">
                        <div style="font-size:12px;font-weight:600;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ Auth::user()->name ?? 'Petugas' }}
                        </div>
                        <div style="font-size:10px;color:var(--sv-sidebar-txt);opacity:.7;">
                            {{ Auth::user()->email ?? '' }}
                        </div>
                    </div>
                    <i class="bi bi-pencil" style="font-size:10px;color:var(--sv-sidebar-txt);opacity:.5;"></i>
                </a>
                <a href="{{ route('logout') }}" class="sv-logout-btn">
                    <i class="bi bi-box-arrow-right nav-icon"></i> Keluar
                </a>
            </div>
        </div>

        {{-- MAIN --}}
        <div class="sv-main">
            {{-- Topbar --}}
            <div class="sv-topbar">
                {{-- Hamburger (mobile) --}}
                <button class="sv-hamburger d-lg-none" id="svHamburger" onclick="openSidebar()" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="sv-topbar-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="globalSearchInput" placeholder="Cari pasien, NIK, atau kode pasien..."
                        autocomplete="off" value="{{ request('q') }}">
                </div>
                <div class="sv-topbar-right">
                    <a href="{{ route('admin.profil') }}" class="sv-user-info text-decoration-none">
                        <div class="user-text d-none d-sm-block">
                            <div class="user-name">{{ Auth::user()->name ?? 'Petugas' }}</div>
                            <div class="user-role">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <div class="sv-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}</div>
                    </a>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="sv-content">
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="sv-footer">
                <span>© 2026 SIVISIT-CareVisitMonitor. Informatika Kesehatan.</span>
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
                    window.location.href = '{{ route("admin.patients.search") }}?q=' + encodeURIComponent(this.value.trim());
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
    </script>
    @yield('scripts')
</body>

</html>