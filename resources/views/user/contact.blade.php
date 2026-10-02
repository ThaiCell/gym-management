@extends('layouts.user')

@section('title', 'Liên hệ - GYM MANAGEMENT')

@section('content')

<style>
    .contact-page {
        background: #0b0b0b;
        color: #fff;
        min-height: 100vh;
    }

    .contact-hero {
        padding: 90px 30px;
        text-align: center;

        background:
            linear-gradient(
                rgba(0,0,0,.75),
                rgba(0,0,0,.88)
            ),
            url("/img/photo-1534438327276-14e5300c3a48.avif");

        background-size: cover;
        background-position: center;
    }

    .contact-label {
        color: #e50914;
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 3px;
    }

    .contact-hero h1 {
        font-size: 50px;
        font-weight: 900;
        margin: 15px 0;
    }

    .contact-hero h1 span {
        color: #e50914;
    }

    .contact-hero p {
        max-width: 700px;
        margin: auto;
        color: #bbb;
        line-height: 1.8;
    }

    .contact-section {
        max-width: 1200px;
        margin: auto;
        padding: 75px 40px;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 35px;
    }

    .contact-box {
        background: #151515;
        border: 1px solid #292929;
        border-radius: 8px;
        padding: 32px;
    }

    .contact-box h2 {
        font-size: 28px;
        margin-bottom: 25px;
    }

    .contact-info {
        display: flex;
        gap: 18px;
        margin-bottom: 25px;
    }

    .contact-icon {
        width: 45px;
        height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #1e1e1e;
        border-radius: 6px;

        font-size: 20px;
    }

    .contact-info h3 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .contact-info p {
        color: #999;
        margin: 0;
        line-height: 1.6;
        font-size: 14px;
    }

    .contact-form-group {
        margin-bottom: 18px;
    }

    .contact-form-group label {
        display: block;
        margin-bottom: 8px;
        color: #ccc;
        font-size: 14px;
    }

    .contact-form-group input,
    .contact-form-group textarea {
        width: 100%;

        background: #0f0f0f;
        color: #fff;

        border: 1px solid #333;
        border-radius: 5px;

        padding: 13px 14px;

        outline: none;
        font-family: inherit;
    }

    .contact-form-group input:focus,
    .contact-form-group textarea:focus {
        border-color: #e50914;
    }

    .contact-form-group textarea {
        min-height: 140px;
        resize: vertical;
    }

    .contact-submit {
        width: 100%;

        border: none;
        border-radius: 5px;

        padding: 14px;

        background: #e50914;
        color: #fff;

        font-weight: bold;
        cursor: pointer;

        transition: .3s;
    }

    .contact-submit:hover {
        background: #ff2633;
    }

    .contact-hours {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #292929;
    }

    .contact-hours h3 {
        font-size: 16px;
        margin-bottom: 12px;
    }

    .hours-row {
        display: flex;
        justify-content: space-between;
        color: #999;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .contact-map {
        margin-top: 35px;

        height: 300px;

        display: flex;
        align-items: center;
        justify-content: center;

        background:
            linear-gradient(
                rgba(0,0,0,.65),
                rgba(0,0,0,.65)
            ),
            url("/img/photo-1571902943202-507ec2618e8f.avif");

        background-size: cover;
        background-position: center;

        border: 1px solid #292929;
        border-radius: 8px;
    }

    .map-content {
        text-align: center;
    }

    .map-content h3 {
        font-size: 24px;
        margin-bottom: 8px;
    }

    .map-content p {
        color: #bbb;
    }

    @media (max-width: 800px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-section {
            padding: 55px 20px;
        }

        .contact-hero h1 {
            font-size: 38px;
        }
    }
</style>


<div class="contact-page">

    @if(session('success'))
    <div style="
        max-width:1200px;
        margin:20px auto 0;
        padding:14px 20px;
        background:#123d1d;
        border:1px solid #28a745;
        color:#fff;
        border-radius:6px;
            ">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="
                max-width:1200px;
                margin:20px auto 0;
                padding:14px 20px;
                background:#3d1212;
                border:1px solid #e50914;
                color:#fff;
                border-radius:6px;
            ">
                <ul style="margin:0;padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <section class="contact-hero">

        <span class="contact-label">
            GYMFIT CONTACT
        </span>

        <h1>
            <span>LIÊN HỆ</span> VỚI CHÚNG TÔI
        </h1>

        <p>
            Bạn cần tư vấn về gói tập, lớp tập hoặc huấn luyện viên?
            Hãy liên hệ với GYMFIT.
        </p>

    </section>


    <section class="contact-section">

        <div class="contact-grid">

            {{-- THÔNG TIN --}}
            <div class="contact-box">

                <h2>
                    THÔNG TIN LIÊN HỆ
                </h2>


                <div class="contact-info">

                    <div class="contact-icon">
                        📍
                    </div>

                    <div>
                        <h3>
                            Địa chỉ
                        </h3>

                        <p>
                            123 Đường GymFit, Quận Trung Tâm,
                            TP. Hồ Chí Minh
                        </p>
                    </div>

                </div>


                <div class="contact-info">

                    <div class="contact-icon">
                        ☎
                    </div>

                    <div>
                        <h3>
                            Điện thoại
                        </h3>

                        <p>
                            0900 123 456
                        </p>
                    </div>

                </div>


                <div class="contact-info">

                    <div class="contact-icon">
                        ✉
                    </div>

                    <div>
                        <h3>
                            Email
                        </h3>

                        <p>
                            contact@gymfit.vn
                        </p>
                    </div>

                </div>


                <div class="contact-hours">

                    <h3>
                        Giờ hoạt động
                    </h3>

                    <div class="hours-row">
                        <span>Thứ 2 - Thứ 6</span>
                        <span>05:30 - 22:00</span>
                    </div>

                    <div class="hours-row">
                        <span>Thứ 7</span>
                        <span>06:00 - 21:00</span>
                    </div>

                    <div class="hours-row">
                        <span>Chủ nhật</span>
                        <span>07:00 - 20:00</span>
                    </div>

                </div>

            </div>


            {{-- FORM --}}
            <div class="contact-box">

                <h2>
                    GỬI TIN NHẮN
                </h2>

                <form action="{{ url('/contact') }}" method="POST">

                    @csrf

                    <div class="contact-form-group">

                        <label>
                            Họ và tên
                        </label>

                        <input
                            type="text"
                            name="ho_ten"
                            placeholder="Nhập họ và tên">
                    </div>


                    <div class="contact-form-group">

                        <label>
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Nhập email">
                    </div>


                    <div class="contact-form-group">

                        <label>
                            Số điện thoại
                        </label>

                        <input
                            type="text"
                            name="so_dien_thoai"
                            placeholder="Nhập số điện thoại">
                    </div>


                    <div class="contact-form-group">

                        <label>
                            Nội dung
                        </label>

                        <textarea
                            name="noi_dung"
                            placeholder="Nhập nội dung cần tư vấn"></textarea>

                    </div>


                    <button
                        type="submit"
                        class="contact-submit">

                        GỬI TIN NHẮN

                    </button>

                </form>

            </div>

        </div>


        <div class="contact-map">

            <div class="map-content">

                <h3>
                    GYMFIT
                </h3>

                <p>
                    Địa điểm phòng Gym
                </p>

            </div>

        </div>

    </section>

</div>

@endsection