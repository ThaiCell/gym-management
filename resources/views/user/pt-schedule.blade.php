@extends('layouts.member')

@section('title', 'Lịch PT - GYM MANAGEMENT')

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

    /* =========================================
       PAGE
    ========================================= */

    .schedule-page {
        max-width: 1200px;
        margin: 0 auto;
    }


    /* =========================================
       HEADING
    ========================================= */

    .schedule-heading {
        margin-bottom: 35px;
    }

    .schedule-heading-top {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
    }

    .schedule-label {
        color: #e50914;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        margin-bottom: 10px;
    }

    .schedule-heading h1 {
        font-size: 34px;
        font-weight: 900;
        margin: 0 0 8px;
        color: #fff;
    }

    .schedule-heading p {
        color: #888;
        font-size: 14px;
        margin: 0;
    }


    /* =========================================
       BUTTON ĐẶT LỊCH
    ========================================= */

    .booking-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 12px 18px;

        background: #e50914;
        color: #fff;

        border-radius: 6px;

        font-size: 12px;
        font-weight: 800;

        text-decoration: none;
        white-space: nowrap;

        transition: .25s;

        box-shadow:
            0 8px 20px rgba(229, 9, 20, .15);
    }

    .booking-btn:hover {
        background: #ff2633;
        color: #fff;

        transform: translateY(-2px);

        box-shadow:
            0 12px 25px rgba(229, 9, 20, .25);
    }


    /* =========================================
       ALERT
    ========================================= */

    .schedule-alert {
        padding: 14px 17px;
        border-radius: 7px;
        margin-bottom: 22px;

        font-size: 12px;
        font-weight: 600;
    }

    .schedule-alert.success {
        color: #4ade80;
        background: rgba(74, 222, 128, .08);
        border: 1px solid rgba(74, 222, 128, .2);
    }

    .schedule-alert.error {
        color: #ff4757;
        background: rgba(229, 9, 20, .08);
        border: 1px solid rgba(229, 9, 20, .2);
    }


    /* =========================================
       SUMMARY
    ========================================= */

    .schedule-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);

        gap: 18px;

        margin-bottom: 30px;
    }

    .summary-card {
        background: #111;

        border: 1px solid #272727;

        border-radius: 7px;

        padding: 22px;

        display: flex;
        align-items: center;

        gap: 16px;

        transition: .3s;
    }

    .summary-card:hover {
        border-color: #e50914;

        transform: translateY(-2px);
    }

    .summary-icon {
        width: 48px;
        height: 48px;

        background: rgba(229, 9, 20, .12);

        border: 1px solid rgba(229, 9, 20, .3);

        border-radius: 6px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;

        flex-shrink: 0;
    }

    .summary-info span {
        display: block;

        color: #888;

        font-size: 11px;

        margin-bottom: 4px;
    }

    .summary-info strong {
        display: block;

        color: #fff;

        font-size: 23px;

        font-weight: 800;
    }


    /* =========================================
       SCHEDULE CONTAINER
    ========================================= */

    .schedule-container {
        background: #111;

        border: 1px solid #272727;

        border-radius: 7px;

        overflow: hidden;
    }

    .schedule-container-header {
        padding: 22px 25px;

        border-bottom: 1px solid #272727;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;
    }

    .schedule-container-header h2 {
        font-size: 20px;

        font-weight: 800;

        color: #fff;

        margin: 0;
    }

    .schedule-container-header span {
        color: #777;

        font-size: 12px;

        white-space: nowrap;
    }


    /* =========================================
       LIST
    ========================================= */

    .schedule-list {
        display: flex;

        flex-direction: column;
    }


    /* =========================================
       ITEM
    ========================================= */

    .schedule-item {
        display: grid;

        grid-template-columns: 130px 1fr auto;

        gap: 25px;

        align-items: center;

        padding: 23px 25px;

        border-bottom: 1px solid #222;

        transition: .25s;
    }

    .schedule-item:last-child {
        border-bottom: none;
    }

    .schedule-item:hover {
        background: #151515;
    }


    /* =========================================
       DATE
    ========================================= */

    .schedule-date {
        text-align: center;

        background: #0b0b0b;

        border: 1px solid #292929;

        border-radius: 6px;

        padding: 12px 10px;
    }

    .schedule-date .day {
        display: block;

        color: #e50914;

        font-size: 25px;

        font-weight: 900;

        line-height: 1;

        margin-bottom: 5px;
    }

    .schedule-date .month {
        display: block;

        color: #aaa;

        font-size: 11px;

        text-transform: uppercase;

        font-weight: 700;
    }


    /* =========================================
       INFO
    ========================================= */

    .schedule-info h3 {
        color: #fff;

        font-size: 16px;

        font-weight: 800;

        margin: 0 0 8px;
    }

    .schedule-info p {
        color: #888;

        font-size: 12px;

        margin: 0 0 5px;
    }

    .schedule-info p:last-child {
        margin-bottom: 0;
    }

    .schedule-info .trainer {
        color: #ddd;
    }

    .schedule-info .trainer strong {
        color: #e50914;
    }


    /* =========================================
       TIME
    ========================================= */

    .schedule-time {
        margin-top: 9px;

        display: inline-flex;

        align-items: center;

        gap: 7px;

        color: #fff;

        background: #1a1a1a;

        border: 1px solid #303030;

        padding: 6px 10px;

        border-radius: 4px;

        font-size: 11px;

        font-weight: 700;
    }


    /* =========================================
       STATUS
    ========================================= */

    .schedule-status {
        text-align: right;
    }

    .status-badge {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 7px 12px;

        border-radius: 20px;

        font-size: 10px;

        font-weight: 800;

        white-space: nowrap;
    }

    .status-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: currentColor;
    }

    .status-booked {
        color: #ff4757;

        background: rgba(229, 9, 20, .1);

        border: 1px solid rgba(229, 9, 20, .25);
    }

    .status-completed {
        color: #4ade80;

        background: rgba(74, 222, 128, .08);

        border: 1px solid rgba(74, 222, 128, .2);
    }

    .status-cancelled {
        color: #999;

        background: #1c1c1c;

        border: 1px solid #333;
    }

    .status-default {
        color: #facc15;

        background: rgba(250, 204, 21, .08);

        border: 1px solid rgba(250, 204, 21, .2);
    }


    /* =========================================
       CANCEL BUTTON
    ========================================= */

    .cancel-form {
        margin-top: 10px;
    }

    .cancel-btn {
        background: transparent;

        border: 1px solid #444;

        color: #999;

        padding: 7px 12px;

        border-radius: 5px;

        font-size: 10px;

        font-weight: 800;

        cursor: pointer;

        transition: .25s;
    }

    .cancel-btn:hover {
        border-color: #e50914;

        color: #e50914;

        background: rgba(229, 9, 20, .05);
    }


    /* =========================================
       EMPTY
    ========================================= */

    .schedule-empty {
        padding: 80px 30px;

        text-align: center;
    }

    .schedule-empty-icon {
        width: 75px;
        height: 75px;

        margin: 0 auto 20px;

        background: #191919;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 31px;
    }

    .schedule-empty h3 {
        color: #fff;

        font-size: 18px;

        margin: 0 0 8px;
    }

    .schedule-empty p {
        color: #777;

        font-size: 13px;

        margin: 0 0 20px;
    }

    .schedule-empty a {
        display: inline-block;

        background: #e50914;

        color: #fff;

        padding: 11px 20px;

        border-radius: 5px;

        font-size: 12px;

        font-weight: 800;

        text-decoration: none;

        transition: .3s;
    }

    .schedule-empty a:hover {
        background: #ff2633;

        transform: translateY(-1px);
    }


    /* =========================================
       RESPONSIVE TABLET
    ========================================= */

    @media (max-width: 900px) {

        .schedule-summary {
            grid-template-columns: 1fr;
        }

        .schedule-item {
            grid-template-columns: 100px 1fr;

            gap: 18px;
        }

        .schedule-status {
            grid-column: 2;

            text-align: left;
        }

    }


    /* =========================================
       RESPONSIVE MOBILE
    ========================================= */

    @media (max-width: 600px) {

        .schedule-heading-top {
            flex-direction: column;

            align-items: flex-start;
        }

        .booking-btn {
            width: 100%;
        }

        .schedule-heading h1 {
            font-size: 27px;
        }

        .schedule-container-header {
            padding: 18px;
        }

        .schedule-container-header h2 {
            font-size: 17px;
        }

        .schedule-item {
            grid-template-columns: 1fr;

            gap: 15px;

            padding: 20px;
        }

        .schedule-date {
            width: 90px;
        }

        .schedule-status {
            grid-column: auto;

            text-align: left;
        }

    }

</style>


<div class="schedule-page">


    {{-- =========================================
         TIÊU ĐỀ
    ========================================= --}}

    <div class="schedule-heading">

        <div class="schedule-heading-top">

            <div>

                <div class="schedule-label">
                    PERSONAL TRAINER
                </div>

                <h1>
                    LỊCH PT CỦA TÔI
                </h1>

                <p>
                    Theo dõi các buổi tập cá nhân với huấn luyện viên của bạn.
                </p>

            </div>


            {{-- NÚT ĐẶT LỊCH PT --}}

            <a
                href="/user/pt-schedule/create"
                class="booking-btn"
            >
                + ĐẶT LỊCH PT
            </a>

        </div>

    </div>


    {{-- =========================================
         THÔNG BÁO
    ========================================= --}}

    @if (session('success'))

        <div class="schedule-alert success">

            ✓ {{ session('success') }}

        </div>

    @endif


    @if (session('error'))

        <div class="schedule-alert error">

            ⚠ {{ session('error') }}

        </div>

    @endif


    {{-- =========================================
         THỐNG KÊ
    ========================================= --}}

    @php

        $tongLich = $lichPT->count();

        $lichDaDat = $lichPT->filter(function ($lich) {

            $status = mb_strtolower(
                $lich->trang_thai ?? ''
            );

            return str_contains($status, 'đặt')
                || str_contains($status, 'dat');

        })->count();


        $lichHoanThanh = $lichPT->filter(function ($lich) {

            $status = mb_strtolower(
                $lich->trang_thai ?? ''
            );

            return str_contains($status, 'hoàn thành')
                || str_contains($status, 'hoan thanh');

        })->count();

    @endphp


    <div class="schedule-summary">


        {{-- TỔNG --}}

        <div class="summary-card">

            <div class="summary-icon">
                📅
            </div>

            <div class="summary-info">

                <span>
                    TỔNG LỊCH TẬP
                </span>

                <strong>
                    {{ $tongLich }}
                </strong>

            </div>

        </div>


        {{-- ĐÃ ĐẶT --}}

        <div class="summary-card">

            <div class="summary-icon">
                ⏰
            </div>

            <div class="summary-info">

                <span>
                    ĐÃ ĐẶT
                </span>

                <strong>
                    {{ $lichDaDat }}
                </strong>

            </div>

        </div>


        {{-- HOÀN THÀNH --}}

        <div class="summary-card">

            <div class="summary-icon">
                ✓
            </div>

            <div class="summary-info">

                <span>
                    ĐÃ HOÀN THÀNH
                </span>

                <strong>
                    {{ $lichHoanThanh }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================
         DANH SÁCH LỊCH
    ========================================= --}}

    <div class="schedule-container">


        {{-- HEADER DANH SÁCH --}}

        <div class="schedule-container-header">

            <h2>
                LỊCH TẬP CỦA BẠN
            </h2>

            <span>
                {{ $tongLich }} buổi
            </span>

        </div>


        @if ($lichPT->count() > 0)


            <div class="schedule-list">


                @foreach ($lichPT as $lich)


                    @php

                        $batDau = \Carbon\Carbon::parse(
                            $lich->thoi_gian_bat_dau
                        );

                        $ketThuc = \Carbon\Carbon::parse(
                            $lich->thoi_gian_ket_thuc
                        );

                        $status = mb_strtolower(
                            $lich->trang_thai ?? ''
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | XÁC ĐỊNH TRẠNG THÁI
                        |--------------------------------------------------------------------------
                        */

                        if (
                            str_contains($status, 'hoàn thành') ||
                            str_contains($status, 'hoan thanh')
                        ) {

                            $statusClass = 'status-completed';

                            $statusText = 'ĐÃ HOÀN THÀNH';

                        } elseif (
                            str_contains($status, 'hủy') ||
                            str_contains($status, 'huy')
                        ) {

                            $statusClass = 'status-cancelled';

                            $statusText = 'ĐÃ HỦY';

                        } elseif (
                            str_contains($status, 'đặt') ||
                            str_contains($status, 'dat')
                        ) {

                            $statusClass = 'status-booked';

                            $statusText = 'ĐÃ ĐẶT LỊCH';

                        } else {

                            $statusClass = 'status-default';

                            $statusText = strtoupper(
                                $lich->trang_thai
                                ?? 'CHƯA XÁC ĐỊNH'
                            );

                        }

                    @endphp


                    <div class="schedule-item">


                        {{-- =====================================
                             NGÀY
                        ====================================== --}}

                        <div class="schedule-date">

                            <span class="day">
                                {{ $batDau->format('d') }}
                            </span>

                            <span class="month">
                                {{ $batDau->format('m/Y') }}
                            </span>

                        </div>


                        {{-- =====================================
                             THÔNG TIN
                        ====================================== --}}

                        <div class="schedule-info">

                            <h3>
                                {{ $lich->ten_goi_pt }}
                            </h3>


                            <p class="trainer">

                                👨‍🏫 PT:

                                <strong>
                                    {{ $lich->ten_pt }}
                                </strong>

                            </p>


                            <p>

                                📅

                                {{ $batDau
                                    ->locale('vi')
                                    ->translatedFormat('l, d/m/Y')
                                }}

                            </p>


                            <div class="schedule-time">

                                ⏰

                                {{ $batDau->format('H:i') }}

                                →

                                {{ $ketThuc->format('H:i') }}

                            </div>

                        </div>


                        {{-- =====================================
                             TRẠNG THÁI
                        ====================================== --}}

                        <div class="schedule-status">


                            <span class="status-badge {{ $statusClass }}">

                                <span class="status-dot"></span>

                                {{ $statusText }}

                            </span>


                            {{-- =================================
                                 NÚT HỦY LỊCH
                            ================================== --}}

                            @if (
                                (
                                    str_contains($status, 'đặt') ||
                                    str_contains($status, 'dat')
                                )
                                &&
                                $batDau->isFuture()
                            )

                                <form
                                    action="{{ url('/user/pt-schedule/' . $lich->lich_pt_id . '/cancel') }}"
                                    method="POST"
                                    class="cancel-form"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch PT này không?');"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="cancel-btn"
                                    >
                                        HỦY LỊCH
                                    </button>

                                </form>

                            @endif

                        </div>


                    </div>


                @endforeach


            </div>


        @else


            {{-- =========================================
                 KHÔNG CÓ LỊCH
            ========================================= --}}

            <div class="schedule-empty">

                <div class="schedule-empty-icon">
                    📅
                </div>

                <h3>
                    Chưa có lịch PT
                </h3>

                <p>
                    Hiện tại bạn chưa có buổi tập PT nào được đặt lịch.
                </p>

                <a href="/pt-packages">
                    XEM GÓI PT
                </a>

            </div>

        @endif


    </div>

</div>


@endsection