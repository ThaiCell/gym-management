@extends('layouts.member')

@section('title', 'Check-in hội viên')
@section('role_name', 'Nhân viên')

@section('menu')

    <a href="/">TRANG CHỦ</a>
    <a href="/about">GIỚI THIỆU</a>
    <a href="/packages">GÓI TẬP</a>
    <a href="/classes">LỚP TẬP</a>
    <a href="/trainers">HUẤN LUYỆN VIÊN</a>
    <a href="/contact">LIÊN HỆ</a>

@endsection


@section('content')

<style>

    .checkin-page {
        max-width: 1100px;
        margin: auto;
    }

    .checkin-hero {
        padding: 35px 40px;
        margin-bottom: 25px;
        border-radius: 18px;
        background:
            linear-gradient(110deg, #111, #171717, #090909);
        border: 1px solid #252525;
    }

    .checkin-label {
        color: #e50914;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .checkin-hero h1 {
        margin: 0;
        color: #fff;
        font-size: 32px;
    }

    .checkin-hero p {
        margin: 10px 0 0;
        color: #777;
        font-size: 13px;
    }

    .checkin-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .checkin-card {
        background: #111;
        border: 1px solid #252525;
        border-radius: 17px;
        overflow: hidden;
    }

    .checkin-card-header {
        padding: 22px 25px;
        border-bottom: 1px solid #222;
    }

    .checkin-card-header h2 {
        margin: 0;
        color: #fff;
        font-size: 19px;
    }

    .checkin-form {
        padding: 25px;
    }

    .checkin-group {
        margin-bottom: 20px;
    }

    .checkin-group label {
        display: block;
        margin-bottom: 8px;
        color: #aaa;
        font-size: 11px;
        font-weight: 700;
    }

    .checkin-group select {
        width: 100%;
        padding: 13px 14px;
        border-radius: 8px;
        border: 1px solid #333;
        background: #181818;
        color: #fff;
        outline: none;
        font-size: 13px;
    }

    .checkin-group select:focus {
        border-color: #e50914;
    }

    .checkin-btn {
        width: 100%;
        padding: 14px;
        border: 0;
        border-radius: 8px;
        background: #e50914;
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: .25s;
    }

    .checkin-btn:hover {
        background: #ff1824;
        transform: translateY(-2px);
    }

    .checkin-alert {
        margin-bottom: 20px;
        padding: 13px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .checkin-success {
        color: #4ade80;
        background: rgba(74, 222, 128, .08);
        border: 1px solid rgba(74, 222, 128, .25);
    }

    .checkin-error {
        color: #ff5b63;
        background: rgba(229, 9, 20, .08);
        border: 1px solid rgba(229, 9, 20, .25);
    }

    .checkin-info {
        padding: 25px;
    }

    .checkin-info-box {
        padding: 18px;
        margin-bottom: 14px;
        border-radius: 10px;
        background: #181818;
        border: 1px solid #252525;
    }

    .checkin-info-box:last-child {
        margin-bottom: 0;
    }

    .checkin-info-box strong {
        display: block;
        margin-bottom: 6px;
        color: #fff;
        font-size: 14px;
    }

    .checkin-info-box span {
        color: #777;
        font-size: 11px;
        line-height: 1.6;
    }

    .checkin-back {
        display: inline-block;
        margin-top: 18px;
        color: #888;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
    }

    .checkin-back:hover {
        color: #e50914;
    }

    @media (max-width: 800px) {

        .checkin-layout {
            grid-template-columns: 1fr;
        }

        .checkin-hero {
            padding: 30px 25px;
        }

        .checkin-hero h1 {
            font-size: 26px;
        }

    }

</style>


<div class="checkin-page">

    {{-- HEADER --}}

    <div class="checkin-hero">

        <div class="checkin-label">
            GYMFIT • STAFF AREA
        </div>

        <h1>
            CHECK-IN HỘI VIÊN
        </h1>

        <p>
            Xác nhận hội viên vào phòng Gym và lưu lịch sử check-in.
        </p>

    </div>


    {{-- THÔNG BÁO --}}

    @if(session('success'))

        <div class="checkin-alert checkin-success">
            ✓ {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="checkin-alert checkin-error">
            ⚠ {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="checkin-alert checkin-error">

            @foreach($errors->all() as $error)
                <div>⚠ {{ $error }}</div>
            @endforeach

        </div>

    @endif


    <div class="checkin-layout">

        {{-- FORM --}}

        <div class="checkin-card">

            <div class="checkin-card-header">

                <h2>
                    Check-in hội viên
                </h2>

            </div>


            <form
                action="{{ url('/staff/check-in') }}"
                method="POST"
                class="checkin-form"
            >

                @csrf


                {{-- HỘI VIÊN --}}

                <div class="checkin-group">

                    <label>
                        HỘI VIÊN
                    </label>

                    <select
                        name="hoi_vien_id"
                        required
                    >

                        <option value="">
                            -- Chọn hội viên --
                        </option>

                        @foreach($hoiVien as $hv)

                            <option
                                value="{{ $hv->hoi_vien_id }}"
                                {{ old('hoi_vien_id') == $hv->hoi_vien_id ? 'selected' : '' }}
                            >
                                {{ $hv->ho_ten }}
                                - {{ $hv->email }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- GÓI TẬP --}}

                <div class="checkin-group">

                    <label>
                        GÓI TẬP ĐANG SỬ DỤNG
                    </label>

                    <select
                        name="dang_ky_goi_tap_id"
                        required
                    >

                        <option value="">
                            -- Chọn gói tập --
                        </option>

                        @foreach($goiTap as $goi)

                            <option
                                value="{{ $goi->dang_ky_goi_tap_id }}"
                                {{ old('dang_ky_goi_tap_id') == $goi->dang_ky_goi_tap_id ? 'selected' : '' }}
                            >
                                {{ $goi->ho_ten }}
                                -
                                {{ $goi->ten_goi }}
                                -
                                Còn {{ $goi->so_buoi_con_lai }} buổi
                            </option>

                        @endforeach

                    </select>

                </div>


                <button
                    type="submit"
                    class="checkin-btn"
                >
                    ✓ XÁC NHẬN CHECK-IN
                </button>

            </form>

        </div>


        {{-- HƯỚNG DẪN --}}

        <div class="checkin-card">

            <div class="checkin-card-header">

                <h2>
                    Quy trình Check-in
                </h2>

            </div>


            <div class="checkin-info">

                <div class="checkin-info-box">

                    <strong>
                        01. Chọn hội viên
                    </strong>

                    <span>
                        Chọn đúng hội viên đang đến phòng Gym.
                    </span>

                </div>


                <div class="checkin-info-box">

                    <strong>
                        02. Chọn gói tập
                    </strong>

                    <span>
                        Chỉ chọn gói đang hoạt động và còn trong thời hạn.
                    </span>

                </div>


                <div class="checkin-info-box">

                    <strong>
                        03. Xác nhận
                    </strong>

                    <span>
                        Hệ thống tự động lưu thời gian và nhân viên thực hiện.
                    </span>

                </div>


                <a
                    href="/staff/dashboard"
                    class="checkin-back"
                >
                    ← QUAY LẠI DASHBOARD
                </a>

            </div>

        </div>

    </div>

</div>

@endsection