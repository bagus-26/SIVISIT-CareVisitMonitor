<?php $__env->startSection('title', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Laporan Monitoring</h1>
        <p>Analisis dan statistik monitoring home care</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.reports.export-pdf')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-file-pdf me-1"></i> Export PDF
        </a>
        <a href="<?php echo e(route('admin.reports.export-excel')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
    </div>
</div>

<?php if(session('info')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle me-2"></i><?php echo e(session('info')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4 sv-animate-in">
    <div class="col-12 col-md-6">
        <div class="sv-card">
            <div class="sv-card-body">
                <label class="form-label" style="font-size:12px;color:#8E8E93;text-transform:uppercase;font-weight:600;">Pilih Periode</label>
                <form method="GET" class="d-flex gap-2">
                    <input type="month" name="month" class="form-control" value="<?php echo e($monthYear); ?>" onchange="this.form.submit()">
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-1">
        <div class="sv-stat-card" style="--accent-color:#007AFF;">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-label">Total Pasien</div>
            <div class="stat-value" style="color:#007AFF;"><?php echo e($totalPatients); ?></div>
            <div class="stat-sub">Pasien terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-2">
        <div class="sv-stat-card" style="--accent-color:#FF9500;">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-label">Total Monitoring</div>
            <div class="stat-value" style="color:#FF9500;"><?php echo e($totalMonitorings); ?></div>
            <div class="stat-sub">Periode <?php echo e(date('M Y', strtotime($monthYear . '-01'))); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-3">
        <div class="sv-stat-card" style="--accent-color:#34C759;">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-label">Status Stabil</div>
            <div class="stat-value" style="color:#34C759;"><?php echo e($totalMonitoringsStable); ?></div>
            <div class="stat-sub">Kondisi pasien stabil</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 sv-animate-in sv-animate-in-4">
        <div class="sv-stat-card" style="--accent-color:#FF3B30;">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-label">Perlu Kontrol</div>
            <div class="stat-value" style="color:#FF3B30;"><?php echo e($totalMonitoringsNeedControl); ?></div>
            <div class="stat-sub">Butuh tindak lanjut</div>
        </div>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-12 col-xl-8 sv-animate-in">
        <div class="sv-table-wrap">
            <div class="sv-section-header">
                <h5><i class="bi bi-calendar-event me-2" style="color:var(--sv-blue);"></i>Monitoring Harian</h5>
            </div>
            <table class="table mb-0" style="font-size:13px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Monitoring</th>
                        <th>Status Stabil</th>
                        <th>Perlu Kontrol</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $dailyMonitorings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:600;"><?php echo e(date('d M Y', strtotime($dm->date))); ?></td>
                        <td><?php echo e($dm->count); ?></td>
                        <td><span class="sv-badge sv-badge-stable"><?php echo e($dm->stable_count); ?></span></td>
                        <td><span class="sv-badge sv-badge-control"><?php echo e($dm->control_count); ?></span></td>
                        <td>
                            <?php
                                $percentage = $dm->count > 0 ? round(($dm->stable_count / $dm->count) * 100) : 0;
                            ?>
                            <div class="progress" style="height:6px;background:#E5E7EB;">
                                <div class="progress-bar bg-success" style="width: <?php echo e($percentage); ?>%;"></div>
                            </div>
                            <?php echo e($percentage); ?>%
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5">
                            <div class="sv-empty-state">
                                <i class="bi bi-graph-up" style="font-size:40px;color:#D1D5DB;"></i>
                                <p>Belum ada data monitoring untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="col-12 col-xl-4 sv-animate-in">
        <div class="sv-card">
            <div class="sv-card-header">
                <h5><i class="bi bi-person-check me-2" style="color:var(--sv-blue);"></i>Monitoring per Petugas</h5>
            </div>
            <div class="sv-card-body">
                <?php $__empty_1 = true; $__currentLoopData = $monitoringsByStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-3 pb-3" style="border-bottom:1px solid #E5E7EB;">
                        <div style="font-weight:600;margin-bottom:5px;"><?php echo e($staff->name); ?></div>
                        <div style="font-size:12px;color:#636366;margin-bottom:8px;">
                            Total: <strong><?php echo e($staff->total); ?></strong> | Stabil: <strong style="color:#34C759;"><?php echo e($staff->stable); ?></strong>
                        </div>
                        <?php
                            $staffPercentage = $staff->total > 0 ? round(($staff->stable / $staff->total) * 100) : 0;
                        ?>
                        <div class="progress" style="height:6px;background:#E5E7EB;">
                            <div class="progress-bar bg-success" style="width: <?php echo e($staffPercentage); ?>%;"></div>
                        </div>
                        <small style="color:#8E8E93;"><?php echo e($staffPercentage); ?>% stabil</small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="sv-empty-state">
                        <i class="bi bi-inbox" style="font-size:30px;color:#D1D5DB;"></i>
                        <p style="font-size:12px;">Belum ada data petugas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3" style="margin-top:10px;">
    
    <div class="col-12 col-xl-6 sv-animate-in">
        <div class="sv-card">
            <div class="sv-card-header">
                <h5><i class="bi bi-geo-alt me-2" style="color:var(--sv-blue);"></i>Sebaran Pasien per Lokasi</h5>
            </div>
            <table class="table table-sm mb-0" style="font-size:13px;">
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th>Jumlah Pasien</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $patientsByLocation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:500;"><?php echo e($location->location ?? 'Tidak Ada'); ?></td>
                        <td><?php echo e($location->count); ?></td>
                        <td>
                            <?php
                                $locPercentage = $totalPatients > 0 ? round(($location->count / $totalPatients) * 100) : 0;
                            ?>
                            <div class="progress" style="height:6px;background:#E5E7EB;">
                                <div class="progress-bar bg-info" style="width: <?php echo e($locPercentage); ?>%;"></div>
                            </div>
                            <?php echo e($locPercentage); ?>%
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3">
                            <div class="text-center py-3">
                                <small class="text-muted">Belum ada data lokasi pasien</small>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="col-12 col-xl-6 sv-animate-in">
        <div class="sv-card">
            <div class="sv-card-header">
                <h5><i class="bi bi-info-circle me-2" style="color:var(--sv-blue);"></i>Informasi Laporan</h5>
            </div>
            <div class="sv-card-body">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Periode Laporan:</strong> <?php echo e(date('d F Y', strtotime($monthYear . '-01'))); ?> - <?php echo e(date('d F Y', strtotime($monthYear . '-01 +1 month -1 day'))); ?>

                </div>
                <ul style="font-size:13px;line-height:1.8;color:#636366;list-style:none;padding:0;">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Total Pasien Terdaftar: <strong><?php echo e($totalPatients); ?></strong></li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Total Monitoring: <strong><?php echo e($totalMonitorings); ?></strong></li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Kondisi Stabil: <strong><?php echo e($totalMonitoringsStable); ?></strong></li>
                    <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>Perlu Kontrol: <strong><?php echo e($totalMonitoringsNeedControl); ?></strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>