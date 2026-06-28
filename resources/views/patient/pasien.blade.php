@extends('layouts.app')
@section('title', 'Daftar Pasien')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Daftar Pasien Binaan</h1>
        <p>Kelola data pasien binaan home care SIVISIT.</p>
    </div>
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Pasien Baru
    </a>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
    <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
</div>
@endif

<div class="sv-table-wrap sv-animate-in">
    <div class="sv-section-header">
        <h5><i class="bi bi-people me-2" style="color:var(--sv-blue);"></i>Data Pasien Binaan</h5>
        <span style="font-size:12px;color:#8E8E93;">{{ $patients->count() }} pasien terdaftar</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Pasien</th>
                    <th>Nama Pasien</th>
                    <th class="d-none d-md-table-cell">Usia</th>
                    <th class="d-none d-lg-table-cell">Diagnosa Medis</th>
                    <th class="d-none d-md-table-cell">Alamat</th>
                    <th>Petugas</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $index => $p)
                    @php
                        $latestMonitoring = $p->monitorings->sortByDesc('created_at')->first();
                        $diagnosa = $latestMonitoring ? ($latestMonitoring->symptoms ?? '-') : '-';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong style="color:var(--sv-blue);">{{ $p->patient_id }}</strong></td>
                        <td class="fw-semibold">{{ $p->patient_name ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ isset($p->datebirth) ? \Carbon\Carbon::parse($p->datebirth)->age . ' Tahun' : '-' }}</td>
                        <td class="d-none d-lg-table-cell">
                            @if($diagnosa !== '-' && !empty($diagnosa))
                                <span class="sv-badge sv-badge-referral" style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $diagnosa }}</span>
                            @else
                                <span class="sv-badge bg-light text-muted">-</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell" style="font-size:12.5px;color:var(--sv-text-sub);">{{ Str::limit($p->address ?? '-', 50) }}</td>
                        <td style="font-size:12px;color:var(--sv-text-sub);">{{ $p->assignedOfficer->name ?? '-' }}</td>
                        <td style="text-align:right;">
                            <div class="d-inline-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary py-1" data-bs-toggle="modal" data-bs-target="#viewModal{{ $p->patient_id }}">Lihat</button>
                                <a href="{{ route('admin.patients.edit', $p->patient_id) }}" class="btn btn-sm btn-outline-secondary py-1 d-none d-md-inline-flex">Edit</a>
                                <form action="{{ route('admin.patients.destroy', $p->patient_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pasien ini beserta riwayat monitoringnya?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 d-none d-md-inline-flex">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="sv-empty-state">
                                <i class="bi bi-people" style="font-size:40px;color:#D1D5DB;"></i>
                                <p>Belum ada data pasien terdaftar.
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.patients.create') }}">Tambah pasien pertama →</a>
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modals for details --}}
@foreach ($patients as $p)
    <div class="modal fade" id="viewModal{{ $p->patient_id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $p->patient_id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background:linear-gradient(135deg,var(--sv-navy),var(--sv-navy-mid));color:white;padding:20px 24px;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" id="viewModalLabel{{ $p->patient_id }}">Detail Pasien: {{ $p->patient_name ?? '' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <span class="text-muted small d-block">ID Pasien / No. RM</span>
                            <strong>{{ $p->patient_id ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">NIK Pasien</span>
                            <strong>{{ $p->nik_dummy ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Kategori &amp; Gender</span>
                            <strong>{{ $p->patient_category ?? '-' }} ({{ $p->gender === 'Male' ? 'Laki-laki' : 'Perempuan' }})</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Tanggal Lahir</span>
                            <strong>{{ isset($p->datebirth) ? \Carbon\Carbon::parse($p->datebirth)->format('d M Y') : '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Alamat Lengkap</span>
                            <strong><i class="bi bi-geo-alt me-1"></i>{{ $p->address ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Nomor Telepon Darurat</span>
                            <strong><i class="bi bi-telephone me-1"></i>{{ $p->family_phone ?? '-' }}</strong>
                        </div>
                    </div>

                    <hr style="border-color:#F0F2F5;">

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-clipboard2-pulse me-2"></i>Riwayat Monitoring Kesehatan</h6>
                    @if($p->monitorings->isEmpty())
                        <div class="alert alert-light text-muted small mb-0">Belum ada catatan monitoring kesehatan untuk pasien ini.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle text-center small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal &amp; Jam</th>
                                        <th>Tensi</th>
                                        <th>Nadi</th>
                                        <th>Nafas</th>
                                        <th>Suhu</th>
                                        <th>Saturasi O2</th>
                                        <th>Gejala/Kondisi</th>
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
                                            <td>
                                                @if($mon->status === 'Stable')
                                                    <span class="badge bg-success-subtle text-success">Stable</span>
                                                @elseif($mon->status === 'Need Referral')
                                                    <span class="badge bg-danger-subtle text-danger">Need Referral</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">Need Control</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid #F0F2F5;padding:16px 24px;border-radius:0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.monitorings.create', ['patient_id' => $p->patient_id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square me-1"></i> Tambah Monitoring
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection