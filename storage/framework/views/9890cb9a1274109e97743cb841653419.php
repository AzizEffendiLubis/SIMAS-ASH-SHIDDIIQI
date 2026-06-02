<?php $__env->startSection('title', 'Ajukan Pengadaan'); ?>
<?php $__env->startSection('page-title', 'Pengadaan Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Ajukan Pengadaan Aset</h1>
        <p>Buat pengajuan pengadaan aset baru untuk unit Anda</p>
    </div>
    <a href="<?php echo e(route('procurements.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="<?php echo e(route('procurements.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_barang" class="form-control <?php echo e($errors->has('nama_barang') ? 'is-invalid' : ''); ?>" placeholder="Nama barang yang dibutuhkan" value="<?php echo e(old('nama_barang')); ?>">
                    <?php $__errorArgs = ['nama_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:#dc2626;">*</span></label>
                    <select name="kategori" class="form-control <?php echo e($errors->has('kategori') ? 'is-invalid' : ''); ?>">
                        <option value="">-- Pilih Kategori --</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(old('kategori')==$cat?'selected':''); ?>><?php echo e($cat); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                    <select name="unit_kerja" class="form-control <?php echo e($errors->has('unit_kerja') ? 'is-invalid' : ''); ?>" <?php echo e(auth()->user()->isAdminUnit() ? 'disabled' : ''); ?>>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit); ?>" <?php echo e(old('unit_kerja', auth()->user()->unit_kerja)==$unit?'selected':''); ?>><?php echo e($unit); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if(auth()->user()->isAdminUnit()): ?>
                    <input type="hidden" name="unit_kerja" value="<?php echo e(auth()->user()->unit_kerja); ?>">
                    <?php endif; ?>
                    <?php $__errorArgs = ['unit_kerja'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="jumlah" class="form-control <?php echo e($errors->has('jumlah') ? 'is-invalid' : ''); ?>" min="1" value="<?php echo e(old('jumlah', 1)); ?>">
                    <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Estimasi Harga (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="estimasi_harga" class="form-control <?php echo e($errors->has('estimasi_harga') ? 'is-invalid' : ''); ?>" min="0" placeholder="0" value="<?php echo e(old('estimasi_harga')); ?>">
                    <?php $__errorArgs = ['estimasi_harga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana <span style="color:#dc2626;">*</span></label>
                    <select name="sumber_dana" class="form-control <?php echo e($errors->has('sumber_dana') ? 'is-invalid' : ''); ?>">
                        <?php $__currentLoopData = ['Dana Yayasan','Dana BOS','Hibah','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sd); ?>" <?php echo e(old('sumber_dana')==$sd?'selected':''); ?>><?php echo e($sd); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alasan Pengadaan <span style="color:#dc2626;">*</span></label>
                <textarea name="alasan_pengadaan" class="form-control <?php echo e($errors->has('alasan_pengadaan') ? 'is-invalid' : ''); ?>" rows="4" placeholder="Jelaskan alasan dan urgensi pengadaan barang ini..."><?php echo e(old('alasan_pengadaan')); ?></textarea>
                <?php $__errorArgs = ['alasan_pengadaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="<?php echo e(route('procurements.index')); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Ajukan Pengadaan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/procurements/create.blade.php ENDPATH**/ ?>