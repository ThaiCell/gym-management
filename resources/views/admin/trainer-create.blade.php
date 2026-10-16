
@extends('layouts.admin')

@section('content')
<style>
    .trainer-create-page {
        padding: 26px;
        color: #fff;
    }

    .trainer-create-header {
        margin-bottom: 22px;
    }

    .trainer-create-header h2 {
        margin: 0;
        font-size: 25px;
    }

    .trainer-create-header p {
        color: #888;
        margin-top: 7px;
    }

    .trainer-create-card {
        max-width: 900px;
        padding: 25px;
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
    }

    .trainer-create-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .trainer-create-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .trainer-create-field.full-width {
        grid-column: 1 / -1;
    }

    .trainer-create-field label {
        color: #ddd;
        font-size: 14px;
        font-weight: 600;
    }

    .trainer-create-field input {
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        background: #222;
        border: 1px solid #393939;
        border-radius: 7px;
        color: #fff;
        outline: none;
    }

    .trainer-create-field input:focus {
        border-color: #ed1c24;
    }

    .trainer-create-hint {
        color: #888;
        font-size: 12px;
    }

    .trainer-create-preview {
        display: none;
        width: 150px;
        height: 150px;
        margin-top: 12px;
        border-radius: 10px;
        border: 1px solid #393939;
        object-fit: cover;
    }

    .trainer-create-alert {
        padding: 12px 15px;
        margin-bottom: 18px;
        border-radius: 8px;
        background: #351013;
        color: #ffb7bc;
    }

    .trainer-create-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 25px;
    }

    .trainer-create-submit,
    .trainer-create-cancel {
        display: inline-block;
        padding: 11px 18px;
        border: none;
        border-radius: 7px;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }

    .trainer-create-submit {
        background: #ed1c24;
        color: #fff;
    }

    .trainer-create-submit:hover {
        background: #c9141b;
    }

    .trainer-create-cancel {
        background: #333;
        color: #fff;
    }

    @media (max-width: 650px) {
        .trainer-create-grid {
            grid-template-columns: 1fr;
        }

        .trainer-create-field.full-width {
            grid-column: auto;
        }
    }
</style>

<div class="trainer-create-page">
    <div class="trainer-create-header">
        <h2>Thêm huấn luyện viên</h2>
        <p>Nhập thông tin và ảnh đại diện huấn luyện viên</p>
    </div>

    @if (session('error'))
        <div class="trainer-create-alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="trainer-create-alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="trainer-create-card">
        <form action="/admin/trainers/create"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="trainer-create-grid">
                <div class="trainer-create-field">
                    <label for="ho_ten">Họ và tên *</label>
                    <input id="ho_ten"
                           type="text"
                           name="ho_ten"
                           value="{{ old('ho_ten') }}"
                           maxlength="100"
                           required>
                </div>

                <div class="trainer-create-field">
                    <label for="email">Email *</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           maxlength="100"
                           required>
                </div>

                <div class="trainer-create-field">
                    <label for="so_dien_thoai">Số điện thoại</label>
                    <input id="so_dien_thoai"
                           type="text"
                           name="so_dien_thoai"
                           value="{{ old('so_dien_thoai') }}"
                           maxlength="20">
                </div>

                <div class="trainer-create-field">
                    <label for="chuyen_mon">Chuyên môn *</label>
                    <input id="chuyen_mon"
                           type="text"
                           name="chuyen_mon"
                           value="{{ old('chuyen_mon') }}"
                           maxlength="255"
                           placeholder="Ví dụ: Gym, Yoga, Cardio"
                           required>
                </div>

                <div class="trainer-create-field full-width">
                    <label for="anh">Ảnh đại diện huấn luyện viên</label>
                    <input id="anh"
                           type="file"
                           name="anh"
                           accept=".jpg,.jpeg,.png,.webp">

                    <span class="trainer-create-hint">
                        Chọn ảnh JPG, PNG hoặc WEBP, tối đa 2 MB.
                    </span>

                    <img id="anh-preview"
                         class="trainer-create-preview"
                         alt="Ảnh xem trước">
                </div>
            </div>

            <div class="trainer-create-actions">
                <button type="submit" class="trainer-create-submit">
                    Lưu huấn luyện viên
                </button>

                <a href="/admin/trainers" class="trainer-create-cancel">
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('anh').addEventListener('change', function () {
    const file = this.files[0];
    const preview = document.getElementById('anh-preview');

    if (!file) {
        preview.style.display = 'none';
        preview.removeAttribute('src');
        return;
    }

    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (!allowedTypes.includes(file.type)) {
        alert('Vui lòng chọn ảnh JPG, PNG hoặc WEBP.');
        this.value = '';
        preview.style.display = 'none';
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Ảnh không được vượt quá 2 MB.');
        this.value = '';
        preview.style.display = 'none';
        return;
    }

    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
});
</script>
@endsection
