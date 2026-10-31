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
        .pr-wrap {
            color: #f4f4f5;
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0 16px;
            box-sizing: border-box;
        }

        .pr-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 26px 28px;
            margin-bottom: 22px;
            border: 1px solid #3a2023;
            border-radius: 18px;
            background: radial-gradient(circle at 90% 15%, rgba(229, 9, 20, .20), transparent 32%), linear-gradient(120deg, #1c1c1f, #120d0e)
        }

        .pr-heading {
            display: flex;
            align-items: center;
            gap: 15px
        }

        .pr-icon {
            display: grid;
            place-items: center;
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            border-radius: 15px;
            background: linear-gradient(145deg, #ff2633, #b9000b);
            font-size: 25px;
            box-shadow: 0 8px 24px rgba(229, 9, 20, .2)
        }

        .pr-title {
            font-size: clamp(23px, 2.3vw, 30px);
            font-weight: 800;
            margin: 0 0 6px;
            letter-spacing: -.5px
        }

        .pr-subtitle {
            color: #a1a1aa;
            margin: 0;
            font-size: 14px;
            line-height: 1.6
        }

        .pr-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid #3a3031;
            border-radius: 999px;
            background: #171315;
            color: #e4e4e7;
            font-size: 12px;
            white-space: nowrap
        }

        .pr-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444
        }

        .pr-alert {
            padding: 15px 17px;
            border-radius: 12px;
            margin-bottom: 18px;
            line-height: 1.65;
            font-size: 14px
        }

        .pr-success {
            background: linear-gradient(120deg, #10251a, #142019);
            border: 1px solid #286342;
            color: #c4f5d4
        }

        .pr-error {
            background: #2b1115;
            border: 1px solid #7f2932;
            color: #fecdd3
        }

        .pr-temp {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px
        }

        .pr-temp code {
            display: inline-block;
            background: #080808;
            color: #fff;
            padding: 11px 15px;
            border: 1px dashed #4b6854;
            border-radius: 8px;
            font-size: 17px;
            letter-spacing: 1.5px
        }

        .pr-copy {
            background: #27272a;
            color: #fff;
            border: 1px solid #52525b;
            border-radius: 8px;
            padding: 10px 13px;
            cursor: pointer;
            font-weight: 700
        }

        .pr-copy:hover {
            background: #3f3f46
        }

        .pr-panel {
            width: 100%;
            max-width: none;
            box-sizing: border-box;
            background: linear-gradient(145deg, #19191b, #151516);
            border: 1px solid #2d2d30;
            border-radius: 12px;
            padding: 22px;
        }

        .pr-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px
        }

        .pr-panel-title {
            font-size: 17px;
            font-weight: 750;
            margin: 0
        }

        .pr-panel-note {
            font-size: 12px;
            color: #8e8e98;
            margin-top: 5px
        }

        .pr-search {
            display: flex;
            gap: 10px;
            margin-bottom: 18px
        }

        .pr-search input {
            flex: 1;
            min-width: 0;
            background: #101012;
            border: 1px solid #36363b;
            color: #f4f4f5;
            border-radius: 10px;
            padding: 12px 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s
        }

        .pr-search input:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, .12)
        }

        .pr-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            border: 1px solid transparent;
            border-radius: 9px;
            padding: 11px 15px;
            background: linear-gradient(135deg, #f31320, #c9000b);
            color: #fff;
            font-weight: 750;
            cursor: pointer;
            transition: transform .15s, filter .15s;
            text-decoration: none;
            white-space: nowrap
        }

        .pr-btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px)
        }

        .pr-btn:active {
            transform: translateY(0)
        }

        .pr-btn-reset {
            padding: 9px 12px;
            font-size: 12px
        }

        .pr-table-wrap {
            overflow-x: auto;
            border: 1px solid #2c2c30;
            border-radius: 12px
        }

        .pr-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 780px
        }

        .pr-table th {
            padding: 14px 15px;
            text-align: left;
            background: #202023;
            color: #a1a1aa;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            white-space: nowrap
        }

        .pr-table td {
            padding: 15px;
            text-align: left;
            border-top: 1px solid #29292d;
            color: #e4e4e7;
            font-size: 13px;
            vertical-align: middle
        }

        .pr-table tbody tr {
            background: #18181a;
            transition: background .15s
        }

        .pr-table tbody tr:hover {
            background: #202023
        }

        .pr-table th:first-child {
            border-radius: 11px 0 0 0
        }

        .pr-table th:last-child {
            border-radius: 0 11px 0 0
        }

        .pr-id {
            color: #a1a1aa;
            font-variant-numeric: tabular-nums
        }

        .pr-name {
            font-weight: 700;
            color: #fafafa
        }

        .pr-email {
            color: #b5b5bd
        }

        .pr-role {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 5px 9px;
            background: #292023;
            border: 1px solid #4b252b;
            border-radius: 7px;
            color: #ffb6bc;
            font-weight: 750;
            font-size: 12px
        }

        .pr-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 10px;
            border: 1px solid #343439;
            border-radius: 999px;
            background: #222225;
            color: #d4d4d8;
            font-size: 12px;
            white-space: nowrap
        }

        .pr-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #71717a
        }

        .pr-status.is-active {
            background: #10251a;
            border-color: #24583a;
            color: #bbf7d0
        }

        .pr-status.is-active .pr-status-dot {
            background: #4ade80
        }

        .pr-status.is-inactive {
            background: #2a1a12;
            border-color: #684025;
            color: #fed7aa
        }

        .pr-status.is-inactive .pr-status-dot {
            background: #fb923c
        }

        .pr-empty {
            padding: 34px !important;
            text-align: center !important;
            color: #a1a1aa !important
        }

        .pr-empty-icon {
            display: block;
            font-size: 28px;
            margin-bottom: 8px
        }

        .pr-pagination {
            margin-top: 20px;
            overflow-x: auto
        }

        .pr-pagination nav {
            display: flex;
            justify-content: flex-end
        }

        .pr-pagination svg {
            width: 18px;
            height: 18px
        }

        .pr-pagination span,
        .pr-pagination a {
            color: #d4d4d8 !important
        }

        @media(max-width:700px) {
            .pr-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 21px
            }

            .pr-chip {
                white-space: normal
            }

            .pr-panel {
                padding: 14px
            }

            .pr-search {
                flex-direction: column
            }

            .pr-search .pr-btn {
                width: 100%
            }

            .pr-panel-head {
                align-items: flex-start;
                flex-direction: column
            }
        }
    </style>

    <div class="pr-wrap">
        <section class="pr-hero">
            <div class="pr-heading">
                <div class="pr-icon">🔐</div>
                <div>
                    <h1 class="pr-title">Cấp lại mật khẩu</h1>
                    <p class="pr-subtitle">Quản lý tài khoản và cấp mật khẩu tạm thời cho người dùng sau khi xác minh danh
                        tính.</p>
                </div>
            </div>
            <div class="pr-chip"><span class="pr-dot"></span> Khu vực quản trị viên</div>
        </section>

        @if (session('success'))
            <div class="pr-alert pr-success">✓ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="pr-alert pr-error">⚠ {{ session('error') }}</div>
        @endif
        @if (session('temporary_password'))
            <div class="pr-alert pr-success">
                <strong>🔑 Mật khẩu tạm thời — chỉ hiển thị lần này</strong>
                <div style="margin-top:5px">Tài khoản: <strong>{{ session('temporary_password_user') }}</strong></div>
                <div class="pr-temp">
                    <code id="temporaryPassword">{{ session('temporary_password') }}</code>
                    <button type="button" class="pr-copy"
                        onclick="navigator.clipboard.writeText(document.getElementById('temporaryPassword').innerText).then(()=>this.innerText='✓ Đã sao chép').catch(()=>alert('Không sao chép được, hãy chọn và sao chép thủ công.'))">Sao
                        chép mật khẩu</button>
                </div>
                <p style="margin:10px 0 0">Gửi mật khẩu riêng cho chủ tài khoản sau khi xác minh. Khuyến nghị người dùng đổi
                    mật khẩu sau khi đăng nhập.</p>
            </div>
        @endif

        <section class="pr-panel">
            <div class="pr-panel-head">
                <div>
                    <h2 class="pr-panel-title">Danh sách tài khoản</h2>
                    <div class="pr-panel-note">Tìm theo họ tên hoặc email để thao tác nhanh chóng.</div>
                </div>
            </div>

            <form method="GET" action="{{ url('/admin/password-resets') }}" class="pr-search">
                <input type="text" name="q" value="{{ $keyword }}"
                    placeholder="⌕  Nhập họ tên hoặc email cần tìm..." aria-label="Tìm theo họ tên hoặc email">
                <button class="pr-btn" type="submit">⌕ Tìm kiếm</button>
                @if (!empty($keyword))
                    <a class="pr-btn" href="{{ url('/admin/password-resets') }}"
                        style="background:#303036;border-color:#45454c">Xóa lọc</a>
                @endif
            </form>

            <div class="pr-table-wrap">
                <table class="pr-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thông tin người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $statusRaw = mb_strtolower(trim((string) $user->trang_thai));
                                $isActive = in_array(
                                    $statusRaw,
                                    [
                                        'hoat_dong',
                                        'hoạt_động',
                                        'hoạt động',
                                        'dang_hoat_dong',
                                        'đang hoạt động',
                                        'active',
                                        '1',
                                    ],
                                    true,
                                );
                                $statusLabel = $isActive
                                    ? 'Hoạt động'
                                    : (in_array(
                                        $statusRaw,
                                        ['ngung_hoat_dong', 'ngừng hoạt động', 'khóa', 'khoa', 'inactive', '0'],
                                        true,
                                    )
                                        ? 'Ngừng hoạt động'
                                        : str_replace('_', ' ', (string) $user->trang_thai));
                            @endphp
                            <tr>
                                <td class="pr-id">#{{ $user->nguoi_dung_id }}</td>
                                <td>
                                    <div class="pr-name">{{ $user->ho_ten }}</div>
                                </td>
                                <td class="pr-email">{{ $user->email }}</td>
                                <td><span class="pr-role">{{ $user->vai_tro_id }}</span></td>
                                <td>
                                    <span
                                        class="pr-status {{ $isActive ? 'is-active' : (in_array($statusRaw, ['ngung_hoat_dong', 'ngừng hoạt động', 'khóa', 'khoa', 'inactive', '0'], true) ? 'is-inactive' : '') }}">
                                        <span class="pr-status-dot"></span>{{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('admin.password-resets.reset', $user->nguoi_dung_id) }}"
                                        data-confirm-submit="Chỉ tiếp tục nếu đã xác minh danh tính chủ tài khoản. Cấp mật khẩu tạm thời cho tài khoản này?"
                                        data-confirm-title="Xác nhận cấp lại mật khẩu" data-confirm-text="Cấp lại mật khẩu">
                                        @csrf

                                        <button type="submit" class="pr-btn pr-btn-reset">
                                            🔑 Cấp lại
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="pr-empty"><span class="pr-empty-icon">⌕</span>Không tìm thấy tài
                                    khoản phù hợp.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pr-pagination">{{ $users->appends(request()->query())->links() }}</div>
        </section>
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
