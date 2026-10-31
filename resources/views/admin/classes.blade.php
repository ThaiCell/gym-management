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
        .class-page {
            color: #f5f5f5;
            padding: 24px;
        }

        .class-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .class-header h2 {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .class-header p {
            color: #aaa;
            margin: 0;
        }

        .class-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 76px;
            min-height: 38px;
            box-sizing: border-box;
            border: 1px solid transparent;
            border-radius: 7px;
            padding: 0 15px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 13px;
            line-height: 1.2;
            white-space: nowrap;
        }

        .class-btn-primary {
            background: #ed0715;
            color: white;
        }

        .class-btn-primary:hover {
            background: #c9000c;
            color: white;
        }

        .class-btn-edit {
            color: #f2f2f2;
            border: 1px solid #414149;
            background: #242428;
        }

        .class-btn-delete {
            color: #ff6871;
            border: 1px solid rgba(229, 9, 20, .48);
            background: rgba(229, 9, 20, .10);
        }

        .class-card {
            background: #171717;
            border: 1px solid #303030;
            border-radius: 12px;
            overflow: hidden;
        }

        .class-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        .class-table th {
            padding: 15px 14px;
            text-align: left;
            background: #222;
            color: #eee;
            font-size: 13px;
            white-space: nowrap;
        }

        .class-table td {
            padding: 14px;
            border-top: 1px solid #303030;
            vertical-align: middle;
            font-size: 14px;
        }

        .class-table tbody tr:hover {
            background: #202020;
        }

        .class-name {
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .class-desc {
            color: #aaa;
            max-width: 300px;
            line-height: 1.5;
        }

        .class-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .class-actions form {
            margin: 0;
        }

        .class-status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            background: #163d28;
            color: #83e6a8;
            white-space: nowrap;
            font-size: 12px;
        }

        .class-status-off {
            background: #3b2929;
            color: #ffaaaa;
        }

        .class-alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .class-alert-success {
            background: #163d28;
            color: #a7efbd;
        }

        .class-alert-error {
            background: #492020;
            color: #ffc0c0;
        }

        .class-empty {
            padding: 35px;
            text-align: center;
            color: #aaa;
        }

        @media(max-width: 700px) {
            .class-page {
                padding: 14px;
            }

            .class-header h2 {
                font-size: 22px;
            }
        }
    </style>

    <div class="class-page">
        <div class="class-header">
            <div>
                <h2>Quản lý lớp tập</h2>
                <p>Quản lý danh sách, lịch tập và số lượng hội viên.</p>
            </div>
            <a href="{{ url('/admin/classes/create') }}" class="class-btn class-btn-primary">
                + Thêm lớp tập
            </a>
        </div>

        @if (session('success'))
            <div class="class-alert class-alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="class-alert class-alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="class-alert class-alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="class-card" style="overflow-x:auto">
            <table class="class-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên lớp tập</th>
                        <th>Lịch tập</th>
                        <th>Sức chứa</th>
                        <th>Đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lopTap as $lop)
                        @php
                            $active = in_array(mb_strtolower($lop->trang_thai), [
                                'hoạt_động',
                                'hoat_dong',
                                'đang hoạt động',
                                'dang_hoat_dong',
                            ]);
                        @endphp
                        <tr>
                            <td>{{ $lop->lop_tap_id }}</td>
                            <td>
                                @if ($lop->anh && file_exists(public_path($lop->anh)))
                                    <img src="{{ asset($lop->anh) }}" alt="{{ $lop->ten_lop }}"
                                        style="width:80px;height:60px;object-fit:cover;border-radius:8px;">
                                @else
                                    <span style="color:#999;">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>
                                <div class="class-name">{{ $lop->ten_lop }}</div>
                                @if ($lop->mo_ta)
                                    <div class="class-desc">
                                        {{ \Illuminate\Support\Str::limit($lop->mo_ta, 90) }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $lop->lich_tap ?: 'Chưa cập nhật' }}</td>
                            <td>{{ $lop->suc_chua ?? 'Không giới hạn' }}</td>
                            <td>{{ $lop->so_dang_ky }}</td>
                            <td>
                                <span class="class-status {{ $active ? '' : 'class-status-off' }}">
                                    {{ $active ? 'Hoạt động' : \App\Support\StatusLabel::format($lop->trang_thai) }}
                                </span>
                            </td>
                            <td>
                                <div class="class-actions">
                                    <a href="{{ url('/admin/classes/' . $lop->lop_tap_id . '/edit') }}"
                                        class="class-btn class-btn-edit">Sửa</a>
                                    <form action="{{ url('/admin/classes/' . $lop->lop_tap_id . '/delete') }}" method="POST"
                                        data-confirm-submit="Xóa lớp tập này? Chỉ lớp chưa có đăng ký mới được xóa.">
                                        @csrf
                                        <button type="submit" class="class-btn class-btn-delete">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="class-empty">Chưa có lớp tập nào.</td>
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
