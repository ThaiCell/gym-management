@extends('layouts.user')
@section('title', 'Quên mật khẩu - GYMFIT')
@section('content')
<div class="auth-page">
  <div class="auth-box">
    <div class="auth-logo">GYM<span>FIT</span></div>
    <h1 class="auth-title">Khôi phục mật khẩu</h1>
    <p class="auth-description">Nhập email đã đăng ký. Nếu tài khoản tồn tại, hệ thống sẽ gửi liên kết đặt lại mật khẩu có hiệu lực trong 60 phút.</p>
    @if(session('status'))<div class="info-message" role="status">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="info-message" role="alert">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="info-message" role="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <label for="email" style="display:block;color:#ddd;font-weight:700;margin:18px 0 8px">Email tài khoản</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="width:100%;padding:14px;border-radius:8px;border:1px solid #34343a;background:#111318;color:#fff">
      <button class="auth-btn" type="submit">GỬI LIÊN KẾT KHÔI PHỤC</button>
    </form>
    <a href="{{ route('login') }}" class="auth-link">Quay lại đăng nhập</a>
  </div>
</div>
@endsection
