@extends('layouts.admin')

@section('content')
    <style>
        .pt-form {
            max-width: 900px;
            padding: 26px
        }

        .pt-muted {
            color: #888;
            font-size: 12px;
            margin-top: 6px
        }

        .pt-alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #351013;
            color: #ffb7bc
        }

        .pt-form-card {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            padding: 24px
        }

        .pt-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px
        }

        .pt-field label {
            display: block;
            margin-bottom: 8px;
            color: #ddd;
            font-weight: 600
        }

        .pt-field input,
        .pt-field select {
            background: #222;
            border: 1px solid #393939;
            color: #fff;
            padding: 11px;
            border-radius: 7px;
            width: 100%;
            box-sizing: border-box
        }

        .pt-preview {
            display: block;
            max-width: 240px;
            max-height: 160px;
            object-fit: cover;
            border: 1px solid #444;
            border-radius: 8px;
            margin: 12px 0
        }

        .pt-form-actions {
            display: flex;
            gap: 10px;
            margin-top: 24px
        }

        .pt-btn {
            display: inline-block;
            background: #e50914;
            color: #fff;
            border: 0;
            border-radius: 7px;
            padding: 10px 14px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600
        }

        .pt-secondary {
            background: #292929
        }

        @media(max-width:600px) {
            .pt-grid {
                grid-template-columns: 1fr
            }

            .pt-form {
                padding: 15px
            }
        }
    </style>

    <div class="pt-form">
        <h2>Sửa gói PT</h2>
        <p style="color:#888;margin:7px 0 22px">
            Cập nhật gói #{{ $package->goi_pt_id }}
        </p>

        @if(session('error'))
            <div class="pt-alert">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="pt-alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="pt-form-card">
            <form method="POST" action="/admin/pt-packages/{{ $package->goi_pt_id }}/update" enctype="multipart/form-data">
                @csrf

                <div class="pt-grid">
                    <div class="pt-field">
                        <label>Tên gói PT *</label>
                        <input name="ten_goi_pt" maxlength="100" required
                            value="{{ old('ten_goi_pt', $package->ten_goi_pt) }}">
                    </div>

                    <div class="pt-field">
                        <label>Giá (VNĐ) *</label>
                        <input type="number" name="gia" min="0" step="1000" required
                            value="{{ old('gia', $package->gia) }}">
                    </div>

                    <div class="pt-field">
                        <label>Số buổi *</label>
                        <input type="number" name="so_buoi" min="1" required
                            value="{{ old('so_buoi', $package->so_buoi) }}">
                    </div>

                    <div class="pt-field">
                        <label>Thời hạn (ngày) *</label>
                        <input type="number" name="thoi_han_ngay" min="1" required
                            value="{{ old('thoi_han_ngay', $package->thoi_han_ngay) }}">
                    </div>

                    <div class="pt-field">
                        <label>Trạng thái *</label>
                        <select name="trang_thai" required>
                            <option value="hoat_dong" {{ old('trang_thai', $package->trang_thai) === 'hoat_dong' ? 'selected' : '' }}>
                                Hoạt động
                            </option>
                            <option value="tam_ngung" {{ old('trang_thai', $package->trang_thai) === 'tam_ngung' ? 'selected' : '' }}>
                                Tạm ngưng
                            </option>
                        </select>
                    </div>

                    <div class="pt-field">
                        <label>Ảnh gói PT (không bắt buộc)</label>

                        @if(!empty($package->anh))
                            <p class="pt-muted">Ảnh hiện tại:</p>
                            <img src="{{ asset($package->anh) }}" class="pt-preview" alt="Ảnh gói PT hiện tại">
                        @else
                            <p class="pt-muted">Gói PT chưa có ảnh.</p>
                        @endif

                        <input type="file" name="anh" id="anh"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <div class="pt-muted">
                            Chọn ảnh mới để thay ảnh hiện tại. Bỏ trống để giữ ảnh cũ. Tối đa 2 MB.
                        </div>
                        <img id="anh-preview" class="pt-preview" alt="Xem trước ảnh mới" hidden>
                    </div>
                </div>

                <div class="pt-form-actions">
                    <button class="pt-btn" type="submit">Lưu thay đổi</button>
                    <a class="pt-btn pt-secondary" href="/admin/pt-packages">Hủy</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('anh').addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];
            const preview = document.getElementById('anh-preview');

            if (!file) {
                preview.hidden = true;
                preview.removeAttribute('src');
                return;
            }

            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                alert('Chỉ chọn ảnh JPG, PNG hoặc WEBP.');
                event.target.value = '';
                preview.hidden = true;
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ảnh không được vượt quá 2 MB.');
                event.target.value = '';
                preview.hidden = true;
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
        });
    </script>
@endsection