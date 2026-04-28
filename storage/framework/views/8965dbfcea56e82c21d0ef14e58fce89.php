<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            background: url('<?php echo e(asset('images/background.png')); ?>') no-repeat center center;
            background-size: cover;
        }
        .card {
            background: #fff; border-radius: 20px; padding: 40px 36px;
            width: 100%; max-width: 440px; position: relative; z-index: 1;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }
        .logo { text-align: center; margin-bottom: 28px; }
        .logo-icon {
            width: 70px; height: 70px; border-radius: 18px;
            background: linear-gradient(135deg, #16a34a, #0C6638);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px; font-size: 28px; font-weight: 900; color: #fff; letter-spacing: -1px;
        }
        .logo h1 { font-size: 28px; font-weight: 900; color: #0f172a; letter-spacing: -1px; }
        .logo p { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 3px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13.5px; font-weight: 600; color: #374151; margin-bottom: 7px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
        input[type=text], input[type=password] {
            width: 100%; padding: 11px 13px 11px 40px; border: 1.5px solid #e2e8f0;
            border-radius: 10px; font-size: 14px; font-family: inherit; color: #1e293b;
            outline: none; transition: all .15s; background: #f8fafc;
        }
        input[type=text]:focus, input[type=password]:focus {
            border-color: #2563eb; background: #fff; box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .toggle-pw { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; font-size: 14px; }
        .error-msg { font-size: 12px; color: #dc2626; margin-top: 5px; }
        .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .remember-row label { font-size: 13px; color: #374151; font-weight: 500; display: flex; align-items: center; gap: 7px; margin: 0; cursor: pointer; }
        .remember-row a { font-size: 13px; color: #1e2024; text-decoration: none; font-weight: 600; }
        .remember-row a:hover { text-decoration: underline; }
        .btn-login {
            width: 100%; padding: 13px; background: #0C6638; color: #fff;
            border: none; border-radius: 10px; font-size: 15px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: background .15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { background: #0C6638; }
        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 22px 0; }
        .register-area { text-align: center; }
        .register-area p { font-size: 13.5px; color: #64748b; font-weight: 600; margin-bottom: 10px; }
        .btn-register {
            width: 100%; padding: 12px; background: #f8fafc; color: #374151;
            border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: all .15s; text-decoration: none; display: block;
        }
        .btn-register:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .alert { padding: 11px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain; /* biar tidak kepotong */
            border-radius: 18px;
            display: block;
            margin: 0 auto 12px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="<?php echo e(asset('images/10505884-5.jpg')); ?>" alt="10505884-5.jpg" class="logo-img">
            <h1>SIMAS</h1>
            <p>Sistem Informasi Manajemen Aset • Ash-Shiddiiqi</p>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success"><i class="fas fa-circle-check"></i> <?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('login.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Masukkan username Anda" value="<?php echo e(old('username')); ?>" autocomplete="username">
                </div>
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="error-msg"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Masukkan password Anda">
                    <span class="toggle-pw" onclick="togglePw()"><i class="fas fa-eye-slash" id="eyeIcon"></i></span>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="error-msg"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="remember-row">
                <label><input type="checkbox" name="remember"> Ingat Saya</label>
                <a href="<?php echo e(route('password.request')); ?>">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-right-to-bracket"></i> Masuk
            </button>
        </form>

        <hr class="divider">

        <div class="register-area">
            <p>Belum punya akun?</p>
            <a href="<?php echo e(route('register')); ?>" class="btn-register">Daftar Sekarang</a>
        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye-slash';
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/auth/login.blade.php ENDPATH**/ ?>