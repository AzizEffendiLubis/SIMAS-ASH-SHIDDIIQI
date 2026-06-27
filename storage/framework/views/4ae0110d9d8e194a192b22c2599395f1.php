<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS — Sistem Informasi Manajemen Aset Ash-Shiddiiqi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark:   #0C6638;
            --green-mid:    #0f7d43;
            --green-light:  #e6f4ed;
            --green-border: #b6dfc8;
            --gold:         #c9a84c;
            --gold-light:   #fdf7e8;
            --text-dark:    #0f172a;
            --text-mid:     #334155;
            --text-muted:   #64748b;
            --white:        #ffffff;
            --surface:      rgba(255,255,255,0.10);
            --surface-card: rgba(255,255,255,0.92);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: url('<?php echo e(asset("images/background.png")); ?>') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-dark);
        }

        /* Overlay gelap — sama dengan halaman login */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background: rgba(10, 25, 15, 0.62);
            backdrop-filter: blur(2px);
            z-index: 0;
        }

        /* ══════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════ */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px;
            height: 64px;
            background: rgba(10, 25, 15, 0.55);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .navbar-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .navbar-brand img {
            width: 36px; height: 36px; border-radius: 8px; object-fit: contain;
        }
        .navbar-brand-text {
            font-size: 17px; font-weight: 800;
            color: #fff; letter-spacing: -0.5px;
        }
        .navbar-brand-text span {
            font-weight: 400; font-size: 12px;
            color: rgba(255,255,255,0.55);
            display: block; line-height: 1; margin-top: 1px;
        }

        .btn-login-nav {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px;
            background: var(--green-dark);
            color: #fff;
            border: none; border-radius: 9px;
            font-size: 13.5px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            text-decoration: none;
            transition: background .15s, transform .1s;
        }
        .btn-login-nav:hover  { background: #09542e; transform: translateY(-1px); }
        .btn-login-nav:active { transform: translateY(0); }

        /* ══════════════════════════════════════════
           KONTEN UTAMA
        ══════════════════════════════════════════ */
        main {
            position: relative; z-index: 1;
            padding-top: 64px; /* offset navbar */
        }

        /* ── HERO ── */
        .hero {
            min-height: calc(100vh - 64px);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 64px 24px 80px;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.20);
            color: rgba(255,255,255,0.80);
            font-size: 12px; font-weight: 600;
            padding: 5px 14px; border-radius: 99px;
            letter-spacing: .3px;
            margin-bottom: 24px;
        }
        .hero-badge i { color: var(--gold); font-size: 11px; }

        .hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 900; color: #fff;
            letter-spacing: -2px; line-height: 1.05;
            margin-bottom: 8px;
        }
        .hero h1 span { color: var(--gold); }

        .hero-sub {
            font-size: clamp(14px, 2vw, 17px);
            color: rgba(255,255,255,0.65);
            font-weight: 500; margin-bottom: 36px;
            max-width: 560px;
            line-height: 1.6;
        }

        .hero-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px;
            background: var(--green-dark);
            color: #fff;
            border-radius: 12px;
            font-size: 15px; font-weight: 700;
            text-decoration: none;
            transition: background .15s, transform .15s, box-shadow .15s;
            box-shadow: 0 8px 32px rgba(12,102,56,0.45);
        }
        .hero-cta:hover {
            background: #09542e;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(12,102,56,0.55);
        }

        .hero-scroll {
            margin-top: 56px;
            color: rgba(255,255,255,0.35);
            font-size: 12px; letter-spacing: .5px;
            display: flex; flex-direction: column; align-items: center; gap: 6px;
            animation: bob 2s ease-in-out infinite;
        }
        .hero-scroll i { font-size: 16px; }
        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(5px); }
        }

        /* ── SECTION WRAPPER ── */
        .section {
            padding: 80px 24px;
        }
        .section-inner {
            max-width: 1060px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 10px;
        }
        .section-title {
            font-size: clamp(24px, 4vw, 36px);
            font-weight: 800; color: #fff;
            letter-spacing: -1px; line-height: 1.15;
            margin-bottom: 14px;
        }
        .section-desc {
            font-size: 15px; color: rgba(255,255,255,0.60);
            max-width: 560px; line-height: 1.7;
            margin-bottom: 48px;
        }

        /* ── TENTANG ── */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .about-card {
            background: var(--surface-card);
            border-radius: 16px;
            padding: 28px 28px 24px;
            border: 1px solid rgba(255,255,255,0.6);
        }
        .about-card-icon {
            width: 44px; height: 44px; border-radius: 10px;
            background: var(--green-light);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }
        .about-card-icon i { font-size: 18px; color: var(--green-dark); }
        .about-card h3 {
            font-size: 15px; font-weight: 700;
            color: var(--text-dark); margin-bottom: 6px;
        }
        .about-card p {
            font-size: 13.5px; color: var(--text-muted); line-height: 1.65;
        }

        /* ── FITUR / PANDUAN ── */
        .fitur-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
        .fitur-card {
            background: var(--surface-card);
            border-radius: 16px;
            padding: 26px 24px 22px;
            border: 1px solid rgba(255,255,255,0.6);
            position: relative;
            overflow: hidden;
            transition: transform .15s, box-shadow .15s;
        }
        .fitur-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
        }
        .fitur-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--green-dark);
            border-radius: 16px 16px 0 0;
        }
        .fitur-num {
            font-size: 11px; font-weight: 800;
            color: var(--green-dark); letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .fitur-card h3 {
            font-size: 14.5px; font-weight: 700;
            color: var(--text-dark); margin-bottom: 6px;
        }
        .fitur-card p {
            font-size: 13px; color: var(--text-muted); line-height: 1.6;
        }
        .fitur-icon {
            position: absolute; bottom: 16px; right: 18px;
            font-size: 28px; color: var(--green-light);
        }

        /* ── PERAN / ROLE ── */
        .role-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }
        .role-card {
            background: var(--surface-card);
            border-radius: 14px;
            padding: 22px 16px 18px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.6);
            transition: transform .15s;
        }
        .role-card:hover { transform: translateY(-2px); }
        .role-avatar {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            font-size: 20px;
        }
        .role-card h3 { font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
        .role-card p  { font-size: 11.5px; color: var(--text-muted); line-height: 1.55; }

        .role-kepsek   .role-avatar { background: #fdf7e8; color: #92690a; }
        .role-adminut  .role-avatar { background: #e6f4ed; color: var(--green-dark); }
        .role-adminun  .role-avatar { background: #eff6ff; color: #1d4ed8; }
        .role-teknisi  .role-avatar { background: #fef3c7; color: #92400e; }
        .role-user     .role-avatar { background: #f3e8ff; color: #6b21a8; }

        /* ── ALUR PENGGUNAAN ── */
        .alur-list {
            display: flex; flex-direction: column; gap: 16px;
            max-width: 700px; margin: 0 auto;
        }
        .alur-item {
            display: flex; gap: 18px; align-items: flex-start;
            background: var(--surface-card);
            border-radius: 14px; padding: 20px 22px;
            border: 1px solid rgba(255,255,255,0.6);
        }
        .alur-num {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--green-dark); color: #fff;
            font-size: 14px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .alur-body h3 { font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 3px; }
        .alur-body p  { font-size: 13px; color: var(--text-muted); line-height: 1.6; }

        /* ── CTA BAWAH ── */
        .cta-bottom {
            text-align: center;
            padding: 80px 24px 100px;
        }
        .cta-box {
            display: inline-block;
            background: var(--surface-card);
            border-radius: 20px;
            padding: 48px 56px;
            border: 1px solid rgba(255,255,255,0.6);
            max-width: 540px;
            width: 100%;
        }
        .cta-box i.big { font-size: 40px; color: var(--green-dark); margin-bottom: 16px; display: block; }
        .cta-box h2 { font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px; letter-spacing: -.5px; }
        .cta-box p  { font-size: 14px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
        .btn-cta-big {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 32px;
            background: var(--green-dark); color: #fff;
            border-radius: 11px; font-size: 14.5px; font-weight: 700;
            text-decoration: none;
            transition: background .15s, transform .15s;
        }
        .btn-cta-big:hover { background: #09542e; transform: translateY(-1px); }

        /* ── FOOTER ── */
        footer {
            position: relative; z-index: 1;
            text-align: center;
            padding: 20px 24px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 12px; color: rgba(255,255,255,0.30);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .fitur-grid { grid-template-columns: repeat(2, 1fr); }
            .role-grid  { grid-template-columns: repeat(3, 1fr); }
            .about-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .navbar { padding: 0 20px; }
            .fitur-grid { grid-template-columns: 1fr; }
            .role-grid  { grid-template-columns: repeat(2, 1fr); }
            .cta-box    { padding: 32px 24px; }
            .section    { padding: 60px 20px; }
        }
    </style>
</head>
<body>

    
    <nav class="navbar">
        <a href="#" class="navbar-brand">
            <img src="<?php echo e(asset('images/10505884-5.jpg')); ?>" alt="Logo SIMAS">
            <div class="navbar-brand-text">
                SIMAS
                <span>Ash-Shiddiiqi</span>
            </div>
        </a>
        <a href="<?php echo e(route('login')); ?>" class="btn-login-nav">
            <i class="fas fa-right-to-bracket"></i> Masuk ke Sistem
        </a>
    </nav>

    <main>

        
        <section class="hero">
            <div class="hero-badge">
                <i class="fas fa-circle-dot"></i>
                Yayasan Ash-Shiddiiqi
            </div>
            <h1>Sistem Informasi<br><span>Manajemen Aset</span></h1>
            <p class="hero-sub">
                Platform terpusat untuk pengelolaan, pemantauan, dan pelaporan seluruh aset
                Yayasan Ash-Shiddiiqi secara efisien dan transparan.
            </p>
            <a href="<?php echo e(route('login')); ?>" class="hero-cta">
                <i class="fas fa-right-to-bracket"></i>
                Masuk ke SIMAS
            </a>
            <div class="hero-scroll">
                <i class="fas fa-chevron-down"></i>
                Gulir untuk panduan
            </div>
        </section>

        
        <section class="section" id="tentang">
            <div class="section-inner">
                <p class="section-label">Tentang Sistem</p>
                <h2 class="section-title">Apa itu SIMAS?</h2>
                <p class="section-desc">
                    SIMAS adalah sistem manajemen aset berbasis web yang dirancang khusus untuk
                    kebutuhan Yayasan Ash-Shiddiiqi — mencakup pencatatan, pemantauan kondisi,
                    pelaporan kerusakan, hingga rekap laporan untuk pimpinan.
                </p>
                <div class="about-grid">
                    <div class="about-card">
                        <div class="about-card-icon"><i class="fas fa-box-archive"></i></div>
                        <h3>Pencatatan Aset Terpusat</h3>
                        <p>Seluruh aset yayasan dicatat dengan kode unik, informasi unit, kondisi, foto, dan riwayat perubahan yang tersimpan secara permanen.</p>
                    </div>
                    <div class="about-card">
                        <div class="about-card-icon"><i class="fas fa-screwdriver-wrench"></i></div>
                        <h3>Manajemen Perbaikan</h3>
                        <p>Laporan kerusakan aset dapat dibuat dan dilacak statusnya — dari laporan masuk, proses perbaikan oleh teknisi, hingga selesai.</p>
                    </div>
                    <div class="about-card">
                        <div class="about-card-icon"><i class="fas fa-shield-halved"></i></div>
                        <h3>Kontrol Akses Berlapis</h3>
                        <p>Setiap pengguna memiliki hak akses sesuai perannya. Kepala Yayasan hanya bisa memantau; pengelolaan data ada di tangan admin.</p>
                    </div>
                    <div class="about-card">
                        <div class="about-card-icon"><i class="fas fa-clock-rotate-left"></i></div>
                        <h3>Riwayat &amp; Audit Trail</h3>
                        <p>Semua aktivitas tercatat otomatis — penambahan, perubahan kondisi, laporan perbaikan — sehingga data selalu dapat dipertanggungjawabkan.</p>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="section" id="fitur">
            <div class="section-inner">
                <p class="section-label">Fitur Sistem</p>
                <h2 class="section-title">Yang Bisa Dilakukan di SIMAS</h2>
                <p class="section-desc">
                    Enam modul utama yang mencakup seluruh siklus hidup aset yayasan dari pengadaan hingga penghapusan.
                </p>
                <div class="fitur-grid">
                    <div class="fitur-card">
                        <p class="fitur-num">01</p>
                        <h3>Data Aset</h3>
                        <p>Catat aset baru lengkap dengan foto, spesifikasi, sumber dana, dan lokasi penempatan. Kode aset dibuat otomatis.</p>
                        <i class="fas fa-box fitur-icon"></i>
                    </div>
                    <div class="fitur-card">
                        <p class="fitur-num">02</p>
                        <h3>Kondisi Aset</h3>
                        <p>Perbarui kondisi aset — Aktif, Rusak, Hilang, atau Habis Pakai — dengan riwayat perubahan yang tersimpan otomatis.</p>
                        <i class="fas fa-heart-pulse fitur-icon"></i>
                    </div>
                    <div class="fitur-card">
                        <p class="fitur-num">03</p>
                        <h3>Laporan Kerusakan</h3>
                        <p>Laporkan kerusakan aset dengan foto dan deskripsi. Admin dan teknisi menangani, status dapat dipantau secara real-time.</p>
                        <i class="fas fa-triangle-exclamation fitur-icon"></i>
                    </div>
                    <div class="fitur-card">
                        <p class="fitur-num">04</p>
                        <h3>Manajemen Pengguna</h3>
                        <p>Admin Utama membuat dan mengelola akun seluruh pengguna sistem sesuai unit dan peran masing-masing.</p>
                        <i class="fas fa-users fitur-icon"></i>
                    </div>
                    <div class="fitur-card">
                        <p class="fitur-num">05</p>
                        <h3>Master Data</h3>
                        <p>Kelola data referensi — unit kerja, sumber dana, dan satuan aset — yang digunakan di seluruh modul sistem.</p>
                        <i class="fas fa-database fitur-icon"></i>
                    </div>
                    <div class="fitur-card">
                        <p class="fitur-num">06</p>
                        <h3>Log Aktivitas</h3>
                        <p>Rekam jejak setiap perubahan data tersedia untuk Admin Utama dan Kepala Yayasan sebagai bahan audit dan pengawasan.</p>
                        <i class="fas fa-list-check fitur-icon"></i>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="section" id="peran">
            <div class="section-inner">
                <p class="section-label">Hak Akses</p>
                <h2 class="section-title">Peran Pengguna dalam SIMAS</h2>
                <p class="section-desc">
                    Sistem membagi akses ke dalam lima peran dengan tanggung jawab yang berbeda untuk menjaga keamanan dan akuntabilitas data.
                </p>
                <div class="role-grid">
                    <div class="role-card role-kepsek">
                        <div class="role-avatar"><i class="fas fa-user-tie"></i></div>
                        <h3>Kepala Yayasan</h3>
                        <p>Pemantauan seluruh aset dan laporan. Hanya membaca, tidak mengubah data.</p>
                    </div>
                    <div class="role-card role-adminut">
                        <div class="role-avatar"><i class="fas fa-user-shield"></i></div>
                        <h3>Admin Utama</h3>
                        <p>Akses penuh — kelola aset, pengguna, laporan, dan master data.</p>
                    </div>
                    <div class="role-card role-adminun">
                        <div class="role-avatar"><i class="fas fa-user-gear"></i></div>
                        <h3>Admin Unit</h3>
                        <p>Kelola aset dan laporan kerusakan di unitnya sendiri.</p>
                    </div>
                    <div class="role-card role-teknisi">
                        <div class="role-avatar"><i class="fas fa-wrench"></i></div>
                        <h3>Teknisi</h3>
                        <p>Terima dan proses laporan kerusakan yang ditugaskan. Catat tindakan dan biaya perbaikan.</p>
                    </div>
                    <div class="role-card role-user">
                        <div class="role-avatar"><i class="fas fa-user"></i></div>
                        <h3>Pengguna</h3>
                        <p>Lihat aset di unitnya dan buat laporan kerusakan.</p>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="section" id="panduan">
            <div class="section-inner" style="text-align:center;">
                <p class="section-label">Panduan Singkat</p>
                <h2 class="section-title">Cara Menggunakan SIMAS</h2>
                <p class="section-desc" style="margin:0 auto 40px;">
                    Empat langkah dasar untuk mulai menggunakan sistem.
                </p>
                <div class="alur-list">
                    <div class="alur-item">
                        <div class="alur-num">1</div>
                        <div class="alur-body">
                            <h3>Login dengan akun yang diberikan Admin</h3>
                            <p>Akun dibuat oleh Admin Utama. Masukkan username dan password, lalu klik tombol <em>Masuk</em>. Tidak ada fitur daftar mandiri.</p>
                        </div>
                    </div>
                    <div class="alur-item">
                        <div class="alur-num">2</div>
                        <div class="alur-body">
                            <h3>Navigasi ke modul yang sesuai peran Anda</h3>
                            <p>Sidebar menu menampilkan hanya menu yang bisa diakses oleh peran Anda. Pilih modul seperti <em>Aset</em>, <em>Perbaikan</em>, atau <em>Pengguna</em>.</p>
                        </div>
                    </div>
                    <div class="alur-item">
                        <div class="alur-num">3</div>
                        <div class="alur-body">
                            <h3>Lakukan pencatatan atau pelaporan</h3>
                            <p>Tambah aset baru, perbarui kondisi aset, atau buat laporan kerusakan lengkap dengan foto dan deskripsi kerusakan.</p>
                        </div>
                    </div>
                    <div class="alur-item">
                        <div class="alur-num">4</div>
                        <div class="alur-body">
                            <h3>Pantau status dan riwayat</h3>
                            <p>Setiap perubahan tersimpan otomatis dalam log aktivitas. Kepala Yayasan dan Admin Utama dapat memantau seluruh rekap dari dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <div class="cta-bottom">
            <div class="cta-box">
                <i class="fas fa-right-to-bracket big"></i>
                <h2>Siap menggunakan SIMAS?</h2>
                <p>Masuk dengan akun yang telah disiapkan oleh Admin Utama Anda.</p>
                <a href="<?php echo e(route('login')); ?>" class="btn-cta-big">
                    <i class="fas fa-right-to-bracket"></i>
                    Masuk ke Sistem
                </a>
            </div>
        </div>

    </main>

    <footer>
        &copy; <?php echo e(date('Y')); ?> SIMAS &mdash; Yayasan Ash-Shiddiiqi. Seluruh hak cipta dilindungi.
    </footer>

</body>
</html><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/welcome.blade.php ENDPATH**/ ?>