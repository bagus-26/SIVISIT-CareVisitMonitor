@extends('layouts.app')
@section('title', 'Data Monitoring')

@section('extra-styles')
<style>
.filter-card { padding:14px 16px; }
@media (max-width: 576px) {
    .filter-card .search-wrap { width:100%; }
    .filter-card .search-wrap input { width:100% !important; min-width:0 !important; }
    .filter-tab { font-size:11.5px; padding:5px 10px; }
}
</style>
@endsection

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-3 sv-animate-in" role="alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger d-flex align-items-center gap-2 mb-3 sv-animate-in" role="alert">
    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
</div>
@endif

<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Data Monitoring Kesehatan</h1>
        <p>Seluruh catatan monitoring pasien home care — diurutkan terbaru.</p>
    </div>
    <a href="{{ route('admin.monitorings.create') }}" class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i> Catat Monitoring
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Stabil</div>
            <div class="stat-value" style="color:#34C759;">{{ $countStable }}</div>
            <div class="stat-sub">Catatan monitoring</div>
        </div>
    </div>
    <div class="col-6 col-md-4 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#FF9500;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label">Perlu Kontrol</div>
            <div class="stat-value" style="color:#FF9500;">{{ $countControl }}</div>
            <div class="stat-sub">Butuh tindak lanjut</div>
        </div>
    </div>
    <div class="col-6 col-md-4 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-hospital-fill"></i></div>
            <div class="stat-label">Perlu Rujukan</div>
            <div class="stat-value" style="color:#FF3B30;">{{ $countReferral }}</div>
            <div class="stat-sub">Segera dirujuk</div>
        </div>
    </div>
</div>

{{-- Filter Tabs + Search --}}
<div class="sv-card mb-3 sv-animate-in filter-card">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex gap-2 flex-wrap" id="filterTabs">
            <button class="filter-tab active" data-filter="all">Semua ({{ $monitorings->count() }})</button>
            <button class="filter-tab tab-stable"   data-filter="stabil">Stabil ({{ $countStable }})</button>
            <button class="filter-tab tab-control"  data-filter="kontrol">Perlu Kontrol ({{ $countControl }})</button>
            <button class="filter-tab tab-referral" data-filter="rujukan">Perlu Rujukan ({{ $countReferral }})</button>
        </div>
        <div class="search-wrap" style="position:relative;">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#8E8E93;pointer-events:none;"></i>
            <input type="text" id="tableSearch" placeholder="Cari nama pasien..."
                   style="padding:7px 12px 7px 32px;border:1.5px solid #D8DCE6;border-radius:8px;font-size:13px;font-family:inherit;outline:none;color:#1C1C1E;transition:all .2s;min-width:200px;"
                   onfocus="this.style.borderColor='#007AFF'" onblur="this.style.borderColor='#D8DCE6'">
        </div>
    </div>
</div>

{{-- Table --}}
<div class="sv-table-wrap sv-animate-in">
    <div class="sv-section-header">
        <h5><i class="bi bi-clipboard2-pulse me-2" style="color:var(--sv-blue);"></i>Riwayat Monitoring</h5>
        <span style="font-size:12px;color:#8E8E93;" id="monCount">{{ $monitorings->count() }} catatan</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0" id="monTable">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Tekanan Darah</th>
                    <th>Suhu (°C)</th>
                    <th>Keluhan</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody id="monBody">
                @forelse($monitorings as $m)
                @php
                    $s = strtolower($m->status ?? '');
                    $fkey = (str_contains($s,'stable')||str_contains($s,'stabil')) ? 'stabil'
                          : ((str_contains($s,'referral')||str_contains($s,'rujukan')) ? 'rujukan' : 'kontrol');
                @endphp
                <tr data-status="{{ $fkey }}" data-name="{{ strtolower($m->patient->patient_name ?? '') }}">
                    <td style="white-space:nowrap;">
                        <div style="font-weight:600;font-size:13px;">{{ $m->monitoring_date ? \Carbon\Carbon::parse($m->monitoring_date)->format('d M Y') : '-' }}</div>
                        <div style="font-size:11px;color:#8E8E93;">{{ $m->monitoring_time ? \Carbon\Carbon::parse($m->monitoring_time)->format('H:i').' WIB' : '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px;">{{ $m->patient->patient_name ?? '-' }}</div>
                        <div style="font-size:11px;color:#007AFF;">{{ $m->patient->patient_id ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $m->blood_pressure ?? '-' }}</div>
                        <div style="font-size:10.5px;color:#8E8E93;">mmHg</div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $m->body_temperature ?? '-' }}</div>
                        <div style="font-size:10.5px;color:#8E8E93;">°C</div>
                    </td>
                    <td style="font-size:13px;max-width:160px;white-space:normal;">{{ Str::limit($m->symptoms ?? '-', 60) }}</td>
                    <td>
                        @if(str_contains($s,'stable') || str_contains($s,'stabil'))
                            <span class="sv-badge sv-badge-stable">Stabil</span>
                        @elseif(str_contains($s,'referral') || str_contains($s,'rujukan'))
                            <span class="sv-badge sv-badge-referral">Perlu Rujukan</span>
                        @else
                            <span class="sv-badge sv-badge-control">Perlu Kontrol</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#636366;">{{ $m->user->name ?? 'Petugas' }}</td>
                    <td style="text-align:right;">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.monitorings.show', $m->id) }}"
                               class="btn btn-sm btn-outline-primary py-0" style="font-size:11.5px;">Detail</a>
                            <a href="{{ route('admin.monitorings.edit', $m->id) }}"
                               class="btn btn-sm btn-outline-secondary py-0" style="font-size:11.5px;">Edit</a>
                            <form action="{{ route('admin.monitorings.destroy', $m->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus catatan monitoring ini?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size:11.5px;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="sv-empty-state">
                            <i class="bi bi-clipboard2-pulse" style="font-size:40px;color:#D1D5DB;"></i>
                            <p>Belum ada catatan monitoring. <a href="{{ route('admin.monitorings.create') }}">Catat monitoring pertama →</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const rows = document.querySelectorAll('#monBody tr[data-status]');
    const monCount = document.getElementById('monCount');
    let activeFilter = 'all';

    function applyFilters() {
        const q = document.getElementById('tableSearch').value.toLowerCase();
        let visible = 0;
        rows.forEach(row => {
            const matchFilter = activeFilter === 'all' || row.dataset.status === activeFilter;
            const matchSearch = !q || row.dataset.name.includes(q);
            const show = matchFilter && matchSearch;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        monCount.textContent = visible + ' catatan';
    }

    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeFilter = this.dataset.filter;
            applyFilters();
        });
    });

    document.getElementById('tableSearch').addEventListener('input', applyFilters);
</script>
@endsection
