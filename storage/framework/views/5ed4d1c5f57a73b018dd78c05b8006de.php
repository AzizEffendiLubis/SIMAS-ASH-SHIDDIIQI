<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang di Sistem Informasi Manajemen Aset Sekolah, <?php echo e(auth()->user()->name); ?>.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-boxes-stacked"></i></div>
        <div>
            <div class="stat-value"><?php echo e(number_format($totalAset)); ?></div>
            <div class="stat-label">Total Aset</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-value"><?php echo e(number_format($asetAktif)); ?></div>
            <div class="stat-label">Aset Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-screwdriver-wrench"></i></div>
        <div>
            <div class="stat-value"><?php echo e(number_format($perbaikan)); ?></div>
            <div class="stat-label">Perlu Perbaikan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-laptop"></i></div>
        <div>
            <div class="stat-value"><?php echo e(number_format($komputer)); ?></div>
            <div class="stat-label">Komputer</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-chair"></i></div>
        <div>
            <div class="stat-value"><?php echo e(number_format($mejaKursi)); ?></div>
            <div class="stat-label">Meja &amp; Kursi</div>
        </div>
    </div>
</div>

<!-- Activity Section -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <!-- Recent Repairs -->
    <div class="card">
        <div class="card-header" style="padding:20px 20px 14px;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:700;">Aktivitas Perbaikan</h2>
            <?php if(auth()->user()->canAccess('perbaikan_aset')): ?>
            <a href="<?php echo e(route('repairs.index')); ?>" style="font-size:12px;color:#0C6638;text-decoration:none;font-weight:600;">Lihat Semua →</a>
            <?php endif; ?>
        </div>
        <div class="card-body" style="padding:0 20px 16px;">
            <?php $__empty_1 = true; $__currentLoopData = $recentRepairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="activity-item">
                <div class="activity-icon <?php echo e($repair->status === 'Selesai' ? 'repair-done' : 'repair'); ?>">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="activity-meta">
                    <div class="title"><?php echo e($repair->asset->nama_barang ?? '-'); ?></div>
                    <div class="sub"><?php echo e($repair->tanggal_laporan->format('d/m/Y')); ?> • <?php echo e($repair->asset->lokasi_barang ?? '-'); ?></div>
                </div>
                <span class="badge badge-<?php echo e($repair->status === 'Selesai' ? 'success' : ($repair->status === 'Sedang Diperbaiki' ? 'info' : 'warning')); ?>" style="font-size:11px;">
                    <?php echo e($repair->status); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="padding:20px 0;text-align:center;color:#94a3b8;font-size:13px;">
                <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                Belum ada data perbaikan
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Procurements -->
    <div class="card">
        <div class="card-header" style="padding:20px 20px 14px;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:700;">Pengadaan Terbaru</h2>
            <?php if(auth()->user()->canAccess('pengadaan_aset')): ?>
            <a href="<?php echo e(route('procurements.index')); ?>" style="font-size:12px;color:#0C6638;text-decoration:none;font-weight:600;">Lihat Semua →</a>
            <?php endif; ?>
        </div>
        <div class="card-body" style="padding:0 20px 16px;">
            <?php $__empty_1 = true; $__currentLoopData = $recentProcurements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="activity-item">
                <div class="activity-icon procurement">
                    <i class="fas fa-cart-plus"></i>
                </div>
                <div class="activity-meta">
                    <div class="title"><?php echo e($proc->nama_barang); ?></div>
                    <div class="sub"><?php echo e($proc->tanggal_pengajuan->format('d/m/Y')); ?> • <?php echo e($proc->unit_kerja); ?></div>
                </div>
                <span class="badge badge-<?php echo e($proc->status === 'Disetujui' ? 'success' : ($proc->status === 'Ditolak' ? 'danger' : 'warning')); ?>" style="font-size:11px;">
                    <?php echo e($proc->status); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="padding:20px 0;text-align:center;color:#94a3b8;font-size:13px;">
                <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                Belum ada pengadaan
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/dashboard/index.blade.php ENDPATH**/ ?>