
<?php $__env->startSection('title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akun pengguna dan atur hak akses sistem</p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
        </a>
    </div>
</div>


<div class="card mb-16">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" action="<?php echo e(route('users.index')); ?>" class="filter-row">

            <div class="search-wrap" style="flex:1;min-width:200px;">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari username, nama, atau email..."
                    value="<?php echo e(request('search')); ?>">
            </div>

            
            <select name="role" class="form-control" style="min-width:160px;width:auto;">
                <option value="">Semua Role</option>
                <option value="admin_utama"    <?php echo e(request('role') === 'admin_utama'    ? 'selected' : ''); ?>>Admin Utama</option>
                <option value="kepala_yayasan" <?php echo e(request('role') === 'kepala_yayasan' ? 'selected' : ''); ?>>Kepala Yayasan</option>
                <option value="admin_unit"     <?php echo e(request('role') === 'admin_unit'     ? 'selected' : ''); ?>>Admin Unit</option>
                <option value="teknisi"        <?php echo e(request('role') === 'teknisi'        ? 'selected' : ''); ?>>Teknisi</option>
                <option value="user"           <?php echo e(request('role') === 'user'           ? 'selected' : ''); ?>>User</option>
            </select>

            
            <select name="status" class="form-control" style="min-width:140px;width:auto;">
                <option value="">Semua Status</option>
                <option value="aktif"    <?php echo e(request('status') === 'aktif'    ? 'selected' : ''); ?>>Aktif</option>
                <option value="nonaktif" <?php echo e(request('status') === 'nonaktif' ? 'selected' : ''); ?>>Non-Aktif</option>
            </select>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if(request()->hasAny(['search', 'role', 'status'])): ?>
                <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Reset
                </a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h2>Daftar Pengguna</h2>
        <span style="font-size:12px;color:var(--gray-400);">
            <?php echo e($users->total()); ?> pengguna terdaftar
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px;">
                                
                                <?php
                                    $avatarBg = match($u->role) {
                                        'admin_utama'    => '#7c3aed',
                                        'kepala_yayasan' => '#c2410c',
                                        'admin_unit'     => '#2563eb',
                                        'teknisi'        => '#15803d',
                                        default          => '#475569',
                                    };
                                ?>
                                <div class="avatar avatar-sm"
                                     style="background:<?php echo e($avatarBg); ?>;">
                                    <?php echo e(strtoupper(substr($u->name, 0, 1))); ?>

                                </div>
                                <span style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                    <?php echo e($u->username); ?>

                                </span>
                            </div>
                        </td>
                        <td style="font-size:13.5px;color:var(--gray-700);">
                            <?php echo e($u->name); ?>

                        </td>
                        <td style="font-size:13px;color:var(--gray-500);">
                            <?php echo e($u->email ?? '—'); ?>

                        </td>
                        <td>
                            
                            <span class="badge <?php echo e($u->role_badge); ?>"><?php echo e($u->role_label); ?></span>
                        </td>
                        <td style="font-size:13px;color:var(--gray-600);">
                            
                            <?php echo e($u->unit->nama_unit ?? '—'); ?>

                        </td>
                        <td>
                            
                            <span class="badge badge-<?php echo e($u->status); ?>"><?php echo e($u->status_label); ?></span>
                        </td>
                        <td style="font-size:12.5px;color:var(--gray-400);white-space:nowrap;">
                            <?php echo e($u->created_at->format('d M Y')); ?>

                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="<?php echo e(route('users.show', $u)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('users.edit', $u)); ?>"
                                   class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>
                                    <?php if(request()->hasAny(['search', 'role', 'status'])): ?>
                                        Tidak ada pengguna yang sesuai filter
                                    <?php else: ?>
                                        Belum ada pengguna terdaftar
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($users->hasPages()): ?>
        <div class="card-footer">
            <div class="pagination">
                <?php echo e($users->appends(request()->query())->links()); ?>

            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/users/index.blade.php ENDPATH**/ ?>