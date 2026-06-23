<?php
require '../config.php';

if (isset($_SESSION['api_token'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'name'     => $_POST['name']     ?? '',
        'email'    => $_POST['email']    ?? '',
        'password' => $_POST['password'] ?? '',
        'password_confirmation' => $_POST['password_confirmation'] ?? '',
    ];

    if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
        if ($data['password'] !== $data['password_confirmation']) {
            $error = 'Konfirmasi password tidak cocok.';
        } elseif (strlen($data['password']) < 8) {
            $error = 'Password minimal 8 karakter.';
        } else {
            unset($data['password_confirmation']);
            $apiCall = callAPI('POST', '/register', $data);

            if ($apiCall['status_code'] == 201 && isset($apiCall['response']['success']) && $apiCall['response']['success'] == true) {
                $success = 'Registrasi berhasil! Silakan cek email Anda untuk melakukan verifikasi.';
                $registeredEmail = $data['email'];
            } else {
                $error = $apiCall['response']['message'] ?? 'Gagal terhubung ke server. Pastikan backend Laravel menyala.';
            }
        }
    } else {
        $error = 'Semua field wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .register-container {
            margin-top: 6%;
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

<div class="container register-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 px-3 py-4">
                <div class="card-body">
                    <div class="text-center">
                        <div class="sv-logo-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-center fw-bold text-primary mb-2">SIVISIT</h3>
                    <p class="text-muted text-center small mb-4">Buat akun baru untuk memulai</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="verifikasi-email.php?email=<?php echo urlencode($registeredEmail); ?>" class="btn btn-outline-primary btn-sm">
                                Kirim ulang verifikasi email
                            </a>
                            <br><br>
                            <a href="login.php" class="btn btn-primary fw-medium">Masuk ke Aplikasi</a>
                        </div>
                    <?php else: ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-medium">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-medium">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label small fw-medium">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Minimal 8 karakter" required minlength="8">
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label small fw-medium">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Ulangi password" required minlength="8">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-medium">Daftar Akun</button>
                            </div>
                        </form>

                        <div style="margin-top: 1.5rem; text-align: center; font-size: 13px; color: #6c757d;">
                            Sudah punya akun? <a href="login.php" style="color: #0d6efd; text-decoration: none;">Masuk di sini</a>
                            <br><br>
                            <a href="../index.php" style="color: #0d6efd; text-decoration: none;">← Kembali ke Beranda</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
