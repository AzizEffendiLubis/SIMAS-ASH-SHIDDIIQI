<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>SIMAS - <?php echo $__env->yieldContent('title', 'Sistem Informasi Manajemen Aset'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #0C6638;
            --primary-dark: #09542e;
            --primary-light: #e6f4ec;
            --danger: #dc2626;
            --warning: #d97706;
            --info: #0891b2;
            --sidebar-w: 240px;
            --topbar-h: 60px;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
            --shadow-lg: 0 8px 32px rgba(0,0,0,.12);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; }

        /* ── Overlay (mobile) ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,.5); z-index: 99;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.open { display: block; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid #e2e8f0;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100vh;
            z-index: 100;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-logo {
            padding: 18px 16px 14px;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        .logo-icon {
            width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
            background: linear-gradient(135deg,#16a34a,#2563eb);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 15px; font-weight: 800;
        }
        .logo-text .brand { font-size: 20px; font-weight: 800; color: #1e293b; letter-spacing: -.5px; line-height: 1; }
        .logo-text .sub   { font-size: 10px; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }

        .sidebar-nav { flex: 1; padding: 14px 10px; overflow-y: auto; }
        .nav-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #cbd5e1; padding: 10px 8px 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: #64748b; font-size: 13.5px; font-weight: 500;
            text-decoration: none; transition: all .15s;
            margin-bottom: 2px;
        }
        .nav-item i { width: 16px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .nav-item:hover  { background: #f8fafc; color: #1e293b; }
        .nav-item.active {
            background: #0C6638; color: #fff;
            font-weight: 600;
        }
        .nav-item.active i {
            color: #ffffff;      
        }

        .sidebar-footer {
            padding: 10px; border-top: 1px solid #f1f5f9; flex-shrink: 0;
        }
        .logout-btn {
            display: flex; align-items: center; gap: 10px; padding: 10px 12px;
            border-radius: 8px; color: #ef4444; font-size: 13.5px; font-weight: 600;
            cursor: pointer; width: 100%; background: none; border: none;
            transition: background .15s; font-family: inherit;
        }
        .logout-btn:hover { background: #fef2f2; }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px; position: fixed;
            top: 0; left: var(--sidebar-w); right: 0;
            z-index: 50; transition: left .28s cubic-bezier(.4,0,.2,1);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .menu-toggle {
            display: none; width: 36px; height: 36px; border: none;
            background: #f8fafc; border-radius: 8px; cursor: pointer;
            align-items: center; justify-content: center;
            font-size: 16px; color: #64748b; border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .topbar-title { font-size: 16px; font-weight: 700; color: #1e293b; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 10px 5px 5px; border-radius: 10px; cursor: pointer;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--primary); display: flex; align-items: center;
            justify-content: center; color: #fff; font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }
        .user-info { line-height: 1.2; }
        .user-info .uname { font-size: 12.5px; font-weight: 600; color: #1e293b; white-space: nowrap; max-width: 120px; overflow: hidden; text-overflow: ellipsis; }
        .user-info .urole { font-size: 11px; color: #94a3b8; white-space: nowrap; }

        /* ── Main content ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left .28s cubic-bezier(.4,0,.2,1);
        }
        .content { padding: 24px 20px; }

        /* ── Page header ── */
        .page-header { margin-bottom: 20px; }
        .page-header h1 { font-size: 20px; font-weight: 800; color: #1e293b; }
        .page-header p  { font-size: 13px; color: #64748b; margin-top: 2px; }
        .page-header-row {
            display: flex; align-items: flex-start;
            justify-content: space-between; flex-wrap: wrap; gap: 12px;
            margin-bottom: 20px;
        }

        /* ── Cards ── */
        .card { background: #fff; border-radius: var(--radius); border: 1px solid #e2e8f0; box-shadow: var(--shadow); }
        .card-body { padding: 20px; }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px; margin-bottom: 20px;
        }
        .stat-card {
            background: #fff; border-radius: var(--radius);
            border: 1px solid #e2e8f0; padding: 16px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow);
        }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .stat-icon.blue { background:#e6f4ec; color:#0C6638;}
        .stat-icon.green  { background:#f0fdf4; color:#16a34a; }
        .stat-icon.orange { background:#fff7ed; color:#ea580c; }
        .stat-icon.purple { background:#faf5ff; color:#9333ea; }
        .stat-icon.teal   { background:#f0fdfa; color:#0d9488; }
        .stat-value { font-size: 24px; font-weight: 800; color: #1e293b; line-height: 1; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 2px; font-weight: 500; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
        .badge-admin   { background:#ede9fe; color:#7c3aed; }
        .badge-kepala  { background:#fff7ed; color:#c2410c; }
        .badge-unit    { background:#eff6ff; color:#2563eb; }
        .badge-teknisi { background:#f0fdf4; color:#15803d; }
        .badge-user    { background:#f1f5f9; color:#475569; }
        .badge-success { background:#dcfce7; color:#15803d; }
        .badge-warning { background:#fef9c3; color:#a16207; }
        .badge-danger  { background:#fee2e2; color:#b91c1c; }
        .badge-info    { background:#e0f2fe; color:#0369a1; }
        .badge-aktif   { background:#dcfce7; color:#15803d; }
        .badge-nonaktif{ background:#fee2e2; color:#b91c1c; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 8px; font-size: 13.5px;
            font-weight: 600; cursor: pointer; border: none;
            text-decoration: none; transition: all .15s; white-space: nowrap;
            font-family: inherit;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger  { background: #dc2626; color: #fff; }
        .btn-danger:hover  { background: #b91c1c; }
        .btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
        .btn-outline:hover { background: #f9fafb; }
        .btn-sm   { padding: 5px 10px; font-size: 12px; }
        .btn-icon { width: 32px; height: 32px; padding: 0; justify-content: center; border-radius: 7px; }

        /* ── Forms ── */
        .form-group  { margin-bottom: 16px; }
        .form-label  { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .form-control {
            width: 100%; padding: 10px 13px;
            border: 1.5px solid #d1d5db; border-radius: 8px;
            font-size: 13.5px; font-family: inherit; color: #1e293b;
            background: #fff; transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .form-control.is-invalid { border-color: var(--danger); }
        select.form-control { cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394a3b8' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 13px center; padding-right: 36px;
        }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-hint { font-size: 11.5px; color: #94a3b8; margin-top: 4px; }
        .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 4px; }

        /* ── Grid helpers ── */
        .form-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .col-span-2  { grid-column: 1 / -1; }

        /* ── Search bar ── */
        .search-wrap { position: relative; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; pointer-events: none; }
        .search-wrap .form-control { padding-left: 36px; }

        /* ── Filters row ── */
        .filter-row {
            display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
        }
        .filter-row .search-wrap { flex: 1; min-width: 180px; }
        .filter-row select.form-control { min-width: 140px; width: auto; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: 8px; font-size: 13.5px;
            margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
        .alert-error   { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 600px; }
        thead th {
            padding: 11px 14px; text-align: left; font-size: 11px;
            font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
            color: #94a3b8; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafbff; }

        /* ── Activity items ── */
        .activity-item { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid #f1f5f9; }
        .activity-item:last-child { border-bottom:none; }
        .activity-icon { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .activity-icon.procurement { background:#eff6ff; color:var(--primary); }
        .activity-icon.repair      { background:#fef9c3; color:#ca8a04; }
        .activity-icon.repair-done { background:#f0fdf4; color:#16a34a; }
        .activity-meta .title { font-size:13px; font-weight:600; color:#1e293b; }
        .activity-meta .sub   { font-size:12px; color:#94a3b8; margin-top:1px; }

        /* ── Modal ── */
        .modal-backdrop { display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); z-index:200; align-items:center; justify-content:center; padding:16px; }
        .modal-backdrop.open { display:flex; }
        .modal { background:#fff; border-radius:16px; width:100%; max-width:520px; box-shadow:var(--shadow-lg); max-height:90vh; overflow-y:auto; }
        .modal-header { padding:20px 24px 0; display:flex; justify-content:space-between; align-items:center; }
        .modal-header h3 { font-size:16px; font-weight:700; }
        .modal-close { width:28px; height:28px; border:none; background:#f1f5f9; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:13px; color:#64748b; }
        .modal-body   { padding:18px 24px; }
        .modal-footer { padding:0 24px 20px; display:flex; justify-content:flex-end; gap:10px; }

        /* ── Confirm modal ── */
        .confirm-modal { max-width:380px; }
        .confirm-icon  { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:22px; }

        /* ── Pagination ── */
        .pagination { display:flex; gap:4px; align-items:center; justify-content:flex-end; padding-top:14px; flex-wrap:wrap; }
        .pagination a, .pagination span { padding:6px 11px; border-radius:7px; font-size:13px; font-weight:500; text-decoration:none; color:#374151; border:1px solid #e2e8f0; background:#fff; }
        .pagination a:hover { background:#f1f5f9; }
        .pagination .active  { background:var(--primary); color:#fff; border-color:var(--primary); }
        .pagination .disabled{ color:#cbd5e1; }

        /* ── Section title ── */
        .section-title {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .8px; color: var(--primary);
            margin-bottom: 14px; margin-top: 4px;
            padding-bottom: 8px; border-bottom: 1.5px solid #eff6ff;
        }

        /* ════════════════════════════════════
           RESPONSIVE — Mobile first
        ════════════════════════════════════ */
        @media (max-width: 768px) {
            :root { --sidebar-w: 240px; }

            /* Sidebar hidden off-screen on mobile */
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }

            /* Topbar spans full width */
            .topbar { left: 0; padding: 0 14px; }
            .menu-toggle { display: flex; }
            .user-info { display: none; }   /* hide name on small screens */

            /* Main shifts right only when sidebar pushes */
            .main-wrapper { margin-left: 0; }

            /* Content padding tighter */
            .content { padding: 16px 14px; }

            /* Stats: 2 columns on mobile */
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-card  { padding: 12px; gap: 10px; }
            .stat-value { font-size: 20px; }

            /* Forms: single column */
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }

            /* Dashboard 2-col → 1-col */
            .dash-two-col { grid-template-columns: 1fr !important; }

            /* Page header row stack */
            .page-header-row { flex-direction: column; align-items: flex-start; }

            /* Filter row wrap */
            .filter-row .search-wrap { min-width: 100%; }
            .filter-row select.form-control { width: 100%; }

            /* Table: allow horizontal scroll */
            .table-wrap { margin: 0 -20px; padding: 0 20px; }

            /* Modal full-width */
            .modal { max-width: 100%; border-radius: 12px; }
        }

        @media (max-width: 400px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stat-label { font-size: 11px; }
        }
        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 5px;
            display: block;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>


<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="<?php echo e(asset('images/10505884-5.jpg')); ?>" alt="10505884-5.jpg" class="logo-img">
        <div class="logo-text">
            <div class="brand">SIMAS</div>
            <div class="sub">Ash-Shiddiiqi</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <?php if(auth()->user()->canAccess('dashboard')): ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('daftar_aset')): ?>
        <a href="<?php echo e(route('assets.index')); ?>" class="nav-item <?php echo e(request()->routeIs('assets.*') ? 'active' : ''); ?>">
            <i class="fas fa-boxes-stacked"></i> Daftar Aset
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('pengadaan_aset')): ?>
        <a href="<?php echo e(route('procurements.index')); ?>" class="nav-item <?php echo e(request()->routeIs('procurements.*') ? 'active' : ''); ?>">
            <i class="fas fa-cart-plus"></i> Pengadaan Aset
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('perbaikan_aset')): ?>
        <a href="<?php echo e(route('repairs.index')); ?>" class="nav-item <?php echo e(request()->routeIs('repairs.*') ? 'active' : ''); ?>">
            <i class="fas fa-wrench"></i> Perbaikan Aset
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('manajemen_pengguna')): ?>
        <div class="nav-label" style="margin-top:6px;">Administrasi</div>
        <a href="<?php echo e(route('users.index')); ?>" class="nav-item <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
            <i class="fas fa-users-gear"></i> Manajemen Pengguna
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <form action="<?php echo e(route('logout')); ?>" method="POST" id="logoutForm"><?php echo csrf_field(); ?>
            <button type="button" class="logout-btn" onclick="openModal('logoutModal')">
                <i class="fas fa-right-from-bracket"></i> Log Out
            </button>
        </form>
    </div>
</aside>


<div class="main-wrapper" id="mainWrapper">

    
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <span class="topbar-title"><?php echo $__env->yieldContent('page-title','Dashboard'); ?></span>
        </div>
        <div class="topbar-right">
            <div class="topbar-user">
                <div class="user-avatar"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></div>
                <div class="user-info">
                    <div class="uname"><?php echo e(Str::limit(auth()->user()->name,22)); ?></div>
                    <div class="urole"><?php echo e(auth()->user()->role_label); ?></div>
                </div>
            </div>
        </div>
    </header>

    
    <main class="content">
        <?php if(session('success')): ?>
        <div class="alert alert-success"><i class="fas fa-circle-check"></i> <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-error"><i class="fas fa-circle-xmark"></i> <?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>


<div class="modal-backdrop" id="logoutModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="confirm-icon" style="background:#fff7ed;color:#ea580c;">
                <i class="fas fa-right-from-bracket"></i>
            </div>
            <h3 style="font-size:17px;font-weight:700;margin-bottom:6px;">Log Out</h3>
            <p style="font-size:13.5px;color:#64748b;">Apakah Anda yakin ingin keluar?</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <button class="btn btn-danger" onclick="document.getElementById('logoutForm').submit()">
                    <i class="fas fa-right-from-bracket"></i> Ya, Keluar
                </button>
                <button class="btn btn-outline" onclick="closeModal('logoutModal')">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Sidebar toggle ──────────────────────────────
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// ── Modal helpers ───────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

// Close modal on backdrop click
document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});

// Close sidebar on resize to desktop
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeSidebar();
});

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity .4s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 400);
    });
}, 4000);
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Acer0\Ash-Shidddiqi\resources\views/layouts/app.blade.php ENDPATH**/ ?>