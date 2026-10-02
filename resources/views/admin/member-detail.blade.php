@extends('layouts.app')

@section('content')

<style>
    .member-detail-page {
        padding: 26px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .detail-title h2 {
        margin: 0;
        color: #fff;
        font-size: 25px;
    }

    .detail-title p {
        margin-top: 6px;
        color: #888;
        font-size: 14px;
    }

    .back-btn {
        text-decoration: none;
        background: #252525;
        color: #fff;
        padding: 10px 16px;
        border-radius: 7px;
        border: 1px solid #333;
    }

    .back-btn:hover {
        background: #333;
        color: #fff;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
        padding: 22px;
    }

    .detail-card h3 {
        margin: 0 0 20px;
        color: #fff;
        font-size: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #292929;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #242424;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #888;
    }

    .info-value {
        color: #fff;
        text-align: right;
        font-weight: 500;
    }

    .status {
        display: inline-block;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
    }

    .status-active {
        background: rgba(0, 200, 83, 0.12);
        color: #00c853;
    }

    .status-other {
        background: rgba(255, 193, 7, 0.12);
        color: #ffc107;
    }

    .package-card {
        margin-top: 20px;
    }

    .package-table {
        width: 100%;
        border-collapse: collapse;
    }

    .package-table th {
        text-align: left;
        color: #888;
        font-size: 12px;
        padding: 12px 8px;
        border-bottom: 1px solid #292929;
    }

    .package-table td {
        color: #ddd;
        font-size: 13px;
        padding: 14px 8px;
        border-bottom: 1px solid #242424;
    }

    .package-name {
        color: #fff;
        font-weight: 600;
    }

    .empty-package {
        color: #777;
        text-align: center;
        padding: 25px !important;
    }

    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-header {
            gap: 15px;
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="member-detail-page">


            @if(session('success'))

            <div style="
                background: rgba(0, 200, 83, 0.12);
                border: 1px solid rgba(0, 200, 83, 0.35);
                color: #00c853;
                padding: 12px 15px;
                border-radius: 7px;
                margin-bottom: 18px;
            ">
                {{ session('success') }}
            </div>

        @endif
    <div class="detail-header">

        <div class="detail-title">
            <h2>Chi tiết hội viên</h2>
            <p>Thông tin chi tiết của hội viên #{{ $member->hoi_vien_id }}</p>
        </div>

       <div style="display: flex; gap: 10px; flex-wrap: wrap;">

            <a
                href="/admin/members/{{ $member->hoi_vien_id }}/edit"
                class="back-btn"
                style="background: #ed1c24; border-color: #ed1c24;"
            >
                ✎ Sửa thông tin
            </a>

            <form
                action="/admin/members/{{ $member->hoi_vien_id }}/toggle-status"
                method="POST"
                style="margin: 0;"
                onsubmit="return confirm(
                    '{{ $member->trang_thai === 'hoat_dong'
                        ? 'Bạn có chắc muốn KHÓA tài khoản hội viên này?'
                        : 'Bạn có chắc muốn MỞ KHÓA tài khoản hội viên này?' }}'
                );"
            >

                @csrf

                @if($member->trang_thai === 'hoat_dong')

                    <button
                        type="submit"
                        class="back-btn"
                        style="
                            background: #5a1a1a;
                            border-color: #7a2222;
                            cursor: pointer;
                        "
                    >
                        🔒 Khóa tài khoản
                    </button>

                @else

                    <button
                        type="submit"
                        class="back-btn"
                        style="
                            background: #064e3b;
                            border-color: #087f5b;
                            cursor: pointer;
                        "
                    >
                        🔓 Mở khóa tài khoản
                    </button>

                @endif

            </form>

            <a
                href="/admin/members"
                class="back-btn"
            >
                ← Quay lại danh sách
            </a>

        </div>

    </div>

    <div class="detail-grid">

        {{-- THÔNG TIN CÁ NHÂN --}}
        <div class="detail-card">

            <h3>Thông tin cá nhân</h3>

            <div class="info-row">
                <span class="info-label">Mã hội viên</span>
                <span class="info-value">
                    #{{ $member->hoi_vien_id }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Họ và tên</span>
                <span class="info-value">
                    {{ $member->ho_ten }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">
                    {{ $member->email }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Số điện thoại</span>
                <span class="info-value">
                    {{ $member->so_dien_thoai ?? 'Chưa cập nhật' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Ngày sinh</span>
                <span class="info-value">
                    {{ $member->ngay_sinh ?? 'Chưa cập nhật' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Địa chỉ</span>
                <span class="info-value">
                    {{ $member->dia_chi ?? 'Chưa cập nhật' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Ngày tham gia</span>
                <span class="info-value">
                    {{ $member->ngay_tham_gia ?? 'Chưa cập nhật' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Trạng thái</span>

                <span class="info-value">

                    @if ($member->trang_thai === 'hoat_dong')

                        <span class="status status-active">
                            Đang hoạt động
                        </span>

                    @else

                        <span class="status status-other">
                            {{ $member->trang_thai }}
                        </span>

                    @endif

                </span>
            </div>

        </div>

        {{-- THÔNG TIN TÀI KHOẢN --}}
        <div class="detail-card">

            <h3>Thông tin tài khoản</h3>

            <div class="info-row">
                <span class="info-label">ID người dùng</span>
                <span class="info-value">
                    #{{ $member->nguoi_dung_id }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Email đăng nhập</span>
                <span class="info-value">
                    {{ $member->email }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Trạng thái tài khoản</span>

                <span class="info-value">

                    @if ($member->trang_thai === 'hoat_dong')

                        <span class="status status-active">
                            Đang hoạt động
                        </span>

                    @else

                        <span class="status status-other">
                            {{ $member->trang_thai }}
                        </span>

                    @endif

                </span>
            </div>

        </div>

    </div>

    {{-- GÓI TẬP --}}
    <div class="detail-card package-card">

        <h3>Gói tập của hội viên</h3>

        <table class="package-table">

            <thead>
                <tr>
                    <th>TÊN GÓI</th>
                    <th>GIÁ</th>
                    <th>BẮT ĐẦU</th>
                    <th>KẾT THÚC</th>
                    <th>BUỔI CÒN LẠI</th>
                    <th>TRẠNG THÁI</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($packages as $package)

                    <tr>

                        <td class="package-name">
                            {{ $package->ten_goi }}
                        </td>

                        <td>
                            {{ number_format($package->gia, 0, ',', '.') }} đ
                        </td>

                        <td>
                            {{ $package->ngay_bat_dau }}
                        </td>

                        <td>
                            {{ $package->ngay_ket_thuc }}
                        </td>

                        <td>
                            {{ $package->so_buoi_con_lai ?? 'Không giới hạn' }}
                        </td>

                        <td>
                            {{ $package->trang_thai }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty-package">
                            Hội viên này chưa đăng ký gói tập.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection