@extends('layouts.admin')

@section('content')
    <div class="welcome">

        <div>
            <h2>
                Chào mừng đến với
                <span>Gym Management</span>
            </h2>

            <p>
                Tổng quan hoạt động phòng gym.
            </p>
        </div>

        <a href="{{ url('/') }}" class="home-button">Về trang chủ</a>
    </div>


    <!-- THỐNG KÊ -->

    <div class="stats">

        <!-- HỘI VIÊN -->

        <div class="card">

            <div class="card-header">

                <span>Tổng hội viên</span>

                <div class="card-icon">
                    👤
                </div>

            </div>

            <h3>{{ number_format($tongHoiVien) }}</h3>

            <p>Số hội viên trong hệ thống</p>

        </div>


        <!-- ĐANG HOẠT ĐỘNG -->

        <div class="card">

            <div class="card-header">

                <span>Đang hoạt động</span>

                <div class="card-icon">
                    ✓
                </div>

            </div>

            <h3>{{ number_format($hoiVienDangHoatDong) }}</h3>

            <p>Hội viên đang có gói tập</p>

        </div>


        <!-- DOANH THU -->

        <div class="card">

            <div class="card-header">

                <span>Doanh thu</span>

                <div class="card-icon">
                    ₫
                </div>

            </div>

            <h3>
                {{ number_format($doanhThu, 0, ',', '.') }} ₫
            </h3>

            <p>Tổng các hóa đơn đã thanh toán</p>

        </div>


        <!-- GÓI TẬP -->

        <div class="card">

            <div class="card-header">

                <span>Gói tập</span>

                <div class="card-icon">
                    ★
                </div>

            </div>

            <h3>{{ number_format($tongGoiTap) }}</h3>

            <p>Gói tập đang hoạt động</p>

        </div>

    </div>


    <!-- DỮ LIỆU GẦN ĐÂY -->

    <div class="dashboard-grid">


        <!-- BIỂU ĐỒ -->

        <div class="panel">

            <h3>Doanh thu 6 tháng gần đây</h3>

            @php
                $doanhThuCaoNhat = max(1, $doanhThuTheoThang->max('doanh_thu'));
            @endphp

            <div class="revenue-chart" role="img" aria-label="Biểu đồ doanh thu đã thanh toán trong 6 tháng gần đây">
                @foreach ($doanhThuTheoThang as $thang)
                    @php
                        $chieuCaoCot = ($thang['doanh_thu'] / $doanhThuCaoNhat) * 100;
                    @endphp

                    <div class="revenue-chart-column">
                        <span class="revenue-chart-value">
                            {{ number_format($thang['doanh_thu'], 0, ',', '.') }} ₫
                        </span>
                        <div class="revenue-chart-track">
                            <div class="revenue-chart-bar" style="height: {{ $chieuCaoCot }}%"></div>
                        </div>
                        <span class="revenue-chart-label">{{ $thang['nhan'] }}</span>
                    </div>
                @endforeach
            </div>

        </div>


        <!-- HOẠT ĐỘNG -->

        <div class="panel">

            <h3>Hóa đơn gần đây</h3>

            @forelse($hoatDongGanDay as $hoaDon)
                <div class="activity">

                    <div class="activity-dot"></div>

                    <div>

                        <p>
                            {{ $hoaDon->ho_ten }}
                        </p>

                        <small>
                            Hóa đơn #{{ $hoaDon->hoa_don_id }}
                            -
                            {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} ₫
                        </small>

                    </div>

                </div>

            @empty

                <div
                    style="
                padding:30px 0;
                text-align:center;
                color:#777;
            ">
                    Chưa có dữ liệu hóa đơn.
                </div>
            @endforelse

        </div>

    </div>
@endsection
