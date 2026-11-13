@extends('layouts.dashboard')

@section('title', 'Dashboard Nhân viên')
@section('role_name', 'Nhân viên')



@section('content')


    {{-- POPUP THONG BAO FLASH: dung chung cho dashboard --}}
    @if (session()->has('success') || session()->has('error'))
        @php
            $flashMessage = session('success') ?? session('error');
            $flashType = session()->has('success') ? 'success' : 'error';
        @endphp
        <div id="gymDashboardFlash" class="gym-dashboard-flash {{ $flashType }}" role="alert" aria-live="assertive">
            <span class="gym-dashboard-flash-icon">{{ $flashType === 'success' ? '✓' : '!' }}</span>
            <div class="gym-dashboard-flash-content">
                <strong>{{ $flashType === 'success' ? 'Thành công' : 'Có lỗi xảy ra' }}</strong>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" onclick="document.getElementById('gymDashboardFlash')?.remove()"
                aria-label="Đóng">&times;</button>
        </div>
        <style>
            .gym-dashboard-flash {
                position: fixed;
                z-index: 999999;
                top: 92px;
                right: 24px;
                width: min(420px, calc(100vw - 32px));
                display: flex;
                align-items: center;
                gap: 13px;
                padding: 16px 18px;
                border-radius: 12px;
                background: #171717;
                border: 1px solid #3b3b3b;
                box-shadow: 0 14px 40px #0009;
                color: #fff;
                font: 500 14px/1.45 system-ui, sans-serif;
                animation: gymFlashIn .25s ease-out
            }

            .gym-dashboard-flash.success {
                border-left: 4px solid #22c55e
            }

            .gym-dashboard-flash.error {
                border-left: 4px solid #ef4444
            }

            .gym-dashboard-flash-icon {
                width: 30px;
                height: 30px;
                flex: 0 0 30px;
                display: grid;
                place-items: center;
                border-radius: 50%;
                font-weight: 900;
                background: #252525;
                color: #4ade80
            }

            .gym-dashboard-flash.error .gym-dashboard-flash-icon {
                color: #f87171
            }

            .gym-dashboard-flash-content {
                display: flex;
                flex: 1;
                min-width: 0;
                flex-direction: column;
                gap: 3px
            }

            .gym-dashboard-flash-content strong {
                font-size: 13px
            }

            .gym-dashboard-flash-content span {
                overflow-wrap: anywhere;
                color: #ddd
            }

            .gym-dashboard-flash button {
                border: 0;
                background: transparent;
                color: #aaa;
                font-size: 24px;
                cursor: pointer;
                padding: 0 2px
            }

            .gym-dashboard-flash button:hover {
                color: #fff
            }

            @keyframes gymFlashIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }

            @media(max-width:600px) {
                .gym-dashboard-flash {
                    top: 82px;
                    right: 12px
                }
            }

            .staff-role-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #ff2633;
                box-shadow: 0 0 8px rgba(255, 38, 51, .75);
            }
        </style>
        <script>
            (function() {
                const el = document.getElementById('gymDashboardFlash');
                if (!el) return;
                setTimeout(() => {
                    if (el.isConnected) {
                        el.style.transition = 'opacity .3s,transform .3s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(-8px)';
                        setTimeout(() => el.remove(), 320)
                    }
                }, 5000)
            })();
        </script>
    @endif

    <style>
        .staff-role-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff2633;
            box-shadow: 0 0 8px rgba(255, 38, 51, .75);
        }

        .staff-dashboard {
            max-width: 1400px;
            margin: auto;
        }

        .staff-hero {
            padding: 42px 45px;
            margin-bottom: 28px;
            border-radius: 18px;
            background:
                linear-gradient(110deg,
                    #111,
                    #171717,
                    #090909);
            border: 1px solid #252525;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }

        .staff-label {
            color: #e50914;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .staff-hero h1 {
            margin: 0;
            color: #fff;
            font-size: 34px;
        }

        .staff-hero p {
            color: #888;
            margin: 10px 0 18px;
            font-size: 14px;
        }

        .staff-role {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 2px;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .10);
            border: 1px solid rgba(229, 9, 20, .45);
            color: #ff4651;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .staff-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .staff-stat {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 22px;
            min-height: 105px;
            background: #111;
            border: 1px solid #252525;
            border-radius: 15px;
            transition: .25s;
        }

        .staff-stat:hover {
            transform: translateY(-4px);
            border-color: #e50914;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .3);
        }

        .staff-hero svg {
            width: 19px;
            height: 19px;
            flex: 0 0 19px;
            fill: none;
            stroke: #fff;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            vertical-align: middle;
            margin-right: 7px;
        }

        .staff-stat-icon svg,
        .staff-item-icon svg,
        .staff-empty-icon svg {
            width: 23px;
            height: 23px;
            fill: none;
            stroke: #ff4651;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .staff-stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            background: rgba(229, 9, 20, .1);
            font-size: 23px;
        }

        .staff-stat span {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .staff-stat strong {
            color: #fff;
            font-size: 28px;
        }

        .staff-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .staff-card {
            background: #111;
            border: 1px solid #252525;
            border-radius: 17px;
            overflow: hidden;
        }

        .staff-card-header {
            padding: 22px 24px;
            border-bottom: 1px solid #222;
        }

        .staff-card-label {
            color: #e50914;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .staff-card-header h2 {
            margin: 5px 0 0;
            color: #fff;
            font-size: 19px;
        }

        .staff-list {
            padding: 7px 24px 18px;
        }

        .staff-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 0;
            border-bottom: 1px solid #202020;
        }

        .staff-item:last-child {
            border-bottom: none;
        }

        .staff-item-left {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .staff-item-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #191919;
            font-size: 18px;
        }

        .staff-item-info h3 {
            margin: 0 0 5px;
            color: #eee;
            font-size: 14px;
        }

        .staff-item-info p {
            margin: 0;
            color: #777;
            font-size: 11px;
        }

        .hero-role {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .12);
            border: 1px solid rgba(229, 9, 20, .3);
            color: #ff4a52;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        /* Badge trạng thái đăng ký gói và hóa đơn */
        .staff-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            max-width: 150px;
            padding: 7px 13px;
            border-radius: 20px;
            background: rgba(229, 9, 20, .12);
            border: 1px solid rgba(229, 9, 20, .35);
            color: #ff4a52;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .2px;
            line-height: 1.2;
            white-space: nowrap;
            text-align: center;
        }

        @media (max-width: 600px) {
            .staff-badge {
                max-width: 112px;
                padding: 6px 9px;
                font-size: 10px;
                white-space: normal;
            }
        }

        .staff-empty {
            text-align: center;
            padding: 50px 20px;
        }

        .staff-empty-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: #191919;
            font-size: 25px;
        }

        .staff-empty h3 {
            margin: 0 0 7px;
            color: #ddd;
            font-size: 15px;
        }

        .staff-empty p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 1000px) {

            .staff-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .staff-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .staff-hero {
                padding: 30px 25px;
            }

            .staff-hero h1 {
                font-size: 26px;
            }

            .staff-stats {
                grid-template-columns: 1fr;
            }

        }
    </style>


    <div class="staff-dashboard">

        {{-- HERO --}}

        <div class="staff-hero">

            <div class="staff-label">
                GYMFIT • STAFF AREA
            </div>

            <h1>
                Xin chào, {{ session('user')->ho_ten }}
            </h1>

            <p>
                Quản lý hội viên, check-in và các hoạt động của phòng Gym.
            </p>

            <div class="staff-role">
                <span class="staff-role-dot"></span> NHÂN VIÊN
            </div>

            <div style="
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:18px;
">

                <a href="/staff/members"
                    style="
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:11px 16px;
            border-radius:7px;
            background:#e50914;
            color:#fff;
            text-decoration:none;
            font-size:11px;
            font-weight:800;
            transition:.25s;
        "
                    onmouseover="this.style.background='#ff1824'" onmouseout="this.style.background='#e50914'">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="8" r="3" />
                        <path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6m2 3a5 5 0 0 1 3 5" />
                    </svg> QUẢN LÝ HỘI VIÊN

                </a>


                <a href="/staff/contacts"
                    style="
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:11px 16px;
            border-radius:7px;
            background:#222;
            border:1px solid #333;
            color:#ddd;
            text-decoration:none;
            font-size:11px;
            font-weight:800;
            transition:.25s;
        "
                    onmouseover="this.style.borderColor='#e50914';this.style.color='#fff'"
                    onmouseout="this.style.borderColor='#333';this.style.color='#ddd'">

                    ✉ XỬ LÝ LIÊN HỆ

                </a>


                <a href="/staff/check-in"
                    style="
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:11px 16px;
            border-radius:7px;
            background:#222;
            border:1px solid #333;
            color:#ddd;
            text-decoration:none;
            font-size:11px;
            font-weight:800;
            transition:.25s;
        "
                    onmouseover="this.style.borderColor='#e50914';this.style.color='#fff'"
                    onmouseout="this.style.borderColor='#333';this.style.color='#ddd'">

                    ✓ CHECK-IN HỘI VIÊN

                </a>

            </div>

        </div>


        {{-- STATS --}}

        <div class="staff-stats">

            <div class="staff-stat">

                <div class="staff-stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="8" r="3" />
                        <path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6m2 3a5 5 0 0 1 3 5" />
                    </svg>
                </div>

                <div>
                    <span>Tổng hội viên</span>
                    <strong>{{ $tongHoiVien }}</strong>
                </div>

            </div>


            <div class="staff-stat">

                <div class="staff-stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="8" />
                        <path d="m8 12 3 3 5-6" />
                    </svg>
                </div>

                <div>
                    <span>Lượt Check-in</span>
                    <strong>{{ $tongCheckIn }}</strong>
                </div>

            </div>


            <div class="staff-stat">

                <div class="staff-stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <path d="M2 10h20M6 15h4" />
                    </svg>
                </div>

                <div>
                    <span>Hóa đơn</span>
                    <strong>{{ $tongHoaDon }}</strong>
                </div>

            </div>


            <div class="staff-stat">

                <div class="staff-stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m3 7 9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4M12 11v10" />
                    </svg>
                </div>

                <div>
                    <span>Đăng ký gói</span>
                    <strong>{{ $tongDangKyGoi }}</strong>
                </div>

            </div>

        </div>


        {{-- CONTENT --}}

        <div class="staff-grid">

            {{-- HỘI VIÊN --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        MEMBERS
                    </div>

                    <h2>
                        Hội viên gần đây
                    </h2>

                </div>


                @if ($hoiVienGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($hoiVienGanDay as $hv)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        👤
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $hv->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $hv->email ?? 'Không có email' }}
                                        </p>

                                    </div>

                                </div>

                                <div class="hero-role">
                                    ● HỘI VIÊN
                                </div>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="9" cy="8" r="3" />
                                <path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6m2 3a5 5 0 0 1 3 5" />
                            </svg>
                        </div>

                        <h3>
                            Chưa có hội viên
                        </h3>

                        <p>
                            Hệ thống chưa có dữ liệu hội viên.
                        </p>

                    </div>

                @endif

            </div>


            {{-- CHECK IN --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        CHECK-IN
                    </div>

                    <h2>
                        Check-in gần đây
                    </h2>

                </div>


                @if ($checkInGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($checkInGanDay as $check)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="12" cy="12" r="8" />
                                            <path d="m8 12 3 3 5-6" />
                                        </svg>
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $check->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $check->thoi_gian }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    CHECK-IN
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="12" r="8" />
                                <path d="m8 12 3 3 5-6" />
                            </svg>
                        </div>

                        <h3>
                            Chưa có check-in
                        </h3>

                        <p>
                            Chưa có lượt check-in nào gần đây.
                        </p>

                    </div>

                @endif

            </div>


            {{-- ĐĂNG KÝ GÓI --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        MEMBERSHIP
                    </div>

                    <h2>
                        Đăng ký gói gần đây
                    </h2>

                </div>


                @if ($dangKyGoiGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($dangKyGoiGanDay as $dk)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="m3 7 9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4M12 11v10" />
                                        </svg>
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $dk->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ $dk->ten_goi }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    {{ \App\Support\StatusLabel::format($dk->trang_thai) }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="m3 7 9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4M12 11v10" />
                            </svg>
                        </div>

                        <h3>
                            Chưa có đăng ký
                        </h3>

                        <p>
                            Chưa có đăng ký gói nào gần đây.
                        </p>

                    </div>

                @endif

            </div>


            {{-- HÓA ĐƠN --}}

            <div class="staff-card">

                <div class="staff-card-header">

                    <div class="staff-card-label">
                        PAYMENT
                    </div>

                    <a href="/staff/payments"
                        style="
                            display:inline-block;
                            margin-top:10px;
                            color:#e50914;
                            font-size:11px;
                            font-weight:800;
                            text-decoration:none;
                        ">
                        QUẢN LÝ HÓA ĐƠN →
                    </a>

                </div>


                @if ($hoaDonGanDay->count() > 0)

                    <div class="staff-list">

                        @foreach ($hoaDonGanDay as $hoaDon)
                            <div class="staff-item">

                                <div class="staff-item-left">

                                    <div class="staff-item-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="2" y="5" width="20" height="14" rx="2" />
                                            <path d="M2 10h20M6 15h4" />
                                        </svg>
                                    </div>

                                    <div class="staff-item-info">

                                        <h3>
                                            {{ $hoaDon->ho_ten }}
                                        </h3>

                                        <p>
                                            {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VNĐ
                                            •
                                            {{ $hoaDon->ngay_lap }}
                                        </p>

                                    </div>

                                </div>

                                <span class="staff-badge">
                                    {{ \App\Support\StatusLabel::format($hoaDon->trang_thai) }}
                                </span>

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="staff-empty">

                        <div class="staff-empty-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="2" y="5" width="20" height="14" rx="2" />
                                <path d="M2 10h20M6 15h4" />
                            </svg>
                        </div>

                        <h3>
                            Chưa có hóa đơn
                        </h3>

                        <p>
                            Hiện chưa có hóa đơn nào trong hệ thống.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
