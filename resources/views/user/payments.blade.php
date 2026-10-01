@extends('layouts.member')

@section('title', 'Thanh toán - GYM MANAGEMENT')

@section('menu')

    <a href="/">
        TRANG CHỦ
    </a>

    <a href="/about">
        GIỚI THIỆU
    </a>

    <a href="/packages">
        GÓI TẬP
    </a>

    <a href="/classes">
        LỚP TẬP
    </a>

    <a href="/trainers">
        HUẤN LUYỆN VIÊN
    </a>

    <a href="/contact">
        LIÊN HỆ
    </a>

@endsection

@section('content')

<style>

    .payment-page {
        color: #fff;
    }

    /* =========================
       HEADER
    ========================= */

    .payment-header {
        margin-bottom: 35px;
    }

    .payment-label {
        color: #e50914;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        margin-bottom: 8px;
    }

    .payment-header h1 {
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .payment-header p {
        color: #888;
        font-size: 14px;
    }


    /* =========================
       SUMMARY
    ========================= */

    .payment-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: #111;
        border: 1px solid #252525;
        border-radius: 7px;
        padding: 22px;
        transition: .3s;
    }

    .summary-card:hover {
        border-color: #e50914;
        transform: translateY(-3px);
    }

    .summary-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(229, 9, 20, .12);
        border: 1px solid rgba(229, 9, 20, .25);
        border-radius: 6px;
        font-size: 19px;
    }

    .summary-title {
        color: #888;
        font-size: 12px;
    }

    .summary-value {
        font-size: 25px;
        font-weight: 800;
    }


    /* =========================
       INVOICE LIST
    ========================= */

    .invoice-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .invoice-card {
        background: #111;
        border: 1px solid #252525;
        border-radius: 7px;
        overflow: hidden;
        transition: .3s;
    }

    .invoice-card:hover {
        border-color: #333;
    }


    /* =========================
       INVOICE TOP
    ========================= */

    .invoice-top {
        padding: 22px 25px;
        border-bottom: 1px solid #252525;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .invoice-number {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .invoice-icon {
        width: 45px;
        height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e50914;
        border-radius: 6px;

        font-size: 20px;
    }

    .invoice-number small {
        display: block;
        color: #777;
        font-size: 10px;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .invoice-number strong {
        font-size: 16px;
    }


    /* =========================
       STATUS
    ========================= */

    .invoice-status {
        padding: 7px 13px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-paid {
        background: rgba(40, 180, 90, .12);
        border: 1px solid rgba(40, 180, 90, .3);
        color: #4cd97b;
    }

    .status-pending {
        background: rgba(255, 180, 0, .1);
        border: 1px solid rgba(255, 180, 0, .3);
        color: #ffbd2e;
    }

    .status-cancel {
        background: rgba(229, 9, 20, .1);
        border: 1px solid rgba(229, 9, 20, .3);
        color: #ff4a54;
    }


    /* =========================
       BODY
    ========================= */

    .invoice-body {
        padding: 22px 25px;
    }

    .invoice-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .invoice-info-item small {
        display: block;
        color: #666;
        font-size: 10px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .invoice-info-item strong {
        font-size: 13px;
        color: #ddd;
    }


    /* =========================
       DETAILS
    ========================= */

    .invoice-details {
        border-top: 1px solid #252525;
        padding-top: 18px;
    }

    .invoice-details-title {
        color: #e50914;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 12px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;

        padding: 11px 0;

        border-bottom: 1px solid #1f1f1f;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-name {
        color: #ddd;
        font-size: 13px;
    }

    .detail-name small {
        display: block;
        color: #666;
        font-size: 10px;
        margin-top: 3px;
    }

    .detail-price {
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }


    /* =========================
       TOTAL
    ========================= */

    .invoice-total {
        margin-top: 18px;
        padding-top: 18px;

        border-top: 1px solid #333;

        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .invoice-total span {
        color: #888;
        font-size: 13px;
    }

    .invoice-total strong {
        color: #e50914;
        font-size: 22px;
        font-weight: 900;
    }


    /* =========================
       EMPTY
    ========================= */

    .payment-empty {
        background: #111;
        border: 1px solid #252525;
        border-radius: 7px;
        padding: 70px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 20px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #181818;
        border-radius: 50%;

        font-size: 30px;
    }

    .payment-empty h2 {
        font-size: 20px;
        margin-bottom: 8px;
    }

    .payment-empty p {
        color: #777;
        font-size: 13px;
        margin-bottom: 22px;
    }

    .empty-btn {
        display: inline-block;
        padding: 11px 20px;

        background: #e50914;
        color: #fff;

        border-radius: 5px;

        font-size: 12px;
        font-weight: 800;

        transition: .3s;
    }

    .empty-btn:hover {
        background: #ff2633;
        color: #fff;
        transform: translateY(-2px);
    }


    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {

        .payment-summary {
            grid-template-columns: 1fr;
        }

        .invoice-info {
            grid-template-columns: 1fr 1fr;
        }

    }

    @media (max-width: 600px) {

        .payment-header h1 {
            font-size: 27px;
        }

        .invoice-top {
            align-items: flex-start;
            flex-direction: column;
        }

        .invoice-info {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .invoice-body,
        .invoice-top {
            padding: 18px;
        }

        .invoice-total strong {
            font-size: 19px;
        }

    }

</style>


<div class="payment-page">


    {{-- =========================
         HEADER
    ========================= --}}

    <div class="payment-header">

        <div class="payment-label">
            GYMFIT PAYMENT
        </div>

        <h1>
            THANH TOÁN & HÓA ĐƠN
        </h1>

        <p>
            Theo dõi các hóa đơn và lịch sử thanh toán của bạn.
        </p>

    </div>


    {{-- =========================
         SUMMARY
    ========================= --}}

    @php

        $tongHoaDon = $hoaDon->count();

        $tongDaThanhToan = $hoaDon
            ->filter(function ($item) {
                return in_array(
                    strtolower($item->trang_thai),
                    [
                        'đã thanh toán',
                        'da thanh toan',
                        'da_thanh_toan',
                        'paid'
                    ]
                );
            })
            ->sum('tong_tien');

        $tongTatCa = $hoaDon->sum('tong_tien');

    @endphp


    <div class="payment-summary">

        <div class="summary-card">

            <div class="summary-top">

                <span class="summary-title">
                    TỔNG HÓA ĐƠN
                </span>

                <div class="summary-icon">
                    🧾
                </div>

            </div>

            <div class="summary-value">
                {{ $tongHoaDon }}
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <span class="summary-title">
                    ĐÃ THANH TOÁN
                </span>

                <div class="summary-icon">
                    ✓
                </div>

            </div>

            <div class="summary-value">
                {{ number_format($tongDaThanhToan, 0, ',', '.') }} ₫
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <span class="summary-title">
                    TỔNG GIÁ TRỊ
                </span>

                <div class="summary-icon">
                    💰
                </div>

            </div>

            <div class="summary-value">
                {{ number_format($tongTatCa, 0, ',', '.') }} ₫
            </div>

        </div>

    </div>


    {{-- =========================
         DANH SÁCH HÓA ĐƠN
    ========================= --}}

    @if($hoaDon->count() > 0)

        <div class="invoice-list">

            @foreach($hoaDon as $hd)

                @php

                    $status = strtolower($hd->trang_thai);

                    if (
                        in_array(
                            $status,
                            [
                                'đã thanh toán',
                                'da thanh toan',
                                'da_thanh_toan',
                                'paid'
                            ]
                        )
                    ) {

                        $statusClass = 'status-paid';

                    } elseif (
                        in_array(
                            $status,
                            [
                                'chờ thanh toán',
                                'cho thanh toan',
                                'cho_thanh_toan',
                                'pending'
                            ]
                        )
                    ) {

                        $statusClass = 'status-pending';

                    } else {

                        $statusClass = 'status-cancel';

                    }

                @endphp


                <div class="invoice-card">


                    {{-- =========================
                         TOP
                    ========================= --}}

                    <div class="invoice-top">

                        <div class="invoice-number">

                            <div class="invoice-icon">
                                🧾
                            </div>

                            <div>

                                <small>
                                    Mã hóa đơn
                                </small>

                                <strong>
                                    #HD{{ str_pad($hd->hoa_don_id, 5, '0', STR_PAD_LEFT) }}
                                </strong>

                            </div>

                        </div>


                        <span class="invoice-status {{ $statusClass }}">

                            {{ $hd->trang_thai }}

                        </span>

                    </div>


                    {{-- =========================
                         BODY
                    ========================= --}}

                    <div class="invoice-body">


                        <div class="invoice-info">

                            <div class="invoice-info-item">

                                <small>
                                    Ngày lập
                                </small>

                                <strong>

                                    {{ \Carbon\Carbon::parse($hd->ngay_lap)->format('d/m/Y H:i') }}

                                </strong>

                            </div>


                            <div class="invoice-info-item">

                                <small>
                                    Phương thức
                                </small>

                                <strong>

                                    {{ $hd->phuong_thuc_thanh_toan ?: 'Chưa cập nhật' }}

                                </strong>

                            </div>


                            <div class="invoice-info-item">

                                <small>
                                    Tổng tiền
                                </small>

                                <strong>

                                    {{ number_format($hd->tong_tien, 0, ',', '.') }} ₫

                                </strong>

                            </div>

                        </div>


                        {{-- =========================
                             CHI TIẾT
                        ========================= --}}

                        <div class="invoice-details">

                            <div class="invoice-details-title">
                                CHI TIẾT HÓA ĐƠN
                            </div>


                            @forelse(
                                $chiTietHoaDon->get($hd->hoa_don_id, collect())
                                as $ct
                            )

                                @php

                                    $tenSanPham =
                                        $ct->ten_goi
                                        ?: $ct->ten_goi_pt
                                        ?: 'Dịch vụ GymFit';

                                @endphp


                                <div class="detail-row">

                                    <div class="detail-name">

                                        {{ $tenSanPham }}

                                        <small>
                                            Số lượng: {{ $ct->so_luong }}
                                        </small>

                                    </div>

                                    <div class="detail-price">

                                        {{ number_format($ct->thanh_tien, 0, ',', '.') }} ₫

                                    </div>

                                </div>

                            @empty

                                <div
                                    style="
                                        color:#666;
                                        font-size:12px;
                                        padding:10px 0;
                                    "
                                >
                                    Chưa có chi tiết hóa đơn.
                                </div>

                            @endforelse


                        </div>


                        {{-- =========================
                             TOTAL
                        ========================= --}}

                        <div class="invoice-total">

                            <span>
                                TỔNG THANH TOÁN
                            </span>

                            <strong>
                                {{ number_format($hd->tong_tien, 0, ',', '.') }} ₫
                            </strong>

                        </div>


                    </div>

                </div>

            @endforeach

        </div>


    @else

        {{-- =========================
             KHÔNG CÓ HÓA ĐƠN
        ========================= --}}

        <div class="payment-empty">

            <div class="empty-icon">
                🧾
            </div>

            <h2>
                Chưa có hóa đơn
            </h2>

            <p>
                Hiện tại bạn chưa có hóa đơn hoặc giao dịch thanh toán nào.
            </p>

            <a href="/packages" class="empty-btn">
                XEM GÓI TẬP
            </a>

        </div>

    @endif


</div>

@endsection
