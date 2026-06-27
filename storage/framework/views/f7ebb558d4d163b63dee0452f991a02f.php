
<?php $__env->startSection('title', 'Log Aktivitas'); ?>
<?php $__env->startSection('page-title', 'Log Aktivitas'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Log Aktivitas Sistem</h1>
        <p>Riwayat seluruh aktivitas yang tercatat dalam sistem</p>
    </div>
    
</div>


<div class="card mb-16">
    <div class="card-body filter-card-body">
        <form method="GET" action="<?php echo e(route('activity-logs.index')); ?>" class="filter-row">

            
            <div class="search-wrap">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari deskripsi aktivitas..."
                    value="<?php echo e(request('search')); ?>">
            </div>

            
            <select name="subject_type" class="form-control">
                <option value="">Semua Entitas</option>
                <option value="Asset"         <?php echo e(request('subject_type') === 'Asset'         ? 'selected' : ''); ?>>Aset</option>
                <option value="Repair"        <?php echo e(request('subject_type') === 'Repair'        ? 'selected' : ''); ?>>Perbaikan</option>
                <option value="User"          <?php echo e(request('subject_type') === 'User'          ? 'selected' : ''); ?>>Pengguna</option>
                <option value="Unit"          <?php echo e(request('subject_type') === 'Unit'          ? 'selected' : ''); ?>>Unit</option>
                <option value="FundingSource" <?php echo e(request('subject_type') === 'FundingSource' ? 'selected' : ''); ?>>Sumber Dana</option>
            </select>

            
            <div class="filter-date-group">
                <label>Dari</label>
                <input type="date" name="dari_tanggal" class="form-control"
                    value="<?php echo e(request('dari_tanggal')); ?>">
            </div>

            
            <div class="filter-date-group">
                <label>Sampai</label>
                <input type="date" name="sampai_tanggal" class="form-control"
                    value="<?php echo e(request('sampai_tanggal')); ?>">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if(request()->hasAny(['search', 'subject_type', 'dari_tanggal', 'sampai_tanggal'])): ?>
                <a href="<?php echo e(route('activity-logs.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Reset
                </a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h2>Riwayat Aktivitas</h2>
        <span class="text-muted" style="font-size:12px;"><?php echo e($logs->total()); ?> log ditemukan</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:155px;">Waktu</th>
                        <th style="width:140px;">Pengguna</th>
                        <th style="width:170px;">Aksi</th>
                        <th style="width:130px;">Entitas</th>
                        <th>Deskripsi</th>
                        <th style="width:120px;">IP Address</th>
                        <th style="width:56px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="white-space:nowrap;">
                            
                            <p class="log-time-date"><?php echo e($log->created_at->format('d M Y')); ?></p>
                            <p class="log-time-clock"><?php echo e($log->created_at->format('H:i:s')); ?></p>
                        </td>

                        <td>
                            <?php if($log->user): ?>
                                
                                <p class="log-user-name"><?php echo e($log->user->name); ?></p>
                                <p class="log-user-username"><?php echo e($log->user->username); ?></p>
                            <?php else: ?>
                                
                                <span class="log-user-system">Sistem</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            
                            <span class="action-badge action-<?php echo e(\Illuminate\Support\Str::contains($log->action, 'tambah') ? 'tambah'
                                : (\Illuminate\Support\Str::contains($log->action, 'edit') ? 'edit'
                                : (\Illuminate\Support\Str::contains($log->action, 'hapus') ? 'hapus'
                                : (\Illuminate\Support\Str::contains($log->action, 'login') ? 'login'
                                : (\Illuminate\Support\Str::contains($log->action, 'update') ? 'update' : 'default'))))); ?>">
                                <?php echo e(str_replace('_', ' ', $log->action)); ?>

                            </span>
                        </td>

                        <td>
                            
                            <?php if($log->subject_type): ?>
                            <span class="subject-tag">
                                <?php echo e(class_basename($log->subject_type)); ?>

                                <?php if($log->subject_id): ?>
                                <span class="text-muted">#<?php echo e($log->subject_id); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php else: ?>
                            <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>

                        <td class="log-description">
                            <span class="log-description-clamp">
                                <?php echo e($log->description ?? '-'); ?>

                            </span>
                        </td>

                        <td class="log-ip">
                            <?php echo e($log->ip_address ?? '-'); ?>

                        </td>

                        <td>
                            
                            <a href="<?php echo e(route('activity-logs.show', $log)); ?>"
                               class="btn btn-outline btn-sm btn-icon" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Tidak ada log aktivitas yang ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($logs->hasPages()): ?>
        <div class="card-footer">
            <div class="pagination">
                
                <?php echo e($logs->appends(request()->query())->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .filter-card-body { padding: 14px 18px; }

    .filter-date-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .filter-date-group label {
        font-size: 11.5px;
        color: var(--gray-400);
        font-weight: 600;
    }
    .filter-date-group .form-control { width: 145px; height: 38px; }

    .filter-actions { display: flex; gap: 8px; align-self: flex-end; }

    /* ── Kolom Waktu ── */
    .log-time-date  { font-size: 13px; font-weight: 500; color: var(--gray-700); }
    .log-time-clock { font-size: 11.5px; color: var(--gray-400); }

    /* ── Kolom Pengguna ── */
    .log-user-name     { font-weight: 600; font-size: 13px; color: var(--gray-700); }
    .log-user-username { font-size: 11.5px; color: var(--gray-400); }
    .log-user-system    { font-size: 12px; color: var(--gray-300); font-style: italic; }

    /* ── Badge Aksi ── */
    .action-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .action-badge.action-tambah  { background: #dcfce7; color: #15803d; }
    .action-badge.action-edit    { background: #dbeafe; color: #1d4ed8; }
    .action-badge.action-hapus   { background: #fee2e2; color: #dc2626; }
    .action-badge.action-login   { background: #f3e8ff; color: #7c3aed; }
    .action-badge.action-update  { background: #fef9c3; color: #a16207; }
    .action-badge.action-default { background: var(--gray-100); color: var(--gray-500); }

    /* ── Tag Entitas ── */
    .subject-tag {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 5px;
        padding: 2px 8px;
        font-size: 11.5px;
        color: var(--gray-600);
    }

    /* ── Deskripsi (clamp 2 baris) ── */
    .log-description { font-size: 13px; color: var(--gray-600); max-width: 280px; }
    .log-description-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ── IP Address ── */
    .log-ip { font-size: 12px; color: var(--gray-400); font-family: monospace; }

    @media (max-width: 768px) {
        .filter-date-group .form-control { width: 100%; }
        .filter-actions { align-self: stretch; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/activity-logs/index.blade.php ENDPATH**/ ?>