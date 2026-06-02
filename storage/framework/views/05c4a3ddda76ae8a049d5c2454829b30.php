
<?php $__env->startSection('title', 'Perbaikan Aset'); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Perbaikan Aset</h1>
        <p>Kelola laporan kerusakan dan perbaikan aset</p>
    </div>
    <div class="ph-right">
        
        <?php if(!auth()->user()->isTeknisi()): ?>
        <a href="<?php echo e(route('repairs.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Laporkan Kerusakan
        </a>
        <?php endif; ?>
    </div>
</div>


<div class="card mb-16">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="<?php echo e(route('repairs.index')); ?>" class="filter-row">

            <div class="search-wrap" style="flex:1;min-width:200px;">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama barang, kode, deskripsi..."
                    value="<?php echo e(request('search')); ?>">
            </div>

            
            <select name="status" class="form-control" style="min-width:170px;width:auto;">
                <option value="">Semua Status</option>
                <option value="pending"
                    <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>
                    Menunggu
                </option>
                <option value="sedang_diperbaiki"
                    <?php echo e(request('status') === 'sedang_diperbaiki' ? 'selected' : ''); ?>>
                    Sedang Diperbaiki
                </option>
                <option value="selesai"
                    <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>
                    Selesai
                </option>
            </select>

            
            <select name="sort" class="form-control" style="min-width:140px;width:auto;">
                <option value="terbaru"
                    <?php echo e(request('sort', 'terbaru') === 'terbaru' ? 'selected' : ''); ?>>
                    Terbaru
                </option>
                <option value="terlama"
                    <?php echo e(request('sort') === 'terlama' ? 'selected' : ''); ?>>
                    Terlama
                </option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if(request()->hasAny(['search', 'status', 'sort'])): ?>
                <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Reset
                </a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h2>Semua Laporan</h2>
        <span style="font-size:12px;color:var(--gray-400);">
            <?php echo e($repairs->total()); ?> laporan ditemukan
        </span>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <?php
                $colCount = (auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()) ? 8 : 7;
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th style="white-space:nowrap;">Tanggal</th>
                        
                        <?php if(auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()): ?>
                        <th>Petugas</th>
                        <?php endif; ?>
                        <th>Status</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $repairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <code style="font-size:12px;background:var(--gray-100);
                                padding:2px 7px;border-radius:5px;color:var(--gray-600);">
                                <?php echo e($repair->kode_perbaikan); ?>

                            </code>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                <?php echo e($repair->nama_aset_laporan); ?>

                            </div>
                            
                            <?php if($repair->asset): ?>
                            <div style="font-size:12px;color:var(--gray-400);">
                                <?php echo e($repair->asset->kode_aset); ?> · <?php echo e($repair->asset->kategori); ?>

                            </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:13px;color:var(--gray-500);">
                            
                            <?php echo e($repair->lokasi_kerusakan
                                ?? optional($repair->asset)->lokasi_barang
                                ?? '—'); ?>

                        </td>
                        <td style="font-size:13px;color:var(--gray-600);max-width:200px;">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;"
                                 title="<?php echo e($repair->deskripsi_kerusakan); ?>">
                                <?php echo e($repair->deskripsi_kerusakan); ?>

                            </div>
                        </td>
                        <td style="font-size:13px;white-space:nowrap;color:var(--gray-600);">
                            <?php echo e($repair->tanggal_laporan->format('d M Y')); ?>

                        </td>
                        
                        <?php if(auth()->user()->isAdminUtama() || auth()->user()->isTeknisi()): ?>
                        <td style="font-size:13px;">
                            <?php if($repair->teknisi): ?>
                                <span style="font-weight:500;color:var(--gray-700);">
                                    <?php echo e($repair->teknisi->name); ?>

                                </span>
                            <?php else: ?>
                                <span style="color:var(--gray-300);">Belum ditugaskan</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td>
                            
                            <span class="badge <?php echo e($repair->status_badge); ?>">
                                <?php echo e($repair->status_label); ?>

                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('repairs.show', $repair)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <?php if(auth()->user()->isAdminUtama() ||
                                    (auth()->user()->isTeknisi() &&
                                     $repair->ditangani_oleh === auth()->id())): ?>
                                <a href="<?php echo e(route('repairs.edit', $repair)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <?php endif; ?>
                                
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e($colCount); ?>">
                            <div class="empty-state">
                                <i class="fas fa-screwdriver-wrench"></i>
                                <p>
                                    <?php if(request()->hasAny(['search', 'status'])): ?>
                                        Tidak ada laporan yang sesuai filter
                                    <?php else: ?>
                                        Belum ada laporan perbaikan
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($repairs->hasPages()): ?>
        <div class="card-footer">
            <div class="pagination">
                <?php echo e($repairs->appends(request()->query())->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/repairs/index.blade.php ENDPATH**/ ?>