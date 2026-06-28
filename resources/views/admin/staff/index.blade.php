@extends('layouts.app')
@section('title', 'Daftar Petugas')

@section('extra-styles')
<style>
@media (max-width:576px) {
    .staff-table th, .staff-table td { font-size:11.5px !important; padding:8px 6px !important; }
    .staff-actions .btn { font-size:10px !important; padding:3px 8px !important; }
}
</style>
@endsection

@section('content')
<div class="sv-page-header sv-animate-in" style="margin-bottom:16px;">
    <div>
        <h1 style="font-size:clamp(16px,4vw,22px);">Manajemen Petugas</h1>
        <p style="font-size:clamp(11.5px,2.5vw,13.5px);">Kelola data petugas monitoring home care</p>
    </div>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary" style="font-size:clamp(11px,2vw,13.5px);">
        <i class="bi bi-person-plus me-1"></i> Tambah Petugas
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:clamp(11px,2vw,13px);">
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Error!</strong>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:clamp(11px,2vw,13px);">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="sv-table-wrap sv-animate-in">
    <div class="table-responsive">
        <table class="table staff-table" style="min-width:450px;">
            <thead>
                <tr>
                    <th style="font-size:clamp(9px,1.8vw,11px);">Nama Petugas</th>
                    <th class="d-none d-sm-table-cell" style="font-size:clamp(9px,1.8vw,11px);">Email</th>
                    <th class="d-none d-md-table-cell" style="font-size:clamp(9px,1.8vw,11px);">NIP</th>
                    <th class="d-none d-sm-table-cell" style="font-size:clamp(9px,1.8vw,11px);">Telepon</th>
                    <th class="d-none d-lg-table-cell" style="font-size:clamp(9px,1.8vw,11px);">Lokasi Tugas</th>
                    <th style="font-size:clamp(9px,1.8vw,11px);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $petugas)
                    <tr>
                        <td>
                            <div style="font-weight:500;font-size:clamp(11px,2vw,13.5px);">{{ $petugas->name }}</div>
                        </td>
                        <td class="d-none d-sm-table-cell" style="font-size:clamp(11px,2vw,13px);color:#636366;">{{ $petugas->email }}</td>
                        <td class="d-none d-md-table-cell" style="font-weight:600;font-size:clamp(11px,2vw,13px);">{{ $petugas->nip }}</td>
                        <td class="d-none d-sm-table-cell" style="font-size:clamp(11px,2vw,13px);">{{ $petugas->phone }}</td>
                        <td class="d-none d-lg-table-cell" style="font-size:clamp(11px,2vw,13px);">{{ $petugas->location }}</td>
                        <td>
                            <div class="d-flex gap-1 staff-actions">
                                <a href="{{ route('admin.staff.edit', $petugas) }}"
                                   class="btn btn-sm btn-outline-primary py-0" style="font-size:clamp(10px,1.6vw,12px);">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.staff.destroy', $petugas) }}" method="POST"
                                      onsubmit="return confirm('Hapus petugas ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size:clamp(10px,1.6vw,12px);">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="sv-empty-state" style="padding:24px 12px;">
                                <i class="bi bi-inbox" style="font-size:28px;color:#D1D5DB;"></i>
                                <p style="font-size:clamp(12px,2.5vw,14px);">Belum ada petugas terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($staff->hasPages())
        <nav aria-label="Page navigation" style="margin-top: 20px;">
            {{ $staff->links() }}
        </nav>
    @endif
</div>
@endsection
