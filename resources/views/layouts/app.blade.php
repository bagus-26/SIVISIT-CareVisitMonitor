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
            @php
                $isAdmin = Auth::user()->role === 'admin';
            @endphp

            <nav class="sv-sidebar-nav">
                @if($isAdmin)
                <span class="sv-nav-section-label">Menu Utama</span>
                <a href="{{ route('admin.dashboard') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
                </a>
                <a href="{{ route('admin.patients.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.index') || request()->routeIs('admin.patients.create') || request()->routeIs('admin.patients.edit') || request()->routeIs('admin.patients.update') ? 'active' : '' }}">
                    <i class="bi bi-people nav-icon"></i> Pasien
                </a>
                <a href="{{ route('admin.staff.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge nav-icon"></i> Petugas
                </a>
                <a href="{{ route('admin.monitorings.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse nav-icon"></i> Kunjungan
                </a>
                <a href="{{ route('admin.reports.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart nav-icon"></i> Laporan
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear nav-icon"></i> Pengaturan
                </a>

                <span class="sv-nav-section-label" style="margin-top:12px;">Lainnya</span>
                <a href="{{ route('admin.rekam-medis.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.rekam-medis.*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open nav-icon"></i> Rekam Medis
                </a>
                <a href="{{ route('admin.patients.search') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.search') ? 'active' : '' }}">
                    <i class="bi bi-search nav-icon"></i> Cari Pasien
                </a>
                <a href="{{ route('admin.location.map') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.location.map') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt nav-icon"></i> Monitoring Lokasi
                </a>
                @else
                <span class="sv-nav-section-label">Menu Petugas</span>
                <a href="{{ route('admin.monitorings.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.index') || request()->routeIs('admin.monitorings.show') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse nav-icon"></i> Kunjungan
                </a>
                <a href="{{ route('admin.monitorings.create') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.create') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square nav-icon"></i> Catat Monitoring
                </a>
                <a href="{{ route('admin.patients.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.index') || request()->routeIs('admin.patients.edit') || request()->routeIs('admin.patients.update') ? 'active' : '' }}">
                    <i class="bi bi-people nav-icon"></i> Pasien
                </a>
                <a href="{{ route('admin.rekam-medis.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.rekam-medis.*') ? 'active' : '' }}">
                    <i class="bi bi-folder2-open nav-icon"></i> Rekam Medis
                </a>
                <a href="{{ route('admin.patients.search') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.search') ? 'active' : '' }}">
                    <i class="bi bi-search nav-icon"></i> Cari Pasien
                </a>
                <a href="{{ route('admin.location.saya') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.location.saya') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt nav-icon"></i> Lokasi Saya
                </a>
                @endif
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
                        <div style="font-size:9px;color:var(--sv-sidebar-txt);opacity:.5;margin-top:1px;">
                            {{ $isAdmin ? 'Administrator' : 'Petugas' }}
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
                            <div class="user-role">{{ $isAdmin ? 'Administrator' : 'Petugas' }}</div>
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
    @yield('scripts')
</body>

</html>
