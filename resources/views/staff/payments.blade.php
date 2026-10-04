@extends('layouts.member')

@section('title', 'Quản lý hóa đơn - GYM MANAGEMENT')
@section('role_name', 'Nhân viên')



@section('content')

    <style>
        .staff-payment-page {
            max-width: 1400px;
            margin: auto;
            color: #fff;
        }

        .payment-header {
            margin-bottom: 30px;
        }

        .payment-label {
            color: #e50914;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .payment-header h1 {
            margin: 0;
            font-size: 34px;
            font-weight: 900;
        }

        .payment-header p {
            color: #777;
            font-size: 13px;
            margin-top: 10px;
        }


        /* =========================
           ALERT
        ========================= */

        .payment-alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 13px;
            font-weight: 700;
        }

        .payment-success {
            background: rgba(40, 180, 90, .1);
            border: 1px solid rgba(40, 180, 90, .35);
            color: #4cd97b;
        }

        .payment-error {
            background: rgba(229, 9, 20, .1);
            border: 1px solid rgba(229, 9, 20, .35);
            color: #ff5c64;
        }


        /* =========================
           SUMMARY
        ========================= */

        .payment-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 14px;
            padding: 22px;
        }

        .summary-title {
            color: #777;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .summary-value {
            font-size: 27px;
            font-weight: 900;
        }

        .summary-value.red {
            color: #e50914;
        }

        .summary-value.green {
            color: #4cd97b;
        }


        /* =========================
           INVOICE
        ========================= */

        .invoice-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .invoice-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 15px;
            overflow: hidden;
            transition: .25s;
        }

        .invoice-card:hover {
            border-color: #3a3a3a;
        }

        .invoice-top {
            padding: 22px 25px;
            border-bottom: 1px solid #222;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .invoice-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .invoice-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;

            background: #e50914;
            border-radius: 10px;

            font-size: 21px;
        }

        .invoice-code small {
            display: block;
            color: #666;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .invoice-code strong {
            font-size: 17px;
        }

        .invoice-status {
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-pending {
            background: rgba(255, 190, 0, .1);
            border: 1px solid rgba(255, 190, 0, .3);
            color: #ffc233;
        }

        .status-paid {
            background: rgba(40, 180, 90, .1);
            border: 1px solid rgba(40, 180, 90, .3);
            color: #4cd97b;
        }

        .status-other {
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
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 22px;
        }

        .info-label {
            display: block;
            color: #666;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .info-value {
            color: #ddd;
            font-size: 13px;
            font-weight: 700;
        }


        /* =========================
           DETAIL
        ========================= */

        .detail-box {
            border-top: 1px solid #222;
            padding-top: 18px;
        }

        .detail-title {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            padding: 11px 0;
            border-bottom: 1px solid #1e1e1e;
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
            font-weight: 800;
            white-space: nowrap;
        }


        /* =========================
           CONFIRM
        ========================= */

        .payment-action {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid #252525;
        }

        .payment-form {
            display: flex;
            align-items: end;
            gap: 12px;
        }

        .payment-field {
            flex: 1;
        }

        .payment-field label {
            display: block;
            color: #888;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .payment-field select {
            width: 100%;
            padding: 12px 13px;
            background: #191919;
            color: #fff;
            border: 1px solid #333;
            border-radius: 7px;
            outline: none;
        }

        .payment-field select:focus {
            border-color: #e50914;
        }

        .confirm-btn {
            border: none;
            padding: 12px 20px;
            background: #e50914;
            color: #fff;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            transition: .25s;
        }

        .confirm-btn:hover {
            background: #ff202b;
            transform: translateY(-2px);
        }


        /* =========================
           EMPTY
        ========================= */

        .payment-empty {
            padding: 70px 25px;
            text-align: center;
            background: #111;
            border: 1px solid #252525;
            border-radius: 15px;
        }

        .payment-empty-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .payment-empty h2 {
            margin: 0 0 7px;
            font-size: 20px;
        }

        .payment-empty p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .payment-summary {
                grid-template-columns: 1fr;
            }

            .invoice-info {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 650px) {

            .payment-header h1 {
                font-size: 27px;
            }

            .invoice-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .invoice-info {
                grid-template-columns: 1fr;
            }

            .payment-form {
                align-items: stretch;
                flex-direction: column;
            }

            .confirm-btn {
                width: 100%;
            }

            .invoice-body,
            .invoice-top {
                padding: 18px;
            }

        }
    </style>


    <div class="staff-payment-page">


        {{-- HEADER --}}

        <div class="payment-header">

            <div class="payment-label">
                GYMFIT • PAYMENT MANAGEMENT
            </div>

            <h1>
                QUẢN LÝ HÓA ĐƠN
            </h1>

            <p>
                Kiểm tra và xác nhận thanh toán của hội viên.
            </p>

        </div>


        {{-- ALERT --}}

        @if (session('success'))
            <div class="payment-alert payment-success">
                ✓ {{ session('success') }}
            </div>
        @endif


        @if (session('error'))
            <div class="payment-alert payment-error">
                ⚠ {{ session('error') }}
            </div>
        @endif


        {{-- SUMMARY --}}

        @php

            $tongHoaDon = $hoaDon->count();

            $choThanhToan = $hoaDon
                ->filter(function ($item) {
                    return in_array(strtolower(trim($item->trang_thai)), [
                        'chờ thanh toán',
                        'cho thanh toan',
                        'cho_thanh_toan',
                        'pending',
                    ]);
                })
                ->count();

            $daThanhToan = $hoaDon->filter(function ($item) {
                return in_array(strtolower(trim($item->trang_thai)), [
                    'đã thanh toán',
                    'da thanh toan',
                    'da_thanh_toan',
                    'paid',
                ]);
            });

        @endphp


        <div class="payment-summary">

            <div class="summary-card">

                <div class="summary-title">
                    TỔNG HÓA ĐƠN
                </div>

                <div class="summary-value">
                    {{ $tongHoaDon }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-title">
                    CHỜ THANH TOÁN
                </div>

                <div class="summary-value red">
                    {{ $choThanhToan }}
                </div>

            </div>


            <div class="summary-card">

                <div class="summary-title">
                    ĐÃ THANH TOÁN
                </div>

                <div class="summary-value green">
                    {{ number_format($daThanhToan->sum('tong_tien'), 0, ',', '.') }} ₫
                </div>

            </div>

        </div>


        {{-- INVOICE LIST --}}

        @if ($hoaDon->count() > 0)

            <div class="invoice-list">

                @foreach ($hoaDon as $hd)
                    @php

                        $status = strtolower(trim($hd->trang_thai));

                        if (in_array($status, ['chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending'])) {
                            $statusClass = 'status-pending';
                        } elseif (in_array($status, ['đã thanh toán', 'da thanh toan', 'da_thanh_toan', 'paid'])) {
                            $statusClass = 'status-paid';
                        } else {
                            $statusClass = 'status-other';
                        }

                    @endphp


                    <div class="invoice-card">


                        {{-- TOP --}}

                        <div class="invoice-top">

                            <div class="invoice-left">

                                <div class="invoice-icon">
                                    🧾
                                </div>

                                <div class="invoice-code">

                                    <small>
                                        Mã hóa đơn
                                    </small>

                                    <strong>
                                        #HD{{ str_pad($hd->hoa_don_id, 5, '0', STR_PAD_LEFT) }}
                                    </strong>

                                </div>

                            </div>


                            <div class="invoice-status {{ $statusClass }}">
                                {{ $hd->trang_thai }}
                            </div>

                        </div>


                        {{-- BODY --}}

                        <div class="invoice-body">


                            <div class="invoice-info">

                                <div>

                                    <span class="info-label">
                                        Hội viên
                                    </span>

                                    <span class="info-value">
                                        {{ $hd->ho_ten }}
                                    </span>

                                </div>


                                <div>

                                    <span class="info-label">
                                        Email
                                    </span>

                                    <span class="info-value">
                                        {{ $hd->email }}
                                    </span>

                                </div>


                                <div>

                                    <span class="info-label">
                                        Ngày lập
                                    </span>

                                    <span class="info-value">
                                        {{ \Carbon\Carbon::parse($hd->ngay_lap)->format('d/m/Y H:i') }}
                                    </span>

                                </div>


                                <div>

                                    <span class="info-label">
                                        Tổng tiền
                                    </span>

                                    <span class="info-value">
                                        {{ number_format($hd->tong_tien, 0, ',', '.') }} ₫
                                    </span>

                                </div>

                            </div>


                            {{-- CHI TIẾT --}}

                            <div class="detail-box">

                                <div class="detail-title">
                                    CHI TIẾT HÓA ĐƠN
                                </div>


                                @forelse($chiTietHoaDon->get(
                                        $hd->hoa_don_id,
                                        collect()
                                    )
                                    as $ct)
                                    @php

                                        $tenGoi = $ct->ten_goi ?: $ct->ten_goi_pt ?: 'Dịch vụ GYM';

                                    @endphp


                                    <div class="detail-row">

                                        <div class="detail-name">

                                            {{ $tenGoi }}

                                            <small>
                                                Số lượng:
                                                {{ $ct->so_luong }}
                                            </small>

                                        </div>

                                        <div class="detail-price">

                                            {{ number_format($ct->thanh_tien, 0, ',', '.') }}
                                            ₫

                                        </div>

                                    </div>

                                @empty

                                    <div
                                        style="
                                        color:#666;
                                        font-size:12px;
                                        padding:10px 0;
                                    ">
                                        Chưa có chi tiết hóa đơn.
                                    </div>
                                @endforelse

                            </div>


                            {{-- THANH TOÁN --}}

                            @if (in_array($status, ['chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending']))
                                <div class="payment-action">

                                    <form action="/staff/payments/{{ $hd->hoa_don_id }}/confirm" method="POST"
                                        class="payment-form">

                                        @csrf

                                        <div class="payment-field">

                                            <label>
                                                PHƯƠNG THỨC THANH TOÁN
                                            </label>

                                            <select name="phuong_thuc_thanh_toan" required>

                                                <option value="">
                                                    -- Chọn phương thức --
                                                </option>

                                                <option value="Tiền mặt">
                                                    Tiền mặt
                                                </option>

                                                <option value="Chuyển khoản">
                                                    Chuyển khoản
                                                </option>

                                                <option value="Thẻ">
                                                    Thẻ
                                                </option>

                                            </select>

                                        </div>


                                        <button type="submit" class="confirm-btn"
                                            onclick="return confirm('Bạn có chắc chắn muốn xác nhận hóa đơn này đã thanh toán?')">
                                            ✓ XÁC NHẬN THANH TOÁN
                                        </button>

                                    </form>

                                </div>
                            @else
                                <div
                                    style="
                                    margin-top:20px;
                                    padding-top:18px;
                                    border-top:1px solid #252525;
                                    color:#4cd97b;
                                    font-size:12px;
                                    font-weight:700;
                                ">

                                    ✓ Đã thanh toán bằng:
                                    {{ $hd->phuong_thuc_thanh_toan ?: 'Chưa cập nhật' }}

                                </div>
                            @endif


                        </div>

                    </div>
                @endforeach

            </div>
        @else
            <div class="payment-empty">

                <div class="payment-empty-icon">
                    🧾
                </div>

                <h2>
                    Chưa có hóa đơn
                </h2>

                <p>
                    Hiện tại hệ thống chưa có hóa đơn nào.
                </p>

            </div>

        @endif


    </div>

@endsection
