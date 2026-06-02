
<?php $__env->startSection('title', 'Master Data'); ?>
<?php $__env->startSection('page-title', 'Master Data'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── MD Overlay (slide-in drawer dari kanan) ── */
    .md-overlay {
        display: none; position: fixed;
        inset: 0; z-index: 300;
        background: rgba(15,23,42,.45);
        align-items: center; justify-content: flex-end;
        animation: mdFadeIn .2s ease;
    }
    .md-overlay.open { display: flex; }
    @keyframes mdFadeIn { from { opacity:0 } to { opacity:1 } }

    .md-box {
        background: #fff;
        width: 100%; max-width: 420px; height: 100vh;
        display: flex; flex-direction: column;
        box-shadow: -8px 0 32px rgba(0,0,0,.12);
        animation: mdSlideIn .25s cubic-bezier(.4,0,.2,1);
        overflow-y: auto;
    }
    @keyframes mdSlideIn {
        from { transform: translateX(40px); opacity:0 }
        to   { transform: translateX(0);    opacity:1 }
    }
    .md-header {
        padding: 18px 20px 14px;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--gray-100); flex-shrink: 0;
    }
    .md-title {
        font-size: 15px; font-weight: 700; color: var(--gray-800);
        display: flex; align-items: center; gap: 8px; margin: 0;
    }
    .md-title i { color: var(--primary); }
    .md-close {
        width: 30px; height: 30px; border: none;
        background: var(--gray-100); border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; color: var(--gray-500); cursor: pointer;
        transition: background var(--transition); flex-shrink: 0;
    }
    .md-close:hover { background: var(--gray-200); }
    .md-body   { padding: 18px 20px; flex: 1; overflow-y: auto; }
    .md-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--gray-100);
        display: flex; justify-content: flex-end; gap: 8px; flex-shrink: 0;
    }

    /* ── Checkbox row ── */
    .check-row {
        display: flex; align-items: center; gap: 9px;
        cursor: pointer; font-size: 13px; font-weight: 600;
        color: var(--gray-700); margin-bottom: 4px;
    }
    .check-row input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer;
    }

    /* ── Kode badge ── */
    .kode-badge {
        font-size: 12px; background: var(--gray-50);
        border: 1px solid var(--gray-200); border-radius: 5px;
        padding: 2px 8px; font-family: monospace; letter-spacing: .5px;
    }

    /* ── Satuan chips ── */
    .satuan-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .satuan-chip {
        background: var(--gray-50); border: 1px solid var(--gray-200);
        border-radius: 8px; padding: 7px 14px;
        font-size: 13.5px; font-weight: 500; color: var(--gray-700);
    }

    /* ── Section divider dalam satu halaman ── */
    .md-section { margin-bottom: 24px; }
    .md-section:last-child { margin-bottom: 0; }

    @media (max-width: 640px) {
        .md-box { max-width: 100%; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Master Data</h1>
        <p>Data referensi sistem: unit kerja, sumber dana, dan satuan aset</p>
    </div>
</div>


<div class="md-section">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-building" style="color:var(--primary);font-size:14px;"></i>
                <h2>Unit Kerja</h2>
                <span class="badge badge-info"><?php echo e($units->count()); ?></span>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:130px;">Kode Unit</th>
                            <th>Nama Unit</th>
                            <th>Deskripsi</th>
                            <th style="width:100px;">Tipe</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:52px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                
                                <span class="kode-badge"><?php echo e($unit->kode_unit); ?></span>
                            </td>
                            <td style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                <?php echo e($unit->nama_unit); ?>

                            </td>
                            <td style="font-size:13px;color:var(--gray-500);">
                                <?php echo e($unit->deskripsi ?? '—'); ?>

                            </td>
                            <td>
                                
                                <?php if($unit->is_yayasan): ?>
                                    <span class="badge badge-warning">Yayasan</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Unit</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($unit->is_active): ?>
                                    <span class="badge badge-aktif">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-nonaktif">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                
                                <button type="button"
                                    class="btn btn-outline btn-sm btn-icon"
                                    title="Edit Unit"
                                    onclick="mdOpenEditUnit(
                                        <?php echo e($unit->id); ?>,
                                        '<?php echo e(e(addslashes($unit->nama_unit))); ?>',
                                        '<?php echo e(e(addslashes($unit->deskripsi ?? ''))); ?>',
                                        <?php echo e($unit->is_active ? 'true' : 'false'); ?>

                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-building"></i>
                                <p>Belum ada unit terdaftar</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="md-section">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-money-bill-wave" style="color:var(--primary);font-size:14px;"></i>
                <h2>Sumber Dana</h2>
                <span class="badge badge-info"><?php echo e($fundingSources->count()); ?></span>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Sumber Dana</th>
                            <th>Deskripsi</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:52px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $fundingSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                <?php echo e($fs->nama_sumber); ?>

                            </td>
                            <td style="font-size:13px;color:var(--gray-500);">
                                <?php echo e($fs->deskripsi ?? '—'); ?>

                            </td>
                            <td>
                                <?php if($fs->is_active): ?>
                                    <span class="badge badge-aktif">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-nonaktif">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-outline btn-sm btn-icon"
                                    title="Edit Sumber Dana"
                                    onclick="mdOpenEditDana(
                                        <?php echo e($fs->id); ?>,
                                        '<?php echo e(e(addslashes($fs->nama_sumber))); ?>',
                                        '<?php echo e(e(addslashes($fs->deskripsi ?? ''))); ?>',
                                        <?php echo e($fs->is_active ? 'true' : 'false'); ?>

                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Belum ada sumber dana terdaftar</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="md-section">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-ruler" style="color:var(--primary);font-size:14px;"></i>
                <h2>Satuan Aset</h2>
                <span class="badge badge-info"><?php echo e($satuanList->count()); ?></span>
            </div>
            <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--gray-400);">
                <i class="fas fa-lock" style="font-size:11px;"></i> Read-only
            </span>
        </div>
        <div class="card-body">
            <p style="font-size:13px;color:var(--gray-400);margin-bottom:14px;">
                Daftar satuan bersifat tetap dan dikelola langsung oleh sistem.
            </p>
            <div class="satuan-chips">
                <?php $__empty_1 = true; $__currentLoopData = $satuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <span class="satuan-chip"><?php echo e($satuan->nama_satuan); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p style="font-size:13px;color:var(--gray-300);">Belum ada satuan terdaftar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<div id="md-overlay" class="md-overlay" onclick="mdHandleOverlayClick(event)">

    
    <div id="md-edit-unit" class="md-box" style="display:none;">
        <div class="md-header">
            <p class="md-title"><i class="fas fa-pen"></i> Edit Unit Kerja</p>
            <button type="button" class="md-close" onclick="mdCloseModal()">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        
        <form method="POST" id="md-form-edit-unit" action="">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="md-body">
                <div class="form-group">
                    <label class="form-label">Nama Unit <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nama_unit" id="md-edit-unit-nama"
                        class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="md-edit-unit-deskripsi"
                        class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="is_active" value="0">
                    <label class="check-row">
                        <input type="checkbox" name="is_active" id="md-edit-unit-aktif" value="1">
                        Unit Aktif
                    </label>
                    <p class="form-hint">Menonaktifkan unit tidak menghapus aset yang sudah ada.</p>
                </div>
            </div>
            <div class="md-footer">
                <button type="button" class="btn btn-outline" onclick="mdCloseModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    
    <div id="md-edit-dana" class="md-box" style="display:none;">
        <div class="md-header">
            <p class="md-title"><i class="fas fa-pen"></i> Edit Sumber Dana</p>
            <button type="button" class="md-close" onclick="mdCloseModal()">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        
        <form method="POST" id="md-form-edit-dana" action="">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="md-body">
                <div class="form-group">
                    <label class="form-label">Nama Sumber Dana <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nama_sumber" id="md-edit-dana-nama"
                        class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="md-edit-dana-deskripsi"
                        class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="is_active" value="0">
                    <label class="check-row">
                        <input type="checkbox" name="is_active" id="md-edit-dana-aktif" value="1">
                        Sumber Dana Aktif
                    </label>
                </div>
            </div>
            <div class="md-footer">
                <button type="button" class="btn btn-outline" onclick="mdCloseModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Buka/tutup drawer ────────────────────────────────────────
function mdOpenModal(boxId) {
    document.querySelectorAll('.md-box').forEach(b => b.style.display = 'none');
    const box = document.getElementById(boxId);
    if (!box) return;
    box.style.display = 'flex';
    box.style.flexDirection = 'column';
    document.getElementById('md-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    const first = box.querySelector('input:not([type="hidden"]), textarea');
    if (first) setTimeout(() => first.focus(), 120);
}

function mdCloseModal() {
    document.getElementById('md-overlay').classList.remove('open');
    document.querySelectorAll('.md-box').forEach(b => b.style.display = 'none');
    document.body.style.overflow = '';
}

function mdHandleOverlayClick(e) {
    if (e.target === document.getElementById('md-overlay')) mdCloseModal();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') mdCloseModal();
});

// ── Populate form edit unit ──────────────────────────────────
// PUT /masterdata/units/{id} → masterdata.units.update
function mdOpenEditUnit(id, nama, deskripsi, isActive) {
    document.getElementById('md-form-edit-unit').action =
        '<?php echo e(route("masterdata.units.update", ":id")); ?>'.replace(':id', id);
    document.getElementById('md-edit-unit-nama').value      = nama;
    document.getElementById('md-edit-unit-deskripsi').value = deskripsi;
    document.getElementById('md-edit-unit-aktif').checked   = isActive;
    mdOpenModal('md-edit-unit');
}

// ── Populate form edit sumber dana ──────────────────────────
// PUT /masterdata/funding-sources/{id} → masterdata.funding.update
function mdOpenEditDana(id, nama, deskripsi, isActive) {
    document.getElementById('md-form-edit-dana').action =
        '<?php echo e(route("masterdata.funding.update", ":id")); ?>'.replace(':id', id);
    document.getElementById('md-edit-dana-nama').value      = nama;
    document.getElementById('md-edit-dana-deskripsi').value = deskripsi;
    document.getElementById('md-edit-dana-aktif').checked   = isActive;
    mdOpenModal('md-edit-dana');
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/master-data/index.blade.php ENDPATH**/ ?>