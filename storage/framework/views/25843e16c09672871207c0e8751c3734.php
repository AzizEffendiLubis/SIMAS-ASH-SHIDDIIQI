<?php $__env->startSection('title', 'Perbaikan Aset'); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h1>Perbaikan Aset</h1>
        <p>Kelola laporan kerusakan dan perbaikan aset</p>
    </div>
    <?php if(!auth()->user()->isPetugasPerbaikan()): ?>
    <a href="<?php echo e(route('repairs.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Laporkan Kerusakan
    </a>
    <?php endif; ?>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div class="search-bar" style="flex:1;min-width:200px;">
                <input type="text" name="search" class="form-control" placeholder="Cari nama barang, kode perbaikan..." value="<?php echo e(request('search')); ?>">
            </div>
            <div style="min-width:160px;">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Pending" <?php echo e(request('status')=='Pending'?'selected':''); ?>>Pending</option>
                    <option value="Sedang Diperbaiki" <?php echo e(request('status')=='Sedang Diperbaiki'?'selected':''); ?>>Sedang Diperbaiki</option>
                    <option value="Selesai" <?php echo e(request('status')=='Selesai'?'selected':''); ?>>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px;"><i class="fas fa-filter"></i> Filter</button>
            <?php if(request()->hasAny(['search','status'])): ?>
            <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline" style="height:42px;">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Lokasi</th>
                        <th>Deskripsi Kerusakan</th>
                        <th>Tanggal Laporan</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $repairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;"><?php echo e($repair->kode_perbaikan); ?></code></td>
                        <td>
                            <div style="font-weight:600;"><?php echo e($repair->asset->nama_barang ?? '-'); ?></div>
                            <div style="font-size:12px;color:#94a3b8;"><?php echo e($repair->asset->kategori ?? ''); ?></div>
                        </td>
                        <td style="font-size:13px;"><?php echo e($repair->asset->lokasi_barang ?? '-'); ?></td>
                        <td style="font-size:13px;max-width:200px;">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;" title="<?php echo e($repair->deskripsi_kerusakan); ?>">
                                <?php echo e($repair->deskripsi_kerusakan); ?>

                            </div>
                        </td>
                        <td style="font-size:13px;"><?php echo e($repair->tanggal_laporan->format('d/m/Y')); ?></td>
                        <td style="font-size:13px;"><?php echo e($repair->teknisi->name ?? '<span style="color:#94a3b8;">Belum ditugaskan</span>'); ?></td>
                        <td>
                            <span class="badge <?php echo e($repair->status === 'Selesai' ? 'badge-success' : ($repair->status === 'Sedang Diperbaiki' ? 'badge-info' : 'badge-warning')); ?>">
                                <?php echo e($repair->status); ?>

                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('repairs.show', $repair)); ?>" class="btn btn-outline btn-sm btn-icon" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo e(route('repairs.edit', $repair)); ?>" class="btn btn-outline btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                                <?php if(auth()->user()->isSuperAdmin()): ?>
                                <button class="btn btn-outline btn-sm btn-icon" style="color:#dc2626;" title="Hapus" onclick="confirmDelete(<?php echo e($repair->id); ?>, '<?php echo e($repair->kode_perbaikan); ?>')"><i class="fas fa-trash"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-tools" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Tidak ada data perbaikan
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($repairs->hasPages()): ?>
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;"><?php echo e($repairs->links('vendor.pagination.simple')); ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-backdrop" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="icon"><i class="fas fa-trash"></i></div>
            <h3>Hapus Data Perbaikan</h3>
            <p>Hapus laporan <strong id="deleteItemName"></strong>?</p>
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
    document.getElementById('deleteForm').action = '/repairs/' + id;
    openModal('deleteModal');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/repairs/index.blade.php ENDPATH**/ ?>