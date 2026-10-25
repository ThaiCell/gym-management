@extends('layouts.user')

@section('title', 'Đặt lại mật khẩu - GYM MANAGEMENT')

@section('content')
    <style>
        .auth-page {
            min-height: calc(100vh - 75px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
            background:
                linear-gradient(rgba(0, 0, 0, .82), rgba(0, 0, 0, .92)),
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
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
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

        .error-message {
            background: rgba(229, 9, 20, .12);
            border: 1px solid #e50914;
            color: #ff6972;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>

    <div class="auth-page">
        <div class="auth-box">
            <div class="auth-logo">GYM<span>FIT</span></div>
            <h1 class="auth-title">Đặt lại mật khẩu</h1>
            <p class="auth-description">Tạo mật khẩu mới cho tài khoản của bạn</p>

            @if ($errors->any())
                <div class="error-message">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input id="password" type="password" name="password" placeholder="Ít nhất 8 ký tự" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="auth-btn">ĐẶT LẠI MẬT KHẨU</button>
            </form>
        </div>
    </div>
@endsection
