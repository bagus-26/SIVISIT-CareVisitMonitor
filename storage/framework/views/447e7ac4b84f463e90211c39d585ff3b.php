<?php $__env->startSection('title', 'Rekam Medis'); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Rekam Medis Pasien</h1>
        <p>Riwayat seluruh kunjungan monitoring per pasien binaan.</p>
    </div>
    <a href="<?php echo e(route('admin.monitorings.create')); ?>" class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i> Catat Monitoring
    </a>
</div>


<div class="row g-3 mb-4">
    <div class="col-4 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#007AFF;">
            <div class="stat-icon"><i class="bi bi-folder2-open"></i></div>
            <div class="stat-label">Total Kunjungan</div>
            <div class="stat-value" style="color:#007AFF;"><?php echo e($totalVisits); ?></div>
            <div class="stat-sub">Seluruh monitoring tercatat</div>
        </div>
    </div>
    <div class="col-4 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Stabil</div>
            <div class="stat-value" style="color:#34C759;"><?php echo e($countStable); ?></div>
            <div class="stat-sub">Kondisi terkontrol</div>
        </div>
    </div>
    <div class="col-4 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label">Perlu Tindak Lanjut</div>
            <div class="stat-value" style="color:#FF3B30;"><?php echo e($countControl + $countReferral); ?></div>
            <div class="stat-sub">Kontrol + rujukan</div>
        </div>
    </div>
</div>


<div class="sv-card mb-4 sv-animate-in" style="padding:12px 16px;">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <span style="font-size:12.5px;font-weight:600;color:var(--sv-text-muted);">Filter Pasien:</span>
        <a href="<?php echo e(route('admin.rekam-medis.index')); ?>"
           class="filter-tab <?php echo e(!request('patient_id') ? 'active' : ''); ?>">
           Semua (<?php echo e($patients->count()); ?>)
        </a>
        <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.rekam-medis.index', ['patient_id' => $p->patient_id])); ?>"
           class="filter-tab <?php echo e(request('patient_id') === $p->patient_id ? 'active' : ''); ?>">
            <?php echo e($p->patient_name); ?>

        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<?php if($displayPatients->isEmpty()): ?>
<div class="sv-empty-state" style="padding:60px 24px;">
    <i class="bi bi-folder2-open" style="font-size:40px;color:#D1D5DB;"></i>
    <p>Tidak ada data pasien ditemukan.</p>
</div>
<?php else: ?>
<?php $__currentLoopData = $displayPatients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $pMons  = $monByPatient[$p->patient_id] ?? collect();
    $gender = ($p->gender ?? '') === 'Male' ? 'L' : 'P';
    $age    = isset($p->datebirth) ? \Carbon\Carbon::parse($p->datebirth)->age . ' Thn' : '-';
?>
<div class="sv-animate-in mb-3" style="background:white;border:1px solid var(--sv-border);border-radius:var(--sv-radius);overflow:hidden;box-shadow:var(--sv-shadow-sm);">

    
    <div style="background:linear-gradient(135deg,var(--sv-navy),var(--sv-navy-mid));padding:16px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:white;flex-shrink:0;"><?php echo e($gender); ?></div>
        <div style="flex:1;">
            <div style="font-size:15px;font-weight:700;color:white;margin-bottom:2px;"><?php echo e($p->patient_name ?? '-'); ?></div>
            <div style="font-size:11.5px;color:rgba(255,255,255,0.6);">
                <?php echo e($p->patient_id); ?> &nbsp;·&nbsp; <?php echo e($age); ?> &nbsp;·&nbsp; <?php echo e($p->patient_category ?? '-'); ?>

            </div>
            <div style="font-size:11px;color:rgba(255,255,255,0.45);margin-top:2px;"><i class="bi bi-geo-alt"></i> <?php echo e(Str::limit($p->address ?? '-', 60)); ?></div>
        </div>
        <div style="text-align:right;color:rgba(255,255,255,0.6);font-size:11px;font-weight:600;">
            <div style="font-size:22px;font-weight:800;color:white;line-height:1;"><?php echo e($pMons->count()); ?></div>
            kunjungan
        </div>
    </div>

    
    <div style="padding:20px;">
        <?php if($pMons->isEmpty()): ?>
        <div style="text-align:center;padding:24px;color:var(--sv-text-muted);font-size:13px;">
            Belum ada catatan monitoring.
            <a href="<?php echo e(route('admin.monitorings.create', ['patient_id' => $p->patient_id])); ?>"
               style="color:var(--sv-blue);margin-left:6px;">Catat sekarang →</a>
        </div>
        <?php else: ?>
        <div class="rm-timeline">
            <?php $__currentLoopData = $pMons->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $ms = strtolower($mon->status ?? '');
                $mCls = (str_contains($ms,'stable')||str_contains($ms,'stabil')) ? 'stable'
                       : ((str_contains($ms,'referral')||str_contains($ms,'rujukan')) ? 'referral' : 'control');
                $mLbl = (str_contains($ms,'stable')||str_contains($ms,'stabil')) ? 'Stabil'
                       : ((str_contains($ms,'referral')||str_contains($ms,'rujukan')) ? 'Perlu Rujukan' : 'Perlu Kontrol');
                $lblColors = ['stable'=>'background:#E8F8ED;color:#1A7A35;','control'=>'background:#FFF4E5;color:#8A4E00;','referral'=>'background:#FFF0EF;color:#C0291F;'];
            ?>
            <div class="rm-timeline-item <?php echo e($mCls); ?>">
                <a href="<?php echo e(route('admin.monitorings.show', $mon->id)); ?>" class="rm-timeline-card text-decoration-none d-block">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="rm-date">
                                <?php echo e($mon->monitoring_date ? \Carbon\Carbon::parse($mon->monitoring_date)->format('d M Y') : '-'); ?>

                                <?php echo e($mon->monitoring_time ? '· '.\Carbon\Carbon::parse($mon->monitoring_time)->format('H:i').' WIB' : ''); ?>

                            </div>
                            <div class="rm-title">Monitoring Umum</div>
                            <div class="rm-vitals">
                                <?php if($mon->blood_pressure): ?> <span><i class="bi bi-heart-pulse"></i> <?php echo e($mon->blood_pressure); ?> mmHg</span> <?php endif; ?>
                                <?php if($mon->body_temperature): ?> <span><i class="bi bi-thermometer-half"></i> <?php echo e($mon->body_temperature); ?>°C</span> <?php endif; ?>
                                <?php if($mon->heart_rate): ?> <span><i class="bi bi-activity"></i> <?php echo e($mon->heart_rate); ?> bpm</span> <?php endif; ?>
                            </div>
                            <div style="font-size:11.5px;color:var(--sv-text-muted);margin-top:6px;line-height:1.5;">
                                <i class="bi bi-chat-text"></i> <?php echo e(Str::limit($mon->symptoms, 80)); ?>

                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <span class="sv-badge" style="<?php echo e($lblColors[$mCls]); ?>"><?php echo e($mLbl); ?></span>
                            <div style="font-size:11px;color:var(--sv-blue);margin-top:6px;font-weight:500;">Lihat Detail →</div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;">
            <?php if($pMons->count() > 6): ?>
            <a href="<?php echo e(route('admin.rekam-medis.index', ['patient_id' => $p->patient_id])); ?>"
               class="btn btn-sm btn-outline-secondary">Lihat Semua <?php echo e($pMons->count()); ?> →</a>
            <?php else: ?>
            <div></div>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.monitorings.create', ['patient_id' => $p->patient_id])); ?>"
               class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square me-1"></i> Tambah Monitoring</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\rekam-medis\index.blade.php ENDPATH**/ ?>