
<?php $__env->startSection('title', 'Detail Perbaikan – ' . $repair->kode_perbaikan); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>
<?php $__env->startSection('page-parent', 'Detail Laporan'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Perbaikan</h1>
        <p>Kode: <strong style="color:var(--gray-700);"><?php echo e($repair->kode_perbaikan); ?></strong></p>
    </div>
    <div class="ph-right">
        
        <?php if(auth()->user()->isAdminUtama() ||
            (auth()->user()->isTeknisi() && $repair->ditangani_oleh === auth()->id())): ?>
        <a href="<?php echo e(route('repairs.edit', $repair)); ?>" class="btn btn-primary">
            <i class="fas fa-pen"></i> Update
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;align-items:start;" class="dash-two-col">

    
    <div style="display:flex;flex-direction:column;gap:16px;">

        
        <div class="card">
            <div class="card-body" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                
                <?php
                    $statusMeta = match($repair->status) {
                        'selesai'           => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'circle-check'],
                        'sedang_diperbaiki' => ['bg' => '#e0f2fe', 'color' => '#0369a1', 'icon' => 'gear'],
                        default             => ['bg' => '#fef9c3', 'color' => '#a16207', 'icon' => 'clock'],
                    };
                ?>
                <div style="width:54px;height:54px;border-radius:14px;flex-shrink:0;
                    background:<?php echo e($statusMeta['bg']); ?>;color:<?php echo e($statusMeta['color']); ?>;
                    display:flex;align-items:center;justify-content:center;font-size:22px;">
                    <i class="fas fa-<?php echo e($statusMeta['icon']); ?><?php echo e($repair->status === 'sedang_diperbaiki' ? ' fa-spin' : ''); ?>"></i>
                </div>
                <div>
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:4px;">Status Perbaikan</p>
                    
                    <span class="badge <?php echo e($repair->status_badge); ?>" style="font-size:13.5px;padding:4px 14px;">
                        <?php echo e($repair->status_label); ?>

                    </span>
                </div>
                <?php if($repair->tanggal_selesai): ?>
                <div style="margin-left:auto;text-align:right;">
                    <p style="font-size:12px;color:var(--gray-400);">Selesai pada</p>
                    <p style="font-weight:700;font-size:14px;color:var(--gray-700);">
                        <?php echo e($repair->tanggal_selesai->format('d M Y')); ?>

                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Informasi Laporan</h2>
            </div>
            <div class="card-body">
                <div class="form-grid" style="gap:14px;margin-bottom:16px;">
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Nama Barang (Laporan)</p>
                        
                        <p style="font-weight:700;font-size:15px;color:var(--gray-800);">
                            <?php echo e($repair->nama_aset_laporan); ?>

                        </p>
                        
                        <?php if($repair->asset): ?>
                        <p style="font-size:12px;margin-top:2px;">
                            <a href="<?php echo e(route('assets.show', $repair->asset)); ?>"
                               style="color:var(--primary);font-weight:600;">
                                <i class="fas fa-link" style="font-size:10px;"></i>
                                <?php echo e($repair->asset->kode_aset); ?>

                            </a>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Lokasi Kerusakan</p>
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($repair->lokasi_kerusakan ?? '-'); ?></p>
                        <?php if($repair->asset && $repair->asset->unit): ?>
                        <p style="font-size:12px;color:var(--gray-400);margin-top:2px;">
                            <?php echo e($repair->asset->unit->nama_unit); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Dilaporkan Oleh</p>
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($repair->pelapor->name ?? '-'); ?></p>
                        
                        <p style="font-size:12px;color:var(--gray-400);margin-top:2px;">
                            <i class="fas fa-building" style="font-size:10px;opacity:.6;"></i>
                            <?php echo e(optional($repair->pelapor?->unit)->nama_unit ?? 'Tanpa Unit'); ?>

                        </p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Tanggal Laporan</p>
                        <p style="font-weight:500;color:var(--gray-700);">
                            <?php echo e($repair->tanggal_laporan->format('d M Y')); ?>

                        </p>
                    </div>

                    
                    <?php if($showTeknisi): ?>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Ditangani Oleh</p>
                        <?php if($repair->teknisi): ?>
                            <p style="font-weight:500;color:var(--gray-700);"><?php echo e($repair->teknisi->name); ?></p>
                        <?php else: ?>
                            <p style="font-size:13px;color:var(--gray-300);font-style:italic;">Belum ditugaskan</p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if($repair->biaya_perbaikan): ?>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Biaya Perbaikan</p>
                        <p style="font-weight:700;font-size:16px;color:var(--primary);">
                            Rp <?php echo e(number_format($repair->biaya_perbaikan, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div style="padding-top:14px;border-top:1px solid var(--gray-100);">
                    <p style="font-size:12px;color:var(--gray-400);font-weight:600;margin-bottom:6px;">
                        Deskripsi Kerusakan
                    </p>
                    <p style="font-size:13.5px;color:var(--gray-700);
                        background:#fef9c3;border:1px solid #fde68a;
                        border-radius:var(--radius-sm);padding:12px 14px;line-height:1.7;">
                        <?php echo e($repair->deskripsi_kerusakan); ?>

                    </p>
                </div>

                
                <?php if($repair->tindakan_perbaikan): ?>
                <div style="margin-top:12px;">
                    <p style="font-size:12px;color:var(--gray-400);font-weight:600;margin-bottom:6px;">
                        Tindakan Perbaikan
                    </p>
                    <p style="font-size:13.5px;color:var(--gray-700);
                        background:#f0fdf4;border:1px solid #bbf7d0;
                        border-radius:var(--radius-sm);padding:12px 14px;line-height:1.7;">
                        <?php echo e($repair->tindakan_perbaikan); ?>

                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:16px;">

        
        <div class="card">
            <div class="card-header">
                <h2>Foto Kerusakan</h2>
                <?php if($repair->photos->isNotEmpty()): ?>
                <span class="badge badge-secondary"><?php echo e($repair->photos->count()); ?> foto</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if($repair->photos->isNotEmpty()): ?>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <?php $__currentLoopData = $repair->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(Storage::url($foto->file_path)); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo e(Storage::url($foto->file_path)); ?>" alt="Foto kerusakan <?php echo e($loop->iteration); ?>"
                                 style="width:100%;border-radius:var(--radius-sm);
                                        border:1px solid var(--gray-200);
                                        object-fit:cover;max-height:220px;
                                        transition:opacity .15s;"
                                 onmouseover="this.style.opacity='.85'"
                                 onmouseout="this.style.opacity='1'">
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state" style="padding:32px 16px;">
                        <i class="fas fa-image"></i>
                        <p>Tidak ada foto kerusakan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($repair->asset && $repair->asset->foto_utama): ?>
        <div class="card">
            <div class="card-header">
                <h2>Foto Aset</h2>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('assets.show', $repair->asset)); ?>" title="Lihat detail aset">
                    <img src="<?php echo e(Storage::url($repair->asset->foto_utama->file_path)); ?>"
                         alt="Foto aset <?php echo e($repair->asset->nama_barang); ?>"
                         style="width:100%;border-radius:var(--radius-sm);
                                border:1px solid var(--gray-200);object-fit:cover;max-height:200px;">
                </a>
                <p style="font-size:12px;color:var(--gray-400);margin-top:8px;text-align:center;">
                    <?php echo e($repair->asset->nama_barang); ?>

                </p>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/repairs/show.blade.php ENDPATH**/ ?>