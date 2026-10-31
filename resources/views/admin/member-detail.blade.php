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
        .member-detail-page {
            padding: 26px;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .detail-title h2 {
            margin: 0;
            color: #fff;
            font-size: 25px;
        }

        .detail-title p {
            margin-top: 6px;
            color: #888;
            font-size: 14px;
        }

        .back-btn {
            text-decoration: none;
            background: #252525;
            color: #fff;
            padding: 10px 16px;
            border-radius: 7px;
            border: 1px solid #333;
        }

        .back-btn:hover {
            background: #333;
            color: #fff;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-card {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 22px;
        }

        .detail-card h3 {
            margin: 0 0 20px;
            color: #fff;
            font-size: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #292929;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 13px 0;
            border-bottom: 1px solid #242424;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #888;
        }

        .info-value {
            color: #fff;
            text-align: right;
            font-weight: 500;
        }

        .status {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-active {
            background: rgba(0, 200, 83, 0.12);
            color: #00c853;
        }

        .status-other {
            background: rgba(255, 193, 7, 0.12);
            color: #ffc107;
        }

        .package-card {
            margin-top: 20px;
        }

        .package-table {
            width: 100%;
            border-collapse: collapse;
        }

        .package-table th {
            text-align: left;
            color: #888;
            font-size: 12px;
            padding: 12px 8px;
            border-bottom: 1px solid #292929;
        }

        .package-table td {
            color: #ddd;
            font-size: 13px;
            padding: 14px 8px;
            border-bottom: 1px solid #242424;
        }

        .package-name {
            color: #fff;
            font-weight: 600;
        }

        .empty-package {
            color: #777;
            text-align: center;
            padding: 25px !important;
        }

        @media (max-width: 900px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-header {
                gap: 15px;
                align-items: flex-start;
                flex-direction: column;
            }
        }
        
        /* Đồng bộ kích thước các nút trên trang chi tiết hội viên */
        .detail-header > div:last-child {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .detail-header > div:last-child > a.back-btn,
        .detail-header > div:last-child > form {
            width: 140px;
            min-width: 140px;
            height: 40px;
            box-sizing: border-box;
        }

        .detail-header > div:last-child > a.back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            white-space: nowrap;
            font-size: 13px;
        }

        .detail-header > div:last-child > form button.back-btn {
            width: 100%;
            height: 40px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            white-space: nowrap;
            font-size: 13px;
        }
    </style>

    <div class="member-detail-page">


        @if (session('success'))
            <div
                style="
                background: rgba(0, 200, 83, 0.12);
                border: 1px solid rgba(0, 200, 83, 0.35);
                color: #00c853;
                padding: 12px 15px;
                border-radius: 7px;
                margin-bottom: 18px;
            ">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="background:#411b1b;color:#ffb5b5;padding:12px 15px;
                border-radius:7px;margin-bottom:18px;">
                {{ session('error') }}
            </div>
        @endif
        <div class="detail-header">

            <div class="detail-title">
                <h2>Chi tiết hội viên</h2>
                <p>Thông tin chi tiết của hội viên #{{ $member->hoi_vien_id }}</p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">

                <a href="/admin/members/{{ $member->hoi_vien_id }}/edit" class="back-btn"
                    style="background: #ed1c24; border-color: #ed1c24;">
                    ✎ Sửa thông tin
                </a>

                <form action="/admin/members/{{ $member->hoi_vien_id }}/toggle-status" method="POST" style="margin: 0;"
                    data-confirm-submit="{{ $member->trang_thai === 'hoat_dong'
                        ? 'Bạn có chắc muốn KHÓA tài khoản hội viên này?'
                        : 'Bạn có chắc muốn MỞ KHÓA tài khoản hội viên này?' }}">

                    @csrf

                    @if ($member->trang_thai === 'hoat_dong')
                        <button type="submit" class="back-btn"
                            style="
                            background: #5a1a1a;
                            border-color: #7a2222;
                            cursor: pointer;
                        ">
                            🔒 Khóa tài khoản
                        </button>
                    @else
                        <button type="submit" class="back-btn"
                            style="
                            background: #064e3b;
                            border-color: #087f5b;
                            cursor: pointer;
                        ">
                            🔓 Mở khóa tài khoản
                        </button>
                    @endif

                </form>
                <form action="/admin/members/{{ $member->hoi_vien_id }}/delete"
                method="POST"
                style="margin:0;"
                data-confirm-submit="Bạn chắc chắn muốn xóa hội viên này? Thao tác này không thể hoàn tác.">
                @csrf
                <button type="submit" class="back-btn"
                    style="background:#7f1d1d;border-color:#991b1b;cursor:pointer;">
                    🗑 Xóa hội viên
                </button>
            </form>
                <a href="/admin/members" class="back-btn">
                    ← Quay lại danh sách
                </a>

            </div>

        </div>

        <div class="detail-grid">

            {{-- THÔNG TIN CÁ NHÂN --}}
            <div class="detail-card">

                <h3>Thông tin cá nhân</h3>

                <div class="info-row">
                    <span class="info-label">Mã hội viên</span>
                    <span class="info-value">
                        #{{ $member->hoi_vien_id }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Họ và tên</span>
                    <span class="info-value">
                        {{ $member->ho_ten }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        {{ $member->email }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Số điện thoại</span>
                    <span class="info-value">
                        {{ $member->so_dien_thoai ?? 'Chưa cập nhật' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Ngày sinh</span>
                    <span class="info-value">
                        {{ $member->ngay_sinh ?? 'Chưa cập nhật' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Địa chỉ</span>
                    <span class="info-value">
                        {{ $member->dia_chi ?? 'Chưa cập nhật' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Ngày tham gia</span>
                    <span class="info-value">
                        {{ $member->ngay_tham_gia ?? 'Chưa cập nhật' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Trạng thái</span>

                    <span class="info-value">

                        @if ($member->trang_thai === 'hoat_dong')
                            <span class="status status-active">
                                Đang hoạt động
                            </span>
                        @else
                            <span class="status status-other">
                                {{ \App\Support\StatusLabel::format($member->trang_thai) }}
                            </span>
                        @endif

                    </span>
                </div>

            </div>

            {{-- THÔNG TIN TÀI KHOẢN --}}
            <div class="detail-card">

                <h3>Thông tin tài khoản</h3>

                <div class="info-row">
                    <span class="info-label">ID người dùng</span>
                    <span class="info-value">
                        #{{ $member->nguoi_dung_id }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Email đăng nhập</span>
                    <span class="info-value">
                        {{ $member->email }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Trạng thái tài khoản</span>

                    <span class="info-value">

                        @if ($member->trang_thai === 'hoat_dong')
                            <span class="status status-active">
                                Đang hoạt động
                            </span>
                        @else
                            <span class="status status-other">
                                {{ \App\Support\StatusLabel::format($member->trang_thai) }}
                            </span>
                        @endif

                    </span>
                </div>

            </div>

        </div>

        {{-- GÓI TẬP --}}
        <div class="detail-card package-card">

            <h3>Gói tập của hội viên</h3>

            <table class="package-table">

                <thead>
                    <tr>
                        <th>TÊN GÓI</th>
                        <th>GIÁ</th>
                        <th>BẮT ĐẦU</th>
                        <th>KẾT THÚC</th>
                        <th>BUỔI CÒN LẠI</th>
                        <th>TRẠNG THÁI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($packages as $package)
                        <tr>

                            <td class="package-name">
                                {{ $package->ten_goi }}
                            </td>

                            <td>
                                {{ number_format($package->gia, 0, ',', '.') }} đ
                            </td>

                            <td>
                                {{ $package->ngay_bat_dau }}
                            </td>

                            <td>
                                {{ $package->ngay_ket_thuc }}
                            </td>

                            <td>
                                {{ $package->so_buoi_con_lai ?? 'Không giới hạn' }}
                            </td>

                            <td>
                                {{ \App\Support\StatusLabel::format($package->trang_thai) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-package">
                                Hội viên này chưa đăng ký gói tập.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

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
