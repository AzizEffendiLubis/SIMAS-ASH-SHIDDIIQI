
<?php $__env->startSection('title', 'Update Perbaikan – ' . $repair->kode_perbaikan); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>
<?php $__env->startSection('page-parent', 'Update Laporan'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Update Laporan Perbaikan</h1>
        <p>Kode: <strong style="color:var(--gray-700);"><?php echo e($repair->kode_perbaikan); ?></strong></p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card repair-form-card">
    <div class="card-body">

        
        <div class="form-section">
            <p class="section-title"><i class="fas fa-circle-info"></i> Info Laporan</p>
            <div class="form-grid info-grid">
                <div>
                    <p class="info-label">Nama Barang (Laporan)</p>
                    <p class="info-value"><?php echo e($repair->nama_aset_laporan); ?></p>
                    
                    <?php if($repair->asset): ?>
                    <p class="info-sub"><?php echo e($repair->asset->kode_aset); ?> · <?php echo e($repair->asset->unit->nama_unit ?? ''); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="info-label">Lokasi Kerusakan</p>
                    <p class="info-value"><?php echo e($repair->lokasi_kerusakan ?? '-'); ?></p>
                </div>
                <div>
                    <p class="info-label">Tanggal Laporan</p>
                    <p class="info-value"><?php echo e($repair->tanggal_laporan->format('d M Y')); ?></p>
                </div>
                <div>
                    <p class="info-label">Dilaporkan Oleh</p>
                    <p class="info-value"><?php echo e($repair->pelapor->name ?? '-'); ?></p>
                </div>
            </div>
            <div>
                <p class="info-label" style="margin-bottom:5px;">Deskripsi Kerusakan</p>
                <p class="readonly-box"><?php echo e($repair->deskripsi_kerusakan); ?></p>
            </div>

            
            <?php if($repair->photos->isNotEmpty()): ?>
            <div class="readonly-photos">
                <p class="info-label">Foto Kerusakan</p>
                <div class="readonly-photos-grid">
                    <?php $__currentLoopData = $repair->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(Storage::url($foto->file_path)); ?>" target="_blank">
                        <img src="<?php echo e(Storage::url($foto->file_path)); ?>" alt="Foto kerusakan">
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <form action="<?php echo e(route('repairs.update', $repair)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <?php if(auth()->user()->isTeknisi()): ?>

            <div class="form-section">
                <p class="section-title"><i class="fas fa-pen-to-square"></i> Update Progres</p>

                <div class="form-group">
                    <label class="form-label">
                        Status Perbaikan <span class="required">*</span>
                    </label>
                    <select name="status" class="form-control <?php echo e($errors->has('status') ? 'is-invalid' : ''); ?>">
                        <option value="sedang_diperbaiki"
                            <?php echo e(old('status', $repair->status) === 'sedang_diperbaiki' ? 'selected' : ''); ?>>
                            Sedang Diperbaiki
                        </option>
                        <option value="selesai"
                            <?php echo e(old('status', $repair->status) === 'selesai' ? 'selected' : ''); ?>>
                            Selesai
                        </option>
                        <option value="tidak_dapat_diperbaiki"
                            <?php echo e(old('status', $repair->status) === 'tidak_dapat_diperbaiki' ? 'selected' : ''); ?>>
                            Tidak Dapat Diperbaiki
                        </option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="invalid-feedback"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tindakan Perbaikan <span class="required">*</span>
                    </label>
                    <textarea name="tindakan_perbaikan" rows="4"
                        class="form-control <?php echo e($errors->has('tindakan_perbaikan') ? 'is-invalid' : ''); ?>"
                        placeholder="Jelaskan tindakan perbaikan yang telah dilakukan..."><?php echo e(old('tindakan_perbaikan', $repair->tindakan_perbaikan)); ?></textarea>
                    <?php $__errorArgs = ['tindakan_perbaikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="invalid-feedback"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Biaya Perbaikan (Rp)</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-money-bill-wave"></i>
                        <input type="number" name="biaya_perbaikan" class="form-control"
                            min="0" step="1000"
                            value="<?php echo e(old('biaya_perbaikan', $repair->biaya_perbaikan)); ?>"
                            placeholder="0">
                    </div>
                    <p class="form-hint">Kosongkan jika biaya belum diketahui</p>
                </div>
            </div>

            
            <?php else: ?>

            <div class="form-section">
                <p class="section-title"><i class="fas fa-file-pen"></i> Detail Laporan</p>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Barang (Laporan) <span class="required">*</span>
                        </label>
                        <input type="text" name="nama_aset_laporan"
                            class="form-control <?php echo e($errors->has('nama_aset_laporan') ? 'is-invalid' : ''); ?>"
                            value="<?php echo e(old('nama_aset_laporan', $repair->nama_aset_laporan)); ?>">
                        <?php $__errorArgs = ['nama_aset_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="invalid-feedback"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status Perbaikan <span class="required">*</span>
                        </label>
                        <select name="status" class="form-control <?php echo e($errors->has('status') ? 'is-invalid' : ''); ?>">
                            <option value="pending"
                                <?php echo e(old('status', $repair->status) === 'pending' ? 'selected' : ''); ?>>
                                Menunggu
                            </option>
                            <option value="sedang_diperbaiki"
                                <?php echo e(old('status', $repair->status) === 'sedang_diperbaiki' ? 'selected' : ''); ?>>
                                Sedang Diperbaiki
                            </option>
                            <option value="selesai"
                                <?php echo e(old('status', $repair->status) === 'selesai' ? 'selected' : ''); ?>>
                                Selesai
                            </option>
                            <option value="tidak_dapat_diperbaiki"
                                <?php echo e(old('status', $repair->status) === 'tidak_dapat_diperbaiki' ? 'selected' : ''); ?>>
                                Tidak Dapat Diperbaiki
                            </option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="invalid-feedback"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Deskripsi Kerusakan <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" rows="3"
                        class="form-control <?php echo e($errors->has('deskripsi_kerusakan') ? 'is-invalid' : ''); ?>"><?php echo e(old('deskripsi_kerusakan', $repair->deskripsi_kerusakan)); ?></textarea>
                    <?php $__errorArgs = ['deskripsi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="invalid-feedback"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Kerusakan</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-location-dot"></i>
                        <input type="text" name="lokasi_kerusakan"
                            class="form-control <?php echo e($errors->has('lokasi_kerusakan') ? 'is-invalid' : ''); ?>"
                            value="<?php echo e(old('lokasi_kerusakan', $repair->lokasi_kerusakan)); ?>"
                            placeholder="Contoh: Ruang Kelas 7A, Lab Komputer">
                    </div>
                    <?php $__errorArgs = ['lokasi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="invalid-feedback"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Tindakan Perbaikan</label>
                    <textarea name="tindakan_perbaikan" rows="3"
                        class="form-control <?php echo e($errors->has('tindakan_perbaikan') ? 'is-invalid' : ''); ?>"
                        placeholder="Tindakan yang telah atau akan dilakukan..."><?php echo e(old('tindakan_perbaikan', $repair->tindakan_perbaikan)); ?></textarea>
                    <?php $__errorArgs = ['tindakan_perbaikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="invalid-feedback"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-section">
                <p class="section-title"><i class="fas fa-link"></i> Penugasan &amp; Keterkaitan Aset</p>

                <div class="form-grid">
                    <div class="form-group">
                        
                        <label class="form-label">Kaitkan ke Aset</label>
                        <select name="asset_id" class="form-control <?php echo e($errors->has('asset_id') ? 'is-invalid' : ''); ?>">
                            <option value="">— Belum dikaitkan —</option>
                            <?php $__currentLoopData = $assets ?? \App\Models\Asset::orderBy('nama_barang')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($asset->id); ?>"
                                <?php echo e(old('asset_id', $repair->asset_id) == $asset->id ? 'selected' : ''); ?>>
                                <?php echo e($asset->nama_barang); ?> (<?php echo e($asset->kode_aset); ?>)
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="form-hint">Kaitkan ke aset yang terdaftar di sistem setelah verifikasi</p>
                        <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="invalid-feedback"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        
                        <label class="form-label">Ditangani Oleh (Teknisi)</label>
                        <select name="ditangani_oleh" class="form-control <?php echo e($errors->has('ditangani_oleh') ? 'is-invalid' : ''); ?>">
                            <option value="">— Pilih Teknisi —</option>
                            <?php $__currentLoopData = $teknisiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t->id); ?>"
                                <?php echo e(old('ditangani_oleh', $repair->ditangani_oleh) == $t->id ? 'selected' : ''); ?>>
                                <?php echo e($t->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="form-hint">Hanya teknisi dengan status aktif</p>
                        <?php $__errorArgs = ['ditangani_oleh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="invalid-feedback"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Biaya Perbaikan (Rp)</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-money-bill-wave"></i>
                        <input type="number" name="biaya_perbaikan" class="form-control"
                            min="0" step="1000"
                            value="<?php echo e(old('biaya_perbaikan', $repair->biaya_perbaikan)); ?>"
                            placeholder="0">
                    </div>
                    <p class="form-hint">Kosongkan jika biaya belum diketahui</p>
                </div>
            </div>

            <?php endif; ?>

            
            <div class="form-actions">
                <a href="<?php echo e(route('repairs.show', $repair)); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .repair-form-card { max-width: 740px; }

    .section-title i { margin-right: 5px; }

    .info-grid { gap: 12px; margin-bottom: 12px; }
    .info-label { font-size: 12px; color: var(--gray-400); margin-bottom: 3px; }
    .info-value { font-weight: 600; font-size: 14px; color: var(--gray-700); }
    .info-sub   { font-size: 12px; color: var(--gray-400); }

    .readonly-box {
        font-size: 13.5px;
        color: var(--gray-700);
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        padding: 10px 13px;
        line-height: 1.6;
    }

    .readonly-photos { margin-top: 12px; }
    .readonly-photos-grid { display: flex; gap: 8px; flex-wrap: wrap; }
    .readonly-photos-grid img {
        width: 72px; height: 72px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-200);
    }

    .form-actions {
        display: flex; gap: 10px; justify-content: flex-end;
        padding-top: 4px;
    }

    @media (max-width: 768px) {
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/repairs/edit.blade.php ENDPATH**/ ?>