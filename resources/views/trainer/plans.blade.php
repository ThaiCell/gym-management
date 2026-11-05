@extends('layouts.dashboard')
@section('title', 'Giáo án tập luyện - GYMFIT')
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
        <small>WORKOUT PROGRAMS</small>
        <h1>Giáo án tập luyện</h1>
        <p class="tw-muted">Tạo giáo án riêng cho từng học viên do bạn phụ trách.</p>
    </div>
    <div class="tw-panel">
        <h3>Tạo giáo án mới</h3>
        <form method="POST" action="/trainer/plans">@csrf<div class="tw-grid">
                <div class="tw-field"><label>Học viên</label><select name="hoi_vien_id" required>
                        <option value="">-- Chọn học viên --</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->hoi_vien_id }}">{{ $s->ho_ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="tw-field"><label>Tên giáo án</label><input name="ten_giao_an" required
                        placeholder="Ví dụ: Tăng cơ tuần 1"></div>
                <div class="tw-field"><label>Mục tiêu</label><input name="muc_tieu" placeholder="Tăng sức mạnh, giảm mỡ...">
                </div>
                <div class="tw-field"><label>Ghi chú</label><input name="ghi_chu"></div>
            </div>
            <h4>Bài tập</h4>
            <div id="exerciseRows">
                <div class="tw-grid exercise-row">
                    <div class="tw-field"><label>Tên bài tập</label><input name="bai_tap[0][ten]" required
                            placeholder="Squat"></div>
                    <div class="tw-field"><label>Số hiệp</label><input type="number" name="bai_tap[0][so_hiep]"
                            min="1" value="3"></div>
                    <div class="tw-field"><label>Số lần / thời lượng</label><input name="bai_tap[0][so_lan]"
                            placeholder="12 lần"></div>
                    <div class="tw-field"><label>Ghi chú bài tập</label><input name="bai_tap[0][ghi_chu]"></div>
                </div>
            </div><button type="button" class="tw-btn" style="background:#333;margin-top:12px" onclick="addExerciseRow()">+
                Thêm bài tập</button><button class="tw-btn" style="margin-top:15px">Lưu giáo án</button>
        </form>
        <script>
            let exerciseIndex = 1;

            function addExerciseRow() {
                const row = document.createElement('div');
                row.className = 'tw-grid exercise-row';
                row.style.marginTop = '12px';
                row.innerHTML =
                    `<div class="tw-field"><label>Tên bài tập</label><input name="bai_tap[${exerciseIndex}][ten]" required placeholder="Tên bài tập"></div><div class="tw-field"><label>Số hiệp</label><input type="number" name="bai_tap[${exerciseIndex}][so_hiep]" min="1" value="3"></div><div class="tw-field"><label>Số lần / thời lượng</label><input name="bai_tap[${exerciseIndex}][so_lan]" placeholder="12 lần"></div><div class="tw-field"><label>Ghi chú bài tập</label><input name="bai_tap[${exerciseIndex}][ghi_chu]"></div>`;
                document.getElementById('exerciseRows').appendChild(row);
                exerciseIndex++;
            }
        </script>
    </div>
    <div class="tw-panel">
        <h3>Giáo án đã tạo</h3>
        <table class="tw-table">
            <thead>
                <tr>
                    <th>Học viên</th>
                    <th>Giáo án</th>
                    <th>Mục tiêu</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $p)
                    <tr>
                        <td>{{ $p->ho_ten }}</td>
                        <td>{{ $p->ten_giao_an }}</td>
                        <td>{{ $p->muc_tieu }}</td>
                        <td>{{ $p->created_at }}</td>
                </tr>@empty<tr>
                        <td colspan="4" class="tw-muted">Chưa có giáo án.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
