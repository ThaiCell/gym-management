@extends('layouts.user')

@section('content')
    <style>
        .pt-catalog {
            background: #090909;
            color: #f7f7f7;
            min-height: 100vh;
            padding-bottom: 80px
        }

        .pt-hero {
            padding: 90px 20px 75px;
            text-align: center;
            background: linear-gradient(90deg, rgba(0, 0, 0, .92), rgba(0, 0, 0, .62)), url('/img/banner1.avif') center/cover
        }

        .pt-kicker {
            color: #ff2633;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 4px;
            text-transform: uppercase
        }

        .pt-hero h1 {
            font-size: clamp(32px, 5vw, 54px);
            font-weight: 950;
            margin: 14px 0;
            text-transform: uppercase
        }

        .pt-hero h1 span {
            color: #ed1c2e
        }

        .pt-hero p {
            max-width: 680px;
            margin: auto;
            color: #bdbdbd;
            line-height: 1.8
        }

        .pt-wrap {
            width: min(1160px, 92%);
            margin: auto
        }

        .pt-intro {
            text-align: center;
            padding: 58px 0 32px
        }

        .pt-intro h2 {
            font-size: 30px;
            font-weight: 900;
            margin: 8px 0
        }

        .pt-intro p {
            color: #929292;
            margin: 0
        }

        .pt-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            align-items: stretch
        }

        .pt-card {
            background: linear-gradient(160deg, #1b1b1b, #111);
            border: 1px solid #303030;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: .25s
        }

        .pt-card:hover {
            transform: translateY(-6px);
            border-color: #e51c2b;
            box-shadow: 0 18px 45px #e509141c
        }

        .pt-card.is-featured {
            border-color: #a51b25
        }

        .pt-ribbon {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #e50914;
            color: white;
            border-radius: 30px;
            padding: 7px 12px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 1px;
            z-index: 2
        }

        .pt-photo {
            height: 190px;
            background: #202020;
            overflow: hidden
        }

        .pt-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: .35s
        }

        .pt-card:hover .pt-photo img {
            transform: scale(1.05)
        }

        .pt-card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex: 1
        }

        .pt-card h3 {
            font-size: 21px;
            font-weight: 850;
            margin: 0 0 12px
        }

        .pt-price {
            font-size: 30px;
            color: #ff3340;
            font-weight: 950;
            letter-spacing: -1px
        }

        .pt-price small {
            font-size: 12px;
            color: #999;
            font-weight: 500;
            letter-spacing: 0
        }

        .pt-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 20px 0;
            padding: 16px 0;
            border-top: 1px solid #303030;
            border-bottom: 1px solid #303030
        }

        .pt-meta div {
            background: #202020;
            border-radius: 9px;
            padding: 12px
        }

        .pt-meta small {
            display: block;
            color: #888;
            font-size: 11px;
            margin-bottom: 5px
        }

        .pt-meta strong {
            font-size: 15px
        }

        .pt-status {
            color: #68d391;
            font-size: 12px;
            font-weight: 800;
            margin: 0 0 18px
        }

        .pt-action {
            display: block;
            margin-top: auto;
            text-decoration: none;
            text-align: center;
            background: #e50914;
            color: #fff;
            border: 1px solid #e50914;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 1px;
            transition: .2s
        }

        .pt-action:hover {
            background: #b90912;
            color: #fff;
            transform: translateY(-1px)
        }

        .pt-action.disabled {
            background: #333;
            border-color: #444;
            color: #888;
            pointer-events: none
        }

        .pt-empty {
            text-align: center;
            border: 1px dashed #444;
            border-radius: 15px;
            padding: 60px 20px;
            color: #aaa
        }

        .pt-alert {
            width: min(1160px, 92%);
            margin: 20px auto 0;
            padding: 14px 18px;
            border-radius: 9px;
            background: #241416;
            border: 1px solid #68232b;
            color: #ffdadd
        }

        .pt-alert.success {
            background: #102219;
            border-color: #1d6a3c;
            color: #c7f5d7
        }


        /* Toast thông báo đồng nhất với trang Lớp tập */
        .gym-toast {
            position: fixed;
            top: 95px;
            right: 30px;
            z-index: 99999;
            width: min(380px, calc(100vw - 32px));
            min-height: 80px;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 18px;
            background: #151515;
            border: 1px solid #333;
            border-left: 4px solid #ff1010;
            border-radius: 8px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .45);
            animation: ptToastSlideIn .35s ease forwards;
        }
        .success-toast, .error-toast { border-left-color: #ff1010; }
        .gym-toast .toast-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #ed1111;
            color: #fff;
            font-size: 20px;
            font-weight: 700;
        }
        .gym-toast .toast-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .gym-toast .toast-content strong {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        .gym-toast .toast-content span {
            color: #aaa;
            font-size: 13px;
            line-height: 1.4;
        }
        .gym-toast .toast-close {
            flex: 0 0 auto;
            padding: 2px 5px;
            border: 0;
            background: transparent;
            color: #777;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
        }
        .gym-toast .toast-close:hover { color: #fff; }
        @keyframes ptToastSlideIn {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes ptToastSlideOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(40px); }
        }
        .gym-toast.hide { animation: ptToastSlideOut .3s ease forwards; }
        @media(max-width:560px) {
            .gym-toast { top: 82px; right: 16px; }
        }

        @media(max-width:900px) {
            .pt-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:620px) {
            .pt-grid {
                grid-template-columns: 1fr
            }

            .pt-hero {
                padding: 65px 18px
            }

            .pt-meta {
                grid-template-columns: 1fr 1fr
            }
        }
    </style>

    <div class="pt-catalog">
        <section class="pt-hero">
            <div class="pt-kicker">GYMFIT · PERSONAL TRAINING</div>
            <h1>Đầu tư cho <span>phiên bản tốt hơn</span></h1>
            <p>Chọn chương trình huấn luyện phù hợp với mục tiêu của bạn. Đội ngũ huấn luyện viên sẽ đồng hành và giúp bạn
                tập luyện đúng phương pháp.</p>
        </section>

        @if (session('success'))
            <div class="gym-toast success-toast" id="ptSuccessToast" role="status" aria-live="polite">
                <div class="toast-icon" aria-hidden="true">✓</div>
                <div class="toast-content">
                    <strong>Thành công</strong>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="toast-close" aria-label="Đóng thông báo"
                    onclick="closePtToast('ptSuccessToast')">×</button>
            </div>
        @endif
        @if (session('error'))
            <div class="gym-toast error-toast" id="ptErrorToast" role="alert" aria-live="assertive">
                <div class="toast-icon" aria-hidden="true">!</div>
                <div class="toast-content">
                    <strong>Không thể thực hiện</strong>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="toast-close" aria-label="Đóng thông báo"
                    onclick="closePtToast('ptErrorToast')">×</button>
            </div>
        @endif

        <main class="pt-wrap">
            <header class="pt-intro">
                <div class="pt-kicker">Chương trình huấn luyện</div>
                <h2>CÁC GÓI PT TẠI GYMFIT</h2>
                <p>Thông tin minh bạch · Huấn luyện cá nhân · Theo sát tiến độ</p>
            </header>

            @if ($goiPt->count())
                <div class="pt-grid">
                    @foreach ($goiPt as $index => $pt)
                        @php
                            $image = !empty($pt->anh) ? ltrim($pt->anh, '/') : 'img/pt1.jpg';
                            $active = in_array(
                                mb_strtolower(trim($pt->trang_thai ?? ''), 'UTF-8'),
                                ['hoat_dong', 'hoạt_động', 'hoạt động', 'đang hoạt động', 'dang_hoat_dong'],
                                true,
                            );
                        @endphp
                        <article class="pt-card {{ $index === 1 ? 'is-featured' : '' }}">
                            @if ($index === 1)
                                <div class="pt-ribbon">ĐƯỢC QUAN TÂM</div>
                            @endif
                            <div class="pt-photo"><img src="{{ asset($image) }}" alt="{{ $pt->ten_goi_pt }}"
                                    onerror="this.src='{{ asset('img/pt1.jpg') }}'"></div>
                            <div class="pt-card-body">
                                <h3>{{ $pt->ten_goi_pt }}</h3>
                                <div class="pt-price">{{ number_format($pt->gia, 0, ',', '.') }}₫ <small>/ trọn gói</small>
                                </div>
                                <div class="pt-meta">
                                    <div><small>BUỔI HUẤN LUYỆN</small><strong>{{ $pt->so_buoi }} buổi</strong></div>
                                    <div><small>THỜI HẠN</small><strong>{{ $pt->thoi_han_ngay }} ngày</strong></div>
                                </div>
                                <div class="pt-status">{{ $active ? '● ĐANG MỞ ĐĂNG KÝ' : '● TẠM NGƯNG' }}</div>
                                @if ($active)
                                    <a class="pt-action" href="{{ url('/pt-packages/' . $pt->goi_pt_id . '/choose') }}">XEM PT
                                        & CHỌN GÓI</a>
                                @else
                                    <span class="pt-action disabled">HIỆN KHÔNG KHẢ DỤNG</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="pt-empty">
                    <div style="font-size:42px">🏋️</div>
                    <h3>Hiện chưa có gói PT</h3>
                    <p>GymFit sẽ cập nhật chương trình mới trong thời gian tới.</p>
                </div>
            @endif
        </main>
    </div>
<script>
    function closePtToast(id) {
        const toast = document.getElementById(id);
        if (!toast) return;
        toast.classList.add('hide');
        window.setTimeout(() => toast.remove(), 300);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('ptSuccessToast')) {
            window.setTimeout(() => closePtToast('ptSuccessToast'), 4000);
        }
        if (document.getElementById('ptErrorToast')) {
            window.setTimeout(() => closePtToast('ptErrorToast'), 5000);
        }
    });
</script>
@endsection
