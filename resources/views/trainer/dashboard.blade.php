@extends('layouts.member')

@section('title', 'Dashboard Huấn luyện viên')
@section('role_name', 'Huấn luyện viên')

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
            GYMFIT • TRAINER AREA
        </div>

        <h1>
            Xin chào, {{ session('user')->ho_ten }}
        </h1>

        <p>
            Quản lý học viên, gói PT và lịch huấn luyện của bạn.
        </p>

        <div class="role-badge">
            ● HUẤN LUYỆN VIÊN
        </div>

    </div>


    <div class="member-stats">

        <div class="member-stat-card">

            <div class="stat-icon">
                👥
            </div>

            <div>
                <span>Học viên</span>
                <strong>{{ $tongHocVien }}</strong>
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
                📅
            </div>

            <div>
                <span>Lịch PT</span>
                <strong>{{ $tongLichPT }}</strong>
            </div>

        </div>


        <div class="member-stat-card">

            <div class="stat-icon">
                🏋️
            </div>

            <div>
                <span>Huấn luyện</span>
                <strong>{{ $tongGoiPT }}</strong>
            </div>

        </div>

    </div>


    <div class="member-card">

        <div class="member-card-header">

            <div>
                <span class="card-label">
                    TRAINING SCHEDULE
                </span>

                <h2>
                    Lịch PT gần đây
                </h2>
            </div>

        </div>


        @if ($lichPT->count() > 0)

            <div class="member-list">

                @foreach ($lichPT as $lich)
                    <div class="member-list-item">

                        <div>

                            <h3>
                                {{ $lich->ho_ten }}
                            </h3>

                            <p>
                                {{ $lich->thoi_gian_bat_dau }}
                                -
                                {{ $lich->thoi_gian_ket_thuc }}
                            </p>

                        </div>


                        <span class="status-badge">
                            {{ $lich->trang_thai }}
                        </span>

                    </div>
                @endforeach

            </div>
        @else
            <div class="member-empty">

                <div class="empty-icon">
                    📅
                </div>

                <h3>
                    Chưa có lịch PT
                </h3>

                <p>
                    Hiện chưa có lịch huấn luyện nào.
                </p>

            </div>

        @endif

    </div>

@endsection
