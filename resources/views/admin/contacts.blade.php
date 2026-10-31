@extends('layouts.admin')

@section('title', 'Xử lý liên hệ')
@section('role_name', 'Quản trị viên')



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
        .contact-page {
            max-width: 1400px;
            margin: auto;
        }

        .page-header {
            padding: 35px 40px;
            margin-bottom: 25px;
            border-radius: 18px;
            background: linear-gradient(110deg, #111, #181818, #090909);
            border: 1px solid #292929;
        }

        .page-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .page-header h1 {
            margin: 8px 0;
            color: #fff;
            font-size: 30px;
        }

        .page-header p {
            color: #888;
            font-size: 13px;
            margin: 0;
        }

        .contact-box {
            background: #111;
            border: 1px solid #292929;
            border-radius: 16px;
            overflow: hidden;
        }

        .contact-item {
            padding: 25px;
            border-bottom: 1px solid #222;
        }

        .contact-item:last-child {
            border-bottom: 0;
        }

        .contact-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .contact-name {
            color: #fff;
            font-weight: 800;
            font-size: 15px;
        }

        .contact-info {
            color: #777;
            font-size: 11px;
            margin-top: 5px;
        }

        .contact-content {
            padding: 15px;
            background: #0b0b0b;
            border: 1px solid #202020;
            border-radius: 9px;
            color: #bbb;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .contact-response {
            padding: 13px 15px;
            background: rgba(74, 222, 128, .05);
            border: 1px solid rgba(74, 222, 128, .15);
            border-radius: 9px;
            color: #8ee5ae;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .response-label {
            display: block;
            color: #6ee7a0;
            font-size: 9px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
        }

        .status-pending {
            color: #facc15;
            background: rgba(250, 204, 21, .08);
            border: 1px solid rgba(250, 204, 21, .2);
        }

        .status-processing {
            color: #60a5fa;
            background: rgba(96, 165, 250, .08);
            border: 1px solid rgba(96, 165, 250, .2);
        }

        .status-done {
            color: #4ade80;
            background: rgba(74, 222, 128, .08);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .status-other {
            color: #ff6970;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        .contact-date {
            color: #666;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .process-box {
            padding: 18px;
            background: #0d0d0d;
            border: 1px solid #252525;
            border-radius: 12px;
        }

        .process-title {
            color: #ddd;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .process-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            color: #777;
            font-size: 10px;
            font-weight: 700;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            background: #111;
            color: #ddd;
            border: 1px solid #303030;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            outline: none;
        }

        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #e50914;
        }

        .form-group textarea {
            min-height: 90px;
            resize: vertical;
            font-family: inherit;
        }

        .btn-save {
            align-self: flex-start;
            padding: 10px 18px;
            background: #e50914;
            color: #fff;
            border: 0;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #ff1824;
        }

        .empty {
            text-align: center;
            padding: 70px 20px;
            color: #777;
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .empty h3 {
            color: #ddd;
            margin: 0 0 8px;
            font-size: 16px;
        }

        .empty p {
            margin: 0;
            font-size: 12px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .success {
            color: #6ee7a0;
            background: rgba(74, 222, 128, .08);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .error {
            color: #ff6970;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        @media (max-width: 700px) {
            .contact-item {
                padding: 18px;
            }

            .contact-top {
                flex-direction: column;
            }

            .page-header {
                padding: 25px 20px;
            }

            .page-header h1 {
                font-size: 24px;
            }
        }
    </style>


    <div class="contact-page">

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div class="alert success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert error">
                ! {{ session('error') }}
            </div>
        @endif


        {{-- HEADER --}}
        <div class="page-header">

            <div class="page-label">
                GYMFIT • CONTACT CENTER
            </div>

            <h1>
                Xử lý liên hệ
            </h1>

            <p>
                Tiếp nhận và xử lý các yêu cầu liên hệ từ khách hàng.
            </p>

        </div>


        {{-- DANH SÁCH LIÊN HỆ --}}
        <div class="contact-box">

            @if ($lienHe->count() > 0)

                @foreach ($lienHe as $lh)
                    <div class="contact-item">

                        {{-- THÔNG TIN KHÁCH --}}
                        <div class="contact-top">

                            <div>

                                <div class="contact-name">
                                    {{ $lh->ho_ten }}
                                </div>

                                <div class="contact-info">
                                    📧 {{ $lh->email }}

                                    &nbsp; • &nbsp;

                                    ☎ {{ $lh->so_dien_thoai }}
                                </div>

                            </div>


                            {{-- TRẠNG THÁI --}}
                            @php
                                $status = strtolower($lh->trang_thai ?? '');
                            @endphp


                            @if ($status == 'chưa xử lý')
                                <span class="status status-pending">
                                    Chưa xử lý
                                </span>
                            @elseif ($status == 'đang xử lý')
                                <span class="status status-processing">
                                    Đang xử lý
                                </span>
                            @elseif ($status == 'đã xử lý')
                                <span class="status status-done">
                                    Đã xử lý
                                </span>
                            @else
                                <span class="status status-other">
                                    {{ \App\Support\StatusLabel::format($lh->trang_thai) }}
                                </span>
                            @endif

                        </div>


                        {{-- NỘI DUNG LIÊN HỆ --}}
                        <div class="contact-content">

                            {{ $lh->noi_dung }}

                        </div>


                        {{-- PHẢN HỒI CŨ --}}
                        @if ($lh->phan_hoi)
                            <div class="contact-response">

                                <span class="response-label">
                                    PHẢN HỒI NHÂN VIÊN
                                </span>

                                {{ $lh->phan_hoi }}

                            </div>
                        @endif


                        {{-- THỜI GIAN --}}
                        <div class="contact-date">

                            Gửi lúc:
                            {{ $lh->tao_luc }}

                            @if ($lh->ten_nhan_vien)
                                &nbsp; • &nbsp;

                                Xử lý bởi:
                                {{ $lh->ten_nhan_vien }}
                            @endif

                        </div>


                        {{-- XỬ LÝ LIÊN HỆ --}}
                        <div class="process-box">

                            <div class="process-title">
                                XỬ LÝ YÊU CẦU
                            </div>

                            <form action="/admin/contacts/{{ $lh->lien_he_id }}/update" method="POST"
                                class="process-form">

                                @csrf


                                {{-- TRẠNG THÁI --}}
                                <div class="form-group">

                                    <label>
                                        TRẠNG THÁI
                                    </label>

                                    <select name="trang_thai" required>

                                        <option value="chưa xử lý"
                                            {{ in_array($lh->trang_thai, ['chưa xử lý', 'chua_xu_ly']) ? 'selected' : '' }}>
                                            Chưa xử lý
                                        </option>

                                        <option value="đang xử lý"
                                            {{ in_array($lh->trang_thai, ['đang xử lý', 'dang_xu_ly']) ? 'selected' : '' }}>
                                            Đang xử lý
                                        </option>

                                        <option value="đã xử lý"
                                            {{ in_array($lh->trang_thai, ['đã xử lý', 'da_xu_ly']) ? 'selected' : '' }}>
                                            Đã xử lý
                                        </option>

                                    </select>

                                </div>


                                {{-- PHẢN HỒI --}}
                                <div class="form-group">

                                    <label>
                                        PHẢN HỒI KHÁCH HÀNG
                                    </label>

                                    <textarea name="phan_hoi" placeholder="Nhập nội dung phản hồi cho khách hàng...">{{ $lh->phan_hoi }}</textarea>

                                </div>


                                {{-- LƯU --}}
                                <button type="submit" class="btn-save">
                                    ✓ LƯU XỬ LÝ
                                </button>

                            </form>

                        </div>

                    </div>
                @endforeach
            @else
                <div class="empty">

                    <div class="empty-icon">
                        📩
                    </div>

                    <h3>
                        Chưa có liên hệ
                    </h3>

                    <p>
                        Hiện chưa có yêu cầu liên hệ nào.
                    </p>

                </div>

            @endif

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
