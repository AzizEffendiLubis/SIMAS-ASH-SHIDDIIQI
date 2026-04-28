
<?php $__env->startSection('title', 'Persetujuan Pengadaan'); ?>
<?php $__env->startSection('page-title', 'Persetujuan Pengadaan'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Persetujuan Pengadaan</h1>
    <p>Tinjau dan proses pengajuan pengadaan aset dari semua unit</p>
</div>


<?php
    $pending   = $procurements->getCollection()->where('status','Pending')->count();
    $disetujui = $procurements->getCollection()->where('status','Disetujui')->count();
    $ditolak   = $procurements->getCollection()->where('status','Ditolak')->count();
?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value"><?php echo e($procurements->total()); ?></div><div class="stat-label">Total Pengajuan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-hourglass-half"></i></div>
        <div><div class="stat-value"><?php echo e($pending); ?></div><div class="stat-label">Menunggu</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-value"><?php echo e($disetujui); ?></div><div class="stat-label">Disetujui</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="fas fa-circle-xmark"></i></div>
        <div><div class="stat-value"><?php echo e($ditolak); ?></div><div class="stat-label">Ditolak</div></div>
    </div>
</div>


<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" class="filter-row">
            <div class="search-wrap" style="flex:1;min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama barang, kode, unit..."
                    value="<?php echo e(request('search')); ?>">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="Pending"   <?php echo e(request('status')=='Pending'   ?'selected':''); ?>>Menunggu</option>
                <option value="Disetujui" <?php echo e(request('status')=='Disetujui' ?'selected':''); ?>>Disetujui</option>
                <option value="Ditolak"   <?php echo e(request('status')=='Ditolak'   ?'selected':''); ?>>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary" style="height:42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <?php if(request()->hasAny(['search','status'])): ?>
            <a href="<?php echo e(route('approvals.index')); ?>" class="btn btn-outline" style="height:42px;">Reset</a>
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
                        <th>Unit</th>
                        <th>Kategori</th>
                        <th style="text-align:center;">Jml</th>
                        <th>Est. Harga</th>
                        <th>Pengaju</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width:110px;">Aksi</th>
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
                        <td>
                            <div style="font-weight:600;"><?php echo e($proc->nama_barang); ?></div>
                        </td>
                        <td style="font-size:13px;"><?php echo e($proc->unit_kerja); ?></td>
                        <td style="font-size:13px;"><?php echo e($proc->kategori); ?></td>
                        <td style="text-align:center;font-weight:600;"><?php echo e($proc->jumlah); ?></td>
                        <td style="font-size:13px;font-weight:600;white-space:nowrap;">
                            Rp <?php echo e(number_format($proc->estimasi_harga, 0, ',', '.')); ?>

                        </td>
                        <td style="font-size:13px;"><?php echo e($proc->pengaju->name ?? '-'); ?></td>
                        <td style="font-size:12.5px;color:#64748b;white-space:nowrap;">
                            <?php echo e($proc->tanggal_pengajuan->format('d/m/Y')); ?>

                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($proc->status === 'Disetujui' ? 'success' : ($proc->status === 'Ditolak' ? 'danger' : 'warning')); ?>">
                                <?php echo e($proc->status); ?>

                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('approvals.show', $proc)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($proc->status === 'Pending'): ?>
                                <button class="btn btn-success btn-sm btn-icon" title="Setujui"
                                    onclick="openApproveModal(<?php echo e($proc->id); ?>, '<?php echo e(addslashes($proc->nama_barang)); ?>', <?php echo e($proc->jumlah); ?>, '<?php echo e($proc->unit_kerja); ?>', <?php echo e($proc->estimasi_harga); ?>)">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-icon" title="Tolak"
                                    onclick="openRejectModal(<?php echo e($proc->id); ?>, '<?php echo e(addslashes($proc->nama_barang)); ?>')">
                                    <i class="fas fa-times"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-clipboard-check" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Tidak ada data pengadaan
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


<div class="modal-backdrop" id="approveModal">
    <div class="modal" style="max-width:480px;">
        <div class="modal-header">
            <h3 style="color:#15803d;"><i class="fas fa-circle-check" style="margin-right:6px;"></i> Setujui Pengadaan</h3>
            <button class="modal-close" onclick="closeModal('approveModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="approveForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="Disetujui">
            <div class="modal-body">
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 14px;margin-bottom:16px;font-size:13.5px;">
                    <strong id="approveItemName"></strong>
                    <div style="font-size:12.5px;color:#64748b;margin-top:4px;" id="approveItemDetail"></div>
                </div>

                <p class="section-title">Data Realisasi Aset (Opsional)</p>
                <p style="font-size:12px;color:#64748b;margin-bottom:12px;">
                    Isi harga dan tanggal realisasi jika berbeda dari estimasi. Jika dikosongkan, akan menggunakan data estimasi.
                </p>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Harga Realisasi (Rp)</label>
                        <input type="number" name="harga_realisasi" id="hargaRealisasi"
                            class="form-control" min="0" placeholder="Kosongkan = pakai estimasi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Realisasi</label>
                        <input type="date" name="tanggal_realisasi" class="form-control"
                            value="<?php echo e(date('Y-m-d')); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan_approval" class="form-control" rows="2"
                        placeholder="Catatan untuk pengaju..."></textarea>
                </div>

                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 12px;font-size:12.5px;color:#1d4ed8;">
                    <i class="fas fa-info-circle" style="margin-right:4px;"></i>
                    Setelah disetujui, aset akan <strong>otomatis muncul di Daftar Aset</strong>.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('approveModal')">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Ya, Setujui & Tambah Aset
                </button>
            </div>
        </form>
    </div>
</div>


<div class="modal-backdrop" id="rejectModal">
    <div class="modal" style="max-width:420px;">
        <div class="modal-header">
            <h3 style="color:#dc2626;"><i class="fas fa-circle-xmark" style="margin-right:6px;"></i> Tolak Pengadaan</h3>
            <button class="modal-close" onclick="closeModal('rejectModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="rejectForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="Ditolak">
            <div class="modal-body">
                <p style="font-size:13.5px;color:#374151;margin-bottom:14px;">
                    Anda akan menolak pengajuan: <strong id="rejectItemName"></strong>
                </p>
                <div class="form-group">
                    <label class="form-label">Alasan Penolakan <span style="color:#dc2626;">*</span></label>
                    <textarea name="catatan_approval" class="form-control" rows="3"
                        placeholder="Tuliskan alasan penolakan agar pengaju mengetahuinya..."
                        required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('rejectModal')">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak Pengadaan
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openApproveModal(id, name, qty, unit, estimasi) {
    document.getElementById('approveForm').action = '/approvals/' + id + '/action';
    document.getElementById('approveItemName').textContent = name;
    document.getElementById('approveItemDetail').textContent =
        qty + ' unit · ' + unit + ' · Est. Rp ' + Number(estimasi).toLocaleString('id-ID');
    document.getElementById('hargaRealisasi').placeholder = 'Estimasi: Rp ' + Number(estimasi).toLocaleString('id-ID');
    openModal('approveModal');
}

function openRejectModal(id, name) {
    document.getElementById('rejectForm').action = '/approvals/' + id + '/action';
    document.getElementById('rejectItemName').textContent = name;
    openModal('rejectModal');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/procurements/approval.blade.php ENDPATH**/ ?>