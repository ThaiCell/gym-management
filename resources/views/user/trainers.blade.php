@extends('layouts.user')

@section('title', 'Huấn luyện viên - GYM MANAGEMENT')

@section('content')

<style>
    .trainers-page {
        background: #0b0b0b;
        color: #fff;
        min-height: 100vh;
    }

    .trainers-hero {
        padding: 90px 30px;
        text-align: center;

        background:
            linear-gradient(
                rgba(0,0,0,.72),
                rgba(0,0,0,.86)
            ),
            url("/img/photo-1517836357463-d25dfeac3438.avif");

        background-size: cover;
        background-position: center;
    }

    .trainers-label {
        color: #e50914;
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 3px;
    }

    .trainers-hero h1 {
        font-size: 50px;
        font-weight: 900;
        margin: 15px 0;
    }

    .trainers-hero h1 span {
        color: #e50914;
    }

    .trainers-hero p {
        max-width: 700px;
        margin: auto;
        color: #bbb;
        line-height: 1.8;
    }

    .trainers-section {
        max-width: 1400px;
        margin: auto;
        padding: 75px 60px;
    }

    .trainers-heading {
        text-align: center;
        margin-bottom: 45px;
    }

    .trainers-heading h2 {
        font-size: 36px;
        margin: 10px 0;
    }

    .trainers-heading p {
        color: #888;
    }

    .trainers-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .trainer-card {
        background: #151515;
        border: 1px solid #292929;
        border-radius: 8px;
        overflow: hidden;
        transition: .3s;
    }

    .trainer-card:hover {
        transform: translateY(-7px);
        border-color: #e50914;
    }

    .trainer-image {
        height: 330px;
        overflow: hidden;
    }

    .trainer-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: .4s;
    }

    .trainer-card:hover .trainer-image img {
        transform: scale(1.04);
    }

    .trainer-body {
        padding: 28px;
    }

    .trainer-body h3 {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .trainer-role {
        color: #e50914;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 18px;
    }

    .trainer-description {
        color: #999;
        line-height: 1.7;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .trainer-info {
        border-top: 1px solid #292929;
        padding-top: 18px;
    }

    .trainer-info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 13px;
    }

    .trainer-info-row span:first-child {
        color: #777;
    }

    .trainer-info-row span:last-child {
        color: #ddd;
        text-align: right;
    }

    .trainer-btn {
        display: block;
        width: 100%;

        text-align: center;
        padding: 12px;

        margin-top: 22px;

        border: 1px solid #555;
        border-radius: 5px;

        color: #fff;
        font-weight: bold;
        font-size: 14px;

        transition: .3s;
    }

    .trainer-btn:hover {
        background: #e50914;
        border-color: #e50914;
        color: #fff;
    }

    .trainers-cta {
        padding: 75px 30px;
        text-align: center;

        background: #111;
        border-top: 1px solid #222;
    }

    .trainers-cta h2 {
        font-size: 36px;
        margin-bottom: 15px;
    }

    .trainers-cta p {
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

    @media (max-width: 1000px) {
        .trainers-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .trainers-section {
            padding: 60px 30px;
        }
    }

    @media (max-width: 650px) {
        .trainers-grid {
            grid-template-columns: 1fr;
        }

        .trainers-hero h1 {
            font-size: 38px;
        }

        .trainers-section {
            padding: 50px 20px;
        }

        .trainers-heading h2,
        .trainers-cta h2 {
            font-size: 30px;
        }
    }
</style>


<div class="trainers-page">

    <section class="trainers-hero">

        <span class="trainers-label">
            GYMFIT TRAINERS
        </span>

        <h1>
            ĐỘI NGŨ <span>HUẤN LUYỆN VIÊN</span>
        </h1>

        <p>
            Đội ngũ huấn luyện viên giàu kinh nghiệm,
            hỗ trợ hội viên xây dựng kế hoạch tập luyện phù hợp.
        </p>

    </section>


    <section class="trainers-section">

        <div class="trainers-heading">

            <span class="trainers-label">
                HUẤN LUYỆN VIÊN
            </span>

            <h2>
                ĐỒNG HÀNH CÙNG BẠN
            </h2>

            <p>
                Lựa chọn huấn luyện viên phù hợp với mục tiêu tập luyện.
            </p>

        </div>


        <div class="trainers-grid">

            {{-- TRAINER 1 --}}
            <div class="trainer-card">

                <div class="trainer-image">
                    <img
                        src="{{ asset('img/trainer1.jpg') }}"
                        alt="Huấn luyện viên">
                </div>

                <div class="trainer-body">

                    <h3>
                        Nguyễn Văn An
                    </h3>

                    <div class="trainer-role">
                        HUẤN LUYỆN VIÊN FITNESS
                    </div>

                    <p class="trainer-description">
                        Hỗ trợ xây dựng kế hoạch tập luyện,
                        phát triển sức mạnh và cải thiện vóc dáng.
                    </p>

                    <div class="trainer-info">

                        <div class="trainer-info-row">
                            <span>Kinh nghiệm</span>
                            <span>5 năm</span>
                        </div>

                        <div class="trainer-info-row">
                            <span>Chuyên môn</span>
                            <span>Fitness</span>
                        </div>

                        <div class="trainer-info-row">
                            <span>Lịch làm việc</span>
                            <span>Thứ 2 - Thứ 7</span>
                        </div>

                    </div>

                    <a href="/contact" class="trainer-btn">
                        LIÊN HỆ
                    </a>

                </div>

            </div>


            {{-- TRAINER 2 --}}
            <div class="trainer-card">

                <div class="trainer-image">
                    <img
                        src="{{ asset('img/trainer2.jpg') }}"
                        alt="Huấn luyện viên Yoga">
                </div>

                <div class="trainer-body">

                    <h3>
                        Trần Thị Bình
                    </h3>

                    <div class="trainer-role">
                        HUẤN LUYỆN VIÊN YOGA
                    </div>

                    <p class="trainer-description">
                        Chuyên hướng dẫn Yoga, cải thiện độ dẻo dai,
                        cân bằng cơ thể và thư giãn tinh thần.
                    </p>

                    <div class="trainer-info">

                        <div class="trainer-info-row">
                            <span>Kinh nghiệm</span>
                            <span>4 năm</span>
                        </div>

                        <div class="trainer-info-row">
                            <span>Chuyên môn</span>
                            <span>Yoga</span>
                        </div>

                        <div class="trainer-info-row">
                            <span>Lịch làm việc</span>
                            <span>Thứ 2 - Thứ 6</span>
                        </div>

                    </div>

                    <a href="/contact" class="trainer-btn">
                        LIÊN HỆ
                    </a>

                </div>

            </div>


            {{-- TRAINER 3 --}}
            <div class="trainer-card">

                <div class="trainer-image">
                    <img
                        src="{{ asset('img/trainer3.jpg') }}"
                        alt="Huấn luyện viên Boxing">
                </div>

                <div class="trainer-body">

                    <h3>
                        Lê Minh Cường
                    </h3>

                    <div class="trainer-role">
                        HUẤN LUYỆN VIÊN BOXING
                    </div>

                    <p class="trainer-description">
                        Hướng dẫn Boxing, rèn luyện sức mạnh,
                        phản xạ và khả năng phối hợp vận động.
                    </p>

                    <div class="trainer-info">

                        <div class="trainer-info-row">
                            <span>Kinh nghiệm</span>
                            <span>6 năm</span>
                        </div>

                        <div class="trainer-info-row">
                            <span>Chuyên môn</span>
                            <span>Boxing</span>
                        </div>

                        <div class="trainer-info-row">
                            <span>Lịch làm việc</span>
                            <span>Thứ 3 - Thứ 7</span>
                        </div>

                    </div>

                    <a href="/contact" class="trainer-btn">
                        LIÊN HỆ
                    </a>

                </div>

            </div>

        </div>

    </section>


    <section class="trainers-cta">

        <h2>
            CẦN HỖ TRỢ LỰA CHỌN?
        </h2>

        <p>
            Liên hệ với GYMFIT để được tư vấn huấn luyện viên phù hợp.
        </p>

        <a href="/contact" class="cta-btn">
            LIÊN HỆ NGAY
        </a>

    </section>

</div>

@endsection