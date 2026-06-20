<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIVISIT') — CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/sivisit.css') }}" rel="stylesheet">
    @yield('extra-styles')
    @yield('head')
</head>

<body>
    <div class="sv-layout">

        {{-- SIDEBAR --}}
        <div class="sv-sidebar" id="svSidebar">
            <div class="sv-sidebar-brand">
                <div class="sv-sidebar-logo">SV</div>
                <div class="sv-sidebar-brand-name">
                    <strong>SIVISIT</strong>
                    <span>CareVisit Monitor</span>
                </div>
            </div>
            <nav class="sv-sidebar-nav">
                <span class="sv-nav-section-label">Menu Utama</span>
                <a href="{{ route('admin.dashboard') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
                <a href="{{ route('admin.patients.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> Daftar Pasien
                </a>
                <a href="{{ route('admin.patients.create') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.create') ? 'active' : '' }}">
                    <span class="nav-icon">➕</span> Tambah Pasien
                </a>
                <a href="{{ route('admin.monitorings.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.index') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span> Data Monitoring
                </a>
                <a href="{{ route('admin.monitorings.create') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.monitorings.create') || request()->routeIs('admin.monitorings.show') ? 'active' : '' }}">
                    <span class="nav-icon">🩺</span> Catat Monitoring
                </a>
                <a href="{{ route('admin.rekam-medis.index') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.rekam-medis.*') ? 'active' : '' }}">
                    <span class="nav-icon">📂</span> Rekam Medis
                </a>
                <a href="{{ route('admin.patients.search') }}"
                    class="sv-nav-link {{ request()->routeIs('admin.patients.search') ? 'active' : '' }}">
                    <span class="nav-icon">🔍</span> Cari Pasien
                </a>
                <span class="sv-nav-section-label" style="margin-top:12px;">Akses Publik</span>
                <a href="{{ route('home') }}" class="sv-nav-link">
                    <span class="nav-icon">🌐</span> Halaman Utama
                </a>
            </nav>
            <div class="sv-sidebar-footer">
                <a href="{{ route('admin.profil') }}"
                    class="sv-sidebar-profile text-decoration-none {{ request()->routeIs('admin.profil') ? 'active' : '' }}">
                    <div class="sv-avatar" style="width:32px;height:32px;font-size:12px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                    <div style="overflow:hidden;flex:1;">
                        <div
                            style="font-size:12px;font-weight:600;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ Auth::user()->name ?? 'Petugas' }}
                        </div>
                        <div style="font-size:10px;color:var(--sv-sidebar-txt);opacity:.7;">
                            {{ Auth::user()->email ?? '' }}
                        </div>
                    </div>
                    <span style="font-size:10px;color:var(--sv-sidebar-txt);opacity:.5;">✎</span>
                </a>
                <a href="{{ route('logout') }}" class="sv-logout-btn">
                    <span class="nav-icon">🚪</span> Keluar
                </a>
            </div>
        </div>

        {{-- MAIN --}}
        <div class="sv-main">
            {{-- Topbar --}}
            <div class="sv-topbar">
                <div class="sv-topbar-search">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="globalSearchInput" placeholder="Cari pasien, NIK, atau kode pasien..."
                        autocomplete="off" value="{{ request('q') }}">
                </div>
                <div class="sv-topbar-right">
                    <a href="{{ route('admin.profil') }}" class="sv-user-info text-decoration-none">
                        <div class="user-text">
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
                <span>© 2026 SIVISIT — CareVisit Monitor. Informatika Kesehatan.</span>
                <span style="font-style:italic;">⚠️ Data bersifat simulasi/dummy. Bukan diagnosis medis.</span>
            </footer>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global search redirect
        document.getElementById('globalSearchInput').addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && this.value.trim()) {
                window.location.href = '{{ route("admin.patients.search") }}?q=' + encodeURIComponent(this.value.trim());
            }
        });
    </script>
    @yield('scripts')
</body>

</html>