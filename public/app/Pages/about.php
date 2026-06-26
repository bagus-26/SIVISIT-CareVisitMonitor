<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require '../config.php';
require_once 'components/ui-config.php';

$activeTab = $_GET['tab'] ?? 'tentang';

$systemFlowSteps = [
    [
        'id' => 1,
        'title' => 'Kunjungan & Input Log',
        'summary' => 'Petugas lapangan menginput log kunjungan melalui aplikasi terminal atau dashboard resmi.',
        'detail' => 'Data tanda vital, geotag kunjungan, dan catatan klinis ringkas dicatat langsung saat petugas berada di lokasi pasien.',
        'keywords' => 'kunjungan input log petugas lapangan dashboard terminal',
        'icon' => '📋',
    ],
    [
        'id' => 2,
        'title' => 'Rekapitulasi & Validasi',
        'summary' => 'Sistem sivisit memvalidasi data berdasarkan parameter instansi terkait.',
        'detail' => 'Algoritma validasi mengecek kelengkapan formulir, konsistensi tanda vital, dan status pasien sebelum data direkapitulasi.',
        'keywords' => 'rekapitulasi validasi sivisit parameter instansi',
        'icon' => '✅',
    ],
    [
        'id' => 3,
        'title' => 'Akses Publik & Unduh',
        'summary' => 'Dashboard tersedia untuk pemantauan keluarga dan laporan rekapitulasi administratif.',
        'detail' => 'Keluarga dapat mencari riwayat via kode pasien atau NIK, lalu mengunduh laporan PDF untuk arsip administratif.',
        'keywords' => 'akses publik unduh dashboard keluarga laporan pdf',
        'icon' => '🔍',
    ],
];

$systemFlowBadges = [
    ['label' => 'Sertifikasi Valid/Aman', 'search' => 'validasi'],
    ['label' => 'Tipe Data Simulasi', 'search' => 'dashboard'],
    ['label' => 'Skala Akses Desktop', 'search' => 'unduh'],
];

$flowSearchQuery = trim($_GET['q'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami — <?= SV_BRAND_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="globals.css" rel="stylesheet">
    <link href="landing.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sv-blue: #007AFF;
            --sv-blue-dark: #0058D0;
            --sv-blue-light: #E8F1FF;
            --sv-navy: #001A42;
            --sv-navy-mid: #002866;
            --sv-bg: #F4F6F9;
            --sv-surface: #FFFFFF;
            --sv-border: #E8ECF0;
            --sv-text-main: #1C1C1E;
            --sv-text-sub: #636366;
            --sv-text-muted: #8E8E93;
            --sv-radius: 14px;
            --sv-radius-lg: 20px;
            --sv-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            --sv-shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.08);
            --sv-transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--sv-bg);
            color: var(--sv-text-main);
            margin: 0; padding: 0;
            padding-top: 68px; /* For fixed navbar */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ── */
        .sv-navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0 32px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .burger-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: background 0.2s;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .burger-btn:hover { background: rgba(0,0,0,0.05); }
        .burger-btn span {
            display: block;
            width: 20px;
            height: 2.5px;
            background: var(--sv-text-main);
            border-radius: 2px;
            transition: 0.25s;
        }
        .burger-btn.active span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
        .burger-btn.active span:nth-child(2) { opacity: 0; }
        .burger-btn.active span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 68px;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--sv-border);
            padding: 12px;
            z-index: 999;
            flex-direction: column;
            gap: 4px;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--sv-text-main);
            font-weight: 500;
            font-size: 15px;
            transition: 0.2s;
        }
        .mobile-menu a:hover { background: var(--sv-bg); }

        .sv-navbar-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sv-navbar-links a {
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            color: var(--sv-text-sub);
            padding: 8px 16px;
            border-radius: 8px;
            transition: var(--sv-transition);
        }

        .sv-navbar-links a:hover, .sv-navbar-links a.active {
            color: var(--sv-blue);
            background: rgba(0, 122, 255, 0.06);
        }

        .btn-sv-primary {
            background: var(--sv-blue);
            color: white !important;
            border-radius: 10px;
            padding: 9px 22px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            box-shadow: 0 4px 14px rgba(0, 122, 255, 0.2);
            transition: var(--sv-transition);
            border: none;
        }

        .btn-sv-primary:hover {
            background: var(--sv-blue-dark) !important;
        }

        /* ── Breadcrumbs ── */
        .sv-breadcrumbs {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sv-text-muted);
            margin-bottom: 12px;
        }

        .sv-breadcrumbs a {
            color: var(--sv-text-muted);
            text-decoration: none;
            transition: var(--sv-transition);
        }

        .sv-breadcrumbs a:hover {
            color: var(--sv-blue);
        }

        /* ── Tabs Controls ── */
        .sv-tab-controls {
            display: flex;
            gap: 8px;
            background: rgba(0, 26, 66, 0.04);
            padding: 6px;
            border-radius: 14px;
            display: inline-flex;
            margin-bottom: 30px;
        }

        .sv-tab-btn {
            border: none;
            background: transparent;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            color: var(--sv-text-sub);
            border-radius: 10px;
            cursor: pointer;
            transition: var(--sv-transition);
        }

        .sv-tab-btn.active {
            background: white;
            color: var(--sv-navy);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        /* ── Cards ── */
        .sv-content-card {
            background: white;
            border-radius: var(--sv-radius-lg);
            border: 1px solid var(--sv-border);
            padding: 36px;
            box-shadow: var(--sv-shadow);
            height: 100%;
        }

        .sv-content-card h2 {
            font-size: 26px;
            font-weight: 800;
            color: var(--sv-navy);
            letter-spacing: -0.8px;
            margin-bottom: 24px;
        }

        /* Visi & Misi Items */
        .sv-list-item {
            margin-bottom: 24px;
        }

        .sv-list-item h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--sv-text-main);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sv-list-item h4 .dot {
            width: 8px; height: 8px;
            background: var(--sv-blue);
            border-radius: 50%;
        }

        .sv-list-item p {
            font-size: 13.5px;
            color: var(--sv-text-sub);
            line-height: 1.6;
            margin: 0;
            padding-left: 16px;
        }

        /* Alert Callout */
        .sv-callout-red {
            background: #FFF0EF;
            border: 1px solid #FFD0CC;
            border-radius: 12px;
            padding: 18px;
            color: #C0291F;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.6;
            letter-spacing: 0.2px;
            margin-top: 24px;
        }

        /* Steps */
        .sv-step-flow {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sv-step-card {
            background: var(--sv-bg);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sv-step-number {
            width: 36px; height: 36px;
            background: var(--sv-blue);
            color: white;
            font-size: 16px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sv-step-text h5 {
            font-size: 14px;
            font-weight: 700;
            color: var(--sv-navy);
            margin-bottom: 4px;
        }

        .sv-step-text p {
            font-size: 12.5px;
            color: var(--sv-text-sub);
            margin: 0;
            line-height: 1.5;
        }

        /* ── Interactive System Flow ── */
        .sv-flow-panel {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .sv-flow-panel h2 {
            margin-bottom: 10px;
        }

        .sv-flow-toolbar {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #F8FAFC;
            border: 1px solid var(--sv-border);
            border-radius: 16px;
        }

        .sv-flow-search {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1px solid var(--sv-border);
            border-radius: 12px;
            padding: 11px 14px;
            transition: var(--sv-transition);
        }

        .sv-flow-search:focus-within {
            border-color: var(--sv-blue);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.10);
        }

        .sv-flow-search input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            color: var(--sv-text-main);
            min-width: 0;
        }

        .sv-flow-search input::placeholder {
            color: var(--sv-text-muted);
        }

        .sv-flow-search-icon {
            font-size: 14px;
            opacity: 0.58;
            flex-shrink: 0;
        }

        .sv-flow-clear {
            border: none;
            background: #F2F4F7;
            color: var(--sv-text-muted);
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            transition: var(--sv-transition);
            display: none;
        }

        .sv-flow-clear.visible { display: inline-grid; place-items: center; }
        .sv-flow-clear:hover { background: #E8ECF0; color: var(--sv-text-main); }

        .sv-flow-count {
            justify-self: end;
            white-space: nowrap;
            font-size: 11px;
            font-weight: 800;
            color: var(--sv-blue-dark);
            background: var(--sv-blue-light);
            border: 1px solid rgba(0, 122, 255, 0.14);
            border-radius: 999px;
            padding: 7px 12px;
        }

        .sv-flow-count:empty {
            display: none;
        }

        .sv-flow-steps {
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
        }

        .sv-flow-step {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr) auto;
            gap: 14px;
            background: #fff;
            border: 1px solid var(--sv-border);
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: var(--sv-transition);
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 0 rgba(0, 26, 66, 0.02);
        }

        .sv-flow-step::before {
            content: '';
            position: absolute;
            left: 37px;
            top: 60px;
            bottom: -18px;
            width: 2px;
            background: linear-gradient(180deg, rgba(0, 122, 255, 0.22), rgba(0, 122, 255, 0));
            pointer-events: none;
        }

        .sv-flow-step:last-child::before {
            display: none;
        }

        .sv-flow-step:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(0, 26, 66, 0.08);
            border-color: rgba(0, 122, 255, 0.22);
        }

        .sv-flow-step.is-active {
            border-color: rgba(0, 122, 255, 0.42);
            background: linear-gradient(180deg, #FFFFFF 0%, #F7FBFF 100%);
            box-shadow: 0 14px 34px rgba(0, 122, 255, 0.12);
        }

        .sv-flow-step.is-active .sv-flow-step-number {
            background: var(--sv-blue);
            color: #fff;
            box-shadow: 0 8px 18px rgba(0, 122, 255, 0.26);
        }

        .sv-flow-step.is-hidden {
            display: none;
        }

        .sv-flow-step-header {
            display: contents;
        }

        .sv-flow-step-copy {
            grid-column: 2;
            grid-row: 1;
            min-width: 0;
        }

        .sv-flow-step-number {
            grid-column: 1;
            grid-row: 1 / span 2;
            width: 42px;
            height: 42px;
            background: var(--sv-blue-light);
            color: var(--sv-blue-dark);
            font-size: 14px;
            font-weight: 800;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: var(--sv-transition);
            position: relative;
            z-index: 1;
        }

        .sv-flow-step-icon {
            grid-column: 3;
            grid-row: 1;
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: #F2F6FB;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            opacity: 0.82;
        }

        .sv-flow-step-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--sv-navy);
            margin: 0 0 5px;
            letter-spacing: -0.15px;
        }

        .sv-flow-step-summary {
            font-size: 12.8px;
            color: var(--sv-text-sub);
            margin: 0;
            line-height: 1.55;
        }

        .sv-flow-step-detail {
            grid-column: 2 / span 2;
            grid-row: 2;
            font-size: 12.5px;
            color: var(--sv-text-muted);
            line-height: 1.6;
            margin: 0;
            padding-top: 12px;
            border-top: 1px solid rgba(216, 220, 230, 0.8);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.25s ease, padding 0.25s ease;
        }

        .sv-flow-step.is-active .sv-flow-step-detail {
            max-height: 140px;
            opacity: 1;
            padding-top: 12px;
        }

        .sv-flow-step mark {
            background: #FFF3B0;
            color: inherit;
            padding: 0 2px;
            border-radius: 3px;
        }

        .sv-flow-connector {
            display: none;
        }

        .sv-flow-empty {
            display: none;
            text-align: center;
            padding: 28px 16px;
            background: var(--sv-bg);
            border-radius: 12px;
            color: var(--sv-text-muted);
            font-size: 13px;
        }

        .sv-flow-empty.visible { display: block; }

        .sv-badge-row--interactive {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--sv-border);
        }

        .sv-badge--clickable {
            cursor: pointer;
            transition: var(--sv-transition);
            user-select: none;
        }

        .sv-badge--clickable:hover {
            border-color: var(--sv-blue);
            color: var(--sv-blue-dark);
            background: #fff;
        }

        .sv-badge--clickable.is-filter-active {
            background: var(--sv-blue);
            color: #fff;
            border-color: var(--sv-blue);
        }

        .sv-badge--clickable.is-hidden { display: none; }

        /* Badges */
        .sv-badge-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .sv-badge {
            background: #F8FAFC;
            color: var(--sv-text-sub);
            font-size: 11px;
            font-weight: 700;
            padding: 7px 13px;
            border-radius: 999px;
            border: 1px solid var(--sv-border);
        }

        .sv-badge-blue {
            background: var(--sv-blue-light);
            color: var(--sv-blue-dark);
            border-color: rgba(0, 122, 255, 0.15);
        }

        @media (max-width: 576px) {
            .sv-content-card {
                padding: 24px;
            }

            .sv-flow-toolbar {
                grid-template-columns: 1fr;
            }

            .sv-flow-count {
                justify-self: start;
            }

            .sv-flow-step {
                grid-template-columns: 38px minmax(0, 1fr);
                padding: 14px;
            }

            .sv-flow-step-icon {
                display: none;
            }

            .sv-flow-step-detail {
                grid-column: 2;
            }
        }

        /* Guide Interactive Elements */
        .sv-simulated-input {
            background: var(--sv-bg);
            border: 1px solid var(--sv-border);
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            margin: 12px 0;
            max-width: 240px;
        }

        .btn-simulated-download {
            background: var(--sv-bg);
            color: var(--sv-text-main);
            border: 1px solid var(--sv-border);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--sv-transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        .btn-simulated-download:hover {
            background: #E8ECF0;
        }

        /* ── Footer ── */
        .sv-footer {
            background: #090E1A;
            color: rgba(255, 255, 255, 0.45);
            padding: 40px 32px;
            font-size: 13px;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: auto;
        }

        .sv-footer-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
            gap: 16px;
        }

        .sv-footer-links {
            display: flex;
            gap: 20px;
        }

        .sv-footer-links a {
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            transition: var(--sv-transition);
        }

        .sv-footer-links a:hover {
            color: white;
        }
    </style>
</head>
<body>

    <!-- ════ NAVBAR ════ -->
    <nav class="sv-navbar">
        <button class="burger-btn" id="burgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="sv-navbar-links">
            <a href="../index.php">Beranda</a>
            <a href="about.php" class="active">Tentang Kami</a>
            <a href="jadwal.php">Cek Jadwal</a>
            <a href="#kontak">Kontak</a>
            <a href="jadwal.php" class="btn-sv-primary ms-3">Cek Jadwal</a>
        </div>
    </nav>
    <div class="mobile-menu" id="mobileMenu">
        <a href="../index.php">Beranda</a>
        <a href="about.php">Tentang Kami</a>
        <a href="jadwal.php">Cek Jadwal</a>
        <a href="#kontak">Kontak</a>
        <a href="jadwal.php">Cek Jadwal</a>
    </div>

    <!-- ════ MAIN CONTAINER ════ -->
    <div class="container py-5">
        
        <!-- Breadcrumbs -->
        <div class="sv-breadcrumbs">
            <a href="../index.php">Beranda</a> &gt; 
            <a href="about.php">Tentang Kami</a>
            <?php if ($activeTab === 'panduan'): ?>
                &gt; <span style="color: var(--sv-blue);">Panduan Pengguna Sistem</span>
            <?php endif; ?>
        </div>

        <!-- Tab Selector -->
        <div class="sv-tab-controls">
            <button class="sv-tab-btn <?= $activeTab === 'tentang' ? 'active' : '' ?>" onclick="window.location.href='about.php?tab=tentang'">
                Tentang sivisit
            </button>
            <button class="sv-tab-btn <?= $activeTab === 'panduan' ? 'active' : '' ?>" onclick="window.location.href='about.php?tab=panduan'">
                Panduan Pengguna Sistem
            </button>
        </div>

        <!-- Tab Content 1: Tentang Kami -->
        <?php if ($activeTab === 'tentang'): ?>
            <div class="text-center mb-5 sv-animate-in">
                <span class="d-inline-block px-3 py-1 mb-3" style="background:#EFF6FF;color:#2563EB;border-radius:20px;font-size:12px;font-weight:600;">Mengenal Lebih Dekat</span>
                <h1 style="font-size:28px;font-weight:800;margin-bottom:12px;">Tentang sivisit</h1>
                <p style="max-width:640px;margin:0 auto 28px;color:var(--sv-text-sub);font-size:15px;line-height:1.7;">
                    Solusi administrasi kesehatan modern yang dirancang untuk efisiensi pemantauan kunjungan pasien dan pengelolaan data klinis secara real-time.
                </p>
                <img src="<?= SV_ABOUT_HERO_IMAGE ?>" alt="Workspace monitoring klinis sivisit" style="width:100%;max-width:900px;border-radius:16px;box-shadow:var(--sv-shadow-lg);object-fit:cover;max-height:420px;">
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="sv-content-card">
                        <h2>Tentang sivisit</h2>
                        <p style="font-size: 14.5px; color: var(--sv-text-sub); line-height: 1.7; margin-bottom: 30px;">
                            Mengenal sistem monitoring terpadu yang menjembatani transparansi pelaporan administratif antara petugas lapangan, institusi kesehatan, dan keluarga pasien.
                        </p>

                        <div class="sv-list-item">
                            <h4><div class="dot"></div> Visi & Misi Kami</h4>
                        </div>

                        <div class="sv-list-item">
                            <h4><div class="dot"></div> Transparansi Pelaporan</h4>
                            <p>Memastikan setiap kunjungan tercatat secara real-time untuk audit yang objektif dan transparan.</p>
                        </div>

                        <div class="sv-list-item">
                            <h4><div class="dot"></div> Efisiensi Administratif</h4>
                            <p>Reduksi beban kerja manual petugas lapangan melalui otomatisasi rekapitulasi data klinis sederhana.</p>
                        </div>

                        <div class="sv-list-item">
                            <h4><div class="dot"></div> Akurasi Data Kunjungan</h4>
                            <p>Verifikasi geografis dan stempel waktu digital untuk validasi operasional yang akuntabel.</p>
                        </div>

                        <div class="sv-callout-red">
                            ⚠️ PENTING: PLATFORM INI DIKEMBANGKAN KHUSUS SEBAGAI LAYANAN MONITORING ADMINISTRATIF DAN SIMULASI DATA, BUKAN UNTUK MEMBERIKAN LAYANAN DIAGNOSIS MEDIS DARURAT MANDIRI.
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="sv-content-card d-flex flex-column justify-content-between">
                        <div class="sv-flow-panel" id="systemFlowPanel">
                            <div>
                                <h2>Alur Sistem sivisit</h2>
                                <p style="font-size: 14.5px; color: var(--sv-text-sub); line-height: 1.7; margin-bottom: 20px;">
                                    Tiga fase terintegrasi yang menjamin akurasi dan kemudahan penyampaian hasil rekapitulasi data home care.
                                </p>
                            </div>

                            <div class="sv-flow-toolbar">
                                <label class="sv-flow-search" for="flowSearchInput">
                                    <span class="sv-flow-search-icon" aria-hidden="true">🔍</span>
                                    <input
                                        type="search"
                                        id="flowSearchInput"
                                        placeholder="Cari fase alur, misal: validasi, unduh, dashboard..."
                                        value="<?= htmlspecialchars($flowSearchQuery) ?>"
                                        autocomplete="off"
                                    >
                                    <button type="button" class="sv-flow-clear" id="flowSearchClear" aria-label="Hapus pencarian">✕</button>
                                </label>
                                <span class="sv-flow-count" id="flowResultCount"></span>
                            </div>

                            <div class="sv-flow-steps sv-flow-steps--interactive" id="flowStepsList" role="list">
                                <?php foreach ($systemFlowSteps as $index => $step): ?>
                                    <article
                                        class="sv-flow-step<?= $index === 0 ? ' is-active' : '' ?>"
                                        role="listitem"
                                        tabindex="0"
                                        data-keywords="<?= htmlspecialchars(strtolower($step['keywords'] . ' ' . $step['title'] . ' ' . $step['summary'] . ' ' . $step['detail'])) ?>"
                                        data-step="<?= (int) $step['id'] ?>"
                                        aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>"
                                    >
                                        <div class="sv-flow-step-header">
                                            <div class="sv-flow-step-number"><?= (int) $step['id'] ?></div>
                                            <div class="sv-flow-step-copy">
                                                <h5 class="sv-flow-step-title" data-field="title"><?= htmlspecialchars($step['title']) ?></h5>
                                                <p class="sv-flow-step-summary" data-field="summary"><?= htmlspecialchars($step['summary']) ?></p>
                                            </div>
                                            <span class="sv-flow-step-icon" aria-hidden="true"><?= $step['icon'] ?></span>
                                        </div>
                                        <p class="sv-flow-step-detail" data-field="detail"><?= htmlspecialchars($step['detail']) ?></p>
                                    </article>
                                <?php endforeach; ?>
                            </div>

                            <div class="sv-flow-empty" id="flowEmptyState">
                                Tidak ada fase yang cocok. Coba kata kunci lain seperti <strong>validasi</strong>, <strong>dashboard</strong>, atau <strong>unduh</strong>.
                            </div>
                        </div>

                        <div>
                            <div class="sv-badge-row sv-badge-row--interactive" id="flowBadgeRow">
                                <?php foreach ($systemFlowBadges as $i => $badge): ?>
                                    <span
                                        class="sv-badge sv-badge--clickable<?= $i === 0 ? ' sv-badge-blue' : '' ?>"
                                        data-search="<?= htmlspecialchars($badge['search']) ?>"
                                        role="button"
                                        tabindex="0"
                                    ><?= htmlspecialchars($badge['label']) ?></span>
                                <?php endforeach; ?>
                            </div>

                            <a href="about.php?tab=panduan" class="btn btn-sv-primary w-100 text-center py-3 mt-4">
                                Pelajari Panduan Penggunaan Sistem →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Tab Content 2: Panduan Pengguna -->
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="sv-content-card">
                        <h2>Panduan Pengguna Sistem sivisit</h2>
                        <p style="font-size: 14.5px; color: var(--sv-text-sub); line-height: 1.7; margin-bottom: 30px;">
                            Pelajari langkah operasional penggunaan platform pelaporan administratif untuk memastikan transparansi pemantauan pasien antara petugas lapangan dan keluarga.
                        </p>

                        <div class="sv-list-item">
                            <h4 style="font-size: 16px; color: var(--sv-navy);"><div class="dot"></div> Panduan untuk Keluarga Pasien</h4>
                        </div>

                        <div class="sv-step-flow mt-3">
                            <div class="sv-step-card" style="background: white; border: 1px solid var(--sv-border);">
                                <div class="sv-step-number" style="background:#E5F0FF; color:var(--sv-blue);">1</div>
                                <div class="sv-step-text">
                                    <h5>Akses Menu Pencarian</h5>
                                    <p>Navigasi ke halaman utama dan pilih menu "Cek Jadwal" atau gunakan kotak pencarian global di sudut kanan atas layout.</p>
                                </div>
                            </div>
                            <div class="sv-step-card" style="background: white; border: 1px solid var(--sv-border);">
                                <div class="sv-step-number" style="background:#E5F0FF; color:var(--sv-blue);">2</div>
                                <div class="sv-step-text">
                                    <h5>Masukkan Kode Unik Pasien</h5>
                                    <p>Ketik kode pasien yang telah diberikan oleh pihak administrasi rumah sakit.</p>
                                    <div class="sv-simulated-input">
                                        <span>🔑 PS003</span>
                                        <span style="color:var(--sv-blue);font-weight:700;font-size:10px;">VALID</span>
                                    </div>
                                </div>
                            </div>
                            <div class="sv-step-card" style="background: white; border: 1px solid var(--sv-border);">
                                <div class="sv-step-number" style="background:#E5F0FF; color:var(--sv-blue);">3</div>
                                <div class="sv-step-text">
                                    <h5>Tinjau Riwayat & Unduh Berkas</h5>
                                    <p>Periksa log kunjungan terbaru. Anda dapat mengunduh laporan detail dalam format PDF untuk arsip pribadi.</p>
                                    <button class="btn-simulated-download">💾 Unduh Laporan PDF</button>
                                </div>
                            </div>
                        </div>

                        <div class="sv-callout-red" style="font-size:11px;">
                            ⚠️ PENTING: Panduan ini disusun untuk mempermudah navigasi administratif sistem. Jika terjadi kendala teknis pada validasi kode pasien, segera hubungi pihak instansi kesehatan terkait.
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="sv-content-card d-flex flex-column justify-content-between">
                        <div>
                            <h2>Alur Operasional Petugas Lapangan</h2>
                            <p style="font-size: 14.5px; color: var(--sv-text-sub); line-height: 1.7; margin-bottom: 30px;">
                                Tata cara pencatatan rekam medis sederhana untuk memastikan integritas dan sinkronisasi data real-time.
                            </p>

                            <div class="sv-step-flow">
                                <div class="sv-step-card">
                                    <div class="sv-step-number">1</div>
                                    <div class="sv-step-text">
                                        <h5>Input Log Lapangan</h5>
                                        <p>Petugas lapangan memasukkan data pemeriksaan real-time ke dalam sistem melalui perangkat mobile, termasuk tanda vital dasar dan catatan klinis ringkas.</p>
                                    </div>
                                </div>
                                <div class="sv-step-card">
                                    <div class="sv-step-number">2</div>
                                    <div class="sv-step-text">
                                        <h5>Sinkronisasi Data</h5>
                                        <p>Sistem secara otomatis mengenkripsi (AES-256) dan menyinkronkan data ke server pusat sivisit untuk divalidasi oleh sistem pakar.</p>
                                    </div>
                                </div>
                                <div class="sv-step-card">
                                    <div class="sv-step-number">3</div>
                                    <div class="sv-step-text">
                                        <h5>Penerbitan Rekomendasi</h5>
                                        <p>Setelah divalidasi, sistem menerbitkan ringkasan status dan rekomendasi tindakan lanjut yang dapat diakses langsung oleh keluarga melalui portal pencarian.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="sv-badge-row">
                                <span class="sv-badge sv-badge-blue">Stabil/Aman</span>
                                <span class="sv-badge">Akses Publik</span>
                                <span class="sv-badge">Format Digital</span>
                            </div>

                            <p style="font-size: 12px; color: var(--sv-text-muted); margin-top: 24px; display: flex; align-items: center; gap: 6px;">
                                💻 Sistem ini dioptimalkan untuk akses desktop guna kenyamanan pemantauan data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- ════ FOOTER ════ -->
    <footer class="sv-footer" id="kontak">
        <div class="sv-footer-container">
            <div>
                Sivisit-Kelompok 9 S1 Informatika UAS Pemrograman WEB ITSK Rs Dr Soepraoen Malang — Data simulasi, bukan diagnosis medis.
            </div>
            <div class="sv-footer-links">
                <a href="#accessibility">Accessibility</a>
                <a href="#privacy">Privacy Policy</a>
                <a href="#terms">Terms of Service</a>
                <a href="#security">Security Disclosure</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('burgerBtn')?.addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('mobileMenu')?.classList.toggle('open');
        });

        (function initSystemFlow() {
            const panel = document.getElementById('systemFlowPanel');
            if (!panel) return;

            const searchInput = document.getElementById('flowSearchInput');
            const clearBtn = document.getElementById('flowSearchClear');
            const countEl = document.getElementById('flowResultCount');
            const emptyEl = document.getElementById('flowEmptyState');
            const steps = Array.from(panel.querySelectorAll('.sv-flow-step'));
            const badges = Array.from(document.querySelectorAll('#flowBadgeRow .sv-badge--clickable'));

            let activeBadgeSearch = '';

            function escapeRegExp(str) {
                return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            function highlightText(el, query) {
                const original = el.dataset.original || el.textContent;
                if (!el.dataset.original) el.dataset.original = original;

                if (!query) {
                    el.textContent = original;
                    return;
                }

                const regex = new RegExp('(' + escapeRegExp(query) + ')', 'gi');
                el.innerHTML = original.replace(regex, '<mark>$1</mark>');
            }

            function setActiveStep(stepEl) {
                steps.forEach(s => {
                    s.classList.remove('is-active');
                    s.setAttribute('aria-expanded', 'false');
                });
                stepEl.classList.add('is-active');
                stepEl.setAttribute('aria-expanded', 'true');
            }

            function applyFilters() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                let visible = 0;

                clearBtn?.classList.toggle('visible', query.length > 0);

                steps.forEach(step => {
                    const keywords = step.dataset.keywords || '';
                    const show = !query || keywords.includes(query);

                    step.classList.toggle('is-hidden', !show);
                    if (show) {
                        visible++;
                        step.querySelectorAll('[data-field]').forEach(field => highlightText(field, query));
                    }
                });

                if (countEl) {
                    countEl.textContent = query
                        ? visible + ' dari ' + steps.length + ' fase'
                        : steps.length + ' fase alur';
                }

                emptyEl?.classList.toggle('visible', visible === 0);

                const firstVisible = steps.find(s => !s.classList.contains('is-hidden'));
                if (firstVisible && !steps.some(s => s.classList.contains('is-active') && !s.classList.contains('is-hidden'))) {
                    setActiveStep(firstVisible);
                }
            }

            steps.forEach(step => {
                step.addEventListener('click', () => {
                    if (!step.classList.contains('is-hidden')) setActiveStep(step);
                });
                step.addEventListener('keydown', e => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        if (!step.classList.contains('is-hidden')) setActiveStep(step);
                    }
                });
            });

            badges.forEach(badge => {
                const toggleBadge = () => {
                    const term = badge.dataset.search || '';
                    if (activeBadgeSearch === term) {
                        activeBadgeSearch = '';
                        badge.classList.remove('is-filter-active');
                        if (searchInput) searchInput.value = '';
                    } else {
                        badges.forEach(b => b.classList.remove('is-filter-active'));
                        activeBadgeSearch = term;
                        badge.classList.add('is-filter-active');
                        if (searchInput) searchInput.value = term;
                    }
                    applyFilters();
                };
                badge.addEventListener('click', toggleBadge);
                badge.addEventListener('keydown', e => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleBadge();
                    }
                });
            });

            searchInput?.addEventListener('input', () => {
                const val = (searchInput.value || '').trim().toLowerCase();
                badges.forEach(b => {
                    b.classList.toggle('is-filter-active', (b.dataset.search || '') === val);
                });
                activeBadgeSearch = badges.find(b => b.classList.contains('is-filter-active'))?.dataset.search || '';
                applyFilters();
            });
            clearBtn?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                activeBadgeSearch = '';
                badges.forEach(b => b.classList.remove('is-filter-active'));
                applyFilters();
                searchInput?.focus();
            });

            applyFilters();
        })();
    </script>
</body>
</html>
