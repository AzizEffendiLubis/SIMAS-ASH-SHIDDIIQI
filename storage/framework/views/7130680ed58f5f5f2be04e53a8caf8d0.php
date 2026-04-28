<?php $__env->startSection('title', 'Update Perbaikan'); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Update Laporan Perbaikan</h1>
        <p>Kode: <strong><?php echo e($repair->kode_perbaikan); ?></strong></p>
    </div>
    <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">

        <!-- Info Aset -->
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px 16px;margin-bottom:20px;">
            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:10px;">Info Aset</p>
            <div class="form-grid" style="gap:12px;">
                <div>
                    <p style="font-size:12px;color:#64748b;">Nama Barang</p>
                    <p style="font-weight:600;font-size:14px;"><?php echo e($repair->asset->nama_barang ?? '-'); ?></p>
                </div>
                <div>
                    <p style="font-size:12px;color:#64748b;">Lokasi</p>
                    <p style="font-weight:600;font-size:14px;"><?php echo e($repair->asset->lokasi_barang ?? '-'); ?></p>
                </div>
                <div>
                    <p style="font-size:12px;color:#64748b;">Tanggal Laporan</p>
                    <p style="font-weight:600;font-size:14px;"><?php echo e($repair->tanggal_laporan->format('d M Y')); ?></p>
                </div>
                <div>
                    <p style="font-size:12px;color:#64748b;">Dilaporkan Oleh</p>
                    <p style="font-weight:600;font-size:14px;"><?php echo e($repair->pelapor->name ?? '-'); ?></p>
                </div>
            </div>
            <div style="margin-top:12px;">
                <p style="font-size:12px;color:#64748b;margin-bottom:4px;">Deskripsi Kerusakan</p>
                <p style="font-size:13.5px;color:#374151;background:#fff;border-radius:7px;padding:10px 12px;border:1px solid #e2e8f0;"><?php echo e($repair->deskripsi_kerusakan); ?></p>
            </div>
        </div>

        <form action="<?php echo e(route('repairs.update', $repair)); ?>" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <?php if(auth()->user()->isPetugasPerbaikan()): ?>
            <!-- Teknisi form: update status & tindakan -->
            <div class="form-group">
                <label class="form-label">Status Perbaikan <span style="color:#dc2626;">*</span></label>
                <select name="status" class="form-control">
                    <option value="Pending" <?php echo e(old('status',$repair->status)=='Pending'?'selected':''); ?>>Pending</option>
                    <option value="Sedang Diperbaiki" <?php echo e(old('status',$repair->status)=='Sedang Diperbaiki'?'selected':''); ?>>Sedang Diperbaiki</option>
                    <option value="Selesai" <?php echo e(old('status',$repair->status)=='Selesai'?'selected':''); ?>>Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tindakan Perbaikan <span style="color:#dc2626;">*</span></label>
                <textarea name="tindakan_perbaikan" class="form-control <?php echo e($errors->has('tindakan_perbaikan') ? 'is-invalid' : ''); ?>" rows="4" placeholder="Jelaskan tindakan perbaikan yang telah dilakukan..."><?php echo e(old('tindakan_perbaikan', $repair->tindakan_perbaikan)); ?></textarea>
                <?php $__errorArgs = ['tindakan_perbaikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Biaya Perbaikan (Rp)</label>
                <input type="number" name="biaya_perbaikan" class="form-control" min="0" value="<?php echo e(old('biaya_perbaikan', $repair->biaya_perbaikan)); ?>" placeholder="0">
            </div>

            <?php else: ?>
            <!-- Admin / Super Admin form -->
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Aset <span style="color:#dc2626;">*</span></label>
                    <select name="asset_id" class="form-control">
                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($asset->id); ?>" <?php echo e(old('asset_id',$repair->asset_id)==$asset->id?'selected':''); ?>><?php echo e($asset->nama_barang); ?> (<?php echo e($asset->kode_barang); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Perbaikan <span style="color:#dc2626;">*</span></label>
                    <select name="status" class="form-control">
                        <option value="Pending" <?php echo e(old('status',$repair->status)=='Pending'?'selected':''); ?>>Pending</option>
                        <option value="Sedang Diperbaiki" <?php echo e(old('status',$repair->status)=='Sedang Diperbaiki'?'selected':''); ?>>Sedang Diperbaiki</option>
                        <option value="Selesai" <?php echo e(old('status',$repair->status)=='Selesai'?'selected':''); ?>>Selesai</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Kerusakan <span style="color:#dc2626;">*</span></label>
                <textarea name="deskripsi_kerusakan" class="form-control" rows="3"><?php echo e(old('deskripsi_kerusakan', $repair->deskripsi_kerusakan)); ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindakan Perbaikan</label>
                <textarea name="tindakan_perbaikan" class="form-control" rows="3" placeholder="Tindakan yang telah dilakukan..."><?php echo e(old('tindakan_perbaikan', $repair->tindakan_perbaikan)); ?></textarea>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Ditangani Oleh</label>
                    <select name="ditangani_oleh" class="form-control">
                        <option value="">-- Pilih Petugas --</option>
                        <?php $__currentLoopData = $teknisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->id); ?>" <?php echo e(old('ditangani_oleh',$repair->ditangani_oleh)==$t->id?'selected':''); ?>><?php echo e($t->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Biaya Perbaikan (Rp)</label>
                    <input type="number" name="biaya_perbaikan" class="form-control" min="0" value="<?php echo e(old('biaya_perbaikan', $repair->biaya_perbaikan)); ?>">
                </div>
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/repairs/edit.blade.php ENDPATH**/ ?>