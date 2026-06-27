
<?php $__env->startSection('title', 'Detail Log Aktivitas'); ?>
<?php $__env->startSection('page-title', 'Log Aktivitas'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Log Aktivitas</h1>
        <p>ID Log: <strong style="color:var(--gray-700);">#<?php echo e($activityLog->id); ?></strong></p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('activity-logs.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="dash-two-col">

    
    
    <div style="display:flex;flex-direction:column;gap:16px;min-width:0;">

        
        <div class="card">
            <div class="card-header">
                <h2>Informasi Aktivitas</h2>
            </div>
            <div class="card-body">

                <div class="form-grid" style="gap:14px;">

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Waktu</p>
                        
                        <p style="font-weight:700;font-size:15px;color:var(--gray-800);">
                            <?php echo e($activityLog->created_at->format('d M Y')); ?>

                        </p>
                        <p style="font-size:12.5px;color:var(--gray-400);margin-top:1px;">
                            <?php echo e($activityLog->created_at->format('H:i:s')); ?>

                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">IP Address</p>
                        <p style="font-weight:500;font-family:monospace;color:var(--gray-700);">
                            <?php echo e($activityLog->ip_address ?? '-'); ?>

                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Pengguna</p>
                        
                        <?php if($activityLog->user): ?>
                            <p style="font-weight:600;color:var(--gray-700);"><?php echo e($activityLog->user->name); ?></p>
                            <p style="font-size:12px;color:var(--gray-400);margin-top:1px;">
                                <?php echo e($activityLog->user->username); ?>

                                
                                &middot; <?php echo e($activityLog->user->role_label); ?>

                            </p>
                        <?php else: ?>
                            <p style="font-size:13px;color:var(--gray-300);font-style:italic;">Sistem</p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Aksi</p>
                        <?php
                            // Badge warna berdasarkan prefix nama aksi, mengikuti palet .badge-* di layout.
                            // Aksi yang tercatat: tambah_aset, edit_kondisi_aset,
                            // tambah_laporan_kerusakan, update_progres_perbaikan,
                            // tambah_pengguna, edit_pengguna, tambah_unit, edit_unit,
                            // tambah_sumber_dana, edit_sumber_dana,
                            // tambah_jenis_gudang, edit_jenis_gudang
                            $actionBadge = match(true) {
                                str_contains($activityLog->action, 'tambah') => 'badge-success',
                                str_contains($activityLog->action, 'edit')   => 'badge-unit',
                                str_contains($activityLog->action, 'hapus')  => 'badge-danger',
                                str_contains($activityLog->action, 'login')  => 'badge-admin',
                                str_contains($activityLog->action, 'update') => 'badge-warning',
                                default                                      => 'badge-secondary',
                            };
                        ?>
                        <span class="badge <?php echo e($actionBadge); ?>" style="font-size:13px;padding:4px 12px;">
                            <?php echo e(str_replace('_', ' ', $activityLog->action)); ?>

                        </span>
                    </div>

                </div>

                <?php if($activityLog->description): ?>
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--gray-100);">
                    <p style="font-size:12px;color:var(--gray-400);font-weight:600;margin-bottom:6px;">Deskripsi</p>
                    <p class="note-box note-box-info">
                        <?php echo e($activityLog->description); ?>

                    </p>
                </div>
                <?php endif; ?>

            </div>
        </div>

        
        <?php if($activityLog->old_data || $activityLog->new_data): ?>
        <div class="card">
            <div class="card-header">
                <h2>Perubahan Data</h2>
            </div>
            <div class="card-body">

                
                <?php
                    $oldData = $activityLog->old_data ?? [];
                    $newData = $activityLog->new_data ?? [];
                    $allKeys = collect(array_keys(array_merge($oldData, $newData)))
                        ->unique()
                        ->sort()
                        ->values();
                ?>

                <?php if($allKeys->isNotEmpty()): ?>
                <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:var(--radius);max-width:100%;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:160px;">Field</th>
                                <th>Nilai Lama</th>
                                <th>Nilai Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Gunakan array_key_exists agar nilai null pun terdeteksi dengan benar
                                    $old     = array_key_exists($key, $oldData) ? $oldData[$key] : null;
                                    $new     = array_key_exists($key, $newData) ? $newData[$key] : null;
                                    $changed = $old !== $new;
                                ?>
                                <tr style="<?php echo e($changed ? 'background:var(--warning-light);' : ''); ?>">
                                    <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:var(--gray-700);">
                                        <?php echo e($key); ?>

                                        <?php if($changed): ?>
                                            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;
                                                background:var(--warning);margin-left:5px;vertical-align:middle;"></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:13px;">
                                        <?php if(is_null($old)): ?>
                                            <span style="color:var(--gray-300);font-style:italic;">—</span>
                                        <?php elseif(is_bool($old)): ?>
                                            <span style="color:<?php echo e($old ? '#16a34a' : 'var(--danger)'); ?>;font-weight:600;">
                                                <?php echo e($old ? 'true' : 'false'); ?>

                                            </span>
                                        <?php elseif(is_array($old)): ?>
                                            <code style="font-size:11.5px;background:var(--gray-100);padding:2px 6px;border-radius:4px;">
                                                <?php echo e(json_encode($old, JSON_UNESCAPED_UNICODE)); ?>

                                            </code>
                                        <?php else: ?>
                                            <?php echo e($old); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:13px;">
                                        <?php if(is_null($new)): ?>
                                            <span style="color:var(--gray-300);font-style:italic;">—</span>
                                        <?php elseif(is_bool($new)): ?>
                                            <span style="color:<?php echo e($new ? '#16a34a' : 'var(--danger)'); ?>;font-weight:600;">
                                                <?php echo e($new ? 'true' : 'false'); ?>

                                            </span>
                                        <?php elseif(is_array($new)): ?>
                                            <code style="font-size:11.5px;background:var(--gray-100);padding:2px 6px;border-radius:4px;">
                                                <?php echo e(json_encode($new, JSON_UNESCAPED_UNICODE)); ?>

                                            </code>
                                        <?php else: ?>
                                            <span style="<?php echo e($changed ? 'font-weight:600;color:var(--primary);' : ''); ?>">
                                                <?php echo e($new); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="empty-state" style="padding:28px 16px;">
                        <i class="fas fa-file-circle-question"></i>
                        <p>Tidak ada data perubahan yang tercatat</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

    </div>

    
    
    <div style="display:flex;flex-direction:column;gap:16px;min-width:0;">

        
        <div class="card">
            <div class="card-header">
                <h2>Entitas Terkait</h2>
            </div>
            <div class="card-body">

                <?php if($activityLog->subject_type && $activityLog->subject_id): ?>
                    <?php
                        // subject_type disimpan sebagai FQCN di DB (misal "App\Models\Asset").
                        // class_basename() mengambil nama class saja → "Asset".
                        $subjectClass = class_basename($activityLog->subject_type);

                        // Resolusi route detail per jenis entitas.
                        // Unit, FundingSource, WarehouseType tidak punya route show tersendiri
                        // (dikelola di master-data.index), maka default => null.
                        $subjectRoute = match($subjectClass) {
                            'Asset'  => route('assets.show',  $activityLog->subject_id),
                            'Repair' => route('repairs.show', $activityLog->subject_id),
                            'User'   => route('users.show',   $activityLog->subject_id),
                            default  => null,
                        };

                        // Ikon Font Awesome per jenis entitas
                        $subjectIcon = match($subjectClass) {
                            'Asset'         => 'box',
                            'Repair'        => 'screwdriver-wrench',
                            'User'          => 'user',
                            'Unit'          => 'building',
                            'FundingSource' => 'money-bill',
                            'WarehouseType' => 'warehouse',
                            default         => 'database',
                        };

                        // Label bahasa Indonesia per jenis entitas
                        $subjectLabel = match($subjectClass) {
                            'Asset'         => 'Aset',
                            'Repair'        => 'Laporan Perbaikan',
                            'User'          => 'Pengguna',
                            'Unit'          => 'Unit',
                            'FundingSource' => 'Sumber Dana',
                            'WarehouseType' => 'Jenis Gudang',
                            default         => $subjectClass,
                        };
                    ?>

                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div class="stat-icon blue" style="width:40px;height:40px;font-size:16px;border-radius:10px;">
                            <i class="fas fa-<?php echo e($subjectIcon); ?>"></i>
                        </div>
                        <div style="min-width:0;">
                            <p style="font-weight:600;font-size:14px;color:var(--gray-700);"><?php echo e($subjectLabel); ?></p>
                            <p style="font-size:12px;color:var(--gray-400);">ID #<?php echo e($activityLog->subject_id); ?></p>
                        </div>
                    </div>

                    <?php if($subjectRoute): ?>
                        <a href="<?php echo e($subjectRoute); ?>" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-arrow-up-right-from-square"></i> Lihat <?php echo e($subjectLabel); ?>

                        </a>
                    <?php else: ?>
                        
                        <a href="<?php echo e(route('master-data.index')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;">
                            <i class="fas fa-arrow-up-right-from-square"></i> Lihat Master Data
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-state" style="padding:28px 16px;">
                        <i class="fas fa-link-slash"></i>
                        <p>Tidak ada entitas terkait pada log ini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if(isset($prevLog) || isset($nextLog)): ?>
        <div class="card">
            <div class="card-header">
                <h2>Navigasi</h2>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">

                <?php if(isset($nextLog)): ?>
                    <a href="<?php echo e(route('activity-logs.show', $nextLog)); ?>"
                        class="btn btn-outline" style="width:100%;justify-content:space-between;">
                        <span><i class="fas fa-arrow-left"></i> Log Lebih Baru</span>
                        <span style="font-size:11.5px;color:var(--gray-400);">#<?php echo e($nextLog->id); ?></span>
                    </a>
                <?php endif; ?>

                <?php if(isset($prevLog)): ?>
                    <a href="<?php echo e(route('activity-logs.show', $prevLog)); ?>"
                        class="btn btn-outline" style="width:100%;justify-content:space-between;">
                        <span>Log Lebih Lama <i class="fas fa-arrow-right"></i></span>
                        <span style="font-size:11.5px;color:var(--gray-400);">#<?php echo e($prevLog->id); ?></span>
                    </a>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── Kotak deskripsi log, mengikuti pola note-box di repair/show ── */
    .note-box {
        font-size: 13.5px;
        color: var(--gray-700);
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        line-height: 1.7;
        word-break: break-word;
    }
    .note-box-info { background: var(--info-light); border: 1px solid #a5f3fc; }

    /* Cegah tabel diff (min-width 580px dari layout) mendorong lebar seluruh
       halaman di mobile — scroll dibatasi hanya di dalam card ini. */
    @media (max-width: 768px) {
        table { font-size: 12.5px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/activity-logs/show.blade.php ENDPATH**/ ?>