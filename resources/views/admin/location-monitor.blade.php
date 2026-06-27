@extends('layouts.app')
@section('title', 'Monitoring Lokasi Petugas')

@section('extra-styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
#petugasMap { height: 580px; border-radius: var(--sv-radius); border: 1px solid var(--sv-border); }
.leaflet-popup-content-wrapper { border-radius: 12px; }
.leaflet-popup-content { margin: 12px 16px; font-size: 13px; min-width: 200px; }
.leaflet-popup-content strong { color: var(--sv-navy); }
.petugas-online { color: #34C759; }
.petugas-offline { color: #8E8E93; }
.lokasi-card { transition: all .2s; cursor: pointer; }
.lokasi-card:hover { border-color: var(--sv-blue); box-shadow: 0 4px 12px rgba(0,122,255,0.12); }
.lokasi-card.active { border-color: var(--sv-blue); background: #F0F7FF; }
.lokasi-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: white; flex-shrink: 0; }
.lokasi-list { max-height: 580px; overflow-y: auto; }
.patient-marker-label { background: white; border: 2px solid #007AFF; border-radius: 20px; padding: 2px 8px; font-size: 10px; font-weight: 600; color: #007AFF; white-space: nowrap; }
</style>
@endsection

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Monitoring Lokasi Petugas</h1>
        <p>Pantau posisi petugas di lapangan secara real-time.</p>
    </div>
    <button class="btn btn-outline-primary" onclick="refreshMap()">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
    </button>
</div>

<div class="row g-3">
    {{-- Map --}}
    <div class="col-12 col-lg-8">
        <div class="sv-card sv-animate-in p-0 overflow-hidden">
            <div id="petugasMap"></div>
        </div>
    </div>

    {{-- Petugas List --}}
    <div class="col-12 col-lg-4">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header d-flex align-items-center justify-content-between">
                <h5><i class="bi bi-people me-2"></i>Petugas</h5>
                <span id="onlineCount" class="badge bg-success">0 online</span>
            </div>
            <div class="lokasi-list" id="petugasList">
                @forelse($petugas as $p)
                <div class="lokasi-card p-3 border-bottom" data-id="{{ $p->id }}"
                     data-lat="{{ $p->latitude }}" data-lng="{{ $p->longitude }}"
                     onclick="focusPetugas(this)">
                    <div class="d-flex align-items-center gap-3">
                        <div class="lokasi-avatar" style="background:{{ $p->is_online ? '#34C759' : '#B0B0B0' }};">
                            {{ strtoupper(substr($p->name, 0, 1)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:13.5px;">{{ $p->name }}</div>
                            <div style="font-size:11.5px;color:var(--sv-text-muted);">
                                <i class="bi bi-geo-alt me-1"></i>{{ $p->location ?? '-' }}
                            </div>
                            <div style="font-size:11px;margin-top:2px;">
                                @if($p->latitude && $p->longitude)
                                    <span class="{{ $p->is_online ? 'petugas-online' : 'petugas-offline' }}">
                                        <i class="bi bi-circle-fill" style="font-size:7px;vertical-align:middle;"></i>
                                        {{ $p->is_online ? 'Online' : 'Offline' }}
                                    </span>
                                    <span class="text-muted ms-2">
                                        {{ $p->last_location_at ? $p->last_location_at->diffForHumans() : '-' }}
                                    </span>
                                @else
                                    <span class="petugas-offline"><i class="bi bi-circle-fill" style="font-size:7px;vertical-align:middle;"></i> No location</span>
                                @endif
                            </div>
                            <div style="font-size:11px;color:var(--sv-text-muted);margin-top:2px;">
                                <i class="bi bi-people me-1"></i>{{ $p->assigned_patients }} pasien
                                <span class="ms-2"><i class="bi bi-clipboard-check"></i> {{ $p->today_visits }} kunjungan hari ini</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.staff.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary py-0" title="Edit Petugas">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted" style="font-size:13px;">
                    <i class="bi bi-people" style="font-size:32px;display:block;margin-bottom:8px;color:#D1D5DB;"></i>
                    Belum ada petugas terdaftar.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Reassign Card --}}
        <div class="sv-card sv-animate-in mt-3" id="reassignCard" style="display:none;">
            <div class="sv-card-header">
                <h5><i class="bi bi-arrow-left-right me-2"></i>Alihkan Pasien</h5>
            </div>
            <div class="sv-card-body" id="reassignBody">
                <p style="font-size:13px;color:var(--sv-text-muted);">Klik petugas untuk melihat pasien yang dapat dialihkan.</p>
            </div>
        </div>
    </div>
</div>

{{-- Patient markers data --}}
<div id="patientData" data-patients='@json($patients)' style="display:none;"></div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map, markers = {}, patientMarkers = [], currentFocus = null;

function initMap() {
    map = L.map('petugasMap').setView([-7.98, 112.63], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    const patients = JSON.parse(document.getElementById('patientData').dataset.patients);
    patients.forEach(function(p) {
        if (!p.latitude || !p.longitude) return;
        const marker = L.circleMarker([p.latitude, p.longitude], {
            radius: 6, color: '#007AFF', fillColor: '#007AFF', fillOpacity: 0.3, weight: 2
        }).addTo(map);
        marker.bindPopup(`
            <strong>${p.patient_name ?? '-'}</strong><br>
            <span style="font-size:11px;">${p.patient_id}</span><br>
            ${p.address ? '<span style="font-size:11px;color:#8E8E93;">📍 '+p.address.substring(0,40)+'</span>' : ''}
            ${p.assigned_officer ? '<br><span style="font-size:11px;">👤 Petugas: '+p.assigned_officer.name+'</span>' : ''}
        `);
        patientMarkers.push(marker);
    });

    updatePetugasMarkers();
}

function updatePetugasMarkers() {
    document.querySelectorAll('.lokasi-card').forEach(function(el) {
        const lat = el.dataset.lat, lng = el.dataset.lng;
        const id = el.dataset.id, name = el.querySelector('.fw-semibold')?.textContent || '';
        if (!lat || !lng) return;

        const isOnline = el.querySelector('.petugas-online') !== null;
        const color = isOnline ? '#34C759' : '#B0B0B0';

        if (markers[id]) map.removeLayer(markers[id]);

        const icon = L.divIcon({
            html: `<div style="width:36px;height:36px;border-radius:50%;background:${color};border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:white;">${name.charAt(0)}</div>`,
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -20],
        });

        markers[id] = L.marker([lat, lng], { icon }).addTo(map);
        markers[id].bindPopup(`
            <strong>${name}</strong><br>
            <span style="font-size:11px;color:#8E8E93;">${el.querySelector('[class*="text-muted"]')?.textContent || ''}</span>
            <br><span style="font-size:11px;"><i class="bi bi-people"></i> Pasien: ${el.querySelector('[class*="text-muted"]')?.nextElementSibling?.textContent?.match(/\d+/)?.[0] || '0'}</span>
        `);

        markers[id].on('click', function() { focusPetugas(el); });
    });

    updateOnlineCount();
}

function updateOnlineCount() {
    const count = document.querySelectorAll('.petugas-online').length;
    document.getElementById('onlineCount').textContent = count + ' online';
}

function focusPetugas(el) {
    const lat = parseFloat(el.dataset.lat);
    const lng = parseFloat(el.dataset.lng);
    const id = el.dataset.id;

    document.querySelectorAll('.lokasi-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');

    if (lat && lng && markers[id]) {
        map.setView([lat, lng], 15);
        markers[id].openPopup();
    }

    currentFocus = id;
    loadReassignOptions(id);
}

function loadReassignOptions(petugasId) {
    const card = document.getElementById('reassignCard');
    const body = document.getElementById('reassignBody');
    card.style.display = 'block';
    body.innerHTML = '<div class="text-center text-muted" style="font-size:13px;"><div class="spinner-border spinner-border-sm me-2"></div>Memuat...</div>';

    fetch('/admin/location/petugas/' + petugasId + '/patients')
        .then(r => r.json())
        .then(data => {
            if (data.patients.length === 0) {
                body.innerHTML = '<p style="font-size:13px;color:var(--sv-text-muted);">Tidak ada pasien yang dialihkan.</p>';
                return;
            }
            let html = '<p style="font-size:12px;color:var(--sv-text-muted);margin-bottom:8px;">Pasien binaan petugas ini:</p>';
            data.patients.forEach(function(p) {
                html += `
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2 p-2" style="background:#F8F9FA;border-radius:8px;font-size:12px;">
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;">${p.patient_name ?? '-'}</div>
                            <div style="font-size:10px;color:var(--sv-text-muted);">${p.patient_id}</div>
                        </div>
                        <select class="form-select form-select-sm" style="width:auto;max-width:140px;font-size:11px;"
                                onchange="reassignPatient(${p.patient_id_orig || "'"+p.patient_id+"'"}, this.value)">
                            <option value="">Alihkan ke...</option>
                            ${data.other_petugas.map(o => `<option value="${o.id}">${o.name}</option>`).join('')}
                        </select>
                    </div>
                `;
            });
            body.innerHTML = html;
        })
        .catch(function() {
            body.innerHTML = '<p style="font-size:13px;color:#FF3B30;">Gagal memuat data.</p>';
        });
}

function reassignPatient(patientId, newOfficerId) {
    if (!newOfficerId) return;
    if (!confirm('Alihkan pasien ini ke petugas lain?')) return;

    fetch('/admin/patients/' + patientId + '/reassign', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}' },
        body: JSON.stringify({ assigned_officer_id: newOfficerId }),
    })
    .then(r => r.json())
    .then(function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal: ' + (data.message || 'unknown error'));
        }
    })
    .catch(function() {
        alert('Terjadi kesalahan.');
    });
}

function refreshMap() {
    location.reload();
}

document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection
