<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS - Lupa Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f4c35 100%); padding: 24px; }
        .card { background: #fff; border-radius: 20px; padding: 40px 36px; width: 100%; max-width: 420px; box-shadow: 0 25px 60px rgba(0,0,0,.35); text-align: center; }
        .icon { width: 64px; height: 64px; border-radius: 16px; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 26px; color: #28eb25; }
        h1 { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        p { font-size: 13.5px; color: #64748b; line-height: 1.6; margin-bottom: 24px; }
        .notice { background: #fef9c3; border: 1px solid #fde047; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #854d0e; text-align: left; margin-bottom: 24px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: #0C6638; color: #fff; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 14px; transition: background .15s; }
        .btn-back:hover { background: #0C6638; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon"><i class="fas fa-key"></i></div>
        <h1>Lupa Password?</h1>
        <p>Untuk mereset password, silakan hubungi Administrator sistem atau Super Admin Anda.</p>
        <div class="notice">
            <i class="fas fa-info-circle"></i> <strong>Info:</strong> Fitur reset password via email belum dikonfigurasi. Hubungi admin: <strong>admin@simas.sch.id</strong>
        </div>
        <a href="<?php echo e(route('login')); ?>" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>