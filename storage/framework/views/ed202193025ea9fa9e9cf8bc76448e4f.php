
<?php $__env->startSection('title', 'Detail Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('page-parent', 'Detail Pengguna'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Detail Pengguna</h1>
        <p>Username: <strong style="color:var(--gray-700);"><?php echo e($user->username); ?></strong></p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>


<div class="dash-two-col">

    
    <div style="display:flex;flex-direction:column;gap:16px;">

        
        <div class="card">
            <div class="card-header">
                <h2>Informasi Pengguna</h2>
                
                <span class="badge badge-<?php echo e($user->status); ?>"><?php echo e($user->status_label); ?></span>
            </div>
            <div class="card-body">
                <?php
                    $avatarColor = match($user->role) {
                        'admin_utama'    => '#7c3aed',
                        'kepala_yayasan' => '#c2410c',
                        'admin_unit'     => '#2563eb',
                        'teknisi'        => '#15803d',
                        default          => '#475569',
                    };
                ?>

                <div class="user-identity-row">
                    <div class="role-avatar" style="background:<?php echo e($avatarColor); ?>;">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <p class="identity-name"><?php echo e($user->name); ?></p>
                        <p class="identity-jabatan"><?php echo e($user->jabatan ?? '-'); ?></p>
                    </div>
                </div>

                <div class="form-grid">
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Username</p>
                        <p style="font-weight:600;color:var(--gray-700);"><?php echo e($user->username); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Email</p>
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($user->email ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">No. Telepon</p>
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($user->phone ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Unit Kerja</p>
                        
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($user->unit->nama_unit ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Dibuat</p>
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($user->created_at->format('d M Y')); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Terakhir Diperbarui</p>
                        <p style="font-weight:500;color:var(--gray-700);"><?php echo e($user->updated_at->format('d M Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Hak Akses Menu</h2>
            </div>
            <div class="card-body">
                <?php if($user->isAdminUtama()): ?>
                    <p class="note-box note-box-success">
                        <i class="fas fa-circle-check"></i>
                        Admin Utama memiliki akses penuh ke seluruh menu secara otomatis.
                    </p>
                <?php else: ?>
                    
                    <div class="menu-access-grid">
                        <?php $__currentLoopData = $allMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $hasAccess = $user->canAccess($key); ?>
                            <div class="menu-access-item <?php echo e($hasAccess ? 'granted' : 'denied'); ?>">
                                <i class="fas fa-<?php echo e($hasAccess ? 'circle-check' : 'circle-xmark'); ?>"></i>
                                <?php echo e($label); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    
    <div style="display:flex;flex-direction:column;gap:16px;">

        
        <div class="card">
            <div class="card-header">
                <h2>Role</h2>
            </div>
            <div class="card-body role-card-body">
                <div class="role-icon" style="background:<?php echo e($avatarColor); ?>;">
                    <i class="fas fa-<?php echo e(match($user->role) {
                        'admin_utama'    => 'shield-halved',
                        'kepala_yayasan' => 'building-columns',
                        'admin_unit'     => 'user-gear',
                        'teknisi'        => 'screwdriver-wrench',
                        default          => 'user',
                    }); ?>"></i>
                </div>
                <div class="role-main">
                    
                    <p class="role-name"><?php echo e($user->role_label); ?></p>
                    <p class="role-desc">
                        <?php switch($user->role):
                            case ('admin_utama'): ?>    Akses penuh ke seluruh sistem <?php break; ?>
                            <?php case ('kepala_yayasan'): ?> Monitoring — tidak dapat mengedit data <?php break; ?>
                            <?php case ('admin_unit'): ?>     Mengelola aset unit sendiri <?php break; ?>
                            <?php case ('teknisi'): ?>        Memperbarui laporan perbaikan <?php break; ?>
                            <?php default: ?>                Melaporkan kerusakan aset
                        <?php endswitch; ?>
                    </p>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h2>Keamanan</h2>
            </div>
            <div class="card-body">
                <div class="security-row">
                    <p class="security-label">Wajib ganti password</p>
                    <?php if($user->must_change_password): ?>
                        <span class="badge badge-warning">Ya</span>
                    <?php else: ?>
                        <span class="badge badge-success">Tidak</span>
                    <?php endif; ?>
                </div>
                <div class="security-row">
                    <p class="security-label">Status akun</p>
                    <span class="badge badge-<?php echo e($user->status); ?>"><?php echo e($user->status_label); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal-backdrop" id="nonaktifModal">
    <div class="modal modal-confirm">
        <div class="modal-body" style="padding:32px 28px;text-align:center;">
            <div class="confirm-icon danger">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 style="font-size:17px;font-weight:800;margin-bottom:6px;">
                Nonaktifkan Pengguna
            </h3>
            <p style="font-size:13.5px;color:var(--gray-400);line-height:1.6;">
                Yakin ingin menonaktifkan akun
                <strong id="nonaktifUserName" style="color:var(--gray-700);"></strong>?
            </p>
            <p style="font-size:12px;color:var(--gray-300);margin-top:6px;">
                Akun tidak dihapus — dapat diaktifkan kembali melalui menu edit.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:24px;">
                <form id="nonaktifForm" method="POST" style="flex:1;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="username" id="nonaktifUsername">
                    <input type="hidden" name="name"     id="nonaktifName">
                    <input type="hidden" name="role"     id="nonaktifRole">
                    <input type="hidden" name="status"   value="nonaktif">
                    <input type="hidden" name="unit_id"  id="nonaktifUnitId">
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
                        Ya, Nonaktifkan
                    </button>
                </form>
                <button class="btn btn-outline" style="flex:1;justify-content:center;" onclick="closeModal('nonaktifModal')">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── Identitas: avatar + nama + jabatan ── */
    .user-identity-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-100);
    }
    .role-avatar {
        width: 56px; height: 56px;
        border-radius: 14px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 22px; font-weight: 700;
    }
    .identity-name    { font-weight: 700; font-size: 16px; color: var(--gray-800); margin-bottom: 3px; }
    .identity-jabatan { font-size: 13px; color: var(--gray-400); }

    /* ── Hak akses menu: checklist grid ── */
    .menu-access-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .menu-access-item {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 500;
        padding: 9px 12px;
        border-radius: var(--radius-sm);
    }
    .menu-access-item i { font-size: 13px; }
    .menu-access-item.granted { color: #15803d; background: var(--primary-xlight); }
    .menu-access-item.denied  { color: var(--gray-400); background: var(--gray-50); }

    /* ── Note box (dipakai untuk pesan akses penuh admin) ── */
    .note-box {
        font-size: 13.5px;
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        line-height: 1.6;
        display: flex; align-items: center; gap: 8px;
    }
    .note-box-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }

    /* ── Role card ── */
    .role-card-body { display: flex; align-items: center; gap: 14px; }
    .role-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 19px;
    }
    .role-name { font-weight: 700; font-size: 15px; color: var(--gray-800); }
    .role-desc { font-size: 12px; color: var(--gray-400); margin-top: 2px; }

    /* ── Keamanan: baris label + badge ── */
    .security-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-100);
    }
    .security-row:last-child { border-bottom: none; }
    .security-label { font-size: 13px; color: var(--gray-500); font-weight: 500; }

    @media (max-width: 768px) {
        .menu-access-grid { grid-template-columns: 1fr; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmNonaktif(id, name, username, role, unitId, menuAccess) {
    document.getElementById('nonaktifUserName').textContent = name;
    document.getElementById('nonaktifForm').action          = '/users/' + id;
    document.getElementById('nonaktifUsername').value       = username;
    document.getElementById('nonaktifName').value           = name;
    document.getElementById('nonaktifRole').value           = role;
    document.getElementById('nonaktifUnitId').value         = unitId || '';

    const form = document.getElementById('nonaktifForm');
    form.querySelectorAll('input[name="menu_access[]"]').forEach(el => el.remove());
    (menuAccess || []).forEach(menu => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'menu_access[]';
        inp.value = menu;
        form.appendChild(inp);
    });

    openModal('nonaktifModal');
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/users/show.blade.php ENDPATH**/ ?>