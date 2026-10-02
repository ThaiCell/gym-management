@extends('layouts.app')

@section('content')

<div class="page-content">

    <div class="page-header">

        <div>
            <h1>Quản lý lịch tập</h1>

            <p>
                Danh sách lịch PT trong hệ thống Gym Management
            </p>
        </div>

        <div class="total-count">
            Tổng: {{ $schedules->count() }} lịch tập
        </div>

    </div>


    {{-- BỘ LỌC --}}

    <div class="filter-box">

        <form method="GET" action="/admin/schedules">

            <input
                type="text"
                name="q"
                value="{{ $keyword }}"
                placeholder="Tìm hội viên hoặc huấn luyện viên..."
            >

            <select name="status">

                <option value="">
                    Tất cả trạng thái
                </option>

                <option
                    value="đã đặt lịch"
                    {{ $status == 'đã đặt lịch' ? 'selected' : '' }}
                >
                    Đã đặt lịch
                </option>

                <option
                    value="đã hoàn thành"
                    {{ $status == 'đã hoàn thành' ? 'selected' : '' }}
                >
                    Đã hoàn thành
                </option>

                <option
                    value="đã hủy"
                    {{ $status == 'đã hủy' ? 'selected' : '' }}
                >
                    Đã hủy
                </option>

            </select>

            <button type="submit">
                Tìm kiếm
            </button>

        </form>

    </div>


    {{-- DANH SÁCH --}}

    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>HỘI VIÊN</th>

                    <th>HUẤN LUYỆN VIÊN</th>

                    <th>GÓI PT</th>

                    <th>BẮT ĐẦU</th>

                    <th>KẾT THÚC</th>

                    <th>TRẠNG THÁI</th>

                    <th>THAO TÁC</th>

                </tr>

            </thead>

            <tbody>

                @forelse($schedules as $schedule)

                    <tr>

                        <td class="id">
                            #{{ $schedule->lich_pt_id }}
                        </td>


                        <td>

                            <strong>
                                {{ $schedule->hoi_vien_ten }}
                            </strong>

                            <small>
                                {{ $schedule->hoi_vien_email }}
                            </small>

                        </td>


                        <td>

                            <strong>
                                {{ $schedule->pt_ten }}
                            </strong>

                            <small>
                                {{ $schedule->pt_email }}
                            </small>

                        </td>


                        <td>
                            {{ $schedule->ten_goi_pt ?? 'Không xác định' }}
                        </td>


                        <td>

                            {{ \Carbon\Carbon::parse(
                                $schedule->thoi_gian_bat_dau
                            )->format('d/m/Y') }}

                            <br>

                            <span class="time">

                                {{ \Carbon\Carbon::parse(
                                    $schedule->thoi_gian_bat_dau
                                )->format('H:i') }}

                            </span>

                        </td>


                        <td>

                            {{ \Carbon\Carbon::parse(
                                $schedule->thoi_gian_ket_thuc
                            )->format('d/m/Y') }}

                            <br>

                            <span class="time">

                                {{ \Carbon\Carbon::parse(
                                    $schedule->thoi_gian_ket_thuc
                                )->format('H:i') }}

                            </span>

                        </td>


                        <td>

                            @if(
                                in_array(
                                    $schedule->trang_thai,
                                    ['đã hoàn thành', 'da_hoan_thanh']
                                )
                            )

                                <span class="status completed">
                                    Đã hoàn thành
                                </span>

                            @elseif(
                                in_array(
                                    $schedule->trang_thai,
                                    ['đã hủy', 'da_huy']
                                )
                            )

                                <span class="status cancelled">
                                    Đã hủy
                                </span>

                            @else

                                <span class="status booked">
                                    Đã đặt lịch
                                </span>

                            @endif

                        </td>


                        <td>

                            <a
                                href="/admin/schedules/{{ $schedule->lich_pt_id }}"
                                class="detail-btn"
                            >
                                Xem chi tiết
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="empty"
                        >
                            Chưa có lịch tập nào.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<style>

.page-content {
    padding: 28px 28px 50px;
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

.total-count {
    color: #aaa;
    font-size: 14px;
}


.filter-box {
    background: #181818;
    border: 1px solid #292929;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 15px;
}

.filter-box form {
    display: flex;
    gap: 10px;
}

.filter-box input,
.filter-box select {
    background: #242424;
    border: 1px solid #383838;
    color: white;
    border-radius: 6px;
    padding: 10px 12px;
    outline: none;
}

.filter-box input {
    width: 330px;
}

.filter-box select {
    min-width: 180px;
}

.filter-box input:focus,
.filter-box select:focus {
    border-color: #ed0010;
}

.filter-box button {
    border: none;
    background: #ed0010;
    color: white;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.filter-box button:hover {
    background: #ff1725;
}


.table-box {
    background: #181818;
    border: 1px solid #292929;
    border-radius: 8px;
    overflow: hidden;
}

.table-box table {
    width: 100%;
    border-collapse: collapse;
}

.table-box th {
    background: #111;
    color: #aaa;
    font-size: 12px;
    text-align: left;
    padding: 13px 15px;
    white-space: nowrap;
}

.table-box td {
    padding: 14px 15px;
    border-top: 1px solid #292929;
    color: #ddd;
    font-size: 13px;
}

.table-box tbody tr:hover {
    background: #1e1e1e;
}

.table-box td strong {
    display: block;
    color: #fff;
}

.table-box td small {
    display: block;
    color: #777;
    margin-top: 4px;
}

.id {
    color: #ff1725 !important;
    font-weight: 600;
}

.time {
    color: #aaa;
    font-size: 12px;
}


.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.status.booked {
    background: rgba(255, 193, 7, .12);
    color: #ffc107;
}

.status.completed {
    background: rgba(0, 200, 83, .12);
    color: #00d66b;
}

.status.cancelled {
    background: rgba(255, 0, 30, .12);
    color: #ff3948;
}


.detail-btn {
    display: inline-block;
    background: #242424;
    border: 1px solid #3a3a3a;
    color: white;
    text-decoration: none;
    padding: 7px 11px;
    border-radius: 5px;
    font-size: 12px;
}

.detail-btn:hover {
    background: #ed0010;
    border-color: #ed0010;
}


.empty {
    text-align: center !important;
    color: #777 !important;
    padding: 40px !important;
}


@media (max-width: 1100px) {

    .table-box {
        overflow-x: auto;
    }

    .table-box table {
        min-width: 1100px;
    }

}

</style>

@endsection