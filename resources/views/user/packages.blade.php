@extends('layouts.user')

@section('title', 'Gói tập - GYM MANAGEMENT')

@section('content')

    <style>
        .packages-page {
            background: #0b0b0b;
            color: #fff;
            min-height: 100vh;
        }

        /* HERO */
        .packages-hero {
            padding: 90px 30px;
            text-align: center;

            background:
                linear-gradient(rgba(0, 0, 0, .72),
                    rgba(0, 0, 0, .85)),
                url("/img/banner4.avif");

            background-size: cover;
            background-position: center;
        }

        .packages-label {
            color: #e50914;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 3px;
        }

        .packages-hero h1 {
            font-size: 50px;
            font-weight: 900;
            margin: 15px 0;
        }

        .packages-hero h1 span {
            color: #e50914;
        }

        .packages-hero p {
            max-width: 700px;
            margin: auto;
            color: #bbb;
            line-height: 1.8;
            font-size: 16px;
        }

        /* CONTENT */
        .packages-section {
            max-width: 1400px;
            margin: auto;
            padding: 75px 60px;
        }

        .packages-heading {
            text-align: center;
            margin-bottom: 45px;
        }

        .packages-heading h2 {
            font-size: 36px;
            margin: 10px 0;
        }

        .packages-heading p {
            color: #888;
        }

        /* GRID */
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        /* CARD */
        .package-card {
            position: relative;
            background: #151515;
            border: 1px solid #292929;
            border-radius: 8px;
            overflow: hidden;
            transition: .3s;
        }

        .package-card:hover {
            transform: translateY(-7px);
            border-color: #e50914;
        }

        .package-card.featured {
            border-color: #e50914;
        }

        .package-badge {
            position: absolute;
            top: 18px;
            right: 18px;

            background: #e50914;
            color: #fff;

            padding: 6px 12px;
            border-radius: 20px;

            font-size: 11px;
            font-weight: bold;
        }

        .package-top {
            padding: 32px 28px 25px;
            border-bottom: 1px solid #292929;
        }

        .package-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .package-description {
            color: #888;
            font-size: 14px;
            line-height: 1.6;
            min-height: 45px;
        }

        .package-price {
            margin-top: 22px;
        }

        .package-price strong {
            font-size: 34px;
            color: #fff;
        }

        .package-price span {
            color: #888;
            font-size: 14px;
        }

        .package-body {
            padding: 28px;
        }

        .package-features {
            list-style: none;
            padding: 0;
            margin: 0 0 25px;
        }

        .package-features li {
            color: #ccc;
            margin-bottom: 13px;
            font-size: 14px;
        }

        .package-features li::before {
            content: "✓";
            color: #e50914;
            font-weight: bold;
            margin-right: 10px;
        }

        .package-btn {
            display: block;
            width: 100%;

            text-align: center;

            padding: 13px;

            border: 1px solid #555;
            border-radius: 5px;

            color: #ff1e2d;
            font-weight: bold;

            transition: .3s;
        }

        .package-btn:hover {
            background: #e50914;
            border-color: #e50914;
            color: #fff;
        }

        .package-card.featured .package-btn {
            background: #e50914;
            border-color: #e50914;
        }

        .package-card.featured .package-btn:hover {
            background: #ff2633;
        }

        /* CTA */
        .packages-cta {
            padding: 75px 30px;
            text-align: center;

            background: #111;
            border-top: 1px solid #222;
        }

        .packages-cta h2 {
            font-size: 36px;
            margin-bottom: 15px;
        }

        .packages-cta p {
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

        /* ================================
                    GYM TOAST NOTIFICATION
                    ================================ */

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

            animation: toastSlideIn 0.35s ease forwards;
        }

        .success-toast {
            border-left-color: #ff1010;
        }

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
            color: white;

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
            animation: toastSlideOut 0.3s ease forwards;
        }

        /* Mobile */
        @media (max-width: 600px) {
            .gym-toast {
                top: 80px;
                left: 15px;
                right: 15px;
                width: auto;
            }
        }

        /* =========================
                   THÔNG BÁO GÓI TẬP
                ========================= */

        .package-notice {
            width: 100%;
            margin: 0 0 30px;
            padding: 16px 20px;

            display: flex;
            align-items: center;
            gap: 14px;

            background: #151515;
            border: 1px solid #292929;
            border-left: 4px solid #e50914;
            border-radius: 8px;

            box-sizing: border-box;
        }

        .package-notice-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #e50914;
            color: #fff;

            border-radius: 50%;

            font-size: 17px;
            font-weight: 800;
        }

        .package-notice-content {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .package-notice-content strong {
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .package-notice-content span {
            color: #999;
            font-size: 13px;
            line-height: 1.5;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            .packages-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .packages-section {
                padding: 60px 30px;
            }
        }

        @media (max-width: 650px) {
            .packages-grid {
                grid-template-columns: 1fr;
            }

            .packages-hero h1 {
                font-size: 38px;
            }

            .packages-section {
                padding: 50px 20px;
            }

            .packages-heading h2,
            .packages-cta h2 {
                font-size: 30px;
            }
        }
    </style>


    <div class="packages-page">

        {{-- HERO --}}
        <section class="packages-hero">

            <span class="packages-label">
                GYMFIT MEMBERSHIP
            </span>

            <h1>
                CHỌN <span>GÓI TẬP</span>
                PHÙ HỢP
            </h1>

            <p>
                Lựa chọn gói tập phù hợp với thời gian,
                nhu cầu và mục tiêu luyện tập của bạn.
            </p>

        </section>


        {{-- DANH SÁCH GÓI --}}
        <section class="packages-section">

            <div class="packages-heading">

                <span class="packages-label">
                    GÓI TẬP
                </span>

                <h2>
                    CÁC GÓI TẬP TẠI GYMFIT
                </h2>

                <p>
                    Bắt đầu hành trình tập luyện của bạn ngay hôm nay.
                </p>

            </div>


            {{-- THÔNG BÁO --}}
            @if (session('success'))
                <div class="gym-toast success-toast" id="successToast">
                    <div class="toast-icon">✓</div>

                    <div class="toast-content">
                        <strong>Đăng ký thành công</strong>
                        <span>{{ session('success') }}</span>
                    </div>

                    <button type="button" class="toast-close" onclick="closeToast('successToast')">
                        ×
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="gym-toast error-toast" id="errorToast">
                    <div class="toast-icon">!</div>

                    <div class="toast-content">
                        <strong>Không thể đăng ký</strong>
                        <span>{{ session('error') }}</span>
                    </div>

                    <button type="button" class="toast-close" onclick="closeToast('errorToast')">
                        ×
                    </button>
                </div>
            @endif



            <div class="packages-grid">


                @foreach ($goiTap as $goi)
                    <div class="package-card">

                        <div class="package-top">

                            <div class="package-name">
                                {{ $goi->ten_goi }}
                            </div>

                            <div class="package-description">
                                Gói tập phù hợp với nhu cầu luyện tập của bạn.
                            </div>

                            <div class="package-price">
                                <strong>
                                    {{ number_format($goi->gia, 0, ',', '.') }}đ
                                </strong>

                                <span>
                                    / {{ $goi->thoi_han_ngay }} ngày
                                </span>
                            </div>

                        </div>


                        <div class="package-body">

                            <ul class="package-features">

                                <li>
                                    Số buổi tập: {{ $goi->so_buoi }}
                                </li>

                                <li>
                                    Sử dụng khu vực tập Gym
                                </li>

                                <li>
                                    Hỗ trợ tư vấn luyện tập
                                </li>

                                <li>
                                    Theo dõi lịch tập
                                </li>

                            </ul>


                            @if (session()->has('user') && session('role_id') == 3)
                                <form action="/packages/register/{{ $goi->goi_tap_id }}" method="POST">
                                    @csrf

                                    <button type="submit" class="package-btn">
                                        ĐĂNG KÝ NGAY
                                    </button>
                                </form>
                            @else
                                <a href="/login" class="package-btn">
                                    ĐĂNG KÝ NGAY
                                </a>
                            @endif

                        </div>

                    </div>
                @endforeach

            </div>

        </section>


        {{-- CTA --}}
        <section class="packages-cta">

            @if(session()->has('user'))

                <h2>
                    BẠN ĐÃ SẴN SÀNG ?
                </h2>

                <p>
                    Quản lý gói tập và thông tin thành viên của bạn tại GYMFIT.
                </p>

                <a
                    href="{{ session('dashboard_path') }}"
                    class="cta-btn"
                >
                    VỀ DASHBOARD
                </a>

            @else

                <h2>
                    BẠN ĐÃ SẴN SÀNG ?
                </h2>

                <p>
                    Tạo tài khoản và bắt đầu hành trình tập luyện cùng GYMFIT.
                </p>

                <a
                    href="/register"
                    class="cta-btn"
                >
                    ĐĂNG KÝ THÀNH VIÊN
                </a>

            @endif

</section>

    </div>
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

        document.addEventListener('DOMContentLoaded', function() {

            const successToast = document.getElementById('successToast');
            const errorToast = document.getElementById('errorToast');

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

        });
    </script>

@endsection
