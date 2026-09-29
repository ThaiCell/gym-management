@extends('layouts.user')

@section('title', 'Giới thiệu - GYM MANAGEMENT')

@section('content')

<style>
    .about-page {
        background: #0b0b0b;
        color: #fff;
    }

    /* HERO */
    .about-hero {
        min-height: 430px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;

        background:
            linear-gradient(90deg,
                rgba(0,0,0,.9),
                rgba(0,0,0,.45)),
            url("/img/photo-1571902943202-507ec2618e8f.avif");

        background-size: cover;
        background-position: center;
    }

    .about-hero-content {
        max-width: 900px;
        padding: 70px 60px;
    }

    .about-small {
        color: #e50914;
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 3px;
        margin-bottom: 15px;
    }

    .about-hero h1 {
        font-size: 58px;
        line-height: 1.2;
        font-weight: 900;
        margin-bottom: 20px;
    }

    .about-hero h1 span {
        color: #e50914;
    }

    .about-hero p {
        color: #ddd;
        font-size: 17px;
        line-height: 1.8;
        max-width: 700px;
    }

    /* INTRO */
    .about-section {
        padding: 75px 60px;
        max-width: 1400px;
        margin: auto;
    }

    .about-intro {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 55px;
        align-items: center;
    }

    .about-image img {
        width: 100%;
        height: 470px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #292929;
    }

    .section-label {
        color: #e50914;
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 2px;
    }

    .about-content h2 {
        font-size: 38px;
        line-height: 1.15;
        margin: 12px 0 20px;
    }

    .about-content p {
        color: #aaa;
        line-height: 1.8;
        margin-bottom: 16px;
    }

    .about-list {
        list-style: none;
        padding: 0;
        margin: 25px 0 0;
    }

    .about-list li {
        color: #ddd;
        margin-bottom: 14px;
        line-height: 1.5;
    }

    .about-list li::before {
        content: "✓";
        color: #e50914;
        font-weight: bold;
        margin-right: 10px;
    }

    /* VALUES */
    .values-section {
        background: #111;
        border-top: 1px solid #222;
        border-bottom: 1px solid #222;
    }

    .section-heading {
        text-align: center;
        margin-bottom: 45px;
    }

    .section-heading h2 {
        font-size: 38px;
        margin: 10px 0;
    }

    .section-heading p {
        color: #888;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .value-card {
        background: #151515;
        border: 1px solid #292929;
        border-radius: 8px;
        padding: 32px 25px;
        transition: .3s;
    }

    .value-card:hover {
        transform: translateY(-5px);
        border-color: #e50914;
    }

    .value-icon {
        font-size: 32px;
        margin-bottom: 18px;
    }

    .value-card h3 {
        font-size: 21px;
        margin-bottom: 10px;
    }

    .value-card p {
        color: #999;
        line-height: 1.7;
        font-size: 14px;
    }

    /* CTA */
    .about-cta {
        text-align: center;
        padding: 80px 30px;

        background:
            linear-gradient(
                rgba(0,0,0,.78),
                rgba(0,0,0,.78)
            ),
            url("/img/photo-1534438327276-14e5300c3a48.avif");

        background-size: cover;
        background-position: center;
    }

    .about-cta h2 {
        font-size: 40px;
        margin-bottom: 15px;
    }

    .about-cta p {
        color: #ccc;
        margin-bottom: 28px;
    }

    .btn-red {
        display: inline-block;
        background: #e50914;
        color: #fff;
        padding: 14px 28px;
        border-radius: 5px;
        font-weight: bold;
        transition: .3s;
    }

    .btn-red:hover {
        background: #ff2633;
        color: #fff;
        transform: translateY(-2px);
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {

        .about-hero-content {
            padding: 55px 30px;
        }

        .about-hero h1 {
            font-size: 43px;
        }

        .about-section {
            padding: 55px 30px;
        }

        .about-intro,
        .values-grid {
            grid-template-columns: 1fr;
        }

        .about-image img {
            height: 350px;
        }
    }

    @media (max-width: 600px) {

        .about-hero h1 {
            font-size: 36px;
        }

        .about-hero p {
            font-size: 15px;
        }

        .about-section {
            padding: 45px 20px;
        }

        .section-heading h2,
        .about-content h2,
        .about-cta h2 {
            font-size: 30px;
        }
    }
</style>


<div class="about-page">

    {{-- HERO --}}
    <section class="about-hero">

        <div class="about-hero-content">

            <div class="about-small">
                VỀ GYMFIT
            </div>

            <h1>
                NƠI BẠN<br>
                <span>THAY ĐỔI</span> BẢN THÂN
            </h1>

            <p>
                GYMFIT là hệ thống quản lý phòng Gym được xây dựng
                nhằm giúp hội viên dễ dàng lựa chọn gói tập,
                tham gia lớp tập và kết nối với huấn luyện viên.
            </p>

        </div>

    </section>


    {{-- GIỚI THIỆU --}}
    <section class="about-section">

        <div class="about-intro">

            <div class="about-image">

                <img
                    src="{{ asset('img/photo-1571902943202-507ec2618e8f.avif') }}"
                    alt="Không gian phòng Gym">

            </div>


            <div class="about-content">

                <span class="section-label">
                    GIỚI THIỆU
                </span>

                <h2>
                    TẬP LUYỆN ĐƠN GIẢN HƠN,
                    HIỆU QUẢ HƠN
                </h2>

                <p>
                    GYMFIT hướng đến một trải nghiệm tập luyện
                    thuận tiện, hiện đại và phù hợp với nhiều nhu cầu.
                </p>

                <p>
                    Tại đây, hội viên có thể xem thông tin gói tập,
                    lớp tập, huấn luyện viên và quản lý các dịch vụ
                    của mình trên cùng một hệ thống.
                </p>

                <ul class="about-list">

                    <li>Không gian tập luyện hiện đại</li>

                    <li>Nhiều lựa chọn gói tập</li>

                    <li>Lớp tập đa dạng</li>

                    <li>Đội ngũ huấn luyện viên</li>

                    <li>Quản lý thông tin tập luyện thuận tiện</li>

                </ul>

            </div>

        </div>

    </section>


    {{-- GIÁ TRỊ --}}
    <section class="values-section">

        <div class="about-section">

            <div class="section-heading">

                <span class="section-label">
                    GIÁ TRỊ CỦA CHÚNG TÔI
                </span>

                <h2>
                    MỘT HỆ THỐNG,
                    NHIỀU TRẢI NGHIỆM
                </h2>

                <p>
                    Những tính năng giúp việc quản lý và tập luyện
                    trở nên thuận tiện hơn.
                </p>

            </div>


            <div class="values-grid">

                <div class="value-card">

                    <div class="value-icon">
                        🏋️
                    </div>

                    <h3>
                        Gói tập phù hợp
                    </h3>

                    <p>
                        Dễ dàng xem và lựa chọn các gói tập
                        phù hợp với thời gian và mục tiêu của bạn.
                    </p>

                </div>


                <div class="value-card">

                    <div class="value-icon">
                        📅
                    </div>

                    <h3>
                        Lớp tập & lịch tập
                    </h3>

                    <p>
                        Theo dõi lớp tập và lịch tập để chủ động
                        sắp xếp thời gian luyện tập.
                    </p>

                </div>


                <div class="value-card">

                    <div class="value-icon">
                        🥇
                    </div>

                    <h3>
                        Huấn luyện viên
                    </h3>

                    <p>
                        Tìm hiểu thông tin huấn luyện viên và
                        lựa chọn dịch vụ PT phù hợp.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- CTA --}}
    <section class="about-cta">

        <h2>
            SẴN SÀNG BẮT ĐẦU?
        </h2>

        <p>
            Khám phá các gói tập và bắt đầu hành trình
            của bạn cùng GYMFIT.
        </p>

        <a href="/packages" class="btn-red">
            XEM GÓI TẬP
        </a>

    </section>

</div>

@endsection