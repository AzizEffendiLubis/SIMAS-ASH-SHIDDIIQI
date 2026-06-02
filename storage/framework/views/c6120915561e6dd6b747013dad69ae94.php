<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ganti Password — SIMAS</title>

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

        body::before {
            content: '';
            position: fixed; inset: 0;
            background: rgba(10, 25, 15, 0.55);
            backdrop-filter: blur(2px);
            z-index: 0;
        }

        /* ── Card — identik login ── */
        .card {
            position: relative; z-index: 1;
            background: #fff; border-radius: 20px; padding: 36px 32px 30px;
            width: 100%; max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }

        /* ── Logo di dalam card — identik login ── */
        .logo { text-align: center; margin-bottom: 28px; }
        .logo-img {
            width: 88px; height: 88px; object-fit: contain;
            border-radius: 18px; display: block; margin: 0 auto 12px;
        }
        .logo h1 { font-size: 26px; font-weight: 900; color: #0f172a; letter-spacing: -1px; }
        .logo p  { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 3px; }

        /* ── Notice bar ── */
        .notice {
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px;
            padding: 11px 14px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 9px;
            font-size: 12.5px; color: #92400e; line-height: 1.5;
        }
        .notice i { color: #d97706; font-size: 13px; margin-top: 1px; flex-shrink: 0; }
        .notice strong { font-weight: 700; }

        /* ── User chip ── */
        .user-chip {
            display: flex; align-items: center; gap: 9px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 10px; padding: 9px 13px; margin-bottom: 20px;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 7px; flex-shrink: 0;
            background: linear-gradient(135deg, #0C6638, #15803d);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700;
        }
        .user-info .uname { font-size: 13px; font-weight: 700; color: #1e293b; }
        .user-info .urole { font-size: 11.5px; color: #94a3b8; margin-top: 1px; }

        /* ── Alert error ── */
        .alert {
            padding: 11px 14px; border-radius: 8px; font-size: 13px;
            margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
        }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        /* ── Form — identik login ── */
        .form-group { margin-bottom: 16px; }
        label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #374151; margin-bottom: 6px; letter-spacing: .1px;
        }

        .input-wrap { position: relative; display: flex; align-items: center; }
        .input-wrap input {
            width: 100%;
            padding: 11px 44px 11px 13px;
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

        /* Toggle di dalam kotak — identik login */
        .toggle-pw {
            position: absolute; right: 13px;
            background: none; border: none; cursor: pointer;
            color: #94a3b8; font-size: 13px; padding: 4px; border-radius: 4px;
            transition: color .15s; z-index: 1;
        }
        .toggle-pw:hover { color: #475569; }

        .error-msg  { font-size: 12px; color: #dc2626; margin-top: 5px; }
        .form-hint  { font-size: 11.5px; color: #94a3b8; margin-top: 5px; }

        /* ── Strength bar ── */
        .strength-wrap { margin-top: 8px; }
        .strength-bar  {
            height: 4px; background: #e2e8f0; border-radius: 99px;
            overflow: hidden; margin-bottom: 4px;
        }
        .strength-fill {
            height: 100%; border-radius: 99px; width: 0;
            transition: width .3s, background .3s;
        }
        .strength-label { font-size: 11.5px; color: #94a3b8; }

        /* ── Rules ── */
        .pw-rules { margin-top: 10px; display: flex; flex-direction: column; gap: 5px; }
        .pw-rule  {
            display: flex; align-items: center; gap: 7px;
            font-size: 12px; color: #94a3b8; transition: color .2s;
        }
        .pw-rule i    { font-size: 11px; }
        .pw-rule.ok   { color: #15803d; }

        /* ── Submit — identik .btn-login ── */
        .btn-login {
            width: 100%; padding: 12px; background: #0C6638; color: #fff;
            border: none; border-radius: 10px; font-size: 15px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: background .15s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 4px;
        }
        .btn-login:hover    { background: #0a5530; }
        .btn-login:active   { background: #07431f; }
        .btn-login:disabled { opacity: .5; cursor: not-allowed; }

        /* ── Footer note — identik login .note ── */
        .note {
            margin-top: 22px; padding: 12px 14px; background: #f8fafc;
            border: 1px solid #e2e8f0; border-radius: 10px;
            font-size: 12px; color: #64748b; text-align: center; line-height: 1.6;
        }
        .note button {
            background: none; border: none; cursor: pointer;
            font-family: inherit; font-size: 12px;
            color: #0C6638; font-weight: 600; padding: 0;
        }
        .note button:hover { text-decoration: underline; }

        @media (max-width: 480px) {
            .card { padding: 28px 20px; }
        }
    </style>
</head>
<body>

<div class="card">

    
    <div class="logo">
        <img src="<?php echo e(asset('images/10505884-5.jpg')); ?>" alt="Logo SIMAS" class="logo-img">
        <h1>SIMAS</h1>
        <p>Sistem Informasi Manajemen Aset • Ash-Shiddiiqi</p>
    </div>

    
    <div class="notice">
        <i class="fas fa-triangle-exclamation"></i>
        <div>
            <strong>Password wajib diganti.</strong>
            Anda menggunakan password default. Buat password baru sebelum melanjutkan.
        </div>
    </div>

    
    <div class="user-chip">
        <div class="user-avatar">
            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

        </div>
        <div class="user-info">
            <div class="uname"><?php echo e(auth()->user()->name); ?></div>
            <div class="urole">
                <?php echo e(auth()->user()->role_label); ?>

                <?php if(auth()->user()->unit): ?>
                    · <?php echo e(auth()->user()->unit->nama_unit); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if($errors->has('password')): ?>
    <div class="alert alert-error">
        <i class="fas fa-circle-xmark"></i> <?php echo e($errors->first('password')); ?>

    </div>
    <?php endif; ?>

    
    <form method="POST" action="<?php echo e(route('password.change.post')); ?>">
        <?php echo csrf_field(); ?>

        
        <div class="form-group">
            <label for="password">Password Baru</label>
            <div class="input-wrap">
                <input type="password" id="password" name="password"
                    class="<?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                    placeholder="Masukkan password baru"
                    autocomplete="new-password" required
                    oninput="checkStrength(this.value); checkRules(this.value);">
                <button type="button" class="toggle-pw"
                    onclick="togglePw('password', this)" tabindex="-1">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="strength-wrap" id="strengthWrap" style="display:none;">
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <span class="strength-label" id="strengthLabel"></span>
            </div>
            <div class="pw-rules">
                <div class="pw-rule" id="rule-len">
                    <i class="fas fa-circle-xmark"></i> Minimal 8 karakter
                </div>
                <div class="pw-rule" id="rule-upper">
                    <i class="fas fa-circle-xmark"></i> Mengandung huruf kapital
                </div>
                <div class="pw-rule" id="rule-num">
                    <i class="fas fa-circle-xmark"></i> Mengandung angka
                </div>
            </div>
        </div>

        
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="input-wrap">
                <input type="password" id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password baru"
                    autocomplete="new-password" required
                    oninput="checkConfirm()">
                <button type="button" class="toggle-pw"
                    onclick="togglePw('password_confirmation', this)" tabindex="-1">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <p class="form-hint" id="confirmHint" style="display:none;"></p>
        </div>

        <button type="submit" class="btn-login" id="btnSubmit" disabled>
            <i class="fas fa-key"></i> Simpan Password Baru
        </button>
    </form>

    
    <div class="note">
        Ingin keluar?
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
            <?php echo csrf_field(); ?>
            <button type="submit">Log Out</button>
        </form>
    </div>

</div>

<script>
function togglePw(id, btn) {
    const inp  = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text'; icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password'; icon.className = 'fas fa-eye';
    }
}

function checkStrength(val) {
    const wrap = document.getElementById('strengthWrap');
    const fill = document.getElementById('strengthFill');
    const lbl  = document.getElementById('strengthLabel');
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = '';
    let s = 0;
    if (val.length >= 8)           s++;
    if (val.length >= 12)          s++;
    if (/[A-Z]/.test(val))        s++;
    if (/[0-9]/.test(val))        s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const lvls = [
        {p:'20%',c:'#ef4444',t:'Sangat Lemah'},
        {p:'40%',c:'#f97316',t:'Lemah'},
        {p:'60%',c:'#eab308',t:'Sedang'},
        {p:'80%',c:'#22c55e',t:'Kuat'},
        {p:'100%',c:'#15803d',t:'Sangat Kuat'},
    ];
    const l = lvls[Math.min(s - 1, 4)] || lvls[0];
    fill.style.width = l.p; fill.style.background = l.c;
    lbl.textContent = l.t; lbl.style.color = l.c;
}

function checkRules(val) {
    setRule('rule-len',   val.length >= 8);
    setRule('rule-upper', /[A-Z]/.test(val));
    setRule('rule-num',   /[0-9]/.test(val));
    updateBtn();
}

function setRule(id, ok) {
    const el = document.getElementById(id);
    el.classList.toggle('ok', ok);
    el.querySelector('i').className = ok ? 'fas fa-circle-check' : 'fas fa-circle-xmark';
}

function checkConfirm() {
    const pw  = document.getElementById('password').value;
    const cf  = document.getElementById('password_confirmation').value;
    const hint = document.getElementById('confirmHint');
    const ctrl = document.getElementById('password_confirmation');
    if (!cf) { hint.style.display = 'none'; ctrl.classList.remove('is-invalid'); updateBtn(); return; }
    const ok = pw === cf;
    hint.style.display = ''; hint.style.color = ok ? '#15803d' : '#dc2626';
    hint.textContent   = ok ? '✓ Password cocok' : 'Password tidak cocok';
    ctrl.classList.toggle('is-invalid', !ok);
    updateBtn();
}

function updateBtn() {
    const pw = document.getElementById('password').value;
    const cf = document.getElementById('password_confirmation').value;
    document.getElementById('btnSubmit').disabled =
        !(pw.length >= 8 && /[A-Z]/.test(pw) && /[0-9]/.test(pw) && pw === cf);
}
</script>

</body>
</html><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/auth/change-password.blade.php ENDPATH**/ ?>