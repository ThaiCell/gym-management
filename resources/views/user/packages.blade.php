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
            linear-gradient(
                rgba(0,0,0,.72),
                rgba(0,0,0,.85)
            ),
            url("/img/photo-1534438327276-14e5300c3a48.avif");

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

        color: #fff;
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

    /* NOTE */
    .packages-note {
        margin-top: 45px;
        padding: 20px;

        background: #111;
        border: 1px solid #222;
        border-radius: 6px;

        text-align: center;
        color: #777;
        font-size: 13px;
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


        <div class="packages-grid">


            {{-- GÓI 1 --}}
            <div class="package-card">

                <div class="package-top">

                    <div class="package-name">
                        GÓI CƠ BẢN
                    </div>

                    <div class="package-description">
                        Phù hợp cho người mới bắt đầu tập luyện.
                    </div>

                    <div class="package-price">
                        <strong>500.000đ</strong>
                        <span>/ tháng</span>
                    </div>

                </div>


                <div class="package-body">

                    <ul class="package-features">
                        <li>Sử dụng khu vực tập Gym</li>
                        <li>Hỗ trợ thiết bị tập luyện</li>
                        <li>Được sử dụng trong giờ hoạt động</li>
                        <li>Hỗ trợ tư vấn cơ bản</li>
                    </ul>

                    <a href="/register" class="package-btn">
                        ĐĂNG KÝ NGAY
                    </a>

                </div>

            </div>


            {{-- GÓI 2 --}}
            <div class="package-card featured">

                <div class="package-badge">
                    PHỔ BIẾN
                </div>

                <div class="package-top">

                    <div class="package-name">
                        GÓI TIÊU CHUẨN
                    </div>

                    <div class="package-description">
                        Lựa chọn cân bằng giữa chi phí và quyền lợi.
                    </div>

                    <div class="package-price">
                        <strong>800.000đ</strong>
                        <span>/ tháng</span>
                    </div>

                </div>


                <div class="package-body">

                    <ul class="package-features">
                        <li>Sử dụng khu vực tập Gym</li>
                        <li>Tham gia các lớp tập</li>
                        <li>Hỗ trợ tư vấn luyện tập</li>
                        <li>Theo dõi lịch tập</li>
                    </ul>

                    <a href="/register" class="package-btn">
                        ĐĂNG KÝ NGAY
                    </a>

                </div>

            </div>


            {{-- GÓI 3 --}}
            <div class="package-card">

                <div class="package-top">

                    <div class="package-name">
                        GÓI CAO CẤP
                    </div>

                    <div class="package-description">
                        Dành cho hội viên muốn có trải nghiệm đầy đủ hơn.
                    </div>

                    <div class="package-price">
                        <strong>1.200.000đ</strong>
                        <span>/ tháng</span>
                    </div>

                </div>


                <div class="package-body">

                    <ul class="package-features">
                        <li>Sử dụng toàn bộ khu vực Gym</li>
                        <li>Tham gia lớp tập</li>
                        <li>Ưu tiên hỗ trợ</li>
                        <li>Tư vấn kế hoạch luyện tập</li>
                    </ul>

                    <a href="/register" class="package-btn">
                        ĐĂNG KÝ NGAY
                    </a>

                </div>

            </div>


            {{-- GÓI 4 --}}
            <div class="package-card">

                <div class="package-top">

                    <div class="package-name">
                        GÓI 3 THÁNG
                    </div>

                    <div class="package-description">
                        Phù hợp với người muốn duy trì lịch tập lâu dài.
                    </div>

                    <div class="package-price">
                        <strong>2.100.000đ</strong>
                        <span>/ 3 tháng</span>
                    </div>

                </div>


                <div class="package-body">

                    <ul class="package-features">
                        <li>Sử dụng khu vực tập Gym</li>
                        <li>Tham gia lớp tập</li>
                        <li>Hỗ trợ tư vấn</li>
                        <li>Tiết kiệm hơn so với từng tháng</li>
                    </ul>

                    <a href="/register" class="package-btn">
                        ĐĂNG KÝ NGAY
                    </a>

                </div>

            </div>


            {{-- GÓI 5 --}}
            <div class="package-card">

                <div class="package-top">

                    <div class="package-name">
                        GÓI 6 THÁNG
                    </div>

                    <div class="package-description">
                        Dành cho người đã có thói quen tập luyện ổn định.
                    </div>

                    <div class="package-price">
                        <strong>3.600.000đ</strong>
                        <span>/ 6 tháng</span>
                    </div>

                </div>


                <div class="package-body">

                    <ul class="package-features">
                        <li>Sử dụng khu vực tập Gym</li>
                        <li>Tham gia các lớp tập</li>
                        <li>Hỗ trợ tư vấn luyện tập</li>
                        <li>Ưu đãi thời hạn dài</li>
                    </ul>

                    <a href="/register" class="package-btn">
                        ĐĂNG KÝ NGAY
                    </a>

                </div>

            </div>


            {{-- GÓI 6 --}}
            <div class="package-card">

                <div class="package-top">

                    <div class="package-name">
                        GÓI 12 THÁNG
                    </div>

                    <div class="package-description">
                        Giải pháp dành cho hội viên muốn tập luyện lâu dài.
                    </div>

                    <div class="package-price">
                        <strong>6.000.000đ</strong>
                        <span>/ năm</span>
                    </div>

                </div>


                <div class="package-body">

                    <ul class="package-features">
                        <li>Sử dụng khu vực tập Gym</li>
                        <li>Tham gia các lớp tập</li>
                        <li>Hỗ trợ tư vấn</li>
                        <li>Quyền lợi dài hạn</li>
                    </ul>

                    <a href="/register" class="package-btn">
                        ĐĂNG KÝ NGAY
                    </a>

                </div>

            </div>

        </div>


        <div class="packages-note">
            * Thông tin giá và quyền lợi hiện đang là giao diện mẫu.
            Sau khi hoàn thiện chức năng, dữ liệu sẽ được lấy từ hệ thống quản lý gói tập.
        </div>

    </section>


    {{-- CTA --}}
    <section class="packages-cta">

        <h2>
            BẠN ĐÃ SẴN SÀNG?
        </h2>

        <p>
            Tạo tài khoản và bắt đầu hành trình tập luyện cùng GYMFIT.
        </p>

        <a href="/register" class="cta-btn">
            ĐĂNG KÝ THÀNH VIÊN
        </a>

    </section>

</div>

@endsection