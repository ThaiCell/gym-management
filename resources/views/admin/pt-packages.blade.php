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
        .pt-page {
            padding: 26px
        }

        .pt-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px
        }

        .pt-head h2 {
            margin: 0;
            font-size: 25px
        }

        .pt-head p {
            color: #888;
            margin-top: 7px
        }

        .pt-card {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            overflow: hidden
        }

        .pt-toolbar {
            padding: 16px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #292929
        }

        .pt-search {
            display: flex;
            gap: 8px;
            max-width: 500px;
            width: 100%
        }

        .pt-search input {
            background: #222;
            border: 1px solid #393939;
            color: #fff;
            padding: 11px;
            border-radius: 7px;
            flex: 1;
            min-width: 0
        }

        .pt-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 76px;
            min-height: 38px;
            height: 38px;
            box-sizing: border-box;
            background: #ed1c24;
            color: #fff;
            border: 0;
            border-radius: 7px;
            padding: 10px 18px;
            text-decoration: none;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            transition: .18s ease;
        }

        .pt-btn:hover {
            background: #bd0710;
            color: white
        }

        .pt-danger {
            background: rgba(229, 9, 20, .10);
            color: #ff6871;
            border: 1px solid rgba(229, 9, 20, .48);
        }

        .pt-btn:hover {
            background: #c9141b;
            color: #fff;
            transform: translateY(-1px);
        }

        .pt-btn:focus-visible {
            outline: 2px solid #ff6871;
            outline-offset: 2px;
        }

        .pt-actions .pt-btn {
            min-width: 76px;
            min-height: 38px;
            height: 38px;
            padding: 0 15px;
        }

        .pt-search .pt-btn {
            min-width: 92px;
            background: #242428;
            border: 1px solid #414149;
            color: #fff;
        }

        .pt-table {
            width: 100%;
            border-collapse: collapse
        }

        .pt-table th {
            background: #101010;
            color: #aaa;
            text-align: left;
            padding: 13px;
            font-size: 12px
        }

        .pt-table td {
            padding: 13px;
            border-top: 1px solid #292929;
            color: #ddd
        }

        .pt-status {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px
        }

        .pt-active {
            background: #10351e;
            color: #8af0ad
        }

        .pt-off {
            background: #3b2d0a;
            color: #ffdc80
        }

        .pt-actions {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap
        }

        .pt-actions form {
            margin: 0
        }

        .pt-alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px
        }

        .pt-ok {
            background: #092e18;
            color: #8af0ad
        }

        .pt-err {
            background: #351013;
            color: #ffb7bc
        }

        @media(max-width:850px) {
            .pt-card {
                overflow-x: auto
            }

            .pt-table {
                min-width: 900px
            }

            .pt-head,
            .pt-toolbar {
                flex-direction: column;
                align-items: stretch
            }
        }
    </style>

    <div class="pt-page">
        <div class="pt-head">
            <div>
                <h2>Quản lý gói PT</h2>
                <p>Thêm, sửa và quản lý các gói huấn luyện cá nhân</p>
            </div>
            <a class="pt-btn" href="/admin/pt-packages/create">+ Thêm gói PT</a>
        </div>

        @if (session('success'))
            <div class="pt-alert pt-ok">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="pt-alert pt-err">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="pt-alert pt-err">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="pt-card">
            <div class="pt-toolbar">
                <form class="pt-search" method="GET" action="/admin/pt-packages">
                    <input name="q" value="{{ $keyword }}" placeholder="Tìm tên gói PT">
                    <button class="pt-btn" type="submit">Tìm</button>
                </form>
                <span>Tổng: {{ $packages->count() }} gói</span>
            </div>

            <table class="pt-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ẢNH</th>
                        <th>TÊN GÓI</th>
                        <th>GIÁ</th>
                        <th>SỐ BUỔI</th>
                        <th>THỜI HẠN</th>
                        <th>TRẠNG THÁI</th>
                        <th>THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>#{{ $package->goi_pt_id }}</td>
                            <td>
                                @if (!empty($package->anh))
                                    <img src="{{ asset($package->anh) }}" alt="Ảnh {{ $package->ten_goi_pt }}"
                                        style="width:64px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #333">
                                @else
                                    <span style="color:#777">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td><strong>{{ $package->ten_goi_pt }}</strong></td>
                            <td>{{ number_format($package->gia, 0, ',', '.') }} đ</td>
                            <td>{{ $package->so_buoi }} buổi</td>
                            <td>{{ $package->thoi_han_ngay }} ngày</td>
                            <td>
                                @if (in_array(mb_strtolower(trim((string) $package->trang_thai), 'UTF-8'),
                                        ['hoat_dong', 'hoạt_động', 'hoạt động', 'dang_hoat_dong', 'đang hoạt động'],
                                        true))
                                    <span
                                        class="pt-status pt-active">{{ \App\Support\StatusLabel::format($package->trang_thai) }}</span>
                                @else
                                    <span
                                        class="pt-status pt-off">{{ \App\Support\StatusLabel::format($package->trang_thai) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="pt-actions">
                                    <a class="pt-btn" href="/admin/pt-packages/{{ $package->goi_pt_id }}/edit">Sửa</a>

                                    <form method="POST" action="/admin/pt-packages/{{ $package->goi_pt_id }}/delete"
                                        data-confirm-submit="Chỉ xóa được khi gói chưa có đăng ký hoặc hóa đơn. Tiếp tục?">
                                        @csrf
                                        <button class="pt-btn pt-danger" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:35px;color:#888">
                                Chưa có gói PT nào.
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



/* Final sizing: match the compact controls on Admin > Gói tập */
.pt-toolbar{display:flex!important;justify-content:space-between!important;align-items:center!important;gap:14px!important;flex-wrap:wrap!important;padding:18px 20px!important;}
.pt-search{display:flex!important;justify-content:flex-start!important;align-items:center!important;gap:10px!important;width:430px!important;max-width:100%!important;margin:0!important;flex:0 1 430px!important;}
.pt-search input{height:40px!important;min-height:40px!important;padding:0 13px!important;box-sizing:border-box!important;flex:1 1 auto!important;min-width:0!important;}
.pt-search .pt-btn{width:auto!important;min-width:92px!important;max-width:110px!important;height:38px!important;min-height:38px!important;padding:0 18px!important;font-size:13px!important;flex:0 0 auto!important;}
.pt-count{margin-left:auto!important;white-space:nowrap!important;}
.pt-actions{display:flex!important;align-items:center!important;justify-content:flex-start!important;gap:8px!important;flex-wrap:nowrap!important;}
.pt-actions .pt-btn{width:auto!important;min-width:58px!important;height:34px!important;min-height:34px!important;padding:0 12px!important;font-size:12px!important;border-radius:6px!important;flex:0 0 auto!important;}
@media(max-width:700px){.pt-toolbar{align-items:flex-start!important}.pt-search{width:100%!important;flex:1 1 100%!important}.pt-count{margin-left:0!important}.pt-actions{flex-wrap:wrap!important}}
</style>
@endsection
