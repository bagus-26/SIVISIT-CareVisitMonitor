@extends('layouts.app')
@section('title', 'Daftar Petugas')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Manajemen Petugas</h1>
        <p>Kelola data petugas monitoring home care</p>
    </div>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Petugas
    </a>
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

<div class="sv-table-wrap sv-animate-in">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Petugas</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th>Telepon</th>
                    <th>Lokasi Tugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $petugas)
                    <tr>
                        <td>
                            <div style="font-weight:500;">{{ $petugas->name }}</div>
                        </td>
                        <td style="font-size:13px;color:#636366;">{{ $petugas->email }}</td>
                        <td style="font-weight:600;">{{ $petugas->nip }}</td>
                        <td>{{ $petugas->phone }}</td>
                        <td>{{ $petugas->location }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.staff.edit', $petugas) }}"
                                   class="btn btn-sm btn-outline-primary py-0" style="font-size:12px;">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.staff.destroy', $petugas) }}" method="POST"
                                      onsubmit="return confirm('Hapus petugas ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size:12px;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="sv-empty-state">
                                <i class="bi bi-inbox" style="font-size:40px;color:#D1D5DB;"></i>
                                <p>Belum ada petugas terdaftar.</p>
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
