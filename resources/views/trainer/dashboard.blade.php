@extends('layouts.member')

@section('title', 'Dashboard Huấn luyện viên')
@section('role_name', 'Huấn luyện viên')



@section('content')

    <style>
        .trainer-dashboard {
            max-width: 1400px;
            margin: auto;
        }

        .trainer-hero {
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

        .trainer-role {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .1);
            border: 1px solid rgba(229, 9, 20, .25);
            color: #ff4a52;
            font-size: 10px;
            font-weight: 800;
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
                padding: 30px 25px;
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
                ● HUẤN LUYỆN VIÊN
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

                @if (session('success'))
                    <div
                        style="
            margin: 18px 24px 0;
            padding: 12px 15px;
            border-radius: 8px;
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.25);
            color: #4ade80;
            font-size: 12px;
            font-weight: 700;
        ">
                        ✓ {{ session('success') }}
                    </div>
                @endif


                @if (session('error'))
                    <div
                        style="
            margin: 18px 24px 0;
            padding: 12px 15px;
            border-radius: 8px;
            background: rgba(229,9,20,.08);
            border: 1px solid rgba(229,9,20,.25);
            color: #ff4a52;
            font-size: 12px;
            font-weight: 700;
        ">
                        ⚠ {{ session('error') }}
                    </div>
                @endif


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
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xác nhận buổi PT này đã hoàn thành không?');">

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
