<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            text-align: center;
            font-size: 18px;
        }

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
        }
    </style>
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

            <a href="/dashboard" class="active">
                <div class="menu-icon">⌂</div>
                <span>Dashboard</span>
            </a>

            <div class="menu-title">Quản lý</div>

            <a href="/admin/members">
                <div class="menu-icon">👤</div>
                <span>Thành viên</span>
            </a>

            <a href="/admin/packages">
                <div class="menu-icon">💳</div>
                <span>Gói tập</span>
            </a>

            <a href="/admin/trainers">
                <div class="menu-icon">🏋</div>
                <span>Huấn luyện viên</span>
            </a>

            <a href="/admin/schedules">
                <div class="menu-icon">📅</div>
                <span>Lịch tập</span>
            </a>

            <div class="menu-title">Hệ thống</div>

            <a href="/admin/payments">
                <div class="menu-icon">💰</div>
                <span>Thanh toán</span>
            </a>

            <a href="/admin/statistics">
                <div class="menu-icon">📊</div>
                <span>Thống kê</span>
            </a>

            <a href="#">
                <div class="menu-icon">⚙</div>
                <span>Cài đặt</span>
            </a>
            <form action="/logout" method="POST" style="margin-top: 20px;"
                onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">
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

</body>

</html>
