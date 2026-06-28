@extends('layouts.app')
@section('title', 'Dashboard')

@section('extra-styles')
<style>
.chart-card { padding: 20px 24px; }
.chart-bars { display:flex; align-items:flex-end; gap:8px; min-height:100px; padding:0 4px; }
@media (max-width: 576px) {
    .chart-card { padding: 12px !important; }
    .chart-bars { min-height:60px !important; gap:3px !important; }
    .chart-bars > div > span { font-size:8px !important; }
}
</style>
@endsection

@php
    $now = now();
    $greetingHour = (int) $now->format('G');
    $greeting = $greetingHour < 12 ? 'Selamat Pagi' : ($greetingHour < 15 ? 'Selamat Siang' : ($greetingHour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $wilayah = config('app.timezone') === 'Asia/Jakarta' ? 'WIB' : (config('app.timezone') === 'Asia/Makassar' ? 'WITA' : 'WIT');
@endphp

@section('content')
{{-- Greeting --}}
<div class="sv-page-header sv-animate-in" style="margin-bottom:16px;">
    <div>
        <h1 style="font-size:clamp(16px,4vw,22px);">{{ $greeting }}, {{ Auth::user()->name ?? 'Petugas' }}</h1>
        <p style="font-size:clamp(11.5px,2.5vw,13.5px);">Berikut adalah ringkasan operasional klinis hari ini, {{ now()->translatedFormat('l, d F Y') }} ({{ $wilayah }}).</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-2 g-md-3 mb-3 mb-md-4">
    <div class="col-6 col-md-3 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#007AFF;">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Total Pasien</div>
            <div class="stat-value" style="color:#007AFF;font-size:clamp(20px,5vw,30px);">{{ $totalPatients }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">{{ $todayVisits }} kunjungan hari ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Selesai</div>
            <div class="stat-value" style="color:#34C759;font-size:clamp(20px,5vw,30px);">{{ $todayFinished }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Status stabil hari ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#FF9500;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Perlu Kontrol</div>
            <div class="stat-value" style="color:#FF9500;font-size:clamp(20px,5vw,30px);">{{ $needControl }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Butuh tindak lanjut</div>
        </div>
    </div>
    <div class="col-6 col-md-3 sv-animate-in sv-animate-in-4">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-hospital-fill"></i></div>
            <div class="stat-label" style="font-size:clamp(9px,2vw,11px);">Rujukan</div>
            <div class="stat-value" style="color:#FF3B30;font-size:clamp(20px,5vw,30px);">{{ $needReferral }}</div>
            <div class="stat-sub" style="font-size:clamp(10px,2vw,12px);">Butuh rujukan</div>
        </div>
    </div>
</div>

<div class="row g-2 g-md-3">
    {{-- Left: Agenda + Chart --}}
    <div class="col-12 col-xl-8 d-flex flex-column gap-2 gap-md-3">
        {{-- Agenda Hari Ini --}}
        <div class="sv-table-wrap sv-animate-in">
            <div class="sv-section-header" style="padding:12px 16px;">
                <h5 style="font-size:clamp(12px,2.5vw,15px);"><i class="bi bi-calendar3 me-2" style="color:var(--sv-blue);"></i>Agenda Hari Ini</h5>
                <a href="{{ route('admin.monitorings.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:clamp(10px,2vw,13px);">Lihat Semua</a>
            </div>
            <div class="table-responsive">
            <table class="table mb-0" style="min-width:400px;">
                <thead>
                    <tr>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Jam</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Pasien</th>
                        <th class="d-none d-sm-table-cell" style="font-size:clamp(9px,1.8vw,11px);">Alamat</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Status</th>
                        <th style="font-size:clamp(9px,1.8vw,11px);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($todayAgenda->isEmpty())
                    <tr>
                        <td colspan="5">
                            <div class="sv-empty-state" style="padding:24px 12px;">
                                <i class="bi bi-calendar-x" style="font-size:28px;color:#D1D5DB;"></i>
                                <p style="font-size:clamp(12px,2.5vw,14px);">Tidak ada agenda kunjungan hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @else
                    @foreach($todayAgenda as $ag)
                    <tr>
                        <td style="font-weight:600;font-size:clamp(11px,2vw,13.5px);white-space:nowrap;">
                            {{ isset($ag->monitoring_time) ? \Carbon\Carbon::parse($ag->monitoring_time)->format('H:i') : '--:--' }}
                        </td>
                        <td style="font-weight:500;font-size:clamp(11px,2vw,13.5px);">{{ $ag->patient->patient_name ?? '-' }}</td>
                        <td class="d-none d-sm-table-cell" style="color:#636366;font-size:clamp(10px,1.8vw,12.5px);">{{ Str::limit($ag->patient->address ?? '-', 30) }}</td>
                        <td>
                            @php $s = strtolower($ag->status ?? ''); @endphp
                            @if(str_contains($s,'stable') || str_contains($s,'stabil'))
                                <span class="sv-badge sv-badge-stable" style="font-size:clamp(9px,1.6vw,11.5px);">Stabil</span>
                            @elseif(str_contains($s,'referral') || str_contains($s,'rujukan'))
                                <span class="sv-badge sv-badge-referral" style="font-size:clamp(9px,1.6vw,11.5px);">Rujukan</span>
                            @else
                                <span class="sv-badge sv-badge-control" style="font-size:clamp(9px,1.6vw,11.5px);">Kontrol</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.monitorings.show', $ag->id) }}"
                               class="btn btn-sm btn-outline-primary py-0" style="font-size:clamp(10px,1.6vw,12px);">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            </div>
        </div>

        {{-- Chart: Tren Kunjungan Harian --}}
        <div class="sv-card sv-animate-in chart-card">
            <h5 style="font-size:clamp(12px,2.5vw,15px);font-weight:600;margin:0 0 2px;">Tren Kunjungan Harian</h5>
            <p style="font-size:clamp(10px,2vw,12px);color:#8E8E93;margin:0 0 12px;">7 hari terakhir — Total: {{ array_sum($weeklyVisits) }} kunjungan</p>
            <div class="chart-bars">
                @foreach($weeklyVisits as $i => $v)
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;">
                    <div style="font-size:clamp(8px,1.8vw,10px);font-weight:600;color:#007AFF;line-height:1;">{{ $v }}</div>
                    <div style="width:100%;background:linear-gradient(180deg,#007AFF,{{ $v > 0 ? '#0058D0' : '#D8DCE6' }});border-radius:4px 4px 0 0;height:{{ max(round(($v / max($maxWeekly,1)) * 70), $v > 0 ? 10 : 3) }}px;min-height:{{ $v > 0 ? '10px' : '3px' }};transition:height 0.4s;" title="{{ $v }} kunjungan"></div>
                    <span style="font-size:clamp(7px,1.5vw,10px);color:#8E8E93;">{{ $dayLabels[$i] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right: Patient Monitoring + Quick Actions --}}
    <div class="col-12 col-xl-4 d-flex flex-column gap-2 gap-md-3">
        {{-- Pemantauan Pasien --}}
        <div class="sv-table-wrap sv-animate-in" style="padding:0;">
            <div class="sv-section-header" style="padding:12px 16px;">
                <h5 style="font-size:clamp(12px,2.5vw,15px);"><i class="bi bi-heart-pulse me-2" style="color:var(--sv-blue);"></i>Pemantauan</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        @forelse($monitorPatients as $mp)
                        <tr>
                            <td style="width:36px;padding:8px 6px 8px 12px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:{{ $mp->color }};display:flex;align-items:center;justify-content:center;color:white;font-size:10px;font-weight:700;">
                                    {{ strtoupper(substr($mp->name, 0, 1)) }}
                                </div>
                            </td>
                            <td style="font-weight:600;font-size:clamp(11px,2vw,13.5px);padding:8px 6px;">{{ $mp->name }}</td>
                            <td style="text-align:right;padding:8px 12px 8px 6px;">
                                @php $s = strtolower($mp->status ?? ''); @endphp
                                @if(str_contains($s,'stable') || str_contains($s,'stabil'))
                                    <span class="sv-badge sv-badge-stable" style="font-size:clamp(9px,1.6vw,11.5px);">Stabil</span>
                                @elseif(str_contains($s,'referral') || str_contains($s,'rujukan'))
                                    <span class="sv-badge sv-badge-referral" style="font-size:clamp(9px,1.6vw,11.5px);">Kritis</span>
                                @else
                                    <span class="sv-badge sv-badge-control" style="font-size:clamp(9px,1.6vw,11.5px);">Beresiko</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <div class="sv-empty-state" style="padding:12px;">
                                    <p style="margin:0;font-size:clamp(11px,2vw,13px);">Belum ada data monitoring.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="sv-card sv-animate-in" style="padding:16px;">
            <h5 style="font-size:clamp(12px,2.5vw,15px);font-weight:600;margin-bottom:4px;">
                <i class="bi bi-search me-2" style="color:var(--sv-blue);"></i>Cari Pasien
            </h5>
            <p style="font-size:clamp(11px,2vw,13px);color:#636366;margin-bottom:12px;">Kode pasien / NIK untuk melihat riwayat.</p>
            <form action="{{ route('admin.patients.search') }}" method="GET">
                <div class="mb-2">
                    <input type="text" name="q" class="form-control" placeholder="Kode / NIK..." style="font-size:clamp(12px,2.5vw,14px);">
                </div>
                <button type="submit" class="btn btn-primary w-100" style="font-size:clamp(11px,2vw,13.5px);">Cari Data Monitoring</button>
            </form>
            <hr style="border-color:#F0F2F5;margin:16px 0;">
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.monitorings.create') }}" class="btn btn-sm btn-outline-primary" style="font-size:clamp(11px,2vw,13.5px);">
                    <i class="bi bi-pencil-square me-1"></i> Catat Monitoring
                </a>
                <a href="{{ route('admin.monitorings.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:clamp(11px,2vw,13.5px);">
                    <i class="bi bi-clipboard2-pulse me-1"></i> Lihat Semua Monitoring
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Disclaimer --}}
<div class="alert alert-warning mt-3 mt-md-4 d-flex align-items-start gap-2" role="alert" style="font-size:clamp(11px,2vw,13.5px);">
    <i class="bi bi-shield-exclamation" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
    <div><strong>Disclaimer:</strong> Seluruh data bersifat simulasi/dummy. Sistem ini tidak memberikan diagnosis medis mandiri.</div>
</div>
@endsection