@extends('layouts.admin')

@section('content')
    <style>
        .class-members-page {
            color: #f5f5f5;
            padding: 24px;
        }

        .class-members-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .class-members-header h2 {
            margin: 0 0 6px;
            font-size: 26px;
        }

        .class-members-header p {
            margin: 0;
            color: #aaa;
        }

        .class-members-card {
            overflow-x: auto;
            background: #171717;
            border: 1px solid #303030;
            border-radius: 12px;
        }

        .class-members-table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        .class-members-table th {
            padding: 15px 14px;
            text-align: left;
            background: #222;
            color: #eee;
            font-size: 13px;
            white-space: nowrap;
        }

        .class-members-table td {
            padding: 14px;
            border-top: 1px solid #303030;
            color: #ddd;
            font-size: 14px;
        }

        .class-members-table tbody tr:hover {
            background: #202020;
        }

        .class-members-empty {
            padding: 35px !important;
            text-align: center;
            color: #aaa !important;
        }

        .class-members-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 15px;
            border: 1px solid #414149;
            border-radius: 7px;
            background: #242428;
            color: #f2f2f2;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        @media(max-width: 700px) {
            .class-members-page {
                padding: 14px;
            }

            .class-members-header h2 {
                font-size: 22px;
            }
        }
    </style>

    <div class="class-members-page">
        <div class="class-members-header">
            <div>
                <h2>Hội viên lớp {{ $lop->ten_lop }}</h2>
                <p>{{ $hoiVien->count() }} hội viên đang tham gia</p>
            </div>
            <a href="{{ url('/admin/classes') }}" class="class-members-back">Quay lại danh sách lớp</a>
        </div>

        <div class="class-members-card">
            <table class="class-members-table">
                <thead>
                    <tr>
                        <th>ID hội viên</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hoiVien as $member)
                        <tr>
                            <td>#{{ $member->hoi_vien_id }}</td>
                            <td>{{ $member->ho_ten }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->so_dien_thoai ?: 'Chưa cập nhật' }}</td>
                            <td>{{ $member->ngay_dang_ky ?: 'Chưa cập nhật' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="class-members-empty">Lớp chưa có hội viên đang tham gia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
