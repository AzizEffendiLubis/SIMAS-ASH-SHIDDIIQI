<?php $__env->startSection('title', 'Laporkan Kerusakan'); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Laporkan Kerusakan Aset</h1>
        <p>Isi formulir berikut untuk melaporkan kerusakan aset</p>
    </div>
    <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="<?php echo e(route('repairs.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label">Pilih Aset yang Rusak <span style="color:#dc2626;">*</span></label>
                <select name="asset_id" class="form-control <?php echo e($errors->has('asset_id') ? 'is-invalid' : ''); ?>">
                    <option value="">-- Pilih Aset --</option>
                    <?php $__currentLoopData = $allAssets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($asset->id); ?>" <?php echo e(old('asset_id')==$asset->id?'selected':''); ?>>
                        <?php echo e($asset->nama_barang); ?> – <?php echo e($asset->kode_barang); ?> (<?php echo e($asset->lokasi_barang); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Kerusakan <span style="color:#dc2626;">*</span></label>
                <textarea name="deskripsi_kerusakan" class="form-control <?php echo e($errors->has('deskripsi_kerusakan') ? 'is-invalid' : ''); ?>" rows="4" placeholder="Jelaskan kerusakan yang terjadi secara detail..."><?php echo e(old('deskripsi_kerusakan')); ?></textarea>
                <?php $__errorArgs = ['deskripsi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Tugaskan Petugas Perbaikan</label>
                <select name="ditangani_oleh" class="form-control">
                    <option value="">-- Pilih Petugas (Opsional) --</option>
                    <?php $__currentLoopData = $teknisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t->id); ?>" <?php echo e(old('ditangani_oleh')==$t->id?'selected':''); ?>><?php echo e($t->name); ?> (<?php echo e($t->unit_kerja); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="form-hint">Jika tidak dipilih, dapat ditugaskan nanti oleh Admin.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Kerusakan (Opsional)</label>
                <input type="file" name="foto_kerusakan" class="form-control" accept="image/*">
                <p class="form-hint">Format JPG/PNG, maks. 2MB</p>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/repairs/create.blade.php ENDPATH**/ ?>