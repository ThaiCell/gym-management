@extends('layouts.dashboard')
@section('title', 'Lịch huấn luyện - GYMFIT')
@section('content')

<style>
    .tw-page{color:#eee}
    .tw-head{margin:12px 0 24px}
    .tw-head small{color:#ef233c;font-weight:800;letter-spacing:2px}
    .tw-head h1{font-size:28px;margin:7px 0;color:#fff}
    .tw-panel{background:linear-gradient(145deg,#151515,#101010);border:1px solid #292929;border-radius:12px;padding:20px;margin-bottom:20px;box-shadow:0 8px 24px #0003}
    .tw-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
    .tw-field label{display:block;color:#aaa;font-size:12px;margin-bottom:6px}
    .tw-field input,.tw-field select,.tw-field textarea{width:100%;box-sizing:border-box;background:#090909;border:1px solid #353535;color:#fff;border-radius:8px;padding:11px 12px;outline:none;transition:border-color .2s,box-shadow .2s}
    .tw-field input:focus,.tw-field select:focus,.tw-field textarea:focus{border-color:#e50914;box-shadow:0 0 0 3px #e5091420}
    .tw-field textarea{min-height:85px}
    .tw-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;background:#e50914;color:#fff;border:0;border-radius:8px;padding:11px 17px;font-weight:800;cursor:pointer;text-decoration:none;transition:.2s}
    .tw-btn:hover{background:#c9000b;color:#fff;transform:translateY(-1px)}
    .tw-table{width:100%;border-collapse:collapse}
    .tw-table th,.tw-table td{text-align:left;padding:12px;border-bottom:1px solid #292929;font-size:13px}
    .tw-table th{color:#ff3548;font-weight:800}
    .tw-table tr:hover td{background:#ffffff05}
    .tw-muted{color:#888}
    .tw-alert{padding:12px 15px;border:1px solid #333;border-left:4px solid #e50914;border-radius:8px;margin:0 0 16px;background:#171717;color:#eee}
    .tw-nav-wrap{margin:4px 0 22px;padding:8px;background:linear-gradient(135deg,#141414,#0e0e0e);border:1px solid #292929;border-radius:13px;box-shadow:0 8px 24px #0003}
    .tw-nav{display:flex;flex-wrap:wrap;gap:8px;align-items:center}
    .tw-nav a{position:relative;display:inline-flex;align-items:center;gap:8px;padding:10px 15px;border:1px solid #333;border-radius:9px;background:#191919;color:#cfcfcf;text-decoration:none;font-size:12px;font-weight:700;letter-spacing:.15px;transition:all .2s ease}
    .tw-nav a .tw-nav-icon{font-size:14px;line-height:1}
    .tw-nav a:hover{background:#241315;border-color:#a91b28;color:#fff;transform:translateY(-1px)}
    .tw-nav a.active{background:linear-gradient(135deg,#e50914,#a90812);border-color:#f02b3a;color:#fff;box-shadow:0 4px 14px #e5091433}
    .tw-nav a.active:after{content:"";position:absolute;left:14px;right:14px;bottom:4px;height:2px;border-radius:3px;background:#ffffff80}
    .tw-status{display:inline-flex;align-items:center;gap:7px;padding:5px 10px;border-radius:20px;border:1px solid #454545;background:#1c1c1c;color:#eee;font-size:12px;font-weight:700;white-space:nowrap}
    .tw-status:before{content:"";width:6px;height:6px;border-radius:50%;background:#aaa}
    .tw-status.is-active{border-color:#236b42;background:#10251a;color:#8ce0a9}
    .tw-status.is-active:before{background:#35c46a}
    .tw-status.is-cancelled{border-color:#79303a;background:#2a1115;color:#ff9aa3}
    .tw-status.is-cancelled:before{background:#f05260}
    @media(max-width:700px){.tw-grid{grid-template-columns:1fr}.tw-panel{overflow-x:auto}.tw-nav{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}.tw-nav a{justify-content:center;padding:11px 8px}}
</style>
<div class="tw-page">
    <nav class="tw-nav-wrap" aria-label="Điều hướng huấn luyện viên">
        <div class="tw-nav">
            <a href="/trainer/dashboard" class="{{ request()->is('trainer/dashboard') ? 'active' : '' }}"><span class="tw-nav-icon">⌂</span> Dashboard</a>
            <a href="/trainer/students" class="{{ request()->is('trainer/students') ? 'active' : '' }}"><span class="tw-nav-icon">♟</span> Học viên</a>
            <a href="/trainer/schedules" class="{{ request()->is('trainer/schedules') ? 'active' : '' }}"><span class="tw-nav-icon">▦</span> Lịch PT</a>
            <a href="/trainer/plans" class="{{ request()->is('trainer/plans') ? 'active' : '' }}"><span class="tw-nav-icon">▤</span> Giáo án</a>
            <a href="/trainer/logs" class="{{ request()->is('trainer/logs') ? 'active' : '' }}"><span class="tw-nav-icon">☷</span> Nhật ký</a>
            <a href="/trainer/progress" class="{{ request()->is('trainer/progress') ? 'active' : '' }}"><span class="tw-nav-icon">↗</span> Tiến độ</a>
        </div>
    </nav>
    @if (session('success'))<div class="tw-alert">✓ {{ session('success') }}</div>@endif
    @if (session('error'))<div class="tw-alert">! {{ session('error') }}</div>@endif
    @if ($errors->any())<div class="tw-alert">{{ $errors->first() }}</div>@endif
</div>

    <div class="tw-head">
        <small>TRAINING CALENDAR</small>
        <h1>Quản lý lịch PT</h1>
        <p class="tw-muted">Theo dõi lịch của học viên, trạng thái và số buổi còn lại. Việc xác nhận hoàn thành vẫn dùng chức
            năng hiện tại ở Dashboard.</p>
    </div>
    <div class="tw-panel">
        <form method="GET" class="tw-grid">
            <div class="tw-field"><label>Lọc trạng thái</label><select name="status">
                    <option value="">Tất cả trạng thái</option>
                    @foreach (['đã đặt', 'đã hoàn thành', 'đã hủy'] as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ mb_strtoupper(mb_substr($st, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($st, 1, null, 'UTF-8') }}</option>
                    @endforeach
                </select>
            </div>
            <div style="align-self:end"><button class="tw-btn">Lọc lịch</button></div>
        </form>
    </div>
    <div class="tw-panel">
        <table class="tw-table">
            <thead>
                <tr>
                    <th>Học viên</th>
                    <th>Gói</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Buổi còn</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                    <tr>
                        <td>{{ $s->ho_ten }}</td>
                        <td>{{ $s->ten_goi_pt }}</td>
                        <td>{{ $s->thoi_gian_bat_dau }}</td>
                        <td>{{ $s->thoi_gian_ket_thuc }}</td>
                        <td>{{ $s->so_buoi_con_lai }}</td>
                        <td>
                            @php
                                $statusText = $s->trang_thai ?? '';
                                $statusClass = str_contains(mb_strtolower($statusText), 'hủy') || str_contains(mb_strtolower($statusText), 'huy')
                                    ? 'is-cancelled'
                                    : (str_contains(mb_strtolower($statusText), 'hoàn thành') || str_contains(mb_strtolower($statusText), 'hoan thanh') || str_contains(mb_strtolower($statusText), 'hoạt động') || str_contains(mb_strtolower($statusText), 'hoat dong')
                                        ? 'is-active' : '');
                            @endphp
                            <span class="tw-status {{ $statusClass }}">{{ mb_strtoupper(mb_substr($statusText, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($statusText, 1, null, 'UTF-8') }}</span>
                        </td>
                </tr>@empty<tr>
                        <td colspan="6" class="tw-muted">Chưa có lịch PT.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:15px">{{ $schedules->links() }}</div>
    </div>
@endsection
