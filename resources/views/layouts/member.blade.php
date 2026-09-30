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
            font-family: Arial, Helvetica, sans-serif;
            background: #0b0b0b;
            color: #fff;
        }


        a {
            text-decoration: none;
        }



        /* =========================================================
           HEADER
        ========================================================= */

        .member-header {

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



        /* =========================================================
           LOGO
        ========================================================= */

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



        /* =========================================================
           MENU
           GIỐNG user.blade.php
        ========================================================= */

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


        

        /* HOVER GIỐNG USER.BLADE */

        .member-menu a:hover {

            color: #e50914;
        }


        /* MENU ĐANG ACTIVE */

        .member-menu a.active {

            color: #e50914;
        }


        /* GẠCH ĐỎ DƯỚI MENU */

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
           BÊN PHẢI
        ========================================================= */

        .member-login {

            display: flex;

            align-items: center;

            gap: 12px;

            flex-shrink: 0;
        }



        /* =========================================================
           DASHBOARD
           GIỐNG LOGIN BUTTON CỦA USER.BLADE
        ========================================================= */

        .dashboard-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 90px;
            height: 36px;

            padding: 0;

            color: #fff;
            background: #111;
            border: 1px solid #555;
            border-radius: 5px;

            font-size: 13px;
            font-weight: 600;
            line-height: 1;

            text-decoration: none;
            transition: 0.3s;
            position: relative;
            box-sizing: border-box;
        }


        .dashboard-btn:hover {

            border-color: #e50914;

            color: #e50914;
        }


        /* DASHBOARD ĐANG Ở TRANG HIỆN TẠI */

        .dashboard-btn.active {

            border-color: #e50914;

            color: #e50914;
        }



        /* =========================================================
           ĐĂNG XUẤT
        ========================================================= */

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
           CONTENT
        ========================================================= */

        .member-content {

            min-height: calc(100vh - 75px);

            padding: 45px 60px 70px;

            max-width: 1500px;

            margin: 0 auto;
        }



        /* =========================================================
           WELCOME
        ========================================================= */

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



        /* =========================================================
           ROLE
        ========================================================= */

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



        /* =========================================================
           THỐNG KÊ
        ========================================================= */

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



        /* =========================================================
           GRID
        ========================================================= */

        .dashboard-grid {

            display: grid;

            grid-template-columns: 1.5fr 1fr;

            gap: 25px;

            align-items: start;
        }



        /* =========================================================
           CARD
        ========================================================= */

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



        /* =========================================================
           LIST
        ========================================================= */

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



        /* =========================================================
           STATUS
        ========================================================= */

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



        /* =========================================================
           EMPTY
        ========================================================= */

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



        /* =========================================================
           FOOTER
        ========================================================= */

        .member-footer {

            background: #050505;

            border-top: 1px solid #252525;

            padding: 25px;

            text-align: center;

            color: #666;

            font-size: 13px;
        }



        /* =========================================================
           RESPONSIVE
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
    

    </style>

</head>


<body>


    <header class="member-header">


        {{-- LOGO --}}

        <a href="/" class="member-logo">

            GYM<span>FIT</span>

        </a>



        {{-- MENU --}}

        <nav class="member-menu">

            @yield('menu')

        </nav>



        {{-- HỒ SƠ / DASHBOARD / ĐĂNG XUẤT --}}

        <div class="member-login">

            {{-- CHỈ HIỆN HỒ SƠ Ở DASHBOARD HỘI VIÊN --}}
            @if (request()->is('user/dashboard'))

                <a href="/user/profile" class="dashboard-btn profile-btn">
                    HỒ SƠ
                </a>

            {{-- TRANG HỒ SƠ --}}
            @elseif (request()->is('user/profile'))

                <a href="/user/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>

            {{-- STAFF / TRAINER --}}
            @else

                <a href="{{ session('dashboard_path', '/user/dashboard') }}"
                    class="dashboard-btn
                    {{ request()->is('staff/dashboard') || request()->is('trainer/dashboard')
                        ? 'active'
                        : '' }}">

                    DASHBOARD

                </a>

            @endif


            {{-- ĐĂNG XUẤT --}}
            <form action="/logout" method="POST" style="display: inline;"
                onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">

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


</body>

</html>
