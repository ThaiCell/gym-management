@extends('layouts.app')

@section('content')

<div class="container-fluid">
@if(session('success'))
    <div style="
        background:#063d1d;
        color:#00d26a;
        border:1px solid #0b6b35;
        padding:12px 15px;
        border-radius:6px;
        margin-bottom:20px;
    ">
        {{ session('success') }}
    </div>
@endif

    {{-- Tiêu đề --}}
    <div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
">

    <div>
        <h2 style="margin:0; font-weight:700;">
            Chi tiết huấn luyện viên
        </h2>

        <p style="color:#888; margin-top:6px;">
            Thông tin chi tiết của huấn luyện viên #{{ $trainer->pt_id }}
        </p>
    </div>

    <div style="display:flex; gap:10px;">

    <a href="/admin/trainers/{{ $trainer->pt_id }}/edit"
       style="
            background:#ed1c24;
            color:white;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
       ">
        ✎ Sửa thông tin
    </a>


    <form method="POST"
          action="/admin/trainers/{{ $trainer->pt_id }}/toggle-status"
          style="margin:0;">

        @csrf

        @if($trainer->trang_thai === 'hoat_dong')

            <button type="submit"
                    onclick="return confirm('Bạn có chắc muốn khóa tài khoản huấn luyện viên này?')"
                    style="
                        background:#a97900;
                        color:white;
                        border:none;
                        padding:10px 18px;
                        border-radius:6px;
                        cursor:pointer;
                    ">
                ⏸ Tạm khóa
            </button>

        @else

            <button type="submit"
                    onclick="return confirm('Bạn có chắc muốn kích hoạt tài khoản huấn luyện viên này?')"
                    style="
                        background:#008f4c;
                        color:white;
                        border:none;
                        padding:10px 18px;
                        border-radius:6px;
                        cursor:pointer;
                    ">
                ▶ Kích hoạt
            </button>

        @endif

    </form>


    <a href="/admin/trainers"
       style="
            background:#333;
            color:white;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
       ">
        ← Quay lại danh sách
    </a>

</div>

</div>


    {{-- Thông tin huấn luyện viên --}}
    <div style="
        background:#171717;
        border:1px solid #292929;
        border-radius:10px;
        padding:20px;
        margin-bottom:20px;
    ">

        <h3 style="
            margin-top:0;
            padding-bottom:15px;
            border-bottom:1px solid #292929;
        ">
            Thông tin huấn luyện viên
        </h3>

        <div style="
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:0 40px;
        ">

            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                border-bottom:1px solid #292929;
            ">
                <span style="color:#888;">
                    Mã huấn luyện viên
                </span>

                <strong>
                    #{{ $trainer->pt_id }}
                </strong>
            </div>


            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                border-bottom:1px solid #292929;
            ">
                <span style="color:#888;">
                    ID người dùng
                </span>

                <strong>
                    #{{ $trainer->nguoi_dung_id }}
                </strong>
            </div>


            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                border-bottom:1px solid #292929;
            ">
                <span style="color:#888;">
                    Họ và tên
                </span>

                <strong>
                    {{ $trainer->ho_ten }}
                </strong>
            </div>


            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                border-bottom:1px solid #292929;
            ">
                <span style="color:#888;">
                    Email
                </span>

                <strong>
                    {{ $trainer->email }}
                </strong>
            </div>


            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                border-bottom:1px solid #292929;
            ">
                <span style="color:#888;">
                    Chuyên môn
                </span>

                <strong>
                    {{ $trainer->chuyen_mon }}
                </strong>
            </div>


            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                border-bottom:1px solid #292929;
            ">
                <span style="color:#888;">
                    Số điện thoại
                </span>

                <strong>
                    {{ $trainer->so_dien_thoai ?? 'Chưa cập nhật' }}
                </strong>
            </div>


            <div style="
                display:flex;
                justify-content:space-between;
                padding:15px 0;
                grid-column:1 / -1;
            ">
                <span style="color:#888;">
                    Trạng thái tài khoản
                </span>

                @if($trainer->trang_thai === 'hoat_dong')
                    <span style="
                        background:#063d1d;
                        color:#00d26a;
                        padding:5px 12px;
                        border-radius:20px;
                    ">
                        Đang hoạt động
                    </span>
                @else
                    <span style="
                        background:#3d1010;
                        color:#ff4d4d;
                        padding:5px 12px;
                        border-radius:20px;
                    ">
                        Đã khóa
                    </span>
                @endif
            </div>

        </div>
    </div>


    {{-- Lịch PT --}}
    <div style="
        background:#171717;
        border:1px solid #292929;
        border-radius:10px;
        padding:20px;
    ">

        <h3 style="
            margin-top:0;
            padding-bottom:15px;
            border-bottom:1px solid #292929;
        ">
            Lịch PT
        </h3>

        <div style="overflow-x:auto;">

            <table style="
                width:100%;
                border-collapse:collapse;
            ">

                <thead>
                    <tr style="background:#101010;">

                        <th style="padding:14px; text-align:left;">
                            HỘI VIÊN
                        </th>

                        <th style="padding:14px; text-align:left;">
                            EMAIL
                        </th>

                        <th style="padding:14px; text-align:left;">
                            BẮT ĐẦU
                        </th>

                        <th style="padding:14px; text-align:left;">
                            KẾT THÚC
                        </th>

                        <th style="padding:14px; text-align:left;">
                            TRẠNG THÁI
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($schedules as $schedule)

                        <tr style="border-top:1px solid #292929;">

                            <td style="padding:14px;">
                                {{ $schedule->ten_hoi_vien }}
                            </td>

                            <td style="padding:14px;">
                                {{ $schedule->email_hoi_vien }}
                            </td>

                            <td style="padding:14px;">
                                {{ date('d/m/Y H:i', strtotime($schedule->thoi_gian_bat_dau)) }}
                            </td>

                            <td style="padding:14px;">
                                {{ date('d/m/Y H:i', strtotime($schedule->thoi_gian_ket_thuc)) }}
                            </td>

                            <td style="padding:14px;">

                                @if($schedule->trang_thai === 'đã hoàn thành')

                                    <span style="
                                        color:#00d26a;
                                    ">
                                        Đã hoàn thành
                                    </span>

                                @elseif($schedule->trang_thai === 'đã hủy')

                                    <span style="
                                        color:#ff4d4d;
                                    ">
                                        Đã hủy
                                    </span>

                                @else

                                    <span style="
                                        color:#ffc107;
                                    ">
                                        Đã đặt lịch
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                style="
                                    padding:30px;
                                    text-align:center;
                                    color:#777;
                                ">
                                Chưa có lịch PT.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection