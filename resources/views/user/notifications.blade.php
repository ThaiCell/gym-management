@extends('layouts.user')

@section('content')

    <style>
        .notifications-page {
            min-height: calc(100vh - 75px);
            background: #0d0d0d;
            padding: 55px 60px 80px;
            color: #fff;
        }

        .notifications-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* HEADER */
        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .notifications-title h1 {
            margin: 0 0 8px;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .notifications-title p {
            margin: 0;
            color: #999;
            font-size: 14px;
        }

        .unread-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            padding: 7px 13px;
            border: 1px solid #333;
            border-radius: 20px;
            color: #ddd;
            font-size: 13px;
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            background: #e50914;
            border-radius: 50%;
            display: inline-block;
        }

        .read-all-btn {
            border: 1px solid #444;
            background: transparent;
            color: #ddd;
            padding: 11px 18px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            transition: .3s;
        }

        .read-all-btn:hover {
            border-color: #e50914;
            color: #e50914;
        }

        /* LIST */
        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notification-item {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 18px;
            padding: 22px 24px;
            background: #151515;
            border: 1px solid #292929;
            border-radius: 8px;
            transition: .3s;
        }

        .notification-item:hover {
            border-color: #444;
            transform: translateY(-1px);
        }

        /* CHƯA ĐỌC */
        .notification-item.unread {
            background: #171111;
            border-left: 3px solid #e50914;
        }

        .notification-icon {
            width: 45px;
            height: 45px;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #222;
            border-radius: 50%;
            font-size: 19px;
        }

        .notification-item.unread .notification-icon {
            background: rgba(229, 9, 20, .12);
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .notification-title {
            margin: 0;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
        }

        .notification-item:not(.unread) .notification-title {
            color: #ccc;
        }

        .notification-time {
            color: #777;
            font-size: 12px;
            white-space: nowrap;
        }

        .notification-message {
            margin: 9px 0 0;
            color: #999;
            font-size: 14px;
            line-height: 1.7;
        }

        .new-label {
            display: inline-block;
            margin-left: 8px;
            padding: 3px 7px;
            background: #e50914;
            color: #fff;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 700;
            vertical-align: middle;
        }

        /* EMPTY */
        .empty-notifications {
            text-align: center;
            padding: 80px 20px;
            background: #151515;
            border: 1px solid #292929;
            border-radius: 8px;
        }

        .empty-icon {
            font-size: 48px;
            opacity: .45;
            margin-bottom: 18px;
        }

        .empty-notifications h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #ddd;
        }

        .empty-notifications p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .notifications-page {
                padding: 35px 20px 60px;
            }

            .notifications-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .notifications-title h1 {
                font-size: 26px;
            }

            .notification-item {
                padding: 18px;
            }

            .notification-top {
                flex-direction: column;
                gap: 6px;
            }

            .notification-time {
                white-space: normal;
            }
        }
    </style>

    <div class="notifications-page">

        <div class="notifications-container">

            {{-- TIÊU ĐỀ --}}
            <div class="notifications-header">

                <div class="notifications-title">

                    <h1>THÔNG BÁO</h1>

                    <p>
                        Xem các thông báo và cập nhật mới nhất từ GYMFIT.
                    </p>

                    @if ($thongBaoChuaDoc > 0)
                        <div class="unread-count">
                            <span class="unread-dot"></span>
                            {{ $thongBaoChuaDoc }} thông báo chưa đọc
                        </div>
                    @else
                        <div class="unread-count">
                            Không có thông báo chưa đọc
                        </div>
                    @endif

                </div>

                {{-- ĐÁNH DẤU TẤT CẢ --}}
                @if ($thongBaoChuaDoc > 0)
                    <form action="/notifications/read-all" method="POST">
                        @csrf

                        <button type="submit" class="read-all-btn">
                            ✓ ĐÁNH DẤU TẤT CẢ ĐÃ ĐỌC
                        </button>
                    </form>
                @endif

            </div>


            {{-- DANH SÁCH THÔNG BÁO --}}
            @if ($thongBao->count() > 0)
                <div class="notifications-list">

                    @foreach ($thongBao as $tb)
                        <div class="notification-item {{ $tb->da_doc == 0 ? 'unread' : '' }}">

                            {{-- ICON --}}
                            <div class="notification-icon">
                                🔔
                            </div>

                            {{-- NỘI DUNG --}}
                            <div class="notification-content">

                                <div class="notification-top">

                                    <h3 class="notification-title">

                                        {{ $tb->tieu_de }}

                                        @if ($tb->da_doc == 0)
                                            <span class="new-label">MỚI</span>
                                        @endif

                                    </h3>

                                    <span class="notification-time">
                                        {{ \Carbon\Carbon::parse($tb->tao_luc)->format('d/m/Y H:i') }}
                                    </span>

                                </div>

                                <p class="notification-message">
                                    {{ $tb->noi_dung }}
                                </p>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                {{-- KHÔNG CÓ THÔNG BÁO --}}
                <div class="empty-notifications">

                    <div class="empty-icon">
                        🔔
                    </div>

                    <h3>Chưa có thông báo</h3>

                    <p>
                        Hiện tại bạn chưa có thông báo nào.
                    </p>

                </div>
            @endif

        </div>

    </div>

@endsection
