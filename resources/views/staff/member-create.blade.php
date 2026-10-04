@extends('layouts.member')

@section('title', 'Thêm hội viên')
@section('role_name', 'Nhân viên')



@section('content')

    <style>
        .form-page {
            max-width: 900px;
            margin: auto;
        }

        .form-header {
            margin-bottom: 22px;
        }

        .form-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .form-header h1 {
            color: #fff;
            margin: 7px 0;
            font-size: 30px;
        }

        .form-header p {
            color: #777;
            font-size: 13px;
        }

        .form-box {
            background: #111;
            border: 1px solid #292929;
            border-radius: 16px;
            padding: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            color: #aaa;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            background: #0b0b0b;
            border: 1px solid #303030;
            border-radius: 8px;
            color: #fff;
            outline: none;
        }

        .form-control:focus {
            border-color: #e50914;
        }

        .error {
            color: #ff6970;
            font-size: 11px;
            margin-top: 5px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 800;
            border: 0;
            cursor: pointer;
        }

        .btn-red {
            background: #e50914;
            color: #fff;
        }

        .btn-gray {
            background: #222;
            color: #ddd;
            border: 1px solid #333;
        }

        @media(max-width:700px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .form-box {
                padding: 20px;
            }

        }
    </style>


    <div class="form-page">

        <div class="form-header">

            <div class="form-label">
                GYMFIT • ADD MEMBER
            </div>

            <h1>
                Thêm hội viên
            </h1>

            <p>
                Tạo tài khoản hội viên mới cho hệ thống.
            </p>

        </div>


        @if (session('error'))
            <div
                style="
            margin-bottom:18px;
            padding:13px;
            border-radius:8px;
            color:#ff6970;
            background:rgba(229,9,20,.08);
            border:1px solid rgba(229,9,20,.2);
        ">
                {{ session('error') }}
            </div>
        @endif


        <div class="form-box">

            <form action="/staff/members" method="POST">

                @csrf

                <div class="form-grid">


                    <div class="form-group">

                        <label>
                            HỌ VÀ TÊN *
                        </label>

                        <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" required>

                        @error('ho_ten')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            EMAIL *
                        </label>

                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>

                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            MẬT KHẨU *
                        </label>

                        <input type="password" name="mat_khau" class="form-control" required>

                        @error('mat_khau')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            SỐ ĐIỆN THOẠI
                        </label>

                        <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}">

                    </div>


                    <div class="form-group">

                        <label>
                            NGÀY SINH
                        </label>

                        <input type="date" name="ngay_sinh" class="form-control" value="{{ old('ngay_sinh') }}">

                    </div>


                    <div class="form-group full">

                        <label>
                            ĐỊA CHỈ
                        </label>

                        <input type="text" name="dia_chi" class="form-control" value="{{ old('dia_chi') }}">

                    </div>


                </div>


                <div class="actions">

                    <button type="submit" class="btn btn-red">
                        ✓ LƯU HỘI VIÊN
                    </button>

                    <a href="/staff/members" class="btn btn-gray">
                        HỦY
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection
