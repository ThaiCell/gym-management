@extends('layouts.app')

@section('content')

<style>
    .page-title {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #888;
        margin-bottom: 25px;
    }

    .box {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .box-title {
        padding: 18px 20px;
        border-bottom: 1px solid #292929;
        font-size: 18px;
        font-weight: 700;
    }

    .info {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .info-item {
        padding: 16px 20px;
        border-bottom: 1px solid #292929;
    }

    .label {
        color: #888;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .value {
        font-weight: 600;
    }

    .invoice-id {
        color: #ff1a1a;
        font-size: 20px;
    }

    .status {
        display: inline-block;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .paid {
        background: #063d20;
        color: #00d96b;
    }

    .waiting {
        background: #443500;
        color: #ffc107;
    }

    .cancel {
        background: #431010;
        color: #ff4d4d;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-table th {
        background: #101010;
        color: #aaa;
        padding: 14px;
        text-align: left;
        font-size: 13px;
    }

    .detail-table td {
        padding: 14px;
        border-top: 1px solid #292929;
    }

    .total-row td {
        font-size: 17px;
        font-weight: 700;
        border-top: 1px solid #555;
    }

    .total-money {
        color: #ff1a1a;
        font-size: 20px;
    }

    .back-btn {
        display: inline-block;
        padding: 9px 15px;
        background: #292929;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: #e50914;
        color: #fff;
    }

    @media(max-width: 800px) {
        .info {
            grid-template-columns: 1fr;
        }
    }
</style>

<a href="/admin/payments" class="back-btn">
    ← Quay lại danh sách
</a>

<div class="page-title">
    Chi tiết hóa đơn
</div>

<div class="page-subtitle">
    Thông tin hóa đơn #{{ $payment->hoa_don_id }}
</div>

<div class="box">

    <div class="box-title">
        Thông tin hóa đơn
    </div>

    <div class="info">

        <div class="info-item">
            <div class="label">Mã hóa đơn</div>
            <div class="value invoice-id">
                #{{ $payment->hoa_don_id }}
            </div>
        </div>

        <div class="info-item">
            <div class="label">Ngày lập</div>
            <div class="value">
                {{ \Carbon\Carbon::parse($payment->ngay_lap)->format('d/m/Y') }}
            </div>
        </div>

        <div class="info-item">
            <div class="label">Hội viên</div>
            <div class="value">
                {{ $payment->ho_ten }}
            </div>
        </div>

        <div class="info-item">
            <div class="label">Email</div>
            <div class="value">
                {{ $payment->email }}
            </div>
        </div>

        <div class="info-item">
            <div class="label">Phương thức thanh toán</div>
            <div class="value">
                {{ $payment->phuong_thuc_thanh_toan ?? 'Chưa thanh toán' }}
            </div>
        </div>

        <div class="info-item">
            <div class="label">Trạng thái</div>
            <div class="value">

                @if($payment->trang_thai == 'da_thanh_toan')
                    <span class="status paid">
                        Đã thanh toán
                    </span>

                @elseif($payment->trang_thai == 'cho_thanh_toan')
                    <span class="status waiting">
                        Chờ thanh toán
                    </span>

                @elseif($payment->trang_thai == 'da_huy')
                    <span class="status cancel">
                        Đã hủy
                    </span>

                @else
                    <span class="status waiting">
                        {{ $payment->trang_thai }}
                    </span>
                @endif

            </div>
        </div>

    </div>

</div>


<div class="box">

    <div class="box-title">
        Chi tiết thanh toán
    </div>

    <table class="detail-table">

        <thead>
            <tr>
                <th>#</th>
                <th>GÓI TẬP / GÓI PT</th>
                <th>SỐ LƯỢNG</th>
                <th>ĐƠN GIÁ</th>
                <th>THÀNH TIỀN</th>
            </tr>
        </thead>

        <tbody>

            @forelse($details as $index => $detail)

                <tr>

                    <td>
                        {{ $index + 1 }}
                    </td>

                    <td>
                        @if($detail->ten_goi)
                            {{ $detail->ten_goi }}

                        @elseif($detail->ten_goi_pt)
                            {{ $detail->ten_goi_pt }}

                        @else
                            Không xác định
                        @endif
                    </td>

                    <td>
                        {{ $detail->so_luong }}
                    </td>

                    <td>
                        {{ number_format($detail->don_gia, 0, ',', '.') }} đ
                    </td>

                    <td>
                        <strong>
                            {{ number_format($detail->thanh_tien, 0, ',', '.') }} đ
                        </strong>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5"
                        style="text-align:center;color:#777;padding:30px;">
                        Hóa đơn chưa có chi tiết.
                    </td>
                </tr>

            @endforelse

        </tbody>

        <tfoot>

            <tr class="total-row">

                <td colspan="4" style="text-align:right;">
                    TỔNG CỘNG
                </td>

                <td class="total-money">
                    {{ number_format($payment->tong_tien, 0, ',', '.') }} đ
                </td>

            </tr>

        </tfoot>

    </table>

</div>

@endsection