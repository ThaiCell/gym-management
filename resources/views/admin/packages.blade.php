@extends('layouts.app')

@section('content')

<style>
    .packages-page {
        padding: 26px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .page-title h2 {
        margin: 0;
        color: #fff;
        font-size: 25px;
    }

    .page-title p {
        margin: 6px 0 0;
        color: #888;
        font-size: 14px;
    }

    .package-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
        overflow: hidden;
    }

    .package-toolbar {
        padding: 18px 20px;
        border-bottom: 1px solid #292929;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .search-form {
        display: flex;
        gap: 10px;
        width: 430px;
        max-width: 100%;
    }

    .search-form input {
        flex: 1;
        background: #222;
        border: 1px solid #333;
        color: #fff;
        border-radius: 7px;
        padding: 10px 13px;
        outline: none;
    }

    .search-form input:focus {
        border-color: #ed1c24;
    }

    .search-btn {
        background: #ed1c24;
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 10px 18px;
        cursor: pointer;
    }

    .search-btn:hover {
        background: #c9141b;
    }

    .package-count {
        color: #999;
        font-size: 14px;
    }

    .package-table {
        width: 100%;
        border-collapse: collapse;
    }

    .package-table th {
        background: #111;
        color: #aaa;
        font-size: 13px;
        font-weight: 500;
        text-align: left;
        padding: 15px 18px;
        border-bottom: 1px solid #292929;
    }

    .package-table td {
        padding: 15px 18px;
        color: #ddd;
        font-size: 14px;
        border-bottom: 1px solid #252525;
    }

    .package-table tbody tr:hover {
        background: #1d1d1d;
    }

    .package-id {
        color: #ed1c24;
        font-weight: 600;
    }

    .package-name {
        color: #fff;
        font-weight: 600;
    }

    .package-price {
        color: #fff;
        font-weight: 600;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .status-active {
        background: rgba(0, 200, 83, 0.12);
        color: #00c853;
    }

    .status-inactive {
        background: rgba(255, 193, 7, 0.12);
        color: #ffc107;
    }

    .empty-row {
        text-align: center !important;
        padding: 45px !important;
        color: #777 !important;
    }

    @media (max-width: 900px) {
        .package-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            width: 100%;
        }

        .package-card {
            overflow-x: auto;
        }

        .package-table {
            min-width: 850px;
        }
    }
    .package-link {
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }

    .package-link:hover {
        color: #ed1c24;
    }
    .add-package-btn {
    display: inline-block;
    background: #ed1c24;
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 7px;
    font-weight: 600;
}

.add-package-btn:hover {
    background: #c9141b;
    color: #fff;
}

</style>

<div class="packages-page">

    <div class="page-header">

        <div class="page-title">
            
            <h2>Quản lý gói tập</h2>
                
            <p>Danh sách các gói tập trong hệ thống Gym Management</p>
            
        </div>
            <a href="/admin/packages/create" class="add-package-btn">
                + Thêm gói tập
             </a>
    </div>

    <div class="package-card">

        <div class="package-toolbar">

            <form
                action="/admin/packages"
                method="GET"
                class="search-form"
            >

                <input
                    type="text"
                    name="q"
                    value="{{ $keyword }}"
                    placeholder="Tìm theo tên gói tập..."
                >

                <button type="submit" class="search-btn">
                    Tìm kiếm
                </button>

            </form>

            <div class="package-count">
                Tổng:
                <strong>{{ $packages->count() }}</strong>
                gói tập
            </div>

        </div>

        <table class="package-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>TÊN GÓI</th>
                    <th>GIÁ</th>
                    <th>THỜI HẠN</th>
                    <th>SỐ BUỔI</th>
                    <th>TRẠNG THÁI</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($packages as $package)

                    <tr>

                        <td>
                            <span class="package-id">
                                #{{ $package->goi_tap_id }}
                            </span>
                        </td>

                        <td>
                            <a
                                href="/admin/packages/{{ $package->goi_tap_id }}"
                                class="package-link"
                            >
                                {{ $package->ten_goi }}
                            </a>
                        </td>

                        <td>
                            <span class="package-price">
                                {{ number_format($package->gia, 0, ',', '.') }} đ
                            </span>
                        </td>

                        <td>
                            {{ $package->thoi_han_ngay }} ngày
                        </td>

                        <td>
                            @if($package->so_buoi !== null)
                                {{ $package->so_buoi }} buổi
                            @else
                                Không giới hạn
                            @endif
                        </td>

                        <td>

                            @if($package->trang_thai === 'hoat_dong')

                                <span class="status status-active">
                                    Đang hoạt động
                                </span>

                            @else

                                <span class="status status-inactive">
                                    {{ $package->trang_thai }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty-row">
                            Không tìm thấy gói tập nào.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection