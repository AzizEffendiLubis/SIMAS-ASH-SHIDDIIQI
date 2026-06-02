<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>SIMAS – <?php echo $__env->yieldContent('title', 'Sistem Informasi Manajemen Aset'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ════════════════════════════════════════════════
           DESIGN TOKENS
        ════════════════════════════════════════════════ */
        :root {
            --primary:        #0C6638;
            --primary-dark:   #094d29;
            --primary-mid:    #118a4a;
            --primary-light:  #e6f4ec;
            --primary-xlight: #f0faf4;

            --accent:         #16a34a;
            --danger:         #dc2626;
            --danger-light:   #fef2f2;
            --warning:        #d97706;
            --warning-light:  #fffbeb;
            --info:           #0891b2;
            --info-light:     #ecfeff;

            --gray-50:  #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            --sidebar-w:   256px;
            --topbar-h:    64px;
            --radius-sm:   6px;
            --radius:      10px;
            --radius-lg:   14px;
            --radius-xl:   18px;

            --shadow-xs: 0 1px 2px rgba(0,0,0,.06);
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08), 0 2px 8px rgba(0,0,0,.06);
            --shadow:    0 2px 6px rgba(0,0,0,.07), 0 6px 24px rgba(0,0,0,.06);
            --shadow-lg: 0 8px 32px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.06);

            --transition: .18s cubic-bezier(.4,0,.2,1);
            --sidebar-transition: .28s cubic-bezier(.4,0,.2,1);
        }

        /* ════════════════════════════════════════════════
           RESET & BASE
        ════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; scroll-behavior: smooth; }
        html, body { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-100);
            color: var(--gray-800);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        a { text-decoration: none; color: inherit; }
        button, input, select, textarea { font-family: inherit; }
        img { display: block; max-width: 100%; }

        /* ════════════════════════════════════════════════
           SCROLLBAR (webkit)
        ════════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

        /* ════════════════════════════════════════════════
           SIDEBAR OVERLAY (mobile)
        ════════════════════════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,.55);
            z-index: 149;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            animation: fadeIn .2s ease;
        }
        .sidebar-overlay.open { display: block; }
        @keyframes fadeIn { from { opacity:0 } to { opacity:1 } }

        /* ════════════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 150;
            transition: transform var(--sidebar-transition);
            overflow: hidden;
        }

        /* ── Branding ── */
        .sidebar-brand {
            padding: 0 16px;
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            gap: 11px;
            border-bottom: 1px solid var(--gray-100);
            flex-shrink: 0;
        }
        .brand-logo {
            width: 38px; height: 38px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: var(--shadow-xs);
        }
        .brand-logo img { width: 100%; height: 100%; object-fit: cover; }
        .brand-text .name {
            font-size: 17px; font-weight: 800;
            color: var(--gray-800); letter-spacing: -.4px; line-height: 1;
        }
        .brand-text .sub {
            font-size: 10.5px; font-weight: 500;
            color: var(--gray-400); margin-top: 2px;
            text-transform: uppercase; letter-spacing: .6px;
        }

        /* ── Nav ── */
        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .nav-section-label {
            font-size: 9.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: var(--gray-400);
            padding: 12px 10px 5px;
            user-select: none;
        }
        .nav-section-label:first-child { padding-top: 4px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 11px;
            border-radius: var(--radius-sm);
            color: var(--gray-500);
            font-size: 13.5px;
            font-weight: 500;
            transition: background var(--transition), color var(--transition);
            margin-bottom: 1px;
            position: relative;
        }
        .nav-link .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 13.5px;
            flex-shrink: 0;
            transition: color var(--transition);
        }
        .nav-link .nav-text { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nav-link:hover {
            background: var(--gray-50);
            color: var(--gray-700);
        }
        .nav-link.active {
            background: var(--primary-xlight);
            color: var(--primary);
            font-weight: 600;
        }
        .nav-link.active .nav-icon { color: var(--primary); }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        /* ── Footer / Logout ── */
        .sidebar-footer {
            padding: 10px;
            border-top: 1px solid var(--gray-100);
            flex-shrink: 0;
        }
        /* User info mini card in sidebar footer */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            margin-bottom: 6px;
        }
        .sidebar-user .su-avatar {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: var(--primary);
            color: #fff;
            font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-user .su-info { min-width: 0; }
        .sidebar-user .su-name {
            font-size: 12.5px; font-weight: 600;
            color: var(--gray-700);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user .su-role {
            font-size: 11px;
            color: var(--gray-400);
            white-space: nowrap;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 11px;
            border-radius: var(--radius-sm);
            color: var(--danger);
            font-size: 13.5px; font-weight: 600;
            cursor: pointer;
            width: 100%;
            background: none; border: none;
            transition: background var(--transition);
            font-family: inherit;
        }
        .logout-btn:hover { background: var(--danger-light); }
        .logout-btn .nav-icon { width: 18px; text-align: center; font-size: 13.5px; }

        /* ════════════════════════════════════════════════
           TOPBAR
        ════════════════════════════════════════════════ */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            position: fixed;
            top: 0; left: var(--sidebar-w); right: 0;
            z-index: 100;
            transition: left var(--sidebar-transition);
            box-shadow: var(--shadow-xs);
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }

        .menu-toggle {
            display: none;
            width: 36px; height: 36px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-sm);
            cursor: pointer;
            align-items: center; justify-content: center;
            font-size: 15px; color: var(--gray-600);
            transition: background var(--transition);
            flex-shrink: 0;
        }
        .menu-toggle:hover { background: var(--gray-100); }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar-breadcrumb .page-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-800);
        }
        .topbar-breadcrumb .separator {
            color: var(--gray-300);
            font-size: 13px;
        }
        .topbar-breadcrumb .parent-title {
            font-size: 13px;
            color: var(--gray-400);
            font-weight: 500;
        }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        /* Notification bell (placeholder, extensible) */
        .topbar-action {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: var(--gray-500);
            cursor: pointer;
            transition: background var(--transition), color var(--transition);
            position: relative;
        }
        .topbar-action:hover { background: var(--primary-light); color: var(--primary); }

        /* Topbar user pill */
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 12px 5px 6px;
            border-radius: var(--radius);
            border: 1px solid var(--gray-200);
            background: var(--gray-50);
            cursor: default;
            transition: background var(--transition);
        }
        .topbar-user:hover { background: var(--gray-100); }
        .tu-avatar {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: var(--primary);
            color: #fff; font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .tu-info .tu-name {
            font-size: 12.5px; font-weight: 600;
            color: var(--gray-700);
            max-width: 130px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            line-height: 1.2;
        }
        .tu-info .tu-role {
            font-size: 11px; color: var(--gray-400);
            white-space: nowrap;
        }

        /* ════════════════════════════════════════════════
           MAIN WRAPPER & CONTENT
        ════════════════════════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
            transition: margin-left var(--sidebar-transition);
        }
        .content {
            padding: 26px 24px;
            max-width: 1400px;
        }

        /* ════════════════════════════════════════════════
           PAGE HEADER
        ════════════════════════════════════════════════ */
        .page-header { margin-bottom: 22px; }
        .page-header h1 { font-size: 21px; font-weight: 800; color: var(--gray-800); line-height: 1.2; }
        .page-header p  { font-size: 13px; color: var(--gray-400); margin-top: 3px; font-weight: 400; }

        .page-header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 22px;
        }
        .page-header-row .ph-left h1 { font-size: 21px; font-weight: 800; color: var(--gray-800); }
        .page-header-row .ph-left p  { font-size: 13px; color: var(--gray-400); margin-top: 3px; }
        .page-header-row .ph-right   { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

        /* ════════════════════════════════════════════════
           CARDS
        ════════════════════════════════════════════════ */
        .card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .card-header h2 {
            font-size: 14.5px; font-weight: 700; color: var(--gray-700);
        }
        .card-body { padding: 20px; }
        .card-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--gray-100);
            background: var(--gray-50);
        }

        /* ════════════════════════════════════════════════
           STAT CARDS (dashboard)
        ════════════════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }
        .stat-card {
            background: #fff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            padding: 18px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow var(--transition), transform var(--transition);
        }
        .stat-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 19px; flex-shrink: 0;
        }
        .stat-icon.green   { background: #dcfce7; color: #16a34a; }
        .stat-icon.blue    { background: var(--primary-light); color: var(--primary); }
        .stat-icon.orange  { background: #fff7ed; color: #ea580c; }
        .stat-icon.red     { background: #fee2e2; color: #dc2626; }
        .stat-icon.purple  { background: #faf5ff; color: #9333ea; }
        .stat-icon.teal    { background: #f0fdfa; color: #0d9488; }
        .stat-icon.yellow  { background: #fefce8; color: #ca8a04; }
        .stat-body { min-width: 0; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--gray-800); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--gray-400); margin-top: 3px; font-weight: 500; }

        /* ════════════════════════════════════════════════
           BADGES — sesuai role & kondisi di models
        ════════════════════════════════════════════════ */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11.5px; font-weight: 600;
            white-space: nowrap; line-height: 1.4;
        }
        /* Role badges (User model: role_badge accessor) */
        .badge-admin    { background: #ede9fe; color: #7c3aed; }
        .badge-kepala   { background: #fff7ed; color: #c2410c; }
        .badge-unit     { background: #eff6ff; color: #2563eb; }
        .badge-teknisi  { background: #f0fdf4; color: #15803d; }
        .badge-user     { background: var(--gray-100); color: var(--gray-600); }
        /* Status badges */
        .badge-aktif    { background: #dcfce7; color: #15803d; }
        .badge-nonaktif { background: #fee2e2; color: #b91c1c; }
        /* Kondisi aset (Asset model: kondisi_badge accessor) */
        .badge-success  { background: #dcfce7; color: #15803d; }  /* aktif */
        .badge-danger   { background: #fee2e2; color: #b91c1c; }  /* rusak */
        .badge-warning  { background: #fef9c3; color: #a16207; }  /* hilang */
        .badge-secondary{ background: var(--gray-100); color: var(--gray-500); } /* habis_pakai */
        /* Repair status (Repair model: status_badge accessor) */
        .badge-info     { background: #e0f2fe; color: #0369a1; }  /* sedang_diperbaiki */
        /* pending → badge-warning, selesai → badge-success */

        /* ════════════════════════════════════════════════
           BUTTONS
        ════════════════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 17px;
            border-radius: var(--radius-sm);
            font-size: 13.5px; font-weight: 600;
            cursor: pointer; border: none;
            transition: background var(--transition), box-shadow var(--transition), transform var(--transition);
            white-space: nowrap; font-family: inherit;
            line-height: 1;
        }
        .btn:active { transform: scale(.97); }
        .btn i { font-size: 13px; }

        .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 1px 2px rgba(12,102,56,.3); }
        .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 2px 8px rgba(12,102,56,.35); }

        .btn-success { background: #16a34a; color: #fff; box-shadow: 0 1px 2px rgba(22,163,74,.3); }
        .btn-success:hover { background: #15803d; }

        .btn-danger  { background: var(--danger); color: #fff; box-shadow: 0 1px 2px rgba(220,38,38,.3); }
        .btn-danger:hover  { background: #b91c1c; }

        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { background: #b45309; }

        .btn-outline {
            background: #fff; color: var(--gray-600);
            border: 1.5px solid var(--gray-200);
            box-shadow: var(--shadow-xs);
        }
        .btn-outline:hover { background: var(--gray-50); border-color: var(--gray-300); }

        .btn-ghost { background: transparent; color: var(--gray-500); }
        .btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }

        .btn-sm   { padding: 6px 12px; font-size: 12px; gap: 5px; }
        .btn-sm i { font-size: 11px; }
        .btn-xs   { padding: 4px 9px;  font-size: 11.5px; gap: 4px; }

        .btn-icon {
            width: 34px; height: 34px; padding: 0;
            justify-content: center;
            border-radius: var(--radius-sm);
        }
        .btn-icon.btn-sm { width: 30px; height: 30px; }

        /* ════════════════════════════════════════════════
           FORMS
        ════════════════════════════════════════════════ */
        .form-group   { margin-bottom: 18px; }
        .form-label   {
            display: block;
            font-size: 13px; font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 6px;
        }
        .form-label .required { color: var(--danger); margin-left: 2px; }

        .form-control {
            width: 100%; padding: 10px 13px;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 13.5px; font-family: inherit;
            color: var(--gray-700);
            background: #fff;
            transition: border-color var(--transition), box-shadow var(--transition);
            outline: none;
            appearance: none;
        }
        .form-control::placeholder { color: var(--gray-300); }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(12,102,56,.12);
        }
        .form-control:disabled, .form-control[readonly] {
            background: var(--gray-50); color: var(--gray-400); cursor: not-allowed;
        }
        .form-control.is-invalid { border-color: var(--danger); }
        .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12); }

        select.form-control {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath fill='none' stroke='%2394a3b8' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M1 1l4.5 4.5L10 1'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }
        textarea.form-control { resize: vertical; min-height: 92px; line-height: 1.6; }

        .form-hint { font-size: 11.5px; color: var(--gray-400); margin-top: 5px; }
        .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .invalid-feedback::before { content: '\f06a'; font-family: 'Font Awesome 6 Free'; font-weight: 900; font-size: 11px; }

        /* Input with icon */
        .input-wrap { position: relative; }
        .input-wrap .input-icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400); font-size: 13px; pointer-events: none;
        }
        .input-wrap .form-control { padding-left: 36px; }

        /* Search bar */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400); font-size: 13px; pointer-events: none;
        }
        .search-wrap .form-control { padding-left: 36px; }

        /* Grids */
        .form-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .col-span-2  { grid-column: 1 / -1; }

        /* Fieldset style section divider */
        .form-section { margin-bottom: 24px; }
        .form-section-title {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .9px; color: var(--primary);
            padding-bottom: 9px; margin-bottom: 16px;
            border-bottom: 1.5px solid var(--primary-light);
        }

        /* Filter row */
        .filter-row {
            display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
        }
        .filter-row .search-wrap { flex: 1; min-width: 200px; }
        .filter-row select.form-control { min-width: 140px; width: auto; }
        .filter-row .form-control { height: 38px; padding-top: 0; padding-bottom: 0; }

        /* ════════════════════════════════════════════════
           ALERTS / FLASH
        ════════════════════════════════════════════════ */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 10px;
            animation: slideDown .22s ease;
        }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px) } to { opacity:1; transform:translateY(0) } }
        .alert i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-warning { background: var(--warning-light); border: 1px solid #fde68a; color: #92400e; }
        .alert-info    { background: var(--info-light); border: 1px solid #a5f3fc; color: #0e7490; }

        /* ════════════════════════════════════════════════
           TABLE
        ════════════════════════════════════════════════ */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--radius);
        }
        table {
            width: 100%; border-collapse: collapse;
            font-size: 13.5px; min-width: 580px;
        }
        thead th {
            padding: 11px 14px;
            text-align: left;
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .7px;
            color: var(--gray-400);
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            white-space: nowrap;
            user-select: none;
        }
        thead th:first-child { border-radius: var(--radius) 0 0 0; }
        thead th:last-child  { border-radius: 0 var(--radius) 0 0; }

        tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-600);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fbfcff; }

        /* ════════════════════════════════════════════════
           ACTIVITY FEED
        ════════════════════════════════════════════════ */
        .activity-list { }
        .activity-item {
            display: flex; align-items: flex-start; gap: 13px;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-100);
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }
        .activity-icon.asset      { background: var(--primary-light); color: var(--primary); }
        .activity-icon.repair     { background: #fef9c3; color: #ca8a04; }
        .activity-icon.repair-done{ background: #f0fdf4; color: #16a34a; }
        .activity-icon.user       { background: #ede9fe; color: #7c3aed; }
        .activity-icon.system     { background: var(--gray-100); color: var(--gray-500); }
        .activity-body { flex: 1; min-width: 0; }
        .activity-body .title {
            font-size: 13px; font-weight: 600; color: var(--gray-700);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .activity-body .meta {
            font-size: 11.5px; color: var(--gray-400); margin-top: 2px;
        }
        .activity-time { font-size: 11px; color: var(--gray-300); white-space: nowrap; flex-shrink: 0; margin-top: 2px; }

        /* ════════════════════════════════════════════════
           MODAL
        ════════════════════════════════════════════════ */
        .modal-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,.48);
            z-index: 300;
            align-items: center; justify-content: center;
            padding: 16px;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .modal-backdrop.open {
            display: flex;
            animation: fadeIn .18s ease;
        }
        .modal {
            background: #fff;
            border-radius: var(--radius-xl);
            width: 100%; max-width: 540px;
            box-shadow: var(--shadow-lg);
            max-height: 92vh; overflow-y: auto;
            animation: modalIn .22s cubic-bezier(.34,1.4,.64,1);
        }
        @keyframes modalIn {
            from { opacity:0; transform: scale(.94) translateY(12px) }
            to   { opacity:1; transform: scale(1) translateY(0) }
        }
        .modal-header {
            padding: 20px 24px 0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header h3 { font-size: 16px; font-weight: 700; color: var(--gray-800); }
        .modal-close {
            width: 30px; height: 30px;
            border: none; background: var(--gray-100);
            border-radius: var(--radius-sm); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: var(--gray-500);
            transition: background var(--transition);
        }
        .modal-close:hover { background: var(--gray-200); }
        .modal-body   { padding: 18px 24px; }
        .modal-footer { padding: 0 24px 20px; display: flex; justify-content: flex-end; gap: 8px; }
        .modal-divider { height: 1px; background: var(--gray-100); margin: 4px 0; }

        /* Confirm modal */
        .modal-confirm { max-width: 400px; }
        .confirm-icon {
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 24px;
        }
        .confirm-icon.danger  { background: #fee2e2; color: var(--danger); }
        .confirm-icon.warning { background: var(--warning-light); color: var(--warning); }

        /* ════════════════════════════════════════════════
           PAGINATION
        ════════════════════════════════════════════════ */
        .pagination {
            display: flex; gap: 4px; align-items: center;
            justify-content: flex-end; padding-top: 16px; flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500;
            text-decoration: none; color: var(--gray-600);
            border: 1px solid var(--gray-200); background: #fff;
            transition: background var(--transition), border-color var(--transition);
            line-height: 1.4;
        }
        .pagination a:hover { background: var(--gray-50); border-color: var(--gray-300); }
        .pagination .active  { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination .disabled{ color: var(--gray-300); pointer-events: none; }

        /* ════════════════════════════════════════════════
           SECTION TITLE (section divider inside cards)
        ════════════════════════════════════════════════ */
        .section-title {
            font-size: 10.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .9px; color: var(--primary);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid var(--primary-light);
        }

        /* ════════════════════════════════════════════════
           MISC UTILITIES
        ════════════════════════════════════════════════ */
        .text-muted   { color: var(--gray-400); }
        .text-danger  { color: var(--danger); }
        .text-success { color: #16a34a; }
        .text-primary { color: var(--primary); }
        .fw-600 { font-weight: 600; }
        .fw-700 { font-weight: 700; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .gap-8  { gap: 8px; }
        .gap-10 { gap: 10px; }
        .gap-12 { gap: 12px; }
        .mt-4   { margin-top: 4px; }
        .mt-8   { margin-top: 8px; }
        .mt-16  { margin-top: 16px; }
        .mb-16  { margin-bottom: 16px; }
        .mb-20  { margin-bottom: 20px; }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 48px 24px; color: var(--gray-400);
        }
        .empty-state i { font-size: 36px; margin-bottom: 12px; opacity: .5; }
        .empty-state p { font-size: 13.5px; }

        /* Avatar initial */
        .avatar {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: var(--radius-sm); background: var(--primary);
            color: #fff; font-weight: 700; flex-shrink: 0;
        }
        .avatar-sm { width: 28px; height: 28px; font-size: 11px; }
        .avatar-md { width: 36px; height: 36px; font-size: 14px; }
        .avatar-lg { width: 44px; height: 44px; font-size: 17px; }

        /* Kondisi dot indicator */
        .kondisi-dot {
            display: inline-flex; align-items: center; gap: 5px; font-size: 13px;
        }
        .kondisi-dot::before {
            content: ''; width: 7px; height: 7px; border-radius: 50%; display: block;
        }
        .kondisi-dot.aktif::before       { background: #16a34a; }
        .kondisi-dot.rusak::before        { background: var(--danger); }
        .kondisi-dot.hilang::before       { background: var(--warning); }
        .kondisi-dot.habis_pakai::before  { background: var(--gray-400); }

        /* Detail rows (show pages) */
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table tr td { padding: 9px 0; vertical-align: top; border-bottom: 1px solid var(--gray-100); font-size: 13.5px; }
        .detail-table tr:last-child td { border-bottom: none; }
        .detail-table .dt-label { color: var(--gray-400); font-weight: 600; font-size: 12px; width: 40%; padding-right: 16px; }
        .detail-table .dt-val   { color: var(--gray-700); font-weight: 500; }

        /* Two-column dashboard layout */
        .dash-two-col {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }

        /* ════════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════════ */

        /* ── Tablet (≤ 1024px) ── */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 220px; }
            .content { padding: 22px 20px; }
            .stats-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
            .dash-two-col { grid-template-columns: 1fr 1fr; }
        }

        /* ── Mobile (≤ 768px) ── */
        @media (max-width: 768px) {
            :root {
                --sidebar-w: 256px;   /* full overlay width on mobile */
                --topbar-h:  58px;
            }

            /* Sidebar: hidden off-canvas */
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }

            /* Topbar spans full width */
            .topbar { left: 0; padding: 0 14px; }
            .menu-toggle { display: flex; }
            .tu-info { display: none; }   /* hide name/role on mobile topbar */

            .main-wrapper { margin-left: 0; }
            .content { padding: 16px 14px; }

            /* Stats: 2-col */
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-card  { padding: 14px 12px; gap: 10px; }
            .stat-value { font-size: 22px; }
            .stat-icon  { width: 40px; height: 40px; font-size: 17px; }

            /* Forms: single column */
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }

            /* Dashboard 2-col → 1-col */
            .dash-two-col { grid-template-columns: 1fr; }

            /* Page header row stacks */
            .page-header-row { flex-direction: column; align-items: flex-start; }
            .page-header-row .ph-right { width: 100%; }

            /* Filters wrap */
            .filter-row { flex-direction: column; }
            .filter-row .search-wrap { min-width: 100%; width: 100%; }
            .filter-row select.form-control { width: 100%; }

            /* Table scroll */
            .table-wrap { margin: 0 -14px; padding: 0 14px; border-radius: 0; }

            /* Modal full width */
            .modal { border-radius: var(--radius-lg); max-width: 100%; }

            /* Topbar breadcrumb: hide parent */
            .topbar-breadcrumb .separator,
            .topbar-breadcrumb .parent-title { display: none; }
        }

        /* ── Small phones (≤ 400px) ── */
        @media (max-width: 400px) {
            .stats-grid { gap: 8px; }
            .stat-label { font-size: 11px; }
            .btn { font-size: 13px; padding: 8px 14px; }
        }

        /* ════════════════════════════════════════════════
           PRINT — hide nav chrome
        ════════════════════════════════════════════════ */
        @media print {
            .sidebar, .topbar, .sidebar-overlay { display: none !important; }
            .main-wrapper { margin-left: 0; padding-top: 0; }
            .content { padding: 0; }
            .card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>


<aside class="sidebar" id="sidebar">

    
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="<?php echo e(asset('images/10505884-5.jpg')); ?>" alt="Logo SIMAS">
        </div>
        <div class="brand-text">
            <div class="name">SIMAS</div>
            <div class="sub">Ash-Shiddiiqi</div>
        </div>
    </div>

    
    <nav class="sidebar-nav" role="navigation" aria-label="Menu utama">

        <div class="nav-section-label">Menu Utama</div>

        <?php if(auth()->user()->canAccess('dashboard')): ?>
        <a href="<?php echo e(route('dashboard')); ?>"
           class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
           aria-current="<?php echo e(request()->routeIs('dashboard') ? 'page' : 'false'); ?>">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('daftar_aset')): ?>
        <a href="<?php echo e(route('assets.index')); ?>"
           class="nav-link <?php echo e(request()->routeIs('assets.*') ? 'active' : ''); ?>">
            <span class="nav-icon"><i class="fas fa-boxes-stacked"></i></span>
            <span class="nav-text">Daftar Aset</span>
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('perbaikan_aset')): ?>
        <a href="<?php echo e(route('repairs.index')); ?>"
           class="nav-link <?php echo e(request()->routeIs('repairs.*') ? 'active' : ''); ?>">
            <span class="nav-icon"><i class="fas fa-screwdriver-wrench"></i></span>
            <span class="nav-text">Perbaikan Aset</span>
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('manajemen_pengguna') || auth()->user()->canAccess('log_aktivitas') || auth()->user()->canAccess('master_data')): ?>
        <div class="nav-section-label" style="margin-top:4px;">Administrasi</div>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('manajemen_pengguna')): ?>
        <a href="<?php echo e(route('users.index')); ?>"
           class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
            <span class="nav-icon"><i class="fas fa-users-gear"></i></span>
            <span class="nav-text">Manajemen Pengguna</span>
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('log_aktivitas')): ?>
        <a href="<?php echo e(route('activity-logs.index')); ?>"
           class="nav-link <?php echo e(request()->routeIs('activity-logs.*') ? 'active' : ''); ?>">
            <span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>
            <span class="nav-text">Log Aktivitas</span>
        </a>
        <?php endif; ?>

        <?php if(auth()->user()->canAccess('master_data')): ?>
        <a href="<?php echo e(route('masterdata.index')); ?>"
           class="nav-link <?php echo e(request()->routeIs('masterdata.*') ? 'active' : ''); ?>">
            <span class="nav-icon"><i class="fas fa-database"></i></span>
            <span class="nav-text">Master Data</span>
        </a>
        <?php endif; ?>

    </nav>

    
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="su-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
            <div class="su-info">
                <div class="su-name"><?php echo e(Str::limit(auth()->user()->name, 22)); ?></div>
                <div class="su-role"><?php echo e(auth()->user()->role_label); ?></div>
            </div>
        </div>

        <form action="<?php echo e(route('logout')); ?>" method="POST" id="logoutForm"><?php echo csrf_field(); ?>
            <button type="button" class="logout-btn" onclick="openModal('logoutModal')" aria-label="Log out">
                <span class="nav-icon"><i class="fas fa-right-from-bracket"></i></span>
                Log Out
            </button>
        </form>
    </div>
</aside>


<div class="main-wrapper" id="mainWrapper">

    
    <header class="topbar" role="banner">
        <div class="topbar-left">
            <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()" aria-label="Toggle menu" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-breadcrumb">
                <span class="page-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></span>
                <?php if (! empty(trim($__env->yieldContent('page-parent')))): ?>
                    <span class="separator"><i class="fas fa-chevron-right" style="font-size:10px;"></i></span>
                    <span class="parent-title"><?php echo $__env->yieldContent('page-parent'); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="topbar-right">
            <div class="topbar-user" title="<?php echo e(auth()->user()->name); ?> — <?php echo e(auth()->user()->role_label); ?>">
                <div class="tu-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
                <div class="tu-info">
                    <div class="tu-name"><?php echo e(Str::limit(auth()->user()->name, 22)); ?></div>
                    <div class="tu-role"><?php echo e(auth()->user()->role_label); ?></div>
                </div>
            </div>
        </div>
    </header>

    
    <main class="content" role="main" id="mainContent">

        
        <?php if(session('success')): ?>
        <div class="alert alert-success" role="alert">
            <i class="fas fa-circle-check"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-error" role="alert">
            <i class="fas fa-circle-xmark"></i>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-circle-info"></i>
            <span><?php echo e(session('info')); ?></span>
        </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-triangle-exclamation"></i>
            <span><?php echo e(session('warning')); ?></span>
        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>

    </main>
</div>


<div class="modal-backdrop" id="logoutModal" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
    <div class="modal modal-confirm">
        <div class="modal-body" style="padding: 32px 28px; text-align: center;">
            <div class="confirm-icon warning">
                <i class="fas fa-right-from-bracket"></i>
            </div>
            <h3 id="logoutModalTitle" style="font-size: 17px; font-weight: 800; margin-bottom: 6px;">
                Keluar dari SIMAS?
            </h3>
            <p style="font-size: 13.5px; color: var(--gray-400); line-height: 1.6;">
                Sesi Anda akan diakhiri. Anda perlu login kembali untuk melanjutkan.
            </p>
            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 24px;">
                <button class="btn btn-outline" onclick="closeModal('logoutModal')">
                    <i class="fas fa-xmark"></i> Batal
                </button>
                <button class="btn btn-danger" onclick="document.getElementById('logoutForm').submit()">
                    <i class="fas fa-right-from-bracket"></i> Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</div>


<script>
/* ── Sidebar ─────────────────────────────────── */
const sidebar     = document.getElementById('sidebar');
const overlay     = document.getElementById('sidebarOverlay');
const menuToggle  = document.getElementById('menuToggle');

function toggleSidebar() {
    const open = sidebar.classList.toggle('open');
    overlay.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
    menuToggle.setAttribute('aria-expanded', open);
}
function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
    menuToggle.setAttribute('aria-expanded', 'false');
}

/* Close on resize to desktop */
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeSidebar();
});

/* Keyboard: Escape closes sidebar */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeSidebar();
        closeModal(document.querySelector('.modal-backdrop.open')?.id);
    }
});

/* ── Modals ──────────────────────────────────── */
function openModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.add('open');
        // Trap focus: focus first focusable element
        const focusable = el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusable.length) focusable[0].focus();
    }
}
function closeModal(id) {
    if (!id) return;
    const el = document.getElementById(id);
    if (el) el.classList.remove('open');
}

/* Close modal on backdrop click */
document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});

/* ── Flash auto-dismiss ──────────────────────── */
(function dismissAlerts() {
    const alerts = document.querySelectorAll('.alert');
    if (!alerts.length) return;
    setTimeout(() => {
        alerts.forEach(a => {
            a.style.transition = 'opacity .35s ease, max-height .35s ease, margin .35s ease, padding .35s ease';
            a.style.opacity = '0';
            a.style.maxHeight = '0';
            a.style.margin = '0';
            a.style.padding = '0';
            a.style.overflow = 'hidden';
            setTimeout(() => a.remove(), 380);
        });
    }, 4500);
})();
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/layouts/app.blade.php ENDPATH**/ ?>