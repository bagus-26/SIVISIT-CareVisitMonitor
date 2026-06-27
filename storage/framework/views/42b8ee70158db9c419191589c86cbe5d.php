<?php $__env->startSection('title', 'Cari Pasien'); ?>



<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Pencarian Pasien</h1>
        <p>Temukan pasien berdasarkan nama, kode RM, atau NIK dummy.</p>
    </div>
</div>


<div class="search-hero sv-animate-in">
    <h2>🔍 Cari Data Pasien</h2>
    <p>Masukkan nama pasien, kode RM (contoh: P001), atau NIK dummy 16 digit.</p>
    <form action="<?php echo e(route('admin.patients.search')); ?>" method="GET">
        <div class="search-hero-input" style="position:relative;">
            <span class="search-ico">🔍</span>
            <input type="text" name="q"
                   value="<?php echo e($query); ?>"
                   placeholder="Nama, kode RM, atau NIK pasien..."
                   autofocus>
            <button type="submit">Cari</button>
        </div>
    </form>
</div>


<?php if($query !== ''): ?>

    <?php if($results->isEmpty()): ?>
    
    <div class="sv-card sv-animate-in text-center" style="padding:48px 24px;">
        <div style="font-size:48px;margin-bottom:12px;">🔍</div>
        <h5 style="font-weight:700;color:var(--sv-text-main);">Pasien tidak ditemukan</h5>
        <p style="color:var(--sv-text-muted);font-size:13.5px;margin-bottom:20px;">
            Tidak ada pasien dengan kata kunci <strong>"<?php echo e($query); ?>"</strong>.
            Pastikan nama, kode RM, atau NIK yang dimasukkan benar.
        </p>
        <a href="<?php echo e(route('admin.patients.create')); ?>" class="btn btn-primary btn-sm">➕ Tambah Pasien Baru</a>
    </div>

    <?php elseif($results->count() > 1): ?>
    
    <div class="sv-animate-in">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h5 style="font-size:15px;font-weight:700;margin:0;">
                Ditemukan <span style="color:var(--sv-blue);"><?php echo e($results->count()); ?></span> pasien
            </h5>
            <span style="font-size:12px;color:var(--sv-text-muted);">Klik untuk lihat riwayat monitoring</span>
        </div>
        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $gender = ($p->gender ?? '') === 'Male' ? '👨' : '👩';
            $monCount = $p->monitorings->count();
            $latestMon = $p->monitorings->sortByDesc('monitoring_date')->first();
            $age = $p->datebirth ? \Carbon\Carbon::parse($p->datebirth)->age . ' Thn' : '-';
        ?>
        <a href="<?php echo e(route('admin.patients.search', ['q' => $p->patient_id])); ?>" class="result-list-item">
            <div class="result-avatar"><?php echo e($gender); ?></div>
            <div style="flex:1;">
                <div style="font-weight:700;font-size:14px;color:var(--sv-text-main);">
                    <?php echo e($p->patient_name ?? '-'); ?>

                </div>
                <div style="font-size:12px;color:var(--sv-text-muted);margin-top:2px;">
                    <?php echo e($p->patient_id ?? ''); ?>

                    · <?php echo e($age); ?>

                    · <?php echo e($p->patient_category ?? ''); ?>

                </div>
                <div style="font-size:11.5px;color:var(--sv-text-muted);margin-top:2px;">
                    📍 <?php echo e(Str::limit($p->address ?? '-', 60)); ?>

                </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <?php if($latestMon): ?>
                    <?php if($latestMon->status === 'Stable'): ?>
                        <span class="sv-badge sv-badge-stable">✅ Stabil</span>
                    <?php elseif($latestMon->status === 'Need Referral'): ?>
                        <span class="sv-badge sv-badge-referral">🚨 Perlu Rujukan</span>
                    <?php else: ?>
                        <span class="sv-badge sv-badge-control">⚠️ Perlu Kontrol</span>
                    <?php endif; ?>
                <?php endif; ?>
                <div style="font-size:11px;color:var(--sv-text-muted);margin-top:4px;">
                    <?php echo e($monCount); ?> kunjungan
                </div>
            </div>
            <span style="color:var(--sv-blue);font-size:16px;">›</span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php else: ?>
    
    <?php
        $p = $found;
        $gender = ($p->gender ?? '') === 'Male' ? '👨' : '👩';
        $latestMon = $patientMonitorings->first();
        $latestStatus = $latestMon->status ?? '';
        $age = $p->datebirth ? \Carbon\Carbon::parse($p->datebirth)->age . ' Thn' : '-';
    ?>
    <div class="sv-search-result sv-animate-in">
        
        <div class="sv-search-result-header">
            <div class="sv-search-avatar"><?php echo e($gender); ?></div>
            <div style="flex:1;">
                <div style="font-size:18px;font-weight:800;color:white;letter-spacing:-0.3px;">
                    <?php echo e($p->patient_name ?? '-'); ?>

                </div>
                <div style="font-size:12.5px;color:rgba(255,255,255,0.6);margin-top:3px;">
                    <?php echo e($p->patient_id ?? ''); ?>

                    &nbsp;·&nbsp; NIK: <?php echo e($p->nik_dummy ?? ''); ?>

                    &nbsp;·&nbsp; <?php echo e($age); ?>

                </div>
            </div>
            <div>
                <?php if($latestStatus): ?>
                    <?php if($latestStatus === 'Stable'): ?>
                        <span class="sv-status-pill stable">✅ Stabil</span>
                    <?php elseif($latestStatus === 'Need Referral'): ?>
                        <span class="sv-status-pill referral">🚨 Perlu Rujukan</span>
                    <?php else: ?>
                        <span class="sv-status-pill control">⚠️ Perlu Kontrol</span>
                    <?php endif; ?>
                <?php endif; ?>
                <div style="margin-top: 8px;">
                    <a href="<?php echo e(route('admin.rekam-medis.index', ['patient_id' => $p->patient_id])); ?>"
                       style="font-size:12px;color:rgba(255,255,255,0.8); text-decoration: underline;">
                        📂 Rekam Medis →
                    </a>
                </div>
            </div>
        </div>

        
        <div class="p-4">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Kategori</div>
                    <div style="font-size:14px;font-weight:600;margin-top:3px;"><?php echo e($p->patient_category ?? '-'); ?></div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Jenis Kelamin</div>
                    <div style="font-size:14px;font-weight:600;margin-top:3px;"><?php echo e(($p->gender ?? '') === 'Male' ? '👨 Laki-laki' : '👩 Perempuan'); ?></div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">No. HP Keluarga</div>
                    <div style="font-size:14px;font-weight:600;margin-top:3px;"><?php echo e($p->family_phone ?? '-'); ?></div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Total Kunjungan</div>
                    <div style="font-size:22px;font-weight:800;color:var(--sv-blue);margin-top:2px;"><?php echo e($patientMonitorings->count()); ?></div>
                </div>
                <div class="col-12">
                    <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;color:var(--sv-text-muted);">Alamat</div>
                    <div style="font-size:13.5px;color:var(--sv-text-sub);margin-top:3px;">📍 <?php echo e($p->address ?? '-'); ?></div>
                </div>
            </div>

            
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <h6 style="font-size:12px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:var(--sv-text-muted);margin:0;">
                    Riwayat Monitoring Kesehatan
                </h6>
                <a href="<?php echo e(route('admin.monitorings.create', ['patient_id' => $p->patient_id])); ?>"
                   class="btn btn-primary btn-sm" style="font-size:12px;">
                    🩺 Catat Monitoring Baru
                </a>
            </div>

            <?php if($patientMonitorings->isEmpty()): ?>
            <div class="sv-empty-state" style="padding:32px 0;">
                <div class="empty-icon">🩺</div>
                <p>Belum ada catatan monitoring untuk pasien ini.</p>
            </div>
            <?php else: ?>
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
                        <?php $__currentLoopData = $patientMonitorings->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:600;">
                                    <?php echo e($mon->monitoring_date ? \Carbon\Carbon::parse($mon->monitoring_date)->format('d M Y') : '-'); ?>

                                </div>
                                <div style="font-size:11px;color:#8E8E93;">
                                    <?php echo e($mon->monitoring_time ? \Carbon\Carbon::parse($mon->monitoring_time)->format('H:i') . ' WIB' : ''); ?>

                                </div>
                            </td>
                            <td style="font-weight:700;"><?php echo e($mon->blood_pressure ?? '-'); ?> <span style="font-size:10px;color:#8E8E93;">mmHg</span></td>
                            <td><?php echo e($mon->body_temperature ?? '-'); ?>°C</td>
                            <td style="max-width:180px;white-space:normal;color:var(--sv-text-sub);">
                                <?php echo e(Str::limit($mon->symptoms ?? '-', 60)); ?>

                            </td>
                            <td>
                                <?php if($mon->status === 'Stable'): ?>
                                    <span class="sv-badge sv-badge-stable">✅ Stabil</span>
                                <?php elseif($mon->status === 'Need Referral'): ?>
                                    <span class="sv-badge sv-badge-referral">🚨 Perlu Rujukan</span>
                                <?php else: ?>
                                    <span class="sv-badge sv-badge-control">⚠️ Perlu Kontrol</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.monitorings.show', $mon->id)); ?>"
                                   class="btn btn-sm btn-outline-primary py-0"
                                   style="font-size:11px;">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php if($patientMonitorings->count() > 10): ?>
            <div class="text-center mt-3">
                <a href="<?php echo e(route('admin.rekam-medis.index', ['patient_id' => $p->patient_id])); ?>"
                   class="btn btn-sm btn-outline-primary">
                    Lihat Semua <?php echo e($patientMonitorings->count()); ?> Kunjungan →
                </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

<?php else: ?>
    
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
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\admin\search.blade.php ENDPATH**/ ?>