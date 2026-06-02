
<?php $__env->startSection('title', 'Detail Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Pengguna</h1>
        <p>Username: <strong><?php echo e($user->username); ?></strong></p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

    
    <div style="display:flex;flex-direction:column;gap:20px;">

        
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Informasi Pengguna
                </p>

                <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                    <?php
                        $avatarColor = match($user->role) {
                            'admin_utama'    => '#7c3aed',
                            'kepala_yayasan' => '#c2410c',
                            'admin_unit'     => '#2563eb',
                            'teknisi'        => '#15803d',
                            default          => '#475569',
                        };
                    ?>
                    <div style="width:56px;height:56px;border-radius:14px;
                        background:<?php echo e($avatarColor); ?>;
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:22px;font-weight:700;flex-shrink:0;">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <p style="font-weight:700;font-size:17px;margin-bottom:3px;"><?php echo e($user->name); ?></p>
                        <p style="font-size:13px;color:#64748b;"><?php echo e($user->jabatan ?? '-'); ?></p>
                    </div>
                    <div style="margin-left:auto;">
                        
                        <span class="badge badge-<?php echo e($user->status); ?>" style="font-size:13px;padding:5px 14px;">
                            <?php echo e($user->status_label); ?>

                        </span>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Username</p>
                        <p style="font-weight:600;"><?php echo e($user->username); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Email</p>
                        <p style="font-weight:500;"><?php echo e($user->email ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">No. Telepon</p>
                        <p style="font-weight:500;"><?php echo e($user->phone ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Unit Kerja</p>
                        
                        <p style="font-weight:500;"><?php echo e($user->unit->nama_unit ?? '-'); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Dibuat</p>
                        <p style="font-weight:500;"><?php echo e($user->created_at->format('d M Y')); ?></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Terakhir Diperbarui</p>
                        <p style="font-weight:500;"><?php echo e($user->updated_at->format('d M Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Hak Akses Menu
                </p>

                <?php if($user->isAdminUtama()): ?>
                    <p style="font-size:13px;color:#16a34a;">
                        <i class="fas fa-circle-check"></i>
                        Admin Utama memiliki akses penuh ke seluruh menu secara otomatis.
                    </p>
                <?php else: ?>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <?php $__currentLoopData = $allMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $hasAccess = $user->canAccess($key); ?>
                            <div style="display:flex;align-items:center;gap:8px;font-size:13px;
                                color:<?php echo e($hasAccess ? '#15803d' : '#94a3b8'); ?>;">
                                <i class="fas fa-<?php echo e($hasAccess ? 'circle-check' : 'circle-xmark'); ?>"
                                   style="font-size:14px;"></i>
                                <?php echo e($label); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    
    <div style="display:flex;flex-direction:column;gap:20px;">

        
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Role
                </p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:44px;height:44px;border-radius:10px;
                        background:<?php echo e($avatarColor); ?>;
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:18px;">
                        <i class="fas fa-<?php echo e(match($user->role) {
                            'admin_utama'    => 'shield-halved',
                            'kepala_yayasan' => 'building-columns',
                            'admin_unit'     => 'user-gear',
                            'teknisi'        => 'screwdriver-wrench',
                            default          => 'user',
                        }); ?>"></i>
                    </div>
                    <div>
                        
                        <p style="font-weight:700;font-size:15px;"><?php echo e($user->role_label); ?></p>
                        <p style="font-size:12px;color:#64748b;">
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
        </div>

        
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Keamanan
                </p>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:13px;color:#64748b;">Wajib ganti password</p>
                        <?php if($user->must_change_password): ?>
                            <span class="badge badge-warning" style="font-size:12px;">Ya</span>
                        <?php else: ?>
                            <span class="badge badge-success" style="font-size:12px;">Tidak</span>
                        <?php endif; ?>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:13px;color:#64748b;">Status akun</p>
                        <span class="badge badge-<?php echo e($user->status); ?>" style="font-size:12px;">
                            <?php echo e($user->status_label); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($user->id !== auth()->id()): ?>
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;
                    color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">
                    Aksi Cepat
                </p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-outline" style="width:100%;justify-content:center;">
                        <i class="fas fa-pen"></i> Edit Data
                    </a>
                    <?php if($user->status === 'aktif'): ?>
                    <button class="btn btn-outline" style="width:100%;justify-content:center;color:#dc2626;"
                        onclick="confirmNonaktif(<?php echo e($user->id); ?>, '<?php echo e(addslashes($user->name)); ?>', '<?php echo e(addslashes($user->username)); ?>', '<?php echo e($user->role); ?>', <?php echo e($user->unit_id ?? 'null'); ?>, <?php echo json_encode($user->menu_access ?? [], 15, 512) ?>)">
                        <i class="fas fa-user-slash"></i> Nonaktifkan
                    </button>
                    <?php else: ?>
                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-outline"
                        style="width:100%;justify-content:center;color:#16a34a;">
                        <i class="fas fa-user-check"></i> Aktifkan Kembali
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>


<div class="modal-backdrop" id="nonaktifModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="icon"><i class="fas fa-user-slash"></i></div>
            <h3>Nonaktifkan Pengguna</h3>
            <p>Yakin ingin menonaktifkan akun <strong id="nonaktifUserName"></strong>?</p>
            <p style="font-size:12px;color:#94a3b8;margin-top:6px;">
                Akun tidak dihapus — dapat diaktifkan kembali melalui menu edit.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="nonaktifForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="username" id="nonaktifUsername">
                    <input type="hidden" name="name"     id="nonaktifName">
                    <input type="hidden" name="role"     id="nonaktifRole">
                    <input type="hidden" name="status"   value="nonaktif">
                    <input type="hidden" name="unit_id"  id="nonaktifUnitId">
                    <button type="submit" class="btn btn-danger">Ya, Nonaktifkan</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('nonaktifModal')">Batal</button>
            </div>
        </div>
    </div>
</div>

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