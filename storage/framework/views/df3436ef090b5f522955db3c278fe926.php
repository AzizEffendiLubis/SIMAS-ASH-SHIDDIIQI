
<?php $__env->startSection('title', 'Daftar Aset'); ?>
<?php $__env->startSection('page-title', 'Daftar Aset'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Daftar Aset</h1>
        <p>Kelola seluruh aset <?php echo e(auth()->user()->isAdminUnit() ? 'unit '.auth()->user()->unit->nama_unit : 'pesantren'); ?></p>
    </div>
    <div class="ph-right">
        
        
        <?php if(auth()->user()->canEditAset()): ?>
        <a href="<?php echo e(route('assets.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Aset
        </a>
        <?php endif; ?>
    </div>
</div>


<div class="card mb-16">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="<?php echo e(route('assets.index')); ?>" class="filter-row">

            <div class="search-wrap" style="flex:1;min-width:200px;">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                       placeholder="Cari nama barang, kode, lokasi..."
                       value="<?php echo e(request('search')); ?>">
            </div>

            <select name="kategori" class="form-control" style="min-width:150px;width:auto;">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat); ?>" <?php echo e(request('kategori') == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            
            
            <?php if(auth()->user()->isAdminUtama() || auth()->user()->isKepalaYayasan()): ?>
            <select name="unit_id" class="form-control" style="min-width:150px;width:auto;">
                <option value="">Semua Unit</option>
                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($unit->id); ?>" <?php echo e(request('unit_id') == $unit->id ? 'selected' : ''); ?>>
                    <?php echo e($unit->nama_unit); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php endif; ?>

            
            <select name="kondisi" class="form-control" style="min-width:140px;width:auto;">
                <option value="">Semua Kondisi</option>
                <option value="aktif"       <?php echo e(request('kondisi') == 'aktif'       ? 'selected' : ''); ?>>Aktif</option>
                <option value="rusak"       <?php echo e(request('kondisi') == 'rusak'       ? 'selected' : ''); ?>>Rusak</option>
                <option value="hilang"      <?php echo e(request('kondisi') == 'hilang'      ? 'selected' : ''); ?>>Hilang</option>
                <option value="habis_pakai" <?php echo e(request('kondisi') == 'habis_pakai' ? 'selected' : ''); ?>>Habis Pakai</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if(request()->hasAny(['search', 'kategori', 'unit_id', 'kondisi', 'sort', 'dir'])): ?>
                <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Reset
                </a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>


<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Nama Barang</th>
                        <th>Kode Aset</th>
                        <th>Unit / Lokasi</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th>Kondisi</th>
                        <th>Sumber Dana</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:var(--gray-400);"><?php echo e($assets->firstItem() + $i); ?></td>
                        <td>
                            <div style="font-weight:600;color:var(--gray-800);"><?php echo e($asset->nama_barang); ?></div>
                            <div style="font-size:12px;color:var(--gray-400);"><?php echo e($asset->kategori); ?></div>
                        </td>
                        <td>
                            
                            <code style="font-size:12px;background:var(--gray-100);padding:2px 7px;border-radius:5px;"><?php echo e($asset->kode_aset); ?></code>
                        </td>
                        <td>
                            <div><?php echo e($asset->lokasi_barang ?? '-'); ?></div>
                            
                            <div style="font-size:12px;color:var(--gray-400);"><?php echo e($asset->unit->nama_unit ?? '-'); ?></div>
                        </td>
                        <td style="text-align:center;font-weight:600;"><?php echo e($asset->jumlah_barang); ?></td>
                        <td>
                            
                            <span class="badge <?php echo e($asset->kondisi_badge); ?>">
                                <?php echo e($asset->kondisi_label); ?>

                            </span>
                        </td>
                        <td style="font-size:13px;">
                            
                            <?php echo e($asset->fundingSource->nama_sumber ?? '-'); ?>

                        </td>
                        <td style="font-size:13px;font-weight:600;white-space:nowrap;">
                            Rp <?php echo e(number_format($asset->harga_barang, 0, ',', '.')); ?>

                        </td>
                        <td>
                            
                            <?php if($asset->foto_utama): ?>
                            <img src="<?php echo e(Storage::url($asset->foto_utama->file_path)); ?>"
                                 alt="foto"
                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid var(--gray-200);">
                            <?php else: ?>
                            <div style="width:40px;height:40px;background:var(--gray-100);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--gray-300);font-size:16px;">
                                <i class="fas fa-image"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('assets.show', $asset)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                
                                <?php if(auth()->user()->canEditAset()): ?>
                                <a href="<?php echo e(route('assets.edit', $asset)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <?php endif; ?>
                                
                                
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <p>Tidak ada data aset</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($assets->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($assets->links('vendor.pagination.simple')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/assets/index.blade.php ENDPATH**/ ?>