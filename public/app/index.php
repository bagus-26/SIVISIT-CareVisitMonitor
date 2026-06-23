<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIVISIT — CareVisit Monitor</title>
    <meta name="description" content="Sistem monitoring pasien home care terpadu.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Pages/globals.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f8f9fb;
            color: var(--sv-text-main);
            padding-top: 68px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
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
            margin-right: 16px;
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

        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: auto;
        }
        .nav-links a {
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            color: var(--sv-text-sub);
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.2s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--sv-blue);
            background: rgba(0,122,255,0.06);
        }
        .nav-links .btn-login {
            background: var(--sv-blue);
            color: #fff !important;
            border-radius: 10px;
            padding: 9px 22px !important;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(0,122,255,0.2);
        }
        .nav-links .btn-login:hover { background: var(--sv-blue-dark); }

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

        .hero {
            text-align: center;
            padding: 80px 24px 60px;
            max-width: 760px;
            margin: 0 auto;
        }
        .hero h1 {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1.5px;
            color: var(--sv-navy);
            margin-bottom: 16px;
            line-height: 1.15;
        }
        .hero h1 span { color: var(--sv-blue); }
        .hero p {
            font-size: 17px;
            color: var(--sv-text-sub);
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .hero-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .hero-actions a {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: 0.2s;
        }
        .hero-actions .btn-primary-custom {
            background: var(--sv-blue);
            color: #fff;
            box-shadow: 0 4px 14px rgba(0,122,255,0.25);
        }
        .hero-actions .btn-primary-custom:hover { background: var(--sv-blue-dark); transform: translateY(-1px); }
        .hero-actions .btn-outline-custom {
            border: 1.5px solid var(--sv-border);
            color: var(--sv-text-sub);
            background: #fff;
        }
        .hero-actions .btn-outline-custom:hover { border-color: var(--sv-blue); color: var(--sv-blue); }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto 60px;
            padding: 0 24px;
        }
        .feature-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--sv-border);
            padding: 28px 24px;
            text-align: center;
            transition: 0.2s;
        }
        .feature-card:hover { box-shadow: var(--sv-shadow); transform: translateY(-2px); }
        .feature-card .icon { font-size: 36px; margin-bottom: 12px; }
        .feature-card h3 { font-size: 16px; font-weight: 700; color: var(--sv-navy); margin-bottom: 8px; }
        .feature-card p { font-size: 13px; color: var(--sv-text-sub); line-height: 1.6; margin: 0; }

        .footer {
            background: var(--sv-navy);
            color: rgba(255,255,255,0.5);
            padding: 32px 24px;
            text-align: center;
            font-size: 13px;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .nav-links a:not(.btn-login) { display: none; }
            .hero h1 { font-size: 28px; }
            .hero { padding: 48px 20px 40px; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <button class="burger-btn" id="burgerBtn" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
    <div class="nav-links">
        <a href="index.php" class="active">Beranda</a>
        <a href="Pages/about.php">Tentang</a>
        <a href="Pages/jadwal.php">Cek Jadwal</a>
        <a href="#kontak">Kontak</a>
        <a href="Pages/login.php" class="btn-login">Masuk Admin</a>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="index.php">Beranda</a>
    <a href="Pages/about.php">Tentang Kami</a>
    <a href="Pages/jadwal.php">Cek Jadwal</a>
    <a href="#kontak">Kontak</a>
    <a href="Pages/login.php">Masuk Admin</a>
</div>

<section class="hero">
    <h1>Monitoring Pasien <span>Home Care</span> Terpadu</h1>
    <p>Platform digital untuk petugas kesehatan dalam memantau dan mencatat kondisi pasien binaan secara terstruktur, transparan, dan real-time.</p>
    <div class="hero-actions">
        <a href="Pages/login.php" class="btn-primary-custom">Masuk ke Dashboard</a>
        <a href="Pages/about.php" class="btn-outline-custom">Pelajari Lebih Lanjut</a>
    </div>
</section>

<div class="features">
    <div class="feature-card">
        <div class="icon">📋</div>
        <h3>Data Monitoring</h3>
        <p>Catat dan pantau tanda vital pasien secara berkala dengan formulir terstruktur.</p>
    </div>
    <div class="feature-card">
        <div class="icon">👥</div>
        <h3>Manajemen Pasien</h3>
        <p>Kelola data pasien binaan, riwayat kunjungan, dan status kesehatan terkini.</p>
    </div>
    <div class="feature-card">
        <div class="icon">🔍</div>
        <h3>Cari Riwayat</h3>
        <p>Keluarga pasien dapat mencari riwayat monitoring melalui kode atau NIK pasien.</p>
    </div>
</div>

<footer class="footer" id="kontak">
    <div> 2026 SIVISIT — CareVisit Monitor. Data bersifat simulasi.</div>
</footer>

<script>
    const burger = document.getElementById('burgerBtn');
    const menu = document.getElementById('mobileMenu');
    burger.addEventListener('click', () => {
        burger.classList.toggle('active');
        menu.classList.toggle('open');
    });
</script>
</body>
</html>
