@extends('layouts.member')

@section('title', 'Dashboard Nhân viên')
@section('role_name', 'Nhân viên')

@section('menu')

    <a href="/">TRANG CHỦ</a>
    <a href="/about">GIỚI THIỆU</a>
    <a href="/packages">GÓI TẬP</a>
    <a href="/classes">LỚP TẬP</a>
    <a href="/trainers">HUẤN LUYỆN VIÊN</a>
    <a href="/contact">LIÊN HỆ</a>

@endsection


@section('content')

    <style>
        .staff-dashboard {
            max-width: 1400px;
            margin: auto;
        }

        .staff-hero {
            padding: 42px 45px;
            margin-bottom: 28px;
            border-radius: 18px;
            background:
                linear-gradient(110deg,
                    #111,
                    #171717,
                    #090909);
            border: 1px solid #252525;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .staff-label {
            color: #e50914;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .staff-hero h1 {
            margin: 0;
            color: #fff;
            font-size: 34px;
        }

        .staff-hero p {
            color: #888;
            margin: 10px 0 18px;
            font-size: 14px;
        }

        .staff-role {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .1);
            border: 1px solid rgba(229, 9, 20, .25);
            color: #ff4a52;
            font-size: 10px;
            font-weight: 800;
        }

        .staff-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .staff-stat {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 22px;
            min-height: 105px;
            background: #111;
            border: 1px solid #252525;
            border-radius: 15px;
            transition: .25s;
        }

        .staff-stat:hover {
            transform: translateY(-4px);
            border-color: #e50914;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .3);
        }

        .staff-stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: rgba(229, 9, 20, .1);
            font-size: 23px;
        }

        .staff-stat span {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .staff-stat strong {
            color: #fff;
            font-size: 28px;
        }

        .staff-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .staff-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 17px;
            overflow: hidden;
        }

        .staff-card-header {
            padding: 22px 24px;
            border-bottom: 1px solid #222;
        }

        .staff-card-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .staff-card-header h2 {
            margin: 5px 0 0;
            color: #fff;
            font-size: 19px;
        }

        .staff-list {
            padding: 7px 24px 18px;
        }

        .staff-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 0;
            border-bottom: 1px solid #202020;
        }

        .staff-item:last-child {
            border-bottom: none;
        }

        .staff-item-left {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .staff-item-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #191919;
            font-size: 18px;
        }

        .staff-item-info h3 {
            margin: 0 0 5px;
            color: #eee;
            font-size: 14px;
        }

        .staff-item-info p {
            margin: 0;
            color: #777;
            font-size: 11px;
        }

        .staff-badge {
            padding: 6px 10px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .1);
            border: 1px solid rgba(229, 9, 20, .2);
            color: #ff4a52;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .staff-empty {
            text-align: center;
            padding: 50px 20px;
        }

        .staff-empty-icon {
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

        .staff-empty h3 {
            margin: 0 0 7px;
            color: #ddd;
            font-size: 15px;
        }

        .staff-empty p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 1000px) {

            .staff-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .staff-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .staff-hero {
                padding: 30px 25px;
            }

            .staff-hero h1 {
                font-size: 26px;
            }

            .staff-stats {
                grid-template-columns: 1fr;
            }

        }
    </style>


    <div class="staff-dashboard">

        {{-- HERO --}}

        <div class="staff-hero">

            <div class="staff-label">
                GYMFIT • STAFF AREA
            </div>

            <h1>
                Xin chào, {{ session('user')->ho_ten }}
            </h1>

            <p>
                Quản lý hội viên, check-in và các hoạt động của phòng Gym.
            </p>

            <a
                href="/staff/check-in"
                style="
                    display:inline-block;
                    margin-top:18px;
                    padding:11px 16px;
                    border-radius:7px;
                    background:#e50914;
                    color:#fff;
                    text-decoration:none;
                    font-size:11px;
                    font-weight:800;
                    transition:.25s;
                "
                onmouseover="this.style.background='#ff1824'"
                onmouseout="this.style.background='#e50914'"
            >
                ✓ CHECK-IN HỘI VIÊN
            </a>

        </div>


        {{-- STATS --}}

        <div class="staff-stats">

            <div class="staff-stat">

                <div class="staff-stat-icon">
                    👥
                </div>

                <div>
                    <span>Tổng hội viên</span>
                    <strong>{{ $tongHoiVien }}</strong>
                </div>

            </div>


            <div class="staff-stat">

                <div class="staff-stat-icon">
                    🟢
                </div>

                <div>
                    <span>Lượt Check-in</span>
                    <strong>{{ $tongCheckIn }}</strong>
                </div>

            </div>


            <div class="staff-stat">

                <div class="staff-stat-icon">
                    💳
                </div>

                <div>
                    <span>Hóa đơn</span>
                    <strong>{{ $tongHoaDon }}</strong>
                </div>

            </div>


            <div class="staff-stat">

                <div class="staff-stat-icon">
                    📦
                </div>

                <div>
                    <span>Đăng ký gói</span>
                    <strong>{{ $tongDangKyGoi }}</strong>
                </div>

            </div>

        </div>


        {{-- CONTENT --}}

        <div class="staff-grid">

            {{-- HỘI VIÊN --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        MEMBERS
                    </div>

                    <h2>
                        Hội viên gần đây
                    </h2>

                </div>


                @if ($hoiVienGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($hoiVienGanDay as $hv)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        👤
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $hv->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $hv->email ?? 'Không có email' }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    HỘI VIÊN
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            👥
                        </div>

                        <h3>
                            Chưa có hội viên
                        </h3>

                        <p>
                            Hệ thống chưa có dữ liệu hội viên.
                        </p>

                    </div>

                @endif

            </div>


            {{-- CHECK IN --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        CHECK-IN
                    </div>

                    <h2>
                        Check-in gần đây
                    </h2>

                </div>


                @if ($checkInGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($checkInGanDay as $check)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        🟢
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $check->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $check->thoi_gian }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    CHECK-IN
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            🟢
                        </div>

                        <h3>
                            Chưa có check-in
                        </h3>

                        <p>
                            Chưa có lượt check-in nào gần đây.
                        </p>

                    </div>

                @endif

            </div>


            {{-- ĐĂNG KÝ GÓI --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        MEMBERSHIP
                    </div>

                    <h2>
                        Đăng ký gói gần đây
                    </h2>

                </div>


                @if ($dangKyGoiGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($dangKyGoiGanDay as $dk)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        📦
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $dk->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $dk->ten_goi }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    {{ $dk->trang_thai }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            📦
                        </div>

                        <h3>
                            Chưa có đăng ký
                        </h3>

                        <p>
                            Chưa có đăng ký gói nào gần đây.
                        </p>

                    </div>

                @endif

            </div>


            {{-- HÓA ĐƠN --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        PAYMENT
                    </div>

                   <a
                        href="/staff/payments"
                        style="
                            display:inline-block;
                            margin-top:10px;
                            color:#e50914;
                            font-size:11px;
                            font-weight:800;
                            text-decoration:none;
                        "
                    >
                        QUẢN LÝ HÓA ĐƠN →
                   </a>

                </div>


                @if ($hoaDonGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($hoaDonGanDay as $hoaDon)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        💳
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $hoaDon->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VNĐ
                                            •
                                            {{ $hoaDon->ngay_lap }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    {{ $hoaDon->trang_thai }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            💳
                        </div>

                        <h3>
                            Chưa có hóa đơn
                        </h3>

                        <p>
                            Hiện chưa có hóa đơn nào trong hệ thống.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
