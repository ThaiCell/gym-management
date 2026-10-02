@extends('layouts.app')

@section('content')

<div class="page-content">

    <div class="page-header">

        <div>
            <h1>Chi tiết lịch tập</h1>

            <p>
                Thông tin lịch PT #{{ $schedule->lich_pt_id }}
            </p>
        </div>

        <a
            href="/admin/schedules"
            class="back-btn"
        >
            ← Quay lại danh sách
        </a>

    </div>


    {{-- THÔNG TIN LỊCH --}}

    <div class="card">

        <div class="card-title">
            Thông tin lịch tập
        </div>

        <div class="info-grid">

            <div class="info-item">
                <span>Mã lịch tập</span>

                <strong>
                    #{{ $schedule->lich_pt_id }}
                </strong>
            </div>


            <div class="info-item">
                <span>Trạng thái</span>

                @if(
                    in_array(
                        $schedule->trang_thai,
                        ['đã hoàn thành', 'da_hoan_thanh']
                    )
                )

                    <strong class="status completed">
                        Đã hoàn thành
                    </strong>

                @elseif(
                    in_array(
                        $schedule->trang_thai,
                        ['đã hủy', 'da_huy']
                    )
                )

                    <strong class="status cancelled">
                        Đã hủy
                    </strong>

                @else

                    <strong class="status booked">
                        Đã đặt lịch
                    </strong>

                @endif

            </div>


            <div class="info-item">
                <span>Thời gian bắt đầu</span>

                <strong>
                    {{ \Carbon\Carbon::parse(
                        $schedule->thoi_gian_bat_dau
                    )->format('d/m/Y H:i') }}
                </strong>
            </div>


            <div class="info-item">
                <span>Thời gian kết thúc</span>

                <strong>
                    {{ \Carbon\Carbon::parse(
                        $schedule->thoi_gian_ket_thuc
                    )->format('d/m/Y H:i') }}
                </strong>
            </div>

        </div>

    </div>


    {{-- HỘI VIÊN --}}

    <div class="card">

        <div class="card-title">
            Thông tin hội viên
        </div>

        <div class="info-grid">

            <div class="info-item">
                <span>Họ và tên</span>

                <strong>
                    {{ $schedule->hoi_vien_ten }}
                </strong>
            </div>


            <div class="info-item">
                <span>Email</span>

                <strong>
                    {{ $schedule->hoi_vien_email }}
                </strong>
            </div>

        </div>

    </div>


    {{-- HUẤN LUYỆN VIÊN --}}

    <div class="card">

        <div class="card-title">
            Thông tin huấn luyện viên
        </div>

        <div class="info-grid">

            <div class="info-item">
                <span>Họ và tên</span>

                <strong>
                    {{ $schedule->pt_ten }}
                </strong>
            </div>


            <div class="info-item">
                <span>Email</span>

                <strong>
                    {{ $schedule->pt_email }}
                </strong>
            </div>


            <div class="info-item">
                <span>Số điện thoại</span>

                <strong>
                    {{ $schedule->pt_so_dien_thoai }}
                </strong>
            </div>


            <div class="info-item">
                <span>Chuyên môn</span>

                <strong>
                    {{ $schedule->chuyen_mon }}
                </strong>
            </div>

        </div>

    </div>


    {{-- GÓI PT --}}

    <div class="card">

        <div class="card-title">
            Thông tin gói PT
        </div>

        <div class="info-grid">

            <div class="info-item">
                <span>Tên gói</span>

                <strong>
                    {{ $schedule->ten_goi_pt ?? 'Không xác định' }}
                </strong>
            </div>


            <div class="info-item">
                <span>Giá gói</span>

                <strong>

                    @if($schedule->goi_pt_gia !== null)

                        {{ number_format(
                            $schedule->goi_pt_gia,
                            0,
                            ',',
                            '.'
                        ) }} ₫

                    @else

                        Không xác định

                    @endif

                </strong>
            </div>


            <div class="info-item">
                <span>Số buổi</span>

                <strong>
                    {{ $schedule->goi_pt_so_buoi ?? 0 }} buổi
                </strong>
            </div>


            <div class="info-item">
                <span>Thời hạn</span>

                <strong>
                    {{ $schedule->thoi_han_ngay ?? 0 }} ngày
                </strong>
            </div>

        </div>

    </div>

</div>


<style>

.page-content {
    padding: 28px;
    padding-bottom: 50px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: end;
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0 0 7px;
    font-size: 25px;
}

.page-header p {
    margin: 0;
    color: #888;
    font-size: 14px;
}

.back-btn {
    background: #292929;
    border: 1px solid #3a3a3a;
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 6px;
    font-size: 13px;
}

.back-btn:hover {
    background: #333;
}


.card {
    background: #181818;
    border: 1px solid #292929;
    border-radius: 8px;
    margin-bottom: 18px;
    overflow: hidden;
}

.card-title {
    padding: 17px 20px;
    border-bottom: 1px solid #292929;
    font-size: 17px;
    font-weight: 600;
    color: white;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.info-item {
    padding: 17px 20px;
    border-bottom: 1px solid #292929;
}

.info-item:nth-child(odd) {
    border-right: 1px solid #292929;
}

.info-item span {
    display: block;
    color: #888;
    font-size: 13px;
    margin-bottom: 7px;
}

.info-item strong {
    color: #fff;
    font-size: 14px;
}


.status {
    display: inline-block;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 12px !important;
}

.status.booked {
    color: #ffc107 !important;
    background: rgba(255, 193, 7, .12);
}

.status.completed {
    color: #00d66b !important;
    background: rgba(0, 200, 83, .12);
}

.status.cancelled {
    color: #ff3948 !important;
    background: rgba(255, 0, 30, .12);
}


@media (max-width: 800px) {

    .page-header {
        display: block;
    }

    .back-btn {
        display: inline-block;
        margin-top: 15px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-item:nth-child(odd) {
        border-right: none;
    }

}

</style>

@endsection