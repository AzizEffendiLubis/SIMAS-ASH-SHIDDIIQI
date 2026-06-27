
<?php $__env->startSection('title', 'Tambah Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('page-parent', 'Tambah Pengguna'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Tambah Pengguna Baru</h1>
        <p>Buat akun pengguna baru untuk mengakses sistem</p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>


<?php if($errors->any()): ?>
<div class="alert alert-error">
    <i class="fas fa-triangle-exclamation"></i>
    <div>
        <p style="font-weight:700;margin-bottom:4px;">Terdapat kesalahan:</p>
        <ul style="margin:0;padding-left:16px;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li style="font-size:13px;"><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<form action="<?php echo e(route('users.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    
    <div class="dash-two-col">

        
        <div style="display:flex;flex-direction:column;gap:16px;">

            
            <div class="card">
                <div class="card-header">
                    <h2>Informasi Akun</h2>
                </div>
                <div class="card-body">
                    <div class="form-grid">

                        
                        <div class="form-group">
                            <label class="form-label">
                                Username (NIS/NIP) <span class="required">*</span>
                            </label>
                            <input type="text" name="username"
                                class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Contoh: 2024001 atau admin_sd"
                                value="<?php echo e(old('username')); ?>" autofocus>
                            <p class="form-hint">Hanya huruf, angka, dan underscore. Tidak dapat diubah pengguna.</p>
                            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="email@sekolah.sch.id"
                                value="<?php echo e(old('email')); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-group col-span-2">
                            <label class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Nama lengkap sesuai dokumen"
                                value="<?php echo e(old('name')); ?>">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan"
                                class="form-control <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Contoh: Waka Sarpras"
                                value="<?php echo e(old('jabatan')); ?>">
                            <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone"
                                class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="08xx-xxxx-xxxx"
                                value="<?php echo e(old('phone')); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h2>Password</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" style="margin-bottom:16px;">
                        <i class="fas fa-circle-info"></i>
                        <span>Pengguna wajib mengganti password saat pertama kali login.</span>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Password <span class="required">*</span>
                            </label>
                            <input type="password" name="password"
                                class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Min. 8 karakter">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Konfirmasi Password <span class="required">*</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
        <div style="display:flex;flex-direction:column;gap:16px;">

            
            <div class="card">
                <div class="card-header">
                    <h2>Role &amp; Unit</h2>
                </div>
                <div class="card-body">

                    
                    <div class="form-group">
                        <label class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <select name="role" id="roleSelect"
                            class="form-control <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            onchange="handleRoleChange(this.value)">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>"
                                    <?php echo e(old('role', 'admin_unit') === $value ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="form-hint" id="roleDesc"></p>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select name="status"
                            class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="aktif"    <?php echo e(old('status', 'aktif') === 'aktif'    ? 'selected' : ''); ?>>Aktif</option>
                            <option value="nonaktif" <?php echo e(old('status') === 'nonaktif' ? 'selected' : ''); ?>>Non-Aktif</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="form-group" id="unitFieldWrap">
                        <label class="form-label" id="unitLabel">
                            Unit Kerja
                            <span class="required" id="unitRequiredMark" style="display:none;">*</span>
                        </label>
                        <select name="unit_id" id="unitSelect"
                            class="form-control <?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">— Pilih Unit —</option>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>"
                                    <?php echo e(old('unit_id') == $unit->id ? 'selected' : ''); ?>>
                                    <?php echo e($unit->nama_unit); ?>

                                    <?php if($unit->kode_unit): ?> (<?php echo e($unit->kode_unit); ?>) <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="form-hint" id="unitHint">Opsional untuk role ini.</p>
                        <?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h2>Hak Akses Menu</h2>
                </div>
                <div class="card-body">
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:12px;">
                        Admin Utama otomatis mendapat akses penuh.
                    </p>
                    
                    <div id="menuAccessList" class="menu-access-list">
                        <?php $__currentLoopData = $allMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="menu-access-item">
                            <input type="checkbox" name="menu_access[]"
                                value="<?php echo e($key); ?>"
                                <?php echo e(in_array($key, old('menu_access', [])) ? 'checked' : ''); ?>>
                            <?php echo e($label); ?>

                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="form-actions">
                <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Pengguna
                </button>
            </div>

        </div>
    </div>
</form>

<?php $__env->startPush('styles'); ?>
<style>
    .menu-access-list { display: flex; flex-direction: column; gap: 10px; }
    .menu-access-item {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; cursor: pointer; color: var(--gray-700);
    }
    .menu-access-item input[type="checkbox"] {
        width: 15px; height: 15px; accent-color: var(--primary); cursor: pointer;
        flex-shrink: 0;
    }

    .form-actions { display: flex; gap: 10px; justify-content: flex-end; }

    @media (max-width: 768px) {
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Sinkron dengan UserController::unitRule()
const ROLES_REQUIRE_UNIT = ['user', 'admin_unit'];

const ROLE_DESC = {
    'admin_utama':    'Akses penuh ke seluruh sistem',
    'kepala_yayasan': 'Monitoring — lihat dashboard, aset, dan log aktivitas',
    'admin_unit':     'Mengelola aset unit sendiri',
    'teknisi':        'Melihat dan memperbarui laporan perbaikan',
    'user':           'Hanya dapat melaporkan kerusakan aset',
};

const ROLE_DEFAULT_MENUS = {
    'admin_utama':    ['dashboard','daftar_aset','perbaikan_aset','manajemen_pengguna','log_aktivitas','master_data'],
    'kepala_yayasan': ['dashboard','daftar_aset','perbaikan_aset','log_aktivitas'],
    'admin_unit':     ['dashboard','daftar_aset','perbaikan_aset'],
    'teknisi':        ['dashboard','perbaikan_aset'],
    'user':           ['dashboard','perbaikan_aset'],
};

function handleRoleChange(role) {
    // ── Unit field ──
    const unitSelect = document.getElementById('unitSelect');
    const unitMark   = document.getElementById('unitRequiredMark');
    const unitHint   = document.getElementById('unitHint');
    const required   = ROLES_REQUIRE_UNIT.includes(role);

    unitSelect.required      = required;
    unitMark.style.display   = required ? 'inline' : 'none';
    unitHint.textContent     = required
        ? 'Wajib dipilih untuk role ini.'
        : 'Opsional untuk role ini.';

    if (!required) unitSelect.value = '';

    // ── Role description ──
    document.getElementById('roleDesc').textContent = ROLE_DESC[role] || '';

    // ── Default menu checkboxes ──
    const defaults = ROLE_DEFAULT_MENUS[role] || [];
    document.querySelectorAll('#menuAccessList input[type=checkbox]').forEach(cb => {
        cb.checked = defaults.includes(cb.value);
    });
}

// Init saat halaman load
document.addEventListener('DOMContentLoaded', () => {
    handleRoleChange(document.getElementById('roleSelect').value);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/users/create.blade.php ENDPATH**/ ?>