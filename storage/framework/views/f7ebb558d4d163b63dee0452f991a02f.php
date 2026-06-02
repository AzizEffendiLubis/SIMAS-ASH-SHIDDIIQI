
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
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="<?php echo e(route('activity-logs.index')); ?>" class="filter-row">

            
            <div class="search-wrap" style="flex:1;min-width:200px;">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari deskripsi aktivitas..."
                    value="<?php echo e(request('search')); ?>">
            </div>

            
            <select name="subject_type" class="form-control" style="min-width:155px;width:auto;">
                <option value="">Semua Entitas</option>
                <option value="Asset"         <?php echo e(request('subject_type') === 'Asset'         ? 'selected' : ''); ?>>Aset</option>
                <option value="Repair"        <?php echo e(request('subject_type') === 'Repair'        ? 'selected' : ''); ?>>Perbaikan</option>
                <option value="User"          <?php echo e(request('subject_type') === 'User'          ? 'selected' : ''); ?>>Pengguna</option>
                <option value="Unit"          <?php echo e(request('subject_type') === 'Unit'          ? 'selected' : ''); ?>>Unit</option>
                <option value="FundingSource" <?php echo e(request('subject_type') === 'FundingSource' ? 'selected' : ''); ?>>Sumber Dana</option>
            </select>

            
            <div style="display:flex;flex-direction:column;gap:4px;">
                <label style="font-size:11.5px;color:var(--gray-400);font-weight:600;">Dari</label>
                <input type="date" name="dari_tanggal" class="form-control" style="width:145px;"
                    value="<?php echo e(request('dari_tanggal')); ?>">
            </div>

            
            <div style="display:flex;flex-direction:column;gap:4px;">
                <label style="font-size:11.5px;color:var(--gray-400);font-weight:600;">Sampai</label>
                <input type="date" name="sampai_tanggal" class="form-control" style="width:145px;"
                    value="<?php echo e(request('sampai_tanggal')); ?>">
            </div>

            <div style="display:flex;gap:8px;align-self:flex-end;">
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
        <span style="font-size:12px;color:var(--gray-400);"><?php echo e($logs->total()); ?> log ditemukan</span>
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
                            
                            <p style="font-size:13px;font-weight:500;color:var(--gray-700);">
                                <?php echo e($log->created_at->format('d M Y')); ?>

                            </p>
                            <p style="font-size:11.5px;color:var(--gray-400);">
                                <?php echo e($log->created_at->format('H:i:s')); ?>

                            </p>
                        </td>

                        <td>
                            <?php if($log->user): ?>
                                
                                <p style="font-weight:600;font-size:13px;color:var(--gray-700);"><?php echo e($log->user->name); ?></p>
                                <p style="font-size:11.5px;color:var(--gray-400);"><?php echo e($log->user->username); ?></p>
                            <?php else: ?>
                                
                                <span style="font-size:12px;color:var(--gray-300);font-style:italic;">Sistem</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            
                            <?php
                                $ac = match(true) {
                                    str_contains($log->action, 'tambah') => ['bg'=>'#dcfce7','text'=>'#15803d'],
                                    str_contains($log->action, 'edit')   => ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
                                    str_contains($log->action, 'hapus')  => ['bg'=>'#fee2e2','text'=>'#dc2626'],
                                    str_contains($log->action, 'login')  => ['bg'=>'#f3e8ff','text'=>'#7c3aed'],
                                    str_contains($log->action, 'update') => ['bg'=>'#fef9c3','text'=>'#a16207'],
                                    default                              => ['bg'=>'var(--gray-100)','text'=>'var(--gray-500)'],
                                };
                            ?>
                            <span style="display:inline-block;padding:3px 10px;border-radius:6px;
                                font-size:12px;font-weight:600;
                                background:<?php echo e($ac['bg']); ?>;color:<?php echo e($ac['text']); ?>;">
                                <?php echo e(str_replace('_', ' ', $log->action)); ?>

                            </span>
                        </td>

                        <td>
                            
                            <?php if($log->subject_type): ?>
                            <span style="background:var(--gray-50);border:1px solid var(--gray-200);
                                border-radius:5px;padding:2px 8px;font-size:11.5px;color:var(--gray-600);">
                                <?php echo e(class_basename($log->subject_type)); ?>

                                <?php if($log->subject_id): ?>
                                <span style="color:var(--gray-400);">#<?php echo e($log->subject_id); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php else: ?>
                            <span style="color:var(--gray-300);">—</span>
                            <?php endif; ?>
                        </td>

                        <td style="font-size:13px;color:var(--gray-600);max-width:280px;">
                            <span style="display:-webkit-box;-webkit-line-clamp:2;
                                -webkit-box-orient:vertical;overflow:hidden;">
                                <?php echo e($log->description ?? '-'); ?>

                            </span>
                        </td>

                        <td style="font-size:12px;color:var(--gray-400);font-family:monospace;">
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
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Tidak ada log aktivitas yang ditemukan</p>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/activity-logs/index.blade.php ENDPATH**/ ?>