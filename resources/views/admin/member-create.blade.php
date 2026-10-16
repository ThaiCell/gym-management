
@extends('layouts.admin')

@section('content')
<style>
.member-create{padding:26px;color:#fff}
.member-card{max-width:900px;background:#171717;border:1px solid #292929;
border-radius:12px;padding:24px}
.member-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.member-field{display:flex;flex-direction:column;gap:8px}
.member-field.full{grid-column:1/-1}
.member-field label{color:#aaa;font-size:14px}
.member-field input{background:#222;border:1px solid #383838;
border-radius:7px;padding:12px;color:#fff}
.member-actions{display:flex;justify-content:flex-end;gap:10px;
margin-top:24px;padding-top:20px;border-top:1px solid #333}
.member-btn{padding:11px 18px;border:0;border-radius:7px;
text-decoration:none;color:white;cursor:pointer}
.member-primary{background:#ed1c24}
.member-secondary{background:#333}
.member-error{background:#411b1b;color:#ffb5b5;padding:12px;
border-radius:7px;margin-bottom:16px}
.member-success{background:#123b25;color:#9cf0b7;padding:12px;border-radius:7px}
@media(max-width:650px){.member-grid{grid-template-columns:1fr}
.member-field.full{grid-column:auto}}
</style>

<div class="member-create">
    <h2>Thêm hội viên</h2>
    <p style="color:#999;margin-bottom:22px">
        Tạo tài khoản và hồ sơ hội viên mới
    </p>

    @if ($errors->any())
        <div class="member-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('error'))
        <div class="member-error">{{ session('error') }}</div>
    @endif

    <div class="member-card">
        <form method="POST" action="/admin/members/create">
            @csrf

            <div class="member-grid">
                <div class="member-field">
                    <label>Họ và tên *</label>
                    <input name="ho_ten" value="{{ old('ho_ten') }}" required maxlength="100">
                </div>

                <div class="member-field">
                    <label>Email đăng nhập *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required maxlength="100">
                </div>

                <div class="member-field">
                    <label>Mật khẩu ban đầu *</label>
                    <input type="password" name="mat_khau" required minlength="6" autocomplete="new-password">
                </div>

                <div class="member-field">
                    <label>Ngày sinh</label>
                    <input type="date" name="ngay_sinh" value="{{ old('ngay_sinh') }}">
                </div>

                <div class="member-field">
                    <label>Số điện thoại</label>
                    <input name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" maxlength="20">
                </div>

                <div class="member-field">
                    <label>Địa chỉ</label>
                    <input name="dia_chi" value="{{ old('dia_chi') }}" maxlength="255">
                </div>
            </div>

            <div class="member-actions">
                <a href="/admin/members" class="member-btn member-secondary">Hủy</a>
                <button type="submit" class="member-btn member-primary">Thêm hội viên</button>
            </div>
        </form>
    </div>
</div>
@endsection
