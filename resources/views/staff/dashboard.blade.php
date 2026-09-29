@extends('layouts.member')

@section('title', 'Dashboard Nhân viên')
@section('role_name', 'Nhân viên')

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

    <div class="member-welcome">

        <div class="welcome-small">
            GYMFIT • STAFF AREA
        </div>

        <h1>
            Xin chào, {{ session('user')->ho_ten }}
        </h1>

        <p>
            Quản lý hội viên, check-in và các hoạt động của phòng Gym.
        </p>

        <div class="role-badge">
            ● NHÂN VIÊN
        </div>

    </div>


    <div class="member-stats">

        <div class="member-stat-card">

            <div class="stat-icon">
                👥
            </div>

            <div>
                <span>Tổng hội viên</span>
                <strong>{{ $tongHoiVien }}</strong>
            </div>

        </div>


        <div class="member-stat-card">

            <div class="stat-icon">
                🟢
            </div>

            <div>
                <span>Lượt Check-in</span>
                <strong>{{ $tongCheckIn }}</strong>
            </div>

        </div>


        <div class="member-stat-card">

            <div class="stat-icon">
                💳
            </div>

            <div>
                <span>Hóa đơn</span>
                <strong>{{ $tongHoaDon }}</strong>
            </div>

        </div>


        <div class="member-stat-card">

            <div class="stat-icon">
                📦
            </div>

            <div>
                <span>Đăng ký gói</span>
                <strong>{{ $tongDangKyGoi }}</strong>
            </div>

        </div>

    </div>


    <div class="member-card">

        <div class="member-card-header">

            <div>
                <span class="card-label">
                    RECENT ACTIVITY
                </span>

                <h2>
                    Hóa đơn gần đây
                </h2>
            </div>

        </div>


        @if ($hoaDonGanDay->count() > 0)

            <div class="member-list">

                @foreach ($hoaDonGanDay as $hoaDon)
                    <div class="member-list-item">

                        <div>

                            <h3>
                                {{ $hoaDon->ho_ten }}
                            </h3>

                            <p>
                                Hóa đơn thanh toán
                            </p>

                        </div>


                        <span class="status-badge">
                            {{ $hoaDon->trang_thai }}
                        </span>

                    </div>
                @endforeach

            </div>
        @else
            <div class="member-empty">

                <div class="empty-icon">
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

@endsection
