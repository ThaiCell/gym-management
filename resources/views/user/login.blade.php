@extends('layouts.user')

@section('title', 'Đăng nhập - GYM MANAGEMENT')

@section('content')

    <style>
        .auth-page {
            min-height: calc(100vh - 75px);

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 50px 20px;

            background:
                linear-gradient(rgba(0, 0, 0, .82),
                    rgba(0, 0, 0, .92)),
                url("/img/banner1.avif");

            background-size: cover;

            background-position: center;
        }


        .auth-box {
            width: 430px;

            background: #111;

            border: 1px solid #292929;

            border-radius: 10px;

            padding: 40px;

            box-shadow:
                0 20px 60px rgba(0, 0, 0, .5);
        }


        .auth-logo {
            text-align: center;

            font-size: 30px;

            font-weight: 900;

            margin-bottom: 10px;
        }


        .auth-logo span {
            color: #e50914;
        }


        .auth-title {
            text-align: center;

            font-size: 26px;

            margin-bottom: 8px;
        }


        .auth-description {
            text-align: center;

            color: #888;

            margin-bottom: 30px;

            font-size: 14px;
        }


        .form-group {
            margin-bottom: 20px;
        }


        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: #ddd;

            font-size: 14px;
        }


        .form-group input {
            width: 100%;

            padding: 13px 15px;

            background: #1b1b1b;

            border: 1px solid #333;

            border-radius: 5px;

            color: white;

            outline: none;
        }


        .form-group input:focus {
            border-color: #e50914;
        }


        .auth-btn {
            width: 100%;

            border: none;

            background: #e50914;

            color: white;

            padding: 14px;

            border-radius: 5px;

            font-weight: bold;

            cursor: pointer;
        }


        .auth-btn:hover {
            background: #ff2633;
        }


        .auth-register {
            text-align: center;

            margin-top: 25px;

            color: #888;

            font-size: 14px;
        }


        .auth-register a {
            color: #e50914;

            font-weight: bold;
        }


        .error-message {
            background:
                rgba(229, 9, 20, .12);

            border:
                1px solid #e50914;

            color: #ff6972;

            padding: 12px;

            border-radius: 5px;

            margin-bottom: 20px;

            font-size: 14px;
        }


        .success-message {
            background:
                rgba(0, 180, 80, .12);

            border:
                1px solid #00b450;

            color: #5ee39a;

            padding: 12px;

            border-radius: 5px;

            margin-bottom: 20px;

            font-size: 14px;
        }
    </style>


    <div class="auth-page">

        <div class="auth-box">

            <div class="auth-logo">
                GYM<span>FIT</span>
            </div>


            <h1 class="auth-title">
                Đăng nhập
            </h1>


            <p class="auth-description">
                Đăng nhập để tiếp tục sử dụng hệ thống
            </p>


            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif


            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif


            <form action="/login" method="POST">

                @csrf


                <div class="form-group">

                    <label>
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Nhập email" required>

                </div>


                <div class="form-group">

                    <label>
                        Mật khẩu
                    </label>

                    <input type="password" name="mat_khau" placeholder="Nhập mật khẩu" required>

                </div>


                <button type="submit" class="auth-btn">
                    ĐĂNG NHẬP
                </button>

            </form>


            <div class="auth-register">

                Chưa có tài khoản?

                <a href="/register">
                    Đăng ký ngay
                </a>

            </div>

        </div>

    </div>

@endsection
