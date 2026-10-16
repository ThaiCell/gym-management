@extends('layouts.admin')

@section('content')
    <style>
        .pt-page {
            padding: 26px
        }

        .pt-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px
        }

        .pt-head h2 {
            margin: 0;
            font-size: 25px
        }

        .pt-head p {
            color: #888;
            margin-top: 7px
        }

        .pt-card {
            background: #171717;
            border: 1px solid #292929;
            border-radius: 12px;
            overflow: hidden
        }

        .pt-toolbar {
            padding: 16px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #292929
        }

        .pt-search {
            display: flex;
            gap: 8px;
            max-width: 500px;
            width: 100%
        }

        .pt-search input {
            background: #222;
            border: 1px solid #393939;
            color: #fff;
            padding: 11px;
            border-radius: 7px;
            flex: 1;
            min-width: 0
        }

        .pt-btn {
            display: inline-block;
            background: #e50914;
            color: white;
            border: 0;
            border-radius: 7px;
            padding: 10px 14px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600
        }

        .pt-btn:hover {
            background: #bd0710;
            color: white
        }

        .pt-danger {
            background: #681016
        }

        .pt-table {
            width: 100%;
            border-collapse: collapse
        }

        .pt-table th {
            background: #101010;
            color: #aaa;
            text-align: left;
            padding: 13px;
            font-size: 12px
        }

        .pt-table td {
            padding: 13px;
            border-top: 1px solid #292929;
            color: #ddd
        }

        .pt-status {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px
        }

        .pt-active {
            background: #10351e;
            color: #8af0ad
        }

        .pt-off {
            background: #3b2d0a;
            color: #ffdc80
        }

        .pt-actions {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap
        }

        .pt-actions form {
            margin: 0
        }

        .pt-alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px
        }

        .pt-ok {
            background: #092e18;
            color: #8af0ad
        }

        .pt-err {
            background: #351013;
            color: #ffb7bc
        }

        @media(max-width:850px) {
            .pt-card {
                overflow-x: auto
            }

            .pt-table {
                min-width: 900px
            }

            .pt-head,
            .pt-toolbar {
                flex-direction: column;
                align-items: stretch
            }
        }
    </style>

    <div class="pt-page">
        <div class="pt-head">
            <div>
                <h2>Quản lý gói PT</h2>
                <p>Thêm, sửa và quản lý các gói huấn luyện cá nhân</p>
            </div>
            <a class="pt-btn" href="/admin/pt-packages/create">+ Thêm gói PT</a>
        </div>

        @if(session('success'))
            <div class="pt-alert pt-ok">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="pt-alert pt-err">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="pt-alert pt-err">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="pt-card">
            <div class="pt-toolbar">
                <form class="pt-search" method="GET" action="/admin/pt-packages">
                    <input name="q" value="{{ $keyword }}" placeholder="Tìm tên gói PT">
                    <button class="pt-btn" type="submit">Tìm</button>
                </form>
                <span>Tổng: {{ $packages->count() }} gói</span>
            </div>

            <table class="pt-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ẢNH</th>
                        <th>TÊN GÓI</th>
                        <th>GIÁ</th>
                        <th>SỐ BUỔI</th>
                        <th>THỜI HẠN</th>
                        <th>TRẠNG THÁI</th>
                        <th>THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>#{{ $package->goi_pt_id }}</td>
                            <td>
                                @if(!empty($package->anh))
                                    <img src="{{ asset($package->anh) }}" alt="Ảnh {{ $package->ten_goi_pt }}"
                                        style="width:64px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #333">
                                @else
                                    <span style="color:#777">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td><strong>{{ $package->ten_goi_pt }}</strong></td>
                            <td>{{ number_format($package->gia, 0, ',', '.') }} đ</td>
                            <td>{{ $package->so_buoi }} buổi</td>
                            <td>{{ $package->thoi_han_ngay }} ngày</td>
                            <td>
                                @if($package->trang_thai === 'hoat_dong')
                                    <span class="pt-status pt-active">Hoạt động</span>
                                @else
                                    <span class="pt-status pt-off">Tạm ngưng</span>
                                @endif
                            </td>
                            <td>
                                <div class="pt-actions">
                                    <a class="pt-btn" href="/admin/pt-packages/{{ $package->goi_pt_id }}/edit">Sửa</a>

                                    <form method="POST" action="/admin/pt-packages/{{ $package->goi_pt_id }}/delete"
                                        onsubmit="return confirm('Chỉ xóa được khi gói chưa có đăng ký hoặc hóa đơn. Tiếp tục?')">
                                        @csrf
                                        <button class="pt-btn pt-danger" type="submit">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:35px;color:#888">
                                Chưa có gói PT nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection