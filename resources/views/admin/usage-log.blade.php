@extends('layouts.app')
@section('title', 'AI Usage Log')

@section('content')
    <div class="sv-page-header sv-animate-in">
        <div>
            <h1>AI Usage Log</h1>
            <p>Catatan transparansi penggunaan AI dalam pengembangan SIVISIT CareVisit Monitor.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAiLogModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Entri
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ══ AI USAGE LOG TABLE ══ --}}
    <div class="sv-table-wrap sv-animate-in mb-4">
        <div class="sv-section-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot" style="font-size:18px;color:var(--sv-blue);"></i>
                <h5 style="margin:0;">Tabel AI Usage Log — Format SRS</h5>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span style="font-size:12px;color:#8E8E93;">{{ $aiLogs->count() }} entri</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak
                </button>
            </div>
        </div>

        <div class="sv-ai-disclaimer">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                AI boleh digunakan untuk brainstorming, desain database, debugging, dokumentasi, dan testing,
                tetapi hasilnya <strong>wajib diverifikasi dan dipahami oleh tim</strong>.
                Seluruh kode yang dihasilkan AI telah di-review, dimodifikasi, dan diuji secara manual.
            </div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0" id="aiLogTable">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th style="width:110px;">Tanggal</th>
                        <th style="width:130px;">Nama Anggota</th>
                        <th style="width:130px;">Tools AI</th>
                        <th>Prompt Penting</th>
                        <th>Hasil dari AI</th>
                        <th>Verifikasi / Revisi Tim</th>
                        <th style="width:60px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aiLogs as $i => $log)
                        <tr>
                            <td style="color:#8E8E93;font-size:13px;">{{ $i + 1 }}</td>
                            <td>
                                <span class="sv-date-badge">
                                    {{ $log->tanggal->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sv-member-avatar">{{ strtoupper(substr($log->nama_anggota, 0, 1)) }}</div>
                                    <span style="font-size:13px;font-weight:500;">{{ $log->nama_anggota }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="sv-tool-badge">
                                    <i class="bi bi-cpu me-1"></i>{{ $log->tools_ai }}
                                </span>
                            </td>
                            <td>
                                <div class="sv-log-text">{{ $log->prompt_penting }}</div>
                            </td>
                            <td>
                                <div class="sv-log-text sv-log-result">{{ $log->hasil_dari_ai }}</div>
                            </td>
                            <td>
                                <div class="sv-log-text sv-log-verify">{{ $log->verifikasi_revisi_tim }}</div>
                            </td>
                            <td style="text-align:center;">
                                <form action="{{ route('admin.usage-log.destroy-ai', $log->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus entri ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="padding:2px 8px;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="sv-empty-state">
                                    <i class="bi bi-robot" style="font-size:40px;color:#D1D5DB;"></i>
                                    <p>Belum ada entri AI Usage Log.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ SYSTEM ACTIVITY LOG ══ --}}
    <div class="sv-table-wrap sv-animate-in">
        <div class="sv-section-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-activity" style="font-size:18px;color:#34C759;"></i>
                <h5 style="margin:0;">Log Aktivitas Sistem</h5>
            </div>
            <span style="font-size:12px;color:#8E8E93;">{{ $systemLogs->count() }} aktivitas terakhir</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th style="width:150px;">Waktu</th>
                        <th>Petugas</th>
                        <th style="width:110px;">Aksi</th>
                        <th style="width:120px;">Modul</th>
                        <th>Deskripsi</th>
                        <th style="width:120px;">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($systemLogs as $i => $log)
                        <tr>
                            <td style="color:#8E8E93;font-size:13px;">{{ $i + 1 }}</td>
                            <td style="font-size:12px;color:#636366;white-space:nowrap;">
                                {{ $log->created_at->format('d M Y') }}<br>
                                <span style="color:#8E8E93;">{{ $log->created_at->format('H:i:s') }} WIB</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sv-member-avatar"
                                        style="background:linear-gradient(135deg,#34C759,#30B354);font-size:11px;">
                                        {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600;">{{ $log->user_name ?? 'Sistem' }}</div>
                                        @if($log->user)
                                            <div style="font-size:11px;color:#8E8E93;">{{ $log->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'LOGIN' => ['#E8F8ED', '#1A7A35'],
                                        'LOGOUT' => ['#FFF0EF', '#C0291F'],
                                        'CREATE' => ['#E8F1FF', '#0058D0'],
                                        'UPDATE' => ['#FFF4E5', '#8A4E00'],
                                        'DELETE' => ['#FFF0EF', '#C0291F'],
                                        'READ' => ['#F2F4F7', '#636366'],
                                    ];
                                    [$bg, $fg] = $actionColors[$log->action] ?? ['#F2F4F7', '#636366'];
                                @endphp
                                <span class="sv-badge" style="background:{{ $bg }};color:{{ $fg }};font-size:11px;">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td style="font-size:12.5px;font-weight:500;color:#007AFF;">{{ $log->module }}</td>
                            <td style="font-size:13px;color:#636366;">{{ $log->description ?? '-' }}</td>
                            <td style="font-size:11.5px;color:#8E8E93;font-family:monospace;">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="sv-empty-state">
                                    <i class="bi bi-activity" style="font-size:40px;color:#D1D5DB;"></i>
                                    <p>Belum ada aktivitas sistem yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ MODAL ADD AI LOG ══ --}}
    <div class="modal fade" id="addAiLogModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                <form action="{{ route('admin.usage-log.store-ai') }}" method="POST">
                    @csrf
                    <div class="modal-header"
                        style="background:linear-gradient(135deg,var(--sv-navy),var(--sv-navy-mid));color:white;padding:20px 24px;border-radius:16px 16px 0 0;">
                        <h5 class="modal-title">
                            <i class="bi bi-robot me-2"></i>Tambah Entri AI Usage Log
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:24px;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nama Anggota <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama_anggota" class="form-control"
                                    placeholder="Nama / Tim Kelompok" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tools AI <span class="text-danger">*</span></label>
                                <select name="tools_ai" class="form-control" required>
                                    <option value="">-- Pilih Tools --</option>
                                    <option value="Google Gemini">Google Gemini</option>
                                    <option value="ChatGPT">ChatGPT</option>
                                    <option value="GitHub Copilot">GitHub Copilot</option>
                                    <option value="Claude AI">Claude AI</option>
                                    <option value="Perplexity AI">Perplexity AI</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Prompt Penting <span
                                        class="text-danger">*</span></label>
                                <textarea name="prompt_penting" class="form-control" rows="3"
                                    placeholder="Contoh: Buatkan rancangan database untuk aplikasi monitoring kesehatan..."
                                    required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Hasil dari AI <span
                                        class="text-danger">*</span></label>
                                <textarea name="hasil_dari_ai" class="form-control" rows="3"
                                    placeholder="Contoh: Saran tabel dan relasi database..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Verifikasi / Revisi Tim <span
                                        class="text-danger">*</span></label>
                                <textarea name="verifikasi_revisi_tim" class="form-control" rows="3"
                                    placeholder="Contoh: Direvisi sesuai kebutuhan project, diuji manual..."
                                    required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="border-top:1px solid #F0F2F5;padding:16px 24px;border-radius:0 0 16px 16px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-lg me-1"></i> Simpan Entri
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-styles')
    <style>
        @media print {

            .sv-sidebar,
            .sv-topbar,
            .sv-footer,
            .btn,
            form[action*="destroy"] {
                display: none !important;
            }

            .sv-main {
                margin: 0 !important;
            }

            .sv-content {
                padding: 0 !important;
            }
        }

        .sv-ai-disclaimer {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #EFF6FF;
            border: 1.5px solid #BFDBFE;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 0 24px 16px;
            font-size: 13.5px;
            color: #1E40AF;
            line-height: 1.6;
        }

        .sv-ai-disclaimer i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .sv-date-badge {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            background: #F3F4F6;
            border-radius: 6px;
            padding: 3px 8px;
            white-space: nowrap;
        }

        .sv-member-avatar {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--sv-blue), var(--sv-navy));
            color: white;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sv-tool-badge {
            display: inline-flex;
            align-items: center;
            background: #F0F9FF;
            color: #0369A1;
            border: 1px solid #BAE6FD;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .sv-log-text {
            font-size: 12.5px;
            color: #374151;
            line-height: 1.6;
            max-width: 240px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sv-log-result {
            color: #1A7A35;
        }

        .sv-log-verify {
            color: #8A4E00;
        }
    </style>
@endsection