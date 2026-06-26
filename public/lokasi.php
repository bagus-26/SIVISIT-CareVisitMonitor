<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_name  = $_SESSION['user_name']  ?? 'Petugas';
$user_role  = $_SESSION['user_role']  ?? 'Petugas';
$user_email = $_SESSION['user_email'] ?? 'petugas@sivisit.com';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Lokasi - SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --blue: #007AFF; --navy: #001A42; --surface: #FFFFFF;
            --bg: #F2F4F7; --text: #1C1C1E; --muted: #8E8E93; --border: #D8DCE6;
        }
        * { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        body {
            background: var(--bg); color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0;
        }
        .dashboard-wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px; background: var(--navy); color: white; padding: 20px;
            position: fixed; height: 100vh; left: 0; top: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1); z-index: 1000;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 30px; padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand-icon {
            font-size: 28px; background: rgba(255,255,255,0.1);
            width: 40px; height: 40px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-brand-text h4 { margin: 0; font-size: 14px; font-weight: 700; }
        .sidebar-brand-text small { opacity: 0.7; font-size: 11px; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin-bottom: 10px; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px; padding: 12px 15px;
            border-radius: 8px; color: rgba(255,255,255,0.8);
            text-decoration: none; transition: all 0.3s ease; font-size: 14px;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: var(--blue); color: white; }
        .sidebar-menu a i { font-size: 18px; width: 20px; }
        .sidebar-footer {
            position: absolute; bottom: 20px; left: 20px; right: 20px;
            padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);
        }
        .user-info { margin-bottom: 15px; }
        .user-info small { display: block; opacity: 0.7; font-size: 12px; }
        .btn-logout {
            width: 100%; background: #FF3B30; color: white; border: none;
            padding: 10px; border-radius: 6px; cursor: pointer; font-size: 13px;
            display: block; text-align: center; text-decoration: none;
        }
        .btn-logout:hover { background: #E5231F; color: white; }
        .main-content { flex: 1; margin-left: 250px; padding: 30px; }
        .page-header {
            margin-bottom: 24px; display: flex; justify-content: space-between;
            align-items: flex-start; flex-wrap: wrap; gap: 16px;
        }
        .page-header h1 { font-size: 24px; font-weight: 700; margin: 0; }
        .page-header p { color: var(--muted); margin: 5px 0 0 0; font-size: 14px; }
        #mapContainer {
            height: 65vh; border-radius: 12px; overflow: hidden;
            border: 1px solid var(--border); position: relative;
            background: #e8e8e8;
        }
        #map { width: 100%; height: 100%; }
        .loc-status {
            display: flex; align-items: center; gap: 8px; padding: 10px 16px;
            border-radius: 8px; background: white; border: 1px solid var(--border);
            font-size: 13px;
        }
        .pulse-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: #34C759; animation: pulse 1.5s infinite; flex-shrink: 0;
        }
        .pulse-dot.inactive { background: #FF3B30; animation: none; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52,199,89,0.6); }
            70% { box-shadow: 0 0 0 8px rgba(52,199,89,0); }
            100% { box-shadow: 0 0 0 0 rgba(52,199,89,0); }
        }
        #locateBtn {
            position: absolute; bottom: 24px; right: 24px; z-index: 1000;
            width: 44px; height: 44px; border-radius: 50%; background: white;
            border: 2px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; cursor: pointer; transition: all 0.2s;
        }
        #locateBtn:hover { background: #E8F1FF; border-color: var(--blue); }
        #locateBtn.tracking { background: var(--blue); border-color: var(--blue); color: white; }
        .info-card {
            background: white; border: 1px solid var(--border);
            border-radius: 12px; padding: 16px; margin-bottom: 16px;
        }
        .info-card h5 { font-size: 14px; font-weight: 700; margin: 0 0 12px; }
        .officer-item {
            display: flex; align-items: center; gap: 10px; padding: 10px;
            border-radius: 8px; border: 1px solid var(--border);
            margin-bottom: 8px; cursor: pointer; transition: all 0.2s;
        }
        .officer-item:hover { border-color: var(--blue); background: #F8FAFF; }
        .officer-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .custom-marker {
            width: 32px; height: 32px; border-radius: 50%; border: 3px solid white;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        .leaflet-popup-content { font-family: 'Segoe UI', sans-serif; font-size: 13px; }
        .leaflet-popup-content strong { display: block; font-size: 14px; margin-bottom: 4px; }

        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 20px; }
            #mapContainer { height: 50vh; }
        }
        @media (max-width: 576px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .sidebar-footer { position: relative; bottom: 0; left: 0; right: 0; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="bi bi-heart-pulse-fill"></i></div>
            <div class="sidebar-brand-text">
                <h4>SIVISIT</h4>
                <small>CareVisit Monitor</small>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="lokasi.php" class="active"><i class="bi bi-geo-alt"></i> Monitoring Lokasi</a></li>
            <li><a href="dashboard.php#pasien"><i class="bi bi-people"></i> Pasien</a></li>
            <li><a href="dashboard.php#petugas"><i class="bi bi-person-badge"></i> Petugas</a></li>
        </ul>
        <div class="sidebar-footer">
            <div class="user-info">
                <div style="font-weight: 600; color: white;"><?php echo htmlspecialchars($user_name); ?></div>
                <small><?php echo htmlspecialchars($user_role); ?></small>
            </div>
            <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <div>
                <h1><i class="bi bi-geo-alt" style="color:var(--blue);"></i> Monitoring Lokasi Petugas</h1>
                <p>Pantau lokasi petugas dan temukan pasien terdekat secara real-time.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="loadPetugas()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            <div class="loc-status" id="gpsStatus">
                <span class="pulse-dot" id="gpsDot"></span>
                <span id="gpsText">Mendeteksi lokasi...</span>
            </div>
            <div class="loc-status"><span>&#x1F4CD;</span><span id="coordDisplay">-- menunggu GPS</span></div>
            <div class="loc-status"><span>&#x1F465;</span><span id="onlineCount">0 petugas</span></div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-8">
                <div id="mapContainer">
                    <div id="map"></div>
                    <button id="locateBtn" onclick="toggleTracking()" title="Aktifkan pelacakan GPS"><i class="bi bi-crosshair"></i></button>
                </div>
                <div class="d-flex gap-4 mt-2 flex-wrap" style="font-size:12px;color:var(--muted);">
                    <span><span style="display:inline-block;width:12px;height:12px;background:var(--blue);border-radius:50%;margin-right:4px;"></span> Petugas</span>
                    <span><span style="display:inline-block;width:12px;height:12px;background:#34C759;border-radius:50%;margin-right:4px;"></span> Saya</span>
                    <span><span style="display:inline-block;width:12px;height:12px;background:#FF3B30;border-radius:50%;margin-right:4px;"></span> Pasien</span>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <!-- Nearby -->
                <div class="info-card">
                    <h5><i class="bi bi-search"></i> Cari Pasien Terdekat</h5>
                    <div class="d-flex gap-2 mb-2">
                        <input type="number" id="radiusInput" class="form-control form-control-sm" value="5" min="1" max="50" style="width:80px;">
                        <span style="line-height:32px;font-size:13px;color:var(--muted);">km</span>
                        <button class="btn btn-primary btn-sm ms-auto" onclick="findNearby()">Cari</button>
                    </div>
                    <div id="nearbyResults" style="max-height:180px;overflow-y:auto;font-size:13px;"></div>
                </div>
                <!-- Petugas List -->
                <div class="info-card">
                    <h5><i class="bi bi-people"></i> Petugas Online</h5>
                    <div id="officerList" style="max-height:250px;overflow-y:auto;"></div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, myMarker, trackingId = null, isTracking = false;
    let petugasMarkers = {}, pasienMarkers = {};
    let myLat = null, myLng = null;

    const API_PROXY = 'app/api_proxy.php';
    const HAS_TOKEN = <?php echo empty($_SESSION['api_token']) ? 'false' : 'true'; ?>;

    function getToken() { return <?php echo json_encode($_SESSION['api_token'] ?? ''); ?>; }
    function apiUrl(ep) { return API_PROXY + ep; }

    function apiFetch(url, options) {
        options = options || {};
        options.headers = options.headers || {};
        options.headers['Accept'] = 'application/json';
        if (getToken()) options.headers['Authorization'] = 'Bearer ' + getToken();
        return fetch(url, options).then(function(r) {
            if (r.status === 401 && !HAS_TOKEN) throw new Error('UNAUTHORIZED');
            return r.json();
        });
    }

    function initMap(lat, lng) {
        if (map) return;
        map = L.map('map', { center: [lat, lng], zoom: 14, zoomControl: true });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> | SIVISIT'
        }).addTo(map);
        myMarker = L.circleMarker([lat, lng], {
            radius: 10, fillColor: '#34C759', color: '#fff', weight: 3, fillOpacity: 0.8
        }).addTo(map).bindPopup('<strong>&#x1F4CD; Lokasi Saya</strong>');
        setTimeout(() => map.invalidateSize(), 300);
    }

    function startTracking() {
        if (!navigator.geolocation) { setGps('GPS tidak didukung', false); return; }
        setGps('Mendapatkan lokasi...', true);
        trackingId = navigator.geolocation.watchPosition(
            function(pos) {
                myLat = pos.coords.latitude; myLng = pos.coords.longitude;
                const acc = pos.coords.accuracy;
                document.getElementById('coordDisplay').textContent = myLat.toFixed(6) + ', ' + myLng.toFixed(6) + ' (&#xb1;' + Math.round(acc) + 'm)';
                setGps('GPS aktif &#xb1;' + Math.round(acc) + 'm', true);
                if (!map) { initMap(myLat, myLng); loadPetugas(); loadPatients(); return; }
                if (myMarker) myMarker.setLatLng([myLat, myLng]);
                sendLocation(myLat, myLng, acc);
            },
            function(err) { setGps('Gagal: ' + err.message, false); if (!map) initMap(-7.9666, 112.6326); },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
        );
        isTracking = true; document.getElementById('locateBtn').classList.add('tracking');
    }

    function stopTracking() {
        if (trackingId !== null) { navigator.geolocation.clearWatch(trackingId); trackingId = null; }
        isTracking = false; setGps('Pelacakan berhenti', false);
        document.getElementById('locateBtn').classList.remove('tracking');
    }

    function toggleTracking() { isTracking ? stopTracking() : startTracking(); }

    function setGps(text, active) {
        document.getElementById('gpsText').innerHTML = text;
        document.getElementById('gpsDot').className = 'pulse-dot' + (active ? '' : ' inactive');
    }

    function sendLocation(lat, lng, accuracy) {
        if (!HAS_TOKEN) return;
        apiFetch(apiUrl('/location/update'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ latitude: lat, longitude: lng, accuracy: accuracy || null, source: 'gps' })
        }).catch(function() {});
    }

    function loadPetugas() {
        if (!HAS_TOKEN) { document.getElementById('officerList').innerHTML = '<div style="padding:16px;text-align:center;color:var(--muted);font-size:12px;">Login petugas diperlukan untuk fitur lokasi.</div>'; return; }
        apiFetch(apiUrl('/location/petugas')).then(function(res) {
            if (!res.success) return;
            var data = res.data || [];
            document.getElementById('onlineCount').textContent = data.length + ' petugas';
            Object.keys(petugasMarkers).forEach(function(k) { if (map) map.removeLayer(petugasMarkers[k]); });
            petugasMarkers = {};
            var list = document.getElementById('officerList');
            if (data.length === 0) { list.innerHTML = '<div style="padding:16px 0;text-align:center;color:var(--muted);font-size:13px;">Belum ada petugas online.</div>'; return; }
            list.innerHTML = '';
            data.forEach(function(p) {
                var lat = parseFloat(p.latitude), lng = parseFloat(p.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                var isMe = (p.id == <?php echo $_SESSION['user_id'] ?? 0; ?>);
                var color = isMe ? '#34C759' : '#007AFF';
                var icon = L.divIcon({
                    className: '',
                    html: '<div class="custom-marker" style="background:' + color + '">' + p.name.charAt(0).toUpperCase() + '</div>',
                    iconSize: [32, 32], iconAnchor: [16, 16]
                });
                var marker = L.marker([lat, lng], { icon: icon }).addTo(map)
                    .bindPopup('<strong>' + (isMe ? '&#x1F464; ' : '') + p.name + '</strong>' +
                        (p.role ? '<br><small>' + p.role + '</small>' : '') +
                        (p.last_location_at_diff ? '<br><small>&#x1F550; ' + p.last_location_at_diff + '</small>' : ''));
                petugasMarkers[p.id] = marker;
                list.innerHTML += '<div class="officer-item" onclick="focusMarker(' + p.id + ')">' +
                    '<div class="officer-avatar" style="background:' + color + '">' + p.name.charAt(0).toUpperCase() + '</div>' +
                    '<div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:13px;">' + p.name + '</div>' +
                    '<div style="font-size:11px;color:var(--muted);">' + (p.last_location_at_diff || 'baru saja') + '</div></div></div>';
            });
        }).catch(function(err) { if (err.message === 'UNAUTHORIZED') document.getElementById('officerList').innerHTML = '<div style="padding:16px;text-align:center;color:var(--muted);font-size:12px;">Silakan login sebagai petugas terlebih dahulu.</div>'; });
    }

    function loadPatients() {
        if (!HAS_TOKEN) return;
        apiFetch(apiUrl('/pasien')).then(function(res) {
            if (!res.success) return;
            var data = (res.data || []).filter(function(p) { return p.latitude && p.longitude; });
            Object.keys(pasienMarkers).forEach(function(k) { if (map) map.removeLayer(pasienMarkers[k]); });
            pasienMarkers = {};
            data.forEach(function(p) {
                var lat = parseFloat(p.latitude), lng = parseFloat(p.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                var icon = L.divIcon({
                    className: '',
                    html: '<div class="custom-marker" style="background:#FF3B30;width:28px;height:28px;font-size:11px;">' + p.patient_name.charAt(0).toUpperCase() + '</div>',
                    iconSize: [28, 28], iconAnchor: [14, 14]
                });
                var marker = L.marker([lat, lng], { icon: icon }).addTo(map)
                    .bindPopup('<strong>&#x1F3E0; ' + p.patient_name + '</strong><br>' +
                        (p.patient_id ? '<small>' + p.patient_id + '</small><br>' : '') +
                        (p.address ? '<small>&#x1F4CD; ' + p.address + '</small>' : ''));
                pasienMarkers[p.patient_id] = marker;
            });
        }).catch(function() {});
    }

    function findNearby() {
        var radius = document.getElementById('radiusInput').value || 5;
        if (!myLat || !myLng) { alert('Aktifkan GPS dulu untuk mencari pasien terdekat.'); return; }
        if (!HAS_TOKEN) { document.getElementById('nearbyResults').innerHTML = '<div style="padding:8px;color:var(--muted);font-size:12px;">Login petugas diperlukan.</div>'; return; }
        apiFetch(apiUrl('/location/nearby?latitude=' + myLat + '&longitude=' + myLng + '&radius=' + radius)).then(function(res) {
            var el = document.getElementById('nearbyResults');
            if (!res.success || !res.data || res.data.length === 0) {
                el.innerHTML = '<div style="padding:8px 0;color:var(--muted);">Tidak ada pasien dalam radius ' + radius + ' km.</div>';
                return;
            }
            pasienData = res.data;
            Object.keys(pasienMarkers).forEach(function(k) { if (map) map.removeLayer(pasienMarkers[k]); });
            pasienMarkers = {};
            el.innerHTML = '<div style="font-weight:600;font-size:12px;margin-bottom:8px;">Ditemukan ' + res.data.length + ' pasien:</div>';
            res.data.forEach(function(p) {
                var lat = parseFloat(p.latitude), lng = parseFloat(p.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                var icon = L.divIcon({
                    className: '',
                    html: '<div class="custom-marker" style="background:#FF3B30;width:28px;height:28px;font-size:11px;">' + p.patient_name.charAt(0).toUpperCase() + '</div>',
                    iconSize: [28, 28], iconAnchor: [14, 14]
                });
                var marker = L.marker([lat, lng], { icon: icon }).addTo(map)
                    .bindPopup('<strong>&#x1F3E0; ' + p.patient_name + '</strong><br><small>Jarak: ' + parseFloat(p.distance).toFixed(2) + ' km</small>');
                pasienMarkers[p.patient_id] = marker;
                el.innerHTML += '<div class="officer-item" onclick="focusPasien(\'' + p.patient_id + '\')"><div style="font-weight:600;font-size:13px;">' + p.patient_name + '</div><div style="font-size:11px;color:var(--muted);">&#x1F4CD; ' + parseFloat(p.distance).toFixed(2) + ' km</div></div>';
            });
        }).catch(function() {
            document.getElementById('nearbyResults').innerHTML = '<div style="color:var(--red);">Gagal memuat data.</div>';
        });
    }

    function focusMarker(id) { var m = petugasMarkers[id]; if (m) { map.setView(m.getLatLng(), 16); m.openPopup(); } }
    function focusPasien(id) { var m = pasienMarkers[id]; if (m) { map.setView(m.getLatLng(), 16); m.openPopup(); } }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() { if (!map) { initMap(-7.9666, 112.6326); loadPetugas(); loadPatients(); } }, 3000);
        startTracking();
        setInterval(function() { if (isTracking) loadPetugas(); }, 30000);
    });
</script>

<footer style="padding:16px 24px;border-top:1px solid #D8DCE6;text-align:center;color:#8E8E93;font-size:12px;background:white;">
    <p style="margin:0 0 4px;">Sivisit-Kelompok 9 S1 Informatika UAS Pemrograman WEB ITSK Rs Dr Soepraoen Malang</p>
    <p style="margin:0;font-style:italic;">&#x26A0;&#xFE0F; Data simulasi/dummy. Tidak memberikan diagnosis medis. Rekomendasi hanya tindak lanjut administratif.</p>
</footer>
</body>
</html>
