<?php
session_start();

// Redirect jika sudah login
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kredensial yang valid
    $valid_credentials = [
        ['email' => 'admin@sivisit.com', 'password' => 'Admin123456', 'name' => 'Administrator', 'role' => 'Admin'],
        ['email' => 'petugas@sivisit.com', 'password' => 'Petugas123456', 'name' => 'Petugas Monitoring', 'role' => 'Petugas'],
    ];

    $user_found = false;
    foreach ($valid_credentials as $cred) {
        if ($cred['email'] === $email && $cred['password'] === $password) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_email'] = $cred['email'];
            $_SESSION['user_name'] = $cred['name'];
            $_SESSION['user_role'] = $cred['role'];
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            $user_found = true;
            header('Location: dashboard.php');
            exit;
        }
    }

    if (!$user_found) {
        $error = 'Email atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="shortcut icon" href="favicon.ico">
    <title>Login - SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --blue: #007AFF;
            --navy: #001A42;
            --surface: #FFFFFF;
            --bg: #F2F4F7;
            --text: #1C1C1E;
        }

        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100%;
            background: var(--bg);
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 0 0 50%;
            background: linear-gradient(155deg, var(--navy) 0%, #003d99 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: white;
        }

        .login-left-content {
            text-align: center;
            max-width: 400px;
        }

        .login-logo {
            font-size: 60px;
            margin-bottom: 30px;
        }

        .login-left h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .login-left p {
            font-size: 15px;
            opacity: 0.9;
            line-height: 1.6;
            margin: 0;
        }

        .login-right {
            flex: 0 0 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .login-header p {
            color: #636366;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #D8DCE6;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .form-control::placeholder {
            color: #8E8E93;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #0066E0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: none;
        }

        .alert-danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .demo-credentials {
            background: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
            margin-top: 25px;
            border-left: 3px solid var(--blue);
        }

        .demo-credentials h5 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6B7280;
            margin-bottom: 10px;
        }

        .demo-item {
            font-size: 13px;
            margin-bottom: 8px;
            color: var(--text);
        }

        .demo-item strong {
            color: var(--blue);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #8E8E93;
            background: none;
            border: none;
            font-size: 18px;
            padding: 0;
        }

        .password-toggle:hover {
            color: var(--blue);
        }

        .form-group-password {
            position: relative;
        }

        @media (max-width: 768px) {
            .login-left {
                display: none;
            }

            .login-right {
                flex: 0 0 100%;
            }

            .login-form-wrapper {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Panel -->
        <div class="login-left">
            <div class="login-left-content">
                <div class="login-logo">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <h1>SIVISIT</h1>
                <p>Sistem Monitoring Kesehatan Pasien Home Care</p>
                <div style="margin-top: 40px; font-size: 13px; opacity: 0.8;">
                    <p>Platform terintegrasi untuk monitoring dan manajemen kunjungan kesehatan rumahan.</p>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="login-right">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <h2>Masuk</h2>
                    <p>Gunakan akun Anda untuk mengakses dashboard</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="nama@contoh.com"
                            required
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        >
                    </div>

                    <div class="form-group form-group-password">
                        <label class="form-label" for="password">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
                    </button>
                </form>

                <div class="demo-credentials">
                    <h5><i class="bi bi-info-circle"></i> Demo Credentials</h5>
                    <div class="demo-item">
                        <strong>Admin:</strong><br>
                        Email: admin@sivisit.com<br>
                        Password: Admin123456
                    </div>
                    <div class="demo-item" style="margin-top: 10px;">
                        <strong>Petugas:</strong><br>
                        Email: petugas@sivisit.com<br>
                        Password: Petugas123456
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        }

        // Auto-fill demo credentials on click
        document.addEventListener('DOMContentLoaded', function() {
            const demoCredentials = document.querySelector('.demo-credentials');
            if (demoCredentials) {
                demoCredentials.style.cursor = 'pointer';
                demoCredentials.addEventListener('click', function() {
                    document.getElementById('email').value = 'admin@sivisit.com';
                    document.getElementById('password').value = 'Admin123456';
                });
            }
        });
    </script>
</body>
</html>
