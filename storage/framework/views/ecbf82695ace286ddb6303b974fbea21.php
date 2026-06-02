<?php $__env->startSection('title', 'Detail Perbaikan'); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Perbaikan</h1>
        <p>Kode: <strong><?php echo e($repair->kode_perbaikan); ?></strong></p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?php echo e(route('repairs.edit', $repair)); ?>" class="btn btn-primary"><i class="fas fa-pen"></i> Update</a>
        <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Status Card -->
        <div class="card">
            <div class="card-body" style="display:flex;align-items:center;gap:16px;padding:20px;">
                <div style="width:56px;height:56px;border-radius:14px;background:<?php echo e($repair->status === 'Selesai' ? '#dcfce7' : ($repair->status === 'Sedang Diperbaiki' ? '#e0f2fe' : '#fef9c3')); ?>;display:flex;align-items:center;justify-content:center;font-size:22px;color:<?php echo e($repair->status === 'Selesai' ? '#16a34a' : ($repair->status === 'Sedang Diperbaiki' ? '#0369a1' : '#a16207')); ?>;">
                    <i class="fas fa-<?php echo e($repair->status === 'Selesai' ? 'circle-check' : ($repair->status === 'Sedang Diperbaiki' ? 'gear fa-spin' : 'clock')); ?>"></i>
                </div>
                <div>
                    <p style="font-size:13px;color:#64748b;margin-bottom:2px;">Status Perbaikan</p>
                    <span class="badge badge-<?php echo e($repair->status === 'Selesai' ? 'success' : ($repair->status === 'Sedang Diperbaiki' ? 'info' : 'warning')); ?>" style="font-size:14px;padding:5px 14px;"><?php echo e($repair->status); ?></span>
                </div>
                <?php if($repair->tanggal_selesai): ?>
                <div style="margin-left:auto;text-align:right;">
                    <p style="font-size:12px;color:#94a3b8;">Selesai pada</p>
                    <p style="font-weight:600;font-size:14px;"><?php echo e($repair->tanggal_selesai->format('d M Y')); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Detail -->
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Perbaikan</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Nama Barang</p>
                        <p style="font-weight:600;font-size:15px;"><?php echo e($repair->asset->nama_barang ?? '-'); ?></p>
                        <p style="font-size:12px;color:#94a3b8;"><?php echo e($repair->asset->kode_barang ?? ''); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Lokasi Barang</p>
                        <p style="font-weight:500;"><?php echo e($repair->asset->lokasi_barang ?? '-'); ?></p>
                        <p style="font-size:12px;color:#94a3b8;"><?php echo e($repair->asset->unit_kerja ?? ''); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Dilaporkan Oleh</p>
                        <p style="font-weight:500;"><?php echo e($repair->pelapor->name ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Laporan</p>
                        <p style="font-weight:500;"><?php echo e($repair->tanggal_laporan->format('d M Y')); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Ditangani Oleh</p>
                        <p style="font-weight:500;"><?php echo e($repair->teknisi->name ?? '<span style="color:#94a3b8;font-style:italic;">Belum ditugaskan</span>'); ?></p>
                    </div>
                    <?php if($repair->biaya_perbaikan): ?>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Biaya Perbaikan</p>
                        <p style="font-weight:700;font-size:16px;color:#2563eb;">Rp <?php echo e(number_format($repair->biaya_perbaikan, 0, ',', '.')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:6px;font-weight:600;">Deskripsi Kerusakan</p>
                    <p style="font-size:13.5px;color:#374151;background:#fef9c3;border-radius:8px;padding:12px 14px;border:1px solid #fde68a;line-height:1.6;"><?php echo e($repair->deskripsi_kerusakan); ?></p>
                </div>

                <?php if($repair->tindakan_perbaikan): ?>
                <div style="margin-top:14px;">
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:6px;font-weight:600;">Tindakan Perbaikan</p>
                    <p style="font-size:13.5px;color:#374151;background:#f0fdf4;border-radius:8px;padding:12px 14px;border:1px solid #bbf7d0;line-height:1.6;"><?php echo e($repair->tindakan_perbaikan); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Foto Sidebar -->
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <p style="font-size:12px;color:#94a3b8;margin-bottom:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Foto Kerusakan</p>
            <?php if($repair->foto_kerusakan): ?>
            <img src="<?php echo e(Storage::url($repair->foto_kerusakan)); ?>" alt="Foto Kerusakan"
                 style="width:100%;border-radius:10px;border:1px solid #e2e8f0;object-fit:cover;">
            <?php else: ?>
            <div style="width:100%;aspect-ratio:1;background:#f8fafc;border-radius:10px;border:2px dashed #e2e8f0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#cbd5e1;">
                <i class="fas fa-image" style="font-size:36px;margin-bottom:8px;"></i>
                <p style="font-size:12px;">Tidak ada foto</p>
            </div>
            <?php endif; ?>

            <?php if($repair->asset && $repair->asset->foto): ?>
            <div style="margin-top:14px;padding-top:14px;border-top:1px solid #f1f5f9;">
                <p style="font-size:12px;color:#94a3b8;margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Foto Aset</p>
                <img src="<?php echo e(Storage::url($repair->asset->foto)); ?>" alt="Foto Aset"
                     style="width:100%;border-radius:10px;border:1px solid #e2e8f0;object-fit:cover;">
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/repairs/show.blade.php ENDPATH**/ ?>