@extends('layouts.member')

@section('title', 'Đặt lịch PT')


@section('content')

    <style>
        .booking-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 70px;
        }

        .booking-title {
            margin-bottom: 30px;
        }

        .booking-title h1 {
            margin: 0;
            color: #fff;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: .5px;
        }

        .booking-title p {
            margin: 10px 0 0;
            color: #999;
            font-size: 14px;
        }

        .booking-title span {
            color: #e50914;
        }

        .booking-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 25px;
            align-items: start;
        }

        .booking-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .card-heading-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(229, 9, 20, .12);
            border: 1px solid rgba(229, 9, 20, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e50914;
            font-size: 20px;
        }

        .card-heading h2 {
            margin: 0;
            color: #fff;
            font-size: 20px;
            font-weight: 800;
        }

        .card-heading p {
            margin: 4px 0 0;
            color: #777;
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            margin-bottom: 9px;
            color: #ddd;
            font-size: 13px;
            font-weight: 700;
        }

        .form-label span {
            color: #e50914;
        }

        .form-control,
        .form-select {
            width: 100%;
            box-sizing: border-box;
            padding: 14px 15px;
            border-radius: 10px;
            border: 1px solid #303030;
            background: #181818;
            color: #fff;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: .25s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, .1);
        }

        .form-select option {
            background: #181818;
            color: #fff;
        }

        .form-control::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .form-note {
            margin-top: 7px;
            color: #666;
            font-size: 11px;
            line-height: 1.5;
        }

        .field-error {
            margin-top: 7px;
            color: #ff4d57;
            font-size: 12px;
        }

        .alert-error {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid rgba(229, 9, 20, .35);
            background: rgba(229, 9, 20, .08);
            color: #ff6670;
            font-size: 13px;
        }

        .alert-error ul {
            margin: 7px 0 0;
            padding-left: 18px;
        }

        .booking-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-book {
            flex: 1;
            border: none;
            border-radius: 10px;
            padding: 14px 20px;
            background: #e50914;
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            transition: .25s;
            box-shadow: 0 8px 20px rgba(229, 9, 20, .2);
        }

        .btn-book:hover {
            background: #ff1724;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(229, 9, 20, .3);
        }

        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 130px;
            padding: 14px 18px;
            border-radius: 10px;
            border: 1px solid #303030;
            background: #181818;
            color: #aaa;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: .25s;
            box-sizing: border-box;
        }

        .btn-back:hover {
            border-color: #555;
            color: #fff;
            background: #202020;
        }

        /* RIGHT INFO */
        .info-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .info-card h3 {
            margin: 0 0 20px;
            color: #fff;
            font-size: 17px;
            font-weight: 800;
        }

        .info-item {
            display: flex;
            gap: 13px;
            padding: 15px 0;
            border-bottom: 1px solid #222;
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: rgba(229, 9, 20, .1);
            color: #e50914;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .info-text strong {
            display: block;
            color: #ddd;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .info-text span {
            color: #777;
            font-size: 11px;
            line-height: 1.5;
        }

        /* PACKAGE BOX */
        .package-list {
            margin-top: 25px;
        }

        .package-list-title {
            color: #888;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .package-preview {
            padding: 13px 14px;
            border-radius: 10px;
            background: #181818;
            border: 1px solid #292929;
            margin-bottom: 9px;
        }

        .package-preview strong {
            display: block;
            color: #eee;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .package-preview span {
            color: #777;
            font-size: 11px;
        }

        .empty-package {
            padding: 18px;
            border-radius: 10px;
            background: #181818;
            border: 1px dashed #333;
            color: #777;
            font-size: 12px;
            text-align: center;
            line-height: 1.6;
        }

        .warning-box {
            margin-top: 20px;
            padding: 14px;
            border-radius: 10px;
            background: rgba(255, 165, 0, .06);
            border: 1px solid rgba(255, 165, 0, .15);
            color: #999;
            font-size: 11px;
            line-height: 1.6;
        }

        .warning-box strong {
            color: #ddd;
        }

        @media (max-width: 850px) {
            .booking-layout {
                grid-template-columns: 1fr;
            }

            .info-card {
                order: -1;
            }
        }

        @media (max-width: 600px) {
            .booking-page {
                padding: 25px 14px 50px;
            }

            .booking-card,
            .info-card {
                padding: 20px;
                border-radius: 14px;
            }

            .booking-title h1 {
                font-size: 25px;
            }

            .booking-actions {
                flex-direction: column;
            }

            .btn-back {
                width: 100%;
            }
        }
    </style>

    <div class="booking-page">

        <div class="booking-title">
            <h1>ĐẶT LỊCH <span>PT</span></h1>
            <p>Chọn gói PT và thời gian phù hợp để đặt buổi tập cùng huấn luyện viên.</p>
        </div>

        <div class="booking-layout">

            {{-- FORM ĐẶT LỊCH --}}
            <div class="booking-card">

                <div class="card-heading">
                    <div class="card-heading-icon">
                        📅
                    </div>

                    <div>
                        <h2>Thông tin buổi tập</h2>
                        <p>Điền đầy đủ thông tin bên dưới</p>
                    </div>
                </div>

                {{-- HIỂN THỊ LỖI --}}
                @if ($errors->any())
                    <div class="alert-error">
                        <strong>Không thể đặt lịch.</strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/user/pt-schedule/create') }}" method="POST">
                    @csrf

                    {{-- GÓI PT --}}
                    <div class="form-group">
                        <label class="form-label" for="dang_ky_goi_pt_id">
                            Gói PT <span>*</span>
                        </label>

                        <select name="dang_ky_goi_pt_id" id="dang_ky_goi_pt_id"
                            class="form-select @error('dang_ky_goi_pt_id') is-invalid @enderror" required>
                            <option value="">-- Chọn gói PT --</option>

                            @forelse ($goiPT as $goi)
                                <option value="{{ $goi->dang_ky_goi_pt_id }}"
                                    {{ old('dang_ky_goi_pt_id') == $goi->dang_ky_goi_pt_id ? 'selected' : '' }}>
                                    {{ $goi->ten_goi_pt }}
                                    -
                                    Còn {{ $goi->so_buoi_con_lai }} buổi
                                    -
                                    PT: {{ $goi->ten_pt ?? 'Chưa xác định' }}
                                </option>
                            @empty
                                <option value="" disabled>
                                    Không có gói PT đang hoạt động
                                </option>
                            @endforelse
                        </select>

                        @error('dang_ky_goi_pt_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                        <div class="form-note">
                            Chỉ những gói PT đang hoạt động và còn buổi tập mới được hiển thị.
                        </div>
                    </div>

                    {{-- NGÀY GIỜ BẮT ĐẦU --}}
                    <div class="form-group">
                        <label class="form-label" for="thoi_gian_bat_dau">
                            Thời gian bắt đầu <span>*</span>
                        </label>

                        <input type="datetime-local" name="thoi_gian_bat_dau" id="thoi_gian_bat_dau"
                            class="form-control @error('thoi_gian_bat_dau') is-invalid @enderror"
                            value="{{ old('thoi_gian_bat_dau') }}" required>

                        @error('thoi_gian_bat_dau')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                        <div class="form-note">
                            Chọn ngày và giờ bạn muốn bắt đầu buổi tập.
                        </div>
                    </div>

                    {{-- NGÀY GIỜ KẾT THÚC --}}
                    <div class="form-group">
                        <label class="form-label" for="thoi_gian_ket_thuc">
                            Thời gian kết thúc <span>*</span>
                        </label>

                        <input type="datetime-local" name="thoi_gian_ket_thuc" id="thoi_gian_ket_thuc"
                            class="form-control @error('thoi_gian_ket_thuc') is-invalid @enderror"
                            value="{{ old('thoi_gian_ket_thuc') }}" required>

                        @error('thoi_gian_ket_thuc')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                        <div class="form-note">
                            Thời gian kết thúc phải sau thời gian bắt đầu.
                        </div>
                    </div>

                    {{-- NÚT --}}
                    <div class="booking-actions">

                        <a href="{{ url('/user/pt-schedule') }}" class="btn-back">
                            ← QUAY LẠI
                        </a>

                        <button type="submit" class="btn-book">
                            ĐẶT LỊCH PT
                        </button>

                    </div>

                </form>

            </div>

            {{-- THÔNG TIN --}}
            <div class="info-card">

                <h3>Lưu ý khi đặt lịch</h3>

                <div class="info-item">
                    <div class="info-icon">1</div>

                    <div class="info-text">
                        <strong>Chọn gói PT</strong>
                        <span>
                            Chọn một gói PT đang hoạt động và còn số buổi.
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">2</div>

                    <div class="info-text">
                        <strong>Chọn thời gian</strong>
                        <span>
                            Chọn ngày và thời gian phù hợp với lịch của bạn.
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">3</div>

                    <div class="info-text">
                        <strong>Kiểm tra lịch</strong>
                        <span>
                            Hệ thống sẽ kiểm tra thời gian trùng lịch trước khi đặt.
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">4</div>

                    <div class="info-text">
                        <strong>Theo dõi lịch tập</strong>
                        <span>
                            Sau khi đặt thành công, lịch sẽ xuất hiện trong Lịch PT.
                        </span>
                    </div>
                </div>

                {{-- DANH SÁCH GÓI --}}
                <div class="package-list">

                    <div class="package-list-title">
                        Gói PT hiện có
                    </div>

                    @forelse ($goiPT as $goi)
                        <div class="package-preview">
                            <strong>
                                {{ $goi->ten_goi_pt }}
                            </strong>

                            <span>
                                Còn {{ $goi->so_buoi_con_lai }} buổi
                                @if (isset($goi->ten_pt))
                                    · PT {{ $goi->ten_pt }}
                                @endif
                            </span>
                        </div>

                    @empty

                        <div class="empty-package">
                            Bạn chưa có gói PT nào đang hoạt động.
                            <br>
                            Hãy đăng ký một gói PT trước.
                        </div>
                    @endforelse

                </div>

                <div class="warning-box">
                    <strong>⚠ Lưu ý:</strong>
                    Không thể đặt lịch nếu thời gian đã trùng với một lịch PT khác.
                    Số buổi sẽ được xử lý theo trạng thái buổi tập.
                </div>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const startInput = document.getElementById('thoi_gian_bat_dau');
            const endInput = document.getElementById('thoi_gian_ket_thuc');

            function getCurrentDateTimeLocal() {
                const now = new Date();

                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hour = String(now.getHours()).padStart(2, '0');
                const minute = String(now.getMinutes()).padStart(2, '0');

                return `${year}-${month}-${day}T${hour}:${minute}`;
            }

            if (startInput) {
                startInput.min = getCurrentDateTimeLocal();
            }

            if (startInput && endInput) {

                startInput.addEventListener('change', function() {

                    if (startInput.value) {

                        endInput.min = startInput.value;

                        if (
                            endInput.value &&
                            endInput.value <= startInput.value
                        ) {
                            endInput.value = '';
                        }
                    }

                });

            }

        });
    </script>

@endsection
