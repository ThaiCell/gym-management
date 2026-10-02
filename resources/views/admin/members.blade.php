@extends('layouts.app')

@section('content')

<style>
    .members-page {
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
        font-size: 25px;
        color: #fff;
    }

    .page-title p {
        margin: 6px 0 0;
        color: #888;
        font-size: 14px;
    }

    .member-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
        overflow: hidden;
    }

    .member-toolbar {
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
        width: 450px;
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

    .member-count {
        color: #999;
        font-size: 14px;
    }

    .member-table {
        width: 100%;
        border-collapse: collapse;
    }

    .member-table th {
        background: #111;
        color: #aaa;
        font-size: 13px;
        font-weight: 500;
        text-align: left;
        padding: 15px 18px;
        border-bottom: 1px solid #292929;
    }

    .member-table td {
        padding: 15px 18px;
        color: #ddd;
        font-size: 14px;
        border-bottom: 1px solid #252525;
    }

    .member-table tbody tr:hover {
        background: #1d1d1d;
    }

    .member-id {
        color: #ed1c24;
        font-weight: 600;
    }

    .member-name {
        color: #fff;
        font-weight: 600;
    }

    .member-email {
        color: #aaa;
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
        .member-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            width: 100%;
        }

        .member-card {
            overflow-x: auto;
        }

        .member-table {
            min-width: 850px;
        }
    }
    .member-link {
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }

    .member-link:hover {
        color: #ed1c24;
    }

</style>

<div class="members-page">

    <div class="page-header">
        <div class="page-title">
            <h2>Quản lý thành viên</h2>
            <p>Danh sách hội viên trong hệ thống Gym Management</p>
        </div>
    </div>

    <div class="member-card">

        <div class="member-toolbar">

            <form action="/admin/members" method="GET" class="search-form">

                <input
                    type="text"
                    name="q"
                    value="{{ $keyword }}"
                    placeholder="Tìm theo họ tên, email hoặc số điện thoại..."
                >

                <button type="submit" class="search-btn">
                    Tìm kiếm
                </button>

            </form>

            <div class="member-count">
                Tổng: <strong>{{ $hoiVien->count() }}</strong> hội viên
            </div>

        </div>

        <table class="member-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>HỌ VÀ TÊN</th>
                    <th>EMAIL</th>
                    <th>SỐ ĐIỆN THOẠI</th>
                    <th>NGÀY THAM GIA</th>
                    <th>TRẠNG THÁI</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($hoiVien as $member)

                    <tr>

                        <td>
                            <span class="member-id">
                                #{{ $member->hoi_vien_id }}
                            </span>
                        </td>

                        <td>
                            <a href="/admin/members/{{ $member->hoi_vien_id }}"
                            class="member-link">
                                {{ $member->ho_ten }}
                            </a>
                        </td>

                        <td>
                            <span class="member-email">
                                {{ $member->email }}
                            </span>
                        </td>

                        <td>
                            {{ $member->so_dien_thoai ?? 'Chưa cập nhật' }}
                        </td>

                        <td>
                            {{ $member->ngay_tham_gia ?? 'Chưa cập nhật' }}
                        </td>

                        <td>

                            @if ($member->trang_thai === 'hoat_dong')

                                <span class="status status-active">
                                    Đang hoạt động
                                </span>

                            @else

                                <span class="status status-inactive">
                                    {{ $member->trang_thai }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty-row">
                            Không tìm thấy hội viên nào.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection