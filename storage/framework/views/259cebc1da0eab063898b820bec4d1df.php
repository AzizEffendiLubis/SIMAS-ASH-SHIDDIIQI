<?php $__env->startSection('title', 'Pengadaan Aset'); ?>
<?php $__env->startSection('page-title', 'Pengadaan Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header-row">
    <div class="page-header">
        <h1>Pengadaan Aset</h1>
        <p>Daftar pengajuan pengadaan aset
            <?php if(auth()->user()->isAdminUnit()): ?> unit <strong><?php echo e(auth()->user()->unit_kerja); ?></strong> <?php endif; ?>
        </p>
    </div>
    <a href="<?php echo e(route('procurements.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajukan Pengadaan
    </a>
</div>


<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" class="filter-row">
            <div class="search-wrap" style="flex:1;min-width:180px;">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama barang atau kode..."
                    value="<?php echo e(request('search')); ?>">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="Pending"   <?php echo e(request('status')=='Pending'   ?'selected':''); ?>>Pending</option>
                <option value="Disetujui" <?php echo e(request('status')=='Disetujui' ?'selected':''); ?>>Disetujui</option>
                <option value="Ditolak"   <?php echo e(request('status')=='Ditolak'   ?'selected':''); ?>>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary" style="height:42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <?php if(request()->hasAny(['search','status'])): ?>
            <a href="<?php echo e(route('procurements.index')); ?>" class="btn btn-outline" style="height:42px;">Reset</a>
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
                        <th>Kategori</th>
                        <th>Unit</th>
                        <th style="text-align:center;">Jml</th>
                        <th>Est. Harga</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $procurements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;">
                                <?php echo e($proc->kode_pengadaan); ?>

                            </code>
                        </td>
                        <td><div style="font-weight:600;"><?php echo e($proc->nama_barang); ?></div></td>
                        <td style="font-size:13px;"><?php echo e($proc->kategori); ?></td>
                        <td style="font-size:13px;"><?php echo e($proc->unit_kerja); ?></td>
                        <td style="text-align:center;font-weight:600;"><?php echo e($proc->jumlah); ?></td>
                        <td style="font-size:13px;font-weight:600;white-space:nowrap;">
                            Rp <?php echo e(number_format($proc->estimasi_harga, 0, ',', '.')); ?>

                        </td>
                        <td style="font-size:12.5px;color:#64748b;white-space:nowrap;">
                            <?php echo e($proc->tanggal_pengajuan->format('d/m/Y')); ?>

                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($proc->status === 'Disetujui' ? 'success' : ($proc->status === 'Ditolak' ? 'danger' : 'warning')); ?>">
                                <?php echo e($proc->status); ?>

                            </span>
                            <?php if($proc->status === 'Ditolak' && $proc->catatan_approval): ?>
                            <div style="font-size:11px;color:#dc2626;margin-top:2px;" title="<?php echo e($proc->catatan_approval); ?>">
                                <i class="fas fa-circle-info"></i> Ada catatan
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('procurements.show', $proc)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($proc->status === 'Pending'): ?>
                                <button class="btn btn-outline btn-sm btn-icon" style="color:#dc2626;"
                                    title="Batalkan"
                                    onclick="confirmDelete(<?php echo e($proc->id); ?>, '<?php echo e(addslashes($proc->nama_barang)); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-cart-plus" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Belum ada pengajuan pengadaan
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($procurements->hasPages()): ?>
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
            <?php echo e($procurements->links('vendor.pagination.simple')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>


<div class="modal-backdrop" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="confirm-icon" style="background:#fee2e2;color:#dc2626;">
                <i class="fas fa-trash"></i>
            </div>
            <h3>Batalkan Pengadaan</h3>
            <p style="color:#64748b;font-size:13.5px;margin-top:6px;">
                Batalkan pengajuan <strong id="deleteItemName"></strong>?
            </p>
            <p style="font-size:12px;color:#94a3b8;margin-top:4px;">Tindakan ini tidak dapat dibatalkan.</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="deleteForm" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('deleteModal')">Kembali</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = '/procurements/' + id;
    openModal('deleteModal');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/procurements/index.blade.php ENDPATH**/ ?>