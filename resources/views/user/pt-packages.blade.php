@extends('layouts.user')

@section('content')

    @php
        /*
    |--------------------------------------------------------------------------
    | ẢNH CHO TỪNG GÓI PT
    |--------------------------------------------------------------------------
    */
        $ptImages = [
            1 => 'pt1.jpg',
            2 => 'pt2.jpg',
            3 => 'pt3.jpg',
            4 => 'pt4.jpg',
        ];
    @endphp


    <style>
        .pt-page {
            background: #080808;
            color: #fff;
            min-height: 100vh;
            padding-bottom: 80px;
        }


        /* =========================================================
           HERO
        ========================================================= */

        .pt-hero {
            min-height: 330px;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            position: relative;

            background:
                linear-gradient(rgba(0, 0, 0, .72),
                    rgba(0, 0, 0, .82)),
                url('/img/banner1.avif') center/cover;
        }


        .pt-hero-content {
            max-width: 850px;
            padding: 40px 20px;
        }


        .pt-label {
            color: #e50914;

            font-size: 14px;
            font-weight: 800;

            letter-spacing: 4px;

            margin-bottom: 15px;
        }


        .pt-hero h1 {
            font-size: 48px;
            font-weight: 900;

            margin: 0 0 18px;

            text-transform: uppercase;
        }


        .pt-hero h1 span {
            color: #e50914;
        }


        .pt-hero p {
            color: #bbb;

            font-size: 16px;
            line-height: 1.7;

            margin: 0;
        }



        /* =========================================================
           CONTENT
        ========================================================= */

        .pt-container {
            width: min(1100px, 92%);

            margin: 0 auto;

            padding-top: 65px;
        }


        .section-title {
            text-align: center;

            margin-bottom: 45px;
        }


        .section-title .small-title {
            color: #e50914;

            font-size: 13px;
            font-weight: 800;

            letter-spacing: 3px;

            margin-bottom: 10px;
        }


        .section-title h2 {
            font-size: 34px;
            font-weight: 900;

            margin: 0 0 10px;
        }


        .section-title p {
            color: #888;

            margin: 0;
        }



        /* =========================================================
           CARDS
        ========================================================= */

        .pt-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 24px;
        }


        .pt-card {
            background: #151515;

            border: 1px solid #292929;

            border-radius: 12px;

            overflow: hidden;

            transition: .3s;

            position: relative;
        }


        .pt-card:hover {
            transform: translateY(-8px);

            border-color: #e50914;

            box-shadow:
                0 15px 40px rgba(229, 9, 20, .15);
        }


        .pt-card.featured {
            border-color: #e50914;
        }



        /* =========================================================
           BADGE
        ========================================================= */

        .pt-badge {
            position: absolute;

            top: 15px;
            right: 15px;

            background: #e50914;

            color: #fff;

            padding: 7px 13px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 800;

            text-transform: uppercase;

            z-index: 2;
        }



        /* =========================================================
           IMAGE
        ========================================================= */

        .pt-image {
            height: 190px;

            overflow: hidden;

            background: #111;
        }


        .pt-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: .4s;

            display: block;
        }


        .pt-card:hover .pt-image img {
            transform: scale(1.06);
        }



        /* =========================================================
           BODY
        ========================================================= */

        .pt-body {
            padding: 25px;
        }


        .pt-body h3 {
            font-size: 21px;

            margin: 0 0 18px;

            font-weight: 800;
        }



        /* =========================================================
           PRICE
        ========================================================= */

        .pt-price {
            color: #e50914;

            font-size: 28px;
            font-weight: 900;

            margin-bottom: 5px;
        }


        .pt-price small {
            color: #888;

            font-size: 13px;
            font-weight: 400;
        }



        /* =========================================================
           INFO
        ========================================================= */

        .pt-info {
            margin: 20px 0;

            border-top: 1px solid #292929;
            border-bottom: 1px solid #292929;

            padding: 15px 0;
        }


        .pt-info-row {
            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding: 7px 0;

            font-size: 14px;
        }


        .pt-info-row span:first-child {
            color: #888;
        }


        .pt-info-row span:last-child {
            color: #eee;

            font-weight: 600;

            text-align: right;
        }


        .pt-status {
            color: #e50914 !important;
        }



        /* =========================================================
           BUTTON
        ========================================================= */

        .pt-btn {
            display: block;

            width: 100%;

            padding: 13px;

            border: 1px solid #e50914;

            border-radius: 6px;

            background: #e50914;

            color: #fff;

            text-align: center;

            text-decoration: none;

            font-size: 13px;
            font-weight: 800;

            text-transform: uppercase;

            transition: .25s;

            cursor: pointer;
        }


        .pt-btn:hover {
            background: #b80710;

            border-color: #b80710;

            color: #fff;
        }


        .pt-btn-form {
            width: 100%;

            padding: 0;

            margin: 0;

            border: 0;

            background: transparent;
        }



        /* =========================================================
           EMPTY
        ========================================================= */

        .pt-empty {
            text-align: center;

            padding: 60px 20px;

            color: #777;
        }


        .pt-empty-icon {
            font-size: 50px;

            margin-bottom: 15px;
        }



        /* =========================================================
           TOAST
        ========================================================= */

        .gym-toast {
            position: fixed;

            top: 95px;
            right: 30px;

            z-index: 99999;

            width: 380px;
            min-height: 80px;

            display: flex;

            align-items: center;

            gap: 15px;

            padding: 16px 18px;

            background: #151515;

            border: 1px solid #333;

            border-left: 4px solid #e50914;

            border-radius: 8px;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.45);

            animation:
                toastSlideIn .35s ease forwards;
        }


        .toast-icon {
            width: 38px;
            height: 38px;

            min-width: 38px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e50914;

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

                transform:
                    translateX(40px);
            }

            to {
                opacity: 1;

                transform:
                    translateX(0);
            }

        }


        @keyframes toastSlideOut {

            from {
                opacity: 1;

                transform:
                    translateX(0);
            }

            to {
                opacity: 0;

                transform:
                    translateX(40px);
            }

        }


        .gym-toast.hide {
            animation:
                toastSlideOut .3s ease forwards;
        }



        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 900px) {

            .pt-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }


            .pt-hero h1 {
                font-size: 38px;
            }

        }


        @media (max-width: 600px) {

            .pt-grid {
                grid-template-columns: 1fr;
            }


            .pt-hero {
                min-height: 280px;
            }


            .pt-hero h1 {
                font-size: 30px;
            }


            .section-title h2 {
                font-size: 27px;
            }


            .gym-toast {
                top: 80px;

                left: 15px;
                right: 15px;

                width: auto;
            }

        }
    </style>



    <div class="pt-page">


        {{-- =====================================================
         TOAST THÔNG BÁO
    ====================================================== --}}

        @if (session('success'))
            <div class="gym-toast" id="gymToast">

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


                <button type="button" class="toast-close" onclick="closeGymToast()">
                    ×
                </button>

            </div>
        @endif



        @if (session('error'))
            <div class="gym-toast" id="gymToast">

                <div class="toast-icon">
                    !
                </div>


                <div class="toast-content">

                    <strong>
                        Thông báo
                    </strong>

                    <span>
                        {{ session('error') }}
                    </span>

                </div>


                <button type="button" class="toast-close" onclick="closeGymToast()">
                    ×
                </button>

            </div>
        @endif



        <!-- =====================================================
             HERO
        ====================================================== -->

        <section class="pt-hero">

            <div class="pt-hero-content">

                <div class="pt-label">
                    GYMFIT PERSONAL TRAINER
                </div>


                <h1>
                    GÓI
                    <span>
                        HUẤN LUYỆN VIÊN
                    </span>
                </h1>


                <p>
                    Lựa chọn gói PT phù hợp với mục tiêu tập luyện
                    và nhận được sự hướng dẫn chuyên nghiệp từ
                    huấn luyện viên của GymFit.
                </p>

            </div>

        </section>



        <!-- =====================================================
             DANH SÁCH GÓI
        ====================================================== -->

        <div class="pt-container">


            <div class="section-title">

                <div class="small-title">
                    PERSONAL TRAINER
                </div>


                <h2>
                    CÁC GÓI PT TẠI GYMFIT
                </h2>


                <p>
                    Tập luyện hiệu quả hơn với huấn luyện viên cá nhân
                </p>

            </div>



            @if ($goiPt->count() > 0)
                <div class="pt-grid">


                    @foreach ($goiPt as $index => $pt)
                        <div class="pt-card
                        {{ $index == 1 ? 'featured' : '' }}">


                            {{-- BADGE PHỔ BIẾN --}}

                            @if ($index == 1)
                                <div class="pt-badge">
                                    Phổ biến
                                </div>
                            @endif



                            {{-- ẢNH --}}

                            <div class="pt-image">

                                @php
                                    $image = $ptImages[$pt->goi_pt_id] ?? 'pt1.jpg';
                                @endphp


                                <img src="{{ asset('img/' . $image) }}" alt="{{ $pt->ten_goi_pt }}">

                            </div>



                            {{-- BODY --}}

                            <div class="pt-body">


                                <h3>
                                    {{ $pt->ten_goi_pt }}
                                </h3>



                                {{-- GIÁ --}}

                                <div class="pt-price">

                                    {{ number_format($pt->gia, 0, ',', '.') }}
                                    ₫

                                    <small>
                                        / gói
                                    </small>

                                </div>



                                {{-- THÔNG TIN --}}

                                <div class="pt-info">


                                    <div class="pt-info-row">

                                        <span>
                                            Số buổi
                                        </span>

                                        <span>
                                            {{ $pt->so_buoi }}
                                            buổi
                                        </span>

                                    </div>



                                    <div class="pt-info-row">

                                        <span>
                                            Thời hạn
                                        </span>

                                        <span>
                                            {{ $pt->thoi_han_ngay }}
                                            ngày
                                        </span>

                                    </div>



                                    <div class="pt-info-row">

                                        <span>
                                            Trạng thái
                                        </span>

                                        <span class="pt-status">

                                            {{ $pt->trang_thai }}

                                        </span>

                                    </div>


                                </div>



                                {{-- =================================================
                                 NÚT ĐĂNG KÝ
                            ================================================== --}}

                                @if (session('user'))
                                    <form action="{{ url('/pt-packages/' . $pt->goi_pt_id . '/register') }}" method="POST"
                                        class="pt-btn-form">

                                        @csrf


                                        <button type="submit" class="pt-btn">

                                            ĐĂNG KÝ NGAY

                                        </button>

                                    </form>
                                @else
                                    <a href="/login" class="pt-btn">

                                        ĐĂNG NHẬP ĐỂ ĐĂNG KÝ

                                    </a>
                                @endif


                            </div>

                        </div>
                    @endforeach


                </div>
            @else
                <!-- KHÔNG CÓ GÓI -->

                <div class="pt-empty">

                    <div class="pt-empty-icon">
                        💪
                    </div>


                    <h3>
                        Chưa có gói PT
                    </h3>


                    <p>
                        Hiện tại phòng gym chưa có gói
                        huấn luyện viên.
                    </p>

                </div>
            @endif


        </div>

    </div>



    <script>
        /*
        |--------------------------------------------------------------------------
        | ĐÓNG TOAST
        |--------------------------------------------------------------------------
        */

        function closeGymToast() {

            const toast =
                document.getElementById('gymToast');


            if (!toast) {
                return;
            }


            toast.classList.add('hide');


            setTimeout(function() {

                toast.remove();

            }, 300);

        }



        /*
        |--------------------------------------------------------------------------
        | TỰ ĐỘNG ẨN TOAST SAU 4 GIÂY
        |--------------------------------------------------------------------------
        */

        setTimeout(function() {

            closeGymToast();

        }, 4000);
    </script>


@endsection
