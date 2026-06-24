@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Pengaturan Akun</h1>
        <p>Kelola profil dan keamanan akun Anda</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Error!</strong>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    {{-- Update Profil --}}
    <div class="col-12 col-lg-6">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-person-circle me-2"></i>Update Profil</h5>
            </div>
            <div class="sv-card-body">
                <form action="{{ route('admin.settings.update-profile') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1" style="color:var(--sv-blue);"></i>Nama Lengkap
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1" style="color:var(--sv-blue);"></i>Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1" style="color:var(--sv-blue);"></i>Nomor Telepon
                        </label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label">
                            <i class="bi bi-geo-alt me-1" style="color:var(--sv-blue);"></i>Lokasi Tugas
                        </label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                               id="location" name="location" value="{{ old('location', Auth::user()->location) }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-1"></i>Simpan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Ubah Password --}}
    <div class="col-12 col-lg-6">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-lock-fill me-2"></i>Ubah Password</h5>
            </div>
            <div class="sv-card-body">
                <form action="{{ route('admin.settings.change-password') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="bi bi-lock me-1" style="color:var(--sv-blue);"></i>Password Saat Ini
                        </label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            <i class="bi bi-key me-1" style="color:var(--sv-blue);"></i>Password Baru
                        </label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                               id="new_password" name="new_password" required>
                        <small class="text-muted d-block mt-1">Minimal 6 karakter</small>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1" style="color:var(--sv-blue);"></i>Konfirmasi Password Baru
                        </label>
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                               id="new_password_confirmation" name="new_password_confirmation" required>
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-arrow-repeat me-1"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>

        <div class="sv-card sv-animate-in" style="margin-top:15px;">
            <div class="sv-card-header">
                <h5><i class="bi bi-shield-check me-2"></i>Informasi Keamanan</h5>
            </div>
            <div class="sv-card-body">
                <div style="font-size:13px;line-height:1.8;color:#636366;">
                    <p><strong>Role:</strong> {{ Auth::user()->role == 'admin' ? 'Administrator' : 'Petugas' }}</p>
                    <p><strong>Email Terverifikasi:</strong>
                        @if(Auth::user()->email_verified_at)
                            <span class="text-success"><i class="bi bi-check-circle"></i> Ya</span>
                        @else
                            <span class="text-warning"><i class="bi bi-exclamation-circle"></i> Belum</span>
                        @endif
                    </p>
                    <p><strong>Terakhir Login:</strong> {{ optional(Auth::user()->updated_at)->diffForHumans() ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Informasi Sistem --}}
<div class="row" style="margin-top:20px;">
    <div class="col-12">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-info-circle me-2"></i>Informasi Sistem</h5>
            </div>
            <div class="sv-card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <ul style="font-size:13px;line-height:2;color:#636366;list-style:none;padding:0;">
                            <li><strong>Nama Aplikasi:</strong> SIVISIT CareVisit Monitor</li>
                            <li><strong>Versi:</strong> 1.0.0</li>
                            <li><strong>PHP Version:</strong> {{ phpversion() }}</li>
                            <li><strong>Database:</strong> MySQL/MariaDB</li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <ul style="font-size:13px;line-height:2;color:#636366;list-style:none;padding:0;">
                            <li><strong>Tanggal Sistem:</strong> {{ now()->format('d F Y H:i:s') }}</li>
                            <li><strong>Timezone:</strong> Asia/Jakarta</li>
                            <li><strong>User ID:</strong> #{{ Auth::user()->id }}</li>
                            <li><strong>Last Activity:</strong> {{ Auth::user()->updated_at->format('d F Y H:i:s') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
