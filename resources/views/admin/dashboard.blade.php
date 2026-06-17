<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #0d6efd;
            color: white;
        }

        .sidebar a {
            text-decoration: none;
        }

        .sidebar a.text-white:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
                <h4 class="fw-bold text-center mb-4">SIVISIT</h4>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link active bg-white text-primary fw-medium">🏠 Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.patients.index') }}" class="nav-link text-white px-3 py-2 d-block rounded">👥 Daftar Pasien</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-white px-3 py-2 d-block rounded opacity-50">📅 Jadwal Kunjungan</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-white px-3 py-2 d-block rounded opacity-50">📝 Rekam Medis</a>
                    </li>
                </ul>
                <hr>
                <div>
                    <a href="{{ route('logout') }}" class="btn btn-danger btn-sm w-100 fw-medium">🚪 Keluar</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-secondary mb-1">Dashboard Pemantauan</h1>
                        <p class="text-muted small mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</p>
                    </div>
                    <div>
                        <span class="badge bg-primary px-3 py-2 rounded-pill">SISTEM MONITORING TERPADU</span>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm bg-white p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small uppercase tracking-wider d-block mb-1">Total Pasien Homecare</span>
                                    <h3 class="fw-bold text-primary mb-0">{{ $totalPatients }} Orang</h3>
                                </div>
                                <span class="fs-1">👥</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm bg-white p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small uppercase tracking-wider d-block mb-1">Kunjungan Hari Ini</span>
                                    <h3 class="fw-bold text-warning mb-0">{{ $todayVisits }} Pasien</h3>
                                </div>
                                <span class="fs-1">📅</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm bg-white p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small uppercase tracking-wider d-block mb-1">Tugas Selesai</span>
                                    <h3 class="fw-bold text-success mb-0">{{ $todayFinished }} Selesai</h3>
                                </div>
                                <span class="fs-1">✅</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agenda Section -->
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">📅 Agenda Kunjungan Rumah Hari Ini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat Rumah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($todayAgenda->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada agenda kunjungan hari ini.</td>
                                    </tr>
                                @else
                                    @foreach($todayAgenda as $agenda)
                                        <tr>
                                            <td class="fw-semibold text-primary">
                                                {{ isset($agenda->monitoring_time) ? date('H:i', strtotime($agenda->monitoring_time)) : '--:--' }} WIB
                                            </td>
                                            <td class="fw-medium">
                                                {{ $agenda->patient->patient_name ?? '-' }}
                                            </td>
                                            <td class="text-muted">
                                                {{ $agenda->patient->address ?? '-' }}
                                            </td>
                                            <td>
                                                @if(($agenda->status ?? '') === 'Stable')
                                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill">Selesai</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning px-2.5 py-1.5 rounded-pill">Tertunda / Belum</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-outline-primary py-1">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Disclaimer Alert -->
                <div class="alert alert-warning shadow-sm border-0 d-flex align-items-start gap-2 mb-0" role="alert">
                    <span class="fs-5">⚠️</span>
                    <div>
                        <strong>Disclaimer:</strong> Seluruh data yang ditampilkan pada sistem ini bersifat simulasi/dummy untuk keperluan monitoring administratif. Sistem ini tidak memberikan diagnosis medis mandiri; konsultasikan hasil monitoring kepada dokter profesional untuk tindakan medis lebih lanjut.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>