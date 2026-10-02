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

    .filter-box {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 20px;
    }

    .filter-box form {
        display: flex;
        align-items: end;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .filter-item label {
        color: #aaa;
        font-size: 13px;
    }

    .filter-item input {
        background: #222;
        border: 1px solid #444;
        color: #fff;
        padding: 9px 12px;
        border-radius: 6px;
    }

    .btn-filter {
        background: #e50914;
        color: #fff;
        border: 0;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-filter:hover {
        background: #ff1b25;
    }

    .btn-reset {
        background: #292929;
        color: #fff;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 6px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 10px;
        padding: 20px;
    }

    .stat-label {
        color: #999;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .stat-value {
        font-size: 25px;
        font-weight: 700;
        color: #fff;
    }

    .stat-card.revenue {
        border-color: #e50914;
    }

    .stat-card.revenue .stat-value {
        color: #ff1a1a;
    }

    .box {
        background: #171717;
        border: 1px solid #292929;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .box-title {
        padding: 18px 20px;
        border-bottom: 1px solid #292929;
        font-size: 18px;
        font-weight: 700;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #292929;
    }

    .data-row:last-child {
        border-bottom: 0;
    }

    .data-label {
        color: #aaa;
    }

    .data-value {
        font-weight: 700;
    }

    .green {
        color: #00d96b;
    }

    .yellow {
        color: #ffc107;
    }

    .red {
        color: #ff4d4d;
    }

    @media (max-width: 1000px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-title">
    Thống kê hệ thống
</div>

<div class="page-subtitle">
    Thống kê hội viên, doanh thu, check-in và lịch PT
</div>


{{-- BỘ LỌC THỜI GIAN --}}
<div class="filter-box">

    <form method="GET" action="/admin/statistics">

        <div class="filter-item">

            <label>Từ ngày</label>

            <input
                type="date"
                name="from"
                value="{{ $from }}"
            >

        </div>


        <div class="filter-item">

            <label>Đến ngày</label>

            <input
                type="date"
                name="to"
                value="{{ $to }}"
            >

        </div>


        <button type="submit" class="btn-filter">
            Thống kê
        </button>


        <a href="/admin/statistics" class="btn-reset">
            Xóa bộ lọc
        </a>

    </form>

</div>


{{-- THỐNG KÊ CHÍNH --}}
<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-label">
            Tổng hội viên
        </div>

        <div class="stat-value">
            {{ $members }}
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Hội viên đang hoạt động
        </div>

        <div class="stat-value">
            {{ $activeMembers }}
        </div>

    </div>


    <div class="stat-card revenue">

        <div class="stat-label">
            Doanh thu
        </div>

        <div class="stat-value">
            {{ number_format($revenue, 0, ',', '.') }} đ
        </div>

    </div>


    <div class="stat-card">

        <div class="stat-label">
            Check-in
        </div>

        <div class="stat-value">
            {{ $checkIns }}
        </div>

    </div>

</div>


{{-- LỊCH PT --}}
<div class="box">

    <div class="box-title">
        Thống kê lịch PT
    </div>


    <div class="data-row">

        <span class="data-label">
            Tổng lịch PT
        </span>

        <span class="data-value">
            {{ $ptSchedules }}
        </span>

    </div>


    <div class="data-row">

        <span class="data-label">
            Đã hoàn thành
        </span>

        <span class="data-value green">
            {{ $completedSchedules }}
        </span>

    </div>


    <div class="data-row">

        <span class="data-label">
            Đã hủy
        </span>

        <span class="data-value red">
            {{ $cancelledSchedules }}
        </span>

    </div>

</div>


{{-- THANH TOÁN --}}
<div class="box">

    <div class="box-title">
        Thống kê thanh toán
    </div>


    <div class="data-row">

        <span class="data-label">
            Số hóa đơn đã thanh toán
        </span>

        <span class="data-value green">
            {{ $paidInvoices }}
        </span>

    </div>


    <div class="data-row">

        <span class="data-label">
            Tổng doanh thu đã ghi nhận
        </span>

        <span class="data-value red">
            {{ number_format($revenue, 0, ',', '.') }} đ
        </span>

    </div>

</div>

@endsection