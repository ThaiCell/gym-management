<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            position: sticky;
            top: 0;
            z-index: 1000;
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
            min-height: calc(100vh - 75px);
        }

        /* =========================================================
   FOOTER
========================================================= */

        .user-footer {
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
    </style>

    @yield('styles')
</head>

<body>

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
                    onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">

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

                <a href="/profile">
                    Trang cá nhân
                </a>

                <a href="/payments">
                    Lịch sử thanh toán
                </a>

                <a href="/notifications">
                    Thông báo
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

                        123 Đường Nguyễn Văn Linh,
                        Quận 7, TP. Hồ Chí Minh

                    </div>

                </div>


                <div class="footer-contact">

                    <div class="footer-contact-icon">
                        ☎
                    </div>

                    <div class="footer-contact-content">

                        <strong>Điện thoại</strong>

                        0123 456 789

                    </div>

                </div>


                <div class="footer-contact">

                    <div class="footer-contact-icon">
                        ✉
                    </div>

                    <div class="footer-contact-content">

                        <strong>Email</strong>

                        gymfit@example.com

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

</body>

</html>
