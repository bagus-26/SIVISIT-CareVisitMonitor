<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke SIVISIT CareVisit Monitor — sistem monitoring kesehatan pasien home care.">
    <title>Masuk — SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── Variables ───────────────────────────────── */
        :root {
            --blue:       #007AFF;
            --blue-dark:  #0058D0;
            --blue-mid:   #0066E0;
            --navy:       #001A42;
            --navy-mid:   #002866;
            --navy-light: #003580;
            --surface:    #FFFFFF;
            --bg:         #F2F4F7;
            --border:     #D8DCE6;
            --text:       #1C1C1E;
            --muted:      #8E8E93;
            --sub:        #636366;
        }

        *, *::before, *::after { box-sizing: border-box; -webkit-font-smoothing: antialiased; }

        html, body {
            margin: 0; padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            height: 100%;
        }

        /* ── Wrapper ─────────────────────────────────── */
        .login-root {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ──────────────────────────────── */
        .login-panel-left {
            flex: 0 0 56%;
            background: linear-gradient(155deg, var(--navy) 0%, var(--navy-mid) 45%, #004BB8 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            overflow: hidden;
        }

        /* decorative blobs */
        .login-panel-left::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,122,255,0.20) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .login-panel-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* grid overlay */
        .lp-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        /* brand block */
        .lp-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 2;
        }
        .lp-brand-mark {
            width: 46px; height: 46px;
            background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            backdrop-filter: blur(6px);
        }
        .lp-brand-name { color: white; font-size: 18px; font-weight: 700; letter-spacing: -0.3px; line-height: 1.15; }
        .lp-brand-sub  { color: rgba(255,255,255,0.5); font-size: 11px; font-weight: 500; letter-spacing: 1.2px; text-transform: uppercase; }

        /* center content */
        .lp-center {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }
        .lp-tagline {
            font-size: clamp(26px, 3.5vw, 38px);
            font-weight: 800;
            color: white;
            letter-spacing: -1.5px;
            line-height: 1.18;
            margin-bottom: 16px;
        }
        .lp-tagline span {
            background: linear-gradient(90deg, #60B8FF, #A8D8FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .lp-tagdesc {
            font-size: 14.5px;
            color: rgba(255,255,255,0.6);
            line-height: 1.75;
            max-width: 380px;
            margin-bottom: 36px;
        }

        /* feature pills */
        .lp-features { display: flex; flex-direction: column; gap: 12px; }
        .lp-feature-item {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 10px;
            padding: 12px 16px;
            backdrop-filter: blur(4px);
            transition: background 0.2s;
        }
        .lp-feature-item:hover { background: rgba(255,255,255,0.10); }
        .lp-feature-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: rgba(0,122,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
            color: #60B8FF;
        }
        .lp-feature-text strong { display: block; font-size: 13px; font-weight: 600; color: white; }
        .lp-feature-text span   { font-size: 11.5px; color: rgba(255,255,255,0.5); }

        /* stats row */
        .lp-stats {
            display: flex; gap: 32px;
            position: relative; z-index: 2;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .lp-stat-val { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; }
        .lp-stat-lbl { font-size: 11px; color: rgba(255,255,255,0.45); font-weight: 500; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.6px; }

        /* ── RIGHT PANEL ─────────────────────────────── */
        .login-panel-right {
            flex: 1;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            position: relative;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 400px;
            animation: fadeSlide 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* form header */
        .lf-header { margin-bottom: 32px; }
        .lf-sup {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11.5px; font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase; color: var(--blue);
            background: rgba(0,122,255,0.08);
            border: 1px solid rgba(0,122,255,0.18);
            border-radius: 20px; padding: 4px 12px;
            margin-bottom: 14px;
        }
        .lf-sup i { font-size: 10px; }
        .lf-title { font-size: 26px; font-weight: 800; color: var(--navy); letter-spacing: -0.8px; margin: 0 0 6px; }
        .lf-sub   { font-size: 13.5px; color: var(--sub); margin: 0; line-height: 1.6; }

        /* form card */
        .lf-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 32px 28px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        }

        /* inputs */
        .lf-input-group { margin-bottom: 20px; }
        .lf-label {
            display: block;
            font-size: 12.5px; font-weight: 600;
            color: var(--text); margin-bottom: 7px;
            letter-spacing: 0.1px;
        }
        .lf-input-wrap { position: relative; }
        .lf-input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 15px; pointer-events: none;
            transition: color 0.18s;
        }
        .lf-input {
            width: 100%;
            padding: 11px 40px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: var(--bg);
            transition: all 0.18s;
            outline: none;
        }
        .lf-input:focus {
            border-color: var(--blue);
            background: white;
            box-shadow: 0 0 0 3px rgba(0,122,255,0.12);
        }
        .lf-input:focus + .lf-input-icon,
        .lf-input-wrap:focus-within .lf-input-icon { color: var(--blue); }
        .lf-input::placeholder { color: var(--muted); }
        .lf-pw-toggle {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; padding: 0;
            cursor: pointer; color: var(--muted); font-size: 15px;
            transition: color 0.18s;
            display: flex; align-items: center;
        }
        .lf-pw-toggle:hover { color: var(--blue); }

        /* submit button */
        .lf-btn {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-mid) 100%);
            color: white;
            border: none; border-radius: 10px;
            font-size: 14.5px; font-weight: 600;
            font-family: inherit; cursor: pointer;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 16px rgba(0,122,255,0.30);
            margin-top: 8px;
        }
        .lf-btn:hover {
            background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,122,255,0.38);
        }
        .lf-btn:active { transform: translateY(0); }

        /* alert */
        .lf-alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px;
            background: #FFF0EF;
            border: 1px solid rgba(255,59,48,0.2);
            border-radius: 10px;
            font-size: 13px; color: #C0291F;
            margin-bottom: 20px;
            animation: fadeSlide 0.3s ease both;
        }
        .lf-alert i { font-size: 15px; flex-shrink: 0; margin-top: 1px; }

        /* hint */
        .lf-hint {
            margin-top: 20px;
            padding: 12px 14px;
            background: rgba(0,122,255,0.05);
            border: 1px solid rgba(0,122,255,0.12);
            border-radius: 10px;
            font-size: 12px; color: var(--sub);
            display: flex; align-items: center; gap: 8px;
        }
        .lf-hint i { color: var(--blue); font-size: 13px; flex-shrink: 0; }
        .lf-hint strong { color: var(--text); }

        /* footer links */
        .lf-footer-link {
            text-align: center; margin-top: 20px;
            font-size: 12.5px; color: var(--muted);
        }
        .lf-footer-link a { color: var(--blue); font-weight: 500; text-decoration: none; }
        .lf-footer-link a:hover { text-decoration: underline; }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 991px) {
            .login-panel-left { display: none; }
            .login-panel-right { background: linear-gradient(155deg, var(--navy) 0%, #004BB8 100%); }
            .lf-card { box-shadow: 0 8px 40px rgba(0,0,0,0.15); }
            .lf-header { display: none; }
            .lf-card::before {
                content: '';
                display: block;
                margin-bottom: 24px;
            }
            .login-mobile-brand {
                display: flex !important;
                align-items: center; gap: 12px;
                margin-bottom: 24px;
            }
        }
        @media (min-width: 992px) {
            .login-mobile-brand { display: none !important; }
        }

        /* mobile brand shown on small screens */
        .login-mobile-brand {
            display: none;
            color: white;
        }
        .login-mobile-brand .lm-mark {
            width: 42px; height: 42px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; border: 1px solid rgba(255,255,255,0.2);
        }
        .login-mobile-brand .lm-name { font-size: 18px; font-weight: 800; color: white; line-height: 1.1; }
        .login-mobile-brand .lm-sub  { font-size: 11px; color: rgba(255,255,255,0.5); letter-spacing: 1px; text-transform: uppercase; }

        @media (max-width: 400px) {
            .lf-card { padding: 24px 18px; border-radius: 16px; }
        }
    </style>
</head>
<body>

<div class="login-root">

    {{-- ── LEFT PANEL ──────────────────────────────── --}}
    <div class="login-panel-left">
        <div class="lp-grid"></div>

        {{-- Brand --}}
        <div class="lp-brand">
            <div class="lp-brand-mark">
                <i class="bi bi-heart-pulse-fill" style="color:#60B8FF;"></i>
            </div>
            <div>
                <div class="lp-brand-name">SIVISIT</div>
                <div class="lp-brand-sub">CareVisit Monitor</div>
            </div>
        </div>

        {{-- Center --}}
        <div class="lp-center">
            <h1 class="lp-tagline">
                Monitoring<br><span>Kesehatan</span><br>Home Care
            </h1>
            <p class="lp-tagdesc">
                Platform digital petugas kesehatan untuk mencatat tanda vital, mengelola rekam medis, dan memantau kondisi pasien secara terstruktur.
            </p>
            <div class="lp-features">
                <div class="lp-feature-item">
                    <div class="lp-feature-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                    <div class="lp-feature-text">
                        <strong>Catat Tanda Vital</strong>
                        <span>TD, suhu, nadi, SpO₂ dengan validasi real-time</span>
                    </div>
                </div>
                <div class="lp-feature-item">
                    <div class="lp-feature-icon"><i class="bi bi-people"></i></div>
                    <div class="lp-feature-text">
                        <strong>Manajemen Pasien</strong>
                        <span>Daftar, edit, dan pantau seluruh pasien binaan</span>
                    </div>
                </div>
                <div class="lp-feature-item">
                    <div class="lp-feature-icon"><i class="bi bi-geo-alt"></i></div>
                    <div class="lp-feature-text">
                        <strong>Peta Lokasi Pasien</strong>
                        <span>Visualisasi lokasi kunjungan berbasis Leaflet</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats bottom --}}
        <div class="lp-stats">
            <div>
                <div class="lp-stat-val">100%</div>
                <div class="lp-stat-lbl">Digital</div>
            </div>
            <div>
                <div class="lp-stat-val">Real-time</div>
                <div class="lp-stat-lbl">Monitoring</div>
            </div>
            <div>
                <div class="lp-stat-val">Aman</div>
                <div class="lp-stat-lbl">Terenkripsi</div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ──────────────────────────────── --}}
    <div class="login-panel-right">
        <div class="login-form-wrap">

            {{-- Mobile brand (hidden on desktop) --}}
            <div class="login-mobile-brand">
                <div class="lm-mark">
                    <i class="bi bi-heart-pulse-fill" style="color:#60B8FF;"></i>
                </div>
                <div>
                    <div class="lm-name">SIVISIT</div>
                    <div class="lm-sub">CareVisit Monitor</div>
                </div>
            </div>

            {{-- Header (desktop only) --}}
            <div class="lf-header">
                <div class="lf-sup">
                    <i class="bi bi-shield-lock-fill"></i> Portal Petugas
                </div>
                <h1 class="lf-title">Masuk ke Akun</h1>
                <p class="lf-sub">Gunakan kredensial petugas Anda untuk mengakses sistem monitoring.</p>
            </div>

            {{-- Form Card --}}
            <div class="lf-card">

                @if ($errors->any())
                <div class="lf-alert" id="loginAlert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="lf-input-group">
                        <label for="email" class="lf-label">Email Petugas</label>
                        <div class="lf-input-wrap">
                            <input type="email" name="email" id="email" class="lf-input"
                                   placeholder="nama@contoh.com"
                                    value="{{ old('email') }}"
                                   autocomplete="email" required>
                            <i class="bi bi-envelope lf-input-icon"></i>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="lf-input-group" style="margin-bottom:6px;">
                        <label for="password" class="lf-label">Password</label>
                        <div class="lf-input-wrap">
                            <input type="password" name="password" id="password" class="lf-input"
                                   placeholder="••••••••"
                                   autocomplete="current-password" required>
                            <i class="bi bi-lock lf-input-icon" style="pointer-events:none;"></i>
                            <button type="button" class="lf-pw-toggle" id="pwToggle" aria-label="Tampilkan password">
                                <i class="bi bi-eye" id="pwToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="lf-btn" id="loginBtn">
                        <i class="bi bi-box-arrow-in-right" id="btnIcon"></i>
                        <span id="btnText">Masuk ke Sistem</span>
                    </button>
                </form>

                {{-- Hint --}}
                <div class="lf-hint">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Gunakan kredensial yang telah diberikan oleh administrator.</span>
                </div>
            </div>

        </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Password toggle
    const pwInput  = document.getElementById('password');
    const pwToggle = document.getElementById('pwToggle');
    const pwIcon   = document.getElementById('pwToggleIcon');
    if (pwToggle) {
        pwToggle.addEventListener('click', () => {
            const isHidden = pwInput.type === 'password';
            pwInput.type   = isHidden ? 'text' : 'password';
            pwIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    // Loading state on submit
    const form    = document.getElementById('loginForm');
    const btn     = document.getElementById('loginBtn');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');
    if (form) {
        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.style.opacity = '0.8';
            btnIcon.className = 'bi bi-arrow-repeat';
            btnIcon.style.animation = 'spin 0.7s linear infinite';
            btnText.textContent = 'Memproses...';
        });
    }
</script>
<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
</body>
</html>