@extends('layouts.app')
@section('title', 'Registrasi Pasien')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Registrasi Pasien Baru</h1>
        <p>Silakan isi formulir kontrol kunjungan dan rekam medis pasien di bawah ini.</p>
    </div>
    <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary">← Kembali</a>
</div>

@if($errors->any())
<div class="alert alert-danger d-flex align-items-start gap-2 mb-4 sv-animate-in" role="alert">
    <span>⚠️</span>
    <div>
        @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
        @endforeach
    </div>
</div>
@endif

<form action="{{ route('admin.patients.store') }}" method="POST" id="patientForm">
    @csrf
    <div class="row g-3">
        {{-- Section 1: Informasi Petugas & RM --}}
        <div class="col-md-6 sv-animate-in sv-animate-in-1">
            <div class="form-section h-100 mb-0">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#E8F1FF;">📋</div>
                    <div>
                        <h6>Informasi Petugas Medis</h6>
                        <p>Detail petugas pemeriksa saat registrasi</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="mb-3">
                        <label for="assigned_officer_id" class="form-label">Pilih Petugas Kesehatan <span style="color:#FF3B30;">*</span></label>
                        <select name="assigned_officer_id" id="assigned_officer_id" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Petugas --</option>
                            @foreach($petugas as $p)
                                <option value="{{ $p->id }}" {{ old('assigned_officer_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->nip ?? 'NIP: -' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="validation-hint">Pilih petugas yang akan bertanggung jawab atas pasien ini</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Diregistrasi Oleh</label>
                        <input type="text" class="form-control" style="background:#F2F4F7;color:#636366;" value="{{ Auth::user()->name }} ({{ Auth::user()->role }})" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 sv-animate-in sv-animate-in-1">
            <div class="form-section h-100 mb-0">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#FFF0EF;">🩺</div>
                    <div>
                        <h6>Rekam Medis &amp; Jadwal Kontrol</h6>
                        <p>Buat kode rekam medis dan tetapkan jadwal pertama</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">Nomor Rekam Medis (No. RM) <span style="color:#FF3B30;">*</span></label>
                        <input type="text" name="patient_id" id="patient_id" class="form-control" value="{{ old('patient_id', $nextId) }}" readonly required>
                        <div class="validation-hint">Nomor RM akan terisi otomatis</div>
                    </div>
                    <div class="mb-0">
                        <label for="monitoring_date" class="form-label">Jadwal Kontrol Pasien</label>
                        <input type="datetime-local" name="monitoring_date" id="monitoring_date" class="form-control" value="{{ old('monitoring_date') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Data Pribadi --}}
        <div class="col-12 sv-animate-in sv-animate-in-2">
            <div class="form-section mb-0">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#E8F8ED;">👤</div>
                    <div>
                        <h6>Data Pribadi &amp; Identitas Pasien</h6>
                        <p>Lengkapi informasi identitas diri pasien binaan</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nik_dummy" class="form-label">NIK Pasien (16 digit) <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="nik_dummy" id="nik_dummy" class="form-control" placeholder="Masukkan 16 digit NIK" value="{{ old('nik_dummy') }}" required pattern="\d{16}">
                        </div>

                        <div class="col-md-6">
                            <label for="patient_name" class="form-label">Nama Lengkap Pasien <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Masukkan nama pasien" value="{{ old('patient_name') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="patient_category" class="form-label">Kategori Pasien <span style="color:#FF3B30;">*</span></label>
                            <select name="patient_category" id="patient_category" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                <option value="Balita" {{ old('patient_category') == 'Balita' ? 'selected' : '' }}>👶 Balita (0 - 5 Tahun)</option>
                                <option value="Anak-anak" {{ old('patient_category') == 'Anak-anak' ? 'selected' : '' }}>👦 Anak-anak</option>
                                <option value="Dewasa" {{ old('patient_category') == 'Dewasa' ? 'selected' : '' }}>🧑 Dewasa</option>
                                <option value="Lansia" {{ old('patient_category') == 'Lansia' ? 'selected' : '' }}>🧓 Lansia (Lanjut Usia)</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="gender" class="form-label">Jenis Kelamin <span style="color:#FF3B30;">*</span></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Gender --</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>👨 Laki-laki</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>👩 Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" class="form-control" placeholder="Contoh: Malang" value="Malang">
                        </div>

                        <div class="col-md-3">
                            <label for="datebirth" class="form-label">Tanggal Lahir <span style="color:#FF3B30;">*</span></label>
                            <input type="date" name="datebirth" id="datebirth" class="form-control" value="{{ old('datebirth') }}" required>
                        </div>

                        <div class="col-md-8">
                            <label for="address" class="form-label">Alamat Rumah Lengkap <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan" value="{{ old('address') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="family_phone" class="form-label">Nomor Telepon Keluarga <span style="color:#FF3B30;">*</span></label>
                            <input type="tel" name="family_phone" id="family_phone" class="form-control" placeholder="Contoh: 081234xxxxxx" value="{{ old('family_phone') }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="col-12 sv-animate-in sv-animate-in-3 mt-4">
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-secondary">🔄 Reset</button>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">💾 Simpan Data Pasien</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('patientForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.textContent = 'Menyimpan...';
        btn.disabled = true;
    });
</script>
@endsection