@extends('layouts.app')
@section('title', 'Dashboard')

@php
    $greetingHour = (int) date('G');
    $greeting = $greetingHour < 12 ? 'Selamat Pagi' : ($greetingHour < 15 ? 'Selamat Siang' : ($greetingHour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

@section('content')
{{-- Greeting --}}
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>{{ $greeting }}, {{ Auth::user()->name ?? 'Petugas' }}</h1>
        <p>Berikut adalah ringkasan operasional klinis hari ini, {{ now()->translatedFormat('l, d F Y') }}.</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#007AFF;">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Total Pasien Binaan</div>
            <div class="stat-value" style="color:#007AFF;">{{ $totalPatients }}</div>
            <div class="stat-sub">{{ $todayVisits }} kunjungan hari ini</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Monitoring Selesai</div>
            <div class="stat-value" style="color:#34C759;">{{ $todayFinished }}</div>
            <div class="stat-sub">Status stabil hari ini</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#FF9500;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label">Perlu Kontrol</div>
            <div class="stat-value" style="color:#FF9500;">{{ $needControl }}</div>
            <div class="stat-sub">Butuh tindak lanjut</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-4">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-hospital-fill"></i></div>
            <div class="stat-label">Perlu Rujukan</div>
            <div class="stat-value" style="color:#FF3B30;">{{ $needReferral }}</div>
            <div class="stat-sub">Emergency action required</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Left: Agenda + Chart --}}
    <div class="col-12 col-xl-8 d-flex flex-column gap-3">
        {{-- Agenda Hari Ini --}}
        <div class="sv-table-wrap sv-animate-in">
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

        {{-- Chart: Tren Kunjungan Harian --}}
        <div class="sv-card sv-animate-in" style="padding:20px 24px;">
            <h5 style="font-size:15px;font-weight:600;margin:0 0 4px;">Tren Kunjungan Harian</h5>
            <p style="font-size:12px;color:#8E8E93;margin:0 0 16px;">Rekapitulasi 7 hari terakhir</p>
            <div style="display:flex;align-items:flex-end;gap:8px;height:120px;padding:0 4px;">
                @foreach($weeklyVisits as $i => $v)
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
                    <div style="width:100%;background:#007AFF;border-radius:6px 6px 0 0;height:{{ max(round(($v / $maxWeekly) * 100), 4) }}%;min-height:4px;transition:height 0.4s;" title="{{ $v }} kunjungan"></div>
                    <span style="font-size:10px;color:#8E8E93;">{{ $dayLabels[$i] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right: Patient Monitoring + Quick Actions --}}
    <div class="col-12 col-xl-4 d-flex flex-column gap-3">
        {{-- Pemantauan Pasien --}}
        <div class="sv-table-wrap sv-animate-in" style="padding:0;">
            <div class="sv-section-header">
                <h5><i class="bi bi-heart-pulse me-2" style="color:var(--sv-blue);"></i>Pemantauan Pasien</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        @forelse($monitorPatients as $mp)
                        <tr>
                            <td style="width:44px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:{{ $mp->color }};display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:700;">
                                    {{ strtoupper(substr($mp->name, 0, 1)) }}
                                </div>
                            </td>
                            <td style="font-weight:600;font-size:13.5px;">{{ $mp->name }}</td>
                            <td style="text-align:right;">
                                @php $s = strtolower($mp->status ?? ''); @endphp
                                @if(str_contains($s,'stable') || str_contains($s,'stabil'))
                                    <span class="sv-badge sv-badge-stable">Stabil</span>
                                @elseif(str_contains($s,'referral') || str_contains($s,'rujukan'))
                                    <span class="sv-badge sv-badge-referral">Kritis</span>
                                @else
                                    <span class="sv-badge sv-badge-control">Beresiko</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <div class="sv-empty-state" style="padding:16px;">
                                    <p style="margin:0;font-size:13px;">Belum ada data monitoring.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="sv-card sv-animate-in">
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
                <a href="{{ route('admin.monitorings.create') }}" class="btn btn-sm btn-outline-primary">
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