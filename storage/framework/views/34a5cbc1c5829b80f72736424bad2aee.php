
<?php $__env->startSection('title', 'Detail Aset'); ?>
<?php $__env->startSection('page-title', 'Daftar Aset'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Aset</h1>
        <p>Informasi lengkap aset <strong><?php echo e($asset->nama_barang); ?></strong></p>
    </div>
    <div style="display:flex;gap:10px;">
        
        
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

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

    <!-- Main Info -->
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kode Aset</p>
                        
                        <code style="font-size:14px;font-weight:700;background:#f1f5f9;padding:4px 10px;border-radius:6px;"><?php echo e($asset->kode_aset); ?></code>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Nama Barang</p>
                        <p style="font-weight:600;font-size:15px;"><?php echo e($asset->nama_barang); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kategori</p>
                        <p style="font-weight:500;"><?php echo e($asset->kategori); ?></p>
                    </div>
                    <?php if($asset->spesifikasi): ?>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Spesifikasi</p>
                        <p style="font-weight:500;"><?php echo e($asset->spesifikasi); ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Lokasi Barang</p>
                        <p style="font-weight:500;"><?php echo e($asset->lokasi_barang ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Unit</p>
                        
                        <p style="font-weight:500;"><?php echo e($asset->unit->nama_unit ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Jumlah Barang</p>
                        
                        <p style="font-weight:600;font-size:18px;">
                            <?php echo e($asset->jumlah_barang); ?>

                            <span style="font-size:13px;font-weight:400;color:#64748b;">
                                <?php echo e($asset->satuan->nama_satuan ?? 'unit'); ?>

                            </span>
                        </p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kondisi Barang</p>
                        
                        <span class="badge <?php echo e($asset->kondisi_badge); ?>">
                            <?php echo e($asset->kondisi_label); ?>

                        </span>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Sumber Dana</p>
                        
                        <p style="font-weight:500;"><?php echo e($asset->fundingSource->nama_sumber ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Harga Barang</p>
                        <p style="font-weight:700;font-size:16px;color:#2563eb;">
                            Rp <?php echo e(number_format($asset->harga_barang, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Pengadaan</p>
                        <p style="font-weight:500;">
                            <?php echo e($asset->tanggal_pengadaan ? $asset->tanggal_pengadaan->format('d M Y') : '-'); ?>

                        </p>
                    </div>
                    <?php if($asset->keterangan_dasar): ?>
                    <div style="grid-column:1/-1;">
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Dasar Penambahan</p>
                        <p style="font-size:13.5px;color:#374151;background:#f8fafc;border-radius:8px;padding:10px 12px;border:1px solid #e2e8f0;"><?php echo e($asset->keterangan_dasar); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($asset->keterangan): ?>
                    <div style="grid-column:1/-1;">
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Keterangan</p>
                        <p style="font-size:13.5px;color:#374151;background:#f8fafc;border-radius:8px;padding:10px 12px;border:1px solid #e2e8f0;"><?php echo e($asset->keterangan); ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Ditambahkan Oleh</p>
                        
                        <p style="font-weight:500;"><?php echo e($asset->creator->name ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Input</p>
                        <p style="font-weight:500;"><?php echo e($asset->created_at->format('d M Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto Aset (multi-foto) -->
        <?php if($asset->photos->isNotEmpty()): ?>
        <div class="card">
            <div class="card-header" style="padding:18px 20px 14px;">
                <h2>Foto Aset</h2>
            </div>
            <div class="card-body" style="padding:0 20px 16px;">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px;">
                    <?php $__currentLoopData = $asset->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="position:relative;">
                        <img src="<?php echo e(Storage::url($foto->file_path)); ?>"
                             alt="Foto <?php echo e($asset->nama_barang); ?>"
                             style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;border:<?php echo e($foto->is_primary ? '2px solid #0C6638' : '1px solid #e2e8f0'); ?>;">
                        <?php if($foto->is_primary): ?>
                        <span style="position:absolute;bottom:4px;left:4px;background:#0C6638;color:#fff;font-size:9px;padding:2px 5px;border-radius:4px;font-weight:600;">UTAMA</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Riwayat Perbaikan -->
        <div class="card">
            <div class="card-header" style="padding:18px 20px 14px;display:flex;align-items:center;justify-content:space-between;">
                <h2>Riwayat Perbaikan</h2>
                
                
                
                <?php if(auth()->user()->canAccess('perbaikan_aset') && !auth()->user()->isTeknisi() && !auth()->user()->isKepalaYayasan()): ?>
                <a href="<?php echo e(route('repairs.create')); ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-plus"></i> Laporkan Kerusakan
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body" style="padding:0 20px 16px;">
                
                <?php $__empty_1 = true; $__currentLoopData = $asset->repairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                        <div>
                            <code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;"><?php echo e($repair->kode_perbaikan); ?></code>
                            
                            <span class="badge <?php echo e($repair->status_badge); ?>" style="margin-left:6px;font-size:11px;">
                                <?php echo e($repair->status_label); ?>

                            </span>
                        </div>
                        <span style="font-size:12px;color:#94a3b8;"><?php echo e($repair->tanggal_laporan->format('d M Y')); ?></span>
                    </div>
                    <p style="font-size:13px;color:#374151;margin-bottom:3px;"><?php echo e($repair->deskripsi_kerusakan); ?></p>
                    <?php if($repair->tindakan_perbaikan): ?>
                    <p style="font-size:12px;color:#64748b;">
                        <i class="fas fa-wrench" style="margin-right:4px;"></i><?php echo e($repair->tindakan_perbaikan); ?>

                    </p>
                    <?php endif; ?>
                    
                    
                    
                    <?php if((auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()) && $repair->teknisi): ?>
                    <p style="font-size:12px;color:#94a3b8;margin-top:3px;">
                        <i class="fas fa-user" style="margin-right:4px;"></i><?php echo e($repair->teknisi->name); ?>

                    </p>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="padding:24px 0;text-align:center;color:#94a3b8;font-size:13px;">
                    <i class="fas fa-check-circle" style="font-size:24px;display:block;margin-bottom:8px;color:#d1fae5;"></i>
                    Belum ada riwayat perbaikan
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar: Foto Utama & Riwayat Kondisi -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Foto Utama -->
        <div class="card">
            <div class="card-body" style="text-align:center;">
                <p style="font-size:12px;color:#94a3b8;margin-bottom:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Foto Utama</p>
                
                <?php if($asset->foto_utama): ?>
                <img src="<?php echo e(Storage::url($asset->foto_utama->file_path)); ?>"
                     alt="Foto <?php echo e($asset->nama_barang); ?>"
                     style="width:100%;border-radius:10px;border:1px solid #e2e8f0;object-fit:cover;">
                <?php else: ?>
                <div style="width:100%;aspect-ratio:1;background:#f8fafc;border-radius:10px;border:2px dashed #e2e8f0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#cbd5e1;">
                    <i class="fas fa-image" style="font-size:36px;margin-bottom:8px;"></i>
                    <p style="font-size:12px;">Tidak ada foto</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Riwayat Kondisi -->
        <div class="card">
            <div class="card-header" style="padding:18px 20px 14px;">
                <h2>Riwayat Kondisi</h2>
            </div>
            <div class="card-body" style="padding:0 20px 16px;">
                
                <?php $__empty_1 = true; $__currentLoopData = $asset->conditionHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="padding:10px 0;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <span style="font-size:13px;font-weight:600;color:#1e293b;">
                            
                            <?php echo e($history->getKondisiChangeLabel()); ?>

                        </span>
                        <span style="font-size:11px;color:#94a3b8;">
                            <?php echo e($history->changed_at?->format('d M Y') ?? '-'); ?>

                        </span>
                    </div>
                    <?php if($history->lokasi_lama || $history->lokasi_baru): ?>
                    <p style="font-size:12px;color:#64748b;margin-bottom:2px;">
                        Lokasi: <?php echo e($history->lokasi_lama ?? '-'); ?> → <?php echo e($history->lokasi_baru ?? '-'); ?>

                    </p>
                    <?php endif; ?>
                    <?php if($history->catatan): ?>
                    <p style="font-size:12px;color:#94a3b8;"><?php echo e($history->catatan); ?></p>
                    <?php endif; ?>
                    
                    <?php if($history->changedBy): ?>
                    <p style="font-size:11px;color:#cbd5e1;margin-top:2px;">
                        <i class="fas fa-user" style="margin-right:3px;"></i><?php echo e($history->changedBy->name); ?>

                    </p>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="padding:20px 0;text-align:center;color:#94a3b8;font-size:13px;">
                    Belum ada riwayat kondisi
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/assets/show.blade.php ENDPATH**/ ?>