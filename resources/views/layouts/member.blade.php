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

            position: sticky;
            top: 0;
            z-index: 1000;
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
            padding: 10px 37.5px;
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
            min-height: calc(100vh - 75px);
            padding: 45px 60px 70px;
            max-width: 1500px;
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


            {{-- HỒ SƠ / DASHBOARD --}}
            @if (request()->is('user/dashboard'))

                {{-- Hội viên đang ở Dashboard → hiện nút HỒ SƠ --}}
                <a href="/user/profile" class="dashboard-btn profile-btn">
                    HỒ SƠ
                </a>
            @elseif (request()->is('user/profile'))
                {{-- Hội viên đang ở Hồ sơ → quay về Dashboard --}}
                <a href="/user/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>
            @elseif (request()->is('staff/*'))
                {{-- Nhân viên → quay về Dashboard nhân viên --}}
                <a href="/staff/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>
            @elseif (request()->is('trainer/*'))
                {{-- PT → quay về Dashboard PT --}}
                <a href="/trainer/dashboard" class="dashboard-btn">
                    DASHBOARD
                </a>
            @else
                {{-- Các trang khác --}}
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

</body>

</html>
