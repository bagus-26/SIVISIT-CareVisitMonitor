<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'User';
$user_role = $_SESSION['user_role'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? 'user@example.com';

$current_date = date('d F Y');
$current_time = date('H:i:s');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --blue: #007AFF;
            --blue-dark: #0058D0;
            --navy: #001A42;
            --surface: #FFFFFF;
            --bg: #F2F4F7;
            --text: #1C1C1E;
            --muted: #8E8E93;
            --border: #D8DCE6;
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

        .sidebar-brand-text small {
            opacity: 0.7;
            font-size: 11px;
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

        .sidebar-menu a i {
            font-size: 18px;
            width: 20px;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-info {
            margin-bottom: 15px;
        }

        .user-info small {
            display: block;
            opacity: 0.7;
            font-size: 12px;
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
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #E5231F;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .page-header p {
            color: var(--muted);
            margin: 5px 0 0 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--surface);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 12px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .stat-icon.blue {
            background: rgba(0, 122, 255, 0.1);
            color: var(--blue);
        }

        .stat-icon.orange {
            background: rgba(255, 149, 0, 0.1);
            color: #FF9500;
        }

        .stat-icon.red {
            background: rgba(255, 59, 48, 0.1);
            color: #FF3B30;
        }

        .stat-icon.green {
            background: rgba(52, 199, 89, 0.1);
            color: #34C759;
        }

        .stat-label {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }

        .stat-subtitle {
            font-size: 12px;
            color: var(--muted);
            margin-top: 8px;
        }

        .content-card {
            background: var(--surface);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .content-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-card h3 i {
            color: var(--blue);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .menu-button {
            background: linear-gradient(135deg, var(--blue), #0066E0);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .menu-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 122, 255, 0.3);
            text-decoration: none;
            color: white;
        }

        .menu-button i {
            font-size: 28px;
        }

        .menu-button.pasien {
            background: linear-gradient(135deg, #FF9500, #FF7700);
        }

        .menu-button.petugas {
            background: linear-gradient(135deg, #34C759, #2FA947);
        }

        .menu-button.kunjungan {
            background: linear-gradient(135deg, #FF3B30, #E5231F);
        }

        .menu-button.laporan {
            background: linear-gradient(135deg, #5856D6, #4D45C4);
        }

        .menu-button.pengaturan {
            background: linear-gradient(135deg, #00B4DB, #0099CC);
        }

        .info-box {
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.1), rgba(0, 122, 255, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid var(--blue);
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 0;
            font-size: 14px;
            color: var(--text);
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            color: var(--muted);
        }

        .breadcrumb-custom a {
            color: var(--blue);
            text-decoration: none;
        }

        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
            }

            .sidebar-footer {
                position: relative;
                bottom: 0;
                left: 0;
                right: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
                <li><a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="lokasi.php"><i class="bi bi-geo-alt"></i> Monitoring Lokasi</a></li>
                <li><a href="#pasien"><i class="bi bi-people"></i> Pasien</a></li>
                <li><a href="#petugas"><i class="bi bi-person-badge"></i> Petugas</a></li>
                <li><a href="#kunjungan"><i class="bi bi-calendar-check"></i> Kunjungan</a></li>
                <li><a href="#laporan"><i class="bi bi-bar-chart"></i> Laporan</a></li>
                <li><a href="#pengaturan"><i class="bi bi-gear"></i> Pengaturan</a></li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div style="font-weight: 600; color: white;"><?php echo htmlspecialchars($user_name); ?></div>
                    <small><?php echo htmlspecialchars($user_role); ?></small>
                </div>
                <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="breadcrumb-custom">
                <i class="bi bi-house"></i>
                <a href="dashboard.php">Dashboard</a>
                <span>></span>
                <span>Home</span>
            </div>

            <div class="page-header">
                <div>
                    <h1>Selamat Datang, <?php echo htmlspecialchars($user_name); ?></h1>
                    <p>Dashboard SIVISIT CareVisit Monitor - <?php echo $current_date; ?></p>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 24px; font-weight: 700; color: var(--blue);"><?php echo $current_time; ?></div>
                    <small style="color: var(--muted);">WIB</small>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <p>
                    <i class="bi bi-info-circle"></i>
                    Ini adalah dashboard demo dengan sistem login PHP native. Semua fitur dapat diakses dari menu di samping.
                </p>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-label">Total Pasien</div>
                    <div class="stat-value">4</div>
                    <div class="stat-subtitle">Data pasien terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-calendar-check"></i></div>
                    <div class="stat-label">Kunjungan Hari Ini</div>
                    <div class="stat-value">0</div>
                    <div class="stat-subtitle">Monitoring tercatat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="stat-label">Perlu Kontrol</div>
                    <div class="stat-value">0</div>
                    <div class="stat-subtitle">Butuh tindak lanjut</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="stat-label">Status Stabil</div>
                    <div class="stat-value">0</div>
                    <div class="stat-subtitle">Selesai hari ini</div>
                </div>
            </div>

            <!-- Main Menu -->
            <div class="content-card">
                <h3><i class="bi bi-grid-3x3-gap"></i> Menu Utama</h3>
                <div class="menu-grid">
                    <a href="#" class="menu-button" data-bs-toggle="modal" data-bs-target="#pasienModal">
                        <i class="bi bi-people"></i>
                        Pasien
                    </a>
                    <a href="#" class="menu-button petugas" data-bs-toggle="modal" data-bs-target="#petugasModal">
                        <i class="bi bi-person-badge"></i>
                        Petugas
                    </a>
                    <a href="#" class="menu-button kunjungan" data-bs-toggle="modal" data-bs-target="#kunjunganModal">
                        <i class="bi bi-calendar-check"></i>
                        Kunjungan
                    </a>
                    <a href="#" class="menu-button laporan" data-bs-toggle="modal" data-bs-target="#laporanModal">
                        <i class="bi bi-bar-chart"></i>
                        Laporan
                    </a>
                    <a href="lokasi.php" class="menu-button" style="background:linear-gradient(135deg,#00B4DB,#0099CC);">
                        <i class="bi bi-geo-alt"></i>
                        Monitoring Lokasi
                    </a>
                    <a href="#" class="menu-button pengaturan" data-bs-toggle="modal" data-bs-target="#pengaturanModal">
                        <i class="bi bi-gear"></i>
                        Pengaturan
                    </a>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="content-card">
                <h3><i class="bi bi-info-circle"></i> Informasi Akun</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <small style="color: var(--muted);">Email</small>
                        <p style="margin: 5px 0; font-weight: 600;"><?php echo htmlspecialchars($user_email); ?></p>
                    </div>
                    <div>
                        <small style="color: var(--muted);">Role / Peran</small>
                        <p style="margin: 5px 0; font-weight: 600;"><?php echo htmlspecialchars($user_role); ?></p>
                    </div>
                    <div>
                        <small style="color: var(--muted);">Login Waktu</small>
                        <p style="margin: 5px 0; font-weight: 600;"><?php echo $_SESSION['login_time']; ?></p>
                    </div>
                    <div>
                        <small style="color: var(--muted);">Status Sistem</small>
                        <p style="margin: 5px 0; font-weight: 600;"><span style="color: #34C759;"><i class="bi bi-circle-fill"></i> Online</span></p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="pasienModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-people"></i> Manajemen Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Fitur manajemen pasien akan dikembangkan sesuai kebutuhan.</p>
                    <p>Anda dapat menambah, mengedit, atau menghapus data pasien melalui modul ini.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="petugasModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-badge"></i> Manajemen Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Fitur manajemen petugas monitoring akan dikembangkan sesuai kebutuhan.</p>
                    <p>Anda dapat mengelola data petugas field, lokasi tugas, dan kontak mereka.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kunjunganModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-calendar-check"></i> Manajemen Kunjungan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Fitur manajemen kunjungan/monitoring akan dikembangkan sesuai kebutuhan.</p>
                    <p>Anda dapat mencatat kunjungan, monitoring kesehatan, dan rekomendasi tindak lanjut.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="laporanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-bar-chart"></i> Laporan & Analisis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Fitur laporan dan analisis akan dikembangkan sesuai kebutuhan.</p>
                    <p>Anda dapat melihat statistik monitoring, export ke PDF/Excel, dan visualisasi data.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pengaturanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-gear"></i> Pengaturan Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div style="padding:20px 30px;border-top:1px solid #D8DCE6;text-align:center;color:#8E8E93;font-size:12px;background:white;margin-left:250px;">
        <p style="margin:0 0 4px;">Sivisit-Kelompok 9 S1 Informatika UAS Pemrograman WEB ITSK Rs Dr Soepraoen Malang</p>
        <p style="margin:0;font-style:italic;">&#x26A0;&#xFE0F; Data simulasi/dummy. Tidak memberikan diagnosis medis. Rekomendasi hanya tindak lanjut administratif.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
