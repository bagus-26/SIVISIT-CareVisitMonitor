<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIVISIT-CareVisitMonitor | Sistem Monitoring Kesehatan Home Care</title>
    <meta name="description" content="SIVISIT-CareVisitMonitor adalah sistem digital pemantauan kondisi kesehatan pasien home care secara real-time. Monitoring tanda vital, rekam medis, dan laporan kunjungan petugas.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body>

{{-- NAVBAR --}}
<nav class="lp-nav" id="lpNav">
    <div class="container-xl">
        <div class="lp-nav-inner">
            <a href="{{ url('/') }}" class="lp-brand">
                <div class="lp-brand-icon">🏥</div>
                <div>
                    <div class="lp-brand-name">SIVISIT</div>
                    <div class="lp-brand-sub">SIVISIT-CareVisitMonitor</div>
                </div>
            </a>
            <div class="lp-nav-links">
                <a href="#fitur" class="lp-nav-link">Fitur</a>
                <a href="#cara-kerja" class="lp-nav-link">Cara Kerja</a>
                <a href="#tentang" class="lp-nav-link">Tentang</a>
            </div>
            <a href="{{ route('login') }}" class="lp-btn-login">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                Masuk Sistem
            </a>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="lp-hero">
    <div class="lp-hero-bg">
        <div class="lp-hero-blob lp-blob-1"></div>
        <div class="lp-hero-blob lp-blob-2"></div>
        <div class="lp-hero-grid"></div>
    </div>
    <div class="container-xl position-relative">
        <div class="row align-items-center" style="min-height:86vh;padding:120px 0 60px;">
            <div class="col-lg-6">
                <div class="lp-hero-badge">
                    <span class="badge-dot"></span>
                    Sistem Aktif — Versi 2026
                </div>
                <h1 class="lp-hero-title">
                    Monitoring <span class="lp-text-gradient">Kesehatan</span><br>Pasien Home Care
                </h1>
                <p class="lp-hero-desc">
                    SIVISIT membantu petugas kesehatan memantau kondisi pasien secara digital — 
                    mencatat tanda vital, riwayat kunjungan, dan status kondisi secara akurat dan efisien.
                </p>
                <div class="lp-hero-cta">
                    <a href="{{ route('login') }}" class="lp-btn-primary">
                        🚀 Mulai Monitoring
                    </a>
                    <a href="#fitur" class="lp-btn-ghost">
                        Pelajari Fitur ↓
                    </a>
                </div>
                <div class="lp-hero-stats">
                    <div class="lp-stat">
                        <div class="lp-stat-val">100%</div>
                        <div class="lp-stat-lbl">Digital</div>
                    </div>
                    <div class="lp-stat-divider"></div>
                    <div class="lp-stat">
                        <div class="lp-stat-val">Real-time</div>
                        <div class="lp-stat-lbl">Monitoring</div>
                    </div>
                    <div class="lp-stat-divider"></div>
                    <div class="lp-stat">
                        <div class="lp-stat-val">Akurat</div>
                        <div class="lp-stat-lbl">Validasi Data</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                <div class="lp-hero-card-wrap">
                    <div class="lp-demo-card lp-demo-card-main">
                        <div class="lp-demo-header">
                            <div class="lp-demo-avatar">👩</div>
                            <div>
                                <div class="lp-demo-name">Siti Rahayu, 67 Thn</div>
                                <div class="lp-demo-id">PSN-2024-001 · Hipertensi</div>
                            </div>
                            <span class="lp-demo-badge stable">✅ Stabil</span>
                        </div>
                        <div class="lp-demo-vitals">
                            <div class="lp-demo-vital">
                                <div class="lp-demo-vital-icon" style="background:#FFF0EF;">❤️</div>
                                <div class="lp-demo-vital-val">120/80</div>
                                <div class="lp-demo-vital-lbl">mmHg</div>
                            </div>
                            <div class="lp-demo-vital">
                                <div class="lp-demo-vital-icon" style="background:#E8F8ED;">🌡️</div>
                                <div class="lp-demo-vital-val">36.5</div>
                                <div class="lp-demo-vital-lbl">°C</div>
                            </div>
                            <div class="lp-demo-vital">
                                <div class="lp-demo-vital-icon" style="background:#E8F1FF;">💓</div>
                                <div class="lp-demo-vital-val">78</div>
                                <div class="lp-demo-vital-lbl">bpm</div>
                            </div>
                            <div class="lp-demo-vital">
                                <div class="lp-demo-vital-icon" style="background:#F5EEFF;">🩸</div>
                                <div class="lp-demo-vital-val">98%</div>
                                <div class="lp-demo-vital-lbl">SpO₂</div>
                            </div>
                        </div>
                        <div class="lp-demo-footer">
                            <span>📅 21 Jun 2026 · 09:30 WIB</span>
                            <span style="color:#007AFF;font-weight:600;">Lihat Detail →</span>
                        </div>
                    </div>
                    <div class="lp-demo-card lp-demo-card-sm lp-demo-card-float-1">
                        <div style="font-size:11px;color:#636366;font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Kunjungan Hari Ini</div>
                        <div style="font-size:28px;font-weight:800;color:#007AFF;line-height:1;">7</div>
                        <div style="font-size:12px;color:#8E8E93;margin-top:2px;">dari 10 terjadwal</div>
                    </div>
                    <div class="lp-demo-card lp-demo-card-sm lp-demo-card-float-2">
                        <div style="font-size:13px;font-weight:600;color:#1C1C1E;margin-bottom:4px;">⚠️ Perlu Kontrol</div>
                        <div style="font-size:11.5px;color:#636366;">Budi Santoso</div>
                        <div style="font-size:11px;color:#FF9500;margin-top:4px;font-weight:600;">TD: 150/95 mmHg</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="lp-section lp-section-light" id="fitur">
    <div class="container-xl">
        <div class="lp-section-header text-center">
            <div class="lp-chip">✨ Fitur Unggulan</div>
            <h2 class="lp-section-title">Semua yang Anda Butuhkan<br>dalam Satu Platform</h2>
            <p class="lp-section-desc">Dirancang khusus untuk petugas kesehatan home care agar proses monitoring lebih terstruktur dan efisien.</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-md-6 col-lg-4">
                <div class="lp-feature-card lp-animate">
                    <div class="lp-feature-icon" style="background:linear-gradient(135deg,#E8F1FF,#C5D9FF);">🩺</div>
                    <h3 class="lp-feature-title">Catat Tanda Vital</h3>
                    <p class="lp-feature-desc">Input tekanan darah, suhu tubuh, nadi, laju napas, dan saturasi oksigen dengan validasi real-time otomatis.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="lp-feature-card lp-animate" style="animation-delay:.05s;">
                    <div class="lp-feature-icon" style="background:linear-gradient(135deg,#E8F8ED,#C0ECCC);">📂</div>
                    <h3 class="lp-feature-title">Rekam Medis Digital</h3>
                    <p class="lp-feature-desc">Riwayat seluruh kunjungan tersimpan secara kronologis per pasien, mudah diakses kapan saja.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="lp-feature-card lp-animate" style="animation-delay:.1s;">
                    <div class="lp-feature-icon" style="background:linear-gradient(135deg,#FFF4E5,#FFD9A0);">⚠️</div>
                    <h3 class="lp-feature-title">Sistem Peringatan Status</h3>
                    <p class="lp-feature-desc">Klasifikasi otomatis status pasien: Stabil, Perlu Kontrol, atau Perlu Rujukan berdasarkan hasil monitoring.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="lp-feature-card lp-animate" style="animation-delay:.15s;">
                    <div class="lp-feature-icon" style="background:linear-gradient(135deg,#F5EEFF,#E0C8FF);">👥</div>
                    <h3 class="lp-feature-title">Manajemen Pasien</h3>
                    <p class="lp-feature-desc">Daftarkan pasien baru, perbarui data, dan kelola seluruh daftar binaan dalam satu antarmuka terpadu.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="lp-feature-card lp-animate" style="animation-delay:.2s;">
                    <div class="lp-feature-icon" style="background:linear-gradient(135deg,#FFF0EF,#FFC9C6);">🔍</div>
                    <h3 class="lp-feature-title">Pencarian Cepat</h3>
                    <p class="lp-feature-desc">Temukan pasien dalam hitungan detik berdasarkan nama, NIK, atau kode pasien, lengkap dengan riwayat monitoring.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="lp-feature-card lp-animate" style="animation-delay:.25s;">
                    <div class="lp-feature-icon" style="background:linear-gradient(135deg,#E8F1FF,#B8D4FF);">📊</div>
                    <h3 class="lp-feature-title">Dashboard Ringkasan</h3>
                    <p class="lp-feature-desc">Pantau agenda kunjungan harian, statistik status pasien, dan aksi cepat langsung dari halaman utama.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="lp-section lp-section-dark" id="cara-kerja">
    <div class="container-xl">
        <div class="lp-section-header text-center">
            <div class="lp-chip lp-chip-dark">🔄 Cara Kerja</div>
            <h2 class="lp-section-title" style="color:white;">Mudah Digunakan oleh Petugas</h2>
            <p class="lp-section-desc" style="color:rgba(255,255,255,0.6);">Alur kerja yang dirancang intuitif untuk petugas di lapangan maupun di kantor.</p>
        </div>
        <div class="row g-4 mt-2 align-items-center">
            <div class="col-lg-6">
                <div class="lp-steps">
                    <div class="lp-step">
                        <div class="lp-step-num">1</div>
                        <div class="lp-step-content">
                            <h4>Login Akun Petugas</h4>
                            <p>Masuk dengan akun yang telah terdaftar untuk mengakses sistem monitoring.</p>
                        </div>
                    </div>
                    <div class="lp-step">
                        <div class="lp-step-num">2</div>
                        <div class="lp-step-content">
                            <h4>Pilih / Daftarkan Pasien</h4>
                            <p>Cari pasien yang ada atau daftarkan pasien baru ke dalam sistem.</p>
                        </div>
                    </div>
                    <div class="lp-step">
                        <div class="lp-step-num">3</div>
                        <div class="lp-step-content">
                            <h4>Catat Hasil Monitoring</h4>
                            <p>Isi tanda vital, keluhan, dan tentukan status kondisi pasien saat kunjungan.</p>
                        </div>
                    </div>
                    <div class="lp-step">
                        <div class="lp-step-num">4</div>
                        <div class="lp-step-content">
                            <h4>Lihat Rekam Medis</h4>
                            <p>Data tersimpan otomatis dan dapat diakses kembali kapanpun dibutuhkan.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lp-flow-visual">
                    <div class="lp-flow-item">
                        <span class="lp-flow-icon">🔐</span>
                        <span>Login Aman</span>
                    </div>
                    <div class="lp-flow-arrow">↓</div>
                    <div class="lp-flow-item">
                        <span class="lp-flow-icon">👤</span>
                        <span>Pilih Pasien</span>
                    </div>
                    <div class="lp-flow-arrow">↓</div>
                    <div class="lp-flow-item" style="border-color:#007AFF;background:rgba(0,122,255,0.12);">
                        <span class="lp-flow-icon">🩺</span>
                        <span>Catat Monitoring</span>
                    </div>
                    <div class="lp-flow-arrow">↓</div>
                    <div class="lp-flow-item">
                        <span class="lp-flow-icon">📂</span>
                        <span>Rekam Medis Tersimpan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT / DISCLAIMER --}}
<section class="lp-section lp-section-light" id="tentang">
    <div class="container-xl">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="lp-chip">ℹ️ Tentang Sistem</div>
                <h2 class="lp-section-title mt-3">SIVISIT-CareVisitMonitor</h2>
                <p class="lp-section-desc text-start">
                    Sistem ini dikembangkan sebagai platform digital untuk membantu petugas kesehatan 
                    dalam mencatat dan memantau kondisi pasien yang menjalani perawatan di rumah (<em>home care</em>).
                </p>
                <p style="font-size:14px;color:#636366;line-height:1.8;">
                    Dengan SIVISIT, proses dokumentasi yang sebelumnya dilakukan secara manual dapat 
                    beralih ke sistem digital yang lebih terstruktur, cepat, dan mudah diakses.
                </p>
                <div class="lp-disclaimer">
                    <span class="lp-disclaimer-icon">⚠️</span>
                    <div>
                        <strong>Disclaimer Penting:</strong> Seluruh data dalam sistem ini bersifat 
                        simulasi/demo. Sistem ini <strong>tidak memberikan diagnosis medis</strong> dan 
                        tidak menggantikan peran tenaga medis profesional. Rekomendasi yang dihasilkan 
                        bersifat administratif semata.
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lp-tech-stack">
                    <h5 style="font-size:13px;font-weight:700;color:#8E8E93;letter-spacing:.8px;text-transform:uppercase;margin-bottom:16px;">Teknologi yang Digunakan</h5>
                    <div class="lp-tech-list">
                        <div class="lp-tech-item">
                            <span class="lp-tech-icon">⚡</span>
                            <div>
                                <div class="lp-tech-name">Laravel 12</div>
                                <div class="lp-tech-desc">Backend Framework PHP</div>
                            </div>
                        </div>
                        <div class="lp-tech-item">
                            <span class="lp-tech-icon">🗄️</span>
                            <div>
                                <div class="lp-tech-name">MySQL</div>
                                <div class="lp-tech-desc">Database Relasional</div>
                            </div>
                        </div>
                        <div class="lp-tech-item">
                            <span class="lp-tech-icon">🎨</span>
                            <div>
                                <div class="lp-tech-name">Bootstrap 5.3 + Custom CSS</div>
                                <div class="lp-tech-desc">UI Framework & Design System</div>
                            </div>
                        </div>
                        <div class="lp-tech-item">
                            <span class="lp-tech-icon">🌐</span>
                            <div>
                                <div class="lp-tech-name">Apache + .htaccess</div>
                                <div class="lp-tech-desc">Web Server & Routing</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA BOTTOM --}}
<section class="lp-cta-section">
    <div class="container-xl text-center">
        <div class="lp-cta-inner">
            <div class="lp-cta-badge">🏥 Untuk Petugas Kesehatan</div>
            <h2 class="lp-cta-title">Siap Memulai Monitoring?</h2>
            <p class="lp-cta-desc">Masuk ke sistem dan mulai mencatat kunjungan pasien home care Anda hari ini.</p>
            <a href="{{ route('login') }}" class="lp-btn-cta">
                Masuk ke Dashboard →
            </a>
            <div class="lp-cta-hint">
                Gunakan kredensial yang telah diberikan administrator
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="lp-footer">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="lp-brand-icon" style="width:28px;height:28px;font-size:14px;">🏥</div>
                    <strong style="font-size:14px;color:white;">SIVISIT</strong>
                    <span style="font-size:11px;color:rgba(255,255,255,0.4);">SIVISIT-CareVisitMonitor</span>
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.4);">© 2026 SIVISIT — Informatika Kesehatan. Hak cipta dilindungi.</div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div style="font-size:12px;color:rgba(255,255,255,0.4);font-style:italic;">
                    ⚠️ Data bersifat simulasi/dummy. Bukan sistem diagnosis medis.
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px;">
                    Powered by <strong style="color:rgba(255,255,255,0.7);">Laravel 12</strong> · Bootstrap 5 · MySQL
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('lpNav');
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });

    // Intersection observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.lp-animate').forEach(el => observer.observe(el));

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(a.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
</body>
</html>
