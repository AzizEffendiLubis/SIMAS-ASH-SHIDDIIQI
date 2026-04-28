<?php $__env->startSection('title', 'Daftar Aset'); ?>
<?php $__env->startSection('page-title', 'Daftar Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h1>Daftar Aset</h1>
        <p>Kelola seluruh aset <?php echo e(auth()->user()->isAdminUnit() ? 'unit '.auth()->user()->unit_kerja : 'pesantren'); ?></p>
    </div>
    <?php if(!auth()->user()->isKepalayayasan()): ?>
    <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Aset
    </a>
    <?php endif; ?>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="<?php echo e(route('assets.index')); ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div class="search-bar" style="flex:1;min-width:200px;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama barang, kode, lokasi..." value="<?php echo e(request('search')); ?>">
            </div>
            <div style="min-width:150px;">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat); ?>" <?php echo e(request('kategori')==$cat?'selected':''); ?>><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php if(auth()->user()->isSuperAdmin() || auth()->user()->iskepalayayasan()): ?>
            <div style="min-width:150px;">
                <select name="unit_kerja" class="form-control">
                    <option value="">Semua Unit</option>
                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($unit); ?>" <?php echo e(request('unit_kerja')==$unit?'selected':''); ?>><?php echo e($unit); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <?php endif; ?>
            <div style="min-width:140px;">
                <select name="kondisi" class="form-control">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" <?php echo e(request('kondisi')=='Baik'?'selected':''); ?>>Baik</option>
                    <option value="Rusak Ringan" <?php echo e(request('kondisi')=='Rusak Ringan'?'selected':''); ?>>Rusak Ringan</option>
                    <option value="Rusak Berat" <?php echo e(request('kondisi')=='Rusak Berat'?'selected':''); ?>>Rusak Berat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <?php if(request()->hasAny(['search','kategori','unit_kerja','kondisi'])): ?>
            <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline" style="height:42px;">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Nama Barang</th>
                        <th>Kode Barang</th>
                        <th>Unit/Lokasi</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th>Kondisi</th>
                        <th>Sumber Dana</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:#94a3b8;"><?php echo e($assets->firstItem() + $i); ?></td>
                        <td>
                            <div style="font-weight:600;color:#1e293b;"><?php echo e($asset->nama_barang); ?></div>
                            <div style="font-size:12px;color:#94a3b8;"><?php echo e($asset->kategori); ?></div>
                        </td>
                        <td><code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;"><?php echo e($asset->kode_barang); ?></code></td>
                        <td>
                            <div><?php echo e($asset->lokasi_barang); ?></div>
                            <div style="font-size:12px;color:#94a3b8;"><?php echo e($asset->unit_kerja); ?></div>
                        </td>
                        <td style="text-align:center;font-weight:600;"><?php echo e($asset->jumlah_barang); ?></td>
                        <td>
                            <span class="badge <?php echo e($asset->kondisi_barang === 'Baik' ? 'badge-success' : ($asset->kondisi_barang === 'Rusak Ringan' ? 'badge-warning' : 'badge-danger')); ?>">
                                <?php echo e($asset->kondisi_barang); ?>

                            </span>
                        </td>
                        <td style="font-size:13px;"><?php echo e($asset->sumber_dana); ?></td>
                        <td style="font-size:13px;font-weight:600;">Rp <?php echo e(number_format($asset->harga_barang, 0, ',', '.')); ?></td>
                        <td>
                            <?php if($asset->foto): ?>
                            <img src="<?php echo e(Storage::url($asset->foto)); ?>" alt="foto" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            <?php else: ?>
                            <div style="width:40px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:16px;"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('assets.show', $asset)); ?>" class="btn btn-outline btn-sm btn-icon" title="Detail"><i class="fas fa-eye"></i></a>
                                <?php if(!auth()->user()->iskepalayayasan()): ?>
                                <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="btn btn-outline btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                                <?php if(auth()->user()->isSuperAdmin()): ?>
                                <button class="btn btn-outline btn-sm btn-icon" style="color:#dc2626;" title="Hapus" onclick="confirmDelete(<?php echo e($asset->id); ?>, '<?php echo e(addslashes($asset->nama_barang)); ?>')"><i class="fas fa-trash"></i></button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-box-open" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Tidak ada data aset
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($assets->hasPages()): ?>
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
            <?php echo e($assets->links('vendor.pagination.simple')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="icon"><i class="fas fa-trash"></i></div>
            <h3>Hapus Aset</h3>
            <p>Apakah Anda yakin ingin menghapus aset</p>
            <p><strong id="deleteItemName" class="strong"></strong></p>
            <p style="font-size:12px;color:#94a3b8;margin-top:6px;">Tindakan ini tidak dapat dibatalkan.</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="deleteForm" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = '/assets/' + id;
    openModal('deleteModal');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/assets/index.blade.php ENDPATH**/ ?>