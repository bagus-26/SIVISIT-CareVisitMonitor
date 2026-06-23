<?php
require '../config.php';

if (isset($_SESSION['api_token'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$isUnverified = false;
$unverifiedEmail = '';

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
        } elseif ($apiCall['status_code'] == 403 && isset($apiCall['response']['verified']) && $apiCall['response']['verified'] == false) {
            $isUnverified = true;
            $error = $apiCall['response']['message'] ?? 'Email belum diverifikasi.';
            $unverifiedEmail = $data['email'];
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
    <title>Login - SIVISIT-CareVisitMonitor</title>
    <link href="https://cdn.jsdelivr.net/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            margin-top: 10%;
        }
        .sv-logo-box {
            width: 56px; height: 56px; 
            background: rgba(13, 110, 253, 0.1); 
            border-radius: 14px; 
            display: flex; align-items: center; justify-content: center; 
            margin: 0 auto 16px auto;
        }
        .sv-logo-box svg {
            fill: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 px-3 py-4">
                <div class="card-body">
                    <div class="text-center">
                        <div class="sv-logo-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 16 16">
                                <path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-center fw-bold text-primary mb-2">SIVISIT</h3>
                    <p class="text-muted text-center small mb-4">Silakan masuk ke akun Anda</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($isUnverified && !empty($unverifiedEmail)): ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Email belum diverifikasi!</strong>
                            <p class="mb-1 small">Klik tombol di bawah untuk mengirim ulang email verifikasi.</p>
                            <a href="verifikasi-email.php?email=<?php echo urlencode($unverifiedEmail); ?>" class="btn btn-warning btn-sm mt-1">
                                Kirim Ulang Verifikasi
                            </a>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label small fw-medium">Email</label>
                            <input type="email" name="email" class="form-control" id="username" placeholder="Masukkan email"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label small fw-medium">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="••••••••"
                                required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-medium">Masuk Aplikasi</button>
                        </div>
                    </form>
                    
                    <!-- Akses Demo -->
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-top: 15px; display: flex; align-items: flex-start; gap: 8px;">
                        <div style="color: #0d6efd; font-size: 14px;">ℹ️</div>
                        <div>
                            <h4 style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 4px 0; color: #1e293b;">Akses Demo</h4>
                            <p style="margin: 0; font-size: 12.5px; color: #334155;">Email: <strong>admin@mediadmin.sim</strong><br>Sandi: <strong>demo1234</strong></p>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem; text-align: center; font-size: 13px; color: #6c757d;">
                        Belum punya akun? <a href="register.php" style="color: #0d6efd; text-decoration: none; font-weight: 600;">Daftar di sini</a>
                        <br><br>
                        <a href="../index.php" style="color: #0d6efd; text-decoration: none;">← Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>