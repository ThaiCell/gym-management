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

    $goiTap = DB::table('goi_tap')
        ->whereIn('trang_thai', [
            'hoạt_động',
            'hoat_dong'
        ])
        ->orderBy('gia', 'asc')
        ->get();

    return view('user.packages', compact('goiTap'));

});

Route::post('/packages/register/{goiTapId}', function ($goiTapId) {

    // Chưa đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên được đăng ký
    if (session('role_id') != 3) {
        return redirect('/packages')
            ->with('error', 'Chỉ hội viên mới có thể đăng ký gói tập.');
    }

    // Lấy hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where(
            'nguoi_dung_id',
            session('user')->nguoi_dung_id
        )
        ->first();

    if (!$hoiVien) {
        return redirect('/packages')
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    // Lấy gói tập
    $goi = DB::table('goi_tap')
        ->where('goi_tap_id', $goiTapId)
        ->whereIn('trang_thai', [
            'hoạt_động',
            'hoat_dong'
        ])
        ->first();

    if (!$goi) {
        return redirect('/packages')
            ->with('error', 'Gói tập không tồn tại hoặc đã ngừng hoạt động.');
    }

    // Kiểm tra hội viên đã có gói đang hoạt động
    $dangKyDangHoatDong = DB::table('dang_ky_goi_tap')
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->whereIn('trang_thai', [
            'đang hoạt động',
            'dang_hoat_dong',
            'hoạt_động',
            'hoat_dong'
        ])
        ->exists();

    if ($dangKyDangHoatDong) {
        return redirect('/packages')
            ->with(
                'error',
                'Bạn đang có một gói tập hoạt động. Vui lòng sử dụng hết gói hiện tại trước khi đăng ký gói mới.'
            );
    }

    // Ngày bắt đầu
    $ngayBatDau = now()->toDateString();

    // Ngày kết thúc
    $ngayKetThuc = now()
        ->addDays($goi->thoi_han_ngay)
        ->toDateString();

    // Tạo đăng ký
    DB::table('dang_ky_goi_tap')->insert([
        'hoi_vien_id' => $hoiVien->hoi_vien_id,
        'goi_tap_id' => $goi->goi_tap_id,
        'ngay_bat_dau' => $ngayBatDau,
        'ngay_ket_thuc' => $ngayKetThuc,
        'so_buoi_con_lai' => $goi->so_buoi ?? 0,
        'trang_thai' => 'đang hoạt động',
    ]);

    return redirect('/packages')
        ->with(
            'success',
            'Đăng ký gói "' . $goi->ten_goi . '" thành công!'
        );

});

/*
|--------------------------------------------------------------------------
| LỚP TẬP
|--------------------------------------------------------------------------
*/

Route::get('/classes', function () {

    $lopTap = DB::table('lop_tap')
        ->whereIn('trang_thai', [
            'hoạt_động',
            'hoat_dong'
        ])
        ->orderBy('lop_tap_id', 'asc')
        ->get();

    return view('user.classes', compact('lopTap'));

});


Route::post('/classes/register/{lopTapId}', function ($lopTapId) {

    // Chưa đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên mới được đăng ký
    if (session('role_id') != 3) {
        return redirect('/classes')
            ->with(
                'error',
                'Chỉ hội viên mới có thể đăng ký lớp tập.'
            );
    }

    // Lấy thông tin hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where(
            'nguoi_dung_id',
            session('user')->nguoi_dung_id
        )
        ->first();

    if (!$hoiVien) {
        return redirect('/classes')
            ->with(
                'error',
                'Không tìm thấy hồ sơ hội viên.'
            );
    }

    // Lấy lớp tập
    $lop = DB::table('lop_tap')
        ->where('lop_tap_id', $lopTapId)
        ->whereIn('trang_thai', [
            'hoạt_động',
            'hoat_dong'
        ])
        ->first();

    if (!$lop) {
        return redirect('/classes')
            ->with(
                'error',
                'Lớp tập không tồn tại hoặc đã ngừng hoạt động.'
            );
    }

    // Kiểm tra đã đăng ký lớp này chưa
    $daDangKy = DB::table('dang_ky_lop')
        ->where('lop_tap_id', $lop->lop_tap_id)
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->whereIn('trang_thai', [
            'đang hoạt động',
            'dang_hoat_dong',
            'hoạt_động',
            'hoat_dong'
        ])
        ->exists();

    if ($daDangKy) {
        return redirect('/classes')
            ->with(
                'error',
                'Bạn đã đăng ký lớp này rồi.'
            );
    }

    // Kiểm tra sức chứa
    if ($lop->suc_chua) {

        $soNguoiDangKy = DB::table('dang_ky_lop')
            ->where(
                'lop_tap_id',
                $lop->lop_tap_id
            )
            ->whereIn('trang_thai', [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ])
            ->count();

        if ($soNguoiDangKy >= $lop->suc_chua) {
            return redirect('/classes')
                ->with(
                    'error',
                    'Lớp tập đã đủ số lượng. Vui lòng chọn lớp khác.'
                );
        }
    }

    // Đăng ký lớp
    DB::table('dang_ky_lop')->insert([
        'lop_tap_id' => $lop->lop_tap_id,
        'hoi_vien_id' => $hoiVien->hoi_vien_id,
        'ngay_dang_ky' => now(),
        'trang_thai' => 'đang hoạt động',
    ]);

    return redirect('/classes')
        ->with(
            'success',
            'Đăng ký lớp "' . $lop->ten_lop . '" thành công!'
        );

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
| GÓI PT
|--------------------------------------------------------------------------
*/

Route::get('/pt-packages', function () {

    $goiPt = DB::table('goi_pt')
        ->orderBy('gia', 'asc')
        ->get();

    return view('user.pt-packages', compact('goiPt'));
});


/*
|--------------------------------------------------------------------------
| ĐĂNG KÝ GÓI PT
|--------------------------------------------------------------------------
*/

Route::post('/pt-packages/{id}/register', function ($id) {

    // Chưa đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên được đăng ký
    if (session('role_id') != 3) {
        return redirect('/' . session('dashboard_path'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;


    // Tìm hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return back()->with(
            'error',
            'Không tìm thấy thông tin hội viên.'
        );
    }


    // Tìm gói PT
    $goiPt = DB::table('goi_pt')
        ->where('goi_pt_id', $id)
        ->first();

    if (!$goiPt) {
        return back()->with(
            'error',
            'Gói PT không tồn tại.'
        );
    }


    // Kiểm tra gói đang hoạt động
    if (
        $goiPt->trang_thai !== 'hoat_dong' &&
        $goiPt->trang_thai !== 'hoạt_động'
    ) {
        return back()->with(
            'error',
            'Gói PT này hiện không hoạt động.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA ĐĂNG KÝ TRÙNG
    |--------------------------------------------------------------------------
    */

    $daDangKy = DB::table('dang_ky_goi_pt')
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->where('goi_pt_id', $goiPt->goi_pt_id)
        ->whereIn('trang_thai', [
            'đang hoạt động',
            'dang_hoat_dong',
            'hoạt_động',
            'hoat_dong'
        ])
        ->exists();


    if ($daDangKy) {

        return back()
            ->with('error', 'Bạn đã đăng ký gói PT này rồi.');

    }


    /*
    |--------------------------------------------------------------------------
    | TÌM HUẤN LUYỆN VIÊN
    |--------------------------------------------------------------------------
    */

    $pt = DB::table('huan_luyen_vien')
        ->orderBy('pt_id', 'asc')
        ->first();


    if (!$pt) {

        return back()
            ->with(
                'error',
                'Hiện tại chưa có huấn luyện viên.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | NGÀY BẮT ĐẦU / KẾT THÚC
    |--------------------------------------------------------------------------
    */

    $ngayBatDau = now()->toDateString();

    $ngayKetThuc = now()
        ->addDays($goiPt->thoi_han_ngay - 1)
        ->toDateString();


    /*
    |--------------------------------------------------------------------------
    | TẠO ĐĂNG KÝ
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        DB::table('dang_ky_goi_pt')->insert([

            'hoi_vien_id' => $hoiVien->hoi_vien_id,

            'goi_pt_id' => $goiPt->goi_pt_id,

            'pt_id' => $pt->pt_id,

            'ngay_bat_dau' => $ngayBatDau,

            'ngay_ket_thuc' => $ngayKetThuc,

            'so_buoi_con_lai' => $goiPt->so_buoi,

            'trang_thai' => 'đang hoạt động',

        ]);


        /*
        |--------------------------------------------------------------------------
        | TẠO THÔNG BÁO
        |--------------------------------------------------------------------------
        */

        DB::table('thong_bao')->insert([

            'nguoi_dung_id' => $nguoiDungId,

            'tieu_de' => 'Đăng ký gói PT thành công',

            'noi_dung' =>
                'Bạn đã đăng ký thành công gói ' .
                $goiPt->ten_goi_pt .
                '. Có ' .
                $goiPt->so_buoi .
                ' buổi, thời hạn ' .
                $goiPt->thoi_han_ngay .
                ' ngày.',

            'da_doc' => false,

            'tao_luc' => now(),

        ]);


        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | QUAY LẠI TRANG GÓI PT + HIỆN TOAST
        |--------------------------------------------------------------------------
        */

        return back()
            ->with(
                'success',
                'Đăng ký gói ' .
                $goiPt->ten_goi_pt .
                ' thành công!'
            );


    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->with(
                'error',
                'Không thể đăng ký gói PT: ' .
                $e->getMessage()
            );

    }

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
| Dữ liệu gói PT
|--------------------------------------------------------------------------
*/

$goiPT = DB::table('dang_ky_goi_pt')
    ->join(
        'goi_pt',
        'dang_ky_goi_pt.goi_pt_id',
        '=',
        'goi_pt.goi_pt_id'
    )
    ->join(
        'huan_luyen_vien',
        'dang_ky_goi_pt.pt_id',
        '=',
        'huan_luyen_vien.pt_id'
    )
    ->join(
        'nguoi_dung',
        'huan_luyen_vien.nguoi_dung_id',
        '=',
        'nguoi_dung.nguoi_dung_id'
    )
    ->where(
        'dang_ky_goi_pt.hoi_vien_id',
        $hoiVien->hoi_vien_id
    )
    ->select(
        'dang_ky_goi_pt.*',
        'goi_pt.ten_goi_pt as ten_goi',
        'nguoi_dung.ho_ten'
    )
    ->orderByDesc('dang_ky_goi_pt.dang_ky_goi_pt_id')
    ->get();

    /*
|--------------------------------------------------------------------------
| Dữ liệu lớp tập
|--------------------------------------------------------------------------
*/

$lopTap = DB::table('dang_ky_lop')
    ->join(
        'lop_tap',
        'dang_ky_lop.lop_tap_id',
        '=',
        'lop_tap.lop_tap_id'
    )
    ->where(
        'dang_ky_lop.hoi_vien_id',
        $hoiVien->hoi_vien_id
    )
    ->select(
        'dang_ky_lop.*',
        'lop_tap.ten_lop',
        'lop_tap.lich_tap',
        'lop_tap.mo_ta'
    )
    ->orderByDesc('dang_ky_lop.ngay_dang_ky')
    ->get();

    /*
|--------------------------------------------------------------------------
| Dữ liệu hóa đơn
|--------------------------------------------------------------------------
*/

$hoaDon = DB::table('hoa_don')
    ->where(
        'hoi_vien_id',
        $hoiVien->hoi_vien_id
    )
    ->orderByDesc('ngay_lap')
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
        'goiPT',
        'lopTap',
        'hoaDon',
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
| HỒ SƠ HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/user/profile', function () {

    if (!session()->has('user')) {

        return redirect('/login');

    }

    if (session('role_id') != 3) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    $user = DB::table('nguoi_dung')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->first();

    $hoiVien = DB::table('hoi_vien')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->first();

    if (!$user || !$hoiVien) {

        return redirect('/user/dashboard')
            ->with(
                'error',
                'Không tìm thấy hồ sơ hội viên.'
            );

    }

    return view(
        'user.profile',
        compact(
            'user',
            'hoiVien'
        )
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| CẬP NHẬT HỒ SƠ HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::post('/user/profile', function () {

    if (!session()->has('user')) {

        return redirect('/login');

    }

    if (session('role_id') != 3) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    $validated = request()->validate([

        'ho_ten' => [
            'required',
            'string',
            'max:255'
        ],

        'email' => [
            'required',
            'email',
            'max:255'
        ],

        'so_dien_thoai' => [
            'nullable',
            'string',
            'max:20'
        ],

        'ngay_sinh' => [
            'nullable',
            'date'
        ],

        'dia_chi' => [
            'nullable',
            'string',
            'max:255'
        ],

        'mat_khau_hien_tai' => [
            'nullable',
            'string'
        ],

        'mat_khau_moi' => [
            'nullable',
            'string',
            'min:6',
            'same:mat_khau_moi_confirmation'
        ],

        'mat_khau_moi_confirmation' => [
            'nullable',
            'string'
        ],

    ], [

        'ho_ten.required' =>
            'Vui lòng nhập họ và tên.',

        'email.required' =>
            'Vui lòng nhập email.',

        'email.email' =>
            'Email không đúng định dạng.',

        'mat_khau_moi.min' =>
            'Mật khẩu mới phải có ít nhất 6 ký tự.',

        'mat_khau_moi.same' =>
            'Xác nhận mật khẩu mới không khớp.',

    ]);

    $emailTonTai = DB::table('nguoi_dung')

        ->where(
            'email',
            $validated['email']
        )

        ->where(
            'nguoi_dung_id',
            '!=',
            $nguoiDungId
        )

        ->exists();

    if ($emailTonTai) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Email này đã được sử dụng bởi tài khoản khác.'
            );

    }

    if (!empty($validated['mat_khau_moi'])) {

        $user = DB::table('nguoi_dung')

            ->where(
                'nguoi_dung_id',
                $nguoiDungId
            )

            ->first();

        $matKhauDung = false;

        if (
            $validated['mat_khau_hien_tai']
            ===
            $user->mat_khau
        ) {

            $matKhauDung = true;

        } elseif (

            str_starts_with(
                $user->mat_khau,
                '$2y$'
            )

            ||

            str_starts_with(
                $user->mat_khau,
                '$2a$'
            )

            ||

            str_starts_with(
                $user->mat_khau,
                '$2b$'
            )

        ) {

            $matKhauDung = Hash::check(
                $validated['mat_khau_hien_tai'],
                $user->mat_khau
            );

        }

        if (!$matKhauDung) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Mật khẩu hiện tại không đúng.'
                );

        }

    }

    DB::beginTransaction();

    try {

        $nguoiDungData = [

            'ho_ten' =>
                $validated['ho_ten'],

            'email' =>
                $validated['email'],

        ];

        if (!empty($validated['mat_khau_moi'])) {

            $nguoiDungData['mat_khau'] =
                Hash::make(
                    $validated['mat_khau_moi']
                );

        }

        DB::table('nguoi_dung')

            ->where(
                'nguoi_dung_id',
                $nguoiDungId
            )

            ->update(
                $nguoiDungData
            );

        DB::table('hoi_vien')

            ->where(
                'nguoi_dung_id',
                $nguoiDungId
            )

            ->update([

                'ngay_sinh' =>
                    $validated['ngay_sinh']
                    ?: null,

                'so_dien_thoai' =>
                    $validated['so_dien_thoai']
                    ?: null,

                'dia_chi' =>
                    $validated['dia_chi']
                    ?: null,

            ]);

        DB::commit();

        $userMoi = DB::table('nguoi_dung')

            ->where(
                'nguoi_dung_id',
                $nguoiDungId
            )

            ->first();

        session([
            'user' => $userMoi
        ]);

        return redirect('/user/profile')

            ->with(
                'success',
                'Cập nhật hồ sơ thành công.'
            );

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                'Không thể cập nhật hồ sơ: '
                . $e->getMessage()
            );

    }

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

