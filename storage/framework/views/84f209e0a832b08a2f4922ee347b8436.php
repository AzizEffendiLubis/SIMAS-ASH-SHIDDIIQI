
<?php $__env->startSection('title', 'Detail Log Aktivitas'); ?>
<?php $__env->startSection('page-title', 'Log Aktivitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Log Aktivitas</h1>
        <p>ID Log: <strong>#<?php echo e($activityLog->id); ?></strong></p>
    </div>
    <a href="<?php echo e(route('activity-logs.index')); ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

    
    <div style="display:flex;flex-direction:column;gap:20px;">

        
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Informasi Aktivitas
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Waktu</p>
                        
                        <p style="font-weight:600;font-size:14px;">
                            <?php echo e($activityLog->created_at->format('d M Y')); ?>

                        </p>
                        <p style="font-size:12.5px;color:#64748b;">
                            <?php echo e($activityLog->created_at->format('H:i:s')); ?>

                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">IP Address</p>
                        <p style="font-weight:500;font-family:monospace;">
                            <?php echo e($activityLog->ip_address ?? '-'); ?>

                        </p>
                    </div>

                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Pengguna</p>
                        
                        <?php if($activityLog->user): ?>
                            <p style="font-weight:600;"><?php echo e($activityLog->user->name); ?></p>
                            <p style="font-size:12px;color:#64748b;">
                                <?php echo e($activityLog->user->username); ?>

                                
                                &middot; <?php echo e($activityLog->user->role_label); ?>

                            </p>
                        <?php else: ?>
                            <p style="color:#94a3b8;font-style:italic;">Sistem</p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Aksi</p>
                        <?php
                            // Warna badge berdasarkan prefix nama aksi.
                            // Aksi yang tercatat: tambah_aset, edit_kondisi_aset,
                            // tambah_laporan_kerusakan, update_progres_perbaikan,
                            // tambah_pengguna, edit_pengguna, tambah_unit, edit_unit,
                            // tambah_sumber_dana, edit_sumber_dana,
                            // tambah_jenis_gudang, edit_jenis_gudang
                            $actionColor = match(true) {
                                str_contains($activityLog->action, 'tambah') => ['bg' => '#dcfce7', 'text' => '#15803d'],
                                str_contains($activityLog->action, 'edit')   => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
                                str_contains($activityLog->action, 'hapus')  => ['bg' => '#fee2e2', 'text' => '#dc2626'],
                                str_contains($activityLog->action, 'login')  => ['bg' => '#f3e8ff', 'text' => '#7c3aed'],
                                str_contains($activityLog->action, 'update') => ['bg' => '#fef9c3', 'text' => '#a16207'],
                                default                                      => ['bg' => '#f1f5f9', 'text' => '#475569'],
                            };
                        ?>
                        <span style="display:inline-block;padding:4px 12px;border-radius:6px;font-size:13px;font-weight:600;
                            background:<?php echo e($actionColor['bg']); ?>;color:<?php echo e($actionColor['text']); ?>;">
                            <?php echo e(str_replace('_', ' ', $activityLog->action)); ?>

                        </span>
                    </div>

                </div>

                <?php if($activityLog->description): ?>
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9;">
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:6px;font-weight:600;">Deskripsi</p>
                    <p style="font-size:13.5px;color:#374151;background:#f8fafc;border-radius:8px;
                        padding:12px 14px;border:1px solid #e2e8f0;line-height:1.6;">
                        <?php echo e($activityLog->description); ?>

                    </p>
                </div>
                <?php endif; ?>

            </div>
        </div>

        
        <?php if($activityLog->old_data || $activityLog->new_data): ?>
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Perubahan Data
                </p>

                
                <?php
                    $oldData = $activityLog->old_data ?? [];
                    $newData = $activityLog->new_data ?? [];
                    $allKeys = collect(array_keys(array_merge($oldData, $newData)))
                        ->unique()
                        ->sort()
                        ->values();
                ?>

                <?php if($allKeys->isNotEmpty()): ?>
                <div class="table-wrap">
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
                                <tr style="<?php echo e($changed ? 'background:#fffbeb;' : ''); ?>">
                                    <td style="font-family:monospace;font-size:12.5px;font-weight:600;color:#374151;">
                                        <?php echo e($key); ?>

                                        <?php if($changed): ?>
                                            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;
                                                background:#f59e0b;margin-left:5px;vertical-align:middle;"></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:13px;">
                                        <?php if(is_null($old)): ?>
                                            <span style="color:#94a3b8;font-style:italic;">—</span>
                                        <?php elseif(is_bool($old)): ?>
                                            <span style="color:<?php echo e($old ? '#16a34a' : '#dc2626'); ?>;">
                                                <?php echo e($old ? 'true' : 'false'); ?>

                                            </span>
                                        <?php elseif(is_array($old)): ?>
                                            <code style="font-size:11.5px;background:#f1f5f9;padding:2px 6px;border-radius:4px;">
                                                <?php echo e(json_encode($old, JSON_UNESCAPED_UNICODE)); ?>

                                            </code>
                                        <?php else: ?>
                                            <?php echo e($old); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:13px;">
                                        <?php if(is_null($new)): ?>
                                            <span style="color:#94a3b8;font-style:italic;">—</span>
                                        <?php elseif(is_bool($new)): ?>
                                            <span style="color:<?php echo e($new ? '#16a34a' : '#dc2626'); ?>;">
                                                <?php echo e($new ? 'true' : 'false'); ?>

                                            </span>
                                        <?php elseif(is_array($new)): ?>
                                            <code style="font-size:11.5px;background:#f1f5f9;padding:2px 6px;border-radius:4px;">
                                                <?php echo e(json_encode($new, JSON_UNESCAPED_UNICODE)); ?>

                                            </code>
                                        <?php else: ?>
                                            <span style="<?php echo e($changed ? 'font-weight:600;color:#1d4ed8;' : ''); ?>">
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
                    <p style="font-size:13px;color:#94a3b8;">Tidak ada data perubahan yang tercatat.</p>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

    </div>

    
    <div style="display:flex;flex-direction:column;gap:20px;">

        
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Entitas Terkait
                </p>

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

                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;
                            display:flex;align-items:center;justify-content:center;
                            color:#2563eb;font-size:16px;">
                            <i class="fas fa-<?php echo e($subjectIcon); ?>"></i>
                        </div>
                        <div>
                            <p style="font-weight:600;font-size:14px;"><?php echo e($subjectLabel); ?></p>
                            <p style="font-size:12px;color:#94a3b8;">ID #<?php echo e($activityLog->subject_id); ?></p>
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
                    <p style="font-size:13px;color:#94a3b8;font-style:italic;">
                        Tidak ada entitas terkait pada log ini.
                    </p>
                <?php endif; ?>
            </div>
        </div>



        
        <?php if(isset($prevLog) || isset($nextLog)): ?>
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:12px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Navigasi
                </p>
                <div style="display:flex;flex-direction:column;gap:8px;">

                    <?php if(isset($nextLog)): ?>
                        <a href="<?php echo e(route('activity-logs.show', $nextLog)); ?>"
                            class="btn btn-outline" style="width:100%;justify-content:space-between;">
                            <span><i class="fas fa-arrow-left"></i> Log Lebih Baru</span>
                            <span style="font-size:11.5px;color:#94a3b8;">#<?php echo e($nextLog->id); ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if(isset($prevLog)): ?>
                        <a href="<?php echo e(route('activity-logs.show', $prevLog)); ?>"
                            class="btn btn-outline" style="width:100%;justify-content:space-between;">
                            <span>Log Lebih Lama <i class="fas fa-arrow-right"></i></span>
                            <span style="font-size:11.5px;color:#94a3b8;">#<?php echo e($prevLog->id); ?></span>
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/activity-logs/show.blade.php ENDPATH**/ ?>