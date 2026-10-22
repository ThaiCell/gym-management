
@extends('layouts.admin')

@section('content')
<style>
    .class-page { color: #f5f5f5; padding: 24px; }
    .class-header {
        display: flex; justify-content: space-between; align-items: center;
        gap: 16px; flex-wrap: wrap; margin-bottom: 22px;
    }
    .class-header h2 { font-size: 26px; font-weight: 700; margin: 0 0 6px; }
    .class-header p { color: #aaa; margin: 0; }
    .class-btn {
        display: inline-block; border: 0; border-radius: 7px; padding: 10px 15px;
        text-decoration: none; cursor: pointer; font-weight: 600; font-size: 14px;
    }
    .class-btn-primary { background: #ed0715; color: white; }
    .class-btn-primary:hover { background: #c9000c; color: white; }
    .class-btn-edit { color: #8bbcff; border: 1px solid #3869a8; background: transparent; }
    .class-btn-delete { color: #ff8585; border: 1px solid #a83d3d; background: transparent; }
    .class-card {
        background: #171717; border: 1px solid #303030; border-radius: 12px;
        overflow: hidden;
    }
    .class-table { width: 100%; border-collapse: collapse; min-width: 850px; }
    .class-table th {
        padding: 15px 14px; text-align: left; background: #222;
        color: #eee; font-size: 13px; white-space: nowrap;
    }
    .class-table td {
        padding: 14px; border-top: 1px solid #303030;
        vertical-align: middle; font-size: 14px;
    }
    .class-table tbody tr:hover { background: #202020; }
    .class-name { font-weight: 700; color: #fff; margin-bottom: 5px; }
    .class-desc { color: #aaa; max-width: 300px; line-height: 1.5; }
    .class-actions { display: flex; gap: 8px; align-items: center; }
    .class-actions form { margin: 0; }
    .class-status {
        display: inline-block; padding: 5px 9px; border-radius: 20px;
        background: #163d28; color: #83e6a8; white-space: nowrap; font-size: 12px;
    }
    .class-status-off { background: #3b2929; color: #ffaaaa; }
    .class-alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 16px; }
    .class-alert-success { background: #163d28; color: #a7efbd; }
    .class-alert-error { background: #492020; color: #ffc0c0; }
    .class-empty { padding: 35px; text-align: center; color: #aaa; }
    @media(max-width: 700px) {
        .class-page { padding: 14px; }
        .class-header h2 { font-size: 22px; }
    }
</style>

<div class="class-page">
    <div class="class-header">
        <div>
            <h2>Quản lý lớp tập</h2>
            <p>Quản lý danh sách, lịch tập và số lượng hội viên.</p>
        </div>
        <a href="{{ url('/admin/classes/create') }}" class="class-btn class-btn-primary">
            + Thêm lớp tập
        </a>
    </div>

    @if(session('success'))
        <div class="class-alert class-alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="class-alert class-alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="class-alert class-alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="class-card" style="overflow-x:auto">
        <table class="class-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên lớp tập</th>
                    <th>Lịch tập</th>
                    <th>Sức chứa</th>
                    <th>Đăng ký</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse($lopTap as $lop)
                @php
                    $active = in_array(mb_strtolower($lop->trang_thai), [
                        'hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'
                    ]);
                @endphp
                <tr>
                    <td>{{ $lop->lop_tap_id }}</td>
                    <td>
                        @if($lop->anh && file_exists(public_path($lop->anh)))
                            <img
                                src="{{ asset($lop->anh) }}"
                                alt="{{ $lop->ten_lop }}"
                                style="width:80px;height:60px;object-fit:cover;border-radius:8px;"
                            >
                        @else
                            <span style="color:#999;">Chưa có ảnh</span>
                        @endif
                    </td>
                    <td>
                        <div class="class-name">{{ $lop->ten_lop }}</div>
                        @if($lop->mo_ta)
                            <div class="class-desc">
                                {{ \Illuminate\Support\Str::limit($lop->mo_ta, 90) }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $lop->lich_tap ?: 'Chưa cập nhật' }}</td>
                    <td>{{ $lop->suc_chua ?? 'Không giới hạn' }}</td>
                    <td>{{ $lop->so_dang_ky }}</td>
                    <td>
                        <span class="class-status {{ $active ? '' : 'class-status-off' }}">
                            {{ $active ? 'Hoạt động' : $lop->trang_thai }}
                        </span>
                    </td>
                    <td>
                        <div class="class-actions">
                            <a href="{{ url('/admin/classes/'.$lop->lop_tap_id.'/edit') }}"
                               class="class-btn class-btn-edit">Sửa</a>
                            <form action="{{ url('/admin/classes/'.$lop->lop_tap_id.'/delete') }}"
                                  method="POST"
                                  data-confirm-submit="Xóa lớp tập này? Chỉ lớp chưa có đăng ký mới được xóa.">
                                @csrf
                                <button type="submit" class="class-btn class-btn-delete">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="class-empty">Chưa có lớp tập nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
