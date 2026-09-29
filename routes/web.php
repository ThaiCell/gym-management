<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\NoCache;


/*
|--------------------------------------------------------------------------
| TRANG CHỦ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('user.home');
});


/*
|--------------------------------------------------------------------------
| GIỚI THIỆU
|--------------------------------------------------------------------------
*/

Route::get('/about', function () {
    return view('user.about');
});


/*
|--------------------------------------------------------------------------
| GÓI TẬP
|--------------------------------------------------------------------------
*/

Route::get('/packages', function () {
    return view('user.packages');
});


/*
|--------------------------------------------------------------------------
| LỚP TẬP
|--------------------------------------------------------------------------
*/

Route::get('/classes', function () {
    return view('user.classes');
});


/*
|--------------------------------------------------------------------------
| HUẤN LUYỆN VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/trainers', function () {
    return view('user.trainers');
});


/*
|--------------------------------------------------------------------------
| LIÊN HỆ
|--------------------------------------------------------------------------
*/

Route::get('/contact', function () {
    return view('user.contact');
});


/*
|--------------------------------------------------------------------------
| ĐĂNG NHẬP - HIỂN THỊ FORM
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('user.login');
});


/*
|--------------------------------------------------------------------------
| ĐĂNG NHẬP - XỬ LÝ
|--------------------------------------------------------------------------
*/

Route::post('/login', function () {

    $email = request('email');
    $matKhau = request('mat_khau');


    /*
    |--------------------------------------------------------------------------
    | Tìm tài khoản
    |--------------------------------------------------------------------------
    */

    $user = DB::table('nguoi_dung')
        ->where('email', $email)
        ->first();


    if (!$user) {

        return back()
            ->withInput()
            ->with('error', 'Email hoặc mật khẩu không đúng.');

    }


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra trạng thái tài khoản
    |--------------------------------------------------------------------------
    */

    if (
        $user->trang_thai !== 'hoạt_động' &&
        $user->trang_thai !== 'hoat_dong'
    ) {

        return back()
            ->withInput()
            ->with('error', 'Tài khoản đang bị khóa.');

    }


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra mật khẩu
    |--------------------------------------------------------------------------
    |
    | Hỗ trợ:
    | 1. Mật khẩu thường trong database
    | 2. Mật khẩu Bcrypt
    |
    */

    $matKhauDung = false;


    // Mật khẩu thường
    if ($matKhau === $user->mat_khau) {

        $matKhauDung = true;

    }


    // Mật khẩu Bcrypt
    else if (
        str_starts_with($user->mat_khau, '$2y$') ||
        str_starts_with($user->mat_khau, '$2a$') ||
        str_starts_with($user->mat_khau, '$2b$')
    ) {

        if (Hash::check($matKhau, $user->mat_khau)) {

            $matKhauDung = true;

        }

    }


    if (!$matKhauDung) {

        return back()
            ->withInput()
            ->with('error', 'Email hoặc mật khẩu không đúng.');

    }


    /*
    |--------------------------------------------------------------------------
    | Lưu session
    |--------------------------------------------------------------------------
    */

    session([
        'user' => $user,
        'role_id' => $user->vai_tro_id,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Chuyển dashboard theo vai trò
    |--------------------------------------------------------------------------
    */

    switch ($user->vai_tro_id) {

        // Admin
        case 1:

            session([
                'dashboard_path' => 'dashboard'
            ]);

            return redirect('/dashboard');


        // Nhân viên
        case 2:

            session([
                'dashboard_path' => 'staff/dashboard'
            ]);

            return redirect('/staff/dashboard');


        // Hội viên
        case 3:

            session([
                'dashboard_path' => 'user/dashboard'
            ]);

            return redirect('/user/dashboard');


        // Huấn luyện viên
        case 4:

            session([
                'dashboard_path' => 'trainer/dashboard'
            ]);

            return redirect('/trainer/dashboard');


        default:

            session()->flush();

            return redirect('/login')
                ->with(
                    'error',
                    'Tài khoản chưa được phân quyền.'
                );
    }

});


/*
|--------------------------------------------------------------------------
| ĐĂNG KÝ - HIỂN THỊ FORM
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {

    return view('user.register');

});


/*
|--------------------------------------------------------------------------
| ĐĂNG KÝ - XỬ LÝ
|--------------------------------------------------------------------------
*/

Route::post('/register', function () {

    $hoTen = request('ho_ten');
    $email = request('email');
    $matKhau = request('mat_khau');
    $xacNhanMatKhau = request('mat_khau_confirmation');


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra dữ liệu
    |--------------------------------------------------------------------------
    */

    if (!$hoTen || !$email || !$matKhau) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Vui lòng nhập đầy đủ thông tin.'
            );

    }


    if ($matKhau !== $xacNhanMatKhau) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Mật khẩu xác nhận không giống nhau.'
            );

    }


    if (strlen($matKhau) < 6) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Mật khẩu phải có ít nhất 6 ký tự.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra email đã tồn tại
    |--------------------------------------------------------------------------
    */

    $emailTonTai = DB::table('nguoi_dung')
        ->where('email', $email)
        ->exists();


    if ($emailTonTai) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Email này đã được sử dụng.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Tạo tài khoản
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();


    try {

        /*
        |--------------------------------------------------------------------------
        | Tạo người dùng
        |--------------------------------------------------------------------------
        */

        $nguoiDungId = DB::table('nguoi_dung')->insertGetId([

            // Đăng ký mới mặc định là Hội viên
            'vai_tro_id' => 3,

            'ho_ten' => $hoTen,

            'email' => $email,

            'mat_khau' => Hash::make($matKhau),

            'trang_thai' => 'hoạt_động',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Tạo hồ sơ hội viên
        |--------------------------------------------------------------------------
        */

        DB::table('hoi_vien')->insert([

            'nguoi_dung_id' => $nguoiDungId,

            'ngay_tham_gia' => now()->toDateString(),

        ]);


        DB::commit();


        return redirect('/login')
            ->with(
                'success',
                'Đăng ký thành công. Vui lòng đăng nhập.'
            );


    } catch (\Exception $e) {

        DB::rollBack();


        return back()
            ->withInput()
            ->with(
                'error',
                'Không thể tạo tài khoản: ' . $e->getMessage()
            );

    }

});


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra đăng nhập
    |--------------------------------------------------------------------------
    */

    if (!session()->has('user')) {

        return redirect('/login');

    }


    /*
    |--------------------------------------------------------------------------
    | Chỉ Admin được vào
    |--------------------------------------------------------------------------
    */

    if (session('role_id') != 1) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Tổng hội viên
    |--------------------------------------------------------------------------
    */

    $tongHoiVien = DB::table('hoi_vien')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Hội viên đang hoạt động
    |--------------------------------------------------------------------------
    */

    $hoiVienDangHoatDong = DB::table('dang_ky_goi_tap')
        ->whereIn(
            'trang_thai',
            [
                'đang hoạt động',
                'hoạt_động',
                'hoat_dong',
                'dang_hoat_dong'
            ]
        )
        ->distinct()
        ->count('hoi_vien_id');


    /*
    |--------------------------------------------------------------------------
    | Doanh thu đã thanh toán
    |--------------------------------------------------------------------------
    */

    $doanhThu = DB::table('hoa_don')
        ->whereIn(
            'trang_thai',
            [
                'đã thanh toán',
                'da thanh toan'
            ]
        )
        ->sum('tong_tien');


    /*
    |--------------------------------------------------------------------------
    | Tổng gói tập đang hoạt động
    |--------------------------------------------------------------------------
    */

    $tongGoiTap = DB::table('goi_tap')
        ->whereIn(
            'trang_thai',
            [
                'hoạt_động',
                'hoat_dong'
            ]
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Hoạt động gần đây
    |--------------------------------------------------------------------------
    */

    $hoatDongGanDay = DB::table('hoa_don')
        ->join(
            'hoi_vien',
            'hoa_don.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->select(
            'hoa_don.hoa_don_id',
            'nguoi_dung.ho_ten',
            'hoa_don.tong_tien',
            'hoa_don.ngay_lap',
            'hoa_don.trang_thai'
        )
        ->orderByDesc('hoa_don.ngay_lap')
        ->limit(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Trả dữ liệu cho Admin
    |--------------------------------------------------------------------------
    */

    return view(
        'dashboard',
        compact(
            'tongHoiVien',
            'hoiVienDangHoatDong',
            'doanhThu',
            'tongGoiTap',
            'hoatDongGanDay'
        )
    );

});


/*
|--------------------------------------------------------------------------
| DASHBOARD NHÂN VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/staff/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra đăng nhập
    |--------------------------------------------------------------------------
    */

    if (!session()->has('user')) {

        return redirect('/login');

    }


    /*
    |--------------------------------------------------------------------------
    | Chỉ Nhân viên
    |--------------------------------------------------------------------------
    */

    if (session('role_id') != 2) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Tổng hội viên
    |--------------------------------------------------------------------------
    */

    $tongHoiVien = DB::table('hoi_vien')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Tổng check-in
    |--------------------------------------------------------------------------
    */

    $tongCheckIn = DB::table('check_in')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Tổng hóa đơn
    |--------------------------------------------------------------------------
    */

    $tongHoaDon = DB::table('hoa_don')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Tổng đăng ký gói
    |--------------------------------------------------------------------------
    */

    $tongDangKyGoi = DB::table('dang_ky_goi_tap')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Hóa đơn gần đây
    |--------------------------------------------------------------------------
    */

    $hoaDonGanDay = DB::table('hoa_don')
        ->join(
            'hoi_vien',
            'hoa_don.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->select(
            'nguoi_dung.ho_ten',
            'hoa_don.ngay_lap',
            'hoa_don.tong_tien',
            'hoa_don.trang_thai'
        )
        ->orderByDesc('hoa_don.ngay_lap')
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Hiển thị
    |--------------------------------------------------------------------------
    */

    return view(
        'staff.dashboard',
        compact(
            'tongHoiVien',
            'tongCheckIn',
            'tongHoaDon',
            'tongDangKyGoi',
            'hoaDonGanDay'
        )
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| DASHBOARD HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/user/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra đăng nhập
    |--------------------------------------------------------------------------
    */

    if (!session()->has('user')) {

        return redirect('/login');

    }


    /*
    |--------------------------------------------------------------------------
    | Chỉ Hội viên
    |--------------------------------------------------------------------------
    */

    if (session('role_id') != 3) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ID người dùng hiện tại
    |--------------------------------------------------------------------------
    */

    $nguoiDungId = session('user')->nguoi_dung_id;


    /*
    |--------------------------------------------------------------------------
    | Tìm hồ sơ hội viên
    |--------------------------------------------------------------------------
    */

    $hoiVien = DB::table('hoi_vien')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->first();


    /*
    |--------------------------------------------------------------------------
    | Nếu chưa có hồ sơ hội viên
    |--------------------------------------------------------------------------
    */

    if (!$hoiVien) {

        return view(
            'user.dashboard',
            [

                'tongGoiTap' => 0,

                'tongGoiPT' => 0,

                'tongLop' => 0,

                'tongHoaDon' => 0,

                'goiTap' => collect(),

                'thongBao' => collect(),

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Gói tập
    |--------------------------------------------------------------------------
    */

    $goiTap = DB::table('dang_ky_goi_tap')
        ->join(
            'goi_tap',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->where(
            'dang_ky_goi_tap.hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->select(
            'goi_tap.ten_goi',
            'dang_ky_goi_tap.ngay_bat_dau',
            'dang_ky_goi_tap.ngay_ket_thuc',
            'dang_ky_goi_tap.so_buoi_con_lai',
            'dang_ky_goi_tap.trang_thai'
        )
        ->orderByDesc(
            'dang_ky_goi_tap.ngay_bat_dau'
        )
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Tổng gói PT
    |--------------------------------------------------------------------------
    */

    $tongGoiPT = DB::table('dang_ky_goi_pt')
        ->where(
            'hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Tổng lớp tập
    |--------------------------------------------------------------------------
    */

    $tongLop = DB::table('dang_ky_lop')
        ->where(
            'hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Tổng hóa đơn
    |--------------------------------------------------------------------------
    */

    $tongHoaDon = DB::table('hoa_don')
        ->where(
            'hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Thông báo
    |--------------------------------------------------------------------------
    */

    $thongBao = DB::table('thong_bao')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->orderByDesc('tao_luc')
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Tổng gói tập
    |--------------------------------------------------------------------------
    */

    $tongGoiTap = $goiTap->count();


    /*
    |--------------------------------------------------------------------------
    | Hiển thị
    |--------------------------------------------------------------------------
    */

    return view(
        'user.dashboard',
        compact(
            'tongGoiTap',
            'tongGoiPT',
            'tongLop',
            'tongHoaDon',
            'goiTap',
            'thongBao'
        )
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| DASHBOARD HUẤN LUYỆN VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/trainer/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra đăng nhập
    |--------------------------------------------------------------------------
    */

    if (!session()->has('user')) {

        return redirect('/login');

    }


    /*
    |--------------------------------------------------------------------------
    | Chỉ Huấn luyện viên
    |--------------------------------------------------------------------------
    */

    if (session('role_id') != 4) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ID người dùng hiện tại
    |--------------------------------------------------------------------------
    */

    $nguoiDungId = session('user')->nguoi_dung_id;


    /*
    |--------------------------------------------------------------------------
    | Tìm hồ sơ huấn luyện viên
    |--------------------------------------------------------------------------
    */

    $pt = DB::table('huan_luyen_vien')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->first();


    /*
    |--------------------------------------------------------------------------
    | Nếu chưa có hồ sơ PT
    |--------------------------------------------------------------------------
    */

    if (!$pt) {

        return view(
            'trainer.dashboard',
            [

                'tongHocVien' => 0,

                'tongGoiPT' => 0,

                'tongLichPT' => 0,

                'lichPT' => collect(),

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Tổng học viên PT
    |--------------------------------------------------------------------------
    */

    $tongHocVien = DB::table('dang_ky_goi_pt')
        ->where(
            'pt_id',
            $pt->pt_id
        )
        ->distinct()
        ->count('hoi_vien_id');


    /*
    |--------------------------------------------------------------------------
    | Tổng đăng ký gói PT
    |--------------------------------------------------------------------------
    */

    $tongGoiPT = DB::table('dang_ky_goi_pt')
        ->where(
            'pt_id',
            $pt->pt_id
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Tổng lịch PT
    |--------------------------------------------------------------------------
    */

    $tongLichPT = DB::table('lich_pt')
        ->where(
            'pt_id',
            $pt->pt_id
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Lịch PT gần đây
    |--------------------------------------------------------------------------
    */

    $lichPT = DB::table('lich_pt')
        ->join(
            'hoi_vien',
            'lich_pt.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where(
            'lich_pt.pt_id',
            $pt->pt_id
        )
        ->select(
            'nguoi_dung.ho_ten',
            'lich_pt.thoi_gian_bat_dau',
            'lich_pt.thoi_gian_ket_thuc',
            'lich_pt.trang_thai'
        )
        ->orderByDesc(
            'lich_pt.thoi_gian_bat_dau'
        )
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Hiển thị
    |--------------------------------------------------------------------------
    */

    return view(
        'trainer.dashboard',
        compact(
            'tongHocVien',
            'tongGoiPT',
            'tongLichPT',
            'lichPT'
        )
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| ĐĂNG XUẤT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    session()->invalidate();

    session()->regenerateToken();

    return redirect('/');

});