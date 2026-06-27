@extends('layouts.app')
@section('title', 'Cari Pasien')



@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Pencarian Pasien</h1>
        <p>Temukan pasien berdasarkan nama, kode RM, atau NIK dummy.</p>
    </div>
</div>

{{-- Search Hero --}}
<div class="search-hero sv-animate-in">
    <h2>🔍 Cari Data Pasien</h2>
    <p>Masukkan nama pasien, kode RM (contoh: P001), atau NIK dummy 16 digit.</p>
    <form action="{{ route('admin.patients.search') }}" method="GET">
        <div class="search-hero-input" style="position:relative;">
            <span class="search-ico">🔍</span>
            <input type="text" name="q"
                   value="{{ $query }}"
                   placeholder="Nama, kode RM, atau NIK pasien..."
                   autofocus>
            <button type="submit">Cari</button>
        </div>
    </form>
</div>

{{-- Results --}}
@if($query !== '')

    @if($results->isEmpty())
    {{-- No result --}}
    <div class="sv-card sv-animate-in text-center" style="padding:48px 24px;">
        <div style="font-size:48px;margin-bottom:12px;">🔍</div>
        <h5 style="font-weight:700;color:var(--sv-text-main);">Pasien tidak ditemukan</h5>
        <p style="color:var(--sv-text-muted);font-size:13.5px;margin-bottom:20px;">
            Tidak ada pasien dengan kata kunci <strong>"{{ $query }}"</strong>.
            Pastikan nama, kode RM, atau NIK yang dimasukkan benar.
        </p>
        <a href="{{ route('admin.patients.create') }}" class="btn btn-primary btn-sm">➕ Tambah Pasien Baru</a>
    </div>

    @elseif($results->count() > 1)
    {{-- Multiple results --}}
    <div class="sv-animate-in">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h5 style="font-size:15px;font-weight:700;margin:0;">
                Ditemukan <span style="color:var(--sv-blue);">{{ $results->count() }}</span> pasien
            </h5>
            <span style="font-size:12px;color:var(--sv-text-muted);">Klik untuk lihat riwayat monitoring</span>
        </div>
        @foreach($results as $p)
        @php
            $gender = ($p->gender ?? '') === 'Male' ? '👨' : '👩';
            $monCount = $p->monitorings->count();
            $latestMon = $p->monitorings->sortByDesc('monitoring_date')->first();
            $age = $p->datebirth ? \Carbon\Carbon::parse($p->datebirth)->age . ' Thn' : '-';
        @endphp
        <a href="{{ route('admin.patients.search', ['q' => $p->patient_id]) }}" class="result-list-item">
            <div class="result-avatar">{{ $gender }}</div>
            <div style="flex:1;">
                <div style="font-weight:700;font-size:14px;color:var(--sv-text-main);">
                    {{ $p->patient_name ?? '-' }}
                </div>
                <div style="font-size:12px;color:var(--sv-text-muted);margin-top:2px;">
                    {{ $p->patient_id ?? '' }}
                    · {{ $age }}
                    · {{ $p->patient_category ?? '' }}
                </div>
                <div style="font-size:11.5px;color:var(--sv-text-muted);margin-top:2px;">
                    📍 {{ Str::limit($p->address ?? '-', 60) }}
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                @if($latestMon)
                    @if($latestMon->status === 'Stable')
                        <span class="sv-badge sv-badge-stable">✅ Stabil</span>
                    @elseif($latestMon->status === 'Need Referral')
                        <span class="sv-badge sv-badge-referral">🚨 Perlu Rujukan</span>
                    @else
                        <span class="sv-badge sv-badge-control">⚠️ Perlu Kontrol</span>
                    @endif
                @endif
                <div style="font-size:11px;color:var(--sv-text-muted);margin-top:4px;">
                    {{ $monCount }} kunjungan
                </div>
            </div>
            <span style="color:var(--sv-blue);font-size:16px;">›</span>
        </a>
        @endforeach
    </div>

    @else
    {{-- Single result — show full details --}}
    @php
        $p = $found;
        $gender = ($p->gender ?? '') === 'Male' ? '👨' : '👩';
        $latestMon = $patientMonitorings->first();
        $latestStatus = $latestMon->status ?? '';
        $age = $p->datebirth ? \Carbon\Carbon::parse($p->datebirth)->age . ' Thn' : '-';
    @endphp
    <div class="sv-search-result sv-animate-in">
        {{-- Header --}}
        <div class="sv-search-result-header">
            <div class="sv-search-avatar">{{ $gender }}</div>
            <div style="flex:1;">
                <div style="font-size:18px;font-weight:800;color:white;letter-spacing:-0.3px;">
                    {{ $p->patient_name ?? '-' }}
                </div>
                <div style="font-size:12.5px;color:rgba(255,255,255,0.6);margin-top:3px;">
                    {{ $p->patient_id ?? '' }}
                    &nbsp;·&nbsp; NIK: {{ $p->nik_dummy ?? '' }}
                    &nbsp;·&nbsp; {{ $age }}
                </div>
            </div>
            <div>
                @if($latestStatus)
                    @if($latestStatus === 'Stable')
                        <span class="sv-status-pill stable">✅ Stabil</span>
                    @elseif($latestStatus === 'Need Referral')
                        <span class="sv-status-pill referral">🚨 Perlu Rujukan</span>
                    @else
                        <span class="sv-status-pill control">⚠️ Perlu Kontrol</span>
                    @endif
                @endif
                <div style="margin-top: 8px;">
                    <a href="{{ route('admin.rekam-medis.index', ['patient_id' => $p->patient_id]) }}"
                       style="font-size:12px;color:rgba(255,255,255,0.8); text-decoration: underline;">
                        📂 Rekam Medis →
                    </a>
                </div>
            </div>
        </div>

        {{-- Patient Details --}}
        <div class="p-4">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Kategori</div>
                    <div style="font-size:14px;font-weight:600;margin-top:3px;">{{ $p->patient_category ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Jenis Kelamin</div>
                    <div style="font-size:14px;font-weight:600;margin-top:3px;">{{ ($p->gender ?? '') === 'Male' ? '👨 Laki-laki' : '👩 Perempuan' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">No. HP Keluarga</div>
                    <div style="font-size:14px;font-weight:600;margin-top:3px;">{{ $p->family_phone ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Total Kunjungan</div>
                    <div style="font-size:22px;font-weight:800;color:var(--sv-blue);margin-top:2px;">{{ $patientMonitorings->count() }}</div>
                </div>
                <div class="col-12">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Alamat</div>
                    <div style="font-size:13.5px;color:var(--sv-text-sub);margin-top:3px;">📍 {{ $p->address ?? '-' }}</div>
                </div>
            </div>

            {{-- Monitoring History --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h6 style="font-size:12px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:var(--sv-text-muted);margin:0;">
                    Riwayat Monitoring Kesehatan
                </h6>
                <a href="{{ route('admin.monitorings.create', ['patient_id' => $p->patient_id]) }}"
                   class="btn btn-primary btn-sm" style="font-size:12px;">
                    🩺 Catat Monitoring Baru
                </a>
            </div>

            @if($patientMonitorings->isEmpty())
            <div class="sv-empty-state" style="padding:32px 0;">
                <div class="empty-icon">🩺</div>
                <p>Belum ada catatan monitoring untuk pasien ini.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-sm mb-0" style="font-size:13px;">
                    <thead style="background:#F8F9FA;">
                        <tr>
                            <th>Tanggal</th>
                            <th>Tekanan Darah</th>
                            <th>Suhu (°C)</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patientMonitorings->take(10) as $mon)
                        <tr>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:600;">
                                    {{ $mon->monitoring_date ? \Carbon\Carbon::parse($mon->monitoring_date)->format('d M Y') : '-' }}
                                </div>
                                <div style="font-size:11px;color:#8E8E93;">
                                    {{ $mon->monitoring_time ? \Carbon\Carbon::parse($mon->monitoring_time)->format('H:i') . ' WIB' : '' }}
                                </div>
                            </td>
                            <td style="font-weight:700;">{{ $mon->blood_pressure ?? '-' }} <span style="font-size:10px;color:#8E8E93;">mmHg</span></td>
                            <td>{{ $mon->body_temperature ?? '-' }}°C</td>
                            <td style="max-width:180px;white-space:normal;color:var(--sv-text-sub);">
                                {{ Str::limit($mon->symptoms ?? '-', 60) }}
                            </td>
                            <td>
                                @if($mon->status === 'Stable')
                                    <span class="sv-badge sv-badge-stable">✅ Stabil</span>
                                @elseif($mon->status === 'Need Referral')
                                    <span class="sv-badge sv-badge-referral">🚨 Perlu Rujukan</span>
                                @else
                                    <span class="sv-badge sv-badge-control">⚠️ Perlu Kontrol</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.monitorings.show', $mon->id) }}"
                                   class="btn btn-sm btn-outline-primary py-0"
                                   style="font-size:11px;">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($patientMonitorings->count() > 10)
            <div class="text-center mt-3">
                <a href="{{ route('admin.rekam-medis.index', ['patient_id' => $p->patient_id]) }}"
                   class="btn btn-sm btn-outline-primary">
                    Lihat Semua {{ $patientMonitorings->count() }} Kunjungan →
                </a>
            </div>
            @endif
            @endif
        </div>
    </div>
    @endif

@else
    {{-- Initial state — no query yet --}}
    <div class="row g-3">
        <div class="col-md-6 sv-animate-in sv-animate-in-1">
            <div class="sv-card" style="height:100%;">
                <div style="font-size:24px;margin-bottom:12px;"><i class="bi bi-search"></i></div>
                <h6 style="font-weight:700;font-size:14px;">Cari Berdasarkan Nama</h6>
                <p style="font-size:13px;color:var(--sv-text-muted);line-height:1.6;">
                    Masukkan nama pasien (sebagian atau lengkap). Contoh: "Slamet", "John", "Jane".
                </p>
            </div>
        </div>
        <div class="col-md-6 sv-animate-in sv-animate-in-2">
            <div class="sv-card" style="height:100%;">
                <div style="font-size:24px;margin-bottom:12px;"><i class="bi bi-person-vcard"></i></div>
                <h6 style="font-weight:700;font-size:14px;">Cari Berdasarkan Kode RM / NIK</h6>
                <p style="font-size:13px;color:var(--sv-text-muted);line-height:1.6;">
                    Masukkan kode RM (contoh: <code>P001</code>) atau NIK dummy 16 digit.
                </p>
            </div>
        </div>
        <div class="col-12 sv-animate-in sv-animate-in-3">
            <div class="sv-card text-center" style="background:#FFFBEC;border-color:#FDEAB0;">
                <span style="font-size:20px;">⚠️</span>
                <p style="font-size:12.5px;color:#8A6200;margin:8px 0 0;">
                    Seluruh data pasien adalah data simulasi dummy untuk keperluan akademik. Bukan data pasien nyata.
                </p>
            </div>
        </div>
    </div>
@endif
@endsection
