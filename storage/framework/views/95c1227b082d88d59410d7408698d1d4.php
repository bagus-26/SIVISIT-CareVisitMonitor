<?php $__env->startSection('title', 'Daftar Pasien'); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Daftar Pasien Binaan</h1>
        <p>Kelola data pasien binaan home care SIVISIT.</p>
    </div>
    <?php if(Auth::user()->role === 'admin'): ?>
    <a href="<?php echo e(route('admin.patients.create')); ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Pasien Baru
    </a>
    <?php endif; ?>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-4 sv-animate-in" role="alert">
    <i class="bi bi-check-circle-fill"></i><span><?php echo e(session('success')); ?></span>
</div>
<?php endif; ?>

<div class="sv-table-wrap sv-animate-in">
    <div class="sv-section-header">
        <h5><i class="bi bi-people me-2" style="color:var(--sv-blue);"></i>Data Pasien Binaan</h5>
        <span style="font-size:12px;color:#8E8E93;"><?php echo e($patients->count()); ?> pasien terdaftar</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Pasien</th>
                    <th>Nama Pasien</th>
                    <th>Usia</th>
                    <th>Diagnosa Medis</th>
                    <th>Alamat</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $latestMonitoring = $p->monitorings->sortByDesc('created_at')->first();
                        $diagnosa = $latestMonitoring ? ($latestMonitoring->symptoms ?? '-') : '-';
                    ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><strong style="color:var(--sv-blue);"><?php echo e($p->patient_id); ?></strong></td>
                        <td class="fw-semibold"><?php echo e($p->patient_name ?? '-'); ?></td>
                        <td><?php echo e(isset($p->datebirth) ? \Carbon\Carbon::parse($p->datebirth)->age . ' Tahun' : '-'); ?></td>
                        <td>
                            <?php if($diagnosa !== '-' && !empty($diagnosa)): ?>
                                <span class="sv-badge sv-badge-referral"><?php echo e($diagnosa); ?></span>
                            <?php else: ?>
                                <span class="sv-badge bg-light text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12.5px;color:var(--sv-text-sub);"><?php echo e(Str::limit($p->address ?? '-', 50)); ?></td>
                        <td style="text-align:right;">
                            <div class="d-inline-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary py-1" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo e($p->patient_id); ?>">Lihat</button>
                                <a href="<?php echo e(route('admin.patients.edit', $p->patient_id)); ?>" class="btn btn-sm btn-outline-secondary py-1">Edit</a>
                                <form action="<?php echo e(route('admin.patients.destroy', $p->patient_id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pasien ini beserta riwayat monitoringnya?')" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="sv-empty-state">
                                <i class="bi bi-people" style="font-size:40px;color:#D1D5DB;"></i>
                                <p>Belum ada data pasien terdaftar.
                                    <?php if(Auth::user()->role === 'admin'): ?>
                                        <a href="<?php echo e(route('admin.patients.create')); ?>">Tambah pasien pertama →</a>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="viewModal<?php echo e($p->patient_id); ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo e($p->patient_id); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background:linear-gradient(135deg,var(--sv-navy),var(--sv-navy-mid));color:white;padding:20px 24px;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" id="viewModalLabel<?php echo e($p->patient_id); ?>">Detail Pasien: <?php echo e($p->patient_name ?? ''); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <span class="text-muted small d-block">ID Pasien / No. RM</span>
                            <strong><?php echo e($p->patient_id ?? '-'); ?></strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">NIK Pasien</span>
                            <strong><?php echo e($p->nik_dummy ?? '-'); ?></strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Kategori &amp; Gender</span>
                            <strong><?php echo e($p->patient_category ?? '-'); ?> (<?php echo e($p->gender === 'Male' ? 'Laki-laki' : 'Perempuan'); ?>)</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Tanggal Lahir</span>
                            <strong><?php echo e(isset($p->datebirth) ? \Carbon\Carbon::parse($p->datebirth)->format('d M Y') : '-'); ?></strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Alamat Lengkap</span>
                            <strong><i class="bi bi-geo-alt me-1"></i><?php echo e($p->address ?? '-'); ?></strong>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Nomor Telepon Darurat</span>
                            <strong><i class="bi bi-telephone me-1"></i><?php echo e($p->family_phone ?? '-'); ?></strong>
                        </div>
                    </div>

                    <hr style="border-color:#F0F2F5;">

                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-clipboard2-pulse me-2"></i>Riwayat Monitoring Kesehatan</h6>
                    <?php if($p->monitorings->isEmpty()): ?>
                        <div class="alert alert-light text-muted small mb-0">Belum ada catatan monitoring kesehatan untuk pasien ini.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle text-center small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal &amp; Jam</th>
                                        <th>Tensi</th>
                                        <th>Nadi</th>
                                        <th>Nafas</th>
                                        <th>Suhu</th>
                                        <th>Saturasi O2</th>
                                        <th>Gejala/Kondisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $p->monitorings->sortByDesc('monitoring_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php echo e(isset($mon->monitoring_date) ? date('d-m-Y', strtotime($mon->monitoring_date)) : ''); ?>

                                                <?php echo e(isset($mon->monitoring_time) ? ' ' . date('H:i', strtotime($mon->monitoring_time)) : ''); ?>

                                            </td>
                                            <td><?php echo e($mon->blood_pressure ?? '-'); ?></td>
                                            <td><?php echo e($mon->heart_rate ?? '-'); ?> bpm</td>
                                            <td><?php echo e($mon->respiratory_rate ?? '-'); ?> x/m</td>
                                            <td><?php echo e($mon->body_temperature ?? '-'); ?> °C</td>
                                            <td><?php echo e($mon->oxygen_saturation ?? '-'); ?>%</td>
                                            <td><?php echo e($mon->symptoms ?? '-'); ?></td>
                                            <td>
                                                <?php if($mon->status === 'Stable'): ?>
                                                    <span class="badge bg-success-subtle text-success">Stable</span>
                                                <?php elseif($mon->status === 'Need Referral'): ?>
                                                    <span class="badge bg-danger-subtle text-danger">Need Referral</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning-subtle text-warning">Need Control</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="border-top:1px solid #F0F2F5;padding:16px 24px;border-radius:0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <a href="<?php echo e(route('admin.monitorings.create', ['patient_id' => $p->patient_id])); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square me-1"></i> Tambah Monitoring
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\patient\pasien.blade.php ENDPATH**/ ?>