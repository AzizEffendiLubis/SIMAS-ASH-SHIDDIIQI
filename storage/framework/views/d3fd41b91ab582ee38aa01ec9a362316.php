<?php $__env->startSection('title', 'Tambah Aset'); ?>
<?php $__env->startSection('page-title', 'Tambah Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Tambah Aset Baru</h1>
        <p>Isi formulir di bawah untuk menambahkan aset baru</p>
    </div>
    <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('assets.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang</p>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_barang" class="form-control <?php echo e($errors->has('nama_barang') ? 'is-invalid' : ''); ?>" placeholder="Contoh: Laptop ASUS VivoBook" value="<?php echo e(old('nama_barang')); ?>">
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
                    <label class="form-label">Lokasi Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="lokasi_barang" class="form-control <?php echo e($errors->has('lokasi_barang') ? 'is-invalid' : ''); ?>" placeholder="Contoh: Ruang Kelas 7A" value="<?php echo e(old('lokasi_barang')); ?>">
                    <?php $__errorArgs = ['lokasi_barang'];
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
                    <select name="unit_kerja" class="form-control <?php echo e($errors->has('unit_kerja') ? 'is-invalid' : ''); ?>">
                        <option value="">-- Pilih Unit --</option>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit); ?>" <?php echo e((old('unit_kerja', auth()->user()->unit_kerja))==$unit?'selected':''); ?>><?php echo e($unit); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['unit_kerja'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;margin-top:8px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Detail &amp; Kondisi</p>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Jumlah Barang <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="jumlah_barang" class="form-control <?php echo e($errors->has('jumlah_barang') ? 'is-invalid' : ''); ?>" min="1" value="<?php echo e(old('jumlah_barang', 1)); ?>">
                    <?php $__errorArgs = ['jumlah_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi Barang <span style="color:#dc2626;">*</span></label>
                    <select name="kondisi_barang" class="form-control <?php echo e($errors->has('kondisi_barang') ? 'is-invalid' : ''); ?>">
                        <option value="Baik" <?php echo e(old('kondisi_barang','Baik')=='Baik'?'selected':''); ?>>Baik</option>
                        <option value="Rusak Ringan" <?php echo e(old('kondisi_barang')=='Rusak Ringan'?'selected':''); ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?php echo e(old('kondisi_barang')=='Rusak Berat'?'selected':''); ?>>Rusak Berat</option>
                    </select>
                    <?php $__errorArgs = ['kondisi_barang'];
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
                    <?php $__errorArgs = ['sumber_dana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="harga_barang" class="form-control <?php echo e($errors->has('harga_barang') ? 'is-invalid' : ''); ?>" min="0" placeholder="0" value="<?php echo e(old('harga_barang')); ?>">
                    <?php $__errorArgs = ['harga_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan <span style="color:#dc2626;">*</span></label>
                    <input type="date" name="tanggal_pengadaan" class="form-control <?php echo e($errors->has('tanggal_pengadaan') ? 'is-invalid' : ''); ?>" value="<?php echo e(old('tanggal_pengadaan', date('Y-m-d'))); ?>">
                    <?php $__errorArgs = ['tanggal_pengadaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Barang</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <p class="form-hint">Format JPG/PNG, maks. 2MB</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan tentang aset ini..."><?php echo e(old('keterangan')); ?></textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Aset</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/assets/create.blade.php ENDPATH**/ ?>