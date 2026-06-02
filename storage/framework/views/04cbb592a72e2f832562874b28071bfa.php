<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS - Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f1f5f9; }
        .container { text-align: center; padding: 40px; }
        .icon { font-size: 64px; color: #e2e8f0; margin-bottom: 16px; }
        h1 { font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
        p { font-size: 15px; color: #64748b; margin-bottom: 24px; }
        a { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; background: #2563eb; color: #fff; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 14px; }
        a:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class="fas fa-lock"></i></div>
        <h1>Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.<br>Hubungi administrator jika Anda membutuhkan akses.</p>
        <a href="<?php echo e(url()->previous() !== url()->current() ? url()->previous() : route('dashboard')); ?>">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/errors/403.blade.php ENDPATH**/ ?>