@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Selamat Datang, {{ Auth::user()->name ?? 'Petugas' }}</h1>
        <p>Ringkasan kondisi pasien home care hari ini, {{ now()->translatedFormat('d F Y') }}.</p>
    </div>
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Pasien
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#007AFF;">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Total Pasien</div>
            <div class="stat-value" style="color:#007AFF;">{{ $totalPatients }}</div>
            <div class="stat-sub">Pasien terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#FF9500;">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-label">Kunjungan Hari Ini</div>
            <div class="stat-value" style="color:#FF9500;">{{ $todayVisits }}</div>
            <div class="stat-sub">Monitoring tercatat</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label">Perlu Kontrol</div>
            <div class="stat-value" style="color:#FF3B30;">{{ $needControl }}</div>
            <div class="stat-sub">Butuh tindak lanjut</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-4">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Status Stabil</div>
            <div class="stat-value" style="color:#34C759;">{{ $todayFinished }}</div>
            <div class="stat-sub">Selesai hari ini</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Agenda Hari Ini --}}
    <div class="col-12 col-xl-8 sv-animate-in">
        <div class="sv-table-wrap">
            <div class="sv-section-header">
                <h5><i class="bi bi-calendar3 me-2" style="color:var(--sv-blue);"></i>Agenda Kunjungan Hari Ini</h5>
                <a href="{{ route('admin.monitorings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Nama Pasien</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($todayAgenda->isEmpty())
                    <tr>
                        <td colspan="5">
                            <div class="sv-empty-state">
                                <i class="bi bi-calendar-x" style="font-size:40px;color:#D1D5DB;"></i>
                                <p>Tidak ada agenda kunjungan hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @else
                    @foreach($todayAgenda as $ag)
                    <tr>
                        <td style="font-weight:600;">
                            {{ isset($ag->monitoring_time) ? \Carbon\Carbon::parse($ag->monitoring_time)->format('H:i') : '--:--' }} WIB
                        </td>
                        <td style="font-weight:500;">{{ $ag->patient->patient_name ?? '-' }}</td>
                        <td style="color:#636366;font-size:12.5px;">{{ Str::limit($ag->patient->address ?? '-', 40) }}</td>
                        <td>
                            @php $s = strtolower($ag->status ?? ''); @endphp
                            @if(str_contains($s,'stable') || str_contains($s,'stabil'))
                                <span class="sv-badge sv-badge-stable">Stabil</span>
                            @elseif(str_contains($s,'referral') || str_contains($s,'rujukan'))
                                <span class="sv-badge sv-badge-referral">Perlu Rujukan</span>
                            @else
                                <span class="sv-badge sv-badge-control">Perlu Kontrol</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.monitorings.show', $ag->id) }}"
                               class="btn btn-sm btn-outline-primary py-0" style="font-size:12px;">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-12 col-xl-4 sv-animate-in">
        <div class="sv-card h-100">
            <h5 style="font-size:15px;font-weight:600;margin-bottom:6px;">
                <i class="bi bi-search me-2" style="color:var(--sv-blue);"></i>Cari Pasien Cepat
            </h5>
            <p style="font-size:13px;color:#636366;margin-bottom:16px;">Masukkan kode pasien atau NIK untuk melihat riwayat monitoring.</p>
            <form action="{{ route('admin.patients.search') }}" method="GET">
                <div class="mb-3">
                    <input type="text" name="q" class="form-control" placeholder="Kode pasien / NIK...">
                </div>
                <button type="submit" class="btn btn-primary w-100">Cari Data Monitoring</button>
            </form>
            <hr style="border-color:#F0F2F5;margin:20px 0;">
            <h6 style="font-size:13px;font-weight:600;color:#636366;margin-bottom:12px;">AKSI CEPAT</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.patients.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-person-plus me-1"></i> Tambah Pasien Baru
                </a>
                <a href="{{ route('admin.monitorings.create') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil-square me-1"></i> Catat Monitoring
                </a>
                <a href="{{ route('admin.monitorings.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-clipboard2-pulse me-1"></i> Lihat Semua Monitoring
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Disclaimer --}}
<div class="alert alert-warning mt-4 d-flex align-items-start gap-2" role="alert">
    <i class="bi bi-shield-exclamation" style="font-size:18px;flex-shrink:0;margin-top:1px;"></i>
    <div><strong>Disclaimer:</strong> Seluruh data bersifat simulasi/dummy. Sistem ini tidak memberikan diagnosis medis mandiri.</div>
</div>
@endsection