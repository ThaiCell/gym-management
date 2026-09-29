@extends('layouts.user')

@section('title', 'Đăng ký - GYM MANAGEMENT')

@section('content')

    <style>
        .register-page {
            min-height: calc(100vh - 75px);

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 50px 20px;

            background: #0b0b0b;
        }


        .register-box {
            width: 480px;

            background: #111;

            border: 1px solid #292929;

            border-radius: 10px;

            padding: 40px;

            box-shadow:
                0 20px 60px rgba(0, 0, 0, .4);
        }


        .register-logo {
            text-align: center;

            font-size: 30px;

            font-weight: 900;

            margin-bottom: 10px;
        }


        .register-logo span {
            color: #e50914;
        }


        .register-title {
            text-align: center;

            font-size: 26px;

            margin-bottom: 8px;
        }


        .register-description {
            text-align: center;

            color: #888;

            margin-bottom: 30px;

            font-size: 14px;
        }


        .form-group {
            margin-bottom: 18px;
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


        .register-btn-submit {
            width: 100%;

            border: none;

            background: #e50914;

            color: white;

            padding: 14px;

            border-radius: 5px;

            font-weight: bold;

            cursor: pointer;
        }


        .register-btn-submit:hover {
            background: #ff2633;
        }


        .register-login {
            text-align: center;

            margin-top: 25px;

            color: #888;

            font-size: 14px;
        }


        .register-login a {
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
    </style>


    <div class="register-page">

        <div class="register-box">

            <div class="register-logo">
                GYM<span>FIT</span>
            </div>


            <h1 class="register-title">
                Tạo tài khoản
            </h1>


            <p class="register-description">
                Đăng ký tài khoản hội viên
            </p>


            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif


            <form action="/register" method="POST">

                @csrf


                <div class="form-group">

                    <label>
                        Họ và tên
                    </label>

                    <input type="text" name="ho_ten" value="{{ old('ho_ten') }}" placeholder="Nhập họ và tên" required>

                </div>


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

                    <input type="password" name="mat_khau" placeholder="Nhập mật khẩu" minlength="6" required>

                </div>


                <div class="form-group">

                    <label>
                        Nhập lại mật khẩu
                    </label>

                    <input type="password" name="mat_khau_confirmation" placeholder="Nhập lại mật khẩu" minlength="6"
                        required>

                </div>


                <button type="submit" class="register-btn-submit">
                    ĐĂNG KÝ
                </button>

            </form>


            <div class="register-login">

                Đã có tài khoản?

                <a href="/login">
                    Đăng nhập
                </a>

            </div>

        </div>

    </div>

@endsection
