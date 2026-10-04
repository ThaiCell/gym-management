@extends('layouts.member')

@section('title', 'Quản lý hội viên')
@section('role_name', 'Nhân viên')



@section('content')

    <style>
        .staff-page {
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
            margin-bottom: 8px;
        }

        .page-header h1 {
            margin: 0;
            color: #fff;
            font-size: 30px;
        }

        .page-header p {
            margin: 9px 0 0;
            color: #888;
            font-size: 13px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 8px;
            flex: 1;
        }

        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 12px 15px;
            background: #111;
            border: 1px solid #292929;
            border-radius: 8px;
            color: #fff;
            outline: none;
        }

        .search-input:focus {
            border-color: #e50914;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 16px;
            border-radius: 8px;
            border: 0;
            text-decoration: none;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .btn-red {
            background: #e50914;
            color: #fff;
        }

        .btn-red:hover {
            background: #ff1824;
        }

        .btn-gray {
            background: #222;
            color: #ddd;
            border: 1px solid #333;
        }

        .btn-gray:hover {
            border-color: #e50914;
            color: #fff;
        }

        .member-box {
            background: #111;
            border: 1px solid #292929;
            border-radius: 16px;
            overflow: hidden;
        }

        .member-table {
            width: 100%;
            border-collapse: collapse;
        }

        .member-table th {
            padding: 16px;
            text-align: left;
            color: #777;
            font-size: 10px;
            letter-spacing: 1px;
            background: #0d0d0d;
            border-bottom: 1px solid #292929;
        }

        .member-table td {
            padding: 15px 16px;
            color: #ddd;
            font-size: 12px;
            border-bottom: 1px solid #222;
        }

        .member-table tr:last-child td {
            border-bottom: 0;
        }

        .member-table tr:hover td {
            background: #151515;
        }

        .member-name {
            color: #fff;
            font-weight: 700;
        }

        .member-email {
            color: #888;
            font-size: 11px;
            margin-top: 4px;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-active {
            color: #4ade80;
            background: rgba(74, 222, 128, .1);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .status-locked {
            color: #ff4b53;
            background: rgba(229, 9, 20, .1);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        .actions {
            display: flex;
            gap: 7px;
        }

        .action-edit {
            color: #fff;
            background: #222;
            border: 1px solid #333;
        }

        .action-edit:hover {
            border-color: #e50914;
        }

        .action-delete {
            color: #ff6970;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        .action-delete:hover {
            background: #e50914;
            color: #fff;
        }

        .empty {
            padding: 70px 20px;
            text-align: center;
            color: #777;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .alert-success {
            color: #6ee7a0;
            background: rgba(74, 222, 128, .08);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .alert-error {
            color: #ff6970;
            background: rgba(229, 9, 20, .08);
            border: 1px solid rgba(229, 9, 20, .2);
        }

        @media (max-width: 900px) {

            .member-box {
                overflow-x: auto;
            }

            .member-table {
                min-width: 900px;
            }

        }

        @media (max-width: 600px) {

            .page-header {
                padding: 28px 22px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-form {
                width: 100%;
            }

        }
    </style>


    <div class="staff-page">

        @if (session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                ! {{ session('error') }}
            </div>
        @endif


        <div class="page-header">

            <div class="page-label">
                GYMFIT • STAFF MEMBERS
            </div>

            <h1>
                Quản lý hội viên
            </h1>

            <p>
                Thêm, chỉnh sửa và quản lý tài khoản hội viên.
            </p>

        </div>


        <div class="toolbar">

            <form action="/staff/members" method="GET" class="search-form">

                <input type="text" name="q" value="{{ request('q') }}" class="search-input"
                    placeholder="Tìm theo tên, email hoặc số điện thoại...">

                <button class="btn btn-gray">
                    🔎 TÌM
                </button>

            </form>


            <a href="/staff/members/create" class="btn btn-red">
                ＋ THÊM HỘI VIÊN
            </a>

        </div>


        <div class="member-box">

            @if ($hoiVien->count() > 0)

                <table class="member-table">

                    <thead>

                        <tr>
                            <th>HỘI VIÊN</th>
                            <th>SỐ ĐIỆN THOẠI</th>
                            <th>NGÀY THAM GIA</th>
                            <th>TRẠNG THÁI</th>
                            <th>THAO TÁC</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($hoiVien as $hv)
                            <tr>

                                <td>

                                    <div class="member-name">
                                        {{ $hv->ho_ten }}
                                    </div>

                                    <div class="member-email">
                                        {{ $hv->email }}
                                    </div>

                                </td>

                                <td>
                                    {{ $hv->so_dien_thoai ?: 'Chưa cập nhật' }}
                                </td>

                                <td>
                                    {{ $hv->ngay_tham_gia ?: '---' }}
                                </td>

                                <td>

                                    @if (in_array($hv->trang_thai, ['hoạt_động', 'hoat_dong', 'đang hoạt động']))
                                        <span class="status status-active">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="status status-locked">
                                            Bị khóa
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="actions">

                                        <a href="/staff/members/{{ $hv->hoi_vien_id }}/edit" class="btn action-edit">
                                            SỬA
                                        </a>


                                        <form action="/staff/members/{{ $hv->hoi_vien_id }}/delete" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa hội viên này không?');">

                                            @csrf

                                            <button type="submit" class="btn action-delete">
                                                XÓA
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
            @else
                <div class="empty">

                    👥

                    <h3 style="color:#ddd;">
                        Chưa có hội viên
                    </h3>

                    <p>
                        Không tìm thấy hội viên phù hợp.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection
