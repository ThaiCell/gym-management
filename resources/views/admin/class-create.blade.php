
@extends('layouts.admin')

@section('content')
<style>
    .class-form-page { color: #f5f5f5; padding: 24px; max-width: 1100px; }
    .class-form-header {
        display: flex; justify-content: space-between; align-items: center;
        gap: 12px; margin-bottom: 22px; flex-wrap: wrap;
    }
    .class-form-header h2 { margin: 0; font-size: 25px; font-weight: 700; }
    .class-form-card {
        background: #171717; border: 1px solid #303030;
        border-radius: 12px; padding: 24px;
    }
    .class-form-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    .class-field { margin-bottom: 20px; }
    .class-field-full { grid-column: 1 / -1; }
    .class-field label {
        display: block; margin-bottom: 8px; color: #eee;
        font-size: 14px; font-weight: 600;
    }
    .class-field input, .class-field textarea, .class-field select {
        display: block; width: 100%; box-sizing: border-box;
        background: #222; border: 1px solid #444; color: #fff;
        border-radius: 7px; padding: 11px 12px; font-size: 14px;
    }
    .class-field input:focus, .class-field textarea:focus, .class-field select:focus {
        outline: none; border-color: #ed0715;
        box-shadow: 0 0 0 2px rgba(237,7,21,.15);
    }
    .class-field input[type=file] { padding: 9px; }
    .class-form-btn {
        display: inline-block; border: 0; border-radius: 7px; padding: 11px 17px;
        cursor: pointer; font-weight: 600; text-decoration: none; font-size: 14px;
    }
    .class-form-primary { background: #ed0715; color: white; }
    .class-form-primary:hover { background: #c9000c; color: white; }
    .class-form-secondary { background: #292929; color: white; border: 1px solid #444; }
    .class-form-alert { background: #492020; color: #ffc0c0; padding: 12px; border-radius: 7px; margin-bottom: 18px; }
    @media(max-width: 700px) {
        .class-form-page { padding: 14px; }
        .class-form-card { padding: 16px; }
        .class-form-grid { grid-template-columns: 1fr; gap: 0; }
        .class-field-full { grid-column: auto; }
    }
</style>

<div class="class-form-page">
    <div class="class-form-header">
        <h2>Thêm lớp tập</h2>
        <a href="{{ url('/admin/classes') }}" class="class-form-btn class-form-secondary">← Quay lại</a>
    </div>

    @if($errors->any())
        <div class="class-form-alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="class-form-card">
        <form method="POST" action="{{ url('/admin/classes/create') }}" enctype="multipart/form-data">
            @csrf
            <div class="class-form-grid">
                <div class="class-field">
                    <label for="ten_lop">Tên lớp *</label>
                    <input id="ten_lop" type="text" name="ten_lop" value="{{ old('ten_lop') }}"
                           required maxlength="100" placeholder="Ví dụ: Yoga cơ bản">
                </div>

                <div class="class-field">
                    <label for="lich_tap">Lịch tập</label>
                    <input id="lich_tap" type="text" name="lich_tap" value="{{ old('lich_tap') }}"
                           maxlength="255" placeholder="Ví dụ: Thứ 2, 4, 6 - 18:00">
                </div>

                <div class="class-field">
                    <label for="suc_chua">Sức chứa tối đa</label>
                    <input id="suc_chua" type="number" name="suc_chua" value="{{ old('suc_chua') }}"
                           min="1" placeholder="Ví dụ: 20">
                </div>

                <div class="class-field">
                    <label for="trang_thai">Trạng thái *</label>
                    <select id="trang_thai" name="trang_thai" required>
                        <option value="hoạt_động" @selected(old('trang_thai', 'hoạt_động') === 'hoạt_động')>Hoạt động</option>
                        <option value="ngừng_hoạt_động" @selected(old('trang_thai') === 'ngừng_hoạt_động')>Ngừng hoạt động</option>
                    </select>
                </div>

                <div class="class-field class-field-full">
                    <label for="mo_ta">Mô tả lớp tập</label>
                    <textarea id="mo_ta" name="mo_ta" rows="4"
                              placeholder="Mô tả nội dung và mục tiêu lớp tập...">{{ old('mo_ta') }}</textarea>
                </div>

                <div class="class-field class-field-full">
                    <label for="anh">Ảnh lớp tập</label>
                    <input id="anh" type="file" name="anh" accept=".jpg,.jpeg,.png,.webp">
                    <small style="display:block;color:#999;margin-top:7px">Định dạng JPG, PNG hoặc WEBP; tối đa 4 MB.</small>
                </div>
            </div>

            <button type="submit" class="class-form-btn class-form-primary">Lưu lớp tập</button>
        </form>
    </div>
</div>
@endsection
