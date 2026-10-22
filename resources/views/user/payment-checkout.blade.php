@extends('layouts.dashboard')
@section('title', 'Thanh toán trực tuyến - GYM MANAGEMENT')
@section('content')
    <style>
        .checkout-page {
            max-width: 940px;
            margin: 12px auto 42px;
            padding: 0 18px;
            color: #f5f5f5
        }

        .checkout-hero {
            position: relative;
            overflow: hidden;
            padding: 30px 32px;
            margin-bottom: 22px;
            border: 1px solid #292929;
            border-radius: 18px;
            background: radial-gradient(circle at 92% 0%, rgba(229, 9, 20, .18), transparent 34%), linear-gradient(135deg, #171717, #0d0d0d)
        }

        .checkout-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ff3943;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase
        }

        .checkout-hero h1 {
            font-size: clamp(25px, 3vw, 34px);
            font-weight: 850;
            letter-spacing: -.5px;
            margin: 10px 0 8px
        }

        .checkout-subtitle {
            max-width: 650px;
            color: #a3a3a3;
            font-size: 13px;
            line-height: 1.7;
            margin: 0
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, .85fr);
            gap: 18px;
            align-items: start
        }

        .checkout-card {
            background: #111;
            border: 1px solid #292929;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 18px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .12)
        }

        .checkout-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px
        }

        .checkout-icon {
            display: grid;
            place-items: center;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            border: 1px solid #472024;
            border-radius: 12px;
            background: #230f11;
            color: #ff3943;
            font-size: 19px
        }

        .checkout-card h2 {
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 4px
        }

        .checkout-muted {
            color: #8f8f8f;
            font-size: 12px;
            line-height: 1.6
        }

        .checkout-invoice {
            padding: 16px;
            border: 1px solid #303030;
            border-radius: 11px;
            background: #151515;
            margin: 10px 0
        }

        .checkout-invoice.selected {
            border-color: #9e2229;
            background: linear-gradient(110deg, rgba(229, 9, 20, .09), transparent 70%)
        }

        .checkout-invoice-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px
        }

        .checkout-invoice-label {
            font-size: 11px;
            color: #999;
            margin-bottom: 4px
        }

        .checkout-invoice h3 {
            font-size: 17px;
            font-weight: 850;
            margin: 0
        }

        .checkout-price {
            font-size: 15px;
            font-weight: 850;
            white-space: nowrap
        }

        .checkout-invoice-date {
            margin-top: 8px
        }

        .checkout-line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0 0;
            margin-top: 10px;
            border-top: 1px solid #282828;
            font-size: 12px
        }

        .checkout-line strong {
            white-space: nowrap
        }

        .checkout-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 17px 0 0;
            margin-top: 16px;
            border-top: 1px solid #303030
        }

        .checkout-total span {
            color: #c6c6c6;
            font-size: 13px;
            font-weight: 700
        }

        .checkout-total strong {
            font-size: 22px;
            font-weight: 900;
            color: #ff3943;
            white-space: nowrap
        }

        .checkout-choice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            border: 1px solid #303030;
            border-radius: 11px;
            background: #141414;
            margin: 10px 0;
            cursor: pointer;
            transition: border-color .18s, background .18s
        }

        .checkout-choice:has(input:checked) {
            border-color: #e50914;
            background: rgba(229, 9, 20, .08)
        }

        .checkout-choice input {
            accent-color: #e50914;
            margin: 3px 0 0;
            flex: 0 0 auto
        }

        .checkout-choice strong {
            display: block;
            font-size: 13px;
            margin-bottom: 4px
        }

        .checkout-choice .checkout-muted {
            display: block
        }

        .checkout-transfer {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 17px;
            border: 1px solid #493033;
            border-radius: 12px;
            background: linear-gradient(130deg, rgba(229, 9, 20, .10), rgba(20, 20, 20, .7))
        }

        .checkout-transfer-icon {
            display: grid;
            place-items: center;
            flex: 0 0 46px;
            height: 46px;
            border-radius: 13px;
            background: #e50914;
            color: white;
            font-size: 21px
        }

        .checkout-transfer strong {
            display: block;
            font-size: 14px;
            margin: 2px 0 5px
        }

        .checkout-transfer p {
            margin: 0;
            color: #aaa;
            font-size: 12px;
            line-height: 1.65
        }

        .checkout-secure {
            display: flex;
            gap: 8px;
            align-items: center;
            color: #999;
            font-size: 11px;
            margin-top: 14px
        }

        .checkout-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px
        }

        .checkout-btn,
        .checkout-secondary {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 12px 17px;
            border-radius: 9px;
            font-weight: 800;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: transform .15s, background .15s
        }

        .checkout-btn {
            flex: 1;
            border: 1px solid #e50914;
            background: #e50914;
            color: #fff;
            box-shadow: 0 7px 18px rgba(229, 9, 20, .16)
        }

        .checkout-btn:hover {
            background: #c9000b;
            transform: translateY(-1px)
        }

        .checkout-secondary {
            border: 1px solid #3a3a3a;
            background: #171717;
            color: #eee
        }

        .checkout-secondary:hover {
            border-color: #666;
            background: #202020
        }

        .checkout-error {
            padding: 13px 15px;
            background: #300d10;
            border: 1px solid #64151a;
            border-radius: 9px;
            color: #ff9a9f;
            margin-bottom: 16px;
            font-size: 13px
        }

        .checkout-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            color: #aaa;
            font-size: 12px
        }

        .checkout-summary-row strong {
            color: #eee;
            text-align: right
        }

        .checkout-summary-row+.checkout-summary-row {
            border-top: 1px solid #262626
        }

        .checkout-summary-total {
            margin-top: 5px;
            padding-top: 15px;
            border-top: 1px solid #383838
        }

        .checkout-summary-total span {
            color: #eee;
            font-size: 13px;
            font-weight: 800
        }

        .checkout-summary-total strong {
            font-size: 23px;
            color: #ff3943;
            font-weight: 900
        }

        @media(max-width:720px) {
            .checkout-layout {
                grid-template-columns: 1fr
            }

            .checkout-hero {
                padding: 23px 20px
            }

            .checkout-card {
                padding: 17px
            }

            .checkout-total strong {
                font-size: 19px
            }
        }

        @media(max-width:420px) {
            .checkout-invoice-top {
                flex-direction: column
            }

            .checkout-actions {
                flex-direction: column
            }

            .checkout-btn,
            .checkout-secondary {
                width: 100%;
                box-sizing: border-box
            }
        }
    </style>

    <div class="checkout-page">
        <header class="checkout-hero">
            <div class="checkout-kicker"><span>●</span> GYMFIT · THANH TOÁN TRỰC TUYẾN</div>
            <h1>Hoàn tất thanh toán</h1>
            <p class="checkout-subtitle">Kiểm tra hóa đơn và chọn thanh toán riêng hoặc gộp các hóa đơn đang chờ. Mọi thông
                tin được tổng hợp trước khi xác nhận.</p>
        </header>

        @if (session('error'))
            <div class="checkout-error"><strong>Chưa thể tiếp tục:</strong> {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ url('/user/payments/checkout') }}">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $hoaDon->hoa_don_id }}">
            <input type="hidden" name="phuong_thuc_thanh_toan" value="Chuyển khoản">

            <div class="checkout-layout">
                <main>
                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <div class="checkout-icon">▤</div>
                            <div>
                                <h2>Chi tiết hóa đơn</h2>
                                <div class="checkout-muted">Thông tin các khoản cần thanh toán</div>
                            </div>
                        </div>

                        @foreach ($hoaDonChoThanhToan as $hd)
                            <article
                                class="checkout-invoice {{ (int) $hd->hoa_don_id === (int) $hoaDon->hoa_don_id ? 'selected' : '' }}">
                                <div class="checkout-invoice-top">
                                    <div>
                                        <div class="checkout-invoice-label">
                                            {{ (int) $hd->hoa_don_id === (int) $hoaDon->hoa_don_id ? 'HÓA ĐƠN ĐANG CHỌN' : 'HÓA ĐƠN CHỜ THANH TOÁN' }}
                                        </div>
                                        <h3>#HD{{ str_pad($hd->hoa_don_id, 5, '0', STR_PAD_LEFT) }}</h3>
                                    </div>
                                    <div class="checkout-price">{{ number_format($hd->tong_tien, 0, ',', '.') }} ₫</div>
                                </div>
                                <div class="checkout-muted checkout-invoice-date">Ngày lập:
                                    {{ \Carbon\Carbon::parse($hd->ngay_lap)->format('d/m/Y H:i') }}</div>
                                @foreach ($chiTiet->get($hd->hoa_don_id, collect()) as $ct)
                                    <div class="checkout-line">
                                        <span>{{ $ct->ten_goi ?: $ct->ten_goi_pt ?: 'Dịch vụ GymFit' }} <span
                                                class="checkout-muted">× {{ $ct->so_luong }}</span></span>
                                        <strong>{{ number_format($ct->thanh_tien, 0, ',', '.') }} ₫</strong>
                                    </div>
                                @endforeach
                            </article>
                        @endforeach
                    </section>

                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <div class="checkout-icon">↗</div>
                            <div>
                                <h2>Phương thức thanh toán</h2>
                                <div class="checkout-muted">Thanh toán trực tuyến bằng chuyển khoản</div>
                            </div>
                        </div>
                        <div class="checkout-transfer">
                            <div class="checkout-transfer-icon">⇄</div>
                            <div>
                                <strong>Chuyển khoản ngân hàng</strong>
                                <p>Phương thức thanh toán trực tuyến duy nhất của GYMFit. Nhấn xác nhận để tiếp tục quy
                                    trình thanh toán mô phỏng của đồ án.</p>
                            </div>
                        </div>
                        <div class="checkout-secure"><span>✓</span><span>Chỉ xử lý các hóa đơn đang chờ thanh toán thuộc tài
                                khoản của bạn.</span></div>
                    </section>
                </main>

                <aside>
                    <section class="checkout-card">
                        <div class="checkout-card-head">
                            <div class="checkout-icon">₫</div>
                            <div>
                                <h2>Tóm tắt thanh toán</h2>
                                <div class="checkout-muted">Chọn cách thanh toán bên dưới</div>
                            </div>
                        </div>

                        <label class="checkout-choice">
                            <input type="radio" name="mode" value="single" checked>
                            <span><strong>Thanh toán riêng</strong><span class="checkout-muted">Chỉ thanh toán hóa đơn
                                    #HD{{ str_pad($hoaDon->hoa_don_id, 5, '0', STR_PAD_LEFT) }}.</span></span>
                        </label>
                        <label class="checkout-choice">
                            <input type="radio" name="mode" value="all">
                            <span><strong>Gộp các hóa đơn chờ</strong><span class="checkout-muted">Thanh toán
                                    {{ $hoaDonChoThanhToan->count() }} hóa đơn trong một lần.</span></span>
                        </label>

                        <div class="checkout-summary-row"><span>Hóa đơn đang
                                chờ</span><strong>{{ $hoaDonChoThanhToan->count() }} hóa đơn</strong></div>
                        <div class="checkout-summary-row"><span>Hóa đơn đang
                                chọn</span><strong>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} ₫</strong></div>
                        <div class="checkout-total checkout-summary-total"><span>Tổng nếu thanh toán
                                gộp</span><strong>{{ number_format($hoaDonChoThanhToan->sum('tong_tien'), 0, ',', '.') }}
                                ₫</strong></div>

                        <p class="checkout-muted" style="margin:13px 0 0">Lưu ý: hiện đây là luồng thanh toán mô phỏng, chưa
                            kết nối ngân hàng hoặc cổng thanh toán thật.</p>
                        <div class="checkout-actions">
                            <button class="checkout-btn" type="submit"
                                data-confirm="Xác nhận thanh toán bằng chuyển khoản trong bản mô phỏng?">Xác
                                nhận thanh toán <span>→</span></button>
                            <a class="checkout-secondary" href="{{ url('/user/payments') }}">Quay lại hóa đơn</a>
                        </div>
                    </section>
                </aside>
            </div>
        </form>
    </div>
@endsection
