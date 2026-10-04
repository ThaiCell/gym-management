@extends('layouts.user')

@section('title', 'Lớp tập - GYM MANAGEMENT')

@section('content')

    <style>
        .classes-page {
            background: #0b0b0b;
            color: #fff;
            min-height: 100vh;
        }

        /* =========================
                               HERO
                            ========================= */

        .classes-hero {
            padding: 90px 30px;
            text-align: center;

            background:
                linear-gradient(rgba(0, 0, 0, .72),
                    rgba(0, 0, 0, .86)),
                url("/img/banner3.avif");

            background-size: cover;
            background-position: center;
        }

        .classes-label {
            color: #e50914;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 3px;
        }

        .classes-hero h1 {
            font-size: 50px;
            font-weight: 900;
            margin: 15px 0;
        }

        .classes-hero h1 span {
            color: #e50914;
        }

        .classes-hero p {
            max-width: 700px;
            margin: auto;
            color: #bbb;
            line-height: 1.8;
            font-size: 16px;
        }


        /* =========================
                               CONTENT
                            ========================= */

        .classes-section {
            max-width: 1400px;
            margin: auto;
            padding: 75px 60px;
        }

        .classes-heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .classes-heading h2 {
            font-size: 36px;
            margin: 10px 0;
        }

        .classes-heading p {
            color: #888;
        }


        /* =========================
                               GRID
                            ========================= */

        .classes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }


        /* =========================
                               CARD
                            ========================= */

        .class-card {
            position: relative;

            background: #151515;
            border: 1px solid #292929;

            border-radius: 8px;

            overflow: hidden;

            transition: .3s;
        }

        .class-card:hover {
            transform: translateY(-7px);
            border-color: #e50914;
        }


        /* =========================
                               IMAGE
                            ========================= */

        .class-image {
            height: 210px;

            position: relative;
            overflow: hidden;
        }

        .class-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: .4s;
        }

        .class-card:hover .class-image img {
            transform: scale(1.05);
        }


        /* =========================
                               STATUS
                            ========================= */

        .class-status {
            position: absolute;

            top: 15px;
            right: 15px;

            background: #e50914;
            color: #fff;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: bold;
        }


        /* =========================
                               BODY
                            ========================= */

        .class-body {
            padding: 28px;
        }

        .class-body h3 {
            font-size: 24px;
            font-weight: 700;

            margin-bottom: 12px;
        }

        .class-description {
            color: #888;

            font-size: 14px;
            line-height: 1.6;

            min-height: 48px;

            margin-bottom: 20px;
        }


        /* =========================
                               INFO
                            ========================= */

        .class-info {
            border-top: 1px solid #292929;

            padding-top: 20px;
            margin-top: 15px;
        }

        .class-info-row {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 13px;

            font-size: 13px;
        }

        .class-info-row span:first-child {
            color: #777;
        }

        .class-info-row span:last-child {
            color: #ddd;

            text-align: right;
        }


        /* =========================
                               BUTTON
                            ========================= */

        .class-btn {
            display: block;

            width: 100%;

            text-align: center;

            margin-top: 24px;

            padding: 13px;

            border: 1px solid #555;

            border-radius: 5px;

            background: transparent;

            color: #ff1e2d;

            font-size: 14px;

            font-weight: bold;

            transition: .3s;

            cursor: pointer;
        }

        .class-btn:hover {
            background: #e50914;

            border-color: #e50914;

            color: #fff;
        }


        /* =========================
                               CTA
                            ========================= */

        .classes-cta {
            padding: 75px 30px;

            text-align: center;

            background: #111;

            border-top: 1px solid #222;
        }

        .classes-cta h2 {
            font-size: 36px;

            margin-bottom: 15px;
        }

        .classes-cta p {
            color: #999;

            margin-bottom: 25px;
        }

        .cta-btn {
            display: inline-block;

            background: #e50914;

            color: #fff;

            padding: 14px 28px;

            border-radius: 5px;

            font-weight: bold;

            transition: .3s;
        }

        .cta-btn:hover {
            background: #ff2633;

            color: #fff;

            transform: translateY(-2px);
        }


        /* =========================
                               TOAST
                            ========================= */

        .gym-toast {
            position: fixed;

            top: 95px;
            right: 30px;

            z-index: 9999;

            width: 380px;
            min-height: 80px;

            display: flex;

            align-items: center;

            gap: 15px;

            padding: 16px 18px;

            background: #151515;

            border: 1px solid #333;

            border-left: 4px solid #ff1010;

            border-radius: 8px;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.45);

            animation: toastSlideIn .35s ease forwards;
        }

        .success-toast,
        .error-toast {
            border-left-color: #ff1010;
        }

        .toast-icon {
            width: 38px;
            height: 38px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #ed1111;

            color: #fff;

            font-size: 20px;

            font-weight: 700;
        }

        .toast-content {
            flex: 1;

            display: flex;

            flex-direction: column;

            gap: 4px;
        }

        .toast-content strong {
            color: #fff;

            font-size: 15px;

            font-weight: 700;
        }

        .toast-content span {
            color: #aaa;

            font-size: 13px;

            line-height: 1.4;
        }

        .toast-close {
            background: transparent;

            border: none;

            color: #777;

            font-size: 24px;

            line-height: 1;

            cursor: pointer;

            padding: 2px 5px;
        }

        .toast-close:hover {
            color: #fff;
        }


        @keyframes toastSlideIn {

            from {
                opacity: 0;

                transform: translateX(40px);
            }

            to {
                opacity: 1;

                transform: translateX(0);
            }

        }


        @keyframes toastSlideOut {

            from {
                opacity: 1;

                transform: translateX(0);
            }

            to {
                opacity: 0;

                transform: translateX(40px);
            }

        }


        .gym-toast.hide {
            animation: toastSlideOut .3s ease forwards;
        }


        /* =========================
                               RESPONSIVE
                            ========================= */

        /* =========================
                               MY CLASSES
                            ========================= */

        .my-classes-header,
        .all-classes-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 25px;
            margin-bottom: 18px;
        }

        .my-classes-header {
            margin-top: 5px;
        }

        .my-classes-label {
            display: block;
            color: #e50914;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2.5px;
            margin-bottom: 7px;
        }

        .my-classes-header h3,
        .all-classes-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
        }

        .my-classes-count {
            color: #777;
            font-size: 12px;
            font-weight: 700;
            padding-bottom: 3px;
        }

        .my-classes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 55px;
        }

        .my-class-card {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 15px;
            background: #121212;
            border: 1px solid #292929;
            border-radius: 7px;
            transition: .25s;
        }

        .my-class-card:hover {
            border-color: #444;
            transform: translateY(-2px);
        }

        .my-class-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e50914;
            color: #fff;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .my-class-info {
            min-width: 0;
            flex: 1;
        }

        .my-class-info h4 {
            margin: 0 0 5px;
            color: #fff;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .my-class-info p {
            margin: 0;
            color: #777;
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .my-class-status {
            color: #4cd97b;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            margin-right: 4px;
            border-radius: 50%;
            background: #4cd97b;
            vertical-align: middle;
        }

        .my-classes-empty {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 55px;
            padding: 20px;
            background: #121212;
            border: 1px dashed #333;
            border-radius: 7px;
            color: #777;
        }

        .empty-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #444;
            border-radius: 50%;
            color: #e50914;
            font-size: 22px;
        }

        .my-classes-empty strong,
        .my-classes-empty span {
            display: block;
        }

        .my-classes-empty strong {
            margin-bottom: 4px;
            color: #ddd;
            font-size: 13px;
        }

        .my-classes-empty span {
            font-size: 11px;
        }

        .all-classes-header {
            align-items: center;
            margin-bottom: 20px;
        }

        .all-classes-line {
            height: 1px;
            flex: 1;
            background: #292929;
        }

        .class-card {
            min-width: 0;
        }

        .class-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .class-number {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .class-info-row strong {
            color: #ddd;
            font-weight: 600;
        }

        .class-actions {
            display: grid;
            grid-template-columns: 1fr 72px;
            gap: 8px;
            margin-top: 24px;
        }

        .registered-btn,
        .cancel-btn {
            min-height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 800;
        }

        .registered-btn {
            border: 1px solid rgba(76, 217, 123, .35);
            background: rgba(76, 217, 123, .06);
            color: #4cd97b;
        }

        .cancel-btn {
            width: 100%;
            border: 1px solid #5a2225;
            background: transparent;
            color: #ff4b55;
            cursor: pointer;
            transition: .25s;
        }

        .cancel-btn:hover {
            background: #e50914;
            border-color: #e50914;
            color: #fff;
        }

        .classes-empty {
            grid-column: 1 / -1;
            padding: 70px 20px;
            text-align: center;
            background: #111;
            border: 1px dashed #333;
            border-radius: 8px;
        }

        .classes-empty h3 {
            margin: 0 0 8px;
            color: #fff;
        }

        .classes-empty p {
            margin: 0;
            color: #777;
        }


        @media (max-width: 1000px) {

            .classes-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .classes-section {
                padding: 60px 30px;
            }

        }


        @media (max-width: 650px) {
            .my-classes-grid {
                grid-template-columns: 1fr;
            }

            .my-class-card {
                padding: 13px;
            }

            .my-class-status {
                display: none;
            }

            .class-actions {
                grid-template-columns: 1fr;
            }



            .classes-grid {
                grid-template-columns: 1fr;
            }

            .classes-hero h1 {
                font-size: 38px;
            }

            .classes-section {
                padding: 50px 20px;
            }

            .classes-heading h2,
            .classes-cta h2 {
                font-size: 30px;
            }

            .gym-toast {
                top: 80px;

                left: 15px;
                right: 15px;

                width: auto;
            }

        }
    </style>


    <div class="classes-page">


        {{-- =========================
         HERO
    ========================= --}}

        <section class="classes-hero">

            <span class="classes-label">
                GYMFIT CLASSES
            </span>

            <h1>
                KHÁM PHÁ <span>LỚP TẬP</span>
            </h1>

            <p>
                Tham gia các lớp tập đa dạng cùng huấn luyện viên
                và những hội viên có cùng mục tiêu luyện tập.
            </p>

        </section>


        {{-- =========================
         DANH SÁCH LỚP
    ========================= --}}

        <section class="classes-section">

            <div class="classes-heading">

                <span class="classes-label">
                    LỚP TẬP
                </span>

                <h2>
                    CÁC LỚP TẬP TẠI GYMFIT
                </h2>

                <p>
                    Lựa chọn lớp tập phù hợp với mục tiêu của bạn.
                </p>

            </div>


            {{-- =========================
             THÔNG BÁO
        ========================= --}}

            @if (session('success'))
                <div class="gym-toast success-toast" id="successToast">

                    <div class="toast-icon">
                        ✓
                    </div>

                    <div class="toast-content">

                        <strong>
                            Thành công
                        </strong>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                    <button type="button" class="toast-close" onclick="closeToast('successToast')">
                        ×
                    </button>

                </div>
            @endif


            @if (session('error'))
                <div class="gym-toast error-toast" id="errorToast">

                    <div class="toast-icon">
                        !
                    </div>

                    <div class="toast-content">

                        <strong>
                            Không thể thực hiện
                        </strong>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>

                    <button type="button" class="toast-close" onclick="closeToast('errorToast')">
                        ×
                    </button>

                </div>
            @endif


            {{-- =========================
             LỚP TẬP CỦA TÔI
        ========================= --}}

            @if (session()->has('user') && session('role_id') == 3)

                <div class="my-classes-header">

                    <div>
                        <span class="my-classes-label">
                            MY CLASSES
                        </span>

                        <h3>
                            Lớp tập của tôi
                        </h3>
                    </div>

                    <span class="my-classes-count">
                        {{ isset($dangKyLop) ? $dangKyLop->count() : 0 }} lớp
                    </span>

                </div>


                @if (isset($dangKyLop) && $dangKyLop->count() > 0)

                    <div class="my-classes-grid">

                        @foreach ($dangKyLop as $dangKy)
                            <div class="my-class-card">

                                <div class="my-class-icon">
                                    <span>GYM</span>
                                </div>

                                <div class="my-class-info">

                                    <h4>
                                        {{ $dangKy->ten_lop }}
                                    </h4>

                                    <p>
                                        {{ $dangKy->lich_tap ?: 'Lịch tập đang cập nhật' }}
                                    </p>

                                </div>

                                <div class="my-class-status">
                                    <span class="status-dot"></span>
                                    ĐANG THAM GIA
                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="my-classes-empty">

                        <div class="empty-icon">
                            +
                        </div>

                        <div>
                            <strong>
                                Bạn chưa đăng ký lớp nào
                            </strong>

                            <span>
                                Chọn một lớp bên dưới để bắt đầu luyện tập.
                            </span>
                        </div>

                    </div>

                @endif

            @endif


            {{-- =========================
             TẤT CẢ LỚP TẬP
        ========================= --}}

            <div class="all-classes-header">

                <div>
                    <span class="my-classes-label">
                        EXPLORE
                    </span>

                    <h3>
                        Tất cả lớp tập
                    </h3>
                </div>

                <span class="all-classes-line"></span>

            </div>


            <div class="classes-grid">

                @forelse($lopTap as $lop)

                    @php

                        $tenLop = strtolower($lop->ten_lop);

                        if (str_contains($tenLop, 'yoga')) {
                            $image = 'img/yoga.jpg';
                        } elseif (str_contains($tenLop, 'gym')) {
                            $image = 'img/photo-1581009146145-b5ef050c2e1e.avif';
                        } elseif (str_contains($tenLop, 'cardio')) {
                            $image = 'img/photo-1534438327276-14e5300c3a48.avif';
                        } elseif (str_contains($tenLop, 'fitness')) {
                            $image = 'img/photo-1571902943202-507ec2618e8f.avif';
                        } elseif (str_contains($tenLop, 'strength')) {
                            $image = 'img/strength.jpg';
                        } elseif (str_contains($tenLop, 'boxing')) {
                            $image = 'img/boxing.jpg';
                        } else {
                            $image = 'img/photo-1571902943202-507ec2618e8f.avif';
                        }

                        $daDangKy = isset($lopDaDangKy) && in_array($lop->lop_tap_id, $lopDaDangKy);

                        $dangKyCuaLop = null;

                        if ($daDangKy && isset($dangKyLop)) {
                            $dangKyCuaLop = $dangKyLop->firstWhere('lop_tap_id', $lop->lop_tap_id);
                        }

                    @endphp


                    <article class="class-card">

                        <div class="class-image">

                            <img src="{{ asset($image) }}" alt="{{ $lop->ten_lop }}">

                            <span class="class-status">
                                {{ $lop->trang_thai }}
                            </span>

                        </div>


                        <div class="class-body">

                            <div class="class-card-top">

                                <div>

                                    <span class="class-number">
                                        CLASS {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    <h3>
                                        {{ $lop->ten_lop }}
                                    </h3>

                                </div>

                            </div>


                            <p class="class-description">

                                {{ $lop->mo_ta ?: 'Tham gia lớp tập cùng huấn luyện viên và các hội viên tại GYMFIT.' }}

                            </p>


                            <div class="class-info">

                                <div class="class-info-row">

                                    <span>
                                        LỊCH TẬP
                                    </span>

                                    <strong>
                                        {{ $lop->lich_tap ?: 'Đang cập nhật' }}
                                    </strong>

                                </div>


                                <div class="class-info-row">

                                    <span>
                                        SỨC CHỨA
                                    </span>

                                    <strong>

                                        @if ($lop->suc_chua)
                                            {{ $lop->suc_chua }} người
                                        @else
                                            Không giới hạn
                                        @endif

                                    </strong>

                                </div>


                                <div class="class-info-row">

                                    <span>
                                        TRẠNG THÁI
                                    </span>

                                    <strong>
                                        {{ $lop->trang_thai }}
                                    </strong>

                                </div>

                            </div>


                            {{-- =========================
                             NÚT ĐĂNG KÝ
                        ========================= --}}

                            @if (session()->has('user') && session('role_id') == 3)
                                @if ($daDangKy && $dangKyCuaLop)
                                    <div class="class-actions">

                                        <div class="registered-btn">
                                            ✓ ĐÃ ĐĂNG KÝ
                                        </div>

                                        <form action="/classes/cancel/{{ $dangKyCuaLop->dang_ky_lop_id }}" method="POST">

                                            @csrf

                                            <button type="submit" class="cancel-btn"
                                                onclick="return confirm('Bạn có chắc muốn hủy đăng ký lớp này?')">
                                                HỦY
                                            </button>

                                        </form>

                                    </div>
                                @else
                                    <form action="/classes/register/{{ $lop->lop_tap_id }}" method="POST">

                                        @csrf

                                        <button type="submit" class="class-btn">
                                            ĐĂNG KÝ NGAY
                                        </button>

                                    </form>
                                @endif
                            @else
                                <a href="/login" class="class-btn">
                                    ĐĂNG KÝ NGAY
                                </a>
                            @endif

                        </div>

                    </article>

                @empty

                    <div class="classes-empty">

                        <h3>
                            Hiện chưa có lớp tập
                        </h3>

                        <p>
                            Vui lòng quay lại sau.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>


        {{-- =========================
         CTA
    ========================= --}}

        <section class="classes-cta">

            <h2>
                TÌM ĐƯỢC LỚP TẬP PHÙ HỢP?
            </h2>

            <p>
                Đăng ký tài khoản để tham gia các lớp tập tại GYMFIT.
            </p>

            <a href="/register" class="cta-btn">
                ĐĂNG KÝ THÀNH VIÊN
            </a>

        </section>


    </div>


    {{-- =========================
     JAVASCRIPT TOAST
========================= --}}

    <script>
        function closeToast(id) {

            const toast = document.getElementById(id);

            if (toast) {

                toast.classList.add('hide');

                setTimeout(() => {

                    toast.remove();

                }, 300);

            }

        }


        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const successToast =
                    document.getElementById('successToast');

                const errorToast =
                    document.getElementById('errorToast');


                if (successToast) {

                    setTimeout(() => {

                        closeToast('successToast');

                    }, 3000);

                }


                if (errorToast) {

                    setTimeout(() => {

                        closeToast('errorToast');

                    }, 4000);

                }

            }
        );
    </script>


@endsection
