<?php
require '../config.php';

$status = $_GET['status'] ?? '';
$email  = $_GET['email'] ?? '';
$resendMessage = '';
$resendError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email'])) {
    $apiCall = callAPI('POST', '/email/resend', ['email' => $_POST['email']]);

    if ($apiCall['status_code'] == 200 && isset($apiCall['response']['success']) && $apiCall['response']['success'] == true) {
        $resendMessage = $apiCall['response']['message'] ?? 'Email verifikasi telah dikirim ulang.';
    } else {
        $resendError = $apiCall['response']['message'] ?? 'Gagal mengirim ulang email. Pastikan backend Laravel menyala.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .verify-container {
            margin-top: 10%;
        }
        .sv-logo-box {
            width: 56px; height: 56px; 
            background: rgba(13, 110, 253, 0.1); 
            border-radius: 14px; 
            display: flex; align-items: center; justify-content: center; 
            margin: 0 auto 16px auto;
        }
        .sv-logo-box svg { fill: #0d6efd; }
        .icon-big { font-size: 48px; }
    </style>
</head>
<body>

<div class="container verify-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 px-3 py-4">
                <div class="card-body text-center">

                    <div class="sv-logo-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                        </svg>
                    </div>
                    <h3 class="fw-bold text-primary mb-2">Verifikasi Email</h3>

                    <?php if ($status === 'verified'): ?>
                        <div class="text-success icon-big mb-2">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="alert alert-success">
                            <strong>Email berhasil diverifikasi!</strong>
                            <p class="mb-1 small">Akun Anda sudah aktif. Silakan masuk ke aplikasi.</p>
                        </div>
                        <a href="login.php" class="btn btn-primary fw-medium">Masuk ke Aplikasi</a>

                    <?php elseif ($status === 'already_verified'): ?>
                        <div class="text-info icon-big mb-2">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div class="alert alert-info">
                            <strong>Email sudah diverifikasi sebelumnya.</strong>
                            <p class="mb-1 small">Silakan langsung masuk ke aplikasi.</p>
                        </div>
                        <a href="login.php" class="btn btn-primary fw-medium">Masuk ke Aplikasi</a>

                    <?php elseif ($status === 'gagal'): ?>
                        <div class="text-danger icon-big mb-2">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="alert alert-danger">
                            <strong>Link verifikasi tidak valid atau telah kedaluwarsa.</strong>
                            <p class="mb-1 small">Silakan kirim ulang email verifikasi.</p>
                        </div>

                    <?php else: ?>
                        <div class="text-warning icon-big mb-2">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="alert alert-info">
                            <strong>Cek email Anda!</strong>
                            <p class="mb-1 small">Kami telah mengirimkan tautan verifikasi ke email Anda. Klik tautan tersebut untuk mengaktifkan akun.</p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($resendMessage)): ?>
                        <div class="alert alert-success mt-3">
                            <?php echo htmlspecialchars($resendMessage); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($resendError)): ?>
                        <div class="alert alert-danger mt-3">
                            <?php echo htmlspecialchars($resendError); ?>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4">

                    <p class="small text-muted mb-2">Tidak menerima email? Kirim ulang:</p>
                    <form action="" method="POST" class="d-flex gap-2 justify-content-center">
                        <input type="email" name="email" class="form-control form-control-sm w-auto" placeholder="Email Anda"
                               value="<?php echo htmlspecialchars($email); ?>" required>
                        <button type="submit" class="btn btn-warning btn-sm fw-medium">Kirim Ulang</button>
                    </form>

                    <div class="mt-3" style="font-size: 13px;">
                        <a href="login.php" style="color: #0d6efd; text-decoration: none;">← Kembali ke Login</a>
                        <br>
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
