<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Pengaturan Akun</h1>
        <p>Kelola profil dan keamanan akun Anda</p>
    </div>
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

<div class="row">
    
    <div class="col-12 col-lg-6">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-person-circle me-2"></i>Update Profil</h5>
            </div>
            <div class="sv-card-body">
                <form action="<?php echo e(route('admin.settings.update-profile')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1" style="color:var(--sv-blue);"></i>Nama Lengkap
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="name" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1" style="color:var(--sv-blue);"></i>Email
                        </label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="email" name="email" value="<?php echo e(old('email', Auth::user()->email)); ?>" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1" style="color:var(--sv-blue);"></i>Nomor Telepon
                        </label>
                        <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="phone" name="phone" value="<?php echo e(old('phone', Auth::user()->phone)); ?>" required>
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label">
                            <i class="bi bi-geo-alt me-1" style="color:var(--sv-blue);"></i>Lokasi Tugas
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="location" name="location" value="<?php echo e(old('location', Auth::user()->location)); ?>" required>
                        <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-1"></i>Simpan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="col-12 col-lg-6">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-lock-fill me-2"></i>Ubah Password</h5>
            </div>
            <div class="sv-card-body">
                <form action="<?php echo e(route('admin.settings.change-password')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="bi bi-lock me-1" style="color:var(--sv-blue);"></i>Password Saat Ini
                        </label>
                        <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="current_password" name="current_password" required>
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            <i class="bi bi-key me-1" style="color:var(--sv-blue);"></i>Password Baru
                        </label>
                        <input type="password" class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="new_password" name="new_password" required>
                        <small class="text-muted d-block mt-1">Minimal 6 karakter</small>
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1" style="color:var(--sv-blue);"></i>Konfirmasi Password Baru
                        </label>
                        <input type="password" class="form-control <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="new_password_confirmation" name="new_password_confirmation" required>
                        <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-arrow-repeat me-1"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>

        <div class="sv-card sv-animate-in" style="margin-top:15px;">
            <div class="sv-card-header">
                <h5><i class="bi bi-shield-check me-2"></i>Informasi Keamanan</h5>
            </div>
            <div class="sv-card-body">
                <div style="font-size:13px;line-height:1.8;color:#636366;">
                    <p><strong>Role:</strong> <?php echo e(Auth::user()->role == 'admin' ? 'Administrator' : 'Petugas'); ?></p>
                    <p><strong>Email Terverifikasi:</strong>
                        <?php if(Auth::user()->email_verified_at): ?>
                            <span class="text-success"><i class="bi bi-check-circle"></i> Ya</span>
                        <?php else: ?>
                            <span class="text-warning"><i class="bi bi-exclamation-circle"></i> Belum</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Terakhir Login:</strong> <?php echo e(optional(Auth::user()->updated_at)->diffForHumans() ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" style="margin-top:20px;">
    <div class="col-12">
        <div class="sv-card sv-animate-in">
            <div class="sv-card-header">
                <h5><i class="bi bi-info-circle me-2"></i>Informasi Sistem</h5>
            </div>
            <div class="sv-card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <ul style="font-size:13px;line-height:2;color:#636366;list-style:none;padding:0;">
                            <li><strong>Nama Aplikasi:</strong> SIVISIT CareVisit Monitor</li>
                            <li><strong>Versi:</strong> 1.0.0</li>
                            <li><strong>PHP Version:</strong> <?php echo e(phpversion()); ?></li>
                            <li><strong>Database:</strong> MySQL/MariaDB</li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <ul style="font-size:13px;line-height:2;color:#636366;list-style:none;padding:0;">
                            <li><strong>Tanggal Sistem:</strong> <?php echo e(now()->format('d F Y H:i:s')); ?></li>
                            <li><strong>Timezone:</strong> Asia/Jakarta</li>
                            <li><strong>User ID:</strong> #<?php echo e(Auth::user()->id); ?></li>
                            <li><strong>Last Activity:</strong> <?php echo e(Auth::user()->updated_at->format('d F Y H:i:s')); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\admin\settings\index.blade.php ENDPATH**/ ?>