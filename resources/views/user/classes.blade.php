@extends('layouts.user')

@section('title', 'Lớp tập - GYM MANAGEMENT')

@section('content')

<style>
    .classes-page {
        background: #0b0b0b;
        color: #fff;
        min-height: 100vh;
    }

    /* HERO */
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
    }

    /* CONTENT */
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

    /* GRID */
    .classes-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    /* CARD */
    .class-card {
        background: #151515;
        border: 1px solid #292929;
        border-radius: 8px;
        overflow: hidden;
        transition: .3s;
    }

    .class-card:hover {
        transform: translateY(-6px);
        border-color: #e50914;
    }

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

    .class-body {
        padding: 25px;
    }

    .class-body h3 {
        font-size: 22px;
        margin-bottom: 10px;
    }

    .class-description {
        color: #999;
        font-size: 14px;
        line-height: 1.7;
        min-height: 48px;
        margin-bottom: 20px;
    }

    .class-info {
        border-top: 1px solid #292929;
        padding-top: 18px;
        margin-top: 15px;
    }

    .class-info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 13px;
    }

    .class-info-row span:first-child {
        color: #777;
    }

    .class-info-row span:last-child {
        color: #ddd;
        text-align: right;
    }

    .class-btn {
        display: block;
        width: 100%;
        text-align: center;

        margin-top: 22px;
        padding: 12px;

        border: 1px solid #555;
        border-radius: 5px;

        color: #fff;
        font-size: 14px;
        font-weight: bold;

        transition: .3s;
    }

    .class-btn:hover {
        background: #e50914;
        border-color: #e50914;
        color: #fff;
    }

    /* NOTE */
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

    /* CTA */
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

    /* RESPONSIVE */
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
    }
</style>


<div class="classes-page">

    {{-- HERO --}}
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


    {{-- DANH SÁCH LỚP --}}
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


        <div class="classes-grid">

            {{-- LỚP 1 --}}
            <div class="class-card">

                <div class="class-image">

                    <img
                        src="{{ asset('img/yoga.jpg') }}"
                        alt="Yoga">

                    <span class="class-status">
                        ĐANG HOẠT ĐỘNG
                    </span>

                </div>

                <div class="class-body">

                    <h3>
                        YOGA
                    </h3>

                    <p class="class-description">
                        Các bài tập Yoga giúp cải thiện sự dẻo dai,
                        cân bằng cơ thể và thư giãn tinh thần.
                    </p>

                    <div class="class-info">

                        <div class="class-info-row">
                            <span>Huấn luyện viên</span>
                            <span>Nguyễn Thị Lan</span>
                        </div>

                        <div class="class-info-row">
                            <span>Lịch tập</span>
                            <span>Thứ 2 - 4 - 6</span>
                        </div>

                        <div class="class-info-row">
                            <span>Thời gian</span>
                            <span>18:00 - 19:00</span>
                        </div>

                        <div class="class-info-row">
                            <span>Sức chứa</span>
                            <span>20 người</span>
                        </div>

                    </div>

                    <a href="/register" class="class-btn">
                        ĐĂNG KÝ LỚP
                    </a>

                </div>

            </div>


            {{-- LỚP 2 --}}
            <div class="class-card">

                <div class="class-image">

                    <img
                        src="{{ asset('img/photo-1581009146145-b5ef050c2e1e.avif') }}"
                        alt="Gym">

                    <span class="class-status">
                        ĐANG HOẠT ĐỘNG
                    </span>

                </div>

                <div class="class-body">

                    <h3>
                        GYM CƠ BẢN
                    </h3>

                    <p class="class-description">
                        Làm quen với các thiết bị Gym và xây dựng
                        nền tảng thể lực cơ bản.
                    </p>

                    <div class="class-info">

                        <div class="class-info-row">
                            <span>Huấn luyện viên</span>
                            <span>Trần Minh Khoa</span>
                        </div>

                        <div class="class-info-row">
                            <span>Lịch tập</span>
                            <span>Thứ 3 - 5 - 7</span>
                        </div>

                        <div class="class-info-row">
                            <span>Thời gian</span>
                            <span>17:00 - 18:00</span>
                        </div>

                        <div class="class-info-row">
                            <span>Sức chứa</span>
                            <span>15 người</span>
                        </div>

                    </div>

                    <a href="/register" class="class-btn">
                        ĐĂNG KÝ LỚP
                    </a>

                </div>

            </div>


            {{-- LỚP 3 --}}
            <div class="class-card">

                <div class="class-image">

                    <img
                        src="{{ asset('img/photo-1534438327276-14e5300c3a48.avif') }}"
                        alt="Cardio">

                    <span class="class-status">
                        ĐANG HOẠT ĐỘNG
                    </span>

                </div>

                <div class="class-body">

                    <h3>
                        CARDIO
                    </h3>

                    <p class="class-description">
                        Bài tập cardio giúp tăng sức bền,
                        đốt cháy năng lượng và cải thiện tim mạch.
                    </p>

                    <div class="class-info">

                        <div class="class-info-row">
                            <span>Huấn luyện viên</span>
                            <span>Lê Hoàng Nam</span>
                        </div>

                        <div class="class-info-row">
                            <span>Lịch tập</span>
                            <span>Thứ 2 - 4 - 6</span>
                        </div>

                        <div class="class-info-row">
                            <span>Thời gian</span>
                            <span>19:00 - 20:00</span>
                        </div>

                        <div class="class-info-row">
                            <span>Sức chứa</span>
                            <span>25 người</span>
                        </div>

                    </div>

                    <a href="/register" class="class-btn">
                        ĐĂNG KÝ LỚP
                    </a>

                </div>

            </div>


            {{-- LỚP 4 --}}
            <div class="class-card">

                <div class="class-image">

                    <img
                        src="{{ asset('img/photo-1571902943202-507ec2618e8f.avif') }}"
                        alt="Fitness">

                    <span class="class-status">
                        ĐANG HOẠT ĐỘNG
                    </span>

                </div>

                <div class="class-body">

                    <h3>
                        FITNESS
                    </h3>

                    <p class="class-description">
                        Chương trình vận động toàn thân giúp
                        cải thiện sức khỏe và vóc dáng.
                    </p>

                    <div class="class-info">

                        <div class="class-info-row">
                            <span>Huấn luyện viên</span>
                            <span>Phạm Quốc Huy</span>
                        </div>

                        <div class="class-info-row">
                            <span>Lịch tập</span>
                            <span>Thứ 3 - 5</span>
                        </div>

                        <div class="class-info-row">
                            <span>Thời gian</span>
                            <span>18:30 - 19:30</span>
                        </div>

                        <div class="class-info-row">
                            <span>Sức chứa</span>
                            <span>20 người</span>
                        </div>

                    </div>

                    <a href="/register" class="class-btn">
                        ĐĂNG KÝ LỚP
                    </a>

                </div>

            </div>


            {{-- LỚP 5 --}}
            <div class="class-card">

                <div class="class-image">

                    <img
                        src="{{ asset('img/strength.jpg') }}"
                        alt="Strength">

                    <span class="class-status">
                        ĐANG HOẠT ĐỘNG
                    </span>

                </div>

                <div class="class-body">

                    <h3>
                        STRENGTH
                    </h3>

                    <p class="class-description">
                        Tập trung phát triển sức mạnh,
                        cơ bắp và khả năng vận động.
                    </p>

                    <div class="class-info">

                        <div class="class-info-row">
                            <span>Huấn luyện viên</span>
                            <span>Võ Minh Thái</span>
                        </div>

                        <div class="class-info-row">
                            <span>Lịch tập</span>
                            <span>Thứ 2 - 4 - 6</span>
                        </div>

                        <div class="class-info-row">
                            <span>Thời gian</span>
                            <span>20:00 - 21:00</span>
                        </div>

                        <div class="class-info-row">
                            <span>Sức chứa</span>
                            <span>15 người</span>
                        </div>

                    </div>

                    <a href="/register" class="class-btn">
                        ĐĂNG KÝ LỚP
                    </a>

                </div>

            </div>


            {{-- LỚP 6 --}}
            <div class="class-card">

                <div class="class-image">

                    <img
                        src="{{ asset('img/boxing.jpg') }}"
                        alt="Boxing">

                    <span class="class-status">
                        ĐANG HOẠT ĐỘNG
                    </span>

                </div>

                <div class="class-body">

                    <h3>
                        BOXING
                    </h3>

                    <p class="class-description">
                        Rèn luyện sức mạnh, phản xạ và khả năng
                        phối hợp thông qua các bài tập Boxing.
                    </p>

                    <div class="class-info">

                        <div class="class-info-row">
                            <span>Huấn luyện viên</span>
                            <span>Nguyễn Hoàng Long</span>
                        </div>

                        <div class="class-info-row">
                            <span>Lịch tập</span>
                            <span>Thứ 3 - 5 - 7</span>
                        </div>

                        <div class="class-info-row">
                            <span>Thời gian</span>
                            <span>19:30 - 20:30</span>
                        </div>

                        <div class="class-info-row">
                            <span>Sức chứa</span>
                            <span>12 người</span>
                        </div>

                    </div>

                    <a href="/register" class="class-btn">
                        ĐĂNG KÝ LỚP
                    </a>

                </div>

            </div>

        </div>


        <div class="classes-note">
            * Thông tin lớp tập hiện đang là dữ liệu giao diện mẫu.
            Sau khi hoàn thiện chức năng, dữ liệu sẽ được lấy từ hệ thống quản lý lớp tập.
        </div>

    </section>


    {{-- CTA --}}
    <section class="classes-cta">

        <h2>
            TÌM ĐƯỢC LỚP TẬP PHÙ HỢP?
        </h2>

        <p>
            Đăng ký tài khoản để tham gia các lớp tập tại GYMFIT.
        </p>

        <a href="/register" class="cta-btn">
            ĐĂNG KÝ NGAY
        </a>

    </section>

</div>

@endsection