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
            font-family: system-ui, -apple-system, "Segoe UI", Roboto,
                "Helvetica Neue", Arial, sans-serif;

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
           BÊN PHẢI
        ========================================================= */

        .member-login {

            display: flex;

            align-items: center;

            gap: 12px;

            flex-shrink: 0;
        }



        /* =========================================================
           CHUÔNG THÔNG BÁO
        ========================================================= */

        .notification-wrapper {

            position: relative;

            display: flex;

            align-items: center;
        }


        .notification-bell {

            position: relative;

            width: 38px;

            height: 38px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: transparent;

            border: none;

            color: #fff;

            font-size: 19px;

            cursor: pointer;

            border-radius: 50%;

            transition: .3s;
        }


        .notification-bell:hover {

            background: #181818;

            color: #e50914;

            transform: translateY(-1px);
        }


        .notification-badge {

            position: absolute;

            top: -2px;

            right: -3px;

            min-width: 17px;

            height: 17px;

            padding: 0 4px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #e50914;

            color: #fff;

            border-radius: 20px;

            font-size: 9px;

            font-weight: 800;

            border: 2px solid #080808;
        }



        /* =========================================================
           HỘP THÔNG BÁO
        ========================================================= */

        .notification-dropdown {

            position: absolute;

            top: 50px;

            right: 0;

            width: 360px;

            background: #111;

            border: 1px solid #292929;

            border-radius: 7px;

            box-shadow: 0 15px 40px rgba(0, 0, 0, .55);

            overflow: hidden;

            display: none;

            z-index: 2000;
        }


        .notification-dropdown.show {

            display: block;
        }


        .notification-dropdown-header {

            padding: 16px 18px;

            border-bottom: 1px solid #292929;

            background: #151515;
        }


        .notification-dropdown-header strong {

            font-size: 13px;

            letter-spacing: .5px;
        }


        .notification-small-count {

            margin-left: 7px;

            color: #e50914;

            font-size: 10px;
        }


        .notification-dropdown-list {

            max-height: 360px;

            overflow-y: auto;
        }


        .notification-dropdown-item {

            display: flex;

            gap: 12px;

            padding: 14px 16px;

            border-bottom: 1px solid #222;

            transition: .2s;
        }


        .notification-dropdown-item:hover {

            background: #181818;
        }


        .notification-dropdown-item.unread {

            background: rgba(229, 9, 20, .045);

            border-left: 3px solid #e50914;
        }


        .notification-dropdown-icon {

            width: 36px;

            height: 36px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #1d1d1d;

            border-radius: 5px;

            font-size: 16px;
        }


        .notification-dropdown-item.unread .notification-dropdown-icon {

            background: rgba(229, 9, 20, .12);
        }


        .notification-dropdown-content {

            min-width: 0;

            flex: 1;
        }


        .notification-dropdown-content strong {

            display: block;

            color: #fff;

            font-size: 12px;

            margin-bottom: 4px;
        }


        .notification-dropdown-content p {

            color: #999;

            font-size: 11px;

            line-height: 1.5;

            margin-bottom: 5px;
        }


        .notification-dropdown-content small {

            color: #666;

            font-size: 9px;
        }


        .notification-no-data {

            padding: 35px 20px;

            text-align: center;

            color: #777;
        }


        .notification-no-data div {

            font-size: 28px;

            margin-bottom: 8px;
        }


        .notification-no-data p {

            font-size: 12px;
        }


        .notification-view-all {

            display: block;

            padding: 13px;

            text-align: center;

            background: #151515;

            color: #e50914;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: .5px;

            transition: .3s;
        }


        .notification-view-all:hover {

            background: #e50914;

            color: #fff;
        }



        /* =========================================================
           DASHBOARD / HỒ SƠ
        ========================================================= */

        .dashboard-btn {

            color: #fff;

            background: #111;

            border: 1px solid #555;

            padding: 10px 18px;

            border-radius: 5px;

            font-size: 14px;

            transition: 0.3s;

            white-space: nowrap;
        }


        .dashboard-btn:hover {

            border-color: #e50914;

            color: #e50914;
        }


        /*
         * Dashboard hiện tại KHÔNG đỏ.
         * Chỉ hover mới đỏ.
         */

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


            .notification-dropdown {

                right: -70px;
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


            .notification-dropdown {

                position: fixed;

                top: 75px;

                right: 15px;

                width: min(360px, calc(100vw - 30px));
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


            .notification-bell {

                width: 34px;

                height: 34px;

                font-size: 17px;
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



        /* =========================================================
           SCROLLBAR
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



        {{-- HỒ SƠ / CHUÔNG / DASHBOARD / ĐĂNG XUẤT --}}

        <div class="member-login">


            {{-- =====================================================
             CHUÔNG THÔNG BÁO
        ====================================================== --}}

            @if (session()->has('user'))

                @php

                    $headerThongBao = \Illuminate\Support\Facades\DB::table('thong_bao')
                        ->where('nguoi_dung_id', session('user')->nguoi_dung_id)
                        ->orderByDesc('tao_luc')
                        ->limit(5)
                        ->get();

                    $headerChuaDoc = \Illuminate\Support\Facades\DB::table('thong_bao')
                        ->where('nguoi_dung_id', session('user')->nguoi_dung_id)
                        ->where('da_doc', 0)
                        ->count();

                @endphp


                <div class="notification-wrapper">


                    <button type="button" class="notification-bell" onclick="toggleNotifications()" title="Thông báo">

                        🔔

                        @if ($headerChuaDoc > 0)
                            <span class="notification-badge">

                                {{ $headerChuaDoc > 99 ? '99+' : $headerChuaDoc }}

                            </span>
                        @endif

                    </button>



                    {{-- HỘP THÔNG BÁO --}}

                    <div id="notificationDropdown" class="notification-dropdown">


                        <div class="notification-dropdown-header">

                            <div>

                                <strong>
                                    THÔNG BÁO
                                </strong>


                                @if ($headerChuaDoc > 0)
                                    <span class="notification-small-count">

                                        {{ $headerChuaDoc }} mới

                                    </span>
                                @endif

                            </div>

                        </div>



                        <div class="notification-dropdown-list">


                            @forelse($headerThongBao as $tb)
                                <div
                                    class="notification-dropdown-item
                                {{ !$tb->da_doc ? 'unread' : '' }}">


                                    <div class="notification-dropdown-icon">

                                        🔔

                                    </div>


                                    <div class="notification-dropdown-content">


                                        <strong>

                                            {{ $tb->tieu_de }}

                                        </strong>


                                        <p>

                                            {{ \Illuminate\Support\Str::limit($tb->noi_dung, 65) }}

                                        </p>


                                        <small>

                                            {{ \Carbon\Carbon::parse($tb->tao_luc)->format('d/m/Y H:i') }}

                                        </small>


                                    </div>


                                </div>


                            @empty


                                <div class="notification-no-data">

                                    <div>
                                        🔔
                                    </div>

                                    <p>
                                        Chưa có thông báo
                                    </p>

                                </div>
                            @endforelse


                        </div>



                        <a href="/notifications" class="notification-view-all">

                            XEM TẤT CẢ THÔNG BÁO

                        </a>


                    </div>

                </div>

            @endif



            {{-- =====================================================
             CHỈ HIỆN HỒ SƠ Ở DASHBOARD HỘI VIÊN
        ====================================================== --}}

            @if (request()->is('user/dashboard'))
                <a href="/user/profile" class="dashboard-btn profile-btn">

                    HỒ SƠ

                </a>
            @elseif(request()->is('user/profile'))
                <a href="/user/dashboard" class="dashboard-btn">

                    DASHBOARD

                </a>
            @else
                {{-- STAFF / TRAINER --}}

                <a href="/{{ session('dashboard_path', 'user/dashboard') }}" class="dashboard-btn">
                    DASHBOARD
                </a>
            @endif



            {{-- =====================================================
             ĐĂNG XUẤT
        ====================================================== --}}

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



    <script>
        function toggleNotifications() {

            const dropdown =
                document.getElementById('notificationDropdown');

            if (!dropdown) {
                return;
            }

            dropdown.classList.toggle('show');

        }



        /*
         * Bấm ra ngoài thì đóng hộp thông báo
         */

        document.addEventListener('click', function(event) {

            const wrapper =
                document.querySelector('.notification-wrapper');

            const dropdown =
                document.getElementById('notificationDropdown');


            if (!wrapper || !dropdown) {
                return;
            }


            if (!wrapper.contains(event.target)) {

                dropdown.classList.remove('show');

            }

        });
    </script>


</body>

</html>
