<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien - SIVISIT</title>
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
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
                <h4 class="fw-bold text-center mb-4">SIVISIT</h4>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-white px-3 py-2 d-block rounded">🏠 Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.patients.index') }}" class="nav-link active bg-white text-primary fw-medium">👥 Daftar Pasien</a>
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

            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 fw-bold text-secondary">Kelola Data Pasien</h1>
                    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary btn-sm fw-medium">+ Tambah Pasien Baru</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success shadow-sm border-0 mb-3" role="alert">
                        🎉 {{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-sm border-0 p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Usia</th>
                                    <th>Diagnosa Medis</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($patients->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">Tidak ada data pasien.</td>
                                    </tr>
                                @else
                                    @php $no = 1; @endphp
                                    @foreach($patients as $p)
                                        @php
                                            $latestMonitoring = $p->monitorings->sortByDesc('created_at')->first();
                                            $diagnosa = $latestMonitoring ? ($latestMonitoring->symptoms ?? '-') : '-';
                                        @endphp
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="fw-medium">{{ $p->patient_name ?? '-' }}</td>
                                            <td>{{ isset($p->datebirth) ? \Carbon\Carbon::parse($p->datebirth)->age . ' Tahun' : '-' }}</td>
                                            <td>
                                                @if($diagnosa !== '-' && !empty($diagnosa))
                                                    <span class="badge bg-danger-subtle text-danger">{{ $diagnosa }}</span>
                                                @else
                                                    <span class="badge bg-light text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $p->address ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-info text-white py-1 me-1" data-bs-toggle="modal" data-bs-target="#viewModal{{ $p->patient_id }}">Lihat</button>
                                                    <a href="{{ route('admin.patients.edit', $p->patient_id) }}" class="btn btn-sm btn-warning text-white py-1 me-1">Edit</a>
                                                    <form action="{{ route('admin.patients.destroy', $p->patient_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pasien ini beserta riwayat monitoringnya?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger py-1">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modals for details -->
    @foreach ($patients as $p)
        <div class="modal fade" id="viewModal{{ $p->patient_id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $p->patient_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="viewModalLabel{{ $p->patient_id }}">Detail Pasien: {{ $p->patient_name ?? '' }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">ID Pasien / No. RM</span>
                                <strong class="fs-6">{{ $p->patient_id ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">NIK Pasien</span>
                                <strong class="fs-6">{{ $p->nik_dummy ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Kategori & Gender</span>
                                <strong class="fs-6">{{ $p->patient_category ?? '-' }} ({{ $p->gender ?? '-' }})</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Tanggal Lahir</span>
                                <strong class="fs-6">{{ isset($p->datebirth) ? date('d M Y', strtotime($p->datebirth)) : '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Alamat Lengkap</span>
                                <strong class="fs-6">{{ $p->address ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Nomor Telepon Darurat</span>
                                <strong class="fs-6">{{ $p->family_phone ?? '-' }}</strong>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold text-primary mb-3">🩺 Riwayat Monitoring Kesehatan</h6>
                        @if($p->monitorings->isEmpty())
                            <div class="alert alert-light text-muted small mb-0">Belum ada catatan monitoring kesehatan untuk pasien ini.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle text-center small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal & Jam</th>
                                            <th>Tensi</th>
                                            <th>Nadi</th>
                                            <th>Nafas</th>
                                            <th>Suhu</th>
                                            <th>Saturasi O2</th>
                                            <th>Gejala/Kondisi</th>
                                            <th>Catatan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($p->monitorings->sortByDesc('monitoring_date') as $mon)
                                            <tr>
                                                <td>
                                                    {{ isset($mon->monitoring_date) ? date('d-m-Y', strtotime($mon->monitoring_date)) : '' }}
                                                    {{ isset($mon->monitoring_time) ? ' ' . date('H:i', strtotime($mon->monitoring_time)) : '' }}
                                                </td>
                                                <td>{{ $mon->blood_pressure ?? '-' }}</td>
                                                <td>{{ $mon->heart_rate ?? '-' }} bpm</td>
                                                <td>{{ $mon->respiratory_rate ?? '-' }} x/m</td>
                                                <td>{{ $mon->body_temperature ?? '-' }} °C</td>
                                                <td>{{ $mon->oxygen_saturation ?? '-' }}%</td>
                                                <td>{{ $mon->symptoms ?? '-' }}</td>
                                                <td>{{ $mon->notes ?? '-' }}</td>
                                                <td>
                                                    <span class="badge {{ ($mon->status === 'Stable') ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $mon->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>