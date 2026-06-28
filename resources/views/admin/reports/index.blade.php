@extends('layouts.app')
@section('title', 'Laporan')

@section('extra-styles')
<style>
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: absolute; left: 0; top: 0; width: 100%; }
    .sv-sidebar, .sv-topbar, .sv-footer, .btn, .no-print { display: none !important; }
    .sv-main { margin-left: 0 !important; }
    .sv-content { padding: 0 !important; }
    .sv-card { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
    .sv-stat-card { break-inside: avoid; }
    .progress { border: 1px solid #ddd; }
    .table { font-size: 11px !important; }
    .table th { background: #f5f5f5 !important; }
}
</style>
@endsection

@section('content')
<div class="sv-page-header sv-animate-in no-print" style="margin-bottom:16px;">
    <div>
        <h1 style="font-size:clamp(16px,4vw,22px);">Laporan Monitoring</h1>
        <p style="font-size:clamp(11.5px,2.5vw,13.5px);">Analisis dan statistik monitoring home care</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button onclick="window.print()" class="btn btn-primary" style="font-size:clamp(11px,2vw,13.5px);">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
        <a href="{{ route('admin.reports.export-pdf') }}" class="btn btn-outline-secondary" style="font-size:clamp(11px,2vw,13.5px);">
            <i class="bi bi-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('admin.reports.export-excel') }}" class="btn btn-outline-secondary" style="font-size:clamp(11px,2vw,13.5px);">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
    </div>
</div>

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show no-print" role="alert" style="font-size:clamp(11px,2vw,13px);">
        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="print-area">
<div class="row g-2 g-md-3 mb-3 mb-md-4 sv-animate-in">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="sv-card" style="padding:14px;">
            <div class="sv-card-body" style="padding:0;">
                <label class="form-label" style="font-size:clamp(10px,2vw,12px);color:#8E8E93;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Pilih Periode</label>
                <form method="GET">
                    <input type="month" name="month" class="form-control" value="{{ $monthYear }}" onchange="this.form.submit()" style="font-size:clamp(12px,2.5vw,14px);">
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Ringkas --}}
<div class="row g-2 g-md-3 mb-3 mb-md-4">
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#007AFF;">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Total Pasien</div>
            <div class="stat-value" style="color:#007AFF;font-size:clamp(20px,5vw,30px);">{{ $totalPatients }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Pasien terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#FF9500;">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Total Monitoring</div>
            <div class="stat-value" style="color:#FF9500;font-size:clamp(20px,5vw,30px);">{{ $totalMonitorings }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Periode {{ date('M Y', strtotime($monthYear . '-01')) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Status Stabil</div>
            <div class="stat-value" style="color:#34C759;font-size:clamp(20px,5vw,30px);">{{ $totalMonitoringsStable }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Kondisi pasien stabil</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-4">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Perlu Kontrol</div>
            <div class="stat-value" style="color:#FF3B30;font-size:clamp(20px,5vw,30px);">{{ $totalMonitoringsNeedControl }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Butuh tindak lanjut</div>
        </div>
    </div>
</div>

<div class="row g-2 g-md-3">
    {{-- Monitoring Harian --}}
    <div class="col-12 col-xl-8 sv-animate-in">
        <div class="sv-table-wrap">
            <div class="sv-section-header" style="padding:10px 14px;">
                <h5 style="font-size:clamp(12px,2.5vw,15px);"><i class="bi bi-calendar-event me-2" style="color:var(--sv-blue);"></i>Monitoring Harian</h5>
            </div>
            <div class="table-responsive">
            <table class="table mb-0" style="font-size:clamp(11px,2vw,13px);min-width:400px;">
                <thead>
                    <tr>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Tanggal</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Monitoring</th>
                        <th class="d-none d-sm-table-cell" style="font-size:clamp(9px,1.8vw,11px);">Stabil</th>
                        <th class="d-none d-sm-table-cell" style="font-size:clamp(9px,1.8vw,11px);">Kontrol</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyMonitorings as $dm)
                    <tr>
                        <td style="font-weight:600;font-size:clamp(10px,2vw,13px);">{{ date('d M', strtotime($dm->date)) }}</td>
                        <td style="font-size:clamp(10px,2vw,13px);">{{ $dm->count }}</td>
                        <td class="d-none d-sm-table-cell"><span class="sv-badge sv-badge-stable" style="font-size:clamp(9px,1.6vw,11.5px);">{{ $dm->stable_count }}</span></td>
                        <td class="d-none d-sm-table-cell"><span class="sv-badge sv-badge-control" style="font-size:clamp(9px,1.6vw,11.5px);">{{ $dm->control_count }}</span></td>
                        <td>
                            @php
                                $percentage = $dm->count > 0 ? round(($dm->stable_count / $dm->count) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:5px;background:#E5E7EB;min-width:40px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percentage }}%;"></div>
                                </div>
                                <span style="font-size:clamp(9px,1.6vw,11.5px);white-space:nowrap;">{{ $percentage }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="sv-empty-state" style="padding:24px 12px;">
                                <i class="bi bi-graph-up" style="font-size:28px;color:#D1D5DB;"></i>
                                <p style="font-size:clamp(12px,2.5vw,14px);">Belum ada data monitoring untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    {{-- Monitoring per Petugas --}}
    <div class="col-12 col-xl-4 sv-animate-in">
        <div class="sv-card" style="padding:14px;">
            <h5 style="font-size:clamp(12px,2.5vw,15px);margin:0 0 12px;"><i class="bi bi-person-check me-2" style="color:var(--sv-blue);"></i>Per Petugas</h5>
            @forelse($monitoringsByStaff as $staff)
                <div class="mb-3 pb-3" style="border-bottom:1px solid #E5E7EB;">
                    <div style="font-weight:600;font-size:clamp(11px,2vw,13.5px);margin-bottom:4px;">{{ $staff->name }}</div>
                    <div style="font-size:clamp(10px,2vw,12px);color:#636366;margin-bottom:6px;">
                        Total: <strong>{{ $staff->total }}</strong> | Stabil: <strong style="color:#34C759;">{{ $staff->stable }}</strong>
                    </div>
                    @php
                        $staffPercentage = $staff->total > 0 ? round(($staff->stable / $staff->total) * 100) : 0;
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height:5px;background:#E5E7EB;">
                            <div class="progress-bar bg-success" style="width: {{ $staffPercentage }}%;"></div>
                        </div>
                        <small style="color:#8E8E93;font-size:clamp(9px,1.6vw,11px);">{{ $staffPercentage }}%</small>
                    </div>
                </div>
            @empty
                <div class="sv-empty-state" style="padding:16px 0;">
                    <i class="bi bi-inbox" style="font-size:24px;color:#D1D5DB;"></i>
                    <p style="font-size:clamp(11px,2vw,12px);">Belum ada data petugas.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="row g-2 g-md-3 mt-2 mt-md-3">
    {{-- Pasien per Lokasi --}}
    <div class="col-12 col-xl-6 sv-animate-in">
        <div class="sv-card" style="padding:14px;">
            <h5 style="font-size:clamp(12px,2.5vw,15px);margin:0 0 12px;"><i class="bi bi-geo-alt me-2" style="color:var(--sv-blue);"></i>Sebaran Pasien per Lokasi</h5>
            <div class="table-responsive">
            <table class="table table-sm mb-0" style="font-size:clamp(11px,2vw,13px);min-width:300px;">
                <thead>
                    <tr>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Lokasi</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Jumlah</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patientsByLocation as $location)
                    <tr>
                        <td style="font-weight:500;font-size:clamp(10px,2vw,13px);">{{ $location->location ?? 'Tidak Ada' }}</td>
                        <td style="font-size:clamp(10px,2vw,13px);">{{ $location->count }}</td>
                        <td>
                            @php
                                $locPercentage = $totalPatients > 0 ? round(($location->count / $totalPatients) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:5px;background:#E5E7EB;min-width:40px;">
                                    <div class="progress-bar bg-info" style="width: {{ $locPercentage }}%;"></div>
                                </div>
                                <span style="font-size:clamp(9px,1.6vw,11px);white-space:nowrap;">{{ $locPercentage }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="text-center py-3">
                                <small class="text-muted" style="font-size:clamp(10px,2vw,12px);">Belum ada data lokasi pasien</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    {{-- Catatan Laporan --}}
    <div class="col-12 col-xl-6 sv-animate-in">
        <div class="sv-card" style="padding:14px;">
            <h5 style="font-size:clamp(12px,2.5vw,15px);margin:0 0 12px;"><i class="bi bi-info-circle me-2" style="color:var(--sv-blue);"></i>Informasi Laporan</h5>
            <div class="alert alert-info mb-3" style="font-size:clamp(10px,2vw,13px);">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Periode:</strong> {{ date('d M Y', strtotime($monthYear . '-01')) }} - {{ date('d M Y', strtotime($monthYear . '-01 +1 month -1 day')) }}
            </div>
            <ul style="font-size:clamp(11px,2vw,13px);line-height:1.8;color:#636366;list-style:none;padding:0;margin:0;">
                <li><i class="bi bi-check-circle text-success me-2"></i>Total Pasien Terdaftar: <strong>{{ $totalPatients }}</strong></li>
                <li><i class="bi bi-check-circle text-success me-2"></i>Total Monitoring: <strong>{{ $totalMonitorings }}</strong></li>
                <li><i class="bi bi-check-circle text-success me-2"></i>Kondisi Stabil: <strong>{{ $totalMonitoringsStable }}</strong></li>
                <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>Perlu Kontrol: <strong>{{ $totalMonitoringsNeedControl }}</strong></li>
            </ul>
        </div>
    </div>
</div>
</div>
@endsection
