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

    <div class="member-welcome">

        <div class="welcome-small">
            GYMFIT • MEMBER AREA
        </div>

        <h1>
            Xin chào, {{ session('user')->ho_ten }}
        </h1>

        <p>
            Quản lý thông tin tập luyện và các dịch vụ của bạn.
        </p>

        <div class="role-badge">
            ● HỘI VIÊN
        </div>

    </div>


    <div class="member-stats">

        <div class="member-stat-card">

            <div class="stat-icon">
                🏋️
            </div>

            <div>
                <span>Gói tập</span>
                <strong>{{ $tongGoiTap }}</strong>
            </div>

        </div>


        <div class="member-stat-card">

            <div class="stat-icon">
                🥇
            </div>

            <div>
                <span>Gói PT</span>
                <strong>{{ $tongGoiPT }}</strong>
            </div>

        </div>


        <div class="member-stat-card">

            <div class="stat-icon">
                🏃
            </div>

            <div>
                <span>Lớp tập</span>
                <strong>{{ $tongLop }}</strong>
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

    </div>


    <div class="dashboard-grid">

        {{-- GÓI TẬP --}}

        <div class="member-card">

            <div class="member-card-header">

                <div>
                    <span class="card-label">
                        MY MEMBERSHIP
                    </span>

                    <h2>
                        Gói tập của tôi
                    </h2>
                </div>

            </div>


            @if ($goiTap->count() > 0)

                <div class="member-list">

                    @foreach ($goiTap as $goi)
                        <div class="member-list-item">

                            <div>

                                <h3>
                                    {{ $goi->ten_goi }}
                                </h3>

                                <p>
                                    Gói tập đang đăng ký
                                </p>

                            </div>


                            <span class="status-badge">
                                {{ $goi->trang_thai }}
                            </span>

                        </div>
                    @endforeach

                </div>
            @else
                <div class="member-empty">

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


        {{-- THÔNG BÁO --}}

        <div class="member-card">

            <div class="member-card-header">

                <div>
                    <span class="card-label">
                        NOTIFICATION
                    </span>

                    <h2>
                        Thông báo
                    </h2>
                </div>

            </div>


            @if ($thongBao->count() > 0)

                <div class="member-list">

                    @foreach ($thongBao as $tb)
                        <div class="member-list-item">

                            <div>

                                <h3>
                                    {{ $tb->tieu_de }}
                                </h3>

                                <p>
                                    {{ $tb->noi_dung }}
                                </p>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                <div class="member-empty">

                    <div class="empty-icon">
                        🔔
                    </div>

                    <h3>
                        Không có thông báo
                    </h3>

                    <p>
                        Hiện chưa có thông báo nào.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection
