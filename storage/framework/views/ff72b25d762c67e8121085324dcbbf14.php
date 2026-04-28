<?php $__env->startSection('title', 'Edit Aset'); ?>
<?php $__env->startSection('page-title', 'Edit Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Edit Aset</h1>
        <p>Perbarui data aset: <strong><?php echo e($asset->nama_barang); ?></strong></p>
    </div>
    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('assets.update', $asset)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang</p>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;">
                <span style="color:#64748b;">Kode Barang:</span>
                <code style="font-weight:700;color:#1e293b;margin-left:6px;"><?php echo e($asset->kode_barang); ?></code>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_barang" class="form-control <?php echo e($errors->has('nama_barang') ? 'is-invalid' : ''); ?>" value="<?php echo e(old('nama_barang', $asset->nama_barang)); ?>">
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
                    <select name="kategori" class="form-control">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(old('kategori', $asset->kategori)==$cat?'selected':''); ?>><?php echo e($cat); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="lokasi_barang" class="form-control" value="<?php echo e(old('lokasi_barang', $asset->lokasi_barang)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                    <select name="unit_kerja" class="form-control" <?php echo e(auth()->user()->isAdminUnit() ? 'disabled' : ''); ?>>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit); ?>" <?php echo e(old('unit_kerja', $asset->unit_kerja)==$unit?'selected':''); ?>><?php echo e($unit); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if(auth()->user()->isAdminUnit()): ?>
                    <input type="hidden" name="unit_kerja" value="<?php echo e($asset->unit_kerja); ?>">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Barang <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="jumlah_barang" class="form-control" min="1" value="<?php echo e(old('jumlah_barang', $asset->jumlah_barang)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi Barang <span style="color:#dc2626;">*</span></label>
                    <select name="kondisi_barang" class="form-control">
                        <?php $__currentLoopData = ['Baik','Rusak Ringan','Rusak Berat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php echo e(old('kondisi_barang', $asset->kondisi_barang)==$k?'selected':''); ?>><?php echo e($k); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana <span style="color:#dc2626;">*</span></label>
                    <select name="sumber_dana" class="form-control">
                        <?php $__currentLoopData = ['Dana Yayasan','Dana BOS','Hibah','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sd); ?>" <?php echo e(old('sumber_dana', $asset->sumber_dana)==$sd?'selected':''); ?>><?php echo e($sd); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="harga_barang" class="form-control" min="0" value="<?php echo e(old('harga_barang', $asset->harga_barang)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan <span style="color:#dc2626;">*</span></label>
                    <input type="date" name="tanggal_pengadaan" class="form-control" value="<?php echo e(old('tanggal_pengadaan', $asset->tanggal_pengadaan?->format('Y-m-d'))); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Barang (Opsional)</label>
                    <?php if($asset->foto): ?>
                    <div style="margin-bottom:8px;">
                        <img src="<?php echo e(Storage::url($asset->foto)); ?>" alt="foto" style="height:60px;border-radius:6px;border:1px solid #e2e8f0;">
                        <p class="form-hint">Upload baru untuk mengganti foto lama</p>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3"><?php echo e(old('keterangan', $asset->keterangan)); ?></textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/assets/edit.blade.php ENDPATH**/ ?>