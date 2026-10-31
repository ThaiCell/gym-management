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
        .packages-page {
            padding: 26px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .page-title h2 {
            margin: 0;
            color: #fff;
            font-size: 25px;
        }

        .page-title p {
            margin: 6px 0 0;
            color: #888;
            font-size: 14px;
        }

        .package-card {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            overflow: hidden;
        }

        .package-toolbar {
            padding: 18px 20px;
            border-bottom: 1px solid #292929;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            width: 430px;
            max-width: 100%;
        }

        .search-form input {
            flex: 1;
            background: #222;
            border: 1px solid #333;
            color: #fff;
            border-radius: 7px;
            padding: 10px 13px;
            outline: none;
        }

        .search-form input:focus {
            border-color: #ed1c24;
        }

        .search-btn {
            background: #ed1c24;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 10px 18px;
            cursor: pointer;
        }

        .search-btn:hover {
            background: #c9141b;
        }

        .package-count {
            color: #999;
            font-size: 14px;
        }

        .package-table {
            width: 100%;
            border-collapse: collapse;
        }

        .package-table th {
            background: #111;
            color: #aaa;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            padding: 15px 18px;
            border-bottom: 1px solid #292929;
        }

        .package-table td {
            padding: 15px 18px;
            color: #ddd;
            font-size: 14px;
            border-bottom: 1px solid #252525;
        }

        .package-table tbody tr:hover {
            background: #1d1d1d;
        }

        .package-id {
            color: #ed1c24;
            font-weight: 600;
        }

        .package-name {
            color: #fff;
            font-weight: 600;
        }

        .package-price {
            color: #fff;
            font-weight: 600;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-active {
            background: rgba(0, 200, 83, 0.12);
            color: #00c853;
        }

        .status-inactive {
            background: rgba(255, 193, 7, 0.12);
            color: #ffc107;
        }

        .empty-row {
            text-align: center !important;
            padding: 45px !important;
            color: #777 !important;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .edit-btn,
        .delete-btn {
            display: inline-block;
            border: 0;
            border-radius: 6px;
            padding: 7px 11px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .edit-btn {
            background: #333;
            color: #fff;
            padding: 15px;
        }

        .edit-btn:hover {
            background: #444;
            color: #fff;
        }

        .delete-btn {
            background: #ed1c24;
            color: #fff;
            padding: 15px;
        }

        .delete-btn:hover {
            background: #c9141b;
        }

        .alert-success,
        .alert-error {
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 7px;
        }

        .alert-success {
            background: rgba(0, 200, 83, .12);
            color: #00c853;
            border: 1px solid rgba(0, 200, 83, .3);
        }

        .alert-error {
            background: rgba(237, 28, 36, .12);
            color: #ff6b72;
            border: 1px solid rgba(237, 28, 36, .3);
        }

        @media (max-width: 900px) {
            .package-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-form {
                width: 100%;
            }

            .package-card {
                overflow-x: auto;
            }

            .package-table {
                min-width: 850px;
            }
        }

        .package-link {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }

        .package-link:hover {
            color: #ed1c24;
        }

        .add-package-btn {
            display: inline-block;
            background: #ed1c24;
            color: #fff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 7px;
            font-weight: 600;
        }

        .add-package-btn:hover {
            background: #c9141b;
            color: #fff;
        }
    </style>

    <div class="packages-page">

        <div class="page-header">

            <div class="page-title">

                <h2>Quản lý gói tập</h2>

                <p>Danh sách các gói tập trong hệ thống Gym Management</p>

            </div>
            <a href="/admin/packages/create" class="add-package-btn">
                + Thêm gói tập
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="package-card">

            <div class="package-toolbar">

                <form action="/admin/packages" method="GET" class="search-form">

                    <input type="text" name="q" value="{{ $keyword }}" placeholder="Tìm theo tên gói tập...">

                    <button type="submit" class="search-btn">
                        Tìm kiếm
                    </button>

                </form>

                <div class="package-count">
                    Tổng:
                    <strong>{{ $packages->count() }}</strong>
                    gói tập
                </div>

            </div>

            <table class="package-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TÊN GÓI</th>
                        <th>GIÁ</th>
                        <th>THỜI HẠN</th>
                        <th>SỐ BUỔI</th>
                        <th>TRẠNG THÁI</th>
                        <th>THAO TÁC</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($packages as $package)
                        <tr>

                            <td>
                                <span class="package-id">
                                    #{{ $package->goi_tap_id }}
                                </span>
                            </td>

                            <td>
                                <a href="/admin/packages/{{ $package->goi_tap_id }}" class="package-link">
                                    {{ $package->ten_goi }}
                                </a>
                            </td>

                            <td>
                                <span class="package-price">
                                    {{ number_format($package->gia, 0, ',', '.') }} đ
                                </span>
                            </td>

                            <td>
                                {{ $package->thoi_han_ngay }} ngày
                            </td>

                            <td>
                                @if ($package->so_buoi !== null)
                                    {{ $package->so_buoi }} buổi
                                @else
                                    Không giới hạn
                                @endif
                            </td>

                            <td>

                                @if (in_array($package->trang_thai, ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong']))
                                    <span class="status status-active">
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="status status-inactive">
                                        {{ \App\Support\StatusLabel::format($package->trang_thai) }}
                                    </span>
                                @endif

                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/packages/{{ $package->goi_tap_id }}/edit" class="edit-btn">
                                        Sửa
                                    </a>

                                    <form action="/admin/packages/{{ $package->goi_tap_id }}/delete" method="POST"
                                        data-confirm-submit="Mày có chắc muốn xóa gói tập này không? Chỉ xóa được khi gói chưa có đăng ký hoặc hóa đơn liên quan.">
                                        @csrf
                                        <button type="submit" class="delete-btn">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="empty-row">
                                Không tìm thấy gói tập nào.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
