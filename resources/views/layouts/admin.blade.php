<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}?v=12345">

    <title>{{ $title ?? 'Gym Management' }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #111;
            color: #fff;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: #0b0b0b;
            border-right: 1px solid #292929;
            padding: 25px 15px;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo h1 {
            color: #e50914;
            font-size: 27px;
            font-weight: bold;
        }

        .logo span {
            color: #fff;
            font-size: 12px;
            letter-spacing: 3px;
        }

        .menu-title {
            color: #777;
            font-size: 11px;
            margin: 20px 15px 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            text-decoration: none;
            color: #aaa;
            padding: 13px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #e50914;
            color: #fff;
        }

        .menu-icon {
            width: 25px;
            flex:0 0 25px;
            display:grid;
            place-items:center;
            color:#ff4651;
            text-align: center;
            font-size: 18px;
        }
        .menu-icon svg { width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round; }

        /* MAIN */
        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            height: 75px;
            background: #151515;
            border-bottom: 1px solid #292929;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .page-title {
            font-size: 22px;
            font-weight: bold;
        }

        .page-title span {
            color: #e50914;
        }

        .admin {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background: #e50914;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .admin-info small {
            display: block;
            color: #777;
            font-size: 11px;
        }

        /* CONTENT */
        .content {
            padding: 30px;
        }

        .welcome {
            background: linear-gradient(135deg, #1d1d1d, #100000);
            border: 1px solid #3a1616;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .welcome h2 {
            font-size: 25px;
            margin-bottom: 8px;
        }

        .welcome h2 span {
            color: #e50914;
        }

        .welcome p {
            color: #888;
        }

        .home-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 11px 16px;
            border-radius: 8px;
            background: #e50914;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }

        .home-button:hover {
            background: #b20710;
        }

        /* STAT CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: #181818;
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 22px;
            transition: 0.2s;
        }

        .card:hover {
            border-color: #e50914;
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            background: #2a0c0c;
            color: #e50914;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .card h3 {
            margin-top: 18px;
            font-size: 28px;
        }

        .card p {
            color: #888;
            margin-top: 5px;
            font-size: 13px;
        }

        /* LOWER */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .panel {
            background: #181818;
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 22px;
        }

        .panel h3 {
            margin-bottom: 20px;
        }

        .revenue-chart {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            align-items: end;
            gap: 12px;
            height: 250px;
            padding-top: 12px;
        }

        .revenue-chart-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            min-width: 0;
            height: 100%;
        }

        .revenue-chart-value {
            color: #aaa;
            font-size: 11px;
            text-align: center;
        }

        .revenue-chart-track {
            display: flex;
            align-items: end;
            width: min(100%, 48px);
            height: 175px;
            overflow: hidden;
            background: #252525;
            border-radius: 7px 7px 3px 3px;
        }

        .revenue-chart-bar {
            width: 100%;
            min-height: 3px;
            background: linear-gradient(180deg, #ff3943, #b20710);
            border-radius: 7px 7px 3px 3px;
            transition: height 0.3s ease;
        }

        .revenue-chart-label {
            color: #aaa;
            font-size: 12px;
            white-space: nowrap;
        }

        .chart {
            height: 230px;
            display: flex;
            align-items: end;
            justify-content: space-around;
            padding-top: 20px;
        }

        .bar {
            width: 35px;
            background: #e50914;
            border-radius: 5px 5px 0 0;
        }

        .bar:nth-child(1) {
            height: 35%;
        }

        .bar:nth-child(2) {
            height: 55%;
        }

        .bar:nth-child(3) {
            height: 45%;
        }

        .bar:nth-child(4) {
            height: 75%;
        }

        .bar:nth-child(5) {
            height: 60%;
        }

        .bar:nth-child(6) {
            height: 90%;
        }

        .bar:nth-child(7) {
            height: 70%;
        }

        .activity {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #292929;
        }

        .activity:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 9px;
            height: 9px;
            background: #e50914;
            border-radius: 50%;
        }

        .activity p {
            font-size: 13px;
        }

        .activity small {
            color: #777;
        }

        .logout-btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            background: #e50914;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }

        .logout-btn:hover {
            background: #b20710;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 70px;
            }

            .logo h1,
            .logo span,
            .menu-title,
            .menu a span {
                display: none;
            }

            .menu a {
                justify-content: center;
            }

            .main {
                margin-left: 70px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .revenue-chart {
                gap: 6px;
            }

            .revenue-chart-value {
                font-size: 9px;
            }

            .revenue-chart-label {
                font-size: 10px;
            }

            .welcome {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    
/* =========================================================
   GYMFIT DESIGN SYSTEM — shared visual layer
   Applies consistently to every page using the three layouts.
   ========================================================= */
:root {
    --gym-bg: #0b0d10;
    --gym-surface: #12161c;
    --gym-surface-2: #181e26;
    --gym-surface-3: #202833;
    --gym-border: rgba(255,255,255,.085);
    --gym-text: #f4f6f8;
    --gym-muted: #9aa4b2;
    --gym-red: #e11d2e;
    --gym-red-dark: #b91424;
    --gym-red-soft: rgba(225,29,46,.13);
    --gym-radius: 16px;
    --gym-shadow: 0 14px 36px rgba(0,0,0,.22);
}
html { scroll-behavior: smooth; }
body { color: var(--gym-text); background: var(--gym-bg); }
::selection { background: rgba(225,29,46,.35); color: #fff; }

/* Consistent surfaces, without forcing a new structure on existing Blade views. */
.card, .stat-card, .dashboard-card, .content-card, .form-card,
.package-card, .trainer-card, .class-card, .payment-card,
.member-card, .info-card, .section-card, .table-container,
.table-wrapper, .chart-card, .profile-card, .detail-card {
    border: 1px solid var(--gym-border);
    border-radius: var(--gym-radius);
    box-shadow: var(--gym-shadow);
}

/* Typography */
h1, h2, h3, h4 { color: var(--gym-text); letter-spacing: -.025em; }
p, .description, .text-muted, small { color: inherit; }
.page-heading, .section-title { letter-spacing: -.03em; }

/* Form controls */
input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
select, textarea {
    max-width: 100%;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 10px;
    background: #171719;
    color: var(--gym-text);
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
}
input:not([type="checkbox"]):not([type="radio"]):focus,
select:focus, textarea:focus {
    outline: none;
    border-color: rgba(225,29,46,.8);
    box-shadow: 0 0 0 3px rgba(225,29,46,.14);
}
input::placeholder, textarea::placeholder { color: #778292; }
label { color: #d7dce3; }

/* Buttons — preserve existing classes and actions. */
button, .btn, [class*="-btn"] { transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease; }
button:not(:disabled):hover, .btn:hover, [class*="-btn"]:hover { filter: brightness(1.07); }
button:focus-visible, a:focus-visible { outline: 2px solid #ff6470; outline-offset: 3px; }

/* Tables */
table { border-collapse: separate; border-spacing: 0; }
thead th { color: #cbd2dc; font-weight: 700; letter-spacing: .035em; }
tbody tr { transition: background .16s ease; }
tbody tr:hover { background: rgba(255,255,255,.035); }
td, th { border-color: var(--gym-border) !important; }

/* Links and badges */
a { transition: color .18s ease, opacity .18s ease; }
.badge, .status, [class*="badge-"] { letter-spacing: .01em; }

/* Admin shell */
.sidebar { background: linear-gradient(180deg, #101012 0%, #09090b 100%) !important; border-right-color: var(--gym-border) !important; }
.sidebar .logo h1 { letter-spacing: .08em; }
.sidebar .menu a { border: 1px solid transparent; border-radius: 11px !important; }
.sidebar .menu a:hover { background: rgba(225,29,46,.10) !important; border-color: rgba(225,29,46,.2); color: #fff !important; }
.sidebar .menu a.active { background: linear-gradient(110deg, var(--gym-red), var(--gym-red-dark)) !important; box-shadow: 0 7px 18px rgba(225,29,46,.2); }
.topbar { background: rgba(12,12,14,.96) !important; border-bottom-color: var(--gym-border) !important; backdrop-filter: blur(14px); }
.content { padding-top: 28px; }

/* Public/member navigation */
.user-header, .member-header { background: rgba(12,15,19,.94) !important; border-bottom: 1px solid var(--gym-border) !important; backdrop-filter: blur(16px); }
.user-logo, .member-logo { letter-spacing: .04em; }
.user-menu a, .member-menu a { position: relative; }
.user-menu a.active, .member-menu a.active { color: #fff !important; }
.user-menu a.active::after, .member-menu a.active::after { content: ""; position: absolute; left: 12px; right: 12px; bottom: -7px; height: 2px; border-radius: 3px; background: var(--gym-red); }
.user-content, .member-content { min-height: 65vh; }
.user-footer { border-top: 1px solid var(--gym-border); }

/* Scrollbar */
::-webkit-scrollbar { width: 9px; height: 9px; }
::-webkit-scrollbar-track { background: #090b0e; }
::-webkit-scrollbar-thumb { background: #343c48; border: 2px solid #090b0e; border-radius: 20px; }
::-webkit-scrollbar-thumb:hover { background: var(--gym-red); }

@media (max-width: 900px) {
    .content { padding: 20px 16px !important; }
    .topbar { padding-left: 18px !important; padding-right: 18px !important; }
}
@media (max-width: 640px) {
    .content { padding: 16px 12px !important; }
    .topbar { min-height: 64px; height: auto !important; gap: 12px; }
    .user-content, .member-content { padding-left: 12px; padding-right: 12px; }
    table { min-width: 620px; }
}

    
        /* GYMFIT ADMIN UI: consistent controls and readable scale */
        .content { padding: 34px 38px; }
        .content > * { max-width: none; }
        .content button:not(.logout-btn), .content a[class*="btn"], .content a[class*="button"],
        .content input[type="submit"], .content button[type="submit"] {
            min-height: 40px;
            padding: 10px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
        }
        .content input[type="text"], .content input[type="search"], .content input[type="email"],
        .content input[type="number"], .content input[type="date"], .content select, .content textarea {
            min-height: 40px;
            background-color: #191919 !important;
            color: #f5f5f5 !important;
            border: 1px solid #383838 !important;
            border-radius: 8px !important;
            outline: none;
        }
        .content input:focus, .content select:focus, .content textarea:focus {
            border-color: #e50914 !important;
            box-shadow: 0 0 0 3px rgba(229,9,20,.12);
        }
        .content input::placeholder, .content textarea::placeholder { color: #888 !important; }
        .content table th { font-size: 13px; }
        .content table td { font-size: 14px; }
        .content table th, .content table td { padding-top: 14px; padding-bottom: 14px; }
        .content form[role="search"] input, .content input[type="search"] {
            padding-left: 38px !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23e50914' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='7'/%3E%3Cpath d='m20 20-4-4'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: 12px center !important;
        }
        .content .search-form input, .content .pt-search input, .content .search-box input {
            background-color: #191919 !important;
            color: #fff !important;
            padding-left: 38px !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23e50914' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='7'/%3E%3Cpath d='m20 20-4-4'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: 12px center !important;
        }
        .content .actions button, .content .actions a, .content .pt-actions button,
        .content .pt-actions a, .content .class-actions button, .content .class-actions a,
        .content td form button {
            min-height: 38px;
            min-width: 66px;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
        }
        .content .search-btn, .content .pt-btn, .content .class-btn,
        .content .btn, .content .button, .content .btn-primary,
        .content a.btn, .content button.btn {
            min-height: 40px;
            padding: 10px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 750;
        }
        .content .btn-edit, .content .class-btn-edit, .content .pt-edit,
        .content a[class*="edit"], .content button[class*="edit"] {
            min-width: 68px;
        }
        .content .btn-delete, .content .class-btn-delete, .content .pt-danger,
        .content a[class*="delete"], .content button[class*="delete"] {
            min-width: 68px;
        }
        @media (max-width: 900px) { .content { padding: 22px 18px; } }
        @media (max-width: 600px) { .content { padding: 16px 12px; } }
    
/* GYMFIT ADMIN — unified action controls */
.content button, .content input[type="submit"], .content a.btn,
.content .search-btn, .content .pt-btn, .content .class-btn,
.content .btn, .content .button, .content .btn-primary,
.content .btn-edit, .content .btn-delete, .content .class-btn-edit,
.content .class-btn-delete, .content .pt-edit, .content .pt-danger,
.content a[class*="edit"], .content button[class*="edit"],
.content a[class*="delete"], .content button[class*="delete"] {
    min-height:42px !important;
    min-width:78px;
    padding:10px 18px !important;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    border-radius:9px !important;
    font-size:13px !important;
    line-height:1.2;
    font-weight:750 !important;
    white-space:nowrap;
    box-sizing:border-box;
}
.content .search-btn, .content button.search-btn,
.content .pt-btn:not(.pt-danger), .content button[type="submit"].search-btn {
    min-width:92px;
}
.content .search-btn::before, .content .pt-btn:not(.pt-danger)::before {
    content:"";
    width:13px; height:13px; flex:0 0 13px;
    display:inline-block;
    border:2px solid currentColor;
    border-radius:50%;
    box-sizing:border-box;
    transform:scale(.82);
    background:linear-gradient(currentColor,currentColor) no-repeat 9px 9px / 6px 2px;
}
.content .delete-btn, .content .btn-delete, .content .class-btn-delete,
.content .pt-danger, .content button[class*="delete"], .content a[class*="delete"] {
    min-width:78px !important;
}
.content .action-buttons, .content .actions, .content .table-actions {
    display:flex; align-items:center; gap:9px; flex-wrap:wrap;
}
.content table td { vertical-align:middle; }
.content button:focus-visible, .content a:focus-visible {
    outline:2px solid #ff6670; outline-offset:2px;
}
@media(max-width:700px) {
    .content button, .content input[type="submit"], .content a.btn,
    .content .search-btn, .content .pt-btn, .content .class-btn {
        min-height:40px !important; min-width:72px; padding:9px 14px !important;
    }
}
\n    
/* GYMFIT TYPOGRAPHY + NAV CONSISTENCY PATCH */
html, body { font-family: "Segoe UI", Roboto, "Noto Sans", Arial, sans-serif !important; }
body, body *:not(i):not([class*="icon"]):not(svg):not(svg *) { font-family: "Segoe UI", Roboto, "Noto Sans", Arial, sans-serif; }
input, button, select, textarea { font: inherit; }
.user-header, .member-header { min-height: 72px; height: 72px; padding-left: clamp(18px, 2.5vw, 42px); padding-right: clamp(18px, 2.5vw, 42px); }
.user-menu, .member-menu { display:flex; align-items:center; justify-content:center; gap:clamp(14px, 1.7vw, 27px); flex:1 1 auto; min-width:0; }
.user-menu a, .member-menu a { white-space:nowrap; font-size:13px; line-height:1.2; font-weight:650; letter-spacing:.01em; }
.user-menu a.active::after, .member-menu a.active::after { bottom:-25px; height:2px; }
.user-logo, .member-logo { flex:0 0 auto; font-size:26px; }
.user-login, .member-login { flex:0 0 auto; gap:9px; }
@media(max-width:1050px) { .user-header, .member-header { padding-left:16px; padding-right:16px; } .user-menu, .member-menu { gap:11px; } .user-menu a, .member-menu a { font-size:11px; } }
@media(max-width:760px) { .user-header, .member-header { height:auto; min-height:68px; flex-wrap:wrap; gap:10px; padding-top:10px; padding-bottom:10px; } .user-menu, .member-menu { order:3; flex:1 0 100%; justify-content:flex-start; overflow-x:auto; padding:5px 0; } .user-menu a.active::after, .member-menu a.active::after { bottom:-5px; } }
</style>
    <link rel="stylesheet" href="{{ asset('css/gymfit-design-system.css') }}?v=2">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <h1>GYM</h1>
            <span>MANAGEMENT</span>
        </div>

        <div class="menu">

            <div class="menu-title">Tổng quan</div>

            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg></div>
                <span>Dashboard</span>
            </a>

            <div class="menu-title">Quản lý</div>

            <a href="/admin/members">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div>
                <span>Thành viên</span>
            </a>

            <a href="/admin/packages">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/></svg></div>
                <span>Gói tập</span>
            </a>
            <a href="/admin/pt-packages" class="menu-item">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9v6m3-9v12m12-12v12m3-9v6M6 12h12"/></svg></div>
                <span>Gói PT</span>
            </a>
            <a href="/admin/classes" class="menu-item">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 21V5l9-3 9 3v16M8 8h2m4 0h2M8 12h2m4 0h2M10 21v-5h4v5"/></svg></div>
                <span>Lớp tập</span>
            </a>
            <a href="/admin/trainers">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9v6m3-9v12m12-12v12m3-9v6M6 12h12"/></svg></div>
                <span>Huấn luyện viên</span>
            </a>

            <a href="/admin/schedules">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 14h3"/></svg></div>
                <span>Lịch tập</span>
            </a>

            <div class="menu-title">Hệ thống</div>

            <a href="/admin/payments">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M5 9h1m12 6h1"/></svg></div>
                <span>Thanh toán</span>
            </a>

            <a href="/admin/statistics">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10h4v10M10 20V4h4v16M16 20v-7h4v7"/></svg></div>
                <span>Thống kê</span>
            </a>

            <a href="{{ url('/admin/password-resets') }}" class="sidebar-link">
                <span class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="8" cy="15" r="5"/><path d="m12 11 9-9m-4 4 3 3m-6-1 3 3"/></svg></span>
                <span>Cấp lại mật khẩu</span>
            </a>

            <a href="/admin/contacts" class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v12H8l-4 4z"/><path d="M8 9h8M8 13h5"/></svg></div>
                <span>Liên hệ</span>
            </a>

            <a href="/admin/settings">
                <div class="menu-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.6a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.6-1.4-2.4 1.4-1.1a8 8 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.6a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.6 1.4 2.4-1.4 1.1a8 8 0 0 1 0 2z"/></svg></div>
                <span>Cài đặt</span>
            </a>
            <form action="/logout" method="POST" style="margin-top: 20px;"
                data-confirm-submit="Bạn có chắc chắn muốn đăng xuất không?">
                @csrf

                <button type="submit" class="logout-btn">
                    ĐĂNG XUẤT
                </button>
            </form>




        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="page-title">
                <span>Gym</span> Dashboard
            </div>

            <div class="admin">

                <div class="admin-avatar">
                    A
                </div>

                <div class="admin-info">
                    <strong>Admin</strong>
                    <small>Quản trị viên</small>
                </div>

            </div>

        </header>


        <!-- CONTENT -->
        <section class="content">

            @yield('content')

        </section>

    </main>

    @include('layouts.partials.gym-modal')

</body>

</html>
