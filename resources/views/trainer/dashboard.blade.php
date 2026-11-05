@extends('layouts.dashboard')

@section('title', 'Dashboard Huấn luyện viên')
@section('role_name', 'Huấn luyện viên')



@section('content')




    <style>
        .tw-page {
            color: #eee
        }

        .tw-head {
            margin: 12px 0 24px
        }

        .tw-head small {
            color: #ef233c;
            font-weight: 800;
            letter-spacing: 2px
        }

        .tw-head h1 {
            font-size: 28px;
            margin: 7px 0;
            color: #fff
        }

        .tw-panel {
            background: linear-gradient(145deg, #151515, #101010);
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px #0003
        }

        .tw-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px
        }

        .tw-field label {
            display: block;
            color: #aaa;
            font-size: 12px;
            margin-bottom: 6px
        }

        .tw-field input,
        .tw-field select,
        .tw-field textarea {
            width: 100%;
            box-sizing: border-box;
            background: #090909;
            border: 1px solid #353535;
            color: #fff;
            border-radius: 8px;
            padding: 11px 12px;
            outline: none;
            transition: border-color .2s, box-shadow .2s
        }

        .tw-field input:focus,
        .tw-field select:focus,
        .tw-field textarea:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 3px #e5091420
        }

        .tw-field textarea {
            min-height: 85px
        }

        .tw-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            background: #e50914;
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 11px 17px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: .2s
        }

        .tw-btn:hover {
            background: #c9000b;
            color: #fff;
            transform: translateY(-1px)
        }

        .tw-table {
            width: 100%;
            border-collapse: collapse
        }

        .tw-table th,
        .tw-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #292929;
            font-size: 13px
        }

        .tw-table th {
            color: #ff3548;
            font-weight: 800
        }

        .tw-table tr:hover td {
            background: #ffffff05
        }

        .tw-muted {
            color: #888
        }

        .tw-alert {
            padding: 12px 15px;
            border: 1px solid #333;
            border-left: 4px solid #e50914;
            border-radius: 8px;
            margin: 0 0 16px;
            background: #171717;
            color: #eee
        }

        .tw-nav-wrap {
            margin: 4px 0 22px;
            padding: 8px;
            background: linear-gradient(135deg, #141414, #0e0e0e);
            border: 1px solid #292929;
            border-radius: 13px;
            box-shadow: 0 8px 24px #0003
        }

        .tw-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center
        }

        .tw-nav a {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border: 1px solid #333;
            border-radius: 9px;
            background: #191919;
            color: #cfcfcf;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .15px;
            transition: all .2s ease
        }

        .tw-nav a .tw-nav-icon {
            font-size: 14px;
            line-height: 1
        }

        .tw-nav a:hover {
            background: #241315;
            border-color: #a91b28;
            color: #fff;
            transform: translateY(-1px)
        }

        .tw-nav a.active {
            background: linear-gradient(135deg, #e50914, #a90812);
            border-color: #f02b3a;
            color: #fff;
            box-shadow: 0 4px 14px #e5091433
        }

        .tw-nav a.active:after {
            content: "";
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 4px;
            height: 2px;
            border-radius: 3px;
            background: #ffffff80
        }

        .tw-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 10px;
            border-radius: 20px;
            border: 1px solid #454545;
            background: #1c1c1c;
            color: #eee;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap
        }

        .tw-status:before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #aaa
        }

        .tw-status.is-active {
            border-color: #236b42;
            background: #10251a;
            color: #8ce0a9
        }

        .tw-status.is-active:before {
            background: #35c46a
        }

        .tw-status.is-cancelled {
            border-color: #79303a;
            background: #2a1115;
            color: #ff9aa3
        }

        .tw-status.is-cancelled:before {
            background: #f05260
        }

        @media(max-width:700px) {
            .tw-grid {
                grid-template-columns: 1fr
            }

            .tw-panel {
                overflow-x: auto
            }

            .tw-nav {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .tw-nav a {
                justify-content: center;
                padding: 11px 8px
            }
        }
    </style>
    <div class="tw-page">
        @if (session('success'))
            <div class="tw-alert">✓ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="tw-alert">! {{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="tw-alert">{{ $errors->first() }}</div>
        @endif
    </div>

    {{-- POPUP THONG BAO FLASH: dung chung cho dashboard --}}
    @if (session()->has('success') || session()->has('error'))
        @php
            $flashMessage = session('success') ?? session('error');
            $flashType = session()->has('success') ? 'success' : 'error';
        @endphp
        <div id="gymDashboardFlash" class="gym-dashboard-flash {{ $flashType }}" role="alert" aria-live="assertive">
            <span class="gym-dashboard-flash-icon">{{ $flashType === 'success' ? '✓' : '!' }}</span>
            <div class="gym-dashboard-flash-content">
                <strong>{{ $flashType === 'success' ? 'Thành công' : 'Có lỗi xảy ra' }}</strong>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" onclick="document.getElementById('gymDashboardFlash')?.remove()"
                aria-label="Đóng">&times;</button>
        </div>
        <style>
            .gym-dashboard-flash {
                position: fixed;
                z-index: 999999;
                top: 92px;
                right: 24px;
                width: min(420px, calc(100vw - 32px));
                display: flex;
                align-items: center;
                gap: 13px;
                padding: 16px 18px;
                border-radius: 12px;
                background: #171717;
                border: 1px solid #3b3b3b;
                box-shadow: 0 14px 40px #0009;
                color: #fff;
                font: 500 14px/1.45 system-ui, sans-serif;
                animation: gymFlashIn .25s ease-out
            }

            .gym-dashboard-flash.success {
                border-left: 4px solid #22c55e
            }

            .gym-dashboard-flash.error {
                border-left: 4px solid #ef4444
            }

            .gym-dashboard-flash-icon {
                width: 30px;
                height: 30px;
                flex: 0 0 30px;
                display: grid;
                place-items: center;
                border-radius: 50%;
                font-weight: 900;
                background: #252525;
                color: #4ade80
            }

            .gym-dashboard-flash.error .gym-dashboard-flash-icon {
                color: #f87171
            }

            .gym-dashboard-flash-content {
                display: flex;
                flex: 1;
                min-width: 0;
                flex-direction: column;
                gap: 3px
            }

            .gym-dashboard-flash-content strong {
                font-size: 13px
            }

            .gym-dashboard-flash-content span {
                overflow-wrap: anywhere;
                color: #ddd
            }

            .gym-dashboard-flash button {
                border: 0;
                background: transparent;
                color: #aaa;
                font-size: 24px;
                cursor: pointer;
                padding: 0 2px
            }

            .gym-dashboard-flash button:hover {
                color: #fff
            }

            @keyframes gymFlashIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }

            @media(max-width:600px) {
                .gym-dashboard-flash {
                    top: 82px;
                    right: 12px
                }
            }
        </style>
        <script>
            (function() {
                const el = document.getElementById('gymDashboardFlash');
                if (!el) return;
                setTimeout(() => {
                    if (el.isConnected) {
                        el.style.transition = 'opacity .3s,transform .3s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(-8px)';
                        setTimeout(() => el.remove(), 320)
                    }
                }, 5000)
            })();
        </script>
    @endif

    <style>
        .trainer-role {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 2px;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .10);
            border: 1px solid rgba(229, 9, 20, .45);
            color: #ff4651;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .trainer-role-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff2633;
            box-shadow: 0 0 8px rgba(255, 38, 51, .75);
        }

        .trainer-dashboard {
            max-width: 1400px;
            margin: auto;
        }

        .trainer-hero {
            padding: 30px 32px;
            margin-bottom: 28px;
            border-radius: 14px;
            background: linear-gradient(110deg, #111, #171717, #090909);
            border: 1px solid #252525;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .trainer-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .trainer-hero-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 18px;
            border-radius: 8px;
            background: #202020;
            border: 1px solid #383838;
            color: #f5f5f5;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            transition: .2s ease;
        }

        .trainer-hero-btn:hover {
            background: #2a2a2a;
            border-color: #e50914;
            color: #fff;
            transform: translateY(-2px);
        }

        .trainer-hero-btn.primary {
            background: #e50914;
            border-color: #e50914;
            color: #fff;
        }

        .trainer-hero-btn.primary:hover {
            background: #c9000b;
            border-color: #c9000b;
        }

        .trainer-label {
            color: #e50914;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .trainer-hero h1 {
            margin: 0;
            color: #fff;
            font-size: 34px;
        }

        .trainer-hero p {
            margin: 10px 0 18px;
            color: #888;
            font-size: 14px;
        }


        .trainer-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .trainer-stat {
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

        .trainer-stat:hover {
            transform: translateY(-4px);
            border-color: #e50914;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .3);
        }

        .trainer-stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: rgba(229, 9, 20, .1);
            font-size: 23px;
        }

        .trainer-stat span {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .trainer-stat strong {
            color: #fff;
            font-size: 28px;
        }

        .trainer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .trainer-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 17px;
            overflow: hidden;
        }

        .trainer-card-header {
            padding: 22px 24px;
            border-bottom: 1px solid #222;
        }

        .trainer-card-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .trainer-card-header h2 {
            margin: 5px 0 0;
            color: #fff;
            font-size: 19px;
        }

        .trainer-list {
            padding: 7px 24px 18px;
        }

        .trainer-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 0;
            border-bottom: 1px solid #202020;
        }

        .trainer-item:last-child {
            border-bottom: none;
        }

        .trainer-item-left {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .trainer-item-icon {
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

        .trainer-item-info h3 {
            margin: 0 0 5px;
            color: #eee;
            font-size: 14px;
        }

        .trainer-item-info p {
            margin: 0;
            color: #777;
            font-size: 11px;
        }

        .trainer-badge {
            padding: 6px 10px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .1);
            border: 1px solid rgba(229, 9, 20, .2);
            color: #ff4a52;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .trainer-empty {
            text-align: center;
            padding: 50px 20px;
        }

        .trainer-empty-icon {
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

        .trainer-empty h3 {
            margin: 0 0 7px;
            color: #ddd;
            font-size: 15px;
        }

        .trainer-empty p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 1000px) {

            .trainer-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .trainer-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .trainer-hero {
                padding: 26px 20px;
            }

            .trainer-hero-actions {
                gap: 8px;
            }

            .trainer-hero-btn {
                width: 100%;
                justify-content: flex-start;
            }

            .trainer-hero h1 {
                font-size: 26px;
            }

            .trainer-stats {
                grid-template-columns: 1fr;
            }

        }
    </style>


    <div class="trainer-dashboard">

        {{-- HERO --}}

        <div class="trainer-hero">

            <div class="trainer-label">
                GYMFIT • TRAINER AREA
            </div>

            <h1>
                Xin chào, {{ session('user')->ho_ten }}
            </h1>

            <p>
                Quản lý học viên, gói PT và lịch huấn luyện của bạn.
            </p>

            <div class="trainer-role">
                <span class="trainer-role-dot"></span> HUẤN LUYỆN VIÊN
            </div>

            <div class="trainer-hero-actions">
                <a href="/trainer/students" class="trainer-hero-btn primary">♧ &nbsp; HỌC VIÊN CỦA TÔI</a>
                <a href="/trainer/schedules" class="trainer-hero-btn">✉ &nbsp; QUẢN LÝ LỊCH PT</a>
                <a href="/trainer/plans" class="trainer-hero-btn">✓ &nbsp; GIÁO ÁN TẬP LUYỆN</a>
                <a href="/trainer/logs" class="trainer-hero-btn">▤ &nbsp; NHẬT KÝ BUỔI TẬP</a>
                <a href="/trainer/progress" class="trainer-hero-btn">↗ &nbsp; TIẾN ĐỘ HỌC VIÊN</a>
            </div>

        </div>


        {{-- STATS --}}

        <div class="trainer-stats">

            <div class="trainer-stat">

                <div class="trainer-stat-icon">
                    👥
                </div>

                <div>
                    <span>Học viên</span>
                    <strong>{{ $tongHocVien }}</strong>
                </div>

            </div>


            <div class="trainer-stat">

                <div class="trainer-stat-icon">
                    🥇
                </div>

                <div>
                    <span>Gói PT</span>
                    <strong>{{ $tongGoiPT }}</strong>
                </div>

            </div>


            <div class="trainer-stat">

                <div class="trainer-stat-icon">
                    📅
                </div>

                <div>
                    <span>Lịch PT</span>
                    <strong>{{ $tongLichPT }}</strong>
                </div>

            </div>


            <div class="trainer-stat">

                <div class="trainer-stat-icon">
                    🏋️
                </div>

                <div>
                    <span>Buổi huấn luyện</span>
                    <strong>{{ $tongLichPT }}</strong>
                </div>

            </div>

        </div>


        {{-- CONTENT --}}

        <div class="trainer-grid">

            {{-- HỌC VIÊN --}}

            <div class="trainer-card">

                <div class="trainer-card-header">

                    <div class="trainer-card-label">
                        MY MEMBERS
                    </div>

                    <h2>
                        Học viên của tôi
                    </h2>

                </div>


                @if ($hocVien->count() > 0)

                    <div class="trainer-list">

                        @foreach ($hocVien as $hv)
                            <div class="trainer-item">

                                <div class="trainer-item-left">

                                    <div class="trainer-item-icon">
                                        👤
                                    </div>

                                    <div class="trainer-item-info">

                                        <h3>
                                            {{ $hv->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $hv->ten_goi_pt }}
                                        </p>

                                    </div>

                                </div>

                                <span class="trainer-badge">
                                    HỌC VIÊN
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="trainer-empty">

                        <div class="trainer-empty-icon">
                            👥
                        </div>

                        <h3>
                            Chưa có học viên
                        </h3>

                        <p>
                            Hiện chưa có học viên được phân công.
                        </p>

                    </div>

                @endif

            </div>


            {{-- LỊCH PT --}}

            <div class="trainer-card">

                <div class="trainer-card-header">

                    <div class="trainer-card-label">
                        TRAINING SCHEDULE
                    </div>

                    <h2>
                        Lịch PT gần đây
                    </h2>

                </div>


                {{-- THÔNG BÁO --}}
                @if ($lichPT->count() > 0)

                    <div class="trainer-list">

                        @foreach ($lichPT as $lich)
                            @php

                                $batDau = \Carbon\Carbon::parse($lich->thoi_gian_bat_dau);

                                $ketThuc = \Carbon\Carbon::parse($lich->thoi_gian_ket_thuc);

                                $status = mb_strtolower($lich->trang_thai ?? '');

                                $daDat = str_contains($status, 'đặt') || str_contains($status, 'dat');

                                $daHoanThanh =
                                    str_contains($status, 'hoàn thành') || str_contains($status, 'hoan thanh');

                                $daHuy = str_contains($status, 'hủy') || str_contains($status, 'huy');

                                $coTheHoanThanh = $daDat && $ketThuc->isPast();

                            @endphp


                            <div class="trainer-item"
                                style="
                        align-items:flex-start;
                    ">

                                {{-- THÔNG TIN --}}

                                <div class="trainer-item-left">

                                    <div class="trainer-item-icon">
                                        📅
                                    </div>

                                    <div class="trainer-item-info">

                                        <h3>
                                            {{ $lich->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $lich->ten_goi_pt }}
                                        </p>

                                        <p style="margin-top:5px;">

                                            📅
                                            {{ $batDau->format('d/m/Y') }}

                                            &nbsp;&nbsp;

                                            ⏰
                                            {{ $batDau->format('H:i') }}
                                            →
                                            {{ $ketThuc->format('H:i') }}

                                        </p>

                                        <p
                                            style="
                                margin-top:5px;
                                color:#555;
                            ">

                                            Còn lại:
                                            {{ $lich->so_buoi_con_lai }}
                                            buổi

                                        </p>

                                    </div>

                                </div>


                                {{-- TRẠNG THÁI + NÚT --}}

                                <div
                                    style="
                        display:flex;
                        flex-direction:column;
                        align-items:flex-end;
                        gap:9px;
                    ">

                                    @if ($daHoanThanh)
                                        <span class="trainer-badge"
                                            style="
                                    color:#4ade80;
                                    background:rgba(74,222,128,.08);
                                    border-color:rgba(74,222,128,.2);
                                ">
                                            ✓ ĐÃ HOÀN THÀNH
                                        </span>
                                    @elseif($daHuy)
                                        <span class="trainer-badge"
                                            style="
                                    color:#999;
                                    background:#1c1c1c;
                                    border-color:#333;
                                ">
                                            ● ĐÃ HỦY
                                        </span>
                                    @else
                                        <span class="trainer-badge">
                                            ● ĐÃ ĐẶT LỊCH
                                        </span>
                                    @endif


                                    {{-- NÚT HOÀN THÀNH --}}

                                    @if ($coTheHoanThanh)
                                        <form action="{{ url('/trainer/pt-schedule/' . $lich->lich_pt_id . '/complete') }}"
                                            method="POST"
                                            data-confirm-submit="Bạn có chắc chắn muốn xác nhận buổi PT này đã hoàn thành không?">

                                            @csrf

                                            <button type="submit"
                                                style="
                                        border:0;
                                        background:#e50914;
                                        color:#fff;
                                        padding:8px 12px;
                                        border-radius:5px;
                                        font-size:10px;
                                        font-weight:800;
                                        cursor:pointer;
                                    ">
                                                ✓ HOÀN THÀNH
                                            </button>

                                        </form>
                                    @elseif($daDat)
                                        <span
                                            style="
                                color:#666;
                                font-size:10px;
                                font-weight:700;
                            ">
                                            CHƯA ĐẾN GIỜ HOÀN THÀNH
                                        </span>
                                    @endif

                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="trainer-empty">

                        <div class="trainer-empty-icon">
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

        </div>

    </div>

@endsection
