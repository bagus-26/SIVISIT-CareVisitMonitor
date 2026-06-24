<?php
require '../config.php';

if (isset($_SESSION['api_token'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'email'    => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? ''
    ];

    if (!empty($data['email']) && !empty($data['password'])) {
        $apiCall = callAPI('POST', '/login', $data);

        if ($apiCall['status_code'] == 200 && isset($apiCall['response']['success']) && $apiCall['response']['success'] == true) {
            $_SESSION['api_token'] = $apiCall['response']['access_token'];
            $_SESSION['user']      = $apiCall['response']['user'];
            if (!empty($_POST['remember'])) {
                $_SESSION['remember_me'] = true;
            }
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $apiCall['response']['message'] ?? 'Email atau password salah.';
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
    <title>Login — SIVISIT CareVisit Monitor</title>
    <link href="auth.css" rel="stylesheet">
</head>
<body>

<div class="auth-page">
    <div class="auth-panel-left">
        <div class="auth-brand">
            <div class="auth-brand-logo">SV</div>
            <h1>Care Visit Monitor</h1>
            <p>Platform monitoring home care untuk petugas kesehatan. Kelola pasien, catat kunjungan, dan pantau kondisi secara real-time.</p>
        </div>
        <div class="auth-features">
            <div class="auth-feature-item">
                <span class="auth-feature-icon">📋</span>
                <span>Input log kunjungan & tanda vital terstruktur</span>
            </div>
            <div class="auth-feature-item">
                <span class="auth-feature-icon">🔒</span>
                <span>Data terenkripsi & akses berbasis peran</span>
            </div>
            <div class="auth-feature-item">
                <span class="auth-feature-icon">📊</span>
                <span>Dashboard rekapitulasi administratif</span>
            </div>
        </div>
    </div>

    <div class="auth-panel-right">
        <div class="auth-form-wrap">
            <h2>Selamat Datang</h2>
            <p class="auth-subtitle">Silakan masuk ke akun petugas Anda</p>

            <?php if (!empty($error)): ?>
                <div class="auth-alert auth-alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form class="auth-form" action="" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="admin@sivisit.com" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <div class="auth-form-row">
                    <label class="auth-remember">
                        <input type="checkbox" name="remember" value="1" checked>
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="auth-btn-submit">Masuk Aplikasi</button>
            </form>

            <div class="auth-demo-hint">
                <strong>Demo login:</strong><br>
                Admin — <code>admin@sivisit.com</code> / <code>Admin123456</code><br>
                Petugas — <code>petugas@sivisit.com</code> / <code>Petugas123456</code>
            </div>

            <a href="../index.php" class="auth-back-link">← Kembali ke Beranda</a>
        </div>
    </div>
</div>

</body>
</html>
