@extends('layouts.user')

@section('title', 'Hồ sơ huấn luyện viên - GYM FIT')

@section('content')
    <div class="profile-page">
        <div class="profile-container">

            <div class="profile-heading">
                <div>
                    <span class="profile-label">GYMFIT · TRAINER AREA</span>
                    <h1>Hồ sơ cá nhân</h1>
                    <p>Quản lý thông tin tài khoản và thông tin cá nhân của bạn.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">
                    <strong>Vui lòng kiểm tra lại:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/trainer/profile" method="POST">
                @csrf

                <div class="profile-grid">

                    {{-- THÔNG TIN CÁ NHÂN --}}
                    <section class="profile-card">
                        <div class="card-title">
                            <span class="title-icon">👤</span>
                            <div>
                                <h2>Thông tin cá nhân</h2>
                                <p>Thông tin huấn luyện viên của bạn.</p>
                            </div>
                        </div>

                        <div class="form-grid">

                            <div class="form-group full">
                                <label>Họ và tên</label>
                                <input type="text" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="so_dien_thoai"
                                    value="{{ old('so_dien_thoai', $huanLuyenVien->so_dien_thoai ?? '') }}" maxlength="20">
                            </div>

                            <div class="form-group">
                                <label>Ngày sinh</label>
                                <input type="date" name="ngay_sinh"
                                    value="{{ old('ngay_sinh', $huanLuyenVien->ngay_sinh ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label>Ngày vào làm</label>
                                <input type="date" value="{{ $huanLuyenVien->ngay_vao_lam ?? '' }}" disabled>
                            </div>

                            <div class="form-group full">
                                <label>Địa chỉ</label>
                                <textarea name="dia_chi" rows="4" maxlength="255">{{ old('dia_chi', $huanLuyenVien->dia_chi ?? '') }}</textarea>
                            </div>

                        </div>
                    </section>

                    {{-- ĐỔI MẬT KHẨU --}}
                    <section class="profile-card">
                        <div class="card-title">
                            <span class="title-icon">🔐</span>
                            <div>
                                <h2>Đổi mật khẩu</h2>
                                <p>Để trống nếu không muốn đổi mật khẩu.</p>
                            </div>
                        </div>

                        <div class="form-grid single">

                            <div class="form-group">
                                <label>Mật khẩu hiện tại</label>
                                <input type="password" name="mat_khau_hien_tai" autocomplete="current-password">
                            </div>

                            <div class="form-group">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="mat_khau_moi" minlength="6" autocomplete="new-password">
                            </div>

                            <div class="form-group">
                                <label>Xác nhận mật khẩu mới</label>
                                <input type="password" name="mat_khau_moi_confirmation" minlength="6"
                                    autocomplete="new-password">
                            </div>

                        </div>

                        <div class="profile-note">
                            <strong>Lưu ý:</strong>
                            Mật khẩu mới phải có ít nhất 6 ký tự.
                        </div>
                    </section>

                </div>

                <div class="profile-actions">
                    <a href="/trainer/dashboard" class="cancel-btn">HỦY</a>
                    <button type="submit" class="save-btn">LƯU THAY ĐỔI</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .profile-page {
            background: #080808;
            min-height: calc(100vh - 75px);
            padding: 65px 30px 90px;
        }

        .profile-container {
            max-width: 1180px;
            margin: 0 auto;
        }

        .profile-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 35px;
        }

        .profile-label {
            display: inline-block;
            color: #e50914;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .profile-heading h1 {
            margin: 0 0 8px;
            color: #fff;
            font-size: 42px;
            font-weight: 900;
        }

        .profile-heading p {
            margin: 0;
            color: #999;
            font-size: 15px;
        }

        .cancel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 20px;
            border: 1px solid #444;
            border-radius: 5px;
            color: #ddd;
            font-size: 13px;
            font-weight: 700;
            transition: .25s;
            text-decoration: none;
        }

        .cancel-btn:hover {
            color: #fff;
            border-color: #e50914;
        }

        .alert {
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .alert.success {
            background: #102411;
            border: 1px solid #245b2b;
            color: #b8e7bd;
        }

        .alert.error {
            background: #26090b;
            border: 1px solid #65151a;
            color: #ffb8bd;
        }

        .alert ul {
            margin: 8px 0 0 20px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1.35fr .9fr;
            gap: 24px;
        }

        .profile-card {
            background: #111;
            border: 1px solid #292929;
            border-radius: 8px;
            padding: 28px;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 1px solid #252525;
        }

        .title-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e50914;
            border-radius: 5px;
            font-size: 21px;
            flex-shrink: 0;
        }

        .card-title h2 {
            margin: 0 0 5px;
            color: #fff;
            font-size: 20px;
        }

        .card-title p {
            margin: 0;
            color: #777;
            font-size: 13px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-grid.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            color: #ddd;
            font-size: 13px;
            font-weight: 700;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            background: #090909;
            border: 1px solid #333;
            border-radius: 5px;
            color: #fff;
            padding: 12px 13px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: .25s;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 105px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 2px rgba(229, 9, 20, .08);
        }

        .form-group input:disabled {
            color: #777;
            cursor: not-allowed;
        }

        .profile-note {
            margin-top: 20px;
            padding: 14px;
            background: #171717;
            border-left: 3px solid #e50914;
            color: #888;
            font-size: 13px;
            line-height: 1.6;
        }

        .profile-note strong {
            color: #ddd;
        }

        .profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .save-btn {
            min-height: 44px;
            padding: 0 24px;
            border: none;
            border-radius: 5px;
            background: #e50914;
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: .25s;
        }

        .save-btn:hover {
            background: #ff2633;
            transform: translateY(-1px);
        }

        @media (max-width: 850px) {
            .profile-page {
                padding: 45px 20px 70px;
            }

            .profile-heading h1 {
                font-size: 34px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .profile-card {
                padding: 20px;
            }

            .profile-actions {
                flex-direction: column-reverse;
            }

            .save-btn,
            .cancel-btn {
                width: 100%;
            }
        }
    </style>
@endsection
