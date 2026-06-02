<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: url('<?php echo e(asset("images/background.png")); ?>') no-repeat center center;
            background-size: cover;
            padding: 24px 16px;
        }

        /* Overlay gelap di atas background */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background: rgba(10, 25, 15, 0.55);
            backdrop-filter: blur(2px);
            z-index: 0;
        }

        /* ── Card ── */
        .card {
            position: relative; z-index: 1;
            background: #fff; border-radius: 20px;
            padding: 36px 32px 30px;
            width: 100%; max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,.35);
        }

        /* ── Logo ── */
        .logo { text-align: center; margin-bottom: 28px; }
        .logo-img {
            width: 88px; height: 88px; object-fit: contain;
            border-radius: 18px; display: block;
            margin: 0 auto 12px;
            box-shadow: 0 4px 16px rgba(12,102,56,.2);
        }
        .logo h1 {
            font-size: 26px; font-weight: 900;
            color: #0f172a; letter-spacing: -1px;
        }
        .logo p { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 3px; }

        /* ── Divider ── */
        .divider {
            border: none; border-top: 1px solid #f1f5f9;
            margin: 0 0 22px;
        }

        /* ── Alert ── */
        .alert {
            padding: 11px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }

        /* ── Form ── */
        .form-group { margin-bottom: 16px; }
        label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #374151; margin-bottom: 6px; letter-spacing: .1px;
        }

        /* Input wrapper: ikon kiri + ikon toggle kanan dalam satu kotak */
        .input-wrap {
            position: relative; display: flex; align-items: center;
        }
        .input-wrap .icon-left {
            position: absolute; left: 13px;
            color: #94a3b8; font-size: 13px;
            pointer-events: none; z-index: 1;
        }
        .input-wrap input {
            width: 100%;
            padding: 11px 44px 11px 38px; /* kanan 44px = ruang ikon toggle */
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; font-family: inherit; color: #1e293b;
            background: #f8fafc; outline: none;
            transition: border-color .15s, background .15s, box-shadow .15s;
        }
        .input-wrap input:focus {
            border-color: #0C6638; background: #fff;
            box-shadow: 0 0 0 3px rgba(12,102,56,.1);
        }
        .input-wrap input.is-invalid { border-color: #dc2626; }
        .input-wrap input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.1); }

        /* Ikon toggle password — di dalam kotak, sisi kanan */
        .toggle-pw {
            position: absolute; right: 13px;
            background: none; border: none;
            cursor: pointer; color: #94a3b8; font-size: 13px;
            padding: 4px; border-radius: 4px;
            transition: color .15s;
            display: flex; align-items: center; justify-content: center;
            z-index: 1;
        }
        .toggle-pw:hover { color: #475569; }

        /* Input username tidak punya toggle — padding kanan normal */
        .input-wrap.no-toggle input { padding-right: 13px; }

        .error-msg { font-size: 12px; color: #dc2626; margin-top: 5px; }

        /* ── Remember me ── */
        .remember-row { margin-bottom: 20px; }
        .remember-row label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: #374151; font-weight: 500; cursor: pointer;
        }
        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #0C6638; cursor: pointer;
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%; padding: 12px;
            background: #0C6638; color: #fff;
            border: none; border-radius: 10px;
            font-size: 14.5px; font-weight: 700;
            cursor: pointer; font-family: inherit;
            transition: background .15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover  { background: #09542e; }
        .btn-login:active { background: #07431f; }

        /* ── Footer note ── */
        .note {
            margin-top: 20px; padding: 11px 14px;
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            font-size: 12px; color: #64748b; text-align: center; line-height: 1.6;
        }

        @media (max-width: 480px) {
            .card { padding: 28px 20px 24px; }
        }
    </style>
</head>
<body>
    <div class="card">

        
        <div class="logo">
            <img src="<?php echo e(asset('images/10505884-5.jpg')); ?>" alt="Logo SIMAS" class="logo-img">
            <h1>SIMAS</h1>
            <p>Sistem Informasi Manajemen Aset · Ash-Shiddiiqi</p>
        </div>

        <hr class="divider">

        
        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-circle-check"></i> <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
        <div class="alert alert-info">
            <i class="fas fa-circle-info"></i> <?php echo e(session('info')); ?>

        </div>
        <?php endif; ?>

        
        
        <form action="<?php echo e(route('login.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap no-toggle">
                    <i class="fas fa-user icon-left"></i>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="<?php echo e($errors->has('username') ? 'is-invalid' : ''); ?>"
                        placeholder="Masukkan username Anda"
                        value="<?php echo e(old('username')); ?>"
                        autocomplete="username"
                        autofocus>
                </div>
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error-msg"><i class="fas fa-circle-xmark"></i> <?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock icon-left"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="<?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                        placeholder="Masukkan password Anda"
                        autocomplete="current-password">
                    
                    <button type="button" class="toggle-pw"
                        onclick="togglePw()"
                        tabindex="-1"
                        aria-label="Tampilkan/sembunyikan password">
                        <i class="fas fa-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error-msg"><i class="fas fa-circle-xmark"></i> <?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember"> Ingat Saya
                </label>
                
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-right-to-bracket"></i> Masuk
            </button>
        </form>

        
        <div class="note">
            Akun dibuat oleh Admin. Hubungi administrator jika belum memiliki akun atau lupa password.
        </div>

    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type    = 'text';
                icon.className = 'fas fa-eye';
            } else {
                input.type    = 'password';
                icon.className = 'fas fa-eye-slash';
            }
        }
    </script>
</body>
</html><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/auth/login.blade.php ENDPATH**/ ?>