<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sivisit — Care Visit Monitor</title>
    <meta name="description" content="Sistem monitoring pasien home care terpadu.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Pages/landing.css?v=2026062403" rel="stylesheet">
    <style>
        .landing-services {
            overflow-x: hidden !important;
        }

        .landing-services-inner {
            max-width: 1200px !important;
        }

        .landing-services-grid {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            gap: 30px !important;
            align-items: stretch !important;
        }

        .landing-service-card {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            height: auto !important;
            overflow: hidden !important;
            border-radius: 20px !important;
        }

        .landing-service-thumb {
            width: 100% !important;
            height: 210px !important;
            overflow: hidden !important;
            position: relative !important;
            display: block !important;
            background: linear-gradient(135deg, #EEF5FF, #F8FAFC) !important;
        }

        .landing-service-thumb--monitoring {
            background: linear-gradient(135deg, #EAF2FF 0%, #F8FBFF 100%) !important;
        }

        .landing-service-thumb--monitoring::before {
            content: '' !important;
            position: absolute !important;
            inset: 32px 42px !important;
            border-radius: 22px !important;
            background: rgba(255, 255, 255, 0.86) !important;
            border: 1px solid rgba(37, 99, 235, 0.14) !important;
            box-shadow: 0 18px 42px rgba(37, 99, 235, 0.12) !important;
        }

        .landing-service-thumb--monitoring::after {
            content: '' !important;
            position: absolute !important;
            left: 92px !important;
            right: 92px !important;
            top: 68px !important;
            height: 92px !important;
            border-radius: 18px !important;
            background:
                linear-gradient(90deg, #2563EB, #60A5FA) 22px 22px / 118px 12px no-repeat,
                linear-gradient(90deg, #D7E6FF, #EEF5FF) 22px 46px / 76% 9px no-repeat,
                linear-gradient(90deg, #D7E6FF, #EEF5FF) 22px 66px / 58% 9px no-repeat,
                linear-gradient(180deg, #93C5FD, #2563EB) calc(100% - 76px) 34px / 12px 42px no-repeat,
                linear-gradient(180deg, #BFDBFE, #3B82F6) calc(100% - 54px) 20px / 12px 56px no-repeat,
                linear-gradient(180deg, #DBEAFE, #60A5FA) calc(100% - 32px) 46px / 12px 30px no-repeat,
                #FFFFFF !important;
            border: 1px solid rgba(37, 99, 235, 0.10) !important;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08) !important;
        }

        .landing-service-thumb img {
            display: block !important;
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            height: 100% !important;
            max-height: none !important;
            object-fit: cover !important;
            object-position: center !important;
            position: static !important;
            inset: auto !important;
            transform: none !important;
        }

        .landing-service-card:hover .landing-service-thumb img {
            transform: scale(1.04) !important;
        }

        .landing-btn-schedule {
            background: linear-gradient(135deg, #2563EB, #007AFF) !important;
            color: #fff !important;
            box-shadow: 0 10px 28px rgba(37, 99, 235, 0.28) !important;
        }

        .landing-btn-schedule:hover {
            background: linear-gradient(135deg, #1D4ED8, #0058D0) !important;
            color: #fff !important;
            transform: translateY(-2px) !important;
        }

        @media (max-width: 992px) {
            .landing-services-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 640px) {
            .landing-services-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body class="landing-page">

<header class="landing-nav">
    <a href="index.php" class="landing-nav-brand">
        <div class="landing-nav-logo">SV</div>
        <span>sivisit</span>
    </a>
    <nav class="landing-nav-links">
        <a href="index.php" class="active">Beranda</a>
        <a href="Pages/about.php">Tentang</a>
        <a href="Pages/jadwal.php">Cek Jadwal</a>
        <a href="#kontak">Kontak</a>
        <a href="Pages/jadwal.php" class="btn-nav-cta">Cek Jadwal</a>
    </nav>
    <button class="landing-burger" id="burgerBtn" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</header>

<div class="landing-mobile-menu" id="mobileMenu">
    <a href="index.php">Beranda</a>
    <a href="Pages/about.php">Tentang Kami</a>
    <a href="Pages/jadwal.php">Cek Jadwal</a>
    <a href="#kontak">Kontak</a>
            <a href="Pages/jadwal.php">Cek Jadwal</a>
</div>

<section class="landing-hero">
    <div class="landing-hero-content">
        <div class="landing-hero-badge">🏥 Platform Home Care Terpadu</div>
        <h1>Monitoring Pasien <em>Home Care</em> yang Transparan</h1>
        <p class="landing-hero-desc">
            Platform digital untuk petugas kesehatan dalam memantau dan mencatat kondisi pasien binaan secara terstruktur, transparan, dan real-time.
        </p>
        <div class="landing-hero-actions">
            <a href="Pages/jadwal.php" class="landing-btn-primary">Cek Jadwal & Riwayat →</a>
            <a href="Pages/about.php" class="landing-btn-outline">Pelajari Lebih Lanjut</a>
        </div>
    </div>
    <div class="landing-hero-visual">
        <div class="landing-hero-illustration">
            <img
                class="landing-hero-photo"
                src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=900&h=700&fit=crop&q=80"
                alt="Petugas kesehatan melakukan kunjungan home care"
                loading="eager"
            >
            <div class="landing-hero-photo-overlay"></div>

            <div class="landing-hero-float-card landing-hero-float-card--top">
                <div class="landing-float-icon">🩺</div>
                <div>
                    <strong>Kunjungan Aktif</strong>
                    <span>8 petugas di lapangan</span>
                </div>
            </div>

            <div class="landing-hero-float-card landing-hero-float-card--mid">
                <div class="landing-float-chart">
                    <span style="height:40%"></span>
                    <span style="height:70%"></span>
                    <span style="height:55%"></span>
                    <span style="height:90%"></span>
                    <span style="height:65%"></span>
                </div>
                <div>
                    <strong>Monitoring Real-time</strong>
                    <span>Data tersinkron otomatis</span>
                </div>
            </div>

            <div class="landing-illust-screen">
                <div class="landing-illust-stats">
                    <div class="landing-illust-stat"><strong>128</strong><span>Pasien</span></div>
                    <div class="landing-illust-stat"><strong>24</strong><span>Kunjungan</span></div>
                    <div class="landing-illust-stat"><strong>98%</strong><span>Stabil</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="landing-services" id="layanan">
    <div class="landing-services-inner">
        <div class="landing-section-head">
            <h2>Layanan Unggulan</h2>
            <p>Tiga pilar utama yang mendukung operasional monitoring home care secara efisien dan akuntabel.</p>
        </div>
        <div class="landing-services-grid">
            <div class="landing-service-card">
                <div class="landing-service-thumb landing-service-thumb--monitoring" aria-label="Data monitoring pasien"></div>
                <div class="landing-service-icon">📋</div>
                <h3>Data Monitoring</h3>
                <p>Catat dan pantau tanda vital pasien secara berkala dengan formulir terstruktur dan validasi otomatis.</p>
            </div>
            <div class="landing-service-card">
                <div class="landing-service-thumb">
                    <img src="https://images.unsplash.com/photo-1584515933487-779824d29309?w=600&h=360&fit=crop&q=80" alt="Manajemen pasien home care" onerror="this.style.display='none'">
                </div>
                <div class="landing-service-icon">👥</div>
                <h3>Manajemen Pasien</h3>
                <p>Kelola data pasien binaan, riwayat kunjungan, dan status kesehatan terkini dalam satu dashboard.</p>
            </div>
            <div class="landing-service-card">
                <div class="landing-service-thumb">
                    <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=360&fit=crop&q=80" alt="Pencarian riwayat pasien" onerror="this.style.display='none'">
                </div>
                <div class="landing-service-icon">🔍</div>
                <h3>Cari Riwayat</h3>
                <p>Keluarga pasien dapat mencari riwayat monitoring melalui kode pasien atau NIK dummy secara publik.</p>
            </div>
        </div>
    </div>
</section>

<section class="landing-cta-band">
    <h2>Siap Memulai Monitoring?</h2>
    <p>Akses dashboard petugas atau cek jadwal kunjungan pasien Anda sekarang.</p>
    <div class="landing-hero-actions" style="justify-content:center;">
        <a href="Pages/jadwal.php" class="landing-btn-primary">Cek Jadwal & Riwayat →</a>
    </div>
</section>

<footer class="landing-footer" id="kontak">
    <div class="landing-footer-grid">
        <div class="landing-footer-col">
            <h4>sivisit</h4>
            <p>Sistem monitoring pasien home care terpadu untuk transparansi pelaporan administratif.</p>
        </div>
        <div class="landing-footer-col">
            <h4>Navigasi</h4>
            <a href="index.php">Beranda</a>
            <a href="Pages/about.php">Tentang Kami</a>
            <a href="Pages/jadwal.php">Cek Jadwal</a>
            <a href="Pages/jadwal.php">Cek Jadwal</a>
        </div>
        <div class="landing-footer-col">
            <h4>Layanan Publik</h4>
            <a href="Pages/jadwal.php">Pencarian Riwayat Pasien</a>
        </div>
        <div class="landing-footer-col">
            <h4>Kontak</h4>
            <p>support@sivisit.local</p>
            <p>Malang, Indonesia</p>
        </div>
    </div>
    <div class="landing-footer-bottom">
        <span>Sivisit-Kelompok 9 S1 Informatika UAS Pemrograman WEB ITSK Rs Dr Soepraoen Malang — Data simulasi, bukan diagnosis medis.</span>
    </div>
</footer>

<script>
document.getElementById('burgerBtn')?.addEventListener('click', function() {
    this.classList.toggle('active');
    document.getElementById('mobileMenu')?.classList.toggle('open');
});
</script>
</body>
</html>
