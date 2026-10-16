
@extends('layouts.admin')

@section('content')
<style>
.settings-page { color:#fff; }
.settings-heading { margin-bottom:24px; }
.settings-heading h2 { font-size:26px; margin-bottom:8px; }
.settings-heading p { color:#999; }
.settings-grid { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.settings-card { background:#181818; border:1px solid #292929;
    border-radius:12px; padding:24px; }
.settings-card h3 { margin-bottom:8px; font-size:19px; }
.settings-desc { color:#999; font-size:13px; margin-bottom:22px; }
.settings-fields { display:grid; gap:17px; }
.settings-field { display:flex; flex-direction:column; gap:8px; }
.settings-field label { color:#bbb; font-size:14px; }
.settings-field input { width:100%; padding:12px; color:#fff;
    background:#222; border:1px solid #383838; border-radius:7px; }
.settings-field input:focus { outline:none; border-color:#ed1c24; }
.settings-submit { background:#ed1c24; color:#fff; border:0;
    border-radius:7px; padding:12px 18px; font-weight:600; cursor:pointer; }
.settings-submit:hover { background:#c9141b; }
.settings-alert { padding:12px 15px; border-radius:7px; margin-bottom:18px; }
.settings-success { background:#123b25; color:#9cf0b7; }
.settings-error { background:#411b1b; color:#ffb5b5; }
.settings-card form { display:grid; gap:18px; }
.settings-note { margin-top:20px; color:#888; font-size:12px; }
@media(max-width:900px) {
    .settings-grid { grid-template-columns:1fr; }
}
</style>

<div class="settings-page">
    <div class="settings-heading">
        <h2>Cài đặt hệ thống</h2>
        <p>Quản lý thông tin phòng gym và bảo mật tài khoản quản trị.</p>
    </div>

    @if(session('success_gym'))
        <div class="settings-alert settings-success">
            {{ session('success_gym') }}
        </div>
    @endif

    @if(session('success_password'))
        <div class="settings-alert settings-success">
            {{ session('success_password') }}
        </div>
    @endif

    @if($errors->any())
        <div class="settings-alert settings-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="settings-grid">
        <section class="settings-card">
            <h3>🏢 Thông tin phòng gym</h3>
            <p class="settings-desc">
                Các thông tin liên hệ cơ bản của phòng tập.
            </p>

            <form method="POST" action="/admin/settings/gym">
                @csrf

                <div class="settings-fields">
                    <div class="settings-field">
                        <label>Tên phòng gym *</label>
                        <input name="ten_phong_gym"
                            value="{{ old('ten_phong_gym', $settings['ten_phong_gym'] ?? 'Gym Management') }}"
                            maxlength="150" required>
                    </div>

                    <div class="settings-field">
                        <label>Địa chỉ</label>
                        <input name="dia_chi"
                            value="{{ old('dia_chi', $settings['dia_chi'] ?? '') }}"
                            maxlength="255">
                    </div>

                    <div class="settings-field">
                        <label>Số điện thoại</label>
                        <input name="so_dien_thoai"
                            value="{{ old('so_dien_thoai', $settings['so_dien_thoai'] ?? '') }}"
                            maxlength="20">
                    </div>

                    <div class="settings-field">
                        <label>Email liên hệ</label>
                        <input type="email" name="email_lien_he"
                            value="{{ old('email_lien_he', $settings['email_lien_he'] ?? '') }}"
                            maxlength="150">
                    </div>
                </div>

                <button class="settings-submit" type="submit">
                    Lưu thông tin
                </button>
            </form>
        </section>

        <section class="settings-card">
            <h3>🔐 Bảo mật tài khoản</h3>
            <p class="settings-desc">
                Đổi mật khẩu đăng nhập Admin.
            </p>

            <form method="POST" action="/admin/settings/password">
                @csrf

                <div class="settings-fields">
                    <div class="settings-field">
                        <label>Mật khẩu hiện tại *</label>
                        <input type="password" name="mat_khau_hien_tai"
                            autocomplete="current-password" required>
                    </div>

                    <div class="settings-field">
                        <label>Mật khẩu mới *</label>
                        <input type="password" name="mat_khau_moi"
                            minlength="8" autocomplete="new-password" required>
                    </div>

                    <div class="settings-field">
                        <label>Xác nhận mật khẩu mới *</label>
                        <input type="password" name="mat_khau_moi_confirmation"
                            minlength="8" autocomplete="new-password" required>
                    </div>
                </div>

                <button class="settings-submit" type="submit">
                    Đổi mật khẩu
                </button>
            </form>

            <p class="settings-note">
                Mật khẩu mới cần tối thiểu 8 ký tự và phải được xác nhận chính xác.
            </p>
        </section>
    </div>
</div>
@endsection
