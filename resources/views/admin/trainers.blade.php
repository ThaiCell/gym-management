@extends('layouts.app')

@section('content')

<style>
    .trainer-page {
        padding: 26px;
    }

    .page-header {
        margin-bottom: 22px;
    }

    .page-header h2 {
        margin: 0;
        color: #fff;
        font-size: 25px;
    }

    .page-header p {
        margin: 6px 0 0;
        color: #888;
        font-size: 14px;
    }

    .table-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
        overflow: hidden;
    }

    .search-area {
        padding: 16px 18px;
        border-bottom: 1px solid #292929;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .search-form {
        display: flex;
        gap: 10px;
        width: 500px;
        max-width: 100%;
    }

    .search-input {
        flex: 1;
        background: #222;
        border: 1px solid #363636;
        color: #fff;
        padding: 10px 13px;
        border-radius: 7px;
        outline: none;
    }

    .search-input:focus {
        border-color: #ed1c24;
    }

    .search-input::placeholder {
        color: #777;
    }

    .search-btn {
        background: #ed1c24;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 7px;
        cursor: pointer;
        font-weight: 600;
    }

    .search-btn:hover {
        background: #c9141b;
    }

    .total-count {
        color: #999;
        font-size: 14px;
        white-space: nowrap;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .trainer-table {
        width: 100%;
        border-collapse: collapse;
    }

    .trainer-table th {
        background: #111;
        color: #aaa;
        font-size: 12px;
        font-weight: 600;
        text-align: left;
        padding: 13px 16px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .trainer-table td {
        padding: 14px 16px;
        border-top: 1px solid #292929;
        color: #ddd;
        font-size: 14px;
        white-space: nowrap;
    }

    .trainer-table tbody tr:hover {
        background: #1d1d1d;
    }

    .trainer-id {
        color: #ed1c24;
        font-weight: 600;
    }

    .trainer-name {
        color: #fff;
        font-weight: 600;
    }

    .specialty {
        color: #ddd;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-active {
        background: rgba(0, 200, 83, 0.12);
        color: #00c853;
    }

    .status-locked {
        background: rgba(237, 28, 36, 0.12);
        color: #ff5252;
    }

    .detail-btn {
        display: inline-block;
        text-decoration: none;
        background: #252525;
        border: 1px solid #363636;
        color: #fff;
        padding: 7px 12px;
        border-radius: 6px;
        font-size: 12px;
    }

    .detail-btn:hover {
        background: #ed1c24;
        border-color: #ed1c24;
        color: #fff;
    }

    .empty-row {
        text-align: center !important;
        color: #777 !important;
        padding: 35px !important;
    }

    @media (max-width: 800px) {
        .search-area {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            width: 100%;
        }
    }
</style>


<div class="trainer-page">

    <div class="page-header">

        <h2>Quản lý huấn luyện viên</h2>

        <p>
            Danh sách huấn luyện viên trong hệ thống Gym Management
        </p>

    </div>


    <div class="table-card">

        <div class="search-area">

            <form
                method="GET"
                action="/admin/trainers"
                class="search-form"
            >

                <input
                    type="text"
                    name="q"
                    class="search-input"
                    placeholder="Tìm theo họ tên, email hoặc chuyên môn..."
                    value="{{ $keyword }}"
                >

                <button
                    type="submit"
                    class="search-btn"
                >
                    Tìm kiếm
                </button>

            </form>


            <div class="total-count">
                Tổng: {{ $trainers->count() }} huấn luyện viên
            </div>

        </div>


        <div class="table-wrapper">

            <table class="trainer-table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>HỌ VÀ TÊN</th>

                        <th>EMAIL</th>

                        <th>CHUYÊN MÔN</th>

                        <th>SỐ ĐIỆN THOẠI</th>

                        <th>TRẠNG THÁI</th>

                        <th>THAO TÁC</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($trainers as $trainer)

                        <tr>

                            <td>
                                <span class="trainer-id">
                                    {{ $trainer->pt_id }}
                                </span>
                            </td>


                            <td>
                                <span class="trainer-name">
                                    {{ $trainer->ho_ten }}
                                </span>
                            </td>


                            <td>
                                {{ $trainer->email }}
                            </td>


                            <td>
                                <span class="specialty">
                                    {{ $trainer->chuyen_mon ?: 'Chưa cập nhật' }}
                                </span>
                            </td>


                            <td>
                                {{ $trainer->so_dien_thoai ?: 'Chưa cập nhật' }}
                            </td>


                            <td>

                                @if($trainer->trang_thai === 'hoat_dong')

                                    <span class="status status-active">
                                        Đang hoạt động
                                    </span>

                                @else

                                    <span class="status status-locked">
                                        Đã khóa
                                    </span>

                                @endif

                            </td>


                            <td>

                                <a
                                   href="/admin/trainers/{{ $trainer->pt_id }}"
                                    class="detail-btn"
                                >
                                    Xem chi tiết
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-row"
                            >
                                Không tìm thấy huấn luyện viên.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection