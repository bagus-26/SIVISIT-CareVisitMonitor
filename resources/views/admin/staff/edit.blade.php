@extends('layouts.app')
@section('title', 'Edit Petugas')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Edit Data Petugas</h1>
        <p>Perbarui informasi petugas monitoring</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Error Validasi!</strong>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5>Data Petugas</h5>
            </div>
            <div class="sv-card-body">
                <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1" style="color:var(--sv-blue);"></i>Nama Lengkap
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $staff->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1" style="color:var(--sv-blue);"></i>Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nip" class="form-label">
                            <i class="bi bi-hash me-1" style="color:var(--sv-blue);"></i>NIP (Nomor Induk Petugas)
                        </label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                               id="nip" name="nip" value="{{ old('nip', $staff->nip) }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1" style="color:var(--sv-blue);"></i>Nomor Telepon
                        </label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', $staff->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">
                            <i class="bi bi-geo-alt me-1" style="color:var(--sv-blue);"></i>Lokasi Tugas
                        </label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                               id="location" name="location" value="{{ old('location', $staff->location) }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1" style="color:var(--sv-blue);"></i>Password Baru (Kosongkan jika tidak diubah)
                        </label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            <button type="button" class="btn btn-link position-absolute end-0 top-0 h-100 text-muted"
                                    onclick="togglePassword('password', 'pwIcon')" style="z-index:5;text-decoration:none;">
                                <i class="bi bi-eye" id="pwIcon"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Minimal 6 karakter</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1" style="color:var(--sv-blue);"></i>Konfirmasi Password
                        </label>
                        <div class="position-relative">
                            <input type="password" class="form-control pe-5 @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation" name="password_confirmation">
                            <button type="button" class="btn btn-link position-absolute end-0 top-0 h-100 text-muted"
                                    onclick="togglePassword('password_confirmation', 'pwConfIcon')" style="z-index:5;text-decoration:none;">
                                <i class="bi bi-eye" id="pwConfIcon"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5>Informasi Petugas</h5>
            </div>
            <div class="sv-card-body">
                <div class="mb-3">
                    <label class="form-label" style="font-size:12px;color:#8E8E93;text-transform:uppercase;font-weight:600;">Dibuat Pada</label>
                    <div style="font-size:14px;color:#1C1C1E;">{{ $staff->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:12px;color:#8E8E93;text-transform:uppercase;font-weight:600;">Terakhir Diperbarui</label>
                    <div style="font-size:14px;color:#1C1C1E;">{{ $staff->updated_at->format('d M Y H:i') }}</div>
                </div>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Ubah password hanya jika diperlukan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
@endsection
