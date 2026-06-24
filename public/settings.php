<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? 'user@example.com';
$message = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $_SESSION['user_name'] = $_POST['name'] ?? $_SESSION['user_name'];
        $message = 'Profil berhasil diperbarui!';
    } elseif ($_POST['action'] === 'change_password') {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Verify old password
        $valid_credentials = [
            'admin@sivisit.com' => 'Admin123456',
            'petugas@sivisit.com' => 'Petugas123456',
        ];

        if (!isset($valid_credentials[$_SESSION['user_email']]) || $valid_credentials[$_SESSION['user_email']] !== $old_password) {
            $error = 'Password lama tidak sesuai!';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Password baru tidak cocok!';
        } elseif (strlen($new_password) < 6) {
            $error = 'Password harus minimal 6 karakter!';
        } else {
            $message = 'Password berhasil diubah! (Note: Demo mode - ubahan tidak disimpan permanen)';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --blue: #007AFF;
            --navy: #001A42;
            --surface: #FFFFFF;
            --bg: #F2F4F7;
            --text: #1C1C1E;
            --muted: #8E8E93;
        }

        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--navy);
            color: white;
            padding: 20px;
            overflow-y: auto;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand-icon {
            font-size: 28px;
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand-text h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--blue);
            color: white;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            width: 100%;
            background: #FF3B30;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            overflow-y: auto;
        }

        .content-card {
            background: var(--surface);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #D8DCE6;
            padding: 10px 12px;
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .btn-primary {
            background: var(--blue);
            border: none;
            border-radius: 6px;
        }

        .btn-primary:hover {
            background: #0066E0;
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div class="sidebar-brand-text">
                    <h4>SIVISIT</h4>
                    <small>CareVisit Monitor</small>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="#"><i class="bi bi-people"></i> Pasien</a></li>
                <li><a href="#"><i class="bi bi-person-badge"></i> Petugas</a></li>
                <li><a href="#"><i class="bi bi-calendar-check"></i> Kunjungan</a></li>
                <li><a href="#"><i class="bi bi-bar-chart"></i> Laporan</a></li>
                <li><a href="settings.php" class="active"><i class="bi bi-gear"></i> Pengaturan</a></li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div style="font-weight: 600; color: white;"><?php echo htmlspecialchars($user_name); ?></div>
                </div>
                <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 style="margin-bottom: 30px;"><i class="bi bi-gear"></i> Pengaturan Akun</h1>

            <?php if ($message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-6">
                    <div class="content-card">
                        <h4 style="margin-bottom: 20px;"><i class="bi bi-person-circle"></i> Update Profil</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_profile">

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" disabled>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="content-card">
                        <h4 style="margin-bottom: 20px;"><i class="bi bi-lock-fill"></i> Ubah Password</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="change_password">

                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" name="old_password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" name="new_password" placeholder="Min. 6 karakter" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-repeat"></i> Ubah Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="content-card">
                        <h4 style="margin-bottom: 20px;"><i class="bi bi-info-circle"></i> Informasi Sistem</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Aplikasi:</strong> SIVISIT CareVisit Monitor</p>
                                <p><strong>Versi:</strong> 2.0 (PHP Native)</p>
                                <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span style="color: #34C759;"><i class="bi bi-circle-fill"></i> Online</span></p>
                                <p><strong>Waktu Akses:</strong> <?php echo date('d F Y H:i:s'); ?></p>
                                <p><strong>Timezone:</strong> Asia/Jakarta</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
