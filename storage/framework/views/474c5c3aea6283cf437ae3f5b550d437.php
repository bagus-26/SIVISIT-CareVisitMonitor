<?php $__env->startSection('title', 'Edit Pasien'); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-page-header sv-animate-in">
    <div>
        <h1>Edit Data Pasien</h1>
        <p>Memperbarui informasi rekam medis pasien: <strong><?php echo e($patient->patient_name); ?></strong></p>
    </div>
    <a href="<?php echo e(route('admin.patients.index')); ?>" class="btn btn-outline-secondary">← Kembali</a>
</div>

<?php if($errors->any()): ?>
<div class="alert alert-danger d-flex align-items-start gap-2 mb-4 sv-animate-in" role="alert">
    <span>⚠️</span>
    <div>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><?php echo e($err); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<form action="<?php echo e(route('admin.patients.update', $patient->patient_id)); ?>" method="POST" id="editPatientForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="row g-3">
        
        <div class="col-md-6 sv-animate-in sv-animate-in-1">
            <div class="form-section h-100 mb-0">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#E8F1FF;">📋</div>
                    <div>
                        <h6>Informasi Petugas Medis</h6>
                        <p>Detail petugas pemeriksa saat registrasi</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="mb-3">
                        <label for="assigned_officer_id" class="form-label">Petugas Kesehatan</label>
                        <select name="assigned_officer_id" id="assigned_officer_id" class="form-select">
                            <option value="">-- Pilih Petugas --</option>
                            <?php $__currentLoopData = $petugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('assigned_officer_id', $patient->assigned_officer_id) == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->name); ?> (<?php echo e($p->nip ?? 'NIP: -'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Diregistrasi Oleh</label>
                        <input type="text" class="form-control" style="background:#F2F4F7;color:#636366;" value="<?php echo e(Auth::user()->name); ?> (<?php echo e(Auth::user()->role); ?>)" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 sv-animate-in sv-animate-in-1">
            <div class="form-section h-100 mb-0">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#FFF0EF;">🩺</div>
                    <div>
                        <h6>Identitas Rekam Medis</h6>
                        <p>Nomor Rekam Medis yang bersifat statis</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="mb-0">
                        <label for="patient_id" class="form-label">Nomor Rekam Medis (No. RM)</label>
                        <input type="text" id="patient_id" class="form-control" style="background:#F2F4F7;color:#636366;" value="<?php echo e($patient->patient_id); ?>" readonly>
                        <div class="validation-hint mt-2">Nomor rekam medis tidak dapat diubah setelah didaftarkan.</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12 sv-animate-in sv-animate-in-2">
            <div class="form-section mb-0">
                <div class="form-section-header">
                    <div class="section-icon" style="background:#E8F8ED;">👤</div>
                    <div>
                        <h6>Data Pribadi &amp; Identitas Pasien</h6>
                        <p>Lengkapi informasi identitas diri pasien binaan</p>
                    </div>
                </div>
                <div class="form-section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nik_dummy" class="form-label">NIK Pasien (16 digit) <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="nik_dummy" id="nik_dummy" class="form-control" placeholder="Masukkan 16 digit NIK" value="<?php echo e(old('nik_dummy', $patient->nik_dummy)); ?>" required pattern="\d{16}">
                        </div>

                        <div class="col-md-6">
                            <label for="patient_name" class="form-label">Nama Lengkap Pasien <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Masukkan nama pasien" value="<?php echo e(old('patient_name', $patient->patient_name)); ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label for="patient_category" class="form-label">Kategori Pasien <span style="color:#FF3B30;">*</span></label>
                            <select name="patient_category" id="patient_category" class="form-select" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="Balita" <?php echo e(old('patient_category', $patient->patient_category) == 'Balita' ? 'selected' : ''); ?>>👶 Balita (0 - 5 Tahun)</option>
                                <option value="Anak-anak" <?php echo e(old('patient_category', $patient->patient_category) == 'Anak-anak' ? 'selected' : ''); ?>>👦 Anak-anak</option>
                                <option value="Dewasa" <?php echo e(old('patient_category', $patient->patient_category) == 'Dewasa' ? 'selected' : ''); ?>>🧑 Dewasa</option>
                                <option value="Lansia" <?php echo e(old('patient_category', $patient->patient_category) == 'Lansia' ? 'selected' : ''); ?>>🧓 Lansia (Lanjut Usia)</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="gender" class="form-label">Jenis Kelamin <span style="color:#FF3B30;">*</span></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="" disabled>-- Pilih Gender --</option>
                                <option value="Male" <?php echo e(old('gender', $patient->gender) == 'Male' ? 'selected' : ''); ?>>👨 Laki-laki</option>
                                <option value="Female" <?php echo e(old('gender', $patient->gender) == 'Female' ? 'selected' : ''); ?>>👩 Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" class="form-control" placeholder="Contoh: Malang" value="Malang">
                        </div>

                        <div class="col-md-3">
                            <label for="datebirth" class="form-label">Tanggal Lahir <span style="color:#FF3B30;">*</span></label>
                            <input type="date" name="datebirth" id="datebirth" class="form-control" value="<?php echo e(old('datebirth', $patient->datebirth)); ?>" required>
                        </div>

                        <div class="col-md-8">
                            <label for="address" class="form-label">Alamat Rumah Lengkap <span style="color:#FF3B30;">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan" value="<?php echo e(old('address', $patient->address)); ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="family_phone" class="form-label">Nomor Telepon Keluarga <span style="color:#FF3B30;">*</span></label>
                            <input type="tel" name="family_phone" id="family_phone" class="form-control" placeholder="Contoh: 081234xxxxxx" value="<?php echo e(old('family_phone', $patient->family_phone)); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12 sv-animate-in sv-animate-in-3 mt-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo e(route('admin.patients.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4" id="submitBtn">💾 Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.getElementById('editPatientForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.textContent = 'Menyimpan...';
        btn.disabled = true;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sivisit_CareVisitMonitor\resources\views\patient\edit-pasien.blade.php ENDPATH**/ ?>