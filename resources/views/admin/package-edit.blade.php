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
        .package-edit-page {
            color: #f5f5f5;
            padding: 24px;
            max-width: 1000px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;

            gap: 12px;

            flex-wrap: wrap;
        }

        .page-header h2 {
            margin: 0;
            color: #fff;
            font-size: 25px;
        }

        .page-header p {
            margin: 6px 0 0;
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

        .form-card {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 25px;
            max-width: 1000px;
        }

        .form-card h3 {
            color: #fff;
            font-size: 18px;
            margin: 0 0 22px;
            padding-bottom: 15px;
            border-bottom: 1px solid #292929;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            color: #ddd;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .required {
            color: #ed1c24;
        }

        .form-control {
            width: 100%;
            box-sizing: border-box;
            background: #222;
            border: 1px solid #363636;
            color: #fff;
            padding: 11px 13px;
            border-radius: 7px;
            outline: none;
        }

        .form-control:focus {
            border-color: #ed1c24;
        }

        .form-note {
            color: #777;
            font-size: 12px;
            margin-top: 6px;
        }

        .error-box {
            background: rgba(237, 28, 36, 0.12);
            border: 1px solid #ed1c24;
            color: #ff6b70;
            border-radius: 7px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 20px;
        }

        .button-area {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #292929;
        }

        .cancel-btn {
            background: #252525;
            color: #fff;
            border: 1px solid #363636;
            padding: 11px 20px;
            border-radius: 7px;
            text-decoration: none;
        }

        .cancel-btn:hover {
            background: #333;
            color: #fff;
        }

        .save-btn {
            background: #ed1c24;
            color: #fff;
            border: none;
            padding: 11px 22px;
            border-radius: 7px;
            font-weight: 600;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #c9141b;
        }

        @media (max-width: 800px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>

    <div class="package-edit-page">

        <div class="page-header">

            <div>
                <h2>Sửa gói tập</h2>

                <p>
                    Chỉnh sửa thông tin gói #{{ $package->goi_tap_id }}
                </p>
            </div>



        </div>


        <div class="form-card">

            <h3>Thông tin gói tập</h3>


            @if ($errors->any())
                <div class="error-box">

                    <ul>

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>
            @endif


            <form method="POST" action="/admin/packages/{{ $package->goi_tap_id }}/update">

                @csrf

                <div class="form-grid">


                    {{-- Tên gói --}}

                    <div class="form-group full">

                        <label>
                            Tên gói tập <span class="required">*</span>
                        </label>

                        <input type="text" name="ten_goi" class="form-control"
                            value="{{ old('ten_goi', $package->ten_goi) }}" required>

                    </div>


                    {{-- Giá --}}

                    <div class="form-group">

                        <label>
                            Giá <span class="required">*</span>
                        </label>

                        <input type="number" name="gia" class="form-control" min="0" step="1000"
                            value="{{ old('gia', $package->gia) }}" required>

                        <div class="form-note">
                            Nhập giá bằng VNĐ.
                        </div>

                    </div>


                    {{-- Thời hạn --}}

                    <div class="form-group">

                        <label>
                            Thời hạn <span class="required">*</span>
                        </label>

                        <input type="number" name="thoi_han_ngay" class="form-control" min="1"
                            value="{{ old('thoi_han_ngay', $package->thoi_han_ngay) }}" required>

                        <div class="form-note">
                            Số ngày sử dụng gói.
                        </div>

                    </div>


                    {{-- Số buổi --}}

                    <div class="form-group">

                        <label>
                            Số buổi
                        </label>

                        <input type="number" name="so_buoi" class="form-control" min="1"
                            value="{{ old('so_buoi', $package->so_buoi) }}">

                        <div class="form-note">
                            Có thể để trống nếu không giới hạn.
                        </div>

                    </div>


                    {{-- Trạng thái --}}

                    <div class="form-group">

                        <label>
                            Trạng thái <span class="required">*</span>
                        </label>

                        <select name="trang_thai" class="form-control" required>

                            <option value="hoat_dong"
                                {{ old('trang_thai', $package->trang_thai) == 'hoat_dong' ? 'selected' : '' }}>
                                Hoạt động
                            </option>

                            <option value="tam_ngung"
                                {{ old('trang_thai', $package->trang_thai) == 'tam_ngung' ? 'selected' : '' }}>
                                Tạm ngưng
                            </option>

                        </select>

                    </div>


                </div>


                <div class="button-area">

                    <a href="/admin/packages/{{ $package->goi_tap_id }}" class="cancel-btn">
                        Hủy
                    </a>

                    <button type="submit" class="save-btn">
                        Lưu thay đổi
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
