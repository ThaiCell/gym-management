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
            linear-gradient(
                rgba(0,0,0,.72),
                rgba(0,0,0,.86)
            ),
            url("/img/photo-1571902943202-507ec2618e8f.avif");

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
       NOTE
    ========================= */

    .classes-note {
        margin-top: 45px;

        padding: 20px;

        background: #111;

        border: 1px solid #222;

        border-radius: 6px;

        text-align: center;

        color: #777;

        font-size: 13px;
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

    @media (max-width: 1000px) {

        .classes-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .classes-section {
            padding: 60px 30px;
        }

    }


    @media (max-width: 650px) {

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
             TOAST THÀNH CÔNG
        ========================= --}}

        @if(session('success'))

            <div class="gym-toast success-toast" id="successToast">

                <div class="toast-icon">
                    ✓
                </div>

                <div class="toast-content">

                    <strong>
                        Đăng ký thành công
                    </strong>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

                <button
                    type="button"
                    class="toast-close"
                    onclick="closeToast('successToast')">

                    ×

                </button>

            </div>

        @endif


        {{-- =========================
             TOAST LỖI
        ========================= --}}

        @if(session('error'))

            <div class="gym-toast error-toast" id="errorToast">

                <div class="toast-icon">
                    !
                </div>

                <div class="toast-content">

                    <strong>
                        Không thể đăng ký
                    </strong>

                    <span>
                        {{ session('error') }}
                    </span>

                </div>

                <button
                    type="button"
                    class="toast-close"
                    onclick="closeToast('errorToast')">

                    ×

                </button>

            </div>

        @endif


        {{-- =========================
             GRID LỚP TẬP
        ========================= --}}

        <div class="classes-grid">


            @foreach($lopTap as $lop)

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

                @endphp


                <div class="class-card">


                    {{-- ẢNH --}}

                    <div class="class-image">

                        <img
                            src="{{ asset($image) }}"
                            alt="{{ $lop->ten_lop }}"
                        >

                        <span class="class-status">

                            {{ $lop->trang_thai }}

                        </span>

                    </div>


                    {{-- BODY --}}

                    <div class="class-body">

                        <h3>

                            {{ $lop->ten_lop }}

                        </h3>


                        <p class="class-description">

                            {{ $lop->mo_ta ?: 'Tham gia lớp tập cùng huấn luyện viên và các hội viên tại GYMFIT.' }}

                        </p>


                        <div class="class-info">


                            <div class="class-info-row">

                                <span>
                                    Lịch tập
                                </span>

                                <span>
                                    {{ $lop->lich_tap ?: 'Đang cập nhật' }}
                                </span>

                            </div>


                            <div class="class-info-row">

                                <span>
                                    Sức chứa
                                </span>

                                <span>

                                    @if($lop->suc_chua)

                                        {{ $lop->suc_chua }} người

                                    @else

                                        Không giới hạn

                                    @endif

                                </span>

                            </div>


                            <div class="class-info-row">

                                <span>
                                    Trạng thái
                                </span>

                                <span>
                                    {{ $lop->trang_thai }}
                                </span>

                            </div>


                        </div>


                        {{-- =========================
                             NÚT ĐĂNG KÝ
                        ========================= --}}

                        @if(session()->has('user') && session('role_id') == 3)

                            <form
                                action="/classes/register/{{ $lop->lop_tap_id }}"
                                method="POST"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="class-btn"
                                >
                                    ĐĂNG KÝ NGAY
                                </button>

                            </form>

                        @else

                            <a
                                href="/login"
                                class="class-btn"
                            >
                                ĐĂNG KÝ NGAY
                            </a>

                        @endif


                    </div>

                </div>

            @endforeach


            {{-- Không có lớp --}}

            @if($lopTap->count() == 0)

                <div
                    style="
                        grid-column: 1 / -1;
                        text-align: center;
                        padding: 60px 20px;
                        color: #777;
                    "
                >

                    <h3 style="color:#fff;">
                        Hiện chưa có lớp tập
                    </h3>

                    <p>
                        Vui lòng quay lại sau.
                    </p>

                </div>

            @endif


        </div>


        <div class="classes-note">

            * Thông tin lớp tập được lấy trực tiếp
            từ hệ thống quản lý GYMFIT.

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

        <a
            href="/register"
            class="cta-btn"
        >
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
        function () {

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