<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pasien - SIVISIT</title>
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

        .card-custom {
            border: none;
            border-radius: 12px;
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
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-secondary mb-1">Edit Data Pasien</h1>
                        <p class="text-muted small mb-0">Memperbarui informasi rekam medis pasien: <strong>{{ $patient->patient_name }}</strong></p>
                    </div>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary btn-sm fw-medium">⬅️ Kembali ke Daftar</a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger shadow-sm border-0 mb-4" role="alert">
                        ⚠️ {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('admin.patients.update', $patient->patient_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">

                        <div class="col-md-6">
                            <div class="card card-custom shadow-sm p-4 h-100">
                                <h5 class="fw-bold text-primary mb-3">📋 Informasi Petugas Medis</h5>
                                <div class="mb-3">
                                    <label for="id_petugas" class="form-label small fw-medium">ID Petugas Medis</label>
                                    <input type="text" class="form-control bg-light" id="id_petugas" value="PM-2026-{{ Auth::user()->id }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_petugas" class="form-label small fw-medium">Nama Petugas Medis</label>
                                    <input type="text" class="form-control bg-light" id="nama_petugas" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-custom shadow-sm p-4 h-100">
                                <h5 class="fw-bold text-danger mb-3">🩺 Identitas Rekam Medis</h5>
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label small fw-medium">Nomor Rekam Medis (No. RM)</label>
                                    <input type="text" id="patient_id" class="form-control bg-light" value="{{ $patient->patient_id }}" readonly>
                                    <span class="text-muted small mt-1 d-block">Nomor rekam medis tidak dapat diubah.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card card-custom shadow-sm p-4">
                                <h5 class="fw-bold text-success mb-3">👤 Data Pribadi & Identitas Pasien</h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nik_dummy" class="form-label small fw-medium">NIK Pasien (Sesuai KTP/KK)</label>
                                        <input type="number" name="nik_dummy" id="nik_dummy" class="form-control" placeholder="Masukkan 16 digit NIK" value="{{ old('nik_dummy', $patient->nik_dummy) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="patient_name" class="form-label small fw-medium">Nama Lengkap Pasien</label>
                                        <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Masukkan nama pasien" value="{{ old('patient_name', $patient->patient_name) }}" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="patient_category" class="form-label small fw-medium">Kategori Pasien</label>
                                        <select name="patient_category" id="patient_category" class="form-select" required>
                                            <option value="" disabled>-- Pilih Kategori --</option>
                                            <option value="Balita" {{ old('patient_category', $patient->patient_category) == 'Balita' ? 'selected' : '' }}>👶 Balita (0 - 5 Tahun)</option>
                                            <option value="Anak-anak" {{ old('patient_category', $patient->patient_category) == 'Anak-anak' ? 'selected' : '' }}>👦 Anak-anak</option>
                                            <option value="Dewasa" {{ old('patient_category', $patient->patient_category) == 'Dewasa' ? 'selected' : '' }}>🧑 Dewasa</option>
                                            <option value="Lansia" {{ old('patient_category', $patient->patient_category) == 'Lansia' ? 'selected' : '' }}>🧓 Lansia (Lanjut Usia)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="gender" class="form-label small fw-medium">Jenis Kelamin</label>
                                        <select name="gender" id="gender" class="form-select" required>
                                            <option value="" disabled>-- Pilih Gender --</option>
                                            <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>👨 Laki-laki</option>
                                            <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>👩 Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="tempat_lahir" class="form-label small fw-medium">Tempat Lahir</label>
                                        <input type="text" id="tempat_lahir" class="form-control" placeholder="Contoh: Malang" value="Malang">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="datebirth" class="form-label small fw-medium">Tanggal Lahir</label>
                                        <input type="date" name="datebirth" id="datebirth" class="form-control" value="{{ old('datebirth', $patient->datebirth) }}" required>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="address" class="form-label small fw-medium">Alamat Rumah Lengkap</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan" value="{{ old('address', $patient->address) }}" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="family_phone" class="form-label small fw-medium">Nomor Telepon Darurat (Emergency)</label>
                                        <input type="tel" name="family_phone" id="family_phone" class="form-control" placeholder="Contoh: 081234xxxxxx (Keluarga)" value="{{ old('family_phone', $patient->family_phone) }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-medium">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
