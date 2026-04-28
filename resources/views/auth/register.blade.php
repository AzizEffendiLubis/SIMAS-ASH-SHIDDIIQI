<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS - Daftar Akun</title>
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

            background: url('{{ asset('images/background.png') }}') no-repeat center center;
            background-size: cover;
        }
                .card {
            background: #fff; border-radius: 20px; padding: 36px;
            width: 100%; max-width: 540px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }
        .logo { text-align: center; margin-bottom: 22px; }
        .logo-icon {
            width: 52px; height: 52px; border-radius: 13px;
            background: linear-gradient(135deg, #16a34a, #0C6638);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px; font-size: 20px; font-weight: 900; color: #fff;
        }
        .logo h1 { font-size: 22px; font-weight: 900; color: #0f172a; letter-spacing: -1px; }
        .logo p  { font-size: 12px; color: #000000; margin-top: 2px; }

        .notice {
            background: #d0fec3; border: 1px solid #7efd47; border-radius: 8px;
            padding: 10px 13px; font-size: 12.5px; color: #080808;
            margin-bottom: 18px; display: flex; align-items: flex-start; gap: 8px;
        }
        .role-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #000000; border: 1px solid #e2f0e3;
            border-radius: 8px; padding: 8px 14px; font-size: 13px;
            color: #475569; font-weight: 600; margin-bottom: 18px; width: 100%;
        }
        .role-badge i { color: #000000; }

        .section-label {
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .8px; color: #000000;
            margin-bottom: 12px; margin-top: 4px;
            padding-bottom: 7px; border-bottom: 1.5px solid #eff6ff;
        }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; }
        .form-group { margin-bottom: 13px; }
        .form-group.full { grid-column: 1 / -1; }
        label { display: block; font-size: 13px; font-weight: 600; color: #385137; margin-bottom: 5px; }
        .req { color: #dc2626; }
        .input-wrap { position: relative; }
        .input-wrap i.prefix {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #94b89b; font-size: 13px; pointer-events: none;
        }
        input[type=text], input[type=email], input[type=password], select {
            width: 100%; padding: 10px 12px 10px 36px;
            border: 1.5px solid #e2e8f0; border-radius: 9px;
            font-size: 13.5px; font-family: inherit; color: #005500;
            outline: none; transition: all .15s; background: #f8fafc;
            appearance: none;
        }
        input:focus, select:focus {
            border-color: #009000; background: #fff;
            box-shadow: 0 0 0 3px rgba(0,144,0,.1);
        }
        input.is-invalid, select.is-invalid { border-color: #dc2626; }
        .toggle-pw {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #94a3b8; font-size: 13px;
        }
        .error-msg { font-size: 11.5px; color: #dc2626; margin-top: 4px; }
        .hint { font-size: 11px; color: #94a3b8; margin-top: 3px; }
        .terms-row {
            display: flex; align-items: flex-start; gap: 8px;
            margin: 16px 0;
        }
        .terms-row input[type=checkbox] { margin-top: 2px; flex-shrink: 0; }
        .terms-row label {
            font-size: 12.5px; color: #64748b; font-weight: 400; margin: 0; cursor: pointer;
        }
        .terms-row a { color: #2563EB; font-weight: 600; }
        .btn-register {
            width: 100%; padding: 13px; background: #0C6638; color: #fff;
            border: none; border-radius: 10px; font-size: 15px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: background .15s;
        }
        .btn-register:hover { background: #0C6638; }
        .login-link { text-align: center; margin-top: 14px; font-size: 13px; color: #64748b; }
        .login-link a { color: #0C6638; font-weight: 600; text-decoration: none; }
        @media(max-width:500px){
            .form-grid { grid-template-columns: 1fr; }
            .card { padding: 24px 18px; }
        }
        .logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 18px;
            display: block;
            margin: 0 auto 12px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
       <img src="{{ asset('images/10505884-5.jpg') }}" alt="10505884-5.jpg" class="logo-img">
        <h1>SIMAS</h1>
        <p>Formulir Pendaftaran Akun</p>
    </div>

    {{-- Pemberitahuan role terkunci --}}
    <div class="notice">
        <i class="fas fa-info-circle" style="margin-top:1px;flex-shrink:0;"></i>
        Akun akan aktif setelah diverifikasi oleh Administrator.</span>
    </div>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <p class="section-label">Data Pribadi</p>
        <div class="form-grid">
            <div class="form-group full">
                <label>Nama Lengkap <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-id-card prefix"></i>
                    <input type="text" name="name" placeholder="Nama lengkap Anda"
                        value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
                </div>
                @error('name')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>Email <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-envelope prefix"></i>
                    <input type="email" name="email" placeholder="email@domain.com"
                        value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
                </div>
                @error('email')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <div class="input-wrap">
                    <i class="fas fa-phone prefix"></i>
                    <input type="text" name="phone" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                </div>
            </div>
        </div>

        <p class="section-label">Data Kepegawaian</p>
        <div class="form-grid">
            <div class="form-group">
                <label>Jabatan <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-briefcase prefix"></i>
                    <select name="jabatan" class="{{ $errors->has('jabatan') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach(['Guru','Staff','Petugas Kebersihan','Petugas Keamanan','Santri','Lainnya'] as $j)
                        <option value="{{ $j }}" {{ old('jabatan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                @error('jabatan')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>Unit Kerja <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-building prefix"></i>
                    <select name="unit_kerja" class="{{ $errors->has('unit_kerja') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ old('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                @error('unit_kerja')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
        </div>

        <p class="section-label">Data Akun</p>
        <div class="form-grid">
            <div class="form-group full">
                <label>Username <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-at prefix"></i>
                    <input type="text" name="username" placeholder="Masukkan username"
                        value="{{ old('username') }}" class="{{ $errors->has('username') ? 'is-invalid' : '' }}">
                </div>
                <p class="hint">Huruf, angka, underscore. Min. 4 karakter.</p>
                @error('username')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>Password <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-lock prefix"></i>
                    <input type="password" name="password" id="pw1" placeholder="Min. 8 karakter"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <span class="toggle-pw" onclick="togglePw('pw1','eye1')">
                        <i id="eye1" class="fas fa-eye-slash"></i>
                    </span>
                </div>
                @error('password')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>Konfirmasi Password <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-lock prefix"></i>
                    <input type="password" name="password_confirmation" id="pw2" placeholder="Ulangi password">
                    <span class="toggle-pw" onclick="togglePw('pw2','eye2')">
                        <i id="eye2" class="fas fa-eye-slash"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="terms-row">
            <input type="checkbox" name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
            <label for="terms">
                Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> dan
                <a href="#">Kebijakan Privasi</a> yang berlaku.
            </label>
        </div>
        @error('terms')<p class="error-msg" style="margin-bottom:10px;">{{ $message }}</p>@enderror

        <button type="submit" class="btn-register">Daftar Sekarang</button>
    </form>

    <p class="login-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
</div>

<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'fas fa-eye-slash' : 'fas fa-eye';
}
</script>
</body>
</html>
