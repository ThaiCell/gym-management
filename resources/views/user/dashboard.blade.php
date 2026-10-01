@extends('layouts.member')

@section('title', 'Dashboard Hội viên')
@section('role_name', 'Hội viên')

@section('menu')

    <a href="/">
        TRANG CHỦ
    </a>

    <a href="/about">
        GIỚI THIỆU
    </a>

    <a href="/packages">
        GÓI TẬP
    </a>

    <a href="/classes">
        LỚP TẬP
    </a>

    <a href="/trainers">
        HUẤN LUYỆN VIÊN
    </a>

    <a href="/contact">
        LIÊN HỆ
    </a>

@endsection


@section('content')

    <style>
        .dashboard-page {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* =========================
           WELCOME
        ========================= */

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            padding: 42px 45px;
            margin-bottom: 28px;
            border-radius: 18px;
            background:
                linear-gradient(110deg,
                    #111 0%,
                    #151515 55%,
                    #090909 100%);
            border: 1px solid #252525;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            right: -80px;
            top: -100px;
            background: #e50914;
            opacity: .08;
            border-radius: 50%;
        }

        .hero-label {
            color: #e50914;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .dashboard-hero h1 {
            margin: 0;
            color: #fff;
            font-size: 34px;
            font-weight: 800;
        }

        .dashboard-hero p {
            margin: 10px 0 18px;
            color: #999;
            font-size: 14px;
        }

        .hero-role {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .12);
            border: 1px solid rgba(229, 9, 20, .3);
            color: #ff4a52;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        /* =========================
           STATS
        ========================= */

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 22px;
            min-height: 105px;
            background: #111;
            border: 1px solid #252525;
            border-radius: 15px;
            color: inherit;
            text-decoration: none;
            transition: .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: #e50914;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .3);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: rgba(229, 9, 20, .10);
            font-size: 24px;
        }

        .stat-info span {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .stat-info strong {
            display: block;
            color: #fff;
            font-size: 28px;
            line-height: 1;
        }

        /* =========================
           CONTENT GRID
        ========================= */

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .dashboard-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        }

        .dashboard-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 24px;
            border-bottom: 1px solid #222;
        }

        .card-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .dashboard-card-header h2 {
            margin: 5px 0 0;
            color: #fff;
            font-size: 19px;
        }

        .view-link {
            color: #777;
            font-size: 11px;
            text-decoration: none;
            transition: .2s;
        }

        .view-link:hover {
            color: #e50914;
        }

        /* =========================
           LIST
        ========================= */

        .dashboard-list {
            padding: 8px 24px 18px;
        }

        .dashboard-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 17px 0;
            border-bottom: 1px solid #202020;
        }

        .dashboard-item:last-child {
            border-bottom: none;
        }

        .item-left {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #191919;
            border-radius: 10px;
            font-size: 18px;
        }

        .item-info {
            min-width: 0;
        }

        .item-info h3 {
            margin: 0 0 5px;
            color: #eee;
            font-size: 14px;
            font-weight: 700;
        }

        .item-info p {
            margin: 0;
            color: #777;
            font-size: 11px;
        }

        /* =========================
           BADGE
        ========================= */

        .status-badge {
            flex-shrink: 0;
            padding: 6px 10px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .10);
            border: 1px solid rgba(229, 9, 20, .22);
            color: #ff4b53;
            font-size: 10px;
            font-weight: 700;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty-box {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: #191919;
            font-size: 25px;
        }

        .empty-box h3 {
            margin: 0 0 7px;
            color: #ddd;
            font-size: 15px;
        }

        .empty-box p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .dashboard-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .dashboard-hero {
                padding: 30px 25px;
            }

            .dashboard-hero h1 {
                font-size: 26px;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .dashboard-card-header {
                padding: 19px;
            }

            .dashboard-list {
                padding-left: 19px;
                padding-right: 19px;
            }

        }
    </style>


    <div class="dashboard-page">

        {{-- HERO --}}

        <div class="dashboard-hero">

            <div class="hero-label">
                GYMFIT • MEMBER AREA
            </div>

            <h1>
                Xin chào, {{ session('user')->ho_ten }}
            </h1>

            <p>
                Quản lý thông tin tập luyện và các dịch vụ của bạn.
            </p>

            <div class="hero-role">
                ● HỘI VIÊN
            </div>

        </div>


        {{-- STATS --}}

        <div class="dashboard-stats">

            <a href="/user/payments" class="stat-card">

                <div class="stat-icon">
                    🏋️
                </div>

                <div class="stat-info">
                    <span>Gói tập</span>
                    <strong>{{ $tongGoiTap }}</strong>
                </div>

            </a>


            <div class="stat-card">

                <div class="stat-icon">
                    🥇
                </div>

                <div class="stat-info">
                    <span>Gói PT</span>
                    <strong>{{ $tongGoiPT }}</strong>
                </div>

            </div>


            <a href="/classes" class="stat-card">

                <div class="stat-icon">
                    🏃
                </div>

                <div class="stat-info">
                    <span>Lớp tập</span>
                    <strong>{{ $tongLop }}</strong>
                </div>

            </a>


            <a href="/user/payments" class="stat-card">

                <div class="stat-icon">
                    💳
                </div>

                <div class="stat-info">
                    <span>Hóa đơn</span>
                    <strong>{{ $tongHoaDon }}</strong>
                </div>

            </a>

        </div>


        {{-- CONTENT --}}

        <div class="dashboard-grid">

            {{-- GÓI TẬP --}}

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>
                        <div class="card-label">
                            MEMBERSHIP
                        </div>

                        <h2>
                            Gói tập của tôi
                        </h2>
                    </div>

                    <a href="/packages" class="view-link">
                        XEM GÓI →
                    </a>

                </div>


                @if ($goiTap->count() > 0)

                    <div class="dashboard-list">

                        @foreach ($goiTap as $goi)
                            <div class="dashboard-item">

                                <div class="item-left">

                                    <div class="item-icon">
                                        🏋️
                                    </div>

                                    <div class="item-info">

                                        <h3>
                                            {{ $goi->ten_goi }}
                                        </h3>

                                        <p>
                                            Gói tập đang đăng ký
                                        </p>

                                    </div>

                                </div>

                                <span class="status-badge">
                                    {{ $goi->trang_thai }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="empty-box">

                        <div class="empty-icon">
                            🏋️
                        </div>

                        <h3>
                            Chưa có gói tập
                        </h3>

                        <p>
                            Bạn chưa đăng ký gói tập nào.
                        </p>

                    </div>

                @endif

            </div>


            {{-- GÓI PT --}}

            <div class="dashboard-card">

                <div class="dashboard-card-header">
                    <div>
                        <div class="card-label">
                            PERSONAL TRAINER
                        </div>

                        <h2>
                            Gói PT của tôi
                        </h2>
                    </div>

                    <a href="/user/pt-schedule"
                    style="
                        color:#888;
                        font-size:11px;
                        font-weight:700;
                        text-decoration:none;
                        transition:.3s;
                    "
                    onmouseover="this.style.color='#e50914'"
                    onmouseout="this.style.color='#888'">
                        XEM LỊCH PT →
                    </a>
                </div>


                @if ($goiPT->count() > 0)

                    <div class="dashboard-list">

                        @foreach ($goiPT as $pt)
                            <div class="dashboard-item">

                                <div class="item-left">

                                    <div class="item-icon">
                                        💪
                                    </div>

                                    <div class="item-info">

                                        <h3>
                                            {{ $pt->ten_goi }}
                                        </h3>

                                        <p>
                                            PT: {{ $pt->ho_ten ?? 'Chưa phân công' }}
                                        </p>

                                    </div>

                                </div>

                                <span class="status-badge">
                                    {{ $pt->trang_thai }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="empty-box">

                        <div class="empty-icon">
                            💪
                        </div>

                        <h3>
                            Chưa có gói PT
                        </h3>

                        <p>
                            Bạn chưa đăng ký gói huấn luyện cá nhân.
                        </p>

                    </div>

                @endif

            </div>


            {{-- LỚP TẬP --}}

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>
                        <div class="card-label">
                            GROUP TRAINING
                        </div>

                        <h2>
                            Lớp tập của tôi
                        </h2>
                    </div>

                    <a href="/classes" class="view-link">
                        XEM LỚP →
                    </a>

                </div>


                @if ($lopTap->count() > 0)

                    <div class="dashboard-list">

                        @foreach ($lopTap as $lop)
                            <div class="dashboard-item">

                                <div class="item-left">

                                    <div class="item-icon">
                                        🏃
                                    </div>

                                    <div class="item-info">

                                        <h3>
                                            {{ $lop->ten_lop }}
                                        </h3>

                                        <p>
                                            {{ $lop->lich_tap ?? 'Lớp tập' }}
                                        </p>

                                    </div>

                                </div>

                                <span class="status-badge">
                                    {{ $lop->trang_thai }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="empty-box">

                        <div class="empty-icon">
                            🏃
                        </div>

                        <h3>
                            Chưa tham gia lớp
                        </h3>

                        <p>
                            Bạn chưa đăng ký lớp tập nào.
                        </p>

                    </div>

                @endif

            </div>


            {{-- HÓA ĐƠN --}}

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>
                        <div class="card-label">
                            PAYMENT
                        </div>

                        <h2>
                            Hóa đơn gần đây
                        </h2>
                    </div>

                    <a href="/payments" class="view-link">
                        XEM TẤT CẢ →
                    </a>

                </div>


                @if ($hoaDon->count() > 0)

                    <div class="dashboard-list">

                        @foreach ($hoaDon as $hd)
                            <div class="dashboard-item">

                                <div class="item-left">

                                    <div class="item-icon">
                                        💳
                                    </div>

                                    <div class="item-info">

                                        <h3>
                                            {{ number_format($hd->tong_tien, 0, ',', '.') }} VNĐ
                                        </h3>

                                        <p>
                                            {{ $hd->ngay_lap }}
                                            •
                                            {{ $hd->phuong_thuc_thanh_toan }}
                                        </p>

                                    </div>

                                </div>

                                <span class="status-badge">
                                    {{ $hd->trang_thai }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="empty-box">

                        <div class="empty-icon">
                            💳
                        </div>

                        <h3>
                            Chưa có hóa đơn
                        </h3>

                        <p>
                            Hiện chưa có hóa đơn nào.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
