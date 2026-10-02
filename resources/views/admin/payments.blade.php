@extends('layouts.app')

@section('content')

<style>
    .page-title {
        margin-bottom: 5px;
        font-size: 26px;
        font-weight: 700;
    }

    .page-subtitle {
        color: #888;
        margin-bottom: 25px;
    }

    .payment-box {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 10px;
        overflow: hidden;
    }

    .payment-top {
        padding: 15px;
        border-bottom: 1px solid #292929;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-text {
        color: #aaa;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-table th {
        background: #101010;
        color: #aaa;
        font-size: 13px;
        padding: 14px;
        text-align: left;
        white-space: nowrap;
    }

    .payment-table td {
        padding: 14px;
        border-top: 1px solid #292929;
        color: #eee;
    }

    .payment-table tr:hover {
        background: #1d1d1d;
    }

    .invoice-id {
        color: #ff1a1a;
        font-weight: 700;
    }

    .amount {
        font-weight: 700;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-paid {
        background: #063d20;
        color: #00d96b;
    }

    .status-waiting {
        background: #443500;
        color: #ffc107;
    }

    .status-cancel {
        background: #431010;
        color: #ff4d4d;
    }

    .btn-detail {
        display: inline-block;
        padding: 7px 12px;
        border: 1px solid #444;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-detail:hover {
        background: #e50914;
        border-color: #e50914;
        color: #fff;
    }

    .empty {
        text-align: center;
        padding: 35px;
        color: #777;
    }

    @media (max-width: 1000px) {
        .payment-box {
            overflow-x: auto;
        }

        .payment-table {
            min-width: 1000px;
        }
    }
</style>

<div class="page-title">Quản lý thanh toán</div>

<div class="page-subtitle">
    Danh sách hóa đơn và tình trạng thanh toán trong hệ thống Gym Management
</div>

<div class="payment-box">

    <div class="payment-top">
        <strong>Danh sách hóa đơn</strong>

        <span class="total-text">
            Tổng: {{ $payments->count() }} hóa đơn
        </span>
    </div>

    @if($payments->count() > 0)

        <table class="payment-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>HỘI VIÊN</th>
                    <th>EMAIL</th>
                    <th>NGÀY LẬP</th>
                    <th>TỔNG TIỀN</th>
                    <th>PHƯƠNG THỨC</th>
                    <th>TRẠNG THÁI</th>
                    <th>THAO TÁC</th>
                </tr>
            </thead>

            <tbody>

                @foreach($payments as $payment)

                    <tr>

                        <td class="invoice-id">
                            #{{ $payment->hoa_don_id }}
                        </td>

                        <td>
                            <strong>{{ $payment->ho_ten }}</strong>
                        </td>

                        <td>
                            {{ $payment->email }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($payment->ngay_lap)->format('d/m/Y') }}
                        </td>

                        <td class="amount">
                            {{ number_format($payment->tong_tien, 0, ',', '.') }} đ
                        </td>

                        <td>
                            @if($payment->phuong_thuc_thanh_toan)
                                {{ $payment->phuong_thuc_thanh_toan }}
                            @else
                                <span style="color:#777;">Chưa thanh toán</span>
                            @endif
                        </td>

                        <td>

                            @if($payment->trang_thai == 'da_thanh_toan')
                                <span class="status status-paid">
                                    Đã thanh toán
                                </span>

                            @elseif($payment->trang_thai == 'cho_thanh_toan')
                                <span class="status status-waiting">
                                    Chờ thanh toán
                                </span>

                            @elseif($payment->trang_thai == 'da_huy')
                                <span class="status status-cancel">
                                    Đã hủy
                                </span>

                            @else
                                <span class="status status-waiting">
                                    {{ $payment->trang_thai }}
                                </span>
                            @endif

                        </td>

                        <td>
                            <a
                                href="/admin/payments/{{ $payment->hoa_don_id }}"
                                class="btn-detail"
                            >
                                Xem chi tiết
                            </a>
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">
            Chưa có hóa đơn nào trong hệ thống.
        </div>

    @endif

</div>

@endsection