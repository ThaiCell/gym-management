<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GYM MANAGEMENT')</title>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height:100vh;
            display:flex;
            flex-direction:column;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #0b0b0b;
            color: #fff;
            line-height: 1.5;
        }

        button,
        input,
        textarea,
        select {
            font-family: inherit;
        }

        a {
            text-decoration: none;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        a,
        button,
        input,
        textarea,
        select {
            font-family: inherit;
        }

        /* =========================================================
           HEADER - ĐỒNG BỘ VỚI user.blade.php
        ========================================================= */

        .member-header {
            height: 75px;
            background: #080808;
            border-bottom: 1px solid #252525;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 60px;

            position: fixed;
            top: 0;
            z-index: 1000;
            width: 100%;
            left: 0;
            right: 0;
        }

        .member-logo {
            font-size: 27px;
            font-weight: 900;
            color: #fff;
            letter-spacing: 1px;
            flex-shrink: 0;
        }

        .member-logo span {
            color: #e50914;
        }

        /* MENU */

        .member-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .member-menu a {
            color: #ddd;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            transition: 0.3s;
        }

        .member-menu a:hover {
            color: #e50914;
        }

        .member-menu a.active {
            color: #e50914;
        }

        .member-menu a.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -27px;
            height: 3px;
            background: #e50914;
        }

        /* =========================================================
           BÊN PHẢI - ĐỒNG BỘ user.blade.php
        ========================================================= */

        .member-login {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .dashboard-btn {
            color: #fff;
            border: 1px solid #555;
            padding: 10px 18px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
            cursor: pointer;
        }

        .dashboard-btn:hover {
            border-color: #e50914;
            color: #e50914;
        }

        .dashboard-btn.active {
            border-color: #e50914;
            color: #e50914;
        }

        .logout-btn {
            background: #e50914;
            color: #fff;

            padding: 11px 19px;
            border-radius: 5px;

            font-size: 14px;
            font-weight: bold;

            transition: 0.3s;

            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .logout-btn:hover {
            background: #ff2633;
            transform: translateY(-1px);
        }

        /* =========================================================
           CHUÔNG THÔNG BÁO
        ========================================================= */

        .notification-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .notification-btn {
            position: relative;
            z-index: 2002;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #333;
            border-radius: 6px;
            background: #101010;
            color: #fff;
            cursor: pointer;
            transition: .25s ease;
            font-size: 18px;
        }

        .notification-btn:hover,
        .notification-btn.open {
            border-color: #e50914;
            color: #fff;
        }

        .notification-badge {
            position: absolute;
            z-index: 2003;
            pointer-events: none;
            top: -5px;
            right: -5px;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e50914;
            color: #fff;
            border: 2px solid #080808;
            border-radius: 50%;
            font-size: 9px;
            font-weight: 800;
            line-height: 1;
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 360px;
            max-width: calc(100vw - 30px);
            background: #111;
            border: 1px solid #2b2b2b;
            border-radius: 12px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .45);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: .2s ease;
            z-index: 2000;
        }

        .notification-wrap.show .notification-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 16px;
            border-bottom: 1px solid #252525;
        }

        .notification-head strong {
            color: #fff;
            font-size: 14px;
        }

        .notification-head span {
            color: #777;
            font-size: 10px;
        }

        .notification-list {
            max-height: 360px;
            overflow-y: auto;
        }

        .notification-item {
            display: block;
            padding: 13px 16px;
            border-bottom: 1px solid #202020;
            color: #fff;
            transition: .2s ease;
        }

        .notification-item:hover {
            background: #181818;
        }

        .notification-item.unread {
            background: rgba(229, 9, 20, .07);
            border-left: 3px solid #e50914;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: #eee;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .notification-dot {
            width: 7px;
            height: 7px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #e50914;
        }

        .notification-content {
            color: #888;
            font-size: 11px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-time {
            display: block;
            margin-top: 5px;
            color: #555;
            font-size: 9px;
        }

        .notification-empty {
            padding: 28px 18px;
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        .notification-list::-webkit-scrollbar {
            width: 5px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        /* =========================================================
           CONTENT
        ========================================================= */

        .member-content {
            flex: 1 0 auto;
            width: 100%;
            max-width: none;
            min-height: calc(100vh - 75px);
            /* Header is fixed: reserve its full height plus breathing room. */
            padding: 112px clamp(24px, 3.6vw, 64px) 70px;
            margin: 0 auto;
        }

        .member-welcome {
            margin-bottom: 30px;
        }

        .member-welcome .welcome-small {
            color: #e50914;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .member-welcome h1 {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .member-welcome p {
            color: #999;
            font-size: 14px;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            margin-top: 15px;
            padding: 7px 14px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .3);
            color: #e50914;
            font-size: 11px;
            font-weight: bold;
        }

        /* THỐNG KÊ */

        .member-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .member-stat-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 6px;
            padding: 22px;

            display: flex;
            align-items: center;
            gap: 15px;

            transition: .3s;
        }

        .member-stat-card:hover {
            border-color: #e50914;
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: #e50914;
            border-radius: 5px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
            flex-shrink: 0;
        }

        .member-stat-card span {
            display: block;
            color: #999;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .member-stat-card strong {
            display: block;
            font-size: 25px;
        }

        /* GRID */

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 25px;
            align-items: start;
        }

        /* CARD */

        .member-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 6px;
            padding: 25px;
            margin-bottom: 25px;
            transition: .3s;
        }

        .member-card:hover {
            border-color: #333;
        }

        .member-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            border-bottom: 1px solid #252525;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .member-card-header h2 {
            font-size: 20px;
        }

        .card-label {
            display: block;
            color: #e50914;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        /* LIST */

        .member-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .member-list-item {
            background: #0b0b0b;
            border: 1px solid #252525;
            border-radius: 5px;
            padding: 16px 18px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            transition: .3s;
        }

        .member-list-item:hover {
            border-color: #e50914;
            transform: translateX(3px);
        }

        .member-list-item h3 {
            font-size: 14px;
            margin-bottom: 6px;
        }

        .member-list-item p {
            color: #888;
            font-size: 12px;
            line-height: 1.5;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .1);
            color: #e50914;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .member-empty {
            text-align: center;
            padding: 35px 20px;
        }

        .empty-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #181818;
            font-size: 27px;
        }

        .member-empty h3 {
            color: #fff;
            font-size: 16px;
            margin-bottom: 7px;
        }

        .member-empty p {
            color: #777;
            font-size: 12px;
        }

        /* FOOTER - CÙNG MÀU VỚI user.blade.php */

        .member-footer {
            flex-shrink:0;
            background: #050505;
            border-top: 1px solid #252525;
            padding: 25px;
            text-align: center;
            color: #666;
            font-size: 13px;
        }

        /* =========================================================
           RESPONSIVE - GIỐNG user.blade.php
        ========================================================= */

        @media (max-width: 1200px) {
            .member-header {
                padding: 0 30px;
            }

            .member-menu {
                gap: 20px;
            }

            .member-menu a {
                font-size: 13px;
            }

            .member-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 1000px) {
            .member-header {
                padding: 0 25px;
            }

            .member-menu {
                gap: 15px;
            }

            .member-menu a {
                font-size: 12px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .member-content {
                padding: 40px 30px 60px;
            }
        }

        @media (max-width: 850px) {
            .member-header {
                height: auto;
                min-height: 75px;
                flex-wrap: wrap;
                padding: 18px 20px;
                gap: 20px;
            }

            .member-menu {
                order: 3;
                width: 100%;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .member-menu a {
                white-space: nowrap;
            }

            .member-menu a.active::after {
                bottom: -8px;
            }
        }

        @media (max-width: 600px) {
            .member-header {
                align-items: flex-start;
            }

            .member-logo {
                font-size: 24px;
            }

            .member-login {
                gap: 6px;
            }

            .dashboard-btn,
            .logout-btn {
                padding: 8px 10px;
                font-size: 11px;
            }

            .member-stats {
                grid-template-columns: 1fr;
            }

            .member-content {
                padding: 30px 20px 50px;
            }

            .member-welcome h1 {
                font-size: 27px;
            }
        }

        /* THANH CUỘN - GIỐNG user.blade.php */

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #080808;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #e50914;
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

    
        /* Dashboard shared scale: keep the established layout, improve legibility */
        .dashboard-main, main, .dashboard-content { font-size: 14px; }
        .dashboard-content { padding-left: 30px; padding-right: 30px; }
        .dashboard-page { max-width: 1560px !important; width: min(100% - 48px, 1560px) !important; }
        .dashboard-page .dashboard-hero { padding: 46px 50px; }
        .dashboard-page .dashboard-stats { gap: 20px; }
        .dashboard-page .stat-card, .dashboard-page .dashboard-panel, .dashboard-page .section-card {
            border-radius: 14px;
        }
        .dashboard-page .stat-icon, .dashboard-page .item-icon, .dashboard-page .empty-icon {
            color: #ff3945 !important;
            background: rgba(229,9,20,.12) !important;
        }
        .dashboard-page button, .dashboard-page a.button, .dashboard-page .btn {
            min-height: 38px; padding: 9px 15px; border-radius: 8px;
        }
        @media(max-width:700px) {
            .dashboard-page { width: min(100% - 24px, 1560px) !important; }
            .dashboard-content { padding-left: 12px; padding-right: 12px; }
        }
    
/* GYMFIT dashboard readability + fixed-header clearance */
.member-content { width:100% !important; max-width:none !important; padding-top:112px !important; }
.member-content > * { min-width:0; }
.dashboard-page, .staff-dashboard, .trainer-dashboard {
    width: min(100%, 1560px) !important;
    max-width: 1560px !important;
    margin-left:auto !important; margin-right:auto !important;
}
.dashboard-page .dashboard-hero, .staff-dashboard .staff-hero, .trainer-dashboard .trainer-hero {
    padding: 48px 52px;
}
.dashboard-page .dashboard-stats, .staff-dashboard .staff-stats, .trainer-dashboard .trainer-stats {
    gap:20px;
}
.dashboard-page .dashboard-card, .staff-dashboard .dashboard-card, .trainer-dashboard .dashboard-card {
    min-height: 280px;
}
.dashboard-page .dashboard-card-header, .staff-dashboard .dashboard-card-header, .trainer-dashboard .dashboard-card-header {
    padding:24px 26px; min-height:88px;
}
.dashboard-page .dashboard-item, .staff-dashboard .dashboard-item, .trainer-dashboard .dashboard-item {
    padding:19px 0;
}
.dashboard-page .item-info h3, .staff-dashboard .item-info h3, .trainer-dashboard .item-info h3 { font-size:15px; }
.dashboard-page .item-info p, .staff-dashboard .item-info p, .trainer-dashboard .item-info p { font-size:12px; }
.dashboard-page .stat-info span { font-size:13px; }
.dashboard-page .stat-info strong { font-size:30px; }
.dashboard-page .gym-line-icon { width:28px; height:28px; }
.dashboard-page .dashboard-alert {
    position:relative; z-index:2; width:100%; margin:0 0 22px;
    padding:16px 20px; border-radius:12px; font-size:14px;
}
.dashboard-page .action-btn, .dashboard-page .action-locked {
    min-height:34px; padding:8px 13px; font-size:11px; white-space:nowrap;
}
@media(max-width:850px) {
    .member-content { padding:145px 20px 48px !important; }
    .dashboard-page, .staff-dashboard, .trainer-dashboard { width:100% !important; }
    .dashboard-page .dashboard-hero, .staff-dashboard .staff-hero, .trainer-dashboard .trainer-hero { padding:32px 25px; }
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


    <header class="member-header">


        {{-- LOGO --}}

        <a href="/" class="member-logo">

            GYM<span>FIT</span>

        </a>



        {{-- MENU --}}

        <nav class="member-menu">

            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
                TRANG CHỦ
            </a>

            <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">
                GIỚI THIỆU
            </a>

            <a href="/packages" class="{{ request()->is('packages*') ? 'active' : '' }}">
                GÓI TẬP
            </a>

            <a href="/pt-packages" class="{{ request()->is('pt-packages*') ? 'active' : '' }}">
                GÓI PT
            </a>

            <a href="/classes" class="{{ request()->is('classes*') ? 'active' : '' }}">
                LỚP TẬP
            </a>

            <a href="/trainers" class="{{ request()->is('trainers*') ? 'active' : '' }}">
                HUẤN LUYỆN VIÊN
            </a>

            <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">
                LIÊN HỆ
            </a>

        </nav>



        {{-- HỒ SƠ / DASHBOARD / ĐĂNG XUẤT --}}

        <div class="member-login">

            {{-- CHUÔNG THÔNG BÁO --}}
            @php
                $thongBaoMoi = collect();
                $soThongBaoChuaDoc = 0;

                if (session()->has('user')) {
                    $nguoiDungHienTai = session('user')->nguoi_dung_id;

                    $thongBaoMoi = DB::table('thong_bao')
                        ->where('nguoi_dung_id', $nguoiDungHienTai)
                        ->orderByDesc('tao_luc')
                        ->limit(5)
                        ->get();

                    $soThongBaoChuaDoc = DB::table('thong_bao')
                        ->where('nguoi_dung_id', $nguoiDungHienTai)
                        ->where('da_doc', 0)
                        ->count();
                }
            @endphp

            <div class="notification-wrap" id="notificationWrap">

                <button type="button" class="notification-btn" id="notificationBtn" aria-label="Thông báo"
                    aria-expanded="false">

                    🔔

                    @if ($soThongBaoChuaDoc > 0)
                        <span class="notification-badge">
                            {{ $soThongBaoChuaDoc > 99 ? '99+' : $soThongBaoChuaDoc }}
                        </span>
                    @endif

                </button>

                <div class="notification-dropdown">

                    <div class="notification-head">
                        <strong>Thông báo</strong>

                        @if ($soThongBaoChuaDoc > 0)
                            <span>{{ $soThongBaoChuaDoc }} chưa đọc</span>
                        @else
                            <span>Mới nhất</span>
                        @endif
                    </div>

                    <div class="notification-list">

                        @forelse ($thongBaoMoi as $tb)
                            <div class="notification-item {{ (int) $tb->da_doc === 0 ? 'unread' : '' }}">


                                <div class="notification-title">

                                    <span>
                                        {{ $tb->tieu_de }}
                                    </span>

                                    @if ((int) $tb->da_doc === 0)
                                        <span class="notification-dot"></span>
                                    @endif

                                </div>

                                <div class="notification-content">
                                    {{ $tb->noi_dung }}
                                </div>

                                <span class="notification-time">
                                    {{ $tb->tao_luc }}
                                </span>

                            </div>

                        @empty

                            <div class="notification-empty">
                                Không có thông báo nào.
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>



            @if ((int) session('role_id') === 3)
                <a href="{{ route('user.history') }}" class="dashboard-btn">LỊCH SỬ</a>
            @endif

            {{-- HỒ SƠ / DASHBOARD THEO TỪNG VAI TRÒ --}}

            {{-- HỘI VIÊN --}}
            @if (request()->is('user/dashboard'))
                <a href="/user/profile" class="dashboard-btn profile-btn">
                    HỒ SƠ
                </a>
            @elseif (request()->is('user/profile'))
                <a href="/user/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>

                {{-- NHÂN VIÊN --}}
            @elseif (request()->is('staff/dashboard'))
                <a href="/staff/profile" class="dashboard-btn profile-btn">
                    HỒ SƠ
                </a>
            @elseif (request()->is('staff/profile'))
                <a href="/staff/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>
            @elseif (request()->is('staff/*'))
                <a href="/staff/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>

                {{-- HUẤN LUYỆN VIÊN (PT) --}}
            @elseif (request()->is('trainer/dashboard'))
                <a href="/trainer/profile" class="dashboard-btn profile-btn">
                    HỒ SƠ
                </a>
            @elseif (request()->is('trainer/profile'))
                <a href="/trainer/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>
            @elseif (request()->is('trainer/*'))
                <a href="/trainer/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>

                {{-- CÁC TRANG KHÁC --}}
            @else
                @if (session('role_id') == 2)
                    <a href="/staff/dashboard" class="dashboard-btn">
                        DASHBOARD
                    </a>
                @elseif (session('role_id') == 4)
                    <a href="/trainer/dashboard" class="dashboard-btn">
                        DASHBOARD
                    </a>
                @elseif (session('role_id') == 3)
                    <a href="/user/dashboard" class="dashboard-btn">
                        DASHBOARD
                    </a>
                @endif
            @endif



            {{-- ĐĂNG XUẤT --}}
            <form action="/logout" method="POST" style="display: inline;"
                data-confirm-submit="Bạn có chắc chắn muốn đăng xuất không?">

                @csrf

                <button type="submit" class="logout-btn">
                    ĐĂNG XUẤT
                </button>

            </form>

        </div>


    </header>



    <main class="member-content">

        @yield('content')

    </main>



    <footer class="member-footer">

        © {{ date('Y') }} GYM FIT. All rights reserved.

    </footer>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const wrap = document.getElementById('notificationWrap');
            const button = document.getElementById('notificationBtn');

            if (!wrap || !button) {
                return;
            }

            // Bấm chuông = mở danh sách + đánh dấu TẤT CẢ thông báo là đã đọc.
            // Số đỏ chuyển về 0 ngay lập tức.
            button.addEventListener('click', function(event) {

                event.preventDefault();
                event.stopPropagation();

                const isOpen = wrap.classList.contains('show');

                if (!isOpen) {
                    wrap.classList.add('show');
                    button.classList.add('open');
                    button.setAttribute('aria-expanded', 'true');

                    const badge = button.querySelector('.notification-badge');
                    if (badge) {
                        badge.remove();
                    }

                    const headUnread = document.querySelector('.notification-head span');
                    if (headUnread) {
                        headUnread.textContent = 'Đã đọc';
                    }

                    document.querySelectorAll('.notification-item.unread').forEach(function(item) {
                        item.classList.remove('unread');
                        const dot = item.querySelector('.notification-dot');
                        if (dot) dot.remove();
                    });

                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

                    fetch('/notifications/read-all', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Không thể cập nhật thông báo.');
                            }

                            return response.json();
                        })
                        .then(function(data) {
                            if (!data.success) {
                                console.error('Không đánh dấu được thông báo.');
                            }
                        })
                        .catch(function(error) {
                            console.error(error);
                        });

                } else {
                    wrap.classList.remove('show');
                    button.classList.remove('open');
                    button.setAttribute('aria-expanded', 'false');
                }

            });

            document.addEventListener('click', function(event) {

                if (!wrap.contains(event.target)) {

                    wrap.classList.remove('show');
                    button.classList.remove('open');
                    button.setAttribute('aria-expanded', 'false');

                }

            });

        });
    </script>

    @include('layouts.partials.gym-modal')

</body>

</html>
