@extends('layouts.user')

@section('title', 'Trang chủ - GYM MANAGEMENT')

@section('content')
    <style>
        .home-page {
            margin: 0;
            background: #0b0b0b;
            color: #fff;
            min-height: 100%;
        }

        /* HERO */
        .hero {
            min-height: 750px;

            display: flex;
            align-items: center;

            position: relative;
            overflow: hidden;

            background:
                linear-gradient(90deg,
                    rgba(0, 0, 0, 0.92) 0%,
                    rgba(0, 0, 0, 0.68) 38%,
                    rgba(0, 0, 0, 0.30) 70%,
                    rgba(0, 0, 0, 0.15) 100%),
                url("/img/photo-1581009146145-b5ef050c2e1e.avif");

            background-size: cover;

            /* chỉnh vị trí ảnh */
            background-position: center 28%;

            background-repeat: no-repeat;
        }

        .hero-content {
            width: 700px;
            padding: 70px;
            position: relative;
            z-index: 2;
        }

        .hero-small {
            color: #e50914;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
        }

        .hero h1 {
            font-size: 65px;
            line-height: 1;
            margin: 0 0 25px;
            font-weight: 900;
        }

        .hero h1 span {
            color: #e50914;
        }

        .hero p {
            color: #ddd;
            font-size: 18px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .btn-red {
            display: inline-block;
            background: #e50914;
            color: #fff;
            padding: 15px 30px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-right: 10px;
            transition: .3s;
        }

        .btn-red:hover {
            background: #ff2633;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline {
            display: inline-block;
            border: 1px solid #fff;
            color: #fff;
            padding: 14px 30px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: .3s;
        }

        .btn-outline:hover {
            background: #fff;
            color: #111;
        }

        /* SECTION */
        .section {
            padding: 70px 60px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-title small {
            color: #e50914;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .section-title h2 {
            font-size: 38px;
            margin: 10px 0;
        }

        .section-title p {
            color: #aaa;
        }

        /* SERVICES */
        .services {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .service-card {
            background: #151515;
            border: 1px solid #292929;
            border-radius: 8px;
            overflow: hidden;
            transition: .3s;
        }

        .service-card:hover {
            transform: translateY(-7px);
            border-color: #e50914;
        }

        .service-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .service-content {
            padding: 25px;
        }

        .service-content h3 {
            margin: 0 0 10px;
            font-size: 22px;
        }

        .service-content p {
            color: #aaa;
            line-height: 1.6;
        }

        /* ABOUT */
        .about-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
            background: #111;
        }

        .about-image img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 8px;
        }

        .about-content small {
            color: #e50914;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .about-content h2 {
            font-size: 40px;
            margin: 12px 0 20px;
        }

        .about-content p {
            color: #aaa;
            line-height: 1.8;
            font-size: 16px;
        }

        .about-list {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }

        .about-list li {
            margin-bottom: 12px;
            color: #ddd;
        }

        .about-list li::before {
            content: "✓";
            color: #e50914;
            font-weight: bold;
            margin-right: 10px;
        }

        /* CTA */
        .cta {
            padding: 80px 40px;
            text-align: center;

            background:
                linear-gradient(rgba(0, 0, 0, .78),
                    rgba(0, 0, 0, .78)),
                url("/img/photo-1534438327276-14e5300c3a48.avif");

            background-size: cover;
            background-position: center;
        }

        .cta h2 {
            font-size: 45px;
            margin: 0 0 15px;
        }

        .cta p {
            color: #ddd;
            font-size: 18px;
            margin-bottom: 30px;
        }

        /* FOOTER */
        .home-footer {
            background: #050505;
            padding: 45px 60px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
            border-top: 1px solid #222;
        }

        .footer-logo {
            font-size: 28px;
            font-weight: 900;
        }

        .footer-logo span {
            color: #e50914;
        }

        .home-footer h3 {
            margin-top: 0;
        }

        .home-footer p,
        .home-footer a {
            color: #999;
            line-height: 1.8;
            text-decoration: none;
        }

        .home-footer a:hover {
            color: #e50914;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero-content {
                width: auto;
                padding: 50px 30px;
            }

            .hero h1 {
                font-size: 45px;
            }

            .services {
                grid-template-columns: 1fr;
            }

            .about-section {
                grid-template-columns: 1fr;
            }

            .home-footer {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 50px 30px;
            }
        }
    </style>


    <div class="home-page">

        {{-- HERO --}}
        <section class="hero">
            <div class="hero-content">

                <div class="hero-small">
                    Fitness • Strength • Lifestyle
                </div>

                <h1>
                    BẮT ĐẦU<br>
                    <span>THAY ĐỔI</span><br>
                    BẢN THÂN
                </h1>

                <p>
                    Chào mừng bạn đến với hệ thống quản lý phòng Gym.
                    Lựa chọn gói tập phù hợp, tham gia các lớp tập
                    và đồng hành cùng đội ngũ huấn luyện viên.
                </p>

                <a href="/packages" class="btn-red">
                    XEM GÓI TẬP
                </a>

                <a href="/about" class="btn-outline">
                    TÌM HIỂU THÊM
                </a>

            </div>
        </section>


        {{-- DỊCH VỤ --}}
        <section class="section">

            <div class="section-title">
                <small>DỊCH VỤ CỦA CHÚNG TÔI</small>

                <h2>
                    TẬP LUYỆN THEO CÁCH CỦA BẠN
                </h2>

                <p>
                    Những lựa chọn phù hợp cho mục tiêu tập luyện của bạn
                </p>
            </div>


            <div class="services">

                <div class="service-card">

                    <img src="{{ asset('img/photo-1534438327276-14e5300c3a48.avif') }}" alt="Phòng tập Gym">

                    <div class="service-content">

                        <h3>Gói tập Gym</h3>

                        <p>
                            Lựa chọn các gói tập phù hợp với thời gian
                            và nhu cầu luyện tập của bạn.
                        </p>

                        <a href="/packages" class="btn-red">
                            Xem gói tập
                        </a>

                    </div>

                </div>


                <div class="service-card">

                    <img src="{{ asset('img/photo-1517836357463-d25dfeac3438.avif') }}" alt="Lớp tập Gym">

                    <div class="service-content">

                        <h3>Lớp tập</h3>

                        <p>
                            Tham gia các lớp tập và lựa chọn lịch tập
                            phù hợp với thời gian của bạn.
                        </p>

                        <a href="/classes" class="btn-red">
                            Xem lớp tập
                        </a>

                    </div>

                </div>


                <div class="service-card">

                    <img src="{{ asset('img/photo-1571019614242-c5c5dee9f50b.avif') }}" alt="Huấn luyện viên">

                    <div class="service-content">

                        <h3>Huấn luyện viên</h3>

                        <p>
                            Đồng hành cùng các huấn luyện viên
                            trong quá trình tập luyện.
                        </p>

                        <a href="/trainers" class="btn-red">
                            Xem HLV
                        </a>

                    </div>

                </div>

            </div>

        </section>


        {{-- GIỚI THIỆU --}}
        <section class="section about-section">

            <div class="about-image">

                <img src="{{ asset('img/photo-1571902943202-507ec2618e8f.avif') }}" alt="Không gian phòng Gym">

            </div>


            <div class="about-content">

                <small>VỀ CHÚNG TÔI</small>

                <h2>
                    TẠO NÊN PHIÊN BẢN TỐT HƠN CỦA BẠN
                </h2>

                <p>
                    Chúng tôi xây dựng môi trường tập luyện hiện đại,
                    thuận tiện và phù hợp với nhiều nhu cầu khác nhau.
                </p>

                <ul class="about-list">

                    <li>Không gian tập luyện hiện đại</li>

                    <li>Nhiều lựa chọn gói tập</li>

                    <li>Lớp tập đa dạng</li>

                    <li>Đội ngũ huấn luyện viên</li>

                </ul>

                <a href="/about" class="btn-red">
                    XEM GIỚI THIỆU
                </a>

            </div>

        </section>


        {{-- CTA --}}
        <section class="cta">

            <h2>
                SẴN SÀNG BẮT ĐẦU?
            </h2>

            <p>
                Hãy lựa chọn gói tập phù hợp và bắt đầu hành trình
                thay đổi bản thân.
            </p>

            <a href="/register" class="btn-red">
                ĐĂNG KÝ NGAY
            </a>

            <a href="/contact" class="btn-outline">
                LIÊN HỆ
            </a>

        </section>
    @endsection
