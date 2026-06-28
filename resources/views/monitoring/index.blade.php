@extends('layouts.app')
@section('title', 'Data Monitoring')

@section('extra-styles')
<style>
.filter-card { padding:14px 16px; }
@media (max-width: 576px) {
    .filter-card { padding:10px 8px !important; }
    .mon-table th, .mon-table td { font-size:10px !important; padding:5px 3px !important; }
    .mon-table .btn { font-size:9px !important; padding:2px 5px !important; }
    .mon-table { min-width:360px !important; }
    .mon-table th:nth-child(1), .mon-table td:nth-child(1) { min-width:50px; }
    .mon-table th:nth-child(2), .mon-table td:nth-child(2) { min-width:70px; }
}
</style>
@endsection

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-2 sv-animate-in" role="alert" style="font-size:11px;">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger d-flex align-items-center gap-2 mb-2 sv-animate-in" role="alert" style="font-size:11px;">
    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
</div>
@endif

<div class="sv-page-header sv-animate-in" style="margin-bottom:12px;">
    <div>
        <h1 style="font-size:16px;">Data Monitoring</h1>
        <p style="font-size:12px;">Catatan monitoring pasien home care.</p>
    </div>
    <a href="{{ route('admin.monitorings.create') }}" class="btn btn-primary" style="font-size:12px;padding:6px 12px;white-space:nowrap;">
        <i class="bi bi-pencil-square me-1"></i> Catat
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-2 mb-3">
    <div class="col-4 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#34C759;padding:10px;">
            <div class="stat-label" style="font-size:9px;">Stabil</div>
            <div class="stat-value" style="color:#34C759;font-size:18px;">{{ $countStable }}</div>
            <div class="stat-sub" style="font-size:9px;">monitoring</div>
        </div>
    </div>
    <div class="col-4 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#FF9500;padding:10px;">
            <div class="stat-label" style="font-size:9px;">Kontrol</div>
            <div class="stat-value" style="color:#FF9500;font-size:18px;">{{ $countControl }}</div>
            <div class="stat-sub" style="font-size:9px;">tindak lanjut</div>
        </div>
    </div>
    <div class="col-4 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;padding:10px;">
            <div class="stat-label" style="font-size:9px;">Rujukan</div>
            <div class="stat-value" style="color:#FF3B30;font-size:18px;">{{ $countReferral }}</div>
            <div class="stat-sub" style="font-size:9px;">segera</div>
        </div>
    </div>
</div>

{{-- Filter Tabs + Search --}}
<div class="sv-card mb-3 sv-animate-in filter-card">
    <div class="d-flex flex-column gap-2">
        <div class="d-flex gap-1 flex-wrap" id="filterTabs">
            <button class="filter-tab active" data-filter="all" style="font-size:10px;padding:4px 10px;">Semua ({{ $monitorings->count() }})</button>
            <button class="filter-tab tab-stable"   data-filter="stabil" style="font-size:10px;padding:4px 10px;">Stabil ({{ $countStable }})</button>
            <button class="filter-tab tab-control"  data-filter="kontrol" style="font-size:10px;padding:4px 10px;">Kontrol ({{ $countControl }})</button>
            <button class="filter-tab tab-referral" data-filter="rujukan" style="font-size:10px;padding:4px 10px;">Rujukan ({{ $countReferral }})</button>
        </div>
        <div class="search-wrap" style="position:relative;width:100%;">
            <i class="bi bi-search" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);color:#8E8E93;pointer-events:none;font-size:11px;"></i>
            <input type="text" id="tableSearch" placeholder="Cari pasien..."
                   style="padding:5px 8px 5px 26px;border:1.5px solid #D8DCE6;border-radius:6px;font-size:12px;font-family:inherit;outline:none;color:#1C1C1E;width:100%;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#007AFF'" onblur="this.style.borderColor='#D8DCE6'">
        </div>
    </div>
</div>

{{-- Mobile Card List (visible only on small screens) --}}
<div class="d-sm-none">
    @forelse($monitorings as $m)
    @php
        $s = strtolower($m->status ?? '');
        $fkey = (str_contains($s,'stable')||str_contains($s,'stabil')) ? 'stabil'
              : ((str_contains($s,'referral')||str_contains($s,'rujukan')) ? 'rujukan' : 'kontrol');
        $statusBadge = str_contains($s,'stable')||str_contains($s,'stabil') ? 'sv-badge-stable'
                     : (str_contains($s,'referral')||str_contains($s,'rujukan') ? 'sv-badge-referral' : 'sv-badge-control');
        $statusText = str_contains($s,'stable')||str_contains($s,'stabil') ? 'Stabil'
                    : (str_contains($s,'referral')||str_contains($s,'rujukan') ? 'Rujukan' : 'Kontrol');
    @endphp
    <div class="sv-card mb-2" style="padding:10px 12px;" data-status="{{ $fkey }}" data-name="{{ strtolower($m->patient->patient_name ?? '') }}">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <span style="font-weight:600;font-size:13px;">{{ $m->patient->patient_name ?? '-' }}</span>
            <span class="sv-badge {{ $statusBadge }}" style="font-size:9px;padding:2px 8px;">{{ $statusText }}</span>
        </div>
        <div style="font-size:11px;color:#007AFF;">{{ $m->patient->patient_id ?? '-' }}</div>
        <div style="font-size:11px;color:#636366;">
            {{ $m->monitoring_date ? \Carbon\Carbon::parse($m->monitoring_date)->format('d M') : '-' }}
            {{ $m->monitoring_time ? ' · '.\Carbon\Carbon::parse($m->monitoring_time)->format('H:i') : '' }}
            <span style="color:#8E8E93;">· {{ $m->blood_pressure ?? '-' }}</span>
        </div>
        <div class="d-flex gap-1 mt-2">
            <a href="{{ route('admin.monitorings.show', $m->id) }}" class="btn btn-sm btn-outline-primary py-0" style="font-size:10px;">Detail</a>
            <a href="{{ route('admin.monitorings.edit', $m->id) }}" class="btn btn-sm btn-outline-secondary py-0" style="font-size:10px;">Edit</a>
            <form action="{{ route('admin.monitorings.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size:10px;">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="sv-empty-state" style="padding:24px 12px;">
        <i class="bi bi-clipboard2-pulse" style="font-size:28px;color:#D1D5DB;"></i>
        <p style="font-size:13px;">Belum ada catatan monitoring. <a href="{{ route('admin.monitorings.create') }}">Catat monitoring pertama →</a></p>
    </div>
    @endforelse
</div>

{{-- Desktop Table (hidden on mobile) --}}
<div class="d-none d-sm-block sv-table-wrap sv-animate-in">
    <div class="sv-section-header" style="padding:10px 14px;">
        <h5 style="font-size:14px;"><i class="bi bi-clipboard2-pulse me-2" style="color:var(--sv-blue);"></i>Riwayat Monitoring</h5>
        <span style="font-size:12px;color:#8E8E93;" id="monCount">{{ $monitorings->count() }} catatan</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 mon-table" id="monTable" style="min-width:500px;">
            <thead>
                <tr>
                    <th style="font-size:10px;">Tanggal</th>
                    <th style="font-size:10px;">Pasien</th>
                    <th class="d-none d-sm-table-cell" style="font-size:10px;">Tensi</th>
                    <th class="d-none d-sm-table-cell" style="font-size:10px;">Suhu</th>
                    <th class="d-none d-md-table-cell" style="font-size:10px;">Keluhan</th>
                    <th style="font-size:10px;">Status</th>
                    <th class="d-none d-lg-table-cell" style="font-size:10px;">Petugas</th>
                    <th style="font-size:10px;text-align:right;">Aksi</th>
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
                    <td style="white-space:nowrap;font-size:11px;">
                        <div style="font-weight:600;">{{ $m->monitoring_date ? \Carbon\Carbon::parse($m->monitoring_date)->format('d M') : '-' }}</div>
                        <div style="font-size:10px;color:#8E8E93;">{{ $m->monitoring_time ? \Carbon\Carbon::parse($m->monitoring_time)->format('H:i') : '' }}</div>
                    </td>
                    <td style="font-size:11px;">
                        <div style="font-weight:600;">{{ $m->patient->patient_name ?? '-' }}</div>
                        <div style="font-size:10px;color:#007AFF;">{{ $m->patient->patient_id ?? '-' }}</div>
                    </td>
                    <td class="d-none d-sm-table-cell" style="font-size:11px;">
                        <div style="font-weight:600;">{{ $m->blood_pressure ?? '-' }}</div>
                    </td>
                    <td class="d-none d-sm-table-cell" style="font-size:11px;">
                        <div style="font-weight:600;">{{ $m->body_temperature ?? '-' }}</div>
                    </td>
                    <td class="d-none d-md-table-cell" style="font-size:11px;max-width:100px;">{{ Str::limit($m->symptoms ?? '-', 30) }}</td>
                    <td style="font-size:10px;">
                        @if(str_contains($s,'stable') || str_contains($s,'stabil'))
                            <span class="sv-badge sv-badge-stable" style="font-size:10px;">Stabil</span>
                        @elseif(str_contains($s,'referral') || str_contains($s,'rujukan'))
                            <span class="sv-badge sv-badge-referral" style="font-size:10px;">Rujukan</span>
                        @else
                            <span class="sv-badge sv-badge-control" style="font-size:10px;">Kontrol</span>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell" style="font-size:11px;color:#636366;">{{ $m->user->name ?? 'Petugas' }}</td>
                    <td style="text-align:right;">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.monitorings.show', $m->id) }}" class="btn btn-sm btn-outline-primary py-0" style="font-size:10px;">Detail</a>
                            <a href="{{ route('admin.monitorings.edit', $m->id) }}" class="btn btn-sm btn-outline-secondary py-0 d-none d-sm-inline-flex" style="font-size:10px;">Edit</a>
                            <form action="{{ route('admin.monitorings.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 d-none d-sm-inline-flex" style="font-size:10px;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="sv-empty-state" style="padding:24px 12px;">
                            <i class="bi bi-clipboard2-pulse" style="font-size:28px;color:#D1D5DB;"></i>
                            <p style="font-size:13px;">Belum ada catatan monitoring. <a href="{{ route('admin.monitorings.create') }}">Catat monitoring pertama →</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile filter: hide/show cards --}}
<script>
(function() {
    const container = document.querySelector('.d-sm-none');
    const cards = container ? container.querySelectorAll('[data-status]') : [];
    const desktopRows = document.querySelectorAll('#monBody tr[data-status]');
    const monCount = document.getElementById('monCount');
    let activeFilter = 'all';

    function applyFilters() {
        const q = (document.getElementById('tableSearch').value || '').toLowerCase();
        let visible = 0;

        cards.forEach(function(el) {
            const matchFilter = activeFilter === 'all' || el.dataset.status === activeFilter;
            const matchSearch = !q || (el.dataset.name || '').includes(q);
            el.style.display = matchFilter && matchSearch ? '' : 'none';
            if (matchFilter && matchSearch) visible++;
        });

        desktopRows.forEach(function(row) {
            const matchFilter = activeFilter === 'all' || row.dataset.status === activeFilter;
            const matchSearch = !q || (row.dataset.name || '').includes(q);
            row.style.display = matchFilter && matchSearch ? '' : 'none';
            if (matchFilter && matchSearch) visible++;
        });

        if (monCount) monCount.textContent = visible + ' catatan';
    }

    document.querySelectorAll('#filterTabs .filter-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('#filterTabs .filter-tab').forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            activeFilter = this.dataset.filter;
            applyFilters();
        });
    });

    document.getElementById('tableSearch').addEventListener('input', applyFilters);
})();
</script>
@endsection
