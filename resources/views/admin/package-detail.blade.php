@extends('layouts.app')

@section('content')

<style>
    .package-detail-page {
        padding: 26px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .detail-header h2 {
        margin: 0;
        color: #fff;
        font-size: 25px;
    }

    .detail-header p {
        margin: 6px 0 0;
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

    .detail-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
    }

    .detail-card h3 {
        margin: 0 0 20px;
        color: #fff;
        font-size: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #292929;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 40px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #242424;
        gap: 20px;
    }

    .info-label {
        color: #888;
    }

    .info-value {
        color: #fff;
        font-weight: 500;
        text-align: right;
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

    .registration-table {
        width: 100%;
        border-collapse: collapse;
    }

    .registration-table th {
        background: #111;
        color: #aaa;
        font-size: 12px;
        text-align: left;
        padding: 13px;
        border-bottom: 1px solid #292929;
    }

    .registration-table td {
        color: #ddd;
        font-size: 13px;
        padding: 14px 13px;
        border-bottom: 1px solid #242424;
    }

    .member-name {
        color: #fff;
        font-weight: 600;
    }

    .empty-row {
        text-align: center;
        color: #777 !important;
        padding: 30px !important;
    }

    @media (max-width: 800px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .detail-card {
            overflow-x: auto;
        }

        .registration-table {
            min-width: 850px;
        }
    }
</style>

<div class="package-detail-page">
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

        <div>
            <h2>Chi tiết gói tập</h2>
            <p>
                Thông tin gói #{{ $package->goi_tap_id }}
            </p>
        </div>

       <div style="display: flex; gap: 10px; align-items: center;">

    <a
        href="/admin/packages/{{ $package->goi_tap_id }}/edit"
        class="back-btn"
        style="
            background: #ed1c24;
            border-color: #ed1c24;
        "
    >
        ✎ Sửa thông tin
    </a>


    <form
        method="POST"
        action="/admin/packages/{{ $package->goi_tap_id }}/toggle-status"
        style="margin: 0;"
    >

        @csrf

        @if($package->trang_thai === 'hoat_dong')

            <button
                type="submit"
                class="back-btn"
                style="
                    background: #7a5200;
                    border-color: #7a5200;
                    color: #fff;
                    cursor: pointer;
                "
                onclick="return confirm('Bạn có chắc muốn tạm ngưng gói tập này?')"
            >
                ⏸ Tạm ngưng
            </button>

        @else

            <button
                type="submit"
                class="back-btn"
                style="
                    background: #087f3f;
                    border-color: #087f3f;
                    color: #fff;
                    cursor: pointer;
                "
                onclick="return confirm('Bạn có chắc muốn kích hoạt lại gói tập này?')"
            >
                ▶ Kích hoạt
            </button>

        @endif

    </form>


    <a
        href="/admin/packages"
        class="back-btn"
    >
        ← Quay lại danh sách
    </a>

</div>

    </div>

    <div class="detail-card">

        <h3>Thông tin gói tập</h3>

        <div class="info-grid">

            <div class="info-row">
                <span class="info-label">Mã gói</span>
                <span class="info-value">
                    #{{ $package->goi_tap_id }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Tên gói</span>
                <span class="info-value">
                    {{ $package->ten_goi }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Giá</span>
                <span class="info-value">
                    {{ number_format($package->gia, 0, ',', '.') }} đ
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Thời hạn</span>
                <span class="info-value">
                    {{ $package->thoi_han_ngay }} ngày
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Số buổi</span>
                <span class="info-value">
                    @if($package->so_buoi !== null)
                        {{ $package->so_buoi }} buổi
                    @else
                        Không giới hạn
                    @endif
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Trạng thái</span>

                <span class="info-value">

                    @if($package->trang_thai === 'hoat_dong')

                        <span class="status status-active">
                            Đang hoạt động
                        </span>

                    @else

                        <span class="status status-inactive">
                            {{ $package->trang_thai }}
                        </span>

                    @endif

                </span>
            </div>

        </div>

    </div>

    <div class="detail-card">

        <h3>
            Hội viên đang sử dụng gói
        </h3>

        <table class="registration-table">

            <thead>
                <tr>
                    <th>HỘI VIÊN</th>
                    <th>EMAIL</th>
                    <th>BẮT ĐẦU</th>
                    <th>KẾT THÚC</th>
                    <th>BUỔI CÒN LẠI</th>
                    <th>TRẠNG THÁI</th>
                </tr>
            </thead>

            <tbody>

                @forelse($registrations as $registration)

                    <tr>

                        <td class="member-name">
                            {{ $registration->ho_ten }}
                        </td>

                        <td>
                            {{ $registration->email }}
                        </td>

                        <td>
                            {{ $registration->ngay_bat_dau }}
                        </td>

                        <td>
                            {{ $registration->ngay_ket_thuc }}
                        </td>

                        <td>
                            {{ $registration->so_buoi_con_lai }}
                        </td>

                        <td>
                            {{ $registration->trang_thai }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty-row">
                            Chưa có hội viên đăng ký gói này.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection