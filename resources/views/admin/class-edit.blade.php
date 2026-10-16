@extends('layouts.admin')

@section('content')
<style>
    .class-form-page {
        color: #f5f5f5;
        padding: 24px;
        max-width: 1100px;
    }

    .class-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .class-form-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
    }

    .class-form-card {
        background: #171717;
        border: 1px solid #303030;
        border-radius: 12px;
        padding: 24px;
    }

    .class-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .class-field {
        margin-bottom: 20px;
        min-width: 0;
    }

    .class-field-full {
        grid-column: 1 / -1;
    }

    .class-field label {
        display: block;
        margin-bottom: 8px;
        color: #eee;
        font-size: 14px;
        font-weight: 600;
    }

    .class-field input,
    .class-field textarea,
    .class-field select {
        display: block;
        width: 100%;
        box-sizing: border-box;
        background: #222;
        border: 1px solid #444;
        color: #fff;
        border-radius: 7px;
        padding: 11px 12px;
        font-size: 14px;
    }

    .class-field input:focus,
    .class-field textarea:focus,
    .class-field select:focus {
        outline: none;
        border-color: #ed0715;
        box-shadow: 0 0 0 2px rgba(237, 7, 21, .15);
    }

    .class-field input[type="file"] {
        padding: 9px;
    }

    .class-current-image {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 12px;
        padding: 6px;
        background: #222;
        border: 1px solid #383838;
        border-radius: 10px;
    }

    .class-current-image img {
        display: block;
        width: 220px;
        height: 145px;
        max-width: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .class-help {
        display: block;
        color: #999;
        margin-top: 7px;
        font-size: 12px;
    }

    .class-form-btn {
        display: inline-block;
        border: 0;
        border-radius: 7px;
        padding: 11px 17px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
    }

    .class-form-primary {
        background: #ed0715;
        color: #fff;
    }

    .class-form-primary:hover {
        background: #c9000c;
        color: #fff;
    }

    .class-form-secondary {
        background: #292929;
        color: #fff;
        border: 1px solid #444;
    }

    .class-form-secondary:hover {
        background: #383838;
        color: #fff;
    }

    .class-form-alert {
        background: #492020;
        color: #ffc0c0;
        padding: 12px;
        border-radius: 7px;
        margin-bottom: 18px;
    }

    @media (max-width: 700px) {
        .class-form-page {
            padding: 14px;
        }

        .class-form-card {
            padding: 16px;
        }

        .class-form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .class-field-full {
            grid-column: auto;
        }
    }
</style>

<div class="class-form-page">
    <div class="class-form-header">
        <div>
            <h2>Sửa lớp tập #{{ $lop->lop_tap_id }}</h2>
            <div style="color:#999; margin-top:6px;">
                Cập nhật thông tin và hình ảnh lớp tập.
            </div>
        </div>

        <a href="{{ url('/admin/classes') }}"
           class="class-form-btn class-form-secondary">
            ← Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="class-form-alert"
             style="background:#163d28;color:#a7efbd;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="class-form-alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="class-form-card">
        <form method="POST"
              action="{{ url('/admin/classes/' . $lop->lop_tap_id . '/edit') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="class-form-grid">
                <div class="class-field">
                    <label for="ten_lop">Tên lớp *</label>
                    <input
                        id="ten_lop"
                        type="text"
                        name="ten_lop"
                        value="{{ old('ten_lop', $lop->ten_lop) }}"
                        required
                        maxlength="100"
                        placeholder="Ví dụ: Yoga cơ bản">
                </div>

                <div class="class-field">
                    <label for="lich_tap">Lịch tập</label>
                    <input
                        id="lich_tap"
                        type="text"
                        name="lich_tap"
                        value="{{ old('lich_tap', $lop->lich_tap) }}"
                        maxlength="255"
                        placeholder="Ví dụ: Thứ 2, 4, 6 - 18:00">
                </div>

                <div class="class-field">
                    <label for="suc_chua">Sức chứa tối đa</label>
                    <input
                        id="suc_chua"
                        type="number"
                        name="suc_chua"
                        value="{{ old('suc_chua', $lop->suc_chua) }}"
                        min="1"
                        placeholder="Ví dụ: 20">
                </div>

                <div class="class-field">
                    <label for="trang_thai">Trạng thái *</label>
                    <select id="trang_thai" name="trang_thai" required>
                        <option
                            value="hoạt_động"
                            @selected(old('trang_thai', $lop->trang_thai) === 'hoạt_động')>
                            Hoạt động
                        </option>
                        <option
                            value="ngừng_hoạt_động"
                            @selected(old('trang_thai', $lop->trang_thai) === 'ngừng_hoạt_động')>
                            Ngừng hoạt động
                        </option>
                    </select>
                </div>

                <div class="class-field class-field-full">
                    <label for="mo_ta">Mô tả lớp tập</label>
                    <textarea
                        id="mo_ta"
                        name="mo_ta"
                        rows="4"
                        placeholder="Mô tả nội dung và mục tiêu lớp tập...">{{ old('mo_ta', $lop->mo_ta) }}</textarea>
                </div>

                <div class="class-field class-field-full">
                    <label>Ảnh lớp hiện tại</label>

                    @if($lop->anh)
                        <div class="class-current-image">
                            <img
                                src="{{ asset($lop->anh) }}"
                                alt="Ảnh {{ $lop->ten_lop }}"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display:none;color:#ffaaaa;padding:12px;">
                                Không tải được ảnh hiện tại.
                            </div>
                        </div>
                        <div style="color:#aaa;font-size:12px;margin-bottom:12px;">
                            Đường dẫn: {{ $lop->anh }}
                        </div>
                    @else
                        <div style="color:#999;margin-bottom:12px;">
                            Lớp này chưa có ảnh.
                        </div>
                    @endif

                    <label for="anh">Chọn ảnh mới (không bắt buộc)</label>
                    <input
                        id="anh"
                        type="file"
                        name="anh"
                        accept=".jpg,.jpeg,.png,.webp">

                    <small class="class-help">
                        JPG, PNG hoặc WEBP; tối đa 4 MB.
                        Không chọn ảnh mới thì ảnh hiện tại được giữ nguyên.
                    </small>
                </div>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px;">
                <button type="submit" class="class-form-btn class-form-primary">
                    Lưu thay đổi
                </button>

                <a href="{{ url('/admin/classes') }}"
                   class="class-form-btn class-form-secondary">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection