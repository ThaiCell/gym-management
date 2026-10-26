@extends('layouts.user')

@section('title', 'Quên mật khẩu - GYM MANAGEMENT')

@section('content')
    <style>
        .auth-page {
            min-height: calc(100vh - 75px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
            background: linear-gradient(rgba(0, 0, 0, .82), rgba(0, 0, 0, .92)), url("/img/banner1.avif");
            background-size: cover;
            background-position: center
        }

        .auth-box {
            width: 430px;
            max-width: 100%;
            background: #111;
            border: 1px solid #292929;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5)
        }

        .auth-logo {
            text-align: center;
            font-size: 30px;
            font-weight: 900;
            margin-bottom: 10px
        }

        .auth-logo span {
            color: #e50914
        }

        .auth-title {
            text-align: center;
            font-size: 26px;
            margin-bottom: 8px
        }

        .auth-description {
            text-align: center;
            color: #888;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.6
        }

        .info-message {
            background: rgba(229, 9, 20, .08);
            border: 1px solid #5a2024;
            color: #ddd;
            padding: 16px;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.7
        }

        .auth-btn {
            display: block;
            width: 100%;
            text-align: center;
            text-decoration: none;
            border: none;
            background: #e50914;
            color: white;
            padding: 14px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px
        }

        .auth-btn:hover {
            background: #ff2633;
            color: white
        }

        .auth-link {
            display: block;
            margin-top: 20px;
            color: #e50914;
            text-align: center;
            font-size: 14px
        }
    </style>
    <div class="auth-page">
        <div class="auth-box">
            <div class="auth-logo">GYM<span>FIT</span></div>
            <h1 class="auth-title">Quên mật khẩu?</h1>
            <p class="auth-description">Liên hệ quản trị viên để được xác minh và cấp mật khẩu tạm thời.</p>
            <div class="info-message">
                <strong>Cách lấy lại mật khẩu</strong>
                <ol style="padding-left:20px;margin:10px 0 0">
                    <li>Liên hệ trực tiếp Admin hoặc bộ phận quản lý phòng gym.</li>
                    <li>Cung cấp thông tin để Admin xác minh đúng chủ tài khoản.</li>
                    <li>Sau khi xác minh, Admin cấp mật khẩu tạm thời cho mày.</li>
                    <li>Đăng nhập bằng mật khẩu tạm thời và đổi mật khẩu sau đó.</li>
                </ol>
            </div>
            <a href="{{ route('login') }}" class="auth-btn">QUAY LẠI ĐĂNG NHẬP</a>
        </div>
    </div>
@endsection
