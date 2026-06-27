@extends('layouts.app')
@section('title', 'Lokasi Saya')

@section('extra-styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
#trackerMap { height: 400px; border-radius: var(--sv-radius); border: 1px solid var(--sv-border); }
.tracking-active { color: #34C759; }
.tracking-inactive { color: #8E8E93; }
.status-pulse { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 6px; }
.status-pulse.active { background: #34C759; box-shadow: 0 0 0 4px rgba(52,199,89,0.2); animation: pulse 2s infinite; }
.status-pulse.inactive { background: #B0B0B0; }
@keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(52,199,89,0.4); } 70% { box-shadow: 0 0 0 8px rgba(52,199,89,0); } 100% { box-shadow: 0 0 0 0 rgba(52,199,89,0); } }
.location-coord { font-size: 12px; color: var(--sv-text-muted); font-family: monospace; }
</style>
@endsection

@section('content')
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Lokasi Saya</h1>
        <p>Bagikan lokasi Anda agar admin dapat memantau posisi petugas di lapangan.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
    <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
</div>
@endif

<div class="row g-3">
    {{-- Map --}}
    <div class="col-12 col-lg-8">
        <div class="sv-card sv-animate-in p-0 overflow-hidden">
            <div id="trackerMap"></div>
        </div>
    </div>

    {{-- Controls --}}
    <div class="col-12 col-lg-4">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-geo-alt me-2"></i>Status Lokasi</h5>
            </div>
            <div class="sv-card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="status-pulse {{ $user->latitude ? 'active' : 'inactive' }}" id="statusDot"></span>
                    <div>
                        <div style="font-weight:600;font-size:14px;" id="statusText">
                            {{ $user->latitude ? 'Lokasi terkirim' : 'Belum berbagi lokasi' }}
                        </div>
                        <div class="location-coord" id="lastUpdate">
                            {{ $user->last_location_at ? 'Terakhir: '.$user->last_location_at->diffForHumans() : '-' }}
                        </div>
                    </div>
                </div>

                <div class="location-coord mb-3" id="coordDisplay">
                    @if($user->latitude && $user->longitude)
                        {{ number_format((float)$user->latitude, 6) }}, {{ number_format((float)$user->longitude, 6) }}
                    @else
                        -
                    @endif
                </div>

                <button class="btn btn-primary w-100 mb-2" id="startTrackingBtn" onclick="startTracking()">
                    <i class="bi bi-play-fill me-1"></i> Mulai Bagikan Lokasi
                </button>
                <button class="btn btn-outline-secondary w-100" id="stopTrackingBtn" style="display:none;" onclick="stopTracking()">
                    <i class="bi bi-stop-fill me-1"></i> Hentikan
                </button>

                <hr style="margin:16px 0;">

                <div style="font-size:12px;color:var(--sv-text-muted);line-height:1.6;">
                    <i class="bi bi-info-circle me-1"></i>
                    Lokasi akan dikirim setiap 30 detik selama fitur aktif. Admin dapat melihat posisi Anda di peta monitoring.
                </div>
            </div>
        </div>

        {{-- Today Summary --}}
        <div class="sv-card sv-animate-in mt-3">
            <div class="sv-card-header">
                <h5><i class="bi bi-clipboard-data me-2"></i>Ringkasan Hari Ini</h5>
            </div>
            <div class="sv-card-body">
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <div style="font-size:24px;font-weight:800;color:var(--sv-blue);">{{ $assignedPatients->count() }}</div>
                        <div style="font-size:11px;color:var(--sv-text-muted);">Pasien Binaan</div>
                    </div>
                    <div>
                        <div style="font-size:24px;font-weight:800;color:var(--sv-blue);">{{ $todayVisits }}</div>
                        <div style="font-size:11px;color:var(--sv-text-muted);">Kunjungan Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assigned Patients --}}
        @if($assignedPatients->isNotEmpty())
        <div class="sv-card sv-animate-in mt-3">
            <div class="sv-card-header">
                <h5><i class="bi bi-people me-2"></i>Pasien Binaan</h5>
            </div>
            <div class="sv-card-body p-0">
                @foreach($assignedPatients as $p)
                <div class="d-flex align-items-center gap-2 p-3 border-bottom" style="font-size:13px;">
                    <div style="width:32px;height:32px;border-radius:50%;background:var(--sv-blue);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:white;flex-shrink:0;">
                        {{ strtoupper(substr($p->patient_name ?? 'P', 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;">{{ $p->patient_name ?? '-' }}</div>
                        <div style="font-size:10px;color:var(--sv-text-muted);">{{ $p->patient_id }} · {{ $p->patient_category ?? '-' }}</div>
                    </div>
                    <a href="{{ route('admin.monitorings.create', ['patient_id' => $p->patient_id]) }}" class="btn btn-sm btn-outline-primary py-0" style="font-size:11px;">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map, userMarker, watchId = null, tracking = false;
let initialLat = {{ $user->latitude ?? 'null' }};
let initialLng = {{ $user->longitude ?? 'null' }};

function initMap() {
    const center = (initialLat && initialLng) ? [initialLat, initialLng] : [-7.98, 112.63];
    map = L.map('trackerMap').setView(center, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    if (initialLat && initialLng) {
        userMarker = L.circleMarker([initialLat, initialLng], {
            radius: 10, color: '#007AFF', fillColor: '#007AFF', fillOpacity: 0.4, weight: 3
        }).addTo(map);
        userMarker.bindPopup('<strong>Lokasi Anda</strong>').openPopup();
    }
}

function startTracking() {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung geolokasi.');
        return;
    }

    document.getElementById('startTrackingBtn').disabled = true;
    document.getElementById('startTrackingBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengaktifkan...';

    navigator.geolocation.getCurrentPosition(function(pos) {
        sendLocation(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);

        watchId = navigator.geolocation.watchPosition(function(p) {
            sendLocation(p.coords.latitude, p.coords.longitude, p.coords.accuracy);
        }, function(err) {
            console.warn('Geolocation error:', err.message);
        }, {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 10000,
        });

        setInterval(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(p) {
                    sendLocation(p.coords.latitude, p.coords.longitude, p.coords.accuracy);
                }, null, { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 });
            }
        }, 30000);

        tracking = true;
        updateUI(true);
        document.getElementById('startTrackingBtn').style.display = 'none';
        document.getElementById('stopTrackingBtn').style.display = '';
    }, function(err) {
        alert('Gagal mendapatkan lokasi: ' + err.message);
        document.getElementById('startTrackingBtn').disabled = false;
        document.getElementById('startTrackingBtn').innerHTML = '<i class="bi bi-play-fill me-1"></i> Mulai Bagikan Lokasi';
    }, {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 5000,
    });
}

function stopTracking() {
    if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }
    tracking = false;
    updateUI(false);
    document.getElementById('startTrackingBtn').style.display = '';
    document.getElementById('stopTrackingBtn').style.display = 'none';
    document.getElementById('startTrackingBtn').disabled = false;
    document.getElementById('startTrackingBtn').innerHTML = '<i class="bi bi-play-fill me-1"></i> Mulai Bagikan Lokasi';
}

function sendLocation(lat, lng, accuracy) {
    fetch('{{ route("admin.location.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ latitude: lat, longitude: lng, accuracy: accuracy || null }),
    }).catch(function() {});

    if (userMarker) {
        userMarker.setLatLng([lat, lng]);
    } else {
        userMarker = L.circleMarker([lat, lng], {
            radius: 10, color: '#007AFF', fillColor: '#007AFF', fillOpacity: 0.4, weight: 3
        }).addTo(map);
        userMarker.bindPopup('<strong>Lokasi Anda</strong>').openPopup();
    }
    map.setView([lat, lng]);

    document.getElementById('coordDisplay').textContent =
        Number(lat).toFixed(6) + ', ' + Number(lng).toFixed(6) +
        (accuracy ? ' (±' + Math.round(accuracy) + 'm)' : '');
    document.getElementById('lastUpdate').textContent = 'Terakhir: beberapa detik yang lalu';
    document.getElementById('statusText').textContent = 'Lokasi terkirim';
}

function updateUI(isActive) {
    const dot = document.getElementById('statusDot');
    dot.className = 'status-pulse ' + (isActive ? 'active' : 'inactive');
}
document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection
