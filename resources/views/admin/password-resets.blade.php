@extends('layouts.admin')

@section('content')
    <style>
        .pr-wrap {
            color: #f4f4f5;
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0 16px;
            box-sizing: border-box;
        }

        .pr-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 26px 28px;
            margin-bottom: 22px;
            border: 1px solid #3a2023;
            border-radius: 18px;
            background: radial-gradient(circle at 90% 15%, rgba(229, 9, 20, .20), transparent 32%), linear-gradient(120deg, #1c1c1f, #120d0e)
        }

        .pr-heading {
            display: flex;
            align-items: center;
            gap: 15px
        }

        .pr-icon {
            display: grid;
            place-items: center;
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            border-radius: 15px;
            background: linear-gradient(145deg, #ff2633, #b9000b);
            font-size: 25px;
            box-shadow: 0 8px 24px rgba(229, 9, 20, .2)
        }

        .pr-title {
            font-size: clamp(23px, 2.3vw, 30px);
            font-weight: 800;
            margin: 0 0 6px;
            letter-spacing: -.5px
        }

        .pr-subtitle {
            color: #a1a1aa;
            margin: 0;
            font-size: 14px;
            line-height: 1.6
        }

        .pr-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid #3a3031;
            border-radius: 999px;
            background: #171315;
            color: #e4e4e7;
            font-size: 12px;
            white-space: nowrap
        }

        .pr-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444
        }

        .pr-alert {
            padding: 15px 17px;
            border-radius: 12px;
            margin-bottom: 18px;
            line-height: 1.65;
            font-size: 14px
        }

        .pr-success {
            background: linear-gradient(120deg, #10251a, #142019);
            border: 1px solid #286342;
            color: #c4f5d4
        }

        .pr-error {
            background: #2b1115;
            border: 1px solid #7f2932;
            color: #fecdd3
        }

        .pr-temp {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px
        }

        .pr-temp code {
            display: inline-block;
            background: #080808;
            color: #fff;
            padding: 11px 15px;
            border: 1px dashed #4b6854;
            border-radius: 8px;
            font-size: 17px;
            letter-spacing: 1.5px
        }

        .pr-copy {
            background: #27272a;
            color: #fff;
            border: 1px solid #52525b;
            border-radius: 8px;
            padding: 10px 13px;
            cursor: pointer;
            font-weight: 700
        }

        .pr-copy:hover {
            background: #3f3f46
        }

        .pr-panel {
            width: 100%;
            max-width: none;
            box-sizing: border-box;
            background: linear-gradient(145deg, #19191b, #151516);
            border: 1px solid #2d2d30;
            border-radius: 12px;
            padding: 22px;
        }

        .pr-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px
        }

        .pr-panel-title {
            font-size: 17px;
            font-weight: 750;
            margin: 0
        }

        .pr-panel-note {
            font-size: 12px;
            color: #8e8e98;
            margin-top: 5px
        }

        .pr-search {
            display: flex;
            gap: 10px;
            margin-bottom: 18px
        }

        .pr-search input {
            flex: 1;
            min-width: 0;
            background: #101012;
            border: 1px solid #36363b;
            color: #f4f4f5;
            border-radius: 10px;
            padding: 12px 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s
        }

        .pr-search input:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, .12)
        }

        .pr-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            border: 1px solid transparent;
            border-radius: 9px;
            padding: 11px 15px;
            background: linear-gradient(135deg, #f31320, #c9000b);
            color: #fff;
            font-weight: 750;
            cursor: pointer;
            transition: transform .15s, filter .15s;
            text-decoration: none;
            white-space: nowrap
        }

        .pr-btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px)
        }

        .pr-btn:active {
            transform: translateY(0)
        }

        .pr-btn-reset {
            padding: 9px 12px;
            font-size: 12px
        }

        .pr-table-wrap {
            overflow-x: auto;
            border: 1px solid #2c2c30;
            border-radius: 12px
        }

        .pr-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 780px
        }

        .pr-table th {
            padding: 14px 15px;
            text-align: left;
            background: #202023;
            color: #a1a1aa;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            white-space: nowrap
        }

        .pr-table td {
            padding: 15px;
            text-align: left;
            border-top: 1px solid #29292d;
            color: #e4e4e7;
            font-size: 13px;
            vertical-align: middle
        }

        .pr-table tbody tr {
            background: #18181a;
            transition: background .15s
        }

        .pr-table tbody tr:hover {
            background: #202023
        }

        .pr-table th:first-child {
            border-radius: 11px 0 0 0
        }

        .pr-table th:last-child {
            border-radius: 0 11px 0 0
        }

        .pr-id {
            color: #a1a1aa;
            font-variant-numeric: tabular-nums
        }

        .pr-name {
            font-weight: 700;
            color: #fafafa
        }

        .pr-email {
            color: #b5b5bd
        }

        .pr-role {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 5px 9px;
            background: #292023;
            border: 1px solid #4b252b;
            border-radius: 7px;
            color: #ffb6bc;
            font-weight: 750;
            font-size: 12px
        }

        .pr-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 10px;
            border: 1px solid #343439;
            border-radius: 999px;
            background: #222225;
            color: #d4d4d8;
            font-size: 12px;
            white-space: nowrap
        }

        .pr-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #71717a
        }

        .pr-status.is-active {
            background: #10251a;
            border-color: #24583a;
            color: #bbf7d0
        }

        .pr-status.is-active .pr-status-dot {
            background: #4ade80
        }

        .pr-status.is-inactive {
            background: #2a1a12;
            border-color: #684025;
            color: #fed7aa
        }

        .pr-status.is-inactive .pr-status-dot {
            background: #fb923c
        }

        .pr-empty {
            padding: 34px !important;
            text-align: center !important;
            color: #a1a1aa !important
        }

        .pr-empty-icon {
            display: block;
            font-size: 28px;
            margin-bottom: 8px
        }

        .pr-pagination {
            margin-top: 20px;
            overflow-x: auto
        }

        .pr-pagination nav {
            display: flex;
            justify-content: flex-end
        }

        .pr-pagination svg {
            width: 18px;
            height: 18px
        }

        .pr-pagination span,
        .pr-pagination a {
            color: #d4d4d8 !important
        }

        @media(max-width:700px) {
            .pr-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 21px
            }

            .pr-chip {
                white-space: normal
            }

            .pr-panel {
                padding: 14px
            }

            .pr-search {
                flex-direction: column
            }

            .pr-search .pr-btn {
                width: 100%
            }

            .pr-panel-head {
                align-items: flex-start;
                flex-direction: column
            }
        }
    </style>

    <div class="pr-wrap">
        <section class="pr-hero">
            <div class="pr-heading">
                <div class="pr-icon">🔐</div>
                <div>
                    <h1 class="pr-title">Cấp lại mật khẩu</h1>
                    <p class="pr-subtitle">Quản lý tài khoản và cấp mật khẩu tạm thời cho người dùng sau khi xác minh danh
                        tính.</p>
                </div>
            </div>
            <div class="pr-chip"><span class="pr-dot"></span> Khu vực quản trị viên</div>
        </section>

        @if (session('success'))
            <div class="pr-alert pr-success">✓ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="pr-alert pr-error">⚠ {{ session('error') }}</div>
        @endif
        @if (session('temporary_password'))
            <div class="pr-alert pr-success">
                <strong>🔑 Mật khẩu tạm thời — chỉ hiển thị lần này</strong>
                <div style="margin-top:5px">Tài khoản: <strong>{{ session('temporary_password_user') }}</strong></div>
                <div class="pr-temp">
                    <code id="temporaryPassword">{{ session('temporary_password') }}</code>
                    <button type="button" class="pr-copy"
                        onclick="navigator.clipboard.writeText(document.getElementById('temporaryPassword').innerText).then(()=>this.innerText='✓ Đã sao chép').catch(()=>alert('Không sao chép được, hãy chọn và sao chép thủ công.'))">Sao
                        chép mật khẩu</button>
                </div>
                <p style="margin:10px 0 0">Gửi mật khẩu riêng cho chủ tài khoản sau khi xác minh. Khuyến nghị người dùng đổi
                    mật khẩu sau khi đăng nhập.</p>
            </div>
        @endif

        <section class="pr-panel">
            <div class="pr-panel-head">
                <div>
                    <h2 class="pr-panel-title">Danh sách tài khoản</h2>
                    <div class="pr-panel-note">Tìm theo họ tên hoặc email để thao tác nhanh chóng.</div>
                </div>
            </div>

            <form method="GET" action="{{ url('/admin/password-resets') }}" class="pr-search">
                <input type="text" name="q" value="{{ $keyword }}"
                    placeholder="⌕  Nhập họ tên hoặc email cần tìm..." aria-label="Tìm theo họ tên hoặc email">
                <button class="pr-btn" type="submit">⌕ Tìm kiếm</button>
                @if (!empty($keyword))
                    <a class="pr-btn" href="{{ url('/admin/password-resets') }}"
                        style="background:#303036;border-color:#45454c">Xóa lọc</a>
                @endif
            </form>

            <div class="pr-table-wrap">
                <table class="pr-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thông tin người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $statusRaw = mb_strtolower(trim((string) $user->trang_thai));
                                $isActive = in_array(
                                    $statusRaw,
                                    [
                                        'hoat_dong',
                                        'hoạt_động',
                                        'hoạt động',
                                        'dang_hoat_dong',
                                        'đang hoạt động',
                                        'active',
                                        '1',
                                    ],
                                    true,
                                );
                                $statusLabel = $isActive
                                    ? 'Hoạt động'
                                    : (in_array(
                                        $statusRaw,
                                        ['ngung_hoat_dong', 'ngừng hoạt động', 'khóa', 'khoa', 'inactive', '0'],
                                        true,
                                    )
                                        ? 'Ngừng hoạt động'
                                        : str_replace('_', ' ', (string) $user->trang_thai));
                            @endphp
                            <tr>
                                <td class="pr-id">#{{ $user->nguoi_dung_id }}</td>
                                <td>
                                    <div class="pr-name">{{ $user->ho_ten }}</div>
                                </td>
                                <td class="pr-email">{{ $user->email }}</td>
                                <td><span class="pr-role">{{ $user->vai_tro_id }}</span></td>
                                <td>
                                    <span
                                        class="pr-status {{ $isActive ? 'is-active' : (in_array($statusRaw, ['ngung_hoat_dong', 'ngừng hoạt động', 'khóa', 'khoa', 'inactive', '0'], true) ? 'is-inactive' : '') }}">
                                        <span class="pr-status-dot"></span>{{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('admin.password-resets.reset', $user->nguoi_dung_id) }}"
                                        data-confirm-submit="Chỉ tiếp tục nếu đã xác minh danh tính chủ tài khoản. Cấp mật khẩu tạm thời cho tài khoản này?"
                                        data-confirm-title="Xác nhận cấp lại mật khẩu" data-confirm-text="Cấp lại mật khẩu">
                                        @csrf

                                        <button type="submit" class="pr-btn pr-btn-reset">
                                            🔑 Cấp lại
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="pr-empty"><span class="pr-empty-icon">⌕</span>Không tìm thấy tài
                                    khoản phù hợp.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pr-pagination">{{ $users->appends(request()->query())->links() }}</div>
        </section>
    </div>
@endsection
