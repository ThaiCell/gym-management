@extends('layouts.user')

@section('content')
    <style>
        .pt-select-page {
            min-height: 100vh;
            background: #090909;
            color: #f5f5f5;
            padding: 48px 18px 80px
        }

        .pt-select-wrap {
            width: min(1100px, 96%);
            margin: auto
        }

        .pt-back {
            display: inline-block;
            color: #aaa;
            text-decoration: none;
            margin-bottom: 25px;
            font-size: 13px
        }

        .pt-back:hover {
            color: #fff
        }

        .pt-head {
            text-align: center;
            margin-bottom: 35px
        }

        .pt-eyebrow {
            color: #ff3340;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 3px
        }

        .pt-head h1 {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 950;
            margin: 10px 0
        }

        .pt-head p {
            color: #999;
            margin: 0
        }

        .pt-summary {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 12px;
            background: #171717;
            border: 1px solid #303030;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 32px
        }

        .pt-summary small {
            display: block;
            color: #888;
            font-size: 10px;
            letter-spacing: 1px;
            margin-bottom: 7px
        }

        .pt-summary strong {
            font-size: 16px
        }

        .pt-summary .price {
            color: #ff3945;
            font-size: 21px
        }

        .pt-section-title {
            font-size: 21px;
            font-weight: 850;
            margin: 0 0 7px
        }

        .pt-section-sub {
            color: #888;
            font-size: 13px;
            margin: 0 0 20px
        }

        .pt-trainer-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px
        }

        .pt-trainer {
            position: relative;
            background: #171717;
            border: 1px solid #303030;
            border-radius: 14px;
            overflow: hidden;
            cursor: pointer;
            transition: .2s
        }

        .pt-trainer:hover {
            border-color: #9d2931;
            transform: translateY(-3px)
        }

        .pt-trainer.selected {
            border: 2px solid #ed1c2e;
            box-shadow: 0 0 0 3px #ed1c2e20
        }

        .pt-trainer input {
            position: absolute;
            opacity: 0;
            pointer-events: none
        }

        .pt-trainer-photo {
            height: 205px;
            background: #252525
        }

        .pt-trainer-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .pt-trainer-info {
            padding: 17px
        }

        .pt-trainer-info h3 {
            font-size: 18px;
            margin: 0 0 7px
        }

        .pt-specialty {
            color: #ff5963;
            font-size: 12px;
            font-weight: 700;
            min-height: 18px
        }

        .pt-achievement {
            color: #999;
            font-size: 12px;
            line-height: 1.6;
            margin-top: 9px;
            min-height: 38px
        }

        .pt-radio {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            color: #bbb;
            font-size: 12px
        }

        .pt-radio-mark {
            width: 17px;
            height: 17px;
            border: 1px solid #666;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center
        }

        .pt-trainer.selected .pt-radio-mark {
            border-color: #ff3340;
            background: #e50914;
            box-shadow: inset 0 0 0 4px #171717
        }

        .pt-confirm {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 28px;
            padding: 20px;
            background: #171717;
            border: 1px solid #303030;
            border-radius: 14px
        }

        .pt-confirm p {
            color: #999;
            margin: 4px 0 0;
            font-size: 12px
        }

        .pt-submit {
            border: 0;
            border-radius: 8px;
            background: #e50914;
            color: #fff;
            padding: 15px 25px;
            font-weight: 900;
            letter-spacing: .5px;
            cursor: pointer
        }

        .pt-submit:disabled {
            background: #444;
            color: #888;
            cursor: not-allowed
        }

        .pt-alert {
            padding: 13px 16px;
            margin-bottom: 18px;
            background: #2b1517;
            border: 1px solid #703039;
            border-radius: 8px;
            color: #ffdadd
        }

        @media(max-width:800px) {
            .pt-trainer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .pt-summary {
                grid-template-columns: 1fr 1fr
            }

            .pt-summary>div:first-child {
                grid-column: 1/-1
            }
        }

        @media(max-width:560px) {
            .pt-trainer-grid {
                grid-template-columns: 1fr
            }

            .pt-confirm {
                align-items: stretch;
                flex-direction: column
            }

            .pt-submit {
                width: 100%
            }

            .pt-summary {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="pt-select-page">
        <div class="pt-select-wrap">
            <a class="pt-back" href="{{ url('/pt-packages') }}">← Quay lại danh sách gói PT</a>
            @if (session('error'))
                <div class="pt-alert">{{ session('error') }}</div>
            @endif

            <header class="pt-head">
                <div class="pt-eyebrow">BƯỚC 02 · CHỌN NGƯỜI ĐỒNG HÀNH</div>
                <h1>Chọn huấn luyện viên của bạn</h1>
                <p>Xem thông tin từng PT và chọn người phù hợp với mục tiêu tập luyện.</p>
            </header>

            <section class="pt-summary">
                <div><small>GÓI BẠN ĐANG CHỌN</small><strong>{{ $goiPt->ten_goi_pt }}</strong></div>
                <div><small>SỐ BUỔI / THỜI HẠN</small><strong>{{ $goiPt->so_buoi }} buổi · {{ $goiPt->thoi_han_ngay }}
                        ngày</strong></div>
                <div><small>TỔNG GIÁ</small><strong class="price">{{ number_format($goiPt->gia, 0, ',', '.') }}₫</strong></div>
            </section>

            @if ($ptList->count())
                <form method="POST" action="{{ url('/pt-packages/' . $goiPt->goi_pt_id . '/register') }}" id="ptChooseForm"
                    data-confirm-submit="Xác nhận đăng ký gói PT với huấn luyện viên đã chọn? Hệ thống sẽ tạo hóa đơn chờ thanh toán."
                    data-confirm-title="Xác nhận đăng ký gói PT" data-confirm-text="Xác nhận đăng ký">
                    @csrf
                    <h2 class="pt-section-title">Đội ngũ huấn luyện viên</h2>
                    <p class="pt-section-sub">Nhấn vào thẻ PT để chọn. Bạn chỉ có thể chọn một huấn luyện viên.</p>
                    <div class="pt-trainer-grid">
                        @foreach ($ptList as $index => $hlv)
                            @php
                                $photo = !empty($hlv->anh)
                                    ? ltrim($hlv->anh, '/')
                                    : 'img/pt' . (($index % 3) + 1) . '.jpg';
                            @endphp
                            <label class="pt-trainer" data-trainer-card>
                                <input type="radio" name="pt_id" value="{{ $hlv->pt_id }}" required>
                                <div class="pt-trainer-photo"><img src="{{ asset($photo) }}" alt="{{ $hlv->ho_ten }}"
                                        onerror="this.src='{{ asset('img/pt1.jpg') }}'"></div>
                                <div class="pt-trainer-info">
                                    <h3>{{ $hlv->ho_ten }}</h3>
                                    <div class="pt-specialty">{{ $hlv->chuyen_mon ?: 'Huấn luyện viên cá nhân' }}</div>
                                    <div class="pt-achievement">
                                        {{ $hlv->thanh_tich ?: 'Sẵn sàng đồng hành cùng bạn trong quá trình tập luyện.' }}
                                    </div>
                                    <div class="pt-radio"><span class="pt-radio-mark"></span><span>Chọn huấn luyện viên
                                            này</span></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="pt-confirm">
                        <div><strong id="selectedTrainer">Bạn chưa chọn huấn luyện viên</strong>
                            <p>Kiểm tra lựa chọn trước khi gửi yêu cầu đăng ký.</p>
                        </div>
                        <button class="pt-submit" type="submit" id="submitPt" disabled>XÁC NHẬN ĐĂNG KÝ →</button>
                    </div>
                </form>
            @else
                <div class="pt-alert">Hiện chưa có huấn luyện viên đang hoạt động. Vui lòng quay lại sau.</div>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-trainer-card]').forEach(function(card) {
            card.addEventListener('click', function() {
                document.querySelectorAll('[data-trainer-card]').forEach(function(item) {
                    item.classList.remove('selected');
                });
                card.classList.add('selected');
                var radio = card.querySelector('input[type="radio"]');
                radio.checked = true;
                document.getElementById('selectedTrainer').textContent = 'Đã chọn: ' + card.querySelector(
                    'h3').textContent;
                document.getElementById('submitPt').disabled = false;
            });
        });

    </script>
@endsection
