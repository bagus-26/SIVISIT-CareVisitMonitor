@extends('layouts.app')
@section('title', 'Catat Monitoring')

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Catat Monitoring Kesehatan</h1>
        <p>Isi hasil pemeriksaan kondisi pasien pada kunjungan ini.</p>
    </div>
    <a href="{{ route('admin.monitorings.index') }}" class="btn btn-outline-secondary">← Kembali</a>
</div>

@if($errors->any())
<div class="alert alert-danger d-flex align-items-start gap-2 mb-4 sv-animate-in" role="alert">
    <span>⚠️</span>
    <div>
        @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
        @endforeach
    </div>
</div>
@endif

<form action="{{ route('admin.monitorings.store') }}" method="POST" id="monForm" novalidate>
    @csrf
    <div class="row g-3">

        {{-- Section 1: Pasien & Jadwal --}}
        <div class="col-12 sv-animate-in sv-animate-in-1">
            <div class="form-section">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#E8F1FF;">📋</div>
                    <div>
                        <h6>Informasi Kunjungan</h6>
                        <p>Pilih pasien dan tanggal kunjungan</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="patient_id" class="form-label">Pasien <span style="color:#FF3B30;">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <option value="" disabled {{ old('patient_id', $prePatientId) ? '' : 'selected' }}>— Pilih Pasien —</option>
                                @foreach($patients as $p)
                                <option value="{{ $p->patient_id }}"
                                    {{ old('patient_id', $prePatientId) === $p->patient_id ? 'selected' : '' }}>
                                    {{ $p->patient_name }} — {{ $p->patient_id }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="monitoring_date" class="form-label">Tanggal Monitoring <span style="color:#FF3B30;">*</span></label>
                            <input type="date" name="monitoring_date" id="monitoring_date" class="form-control"
                                   value="{{ old('monitoring_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="monitoring_time" class="form-label">Jam Kunjungan</label>
                            <input type="time" name="monitoring_time" id="monitoring_time" class="form-control"
                                   value="{{ old('monitoring_time', date('H:i')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Petugas Pemeriksa</label>
                            <input type="text" class="form-control" style="background:#F2F4F7;color:#636366;"
                                   value="{{ Auth::user()->name ?? 'Petugas' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Tanda Vital --}}
        <div class="col-12 sv-animate-in sv-animate-in-2">
            <div class="form-section">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#FFF0EF;">🩺</div>
                    <div>
                        <h6>Tanda-Tanda Vital</h6>
                        <p>Hasil pengukuran fisik pasien — wajib diisi dengan benar</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="blood_pressure" class="form-label">Tekanan Darah (mmHg) <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="blood_pressure" id="blood_pressure" class="form-control"
                                   placeholder="Contoh: 120/80"
                                   value="{{ old('blood_pressure') }}" required pattern="\d{2,3}\/\d{2,3}">
                            <div class="validation-hint" id="bpHint">Format: sistolik/diastolik (mis. 120/80)</div>
                        </div>
                        <div class="col-md-4">
                            <label for="body_temperature" class="form-label">Suhu Tubuh (°C) <span style="color:#FF3B30;">*</span></label>
                            <input type="number" name="body_temperature" id="body_temperature" class="form-control"
                                   placeholder="Contoh: 36.5" value="{{ old('body_temperature') }}"
                                   min="35.0" max="42.0" step="0.1" required>
                            <div class="validation-hint" id="tempHint">Rentang normal: 35.0°C – 42.0°C</div>
                        </div>
                        <div class="col-md-4">
                            <label for="heart_rate" class="form-label">Nadi (bpm)</label>
                            <input type="number" name="heart_rate" id="heart_rate" class="form-control"
                                   placeholder="Contoh: 80" value="{{ old('heart_rate') }}" min="30" max="250">
                            <div class="validation-hint">Normal: 60–100 bpm</div>
                        </div>
                        <div class="col-md-4">
                            <label for="respiratory_rate" class="form-label">Laju Napas (x/menit)</label>
                            <input type="number" name="respiratory_rate" id="respiratory_rate" class="form-control"
                                   placeholder="Contoh: 18" value="{{ old('respiratory_rate') }}" min="5" max="60">
                            <div class="validation-hint">Normal: 12–20 x/menit</div>
                        </div>
                        <div class="col-md-4">
                            <label for="oxygen_saturation" class="form-label">Saturasi O₂ (%)</label>
                            <input type="number" name="oxygen_saturation" id="oxygen_saturation" class="form-control"
                                   placeholder="Contoh: 98" value="{{ old('oxygen_saturation') }}"
                                   min="50" max="100" step="0.1">
                            <div class="validation-hint">Normal: ≥ 95%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Keluhan & Catatan --}}
        <div class="col-12 sv-animate-in sv-animate-in-3">
            <div class="form-section">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#E8F8ED;">📝</div>
                    <div>
                        <h6>Keluhan &amp; Catatan Petugas</h6>
                        <p>Keluhan pasien dan rekomendasi tindak lanjut</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="symptoms" class="form-label">Keluhan / Kondisi Pasien <span style="color:#FF3B30;">*</span></label>
                            <textarea name="symptoms" id="symptoms" class="form-control" rows="3"
                                      placeholder="Deskripsikan keluhan atau kondisi yang ditemukan..." required>{{ old('symptoms') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Catatan Petugas</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                      placeholder="Catatan tambahan petugas...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="recommendation" class="form-label">Rekomendasi Tindak Lanjut</label>
                            <textarea name="recommendation" id="recommendation" class="form-control" rows="2"
                                      placeholder="Contoh: Jadwalkan kontrol ulang dalam 3 hari...">{{ old('recommendation') }}</textarea>
                            <div class="validation-hint">⚠️ Rekomendasi bersifat administratif, bukan nasihat medis.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="next_visit_date" class="form-label">Tanggal Kunjungan Berikutnya</label>
                            <input type="date" name="next_visit_date" id="next_visit_date" class="form-control"
                                   value="{{ old('next_visit_date') }}" min="{{ date('Y-m-d') }}">
                            <div class="validation-hint">Jadwalkan kunjungan lanjutan jika diperlukan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Status --}}
        <div class="col-12 sv-animate-in sv-animate-in-4">
            <div class="form-section">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#FFF4E5;">🏷️</div>
                    <div>
                        <h6>Status Kondisi Pasien</h6>
                        <p>Tentukan status kondisi berdasarkan hasil monitoring</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <label class="form-label d-block mb-3">Status <span style="color:#FF3B30;">*</span></label>
                    <div class="status-options">
                        <div class="status-option">
                            <input type="radio" name="status" id="statusStable" value="Stable"
                                   {{ old('status') === 'Stable' ? 'checked' : '' }} required>
                            <label for="statusStable">✅ Stabil</label>
                        </div>
                        <div class="status-option">
                            <input type="radio" name="status" id="statusControl" value="Need Control"
                                   {{ old('status') === 'Need Control' ? 'checked' : '' }}>
                            <label for="statusControl">⚠️ Perlu Kontrol</label>
                        </div>
                        <div class="status-option">
                            <input type="radio" name="status" id="statusReferral" value="Need Referral"
                                   {{ old('status') === 'Need Referral' ? 'checked' : '' }}>
                            <label for="statusReferral">🚨 Perlu Rujukan</label>
                        </div>
                    </div>
                    <div class="mt-3 p-3 rounded" style="background:#F8F9FA;font-size:12.5px;color:#636366;line-height:1.7;">
                        <strong>Panduan status:</strong><br>
                        <span style="color:#1A7A35;">✅ Stabil</span> — Kondisi pasien baik, tidak ada perubahan signifikan.<br>
                        <span style="color:#8A4E00;">⚠️ Perlu Kontrol</span> — Ada temuan yang perlu ditindaklanjuti.<br>
                        <span style="color:#C0291F;">🚨 Perlu Rujukan</span> — Kondisi memerlukan penanganan di fasilitas kesehatan.
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="col-12 sv-animate-in sv-animate-in-4">
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-secondary">🔄 Reset</button>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">💾 Simpan Monitoring</button>
            </div>
            <p class="text-end mt-2" style="font-size:12px;color:#8E8E93;">
                ⚠️ Sistem ini tidak memberikan diagnosis medis. Rekomendasi hanya bersifat administratif.
            </p>
        </div>

    </div>
</form>
@endsection

@section('scripts')
<script>
    // Real-time Blood Pressure Validation
    document.getElementById('blood_pressure').addEventListener('input', function() {
        const val = this.value.trim();
        const hint = document.getElementById('bpHint');
        const regex = /^\d{2,3}\/\d{2,3}$/;
        if (!val) { hint.className = 'validation-hint'; hint.textContent = 'Format: sistolik/diastolik (mis. 120/80)'; return; }
        if (!regex.test(val)) { hint.className = 'validation-hint error'; hint.textContent = '✗ Format salah. Gunakan: 120/80'; return; }
        const [sys, dia] = val.split('/').map(Number);
        if (sys < 60 || sys > 250 || dia < 40 || dia > 150) {
            hint.className = 'validation-hint error';
            hint.textContent = '✗ Nilai di luar rentang. Sistolik: 60–250, Diastolik: 40–150';
        } else {
            hint.className = 'validation-hint ok';
            hint.textContent = '✓ Tekanan darah valid';
        }
    });

    // Real-time Temperature Validation
    document.getElementById('body_temperature').addEventListener('input', function() {
        const val = parseFloat(this.value);
        const hint = document.getElementById('tempHint');
        if (!this.value) { hint.className = 'validation-hint'; hint.textContent = 'Rentang normal: 35.0°C – 42.0°C'; return; }
        if (val < 35 || val > 42) {
            hint.className = 'validation-hint error';
            hint.textContent = '✗ Suhu di luar rentang valid (35.0 – 42.0°C)';
        } else {
            hint.className = 'validation-hint ok';
            const status = val >= 37.5 ? '🔴 Demam' : (val < 36.0 ? '🔵 Hipotermi' : '🟢 Normal');
            hint.textContent = `✓ Suhu valid — ${status}`;
        }
    });

    document.getElementById('monForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.textContent = 'Menyimpan...';
        btn.disabled = true;
    });
</script>
@endsection
