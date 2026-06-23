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
    <link href="../pages/global.css" rel="stylesheet">
    <link href="../pages/auth.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- ════ LEFT PANEL ════ -->
        <div class="auth-sidebar">
            <div class="auth-sidebar-content">
                <!-- Figma image placeholder -->
                <div style="font-size: 48px; margin-bottom: 20px;">🏥</div>
                <h2 style="color: white; font-size: 28px; font-weight: bold; margin-bottom: 16px;">Sistem Monitoring<br>Pasien Home Care</h2>
                <p style="color: rgba(255,255,255,0.8); line-height: 1.6; font-size: 15px;">Platform digital untuk petugas kesehatan dalam memantau dan mencatat kondisi pasien binaan secara terstruktur.</p>
                
                <div style="margin-top: 40px; font-size: 12px; color: rgba(255,255,255,0.5);">
                    ⚠️ Seluruh data bersifat simulasi/dummy. Sistem ini tidak memberikan diagnosis medis.
                </div>
            </div>
        </div>

        <!-- ════ RIGHT PANEL ════ -->
        <div class="auth-form-container">
            <a href="../index.php" style="color: var(--text-muted); font-size: 14px; text-decoration: none; margin-bottom: 2rem; display: inline-block;">
                ← Kembali ke Beranda
            </a>

            <h2>Selamat Datang</h2>
            <p>Masuk ke akun petugas / admin Anda</p>

            <?php if (!empty($error)): ?>
                <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="petugas@sivisit.id"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="form-group" style="position: relative;">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        style="padding-right: 40px;"
                    >
                    <button type="button" id="togglePassword" style="position: absolute; right: 12px; top: 38px; background: none; border: none; cursor: pointer; color: var(--text-muted);">
                        👁️
                    </button>
                </div>

                <button type="submit" class="btn btn-primary auth-btn" id="loginBtn">
                    Masuk ke Sistem
                </button>
            </form>

            <div style="margin-top: 2rem; text-align: center; font-size: 14px; color: var(--text-muted);">
                Butuh akses keluarga / pasien?<br>
                <a href="cari-pasien.php" style="color: var(--primary); font-weight: 500; text-decoration: none; margin-top: 8px; display: inline-block;">Cari Riwayat Pasien →</a>
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