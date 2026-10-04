@extends('layouts.member')

@section('title', 'Xử lý liên hệ')
@section('role_name', 'Nhân viên')



@section('content')

    <style>
        .contact-page {
            max-width: 1400px;
            margin: auto;
        }

        .page-header {
            padding: 35px 40px;
            margin-bottom: 25px;
            border-radius: 18px;
            background: linear-gradient(110deg, #111, #181818, #090909);
            border: 1px solid #292929;
        }

        .page-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .page-header h1 {
            margin: 8px 0;
            color: #fff;
            font-size: 30px;
        }

        .page-header p {
            color: #888;
            font-size: 13px;
            margin: 0;
        }

        .contact-box {
            background: #111;
            border: 1px solid #292929;
            border-radius: 16px;
            overflow: hidden;
        }

        .contact-item {
            padding: 25px;
            border-bottom: 1px solid #222;
        }

        .contact-item:last-child {
            border-bottom: 0;
        }

        .contact-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .contact-name {
            color: #fff;
            font-weight: 800;
            font-size: 15px;
        }

        .contact-info {
            color: #777;
            font-size: 11px;
            margin-top: 5px;
        }

        .contact-content {
            padding: 15px;
            background: #0b0b0b;
            border: 1px solid #202020;
            border-radius: 9px;
            color: #bbb;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .contact-response {
            padding: 13px 15px;
            background: rgba(74, 222, 128, .05);
            border: 1px solid rgba(74, 222, 128, .15);
            border-radius: 9px;
            color: #8ee5ae;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .response-label {
            display: block;
            color: #6ee7a0;
            font-size: 9px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
        }

        .status-pending {
            color: #facc15;
            background: rgba(250, 204, 21, .08);
            border: 1px solid rgba(250, 204, 21, .2);
        }

        .status-processing {
            color: #60a5fa;
            background: rgba(96, 165, 250, .08);
            border: 1px solid rgba(96, 165, 250, .2);
        }

        .status-done {
            color: #4ade80;
            background: rgba(74, 222, 128, .08);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .status-other {
            color: #ff6970;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        .contact-date {
            color: #666;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .process-box {
            padding: 18px;
            background: #0d0d0d;
            border: 1px solid #252525;
            border-radius: 12px;
        }

        .process-title {
            color: #ddd;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .process-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            color: #777;
            font-size: 10px;
            font-weight: 700;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            background: #111;
            color: #ddd;
            border: 1px solid #303030;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 11px;
            outline: none;
        }

        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #e50914;
        }

        .form-group textarea {
            min-height: 90px;
            resize: vertical;
            font-family: inherit;
        }

        .btn-save {
            align-self: flex-start;
            padding: 10px 18px;
            background: #e50914;
            color: #fff;
            border: 0;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #ff1824;
        }

        .empty {
            text-align: center;
            padding: 70px 20px;
            color: #777;
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .empty h3 {
            color: #ddd;
            margin: 0 0 8px;
            font-size: 16px;
        }

        .empty p {
            margin: 0;
            font-size: 12px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .success {
            color: #6ee7a0;
            background: rgba(74, 222, 128, .08);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .error {
            color: #ff6970;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        @media (max-width: 700px) {
            .contact-item {
                padding: 18px;
            }

            .contact-top {
                flex-direction: column;
            }

            .page-header {
                padding: 25px 20px;
            }

            .page-header h1 {
                font-size: 24px;
            }
        }
    </style>


    <div class="contact-page">

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div class="alert success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert error">
                ! {{ session('error') }}
            </div>
        @endif


        {{-- HEADER --}}
        <div class="page-header">

            <div class="page-label">
                GYMFIT • CONTACT CENTER
            </div>

            <h1>
                Xử lý liên hệ
            </h1>

            <p>
                Tiếp nhận và xử lý các yêu cầu liên hệ từ khách hàng.
            </p>

        </div>


        {{-- DANH SÁCH LIÊN HỆ --}}
        <div class="contact-box">

            @if ($lienHe->count() > 0)

                @foreach ($lienHe as $lh)
                    <div class="contact-item">

                        {{-- THÔNG TIN KHÁCH --}}
                        <div class="contact-top">

                            <div>

                                <div class="contact-name">
                                    {{ $lh->ho_ten }}
                                </div>

                                <div class="contact-info">
                                    📧 {{ $lh->email }}

                                    &nbsp; • &nbsp;

                                    ☎ {{ $lh->so_dien_thoai }}
                                </div>

                            </div>


                            {{-- TRẠNG THÁI --}}
                            @php
                                $status = strtolower($lh->trang_thai ?? '');
                            @endphp


                            @if ($status == 'chưa xử lý')
                                <span class="status status-pending">
                                    Chưa xử lý
                                </span>
                            @elseif ($status == 'đang xử lý')
                                <span class="status status-processing">
                                    Đang xử lý
                                </span>
                            @elseif ($status == 'đã xử lý')
                                <span class="status status-done">
                                    Đã xử lý
                                </span>
                            @else
                                <span class="status status-other">
                                    {{ $lh->trang_thai }}
                                </span>
                            @endif

                        </div>


                        {{-- NỘI DUNG LIÊN HỆ --}}
                        <div class="contact-content">

                            {{ $lh->noi_dung }}

                        </div>


                        {{-- PHẢN HỒI CŨ --}}
                        @if ($lh->phan_hoi)
                            <div class="contact-response">

                                <span class="response-label">
                                    PHẢN HỒI NHÂN VIÊN
                                </span>

                                {{ $lh->phan_hoi }}

                            </div>
                        @endif


                        {{-- THỜI GIAN --}}
                        <div class="contact-date">

                            Gửi lúc:
                            {{ $lh->tao_luc }}

                            @if ($lh->ten_nhan_vien)
                                &nbsp; • &nbsp;

                                Xử lý bởi:
                                {{ $lh->ten_nhan_vien }}
                            @endif

                        </div>


                        {{-- XỬ LÝ LIÊN HỆ --}}
                        <div class="process-box">

                            <div class="process-title">
                                XỬ LÝ YÊU CẦU
                            </div>

                            <form action="/staff/contacts/{{ $lh->lien_he_id }}/update" method="POST"
                                class="process-form">

                                @csrf


                                {{-- TRẠNG THÁI --}}
                                <div class="form-group">

                                    <label>
                                        TRẠNG THÁI
                                    </label>

                                    <select name="trang_thai" required>

                                        <option value="chưa xử lý" {{ $lh->trang_thai == 'chưa xử lý' ? 'selected' : '' }}>
                                            Chưa xử lý
                                        </option>

                                        <option value="đang xử lý" {{ $lh->trang_thai == 'đang xử lý' ? 'selected' : '' }}>
                                            Đang xử lý
                                        </option>

                                        <option value="đã xử lý" {{ $lh->trang_thai == 'đã xử lý' ? 'selected' : '' }}>
                                            Đã xử lý
                                        </option>

                                    </select>

                                </div>


                                {{-- PHẢN HỒI --}}
                                <div class="form-group">

                                    <label>
                                        PHẢN HỒI KHÁCH HÀNG
                                    </label>

                                    <textarea name="phan_hoi" placeholder="Nhập nội dung phản hồi cho khách hàng...">{{ $lh->phan_hoi }}</textarea>

                                </div>


                                {{-- LƯU --}}
                                <button type="submit" class="btn-save">
                                    ✓ LƯU XỬ LÝ
                                </button>

                            </form>

                        </div>

                    </div>
                @endforeach
            @else
                <div class="empty">

                    <div class="empty-icon">
                        📩
                    </div>

                    <h3>
                        Chưa có liên hệ
                    </h3>

                    <p>
                        Hiện chưa có yêu cầu liên hệ nào.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection
