@extends('layouts.user')
@section('title', 'Đặt lại mật khẩu - GYMFIT')
@section('content')
<div class="auth-page">
  <div class="auth-box">
    <div class="auth-logo">GYM<span>FIT</span></div>
    <h1 class="auth-title">Đặt mật khẩu mới</h1>
    <p class="auth-description">Tạo mật khẩu mới có ít nhất 8 ký tự.</p>
    @if($errors->any())<div class="info-message" role="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">
      <input type="hidden" name="token" value="{{ $token }}">
      <label for="password" style="display:block;color:#ddd;font-weight:700;margin:18px 0 8px">Mật khẩu mới</label>
      <input id="password" type="password" name="password" minlength="8" required autocomplete="new-password" style="width:100%;padding:14px;border-radius:8px;border:1px solid #34343a;background:#111318;color:#fff">
      <label for="password_confirmation" style="display:block;color:#ddd;font-weight:700;margin:18px 0 8px">Xác nhận mật khẩu</label>
      <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" required autocomplete="new-password" style="width:100%;padding:14px;border-radius:8px;border:1px solid #34343a;background:#111318;color:#fff">
      <button class="auth-btn" type="submit">CẬP NHẬT MẬT KHẨU</button>
    </form>
  </div>
</div>
@endsection
