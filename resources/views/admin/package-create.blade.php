@extends('layouts.app')

@section('content')

<style>
    .package-create-page {
        padding: 26px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
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

    .form-control::placeholder {
        color: #777;
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

<div class="package-create-page">

    <div class="page-header">

        <div>
            <h2>Thêm gói tập</h2>

            <p>
                Tạo một gói tập mới cho hệ thống Gym Management
            </p>
        </div>

        <a href="/admin/packages" class="back-btn">
            ← Quay lại danh sách
        </a>

    </div>


    <div class="form-card">

        <h3>Thông tin gói tập</h3>


        @if($errors->any())

            <div class="error-box">

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form method="POST" action="/admin/packages">

            @csrf

            <div class="form-grid">


                {{-- Tên gói --}}

                <div class="form-group full">

                    <label>
                        Tên gói tập <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="ten_goi"
                        class="form-control"
                        placeholder="Ví dụ: Gói Premium 3 Tháng"
                        value="{{ old('ten_goi') }}"
                        required
                    >

                </div>


                {{-- Giá --}}

                <div class="form-group">

                    <label>
                        Giá <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="gia"
                        class="form-control"
                        placeholder="Ví dụ: 800000"
                        min="0"
                        step="1000"
                        value="{{ old('gia') }}"
                        required
                    >

                    <div class="form-note">
                        Nhập giá bằng VNĐ.
                    </div>

                </div>


                {{-- Thời hạn --}}

                <div class="form-group">

                    <label>
                        Thời hạn <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="thoi_han_ngay"
                        class="form-control"
                        placeholder="Ví dụ: 90"
                        min="1"
                        value="{{ old('thoi_han_ngay') }}"
                        required
                    >

                    <div class="form-note">
                        Số ngày sử dụng gói.
                    </div>

                </div>


                {{-- Số buổi --}}

                <div class="form-group">

                    <label>
                        Số buổi
                    </label>

                    <input
                        type="number"
                        name="so_buoi"
                        class="form-control"
                        placeholder="Ví dụ: 90"
                        min="1"
                        value="{{ old('so_buoi') }}"
                    >

                    <div class="form-note">
                        Có thể để trống nếu không giới hạn số buổi.
                    </div>

                </div>


                {{-- Trạng thái --}}

                <div class="form-group">

                    <label>
                        Trạng thái <span class="required">*</span>
                    </label>

                    <select
                        name="trang_thai"
                        class="form-control"
                        required
                    >

                        <option
                            value="hoat_dong"
                            {{ old('trang_thai', 'hoat_dong') == 'hoat_dong' ? 'selected' : '' }}
                        >
                            Hoạt động
                        </option>

                        <option
                            value="tam_ngung"
                            {{ old('trang_thai') == 'tam_ngung' ? 'selected' : '' }}
                        >
                            Tạm ngưng
                        </option>

                    </select>

                </div>


            </div>


            <div class="button-area">

                <a
                    href="/admin/packages"
                    class="cancel-btn"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Lưu gói tập
                </button>

            </div>

        </form>

    </div>

</div>

@endsection