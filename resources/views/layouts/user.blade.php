<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}?v=12345">

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
   HEADER NGƯỜI DÙNG
========================================================= */

        .user-header {
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

        /* LOGO */

        .user-logo {
            font-size: 27px;
            font-weight: 900;
            color: #fff;
            letter-spacing: 1px;
        }

        .user-logo span {
            color: #e50914;
        }

        /* =========================================================
   MENU
========================================================= */

        .user-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .user-menu a {
            color: #ddd;
            font-size: 14px;
            font-weight: 600;

            position: relative;

            transition: 0.3s;
        }

        .user-menu a:hover {
            color: #e50914;
        }

        .user-menu a.active {
            color: #e50914;
        }

        .user-menu a.active::after {
            content: "";

            position: absolute;

            left: 0;
            right: 0;
            bottom: -27px;

            height: 3px;

            background: #e50914;
        }

        /* =========================================================
   ĐĂNG NHẬP / ĐĂNG KÝ
========================================================= */

        .user-login {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-btn {
            color: #fff;

            border: 1px solid #555;

            padding: 10px 18px;

            border-radius: 5px;

            font-size: 14px;

            transition: 0.3s;
        }

        .login-btn:hover {
            border-color: #e50914;
            color: #e50914;
        }

        .register-btn {
            background: #e50914;
            color: #fff;

            padding: 11px 19px;

            border-radius: 5px;

            font-size: 14px;
            font-weight: bold;

            transition: 0.3s;
        }

        .register-btn:hover {
            background: #ff2633;
            transform: translateY(-1px);
        }

        /* =========================================================
   CONTENT
========================================================= */

        .user-content {
            flex:1 0 auto;
            min-height:calc(100vh - 75px);
        }

        /* =========================================================
   FOOTER
========================================================= */

        .user-footer {
            flex-shrink:0;
            background: #050505;

            border-top: 1px solid #252525;

            padding: 65px 70px 0;
        }

        .footer-grid {
            max-width: 1400px;

            margin: auto;

            display: grid;

            grid-template-columns:
                2fr 1fr 1fr 1.3fr;

            gap: 60px;
        }

        /* =========================================================
   FOOTER - CỘT 1
========================================================= */

        .footer-logo {
            font-size: 30px;

            font-weight: 900;

            letter-spacing: 1px;

            margin-bottom: 20px;
        }

        .footer-logo span {
            color: #e50914;
        }

        .footer-description {
            color: #999;

            line-height: 1.8;

            font-size: 14px;

            max-width: 400px;
        }

        /* =========================================================
   SOCIAL
========================================================= */

        .footer-social {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
        }

        .footer-social a {
            width: 42px;
            height: 42px;

            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;

            padding: 0 !important;
            margin: 0 !important;

            box-sizing: border-box;

            background: #111;
            border: 1px solid #333;
            border-radius: 50%;

            color: #aaa;

            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            line-height: 1;

            text-align: center;

            transition:
                background .25s ease,
                border-color .25s ease,
                color .25s ease,
                transform .25s ease;
        }

        .footer-social a:hover {
            background: #e50914;
            border-color: #e50914;
            color: #fff;

            transform: translateY(-3px);

            padding: 0 !important;
        }

        /* Không cho CSS link chung làm lệch icon */
        .footer-social a:hover,
        .footer-social a:focus {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 0 !important;
        }

        /* =========================================================
   FOOTER - TIÊU ĐỀ
========================================================= */

        .footer-column h3 {
            color: #fff;

            font-size: 16px;

            margin-bottom: 22px;

            position: relative;

            padding-bottom: 12px;
        }

        .footer-column h3::after {
            content: "";

            position: absolute;

            left: 0;
            bottom: 0;

            width: 35px;
            height: 2px;

            background: #e50914;
        }

        /* =========================================================
   FOOTER - LINK
========================================================= */

        .footer-column>a {
            display: block;

            color: #999;
            font-size: 14px;

            margin-bottom: 13px;

            transition: 0.3s;
        }

        .footer-column>a:hover {
            color: #e50914;
            padding-left: 5px;
        }

        /* =========================================================
   FOOTER - LIÊN HỆ
========================================================= */

        .footer-contact {
            display: flex;

            gap: 12px;

            margin-bottom: 17px;
        }

        .footer-contact-icon {
            width: 30px;

            flex-shrink: 0;

            color: #e50914;

            font-size: 17px;
        }

        .footer-contact-content {
            color: #999;

            font-size: 14px;

            line-height: 1.6;
        }

        .footer-contact-content strong {
            color: #ddd;

            display: block;

            margin-bottom: 3px;
        }

        /* =========================================================
   FOOTER BOTTOM
========================================================= */

        .footer-bottom {
            max-width: 1400px;

            margin: 55px auto 0;

            padding: 22px 0;

            border-top: 1px solid #222;

            display: flex;

            justify-content: space-between;

            align-items: center;

            color: #666;

            font-size: 13px;
        }

        .footer-bottom-links {
            display: flex;

            gap: 25px;
        }

        .footer-bottom-links a {
            color: #666;

            transition: 0.3s;
        }

        .footer-bottom-links a:hover {
            color: #e50914;
        }

        /* =========================================================
   RESPONSIVE - TABLET
========================================================= */

        @media (max-width: 1200px) {

            .user-header {
                padding: 0 30px;
            }

            .user-menu {
                gap: 20px;
            }

            .user-menu a {
                font-size: 13px;
            }

            .footer-grid {
                grid-template-columns:
                    1.5fr 1fr 1fr 1.3fr;

                gap: 35px;
            }

        }

        /* =========================================================
   RESPONSIVE - TABLET NHỎ
========================================================= */

        @media (max-width: 1000px) {

            .user-header {
                padding: 0 25px;
            }

            .user-menu {
                gap: 15px;
            }

            .user-menu a {
                font-size: 12px;
            }

            .login-btn,
            .register-btn {
                padding: 8px 12px;
            }

            .user-footer {
                padding: 50px 30px 0;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;

                gap: 40px;
            }

        }

        /* =========================================================
   RESPONSIVE - ĐIỆN THOẠI
========================================================= */

        @media (max-width: 850px) {

            .user-header {
                height: auto;

                min-height: 75px;

                flex-wrap: wrap;

                padding: 18px 20px;

                gap: 20px;
            }

            .user-menu {
                order: 3;

                width: 100%;

                overflow-x: auto;

                padding-bottom: 5px;
            }

            .user-menu a {
                white-space: nowrap;
            }

            .user-menu a.active::after {
                bottom: -8px;
            }

        }

        /* =========================================================
   RESPONSIVE - ĐIỆN THOẠI NHỎ
========================================================= */

        @media (max-width: 600px) {

            .user-header {
                align-items: flex-start;
            }

            .user-logo {
                font-size: 24px;
            }

            .user-login {
                gap: 6px;
            }

            .login-btn,
            .register-btn {
                padding: 8px 10px;

                font-size: 11px;
            }

            .footer-grid {
                grid-template-columns: 1fr;

                gap: 35px;
            }

            .user-footer {
                padding: 45px 25px 0;
            }

            .footer-bottom {
                flex-direction: column;

                gap: 15px;

                text-align: center;
            }

            .footer-bottom-links {
                flex-wrap: wrap;

                justify-content: center;

                gap: 15px;
            }

        }

        /* =========================================================
   THANH CUỘN
========================================================= */

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

    @yield('styles')
    
    <link rel="stylesheet" href="{{ asset('css/gymfit-design-system.css') }}?v=2">
</head>

<body>
@php
    $gymSettings = \Illuminate\Support\Facades\DB::table('cai_dat')
        ->pluck('gia_tri', 'khoa');
@endphp
    {{-- HEADER NGƯỜI DÙNG --}}
    <header class="user-header">

        {{-- LOGO --}}
        <a href="/" class="user-logo">
            GYM<span>FIT</span>
        </a>


        {{-- MENU --}}
        <nav class="user-menu">

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


        {{-- ĐĂNG NHẬP / ĐĂNG KÝ --}}
        <div class="user-login">

            @if (session()->has('user'))

                {{-- TRANG DASHBOARD HỘI VIÊN --}}
                @if (request()->is('user/dashboard'))

                    <a href="/user/profile" class="login-btn">
                        HỒ SƠ
                    </a>

                {{-- TRANG HỒ SƠ --}}
                @elseif (request()->is('user/profile'))

                    <a href="/user/dashboard" class="login-btn">
                        DASHBOARD
                    </a>

                {{-- CÁC TRANG NGOÀI --}}
                @else

                    <a href="/user/dashboard" class="login-btn">
                        DASHBOARD
                    </a>

                @endif


                {{-- ĐĂNG XUẤT --}}
                <form action="/logout" method="POST" style="display: inline;"
                    data-confirm-submit="Bạn có chắc chắn muốn đăng xuất không?">

                    @csrf

                    <button type="submit" class="register-btn"
                        style="
                            border: none;
                            cursor: pointer;
                            font-family: inherit;
                        ">
                        ĐĂNG XUẤT
                    </button>

                </form>

            @else

                <a href="/login" class="login-btn">
                    ĐĂNG NHẬP
                </a>

                <a href="/register" class="register-btn">
                    ĐĂNG KÝ
                </a>

            @endif

        </div>

    </header>


    {{-- NỘI DUNG --}}
    <main class="user-content">

        @yield('content')

    </main>


    {{-- FOOTER --}}
    <footer class="user-footer">

        <div class="footer-grid">

            {{-- CỘT 1 --}}
            <div class="footer-column">

                <div class="footer-logo">
                    GYM<span>FIT</span>
                </div>

                <p class="footer-description">
                    GYM FIT mang đến môi trường tập luyện hiện đại,
                    chuyên nghiệp và thân thiện. Đồng hành cùng bạn
                    trong hành trình xây dựng một cơ thể khỏe mạnh
                    và một lối sống tích cực.
                </p>

                <div class="footer-social">

                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" title="Facebook">
                        f
                    </a>

                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" title="Instagram">
                        ◎
                    </a>

                    <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" title="YouTube">
                        ▶
                    </a>

                    <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" title="TikTok">
                        ♪
                    </a>

                </div>

            </div>


            {{-- CỘT 2 --}}
            <div class="footer-column">

                <h3>KHÁM PHÁ</h3>

                <a href="/">
                    Trang chủ
                </a>

                <a href="/about">
                    Giới thiệu
                </a>

                <a href="/packages">
                    Gói tập
                </a>

                <a href="/pt-packages">
                    Gói PT
                </a>

                <a href="/classes">
                    Lớp tập
                </a>

                <a href="/trainers">
                    Huấn luyện viên
                </a>

                <a href="/contact">
                    Liên hệ
                </a>

            </div>


            {{-- CỘT 3 --}}
            <div class="footer-column">

                <h3>HỖ TRỢ</h3>

                <a href="/login">
                    Đăng nhập
                </a>

                <a href="/register">
                    Đăng ký tài khoản
                </a>

                <a href="/user/profile">
                    Trang cá nhân
                </a>

                <a href="/user/payments">
                    Lịch sử thanh toán
                </a>

               

            </div>


            {{-- CỘT 4 --}}
            <div class="footer-column">

                <h3>THÔNG TIN LIÊN HỆ</h3>

                <div class="footer-contact">

                    <div class="footer-contact-icon">
                        📍
                    </div>

                    <div class="footer-contact-content">

                        <strong>Địa chỉ</strong>
                        {{ $gymSettings['dia_chi'] ?? 'Chưa cập nhật địa chỉ' }}

                    </div>

                </div>


                <div class="footer-contact">

                    <div class="footer-contact-icon">
                        ☎
                    </div>

                    <div class="footer-contact-content">

                        <strong>Điện thoại</strong>
                        {{ $gymSettings['so_dien_thoai'] ?? 'Chưa cập nhật số điện thoại' }}

                    </div>

                </div>


                <div class="footer-contact">

                    <div class="footer-contact-icon">
                        ✉
                    </div>

                    <div class="footer-contact-content">

                        <strong>Email</strong>
                        {{ $gymSettings['email_lien_he'] ?? 'Chưa cập nhật email' }}

                    </div>

                </div>


                <div class="footer-contact">

                    <div class="footer-contact-icon">
                        🕐
                    </div>

                    <div class="footer-contact-content">

                        <strong>Giờ hoạt động</strong>

                        Thứ 2 - Chủ nhật: 05:00 - 22:00

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER BOTTOM --}}
        <div class="footer-bottom">

            <div>
                © {{ date('Y') }} GYM FIT. All rights reserved.
            </div>

            <div class="footer-bottom-links">

                <a href="#">
                    Chính sách bảo mật
                </a>

                <a href="#">
                    Điều khoản sử dụng
                </a>

            </div>

        </div>

    </footer>

    @include('layouts.partials.gym-modal')

</body>

</html>
