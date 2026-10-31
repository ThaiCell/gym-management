
@extends('layouts.admin')

@section('content')

<style id="gymfit-admin-ui-final">
    /* GYMFIT admin typography and consistent controls */
    body { font-family: "Segoe UI", Arial, "Noto Sans", sans-serif !important; }
    body h1, body h2, body h3, body h4, body p, body label, body th, body td, body a, body button, body input, body select, body textarea, body small, body span { font-family: inherit; }
    button, input, select, textarea { font-family: inherit !important; }
    .gymfit-form-actions, .class-form-actions, .trainer-create-actions, .pt-form-actions, .button-area, .member-actions {
        display:flex !important; justify-content:center !important; align-items:center !important;
        gap:14px !important; flex-wrap:wrap !important; margin:28px auto 0 !important;
        width:100% !important;
    }
    .gymfit-form-actions > *, .class-form-actions > *, .trainer-create-actions > *,
    .pt-form-actions > *, .button-area > *, .member-actions > *,
    .class-form-btn, .trainer-create-submit, .trainer-create-cancel,
    .pt-btn, .save-btn, .cancel-btn, .member-btn {
        box-sizing:border-box !important; display:inline-flex !important;
        justify-content:center !important; align-items:center !important;
        min-width:190px !important; width:190px !important; height:48px !important;
        min-height:48px !important; padding:0 18px !important; margin:0 !important;
        border-radius:8px !important; font-size:14px !important; font-weight:700 !important;
        line-height:1.2 !important; text-align:center !important; text-decoration:none !important;
        white-space:nowrap !important; cursor:pointer !important;
    }
    .gymfit-form-page, .class-form-page, .trainer-create-page, .member-create, .edit-member-page,
    .package-create-page, .package-edit-page, .pt-form, .container-fluid:has(form), .page-form-wrap {
        width:min(100% - 48px, 1180px) !important; max-width:1180px !important;
        margin:28px auto !important; padding:20px !important; box-sizing:border-box !important;
    }
    .gymfit-form-card, .class-form-card, .trainer-create-card, .member-card, .edit-card, .form-card, .pt-form-card {
        width:100% !important; max-width:none !important; padding:30px !important;
        box-sizing:border-box !important;
    }
    .filter-box form { justify-content:center !important; align-items:center !important;
        flex-wrap:wrap !important; gap:12px !important; }
    .filter-box input, .filter-box select, .filter-box button {
        height:44px !important; min-height:44px !important; box-sizing:border-box !important;
        display:inline-flex !important; align-items:center !important;
    }
    .filter-box button { min-width:120px !important; justify-content:center !important;
        padding:0 22px !important; font-size:14px !important; font-weight:700 !important; }
    @media(max-width:700px) {
        .gymfit-form-page, .class-form-page, .trainer-create-page, .member-create, .edit-member-page,
        .package-create-page, .package-edit-page, .pt-form, .container-fluid:has(form), .page-form-wrap { width:100% !important; margin:12px auto !important; padding:12px !important; }
        .gymfit-form-actions > *, .class-form-actions > *, .trainer-create-actions > *,
        .pt-form-actions > *, .button-area > *, .member-actions > *, .class-form-btn, .trainer-create-submit,
        .trainer-create-cancel, .pt-btn, .save-btn, .cancel-btn, .member-btn {
            width:100% !important; min-width:0 !important;
        }
    }
</style>

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

<style id="gymfit-admin-package-standard">

/* ===== GYMFIT ADMIN - SHARED DESIGN BASED ON PACKAGES PAGE ===== */
:root{
 --gym-bg:#0b0b0d; --gym-panel:#171717; --gym-panel-2:#1d1d1f;
 --gym-border:#2b2b2f; --gym-text:#f4f4f5; --gym-muted:#9a9aa1;
 --gym-red:#ed1c24; --gym-red-hover:#c9141b;
}
body, body *{
 font-family:"Segoe UI",Arial,"Noto Sans",sans-serif !important;
}
body{color:var(--gym-text);}
.admin-content,.content,.main-content,.page-content,.container-fluid{
 color:var(--gym-text);
}
.page-header,.class-header,.pt-head,.trainer-header,.search-area,.member-toolbar,.payment-top{
 gap:14px;
}
.page-header h1,.page-header h2,.page-title,.class-header h2,.pt-head h2,
.trainer-header h2,.settings-heading,.pr-title{
 color:#fff !important;font-size:25px !important;font-weight:750 !important;
 letter-spacing:-.25px;
}
.page-header p,.page-subtitle,.page-title p,.class-header p,.pt-head p,
.total-count,.package-count,.member-count,.total-text{
 color:var(--gym-muted) !important;font-size:14px !important;
}
/* Common cards and panels */
.class-card,.pt-card,.table-card,.member-card,.payment-box,.contact-box,
.process-box,.detail-card,.settings-card,.box,.table-box,.filter-box,
.package-card,.card,.panel,.pr-panel,.chart-box,.stat-card{
 background:var(--gym-panel) !important;
 border:1px solid var(--gym-border) !important;
 border-radius:10px !important;
 box-shadow:none !important;
}
.class-card,.pt-card,.table-card,.member-card,.payment-box,.table-box{
 overflow:hidden;
}
/* Search/filter toolbar */
.search-area,.member-toolbar,.pt-toolbar,.payment-top,.filter-box,
.contact-top,.pr-search{
 background:#191919 !important;
 border-color:var(--gym-border) !important;
}
.search-form,.pt-search,.filter-box form,.pr-search form{
 display:flex !important;align-items:center !important;gap:10px !important;
 flex-wrap:wrap !important;
}
.search-form input,.search-input,.pt-search input,.filter-box input,
.filter-box select,.pr-search input, input[type="search"]{
 height:44px !important;min-height:44px !important;
 box-sizing:border-box !important;background:#222 !important;
 color:#f5f5f5 !important;border:1px solid #38383d !important;
 border-radius:7px !important;padding:0 13px !important;
 font-size:14px !important;
}
.search-form input:focus,.search-input:focus,.pt-search input:focus,
.filter-box input:focus,.filter-box select:focus{
 outline:none !important;border-color:var(--gym-red) !important;
 box-shadow:0 0 0 2px rgba(237,28,36,.12) !important;
}
.search-btn,.filter-box button,.btn-filter,.pt-search .pt-btn{
 min-width:120px !important;height:44px !important;min-height:44px !important;
 padding:0 20px !important;display:inline-flex !important;
 align-items:center !important;justify-content:center !important;
 border-radius:7px !important;font-size:14px !important;font-weight:700 !important;
}
/* Tables */
.package-table,.pt-table,.class-table,.member-table,.trainer-table,
.payment-table,.table-box table,.pr-table,.detail-table,.registration-table{
 width:100%;border-collapse:collapse !important;
}
.package-table th,.pt-table th,.class-table th,.member-table th,
.trainer-table th,.payment-table th,.table-box th,.pr-table th{
 background:#111 !important;color:#aaa !important;
 border-bottom:1px solid var(--gym-border) !important;
 padding:14px 16px !important;font-size:13px !important;
 font-weight:650 !important;white-space:nowrap;
}
.package-table td,.pt-table td,.class-table td,.member-table td,
.trainer-table td,.payment-table td,.table-box td,.pr-table td{
 color:#ddd !important;border-top:1px solid #292929 !important;
 padding:14px 16px !important;font-size:14px !important;vertical-align:middle;
}
.package-table tbody tr:hover,.pt-table tbody tr:hover,.class-table tbody tr:hover,
.member-table tbody tr:hover,.trainer-table tbody tr:hover,
.payment-table tbody tr:hover,.table-box tbody tr:hover{
 background:#202020 !important;
}
/* All buttons share consistent geometry */
button,.btn,.class-btn,.pt-btn,.member-btn,.search-btn,.detail-btn,
.btn-detail,.back-btn,.add-trainer-btn,.home-button,.btn-filter,.btn-reset,
.btn-save,.save-btn,.cancel-btn,.trainer-create-submit,.trainer-create-cancel,
.class-form-btn,.pr-btn,.settings-submit,a.button{
 box-sizing:border-box !important;display:inline-flex !important;
 align-items:center !important;justify-content:center !important;
 min-height:40px !important;padding:0 18px !important;
 border-radius:7px !important;font-family:inherit !important;
 font-size:13px !important;font-weight:700 !important;line-height:1.2 !important;
 text-decoration:none !important;white-space:nowrap;cursor:pointer;
 transition:background .16s ease,border-color .16s ease,transform .16s ease;
}
.class-btn-primary,.pt-btn:not(.pt-secondary):not(.pt-danger),
.member-primary,.trainer-create-submit,.btn-save,.settings-submit,
.search-btn,.filter-box button,.btn-filter,.add-trainer-btn,.home-button{
 background:var(--gym-red) !important;color:#fff !important;border:1px solid var(--gym-red) !important;
}
.class-btn-primary:hover,.pt-btn:hover,.member-primary:hover,
.trainer-create-submit:hover,.btn-save:hover,.settings-submit:hover,
.search-btn:hover,.filter-box button:hover,.btn-filter:hover,.add-trainer-btn:hover{
 background:var(--gym-red-hover) !important;border-color:var(--gym-red-hover) !important;
 color:#fff !important;
}
.class-btn-edit,.pt-secondary,.member-secondary,.cancel-btn,.trainer-create-cancel,
.back-btn,.detail-btn,.btn-detail,.btn-reset{
 background:#252529 !important;color:#f2f2f2 !important;
 border:1px solid #414149 !important;
}
.class-btn-delete,.pt-danger{
 background:rgba(229,9,20,.10) !important;color:#ff6871 !important;
 border:1px solid rgba(229,9,20,.48) !important;
}
.class-actions,.pt-actions,.member-actions,.button-area,.gymfit-form-actions,
.class-form-actions,.trainer-create-actions,.pt-form-actions{
 display:flex !important;align-items:center !important;gap:10px !important;
 flex-wrap:wrap !important;
}
/* Add/edit forms: centered, wider and less empty */
.class-form-page,.trainer-create-page,.member-create,.edit-member-page,
.package-create-page,.package-edit-page,.pt-form,.settings-page,
.container-fluid:has(form),.page-form-wrap{
 width:min(calc(100% - 56px),1180px) !important;max-width:1180px !important;
 margin:30px auto !important;padding:24px !important;box-sizing:border-box !important;
}
.class-form-card,.trainer-create-card,.member-card,.edit-card,.form-card,
.pt-form-card,.settings-card{
 width:100% !important;max-width:none !important;padding:30px !important;
 box-sizing:border-box !important;background:var(--gym-panel) !important;
 border:1px solid var(--gym-border) !important;border-radius:10px !important;
}
.class-form-header,.trainer-create-header,.edit-header,.detail-header{
 margin-bottom:20px !important;
}
.class-form-grid,.trainer-create-grid,.member-grid,.form-grid,.pt-grid,.settings-grid{
 gap:18px !important;
}
.class-field,.trainer-create-field,.member-field,.form-group,.pt-field,.settings-field{
 margin-bottom:14px;
}
.class-field label,.trainer-create-field label,.member-field label,
.form-group label,.pt-field label,.settings-field label{
 display:block;color:#eee !important;font-size:14px !important;
 font-weight:650 !important;margin-bottom:8px !important;
}
.class-field input,.class-field select,.class-field textarea,
.trainer-create-field input,.trainer-create-field select,.trainer-create-field textarea,
.member-field input,.member-field select,.member-field textarea,
.form-group input,.form-group select,.form-group textarea,
.pt-field input,.pt-field select,.pt-field textarea,
.settings-field input,.settings-field select,.settings-field textarea{
 width:100%;min-height:44px;box-sizing:border-box;
 background:#222 !important;color:#f5f5f5 !important;
 border:1px solid #38383d !important;border-radius:7px !important;
 padding:10px 13px !important;font-size:14px !important;
}
.class-field textarea,.trainer-create-field textarea,.form-group textarea{min-height:110px;}
/* Form actions: Save and Cancel same size, centered */
.class-form-actions,.trainer-create-actions,.pt-form-actions,
.member-actions,.button-area,.gymfit-form-actions{
 justify-content:center !important;width:100% !important;
 margin:26px auto 0 !important;gap:12px !important;
}
.class-form-actions > *, .trainer-create-actions > *,
.pt-form-actions > *,.member-actions > *,.button-area > *,
.gymfit-form-actions > *, .class-form-btn,.trainer-create-submit,
.trainer-create-cancel,.pt-form-actions .pt-btn,.member-btn,
.save-btn,.cancel-btn{
 width:190px !important;min-width:190px !important;
 height:48px !important;min-height:48px !important;
 padding:0 18px !important;font-size:14px !important;
}
/* Status chips */
.status,.class-status,.pt-status,.pr-status{
 display:inline-flex !important;align-items:center;justify-content:center;
 border-radius:20px !important;padding:5px 10px !important;
 font-size:12px !important;font-weight:650 !important;white-space:nowrap;
}
/* responsive */
@media(max-width:760px){
 .class-form-page,.trainer-create-page,.member-create,.edit-member-page,
 .package-create-page,.package-edit-page,.pt-form,.settings-page,
 .container-fluid:has(form),.page-form-wrap{
  width:100% !important;margin:12px auto !important;padding:12px !important;
 }
 .class-form-card,.trainer-create-card,.member-card,.edit-card,.form-card,
 .pt-form-card,.settings-card{padding:18px !important;}
 .class-form-actions > *, .trainer-create-actions > *,
 .pt-form-actions > *,.member-actions > *,.button-area > *,
 .gymfit-form-actions > *{width:100% !important;min-width:0 !important;}
 .search-area,.member-toolbar,.pt-toolbar,.payment-top{align-items:stretch !important;flex-direction:column;}
}

</style>
@endsection
