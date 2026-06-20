@extends('layouts.app')
@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Detail Pemeriksaan Umum</h1>
        <p>
            <span style="color:var(--sv-text-muted);">{{ $monitoring->monitoring_date ? \Carbon\Carbon::parse($monitoring->monitoring_date)->format('d F Y') : '-' }}</span>
            @if($monitoring->monitoring_time)
                <span style="color:var(--sv-border);margin:0 6px;">•</span>
                <span style="color:var(--sv-text-muted);">{{ \Carbon\Carbon::parse($monitoring->monitoring_time)->format('H:i') }} WIB</span>
            @endif
            <span style="color:var(--sv-border);margin:0 6px;">•</span>
            <span style="color:var(--sv-blue);font-weight:600;">{{ $monitoring->user->name ?? 'Petugas' }}</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">🖨️ Cetak</button>
        <a href="{{ route('admin.monitorings.create', ['patient_id' => $monitoring->patient_id]) }}" class="btn btn-primary btn-sm">🩺 Monitoring Baru</a>
        <a href="{{ route('admin.monitorings.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>
</div>

<div class="row g-3">
    {{-- Main Content --}}
    <div class="col-12 col-xl-8">

        {{-- Patient Info --}}
        <div class="sv-card mb-3 sv-animate-in sv-animate-in-1">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h6 style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);margin:0;">👤 Informasi Pasien</h6>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Nama Pasien</div>
                    <div style="font-size:16px;font-weight:700;margin-top:3px;">{{ $monitoring->patient->patient_name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Kode / No. RM</div>
                    <div style="font-size:14px;font-weight:600;color:var(--sv-blue);margin-top:3px;">{{ $monitoring->patient_id ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Tanggal</div>
                    <div style="font-size:14px;font-weight:500;margin-top:3px;">{{ $monitoring->monitoring_date ? \Carbon\Carbon::parse($monitoring->monitoring_date)->format('d M Y') : '-' }}</div>
                </div>
                <div class="col-md-2">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Jam</div>
                    <div style="font-size:14px;font-weight:500;margin-top:3px;">{{ $monitoring->monitoring_time ? \Carbon\Carbon::parse($monitoring->monitoring_time)->format('H:i').' WIB' : '-' }}</div>
                </div>
                @if($monitoring->patient->address ?? false)
                <div class="col-12">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Alamat</div>
                    <div style="font-size:13.5px;color:var(--sv-text-sub);margin-top:3px;">📍 {{ $monitoring->patient->address }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Vital Signs --}}
        <div class="sv-card mb-3 sv-animate-in sv-animate-in-2">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h6 style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);margin:0;">🩺 Tanda-Tanda Vital</h6>
                @php
                    $s = strtolower($monitoring->status ?? '');
                    $statusCls = (str_contains($s,'stable')||str_contains($s,'stabil')) ? 'sv-badge-stable'
                               : ((str_contains($s,'referral')||str_contains($s,'rujukan')) ? 'sv-badge-referral' : 'sv-badge-control');
                    $statusLbl = (str_contains($s,'stable')||str_contains($s,'stabil')) ? '✅ Stabil'
                               : ((str_contains($s,'referral')||str_contains($s,'rujukan')) ? '🚨 Perlu Rujukan' : '⚠️ Perlu Kontrol');
                @endphp
                <span class="sv-badge {{ $statusCls }}">{{ $statusLbl }}</span>
            </div>
            <div class="row g-3">
                {{-- Blood Pressure --}}
                @php
                    $bpParts = explode('/', $monitoring->blood_pressure ?? '');
                    $sys = (int)($bpParts[0] ?? 0); $dia = (int)($bpParts[1] ?? 0);
                    $bpClass = ($sys>=140||$dia>=90) ? 'danger' : (($sys>=120||$dia>=80) ? 'warning' : 'normal');
                    $bpLabel = ($sys>=140||$dia>=90) ? 'Hipertensi' : (($sys<=90||$dia<=60) ? 'Hipotensi' : (($sys>=120||$dia>=80) ? 'Pre-Hipertensi' : 'Normal'));
                @endphp
                <div class="col-6 col-md-4">
                    <div class="sv-vital-card">
                        <div class="vital-label"><span>❤️</span> Tekanan Darah</div>
                        @if($sys && $dia)
                        <div class="sv-vital-big">
                            <span class="val">{{ $sys }}</span>
                            <span class="sep">/</span>
                            <span class="val">{{ $dia }}</span>
                            <span class="unit">mmHg</span>
                        </div>
                        <div class="vital-status vital-{{ $bpClass }}">{{ $bpLabel }}</div>
                        @else
                        <div class="sv-vital-big"><span class="val" style="font-size:24px;">{{ $monitoring->blood_pressure ?? '-' }}</span></div>
                        @endif
                    </div>
                </div>

                {{-- Suhu --}}
                @php
                    $temp = (float)($monitoring->body_temperature ?? 0);
                    $tempClass = $temp >= 38.0 ? 'danger' : ($temp < 36.0 ? 'warning' : 'normal');
                    $tempLabel = $temp >= 38.0 ? '🔴 Demam' : ($temp < 36.0 ? '🔵 Hipotermi' : '🟢 Normal');
                @endphp
                <div class="col-6 col-md-4">
                    <div class="sv-vital-card">
                        <div class="vital-label"><span>🌡️</span> Suhu Tubuh</div>
                        <div class="sv-vital-big">
                            <span class="val">{{ $temp ? number_format($temp,1) : '-' }}</span>
                            <span class="unit">°C</span>
                        </div>
                        @if($temp)
                        <div class="vital-status vital-{{ $tempClass }}">{{ $tempLabel }}</div>
                        @endif
                    </div>
                </div>

                {{-- Nadi --}}
                @if($monitoring->heart_rate)
                <div class="col-6 col-md-4">
                    <div class="sv-vital-card">
                        <div class="vital-label"><span>💓</span> Nadi</div>
                        <div class="sv-vital-big">
                            <span class="val">{{ $monitoring->heart_rate }}</span>
                            <span class="unit">bpm</span>
                        </div>
                        <div class="vital-status vital-{{ ($monitoring->heart_rate>=60&&$monitoring->heart_rate<=100)?'normal':'warning' }}">
                            {{ ($monitoring->heart_rate>=60&&$monitoring->heart_rate<=100)?'Normal':'Di luar normal' }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Laju Napas --}}
                @if($monitoring->respiratory_rate)
                <div class="col-6 col-md-4">
                    <div class="sv-vital-card">
                        <div class="vital-label"><span>🫁</span> Laju Napas</div>
                        <div class="sv-vital-big">
                            <span class="val">{{ $monitoring->respiratory_rate }}</span>
                            <span class="unit">x/mnt</span>
                        </div>
                        <div class="vital-status vital-{{ ($monitoring->respiratory_rate>=12&&$monitoring->respiratory_rate<=20)?'normal':'warning' }}">
                            {{ ($monitoring->respiratory_rate>=12&&$monitoring->respiratory_rate<=20)?'Normal':'Di luar normal' }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- SpO2 --}}
                @if($monitoring->oxygen_saturation)
                <div class="col-6 col-md-4">
                    <div class="sv-vital-card">
                        <div class="vital-label"><span>🩸</span> Saturasi O₂</div>
                        <div class="sv-vital-big">
                            <span class="val">{{ number_format($monitoring->oxygen_saturation,0) }}</span>
                            <span class="unit">%</span>
                        </div>
                        <div class="vital-status vital-{{ $monitoring->oxygen_saturation>=95?'normal':'danger' }}">
                            {{ $monitoring->oxygen_saturation>=95?'Normal':'⚠️ Rendah' }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Keluhan & Catatan --}}
        <div class="sv-card sv-animate-in sv-animate-in-3">
            <h6 style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);margin-bottom:16px;">📝 Keluhan &amp; Catatan Administratif</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);margin-bottom:8px;">Keluhan / Kondisi</div>
                    <p style="font-size:13.5px;color:var(--sv-text-sub);line-height:1.7;">{{ $monitoring->symptoms ?? 'Tidak ada catatan.' }}</p>
                </div>
                <div class="col-md-6">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);margin-bottom:8px;">Rekomendasi Tindak Lanjut</div>
                    <p style="font-size:13.5px;color:var(--sv-text-sub);line-height:1.7;">{{ $monitoring->notes ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>
            <div class="mt-3 p-3 rounded" style="background:#F8F9FA;font-size:12px;color:#8E8E93;line-height:1.6;">
                ⚠️ <strong>Disclaimer:</strong> Rekomendasi bersifat administratif dan bukan diagnosis medis. Data ini adalah simulasi akademik.
            </div>
        </div>
    </div>

    {{-- Riwayat Kunjungan Sidebar --}}
    <div class="col-12 col-xl-4 sv-animate-in">
        <div class="sv-card h-100">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h5 style="font-size:14px;font-weight:700;margin:0;">📂 Riwayat Kunjungan</h5>
                <a href="{{ route('admin.rekam-medis.index', ['patient_id' => $monitoring->patient_id]) }}"
                   style="font-size:12px;color:var(--sv-blue);">Lihat Semua</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($patientHistory as $hist)
                @php
                    $hs = strtolower($hist->status ?? '');
                    $hCls = (str_contains($hs,'stable')||str_contains($hs,'stabil')) ? 'stable'
                           : ((str_contains($hs,'referral')||str_contains($hs,'rujukan')) ? 'referral' : 'control');
                    $dotColors = ['stable'=>'#34C759','control'=>'#FF9500','referral'=>'#FF3B30'];
                @endphp
                <a href="{{ route('admin.monitorings.show', $hist->id) }}"
                   style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;border:1px solid {{ $hist->id === $monitoring->id ? '#007AFF' : 'var(--sv-border)' }};background:{{ $hist->id === $monitoring->id ? '#E8F1FF' : 'white' }};text-decoration:none;transition:all 0.2s;"
                   class="history-link">
                    <div style="width:8px;height:8px;border-radius:50%;background:{{ $dotColors[$hCls] }};flex-shrink:0;"></div>
                    <div style="flex:1;overflow:hidden;">
                        <div style="font-size:12px;font-weight:600;color:var(--sv-text-main);">
                            {{ $hist->monitoring_date ? \Carbon\Carbon::parse($hist->monitoring_date)->format('d M Y') : '-' }}
                        </div>
                        <div style="font-size:11px;color:var(--sv-text-muted);">
                            {{ $hist->blood_pressure ?? '-' }} mmHg
                            @if($hist->body_temperature) · {{ $hist->body_temperature }}°C @endif
                        </div>
                    </div>
                    @if($hist->id === $monitoring->id)
                    <span style="font-size:10px;color:var(--sv-blue);font-weight:600;">AKTIF</span>
                    @endif
                </a>
                @empty
                <div class="sv-empty-state" style="padding:24px 0;">
                    <div class="empty-icon">🩺</div>
                    <p>Belum ada riwayat.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-3 pt-3" style="border-top:1px solid var(--sv-border);">
                <a href="{{ route('admin.monitorings.create', ['patient_id' => $monitoring->patient_id]) }}"
                   class="btn btn-primary w-100 btn-sm">🩺 Tambah Monitoring Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection
