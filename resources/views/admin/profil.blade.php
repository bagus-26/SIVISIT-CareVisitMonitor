@extends('layouts.app')
@section('title', 'Profil Petugas')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Profil Petugas</h1>
        <p>Informasi akun dan data diri petugas kesehatan.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger d-flex align-items-start gap-2 mb-4 sv-animate-in" role="alert">
    <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;margin-top:2px;"></i>
    <div>
        @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
        @endforeach
    </div>
</div>
@endif

<div class="row g-3">
    {{-- Profile Hero Card --}}
    <div class="col-12 col-lg-4 sv-animate-in sv-animate-in-1">
        <div class="sv-profile-hero">
            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="sv-profile-avatar">{{ strtoupper(substr($user->name ?? 'P', 0, 1)) }}</div>
                <div class="sv-profile-info">
                    <div class="profile-name" style="color:white; font-size: 18px; font-weight:700;">{{ $user->name }}</div>
                    <div class="badge-role">
                        <i class="bi bi-heart-pulse-fill me-1"></i>{{ $user->role ?? 'Petugas Kesehatan' }}
                    </div>
                    <div class="profile-id" style="color:rgba(255,255,255,0.5); font-size:11px; margin-top:4px;">ID: PM-{{ date('Y') }}-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <div class="profile-stat-row">
                <div class="profile-stat-item">
                    <div class="psi-val">{{ $totalPatients }}</div>
                    <div class="psi-lbl">Pasien Binaan</div>
                </div>
                <div class="profile-stat-item">
                    <div class="psi-val">{{ $totalMonitoring }}</div>
                    <div class="psi-lbl">Total Kunjungan</div>
                </div>
            </div>

            <div class="mt-4" style="position:relative;z-index:1;">
                <button class="edit-modal-btn w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil me-1"></i> Edit Profil
                </button>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="sv-card">
            <h6 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);margin-bottom:14px;">Aksi Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.patients.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-person-plus me-1"></i> Tambah Pasien Baru
                </a>
                <a href="{{ route('admin.monitorings.create') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil-square me-1"></i> Catat Monitoring
                </a>
                <a href="{{ route('admin.rekam-medis.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-folder2-open me-1"></i> Lihat Rekam Medis
                </a>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger mt-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </a>
            </div>
        </div>
    </div>

    {{-- Info Detail --}}
    <div class="col-12 col-lg-8 sv-animate-in sv-animate-in-2">
        {{-- Informasi Pribadi --}}
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-person-circle me-2"></i> Informasi Pribadi
            </div>
            <div class="info-card-body">
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#E8F1FF;"><i class="bi bi-person-fill" style="color:#0058D0;"></i></div>
                    <div class="info-content">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#E8F8ED;"><i class="bi bi-tag-fill" style="color:#1A7A35;"></i></div>
                    <div class="info-content">
                        <div class="info-label">NIP / Kode Petugas</div>
                        <div class="info-value" style="font-family:monospace;font-size:15px;">{{ $user->nip ?? ('19' . date('Y') . '0' . str_pad($user->id, 6, '0', STR_PAD_LEFT)) }}</div>
                    </div>
                </div>
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#FFF4E5;"><i class="bi bi-hospital-fill" style="color:#8A4E00;"></i></div>
                    <div class="info-content">
                        <div class="info-label">Jabatan / Role</div>
                        <div class="info-value">{{ $user->role ?? 'Petugas Kesehatan' }}</div>
                    </div>
                </div>
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#F5EEFF;"><i class="bi bi-calendar3" style="color:#7B35A0;"></i></div>
                    <div class="info-content">
                        <div class="info-label">Bergabung Sejak</div>
                        <div class="info-value">{{ $user->created_at ? $user->created_at->format('d M Y') : date('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Kontak --}}
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-telephone-fill me-2"></i> Informasi Kontak
            </div>
            <div class="info-card-body">
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#E8F1FF;"><i class="bi bi-envelope-fill" style="color:#0058D0;"></i></div>
                    <div class="info-content">
                        <div class="info-label">Alamat Email</div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#E8F8ED;"><i class="bi bi-phone-fill" style="color:#1A7A35;"></i></div>
                    <div class="info-content">
                        <div class="info-label">No. HP (Dummy)</div>
                        <div class="info-value">{{ $user->phone ?? '08' . str_pad($user->id + 22, 9, '2', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="sv-info-row">
                    <div class="info-icon" style="background:#FFF0EF;"><i class="bi bi-geo-alt-fill" style="color:#C0291F;"></i></div>
                    <div class="info-content">
                        <div class="info-label">Lokasi / Wilayah Tugas</div>
                        <div class="info-value">{{ $user->location ?? 'Puskesmas Dinoyo, Malang' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Disclaimer --}}
        <div class="sv-card" style="background:#FFFBEC;border-color:#FDEAB0;">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <i class="bi bi-shield-exclamation" style="font-size:22px;color:#8A5E00;flex-shrink:0;margin-top:2px;"></i>
                <div>
                    <div style="font-weight:700;font-size:13px;color:#7A5500;margin-bottom:4px;">Data Simulasi Akademik</div>
                    <p style="font-size:12.5px;color:#8A6200;line-height:1.7;margin:0;">
                        Seluruh data profil dan informasi pasien pada sistem ini bersifat simulasi/dummy
                        untuk keperluan UAS Pemrograman Web — Informatika Kesehatan.
                        Tidak ada data nyata pasien yang digunakan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <form action="{{ route('admin.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header" style="border-bottom:1px solid #F0F2F5;padding:20px 24px;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" style="font-size:16px;font-weight:700;">
                        <i class="bi bi-pencil me-2"></i>Edit Profil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span style="color:#FF3B30;">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Nama petugas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan / Role</label>
                        <input type="text" name="role" class="form-control"
                               value="{{ old('role', $user->role ?? 'Petugas Kesehatan') }}"
                               placeholder="Contoh: Perawat, Bidan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span style="color:#FF3B30;">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $user->email) }}"
                               placeholder="email@example.com" required>
                    </div>
                    <div class="p-3 rounded" style="background:#F8F9FA;font-size:12px;color:#636366;">
                        <i class="bi bi-info-circle me-1"></i> Perubahan akan disimpan ke database secara permanen.
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #F0F2F5;padding:16px 24px;border-radius:0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-floppy me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
