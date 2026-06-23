<?php
require '../config.php';

if (isset($_SESSION['api_token'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'email'    => $_POST['email']    ?? '',
        'password' => $_POST['password'] ?? ''
    ];

    if (!empty($data['email']) && !empty($data['password'])) {
        $apiCall = callAPI('POST', '/login', $data);

        if ($apiCall['status_code'] == 200 && isset($apiCall['response']['success']) && $apiCall['response']['success'] == true) {
            $_SESSION['api_token'] = $apiCall['response']['access_token'];
            $_SESSION['user']      = $apiCall['response']['user'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $apiCall['response']['message'] ?? 'Gagal terhubung ke server atau kredensial salah. Pastikan backend Laravel menyala.';
        }
    } else {
        $error = 'Email dan password wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIVISIT</title>
    <meta name="description" content="Login ke sistem SIVISIT untuk memantau pasien home care.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/global.css" rel="stylesheet">
    <link href="../frontend-CareVisitMonitor/pages/auth.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- ════ LEFT PANEL ════ -->
        <div class="auth-sidebar" style="background-color: var(--sidebar-bg); border-right: 1px solid var(--border);">
            <div class="auth-sidebar-content" style="text-align: left; padding: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 4rem;">
                    <div style="width: 28px; height: 28px; background: var(--primary); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">🏥</div>
                    <div style="font-weight: 700; font-size: 1.125rem;">MediAdmin <span style="font-weight: 400; color: #cbd5e1; font-size: 0.875rem;">CareVisit Monitor</span></div>
                </div>
                
                <div style="background: rgba(255,255,255,0.05); border-radius: 16px; padding: 2rem; margin-bottom: 3rem; text-align: center; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png" alt="Illustration" style="width: 100%; max-width: 240px; margin-bottom: 1rem; opacity: 0.5;">
                    <h3 style="color: white; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Sistem Monitoring Pasien Binaan</h3>
                    <p style="color: #94a3b8; font-size: 0.875rem; line-height: 1.6; margin: 0;">Platform terpusat untuk memantau, mengelola, dan menganalisis kunjungan kesehatan pasien secara real-time dengan akurasi data maksimal.</p>
                </div>
                
                <div style="display: flex; justify-content: space-between; text-align: center; color: #cbd5e1;">
                    <div>
                        <div style="color: var(--primary); font-size: 1.25rem; margin-bottom: 0.5rem;">🛡️</div>
                        <div style="font-size: 0.75rem; font-weight: 600;">Data Aman</div>
                    </div>
                    <div>
                        <div style="color: var(--primary); font-size: 1.25rem; margin-bottom: 0.5rem;">⚡</div>
                        <div style="font-size: 0.75rem; font-weight: 600;">Real-time</div>
                    </div>
                    <div>
                        <div style="color: var(--primary); font-size: 1.25rem; margin-bottom: 0.5rem;">👆</div>
                        <div style="font-size: 0.75rem; font-weight: 600;">Mudah Digunakan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════ RIGHT PANEL ════ -->
        <div class="auth-form-container">
            <h2 style="color: var(--primary); font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Selamat Datang Kembali 👋</h2>
            <p style="color: var(--text-dark); font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Masuk ke Dashboard</p>
            <div class="auth-subtitle" style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2rem; line-height: 1.5;">Silakan masukkan kredensial administrator Anda untuk melanjutkan ke panel kontrol.</div>

            <?php if (!empty($error)): ?>
                <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email" style="font-weight: 500; color: var(--text-dark); font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Email atau Username</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 10px; color: var(--text-muted);">✉️</span>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            placeholder="admin@mediadmin.sim"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required
                            autocomplete="email"
                            style="padding-left: 2.5rem;"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label for="password" style="font-weight: 500; color: var(--text-dark); font-size: 0.875rem; margin: 0;">Kata Sandi</label>
                        <a href="#" style="color: var(--primary); font-size: 0.875rem; text-decoration: none;">Lupa Kata Sandi?</a>
                    </div>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 10px; color: var(--text-muted);">🔒</span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan kata sandi"
                            required
                            autocomplete="current-password"
                            style="padding-left: 2.5rem; padding-right: 40px;"
                        >
                        <button type="button" id="togglePassword" style="position: absolute; right: 12px; top: 8px; background: none; border: none; cursor: pointer; color: var(--text-muted);">
                            👁️
                        </button>
                    </div>
                </div>
                
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <input type="checkbox" id="remember" style="width: 16px; height: 16px; border: 1px solid var(--border); border-radius: 4px; accent-color: var(--primary);">
                    <label for="remember" style="font-size: 0.875rem; color: var(--text-dark); margin: 0; font-weight: 400;">Ingat saya untuk login berikutnya</label>
                </div>

                <button type="submit" class="btn btn-primary auth-btn" id="loginBtn" style="font-weight: 600;">
                    Masuk Sekarang
                </button>
            </form>
            
            <div class="demo-box" style="background-color: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem; margin-top: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <div class="demo-box-icon" style="color: var(--primary); font-size: 1.25rem; margin-top: -2px;">ℹ️</div>
                <div class="demo-box-content">
                    <h4 style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.25rem 0; color: var(--text-dark);">Akses Demo</h4>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--text-dark);">Email: <strong>admin@mediadmin.sim</strong><br>Sandi: <strong>demo1234</strong></p>
                </div>
            </div>

            <div style="margin-top: 3rem; text-align: center; font-size: 0.875rem; color: var(--text-muted);">
                Hanya Admin dan Petugas Kesehatan yang dapat<br>mengakses halaman ini.
                <div style="margin-top: 1rem;">
                    <a href="../index.php" style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500;">
                        ← Kembali ke Halaman Utama
                    </a>
                </div>
                <div style="margin-top: 2rem; font-size: 0.75rem; color: #cbd5e1;">
                    Versi Sistem v2.4.0 • Server Status: <span style="color: var(--success); font-weight: 600;">ONLINE</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput  = document.getElementById('password');

    toggleBtn.addEventListener('click', () => {
        const isPassword = pwdInput.type === 'password';
        pwdInput.type = isPassword ? 'text' : 'password';
        toggleBtn.textContent = isPassword ? '🙈' : '👁️';
    });

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', () => {
        const btn = document.getElementById('loginBtn');
        btn.textContent = 'Memproses...';
        btn.style.opacity = '0.7';
    });
</script>
</body>
</html>