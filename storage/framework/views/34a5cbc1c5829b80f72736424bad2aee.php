
<?php $__env->startSection('title', 'Detail Aset'); ?>
<?php $__env->startSection('page-title', 'Daftar Aset'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Aset</h1>
        <p>Informasi lengkap aset <strong><?php echo e($asset->nama_barang); ?></strong></p>
    </div>
    <div class="ph-right">
        
        
        <?php if(auth()->user()->canEditAset()): ?>
        <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="dash-two-col">

    <!-- Main Info -->
    
    <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

        <div class="card">
            <div class="card-body">
                <p class="section-title">Informasi Barang</p>
                <table class="detail-table">
                    <tr>
                        <td class="dt-label">Kode Aset</td>
                        <td class="dt-val">
                            
                            <code style="font-size:13px;font-weight:700;background:var(--gray-100);padding:3px 9px;border-radius:6px;"><?php echo e($asset->kode_aset); ?></code>
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Nama Barang</td>
                        <td class="dt-val" style="font-weight:600;font-size:15px;"><?php echo e($asset->nama_barang); ?></td>
                    </tr>
                    <tr>
                        <td class="dt-label">Kategori</td>
                        <td class="dt-val"><?php echo e($asset->kategori); ?></td>
                    </tr>
                    <?php if($asset->spesifikasi): ?>
                    <tr>
                        <td class="dt-label">Spesifikasi</td>
                        <td class="dt-val"><?php echo e($asset->spesifikasi); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="dt-label">Lokasi Barang</td>
                        <td class="dt-val"><?php echo e($asset->lokasi_barang ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="dt-label">Unit</td>
                        
                        <td class="dt-val"><?php echo e($asset->unit->nama_unit ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="dt-label">Jumlah Barang</td>
                        
                        <td class="dt-val" style="font-weight:600;font-size:16px;">
                            <?php echo e($asset->jumlah_barang); ?>

                            <span style="font-size:13px;font-weight:400;color:var(--gray-500);">
                                <?php echo e($asset->satuan->nama_satuan ?? 'unit'); ?>

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Kondisi Barang</td>
                        <td class="dt-val">
                            
                            <span class="badge <?php echo e($asset->kondisi_badge); ?>">
                                <?php echo e($asset->kondisi_label); ?>

                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Sumber Dana</td>
                        
                        <td class="dt-val"><?php echo e($asset->fundingSource->nama_sumber ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="dt-label">Harga Barang</td>
                        <td class="dt-val" style="font-weight:700;font-size:15px;color:var(--primary);">
                            Rp <?php echo e(number_format($asset->harga_barang, 0, ',', '.')); ?>

                        </td>
                    </tr>
                    <tr>
                        <td class="dt-label">Tanggal Pengadaan</td>
                        <td class="dt-val">
                            <?php echo e($asset->tanggal_pengadaan ? $asset->tanggal_pengadaan->format('d M Y') : '-'); ?>

                        </td>
                    </tr>
                    <?php if($asset->keterangan_dasar): ?>
                    <tr>
                        <td class="dt-label">Dasar Penambahan</td>
                        <td class="dt-val">
                            <p style="font-size:13px;color:var(--gray-700);background:var(--gray-50);border-radius:8px;padding:9px 11px;border:1px solid var(--gray-200);"><?php echo e($asset->keterangan_dasar); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if($asset->keterangan): ?>
                    <tr>
                        <td class="dt-label">Keterangan</td>
                        <td class="dt-val">
                            <p style="font-size:13px;color:var(--gray-700);background:var(--gray-50);border-radius:8px;padding:9px 11px;border:1px solid var(--gray-200);"><?php echo e($asset->keterangan); ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="dt-label">Ditambahkan Oleh</td>
                        
                        <td class="dt-val"><?php echo e($asset->creator->name ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="dt-label">Tanggal Input</td>
                        <td class="dt-val"><?php echo e($asset->created_at->format('d M Y')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Foto Aset (multi-foto) -->
        <?php if($asset->photos->isNotEmpty()): ?>
        <div class="card">
            <div class="card-header">
                <h2>Foto Aset</h2>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:10px;">
                    <?php $__currentLoopData = $asset->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="position:relative;">
                        <img src="<?php echo e(Storage::url($foto->file_path)); ?>"
                             alt="Foto <?php echo e($asset->nama_barang); ?>"
                             style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;border:<?php echo e($foto->is_primary ? '2px solid var(--primary)' : '1px solid var(--gray-200)'); ?>;">
                        <?php if($foto->is_primary): ?>
                        <span style="position:absolute;bottom:4px;left:4px;background:var(--primary);color:#fff;font-size:9px;padding:2px 5px;border-radius:4px;font-weight:600;">UTAMA</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Riwayat Perbaikan -->
        <div class="card">
            <div class="card-header">
                <h2>Riwayat Perbaikan</h2>
                
                
                
                <?php if(auth()->user()->canAccess('perbaikan_aset') && !auth()->user()->isTeknisi() && !auth()->user()->isKepalaYayasan()): ?>
                <a href="<?php echo e(route('repairs.create')); ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-plus"></i> Laporkan Kerusakan
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                
                <div class="activity-list">
                <?php $__empty_1 = true; $__currentLoopData = $asset->repairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="activity-item" style="flex-direction:column;align-items:stretch;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                            <div>
                                <code style="font-size:12px;background:var(--gray-100);padding:2px 7px;border-radius:5px;"><?php echo e($repair->kode_perbaikan); ?></code>
                                
                                <span class="badge <?php echo e($repair->status_badge); ?>" style="margin-left:6px;font-size:11px;">
                                    <?php echo e($repair->status_label); ?>

                                </span>
                            </div>
                            <span style="font-size:12px;color:var(--gray-400);white-space:nowrap;"><?php echo e($repair->tanggal_laporan->format('d M Y')); ?></span>
                        </div>
                        <p style="font-size:13px;color:var(--gray-700);margin-bottom:3px;"><?php echo e($repair->deskripsi_kerusakan); ?></p>
                        <?php if($repair->tindakan_perbaikan): ?>
                        <p style="font-size:12px;color:var(--gray-500);">
                            <i class="fas fa-wrench" style="margin-right:4px;"></i><?php echo e($repair->tindakan_perbaikan); ?>

                        </p>
                        <?php endif; ?>
                        
                        
                        
                        <?php if((auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()) && $repair->teknisi): ?>
                        <p style="font-size:11px;color:var(--gray-400);margin-top:3px;">
                            <i class="fas fa-user" style="margin-right:3px;"></i><?php echo e($repair->teknisi->name); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle" style="color:#bbf7d0;"></i>
                        <p>Belum ada riwayat perbaikan</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Foto Utama & Riwayat Kondisi -->
    
    <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

        <!-- Foto Utama -->
        <div class="card">
            <div class="card-body" style="text-align:center;">
                <p class="section-title" style="text-align:left;">Foto Utama</p>
                
                <?php if($asset->foto_utama): ?>
                <img src="<?php echo e(Storage::url($asset->foto_utama->file_path)); ?>"
                     alt="Foto <?php echo e($asset->nama_barang); ?>"
                     style="width:100%;border-radius:10px;border:1px solid var(--gray-200);object-fit:cover;">
                <?php else: ?>
                <div style="width:100%;aspect-ratio:1;background:var(--gray-50);border-radius:10px;border:2px dashed var(--gray-200);display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--gray-300);">
                    <i class="fas fa-image" style="font-size:32px;margin-bottom:8px;"></i>
                    <p style="font-size:12px;">Tidak ada foto</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Riwayat Kondisi -->
        <div class="card">
            <div class="card-header">
                <h2>Riwayat Kondisi</h2>
            </div>
            <div class="card-body">
                
                <div class="activity-list">
                <?php $__empty_1 = true; $__currentLoopData = $asset->conditionHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="activity-item" style="flex-direction:column;align-items:stretch;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                            <span style="font-size:13px;font-weight:600;color:var(--gray-800);">
                                
                                <?php echo e($history->getKondisiChangeLabel()); ?>

                            </span>
                            <span style="font-size:11px;color:var(--gray-400);white-space:nowrap;">
                                <?php echo e($history->changed_at?->format('d M Y') ?? '-'); ?>

                            </span>
                        </div>
                        <?php if($history->lokasi_lama || $history->lokasi_baru): ?>
                        <p style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">
                            Lokasi: <?php echo e($history->lokasi_lama ?? '-'); ?> → <?php echo e($history->lokasi_baru ?? '-'); ?>

                        </p>
                        <?php endif; ?>
                        <?php if($history->catatan): ?>
                        <p style="font-size:12px;color:var(--gray-400);"><?php echo e($history->catatan); ?></p>
                        <?php endif; ?>
                        
                        <?php if($history->changedBy): ?>
                        <p style="font-size:11px;color:var(--gray-300);margin-top:2px;">
                            <i class="fas fa-user" style="margin-right:3px;"></i><?php echo e($history->changedBy->name); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <p>Belum ada riwayat kondisi</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/assets/show.blade.php ENDPATH**/ ?>