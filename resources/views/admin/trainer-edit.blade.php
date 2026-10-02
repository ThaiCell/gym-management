@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:25px;
    ">
        <div>
            <h2 style="margin:0; font-weight:700;">
                Sửa thông tin huấn luyện viên
            </h2>

            <p style="color:#888; margin-top:6px;">
                Cập nhật thông tin huấn luyện viên #{{ $trainer->pt_id }}
            </p>
        </div>

        <a href="/admin/trainers/{{ $trainer->pt_id }}"
           style="
                background:#333;
                color:white;
                padding:10px 18px;
                border-radius:6px;
                text-decoration:none;
           ">
            ← Quay lại
        </a>
    </div>


    @if(session('error'))
        <div style="
            background:#3d1010;
            color:#ff6b6b;
            border:1px solid #8b2020;
            padding:12px 15px;
            border-radius:6px;
            margin-bottom:20px;
        ">
            {{ session('error') }}
        </div>
    @endif


    @if($errors->any())
        <div style="
            background:#3d1010;
            color:#ff6b6b;
            border:1px solid #8b2020;
            padding:12px 15px;
            border-radius:6px;
            margin-bottom:20px;
        ">
            <strong>Có lỗi:</strong>

            <ul style="margin:8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST"
          action="/admin/trainers/{{ $trainer->pt_id }}/edit">

        @csrf

        <div style="
            background:#171717;
            border:1px solid #292929;
            border-radius:10px;
            padding:25px;
        ">

            <h3 style="
                margin-top:0;
                padding-bottom:15px;
                border-bottom:1px solid #292929;
            ">
                Thông tin huấn luyện viên
            </h3>


            {{-- Họ tên --}}
            <div style="margin-bottom:20px;">

                <label style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:600;
                ">
                    Họ và tên
                </label>

                <input
                    type="text"
                    name="ho_ten"
                    value="{{ old('ho_ten', $trainer->ho_ten) }}"
                    required
                    style="
                        width:100%;
                        box-sizing:border-box;
                        background:#222;
                        border:1px solid #444;
                        color:white;
                        padding:12px;
                        border-radius:6px;
                    "
                >

            </div>


            {{-- Email --}}
            <div style="margin-bottom:20px;">

                <label style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:600;
                ">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $trainer->email) }}"
                    required
                    style="
                        width:100%;
                        box-sizing:border-box;
                        background:#222;
                        border:1px solid #444;
                        color:white;
                        padding:12px;
                        border-radius:6px;
                    "
                >

            </div>


            {{-- Số điện thoại --}}
            <div style="margin-bottom:20px;">

                <label style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:600;
                ">
                    Số điện thoại
                </label>

                <input
                    type="text"
                    name="so_dien_thoai"
                    value="{{ old('so_dien_thoai', $trainer->so_dien_thoai) }}"
                    style="
                        width:100%;
                        box-sizing:border-box;
                        background:#222;
                        border:1px solid #444;
                        color:white;
                        padding:12px;
                        border-radius:6px;
                    "
                >

            </div>


            {{-- Chuyên môn --}}
            <div style="margin-bottom:25px;">

                <label style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:600;
                ">
                    Chuyên môn
                </label>

                <input
                    type="text"
                    name="chuyen_mon"
                    value="{{ old('chuyen_mon', $trainer->chuyen_mon) }}"
                    required
                    style="
                        width:100%;
                        box-sizing:border-box;
                        background:#222;
                        border:1px solid #444;
                        color:white;
                        padding:12px;
                        border-radius:6px;
                    "
                >

            </div>


            <div style="
                display:flex;
                justify-content:flex-end;
                gap:10px;
            ">

                <a href="/admin/trainers/{{ $trainer->pt_id }}"
                   style="
                        background:#333;
                        color:white;
                        padding:11px 20px;
                        border-radius:6px;
                        text-decoration:none;
                   ">
                    Hủy
                </a>

                <button
                    type="submit"
                    style="
                        background:#ed1c24;
                        color:white;
                        border:none;
                        padding:11px 22px;
                        border-radius:6px;
                        cursor:pointer;
                        font-weight:600;
                    ">
                    Lưu thay đổi
                </button>

            </div>

        </div>

    </form>

</div>

@endsection