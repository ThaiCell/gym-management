@extends('layouts.user')

@section('title', 'Huấn luyện viên - GYM MANAGEMENT')

@section('content')
    <style>
        .trainers-page {
            background: #0b0b0b;
            color: #fff;
            min-height: 100vh
        }

        .trainers-hero {
            padding: 95px 30px;
            text-align: center;
            background: linear-gradient(rgba(0, 0, 0, .72), rgba(0, 0, 0, .86)), url("/img/banner5.avif");
            background-size: cover;
            background-position: center
        }

        .trainers-label {
            color: #e50914;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 3px
        }

        .trainers-hero h1 {
            font-size: 50px;
            font-weight: 900;
            margin: 15px 0
        }

        .trainers-hero h1 span {
            color: #e50914
        }

        .trainers-hero p {
            max-width: 700px;
            margin: auto;
            color: #bbb;
            line-height: 1.8
        }

        .trainers-section {
            max-width: 1400px;
            margin: auto;
            padding: 75px 60px
        }

        .trainers-heading {
            text-align: center;
            margin-bottom: 45px
        }

        .trainers-heading h2 {
            font-size: 36px;
            margin: 10px 0
        }

        .trainers-heading p {
            color: #888
        }

        .trainers-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 25px
        }

        .trainer-card {
            background: #151515;
            border: 1px solid #292929;
            border-radius: 8px;
            overflow: hidden;
            transition: .3s
        }

        .trainer-card:hover {
            transform: translateY(-7px);
            border-color: #e50914
        }

        .trainer-image {
            height: 330px;
            overflow: hidden
        }

        .trainer-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .4s
        }

        .trainer-card:hover .trainer-image img {
            transform: scale(1.04)
        }

        .trainer-body {
            padding: 28px
        }

        .trainer-body h3 {
            font-size: 24px;
            margin: 0 0 5px
        }

        .trainer-role {
            color: #e50914;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px
        }

        .trainer-description {
            color: #999;
            line-height: 1.7;
            font-size: 14px;
            margin-bottom: 20px
        }

        .trainer-info {
            border-top: 1px solid #292929;
            padding-top: 18px
        }

        .trainer-info-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 13px
        }

        .trainer-info-row span:first-child {
            color: #777
        }

        .trainer-info-row span:last-child {
            color: #ddd;
            text-align: right;
            overflow-wrap: anywhere
        }

        .trainer-avatar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 30% 20%, rgba(229, 9, 20, .45), transparent 35%), linear-gradient(135deg, #242424, #0b0b0b);
            color: #fff;
            font-size: 64px;
            font-weight: 900;
            letter-spacing: 4px
        }

        .trainer-empty {
            grid-column: 1/-1;
            padding: 60px 25px;
            text-align: center;
            background: #151515;
            border: 1px solid #292929;
            border-radius: 8px
        }

        .trainer-empty-icon {
            font-size: 48px;
            margin-bottom: 15px
        }

        .trainer-empty h3 {
            font-size: 22px;
            margin-bottom: 8px
        }

        .trainer-empty p {
            color: #888
        }

        .trainer-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 12px;
            margin-top: 22px;
            border: 1px solid #555;
            border-radius: 5px;
            color: #fff;
            background: transparent;
            font-family: inherit;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: .3s
        }

        .trainer-btn:hover {
            background: #e50914;
            border-color: #e50914;
            color: #fff
        }

        .trainers-cta {
            padding: 75px 30px;
            text-align: center;
            background: #111;
            border-top: 1px solid #222
        }

        .trainers-cta h2 {
            font-size: 36px;
            margin-bottom: 15px
        }

        .trainers-cta p {
            color: #999;
            margin-bottom: 25px
        }

        .cta-btn {
            display: inline-block;
            background: #e50914;
            color: #fff;
            padding: 14px 28px;
            border-radius: 5px;
            font-weight: bold;
            transition: .3s
        }

        .cta-btn:hover {
            background: #ff2633;
            color: #fff;
            transform: translateY(-2px)
        }

        .trainer-modal {
            /* Giữ hộp thoại ở chính giữa màn hình */
            position: fixed;
            inset: 0;
            margin: auto;
            width: min(560px, calc(100% - 30px));
            max-height: 85vh;
            overflow-y: auto;
            padding: 0;
            color: #fff;
            background: #151515;
            border: 1px solid #383838;
            border-radius: 14px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .7)
        }

        .trainer-modal::backdrop {
            background: rgba(0, 0, 0, .82);
            backdrop-filter: blur(5px)
        }

        .trainer-modal-header {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 15px;
            padding: 28px 26px 24px;
            background: linear-gradient(135deg, #241011, #151515);
            border-bottom: 1px solid #333
        }

        .trainer-modal-photo {
            width: 124px;
            height: 124px;
            flex-shrink: 0;
            overflow: hidden;
            border: 3px solid #e50914;
            border-radius: 50%;
            background: #222;
            box-shadow: 0 0 0 6px rgba(229, 9, 20, .12);
        }

        .trainer-modal-photo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .trainer-modal-photo .trainer-avatar {
            font-size: 36px;
            letter-spacing: 1px;
        }

        .trainer-modal-heading {
            min-width: 0;
        }

        .trainer-modal-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            padding: 18px 26px 4px;
        }

        .trainer-modal-stat {
            padding: 16px 12px;
            text-align: center;
            background: #202020;
            border: 1px solid #333;
            border-radius: 10px;
        }

        .trainer-modal-stat strong {
            display: block;
            color: #fff;
            font-size: 27px;
            line-height: 1.3;
        }

        .trainer-modal-stat span {
            display: block;
            margin-top: 6px;
            color: #aaa;
            font-size: 12px;
        }

        .trainer-modal-label {
            color: #ff2633;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px
        }

        .trainer-modal-header h2 {
            margin: 12px 0 6px;
            font-size: 26px;
            font-weight: 800
        }

        .trainer-modal-header p {
            margin: 0;
            color: #bbb;
            line-height: 1.6
        }

        .trainer-modal-close {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            color: #fff;
            background: #292929;
            border: 1px solid #444;
            border-radius: 50%;
            font-size: 25px;
            cursor: pointer
        }

        .trainer-modal-close:hover {
            background: #e50914;
            border-color: #e50914
        }

        .trainer-modal-content {
            padding: 10px 26px
        }

        .trainer-profile-item {
            display: flex;
            flex-direction: column;
            gap: 7px;
            padding: 16px 0;
            border-bottom: 1px solid #292929
        }

        .trainer-profile-item span {
            color: #999;
            font-size: 13px
        }

        .trainer-profile-item strong {
            color: #f5f5f5;
            font-size: 15px;
            line-height: 1.7;
            overflow-wrap: anywhere;
            white-space: pre-line
        }

        .trainer-modal-footer {
            padding: 0 26px 26px
        }

        .trainer-modal-footer .trainer-btn {
            margin-top: 8px
        }

        @media(max-width:1000px) {
            .trainers-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .trainers-section {
                padding: 60px 30px
            }
        }

        @media(max-width:650px) {
            .trainers-grid {
                grid-template-columns: 1fr
            }

            .trainers-hero h1 {
                font-size: 38px
            }

            .trainers-section {
                padding: 50px 20px
            }

            .trainers-heading h2,
            .trainers-cta h2 {
                font-size: 30px
            }
        }

        @media(max-width:500px) {

            .trainer-modal-header,
            .trainer-modal-content {
                padding-left: 18px;
                padding-right: 18px
            }

            .trainer-modal-stats {
                padding-left: 18px;
                padding-right: 18px;
                gap: 8px;
            }

            .trainer-modal-stat strong {
                font-size: 23px;
            }

            .trainer-modal-header h2 {
                font-size: 21px;
            }
        }
    </style>

    <div class="trainers-page">
        <section class="trainers-hero">
            <span class="trainers-label">GYMFIT TRAINERS</span>
            <h1>ĐỘI NGŨ <span>HUẤN LUYỆN VIÊN</span></h1>
            <p>Đội ngũ huấn luyện viên giàu kinh nghiệm, hỗ trợ hội viên xây dựng kế hoạch tập luyện phù hợp.</p>
        </section>

        <section class="trainers-section">
            <div class="trainers-heading">
                <span class="trainers-label">HUẤN LUYỆN VIÊN</span>
                <h2>ĐỒNG HÀNH CÙNG BẠN</h2>
                <p>Lựa chọn huấn luyện viên phù hợp với mục tiêu tập luyện.</p>
            </div>

            <div class="trainers-grid">
                @forelse ($trainers as $trainer)
                    @php
                        $initials = collect(preg_split('/\s+/', trim($trainer->ho_ten)))
                            ->filter()
                            ->map(fn($word) => mb_substr($word, 0, 1))
                            ->take(2)
                            ->implode('');

                        $image = trim($trainer->anh ?? '');
                        $imagePath = $image !== '' ? public_path($image) : '';
                        $hasImage = $image !== '' && file_exists($imagePath);
                        $hocVienDangPt = $trainer->so_hoc_vien ?? 0;
                        $soLich = $trainer->so_lich ?? 0;
                    @endphp

                    <div class="trainer-card">
                        <div class="trainer-image">
                            @if ($hasImage)
                                <img src="{{ asset($image) }}" alt="{{ $trainer->ho_ten }}"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="trainer-avatar" style="display:none">{{ mb_strtoupper($initials) }}</div>
                            @else
                                <div class="trainer-avatar">{{ mb_strtoupper($initials) }}</div>
                            @endif
                        </div>

                        <div class="trainer-body">
                            <h3>{{ $trainer->ho_ten }}</h3>
                            <div class="trainer-role">HUẤN LUYỆN VIÊN</div>
                            <p class="trainer-description">{{ $trainer->chuyen_mon ?: 'Chưa cập nhật chuyên môn.' }}</p>

                            <div class="trainer-info">
                                <div class="trainer-info-row"><span>Chuyên
                                        môn</span><span>{{ $trainer->chuyen_mon ?: 'Chưa cập nhật' }}</span></div>
                                <div class="trainer-info-row"><span>Điện
                                        thoại</span><span>{{ $trainer->so_dien_thoai ?: 'Chưa cập nhật' }}</span></div>
                                <div class="trainer-info-row"><span>Học viên PT</span><span>{{ $hocVienDangPt }}</span>
                                </div>
                                <div class="trainer-info-row"><span>Lịch PT</span><span>{{ $soLich }} lịch</span>
                                </div>
                            </div>

                            <button type="button" class="trainer-btn"
                                onclick="document.getElementById('trainerModal{{ $trainer->pt_id }}').showModal()">
                                Xem thêm
                            </button>
                        </div>
                    </div>

                    <dialog id="trainerModal{{ $trainer->pt_id }}" class="trainer-modal">
                        <div class="trainer-modal-header">
                            <button type="button" class="trainer-modal-close" style="position:absolute;right:14px;top:14px"
                                aria-label="Đóng hồ sơ" onclick="this.closest('dialog').close()">&times;</button>

                            <div class="trainer-modal-photo">
                                @if ($hasImage)
                                    <img src="{{ asset($image) }}" alt="{{ $trainer->ho_ten }}"
                                        onerror="this.style.display='none'; this.parentElement.querySelector('.trainer-avatar').style.display='flex';">
                                    <div class="trainer-avatar" style="display:none">{{ mb_strtoupper($initials) }}</div>
                                @else
                                    <div class="trainer-avatar">{{ mb_strtoupper($initials) }}</div>
                                @endif
                            </div>

                            <div class="trainer-modal-heading">
                                <span class="trainer-modal-label">GYMFIT · HỒ SƠ HUẤN LUYỆN VIÊN</span>
                                <h2>{{ $trainer->ho_ten }}</h2>
                                <p>{{ $trainer->chuyen_mon ?: 'Chưa cập nhật chuyên môn' }}</p>
                            </div>
                        </div>

                        <div class="trainer-modal-stats">
                            <div class="trainer-modal-stat">
                                <strong>{{ $hocVienDangPt }}</strong>
                                <span>HỌC VIÊN PT</span>
                            </div>
                            <div class="trainer-modal-stat">
                                <strong>{{ $soLich }}</strong>
                                <span>LỊCH PT</span>
                            </div>
                        </div>

                        <div class="trainer-modal-content">
                            <div class="trainer-profile-item">
                                <span>Họ và tên</span>
                                <strong>{{ $trainer->ho_ten }}</strong>
                            </div>
                            <div class="trainer-profile-item">
                                <span>Chuyên môn</span>
                                <strong>{{ $trainer->chuyen_mon ?: 'Chưa cập nhật' }}</strong>
                            </div>
                            <div class="trainer-profile-item">
                                <span>Điện thoại</span>
                                <strong>{{ $trainer->so_dien_thoai ?: 'Chưa cập nhật' }}</strong>
                            </div>
                            <div class="trainer-profile-item">
                                <span>Bằng cấp và giải thưởng</span>
                                <strong>{{ $trainer->thanh_tich ?: 'Chưa cập nhật' }}</strong>
                            </div>
                        </div>

                        <div class="trainer-modal-footer">
                            <button type="button" class="trainer-btn" onclick="this.closest('dialog').close()">Đóng hồ
                                sơ</button>
                        </div>
                    </dialog>
                @empty
                    <div class="trainer-empty">
                        <div class="trainer-empty-icon">🏋️</div>
                        <h3>Chưa có huấn luyện viên</h3>
                        <p>Hiện tại chưa có huấn luyện viên đang hoạt động.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="trainers-cta">
            <h2>CẦN HỖ TRỢ LỰA CHỌN?</h2>
            <p>Liên hệ với GYMFIT để được tư vấn huấn luyện viên phù hợp.</p>
            <a href="/contact" class="cta-btn">LIÊN HỆ NGAY</a>
        </section>
    </div>
@endsection
