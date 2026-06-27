
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong style="color:var(--gray-700);"><?php echo e(auth()->user()->name); ?></strong>
        &mdash; <?php echo e(auth()->user()->role_label); ?></p>
</div>


<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?php echo e(number_format($totalAset)); ?></div>
            <div class="stat-label">Total Aset</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?php echo e(number_format($asetAktif)); ?></div>
            <div class="stat-label">Aset Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        
        <div class="stat-icon orange">
            <i class="fas fa-screwdriver-wrench"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?php echo e(number_format($perbaikanAktif)); ?></div>
            <div class="stat-label">Perlu Perbaikan</div>
        </div>
    </div>
    <div class="stat-card">
        
        <div class="stat-icon purple">
            <i class="fas fa-laptop"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?php echo e(number_format($totalKomputer)); ?></div>
            <div class="stat-label">Komputer</div>
        </div>
    </div>
    <div class="stat-card">
        
        <div class="stat-icon teal">
            <i class="fas fa-chair"></i>
        </div>
        <div class="stat-body">
            <div class="stat-value"><?php echo e(number_format($totalFurnitur)); ?></div>
            <div class="stat-label">Furnitur</div>
        </div>
    </div>
</div>



<div class="dash-two-col" style="align-items:stretch;">

    
    <div class="card" style="display:flex;flex-direction:column;height:100%;">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-screwdriver-wrench" style="color:var(--warning);font-size:14px;"></i>
                <h2>Aktivitas Perbaikan</h2>
            </div>
            <?php if(auth()->user()->canAccess('perbaikan_aset')): ?>
            <a href="<?php echo e(route('repairs.index')); ?>"
               style="font-size:12px;color:var(--primary);font-weight:600;
                      display:flex;align-items:center;gap:4px;">
                Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px;"></i>
            </a>
            <?php endif; ?>
        </div>
        <div class="card-body" style="padding:4px 20px 16px;flex:1;display:flex;flex-direction:column;">
            
            <?php $__empty_1 = true; $__currentLoopData = $recentRepairs->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="activity-item">
                <div class="activity-icon <?php echo e($repair->status === 'selesai' ? 'repair-done' : 'repair'); ?>">
                    <i class="fas fa-<?php echo e($repair->status === 'selesai' ? 'circle-check' : 'screwdriver-wrench'); ?>"></i>
                </div>
                <div class="activity-body">
                    
                    <div class="title"><?php echo e($repair->nama_aset_laporan); ?></div>
                    <div class="meta">
                        <i class="fas fa-calendar" style="font-size:10px;opacity:.5;"></i>
                        <?php echo e($repair->tanggal_laporan->format('d M Y')); ?>

                        <?php if($repair->lokasi_kerusakan): ?>
                            &nbsp;·&nbsp;
                            <i class="fas fa-location-dot" style="font-size:10px;opacity:.5;"></i>
                            <?php echo e($repair->lokasi_kerusakan); ?>

                        <?php endif; ?>
                    </div>
                </div>
                
                <span class="badge <?php echo e($repair->status_badge); ?>" style="font-size:11px;flex-shrink:0;">
                    <?php echo e($repair->status_label); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:28px 0;margin:auto;">
                <i class="fas fa-inbox"></i>
                <p>Belum ada laporan perbaikan</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card" style="display:flex;flex-direction:column;height:100%;">
        <div class="card-header">
            <?php if($recentLogs !== null): ?>
                
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-clock-rotate-left" style="color:var(--primary);font-size:14px;"></i>
                    <h2>Log Aktivitas</h2>
                </div>
                <?php if(auth()->user()->canAccess('log_aktivitas')): ?>
                <a href="<?php echo e(route('activity-logs.index')); ?>"
                   style="font-size:12px;color:var(--primary);font-weight:600;
                          display:flex;align-items:center;gap:4px;">
                    Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                </a>
                <?php endif; ?>
            <?php else: ?>
                <div style="display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-chart-bar" style="color:var(--primary);font-size:14px;"></i>
                    <h2>Kondisi Aset</h2>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body" style="padding:4px 20px 16px;flex:1;display:flex;flex-direction:column;">

            <?php if($recentLogs !== null): ?>
                
                <?php $__empty_1 = true; $__currentLoopData = $recentLogs->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="activity-item">
                    <div class="activity-icon asset">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">
                            <?php echo e(Str::limit($log->description ?? str_replace('_', ' ', $log->action), 52)); ?>

                        </div>
                        <div class="meta">
                            <i class="fas fa-calendar" style="font-size:10px;opacity:.5;"></i>
                            <?php echo e($log->created_at->format('d M Y, H:i')); ?>

                            <?php if($log->user): ?>
                                &nbsp;·&nbsp;
                                <i class="fas fa-user" style="font-size:10px;opacity:.5;"></i>
                                <?php echo e($log->user->name); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="activity-time"
                          title="<?php echo e($log->created_at->format('d M Y H:i:s')); ?>">
                        <?php echo e($log->created_at->diffForHumans(null, true)); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state" style="padding:28px 0;margin:auto;">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada aktivitas tercatat</p>
                </div>
                <?php endif; ?>

            <?php else: ?>
                
                <div class="activity-item">
                    <div class="activity-icon asset">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Aset Aktif</div>
                        <div class="meta">Kondisi baik, sedang digunakan</div>
                    </div>
                    <span class="badge badge-success" style="font-size:11px;flex-shrink:0;">
                        <?php echo e(number_format($asetAktif)); ?>

                    </span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon repair">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Aset Rusak</div>
                        <div class="meta">Memerlukan perhatian atau perbaikan</div>
                    </div>
                    <span class="badge badge-danger" style="font-size:11px;flex-shrink:0;">
                        <?php echo e(number_format($asetRusak)); ?>

                    </span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background:var(--warning-light);color:var(--warning);">
                        <i class="fas fa-circle-question"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Aset Hilang</div>
                        <div class="meta">Tidak ditemukan keberadaannya</div>
                    </div>
                    <span class="badge badge-warning" style="font-size:11px;flex-shrink:0;">
                        <?php echo e(number_format($asetHilang)); ?>

                    </span>
                </div>
                <div class="activity-item">
                    <div class="activity-icon system">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="activity-body">
                        <div class="title">Habis Pakai</div>
                        <div class="meta">Sudah tidak dapat digunakan</div>
                    </div>
                    <span class="badge badge-secondary" style="font-size:11px;flex-shrink:0;">
                        <?php echo e(number_format($asetHabisPakai ?? 0)); ?>

                    </span>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/dashboard/index.blade.php ENDPATH**/ ?>