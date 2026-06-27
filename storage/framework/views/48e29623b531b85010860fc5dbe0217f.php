<?php $__env->startSection('title', 'Daftar Petugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Manajemen Petugas</h1>
        <p>Kelola data petugas monitoring home care</p>
    </div>
    <a href="<?php echo e(route('admin.staff.create')); ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah Petugas
    </a>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="bi bi-exclamation-triangle me-2"></i>Error!</strong>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><?php echo e($error); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="sv-table-wrap sv-animate-in">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Petugas</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th>Telepon</th>
                    <th>Lokasi Tugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $petugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="font-weight:500;"><?php echo e($petugas->name); ?></div>
                        </td>
                        <td style="font-size:13px;color:#636366;"><?php echo e($petugas->email); ?></td>
                        <td style="font-weight:600;"><?php echo e($petugas->nip); ?></td>
                        <td><?php echo e($petugas->phone); ?></td>
                        <td><?php echo e($petugas->location); ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('admin.staff.edit', $petugas)); ?>"
                                   class="btn btn-sm btn-outline-primary py-0" style="font-size:12px;">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="<?php echo e(route('admin.staff.destroy', $petugas)); ?>" method="POST"
                                      onsubmit="return confirm('Hapus petugas ini?')" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size:12px;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6">
                            <div class="sv-empty-state">
                                <i class="bi bi-inbox" style="font-size:40px;color:#D1D5DB;"></i>
                                <p>Belum ada petugas terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($staff->hasPages()): ?>
        <nav aria-label="Page navigation" style="margin-top: 20px;">
            <?php echo e($staff->links()); ?>

        </nav>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\admin\staff\index.blade.php ENDPATH**/ ?>