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

    // =========================================================
    // KIỂM TRA ĐĂNG NHẬP
    // =========================================================

    if (!session()->has('user')) {
        return redirect('/login');
    }


    // =========================================================
    // CHỈ HỘI VIÊN ĐƯỢC ĐĂNG KÝ
    // =========================================================

    if (session('role_id') != 3) {

        return redirect('/packages')
            ->with(
                'error',
                'Chỉ hội viên mới có thể đăng ký gói tập.'
            );

    }


    // =========================================================
    // TÌM HỘI VIÊN
    // =========================================================

    $nguoiDungId = session('user')->nguoi_dung_id;

    $hoiVien = DB::table('hoi_vien')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->first();


    if (!$hoiVien) {

        return redirect('/packages')
            ->with(
                'error',
                'Không tìm thấy hồ sơ hội viên.'
            );

    }


    // =========================================================
    // TÌM GÓI TẬP
    // =========================================================

    $goi = DB::table('goi_tap')
        ->where(
            'goi_tap_id',
            $goiTapId
        )
        ->whereIn(
            'trang_thai',
            [
                'hoạt_động',
                'hoat_dong'
            ]
        )
        ->first();


    if (!$goi) {

        return redirect('/packages')
            ->with(
                'error',
                'Gói tập không tồn tại hoặc đã ngừng hoạt động.'
            );

    }


    // =========================================================
    // KIỂM TRA HỘI VIÊN ĐÃ CÓ GÓI ĐANG HOẠT ĐỘNG
    // =========================================================

    $dangKyDangHoatDong = DB::table('dang_ky_goi_tap')
        ->where(
            'hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->whereIn(
            'trang_thai',
            [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ]
        )
        ->exists();


    if ($dangKyDangHoatDong) {

        return redirect('/packages')
            ->with(
                'error',
                'Bạn đang có một gói tập hoạt động. Vui lòng sử dụng hết gói hiện tại trước khi đăng ký gói mới.'
            );

    }


    // =========================================================
    // NGÀY BẮT ĐẦU / KẾT THÚC
    // =========================================================

    $ngayBatDau = now()->toDateString();

    $ngayKetThuc = now()
        ->addDays($goi->thoi_han_ngay)
        ->toDateString();


    // =========================================================
    // TÌM NHÂN VIÊN LẬP HÓA ĐƠN
    // =========================================================

    /*
    |--------------------------------------------------------------------------
    | Bảng hoa_don bắt buộc phải có nhan_vien_id.
    | Nếu hội viên tự đăng ký trên website thì lấy nhân viên
    | đầu tiên trong hệ thống làm người lập hóa đơn.
    |--------------------------------------------------------------------------
    */

    $nhanVien = DB::table('nhan_vien')
        ->orderBy('nhan_vien_id', 'asc')
        ->first();


    if (!$nhanVien) {

        return redirect('/packages')
            ->with(
                'error',
                'Hệ thống chưa có nhân viên để lập hóa đơn.'
            );

    }


    // =========================================================
    // TẠO ĐĂNG KÝ + HÓA ĐƠN + CHI TIẾT HÓA ĐƠN
    // =========================================================

    try {

        DB::transaction(function () use (
            $hoiVien,
            $goi,
            $ngayBatDau,
            $ngayKetThuc,
            $nhanVien
        ) {

            // -------------------------------------------------
            // 1. TẠO ĐĂNG KÝ GÓI TẬP
            // -------------------------------------------------

            $dangKyGoiTapId = DB::table('dang_ky_goi_tap')
                ->insertGetId([

                    'hoi_vien_id' =>
                        $hoiVien->hoi_vien_id,

                    'goi_tap_id' =>
                        $goi->goi_tap_id,

                    'ngay_bat_dau' =>
                        $ngayBatDau,

                    'ngay_ket_thuc' =>
                        $ngayKetThuc,

                    'so_buoi_con_lai' =>
                        $goi->so_buoi ?? 0,

                    'trang_thai' =>
                        'đang hoạt động',

                ]);


            // -------------------------------------------------
            // 2. TẠO HÓA ĐƠN
            // -------------------------------------------------

            $hoaDonId = DB::table('hoa_don')
                ->insertGetId([

                    'hoi_vien_id' =>
                        $hoiVien->hoi_vien_id,

                    'nhan_vien_id' =>
                        $nhanVien->nhan_vien_id,

                    'ngay_lap' =>
                        now(),

                    'tong_tien' =>
                        $goi->gia,

                    'trang_thai' =>
                        'chờ thanh toán',

                    'phuong_thuc_thanh_toan' =>
                        null,

                ]);


            // -------------------------------------------------
            // 3. TẠO CHI TIẾT HÓA ĐƠN
            // -------------------------------------------------

            DB::table('chi_tiet_hoa_don')
                ->insert([

                    'hoa_don_id' =>
                        $hoaDonId,

                    'goi_tap_id' =>
                        $goi->goi_tap_id,

                    'goi_pt_id' =>
                        null,

                    'so_luong' =>
                        1,

                    'don_gia' =>
                        $goi->gia,

                    'thanh_tien' =>
                        $goi->gia,

                ]);


            // -------------------------------------------------
            // 4. TẠO THÔNG BÁO
            // -------------------------------------------------

            DB::table('thong_bao')
                ->insert([

                    'nguoi_dung_id' =>
                        $hoiVien->nguoi_dung_id,

                    'tieu_de' =>
                        'Đăng ký gói tập thành công',

                    'noi_dung' =>
                        'Bạn đã đăng ký gói "'
                        . $goi->ten_goi
                        . '". Hóa đơn đang chờ thanh toán.',

                    'da_doc' =>
                        0,

                    'tao_luc' =>
                        now(),

                ]);

        });


        // =====================================================
        // THÀNH CÔNG
        // =====================================================

        return redirect('/packages')
            ->with(
                'success',
                'Đăng ký gói "'
                . $goi->ten_goi
                . '" thành công! Hóa đơn đã được tạo.'
            );


    } catch (\Exception $e) {

        // =====================================================
        // LỖI
        // =====================================================

        return redirect('/packages')
            ->with(
                'error',
                'Không thể tạo đăng ký và hóa đơn: '
                . $e->getMessage()
            );

    }

});



/*
|--------------------------------------------------------------------------
| LỚP TẬP
|--------------------------------------------------------------------------
*/

Route::get('/classes', function () {

    // Danh sách lớp đang hoạt động
    $lopTap = DB::table('lop_tap')
        ->whereIn('trang_thai', [
            'hoạt_động',
            'hoat_dong'
        ])
        ->orderBy('lop_tap_id', 'asc')
        ->get();


    // Mặc định chưa đăng nhập
    $hoiVien = null;
    $lopDaDangKy = [];
    $dangKyLop = [];


    // Nếu là hội viên
    if (
        session()->has('user') &&
        session('role_id') == 3
    ) {

        $hoiVien = DB::table('hoi_vien')
            ->where(
                'nguoi_dung_id',
                session('user')->nguoi_dung_id
            )
            ->first();


        if ($hoiVien) {

           $dangKyLop = DB::table('dang_ky_lop')
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
    ->where(
        'dang_ky_lop.trang_thai',
        '!=',
        'đã hủy'
    )
    ->select(
        'dang_ky_lop.dang_ky_lop_id',
        'dang_ky_lop.lop_tap_id',
        'dang_ky_lop.ngay_dang_ky',
        'dang_ky_lop.trang_thai',
        'lop_tap.ten_lop',
        'lop_tap.lich_tap'
    )
    ->orderByDesc('dang_ky_lop.ngay_dang_ky')
    ->get();

$lopDaDangKy = $dangKyLop
    ->pluck('lop_tap_id')
    ->toArray();

        }

    }


    return view(
        'user.classes',
        compact(
            'lopTap',
            'lopDaDangKy',
            'dangKyLop'
        )
    );

});


/*
|--------------------------------------------------------------------------
| ĐĂNG KÝ LỚP
|--------------------------------------------------------------------------
*/

Route::post('/classes/register/{lopTapId}', function ($lopTapId) {

    // Chưa đăng nhập
    if (!session()->has('user')) {

        return redirect('/login');

    }


    // Chỉ hội viên
    if (session('role_id') != 3) {

        return redirect('/classes')
            ->with(
                'error',
                'Chỉ hội viên mới có thể đăng ký lớp tập.'
            );

    }


    // Tìm hội viên
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


    // Tìm lớp
    $lop = DB::table('lop_tap')
        ->where(
            'lop_tap_id',
            $lopTapId
        )
        ->whereIn('trang_thai', [
            'hoạt_động',
            'hoat_dong',
            'hoạt động'
        ])
        ->first();


    if (!$lop) {

        return redirect('/classes')
            ->with(
                'error',
                'Lớp tập không tồn tại hoặc đã ngừng hoạt động.'
            );

    }


    // Kiểm tra đăng ký trùng
    $daDangKy = DB::table('dang_ky_lop')
        ->where(
            'lop_tap_id',
            $lop->lop_tap_id
        )
        ->where(
            'hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
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


    // Tạo đăng ký
    DB::table('dang_ky_lop')
        ->insert([

            'lop_tap_id' =>
                $lop->lop_tap_id,

            'hoi_vien_id' =>
                $hoiVien->hoi_vien_id,

            'ngay_dang_ky' =>
                now(),

            'trang_thai' =>
                'đang hoạt động',

        ]);


    // Tạo thông báo
    DB::table('thong_bao')
        ->insert([

            'nguoi_dung_id' =>
                $hoiVien->nguoi_dung_id,

            'tieu_de' =>
                'Đăng ký lớp thành công',

            'noi_dung' =>
                'Bạn đã đăng ký lớp "'
                . $lop->ten_lop
                . '" thành công.',

            'da_doc' =>
                0,

            'tao_luc' =>
                now(),

        ]);


    return redirect('/classes')
        ->with(
            'success',
            'Đăng ký lớp "'
            . $lop->ten_lop
            . '" thành công!'
        );

});


/*
|--------------------------------------------------------------------------
| HỦY ĐĂNG KÝ LỚP
|--------------------------------------------------------------------------
*/

Route::post('/classes/cancel/{id}', function ($id) {

    // Chưa đăng nhập
    if (!session()->has('user')) {

        return redirect('/login');

    }


    // Chỉ hội viên
    if (session('role_id') != 3) {

        return redirect('/classes');

    }


    // Tìm hội viên
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


    // Tìm đăng ký
    $dangKy = DB::table('dang_ky_lop')
        ->join(
            'lop_tap',
            'dang_ky_lop.lop_tap_id',
            '=',
            'lop_tap.lop_tap_id'
        )
        ->where(
            'dang_ky_lop.dang_ky_lop_id',
            $id
        )
        ->where(
            'dang_ky_lop.hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->select(
            'dang_ky_lop.*',
            'lop_tap.ten_lop'
        )
        ->first();


    if (!$dangKy) {

        return redirect('/classes')
            ->with(
                'error',
                'Không tìm thấy đăng ký lớp.'
            );

    }


    // Chỉ hủy đăng ký đang hoạt động
    if (!in_array(
        strtolower(trim($dangKy->trang_thai)),
        [
            'đang hoạt động',
            'dang_hoat_dong',
            'hoạt động',
            'hoat_dong'
        ]
    )) {

        return redirect('/classes')
            ->with(
                'error',
                'Đăng ký lớp này đã được xử lý.'
            );

    }


    // Cập nhật trạng thái
    DB::table('dang_ky_lop')
        ->where(
            'dang_ky_lop_id',
            $id
        )
        ->update([

            'trang_thai' =>
                'đã hủy',

        ]);


    // Thông báo
    DB::table('thong_bao')
        ->insert([

            'nguoi_dung_id' =>
                $hoiVien->nguoi_dung_id,

            'tieu_de' =>
                'Hủy đăng ký lớp',

            'noi_dung' =>
                'Bạn đã hủy đăng ký lớp "'
                . $dangKy->ten_lop
                . '".',

            'da_doc' =>
                0,

            'tao_luc' =>
                now(),

        ]);


    return redirect('/classes')
        ->with(
            'success',
            'Đã hủy đăng ký lớp "'
            . $dangKy->ten_lop
            . '".'
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
    if (!in_array($user->trang_thai, [
        'hoat_dong',
        'hoạt_động',
        'hoạt động',
    ])) {
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


Route::get('/admin/members', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $keyword = trim(request('q', ''));

    $query = DB::table('hoi_vien')
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->select(
            'hoi_vien.hoi_vien_id',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai',
            'hoi_vien.so_dien_thoai',
            'hoi_vien.ngay_tham_gia'
        );

    if ($keyword !== '') {
        $query->where(function ($q) use ($keyword) {
            $q->where('nguoi_dung.ho_ten', 'like', '%' . $keyword . '%')
              ->orWhere('nguoi_dung.email', 'like', '%' . $keyword . '%')
              ->orWhere('hoi_vien.so_dien_thoai', 'like', '%' . $keyword . '%');
        });
    }

    $hoiVien = $query
        ->orderByDesc('hoi_vien.hoi_vien_id')
        ->get();

    return view('admin.members', compact('hoiVien', 'keyword'));

});

Route::get('/admin/members/{id}', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $member = DB::table('hoi_vien')
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('hoi_vien.hoi_vien_id', $id)
        ->select(
            'hoi_vien.hoi_vien_id',
            'hoi_vien.nguoi_dung_id',
            'hoi_vien.ngay_sinh',
            'hoi_vien.so_dien_thoai',
            'hoi_vien.dia_chi',
            'hoi_vien.ngay_tham_gia',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai'
        )
        ->first();

    if (!$member) {
        return redirect('/admin/members')
            ->with('error', 'Không tìm thấy hội viên.');
    }

    $packages = DB::table('dang_ky_goi_tap')
        ->join(
            'goi_tap',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->where('dang_ky_goi_tap.hoi_vien_id', $id)
        ->select(
            'goi_tap.ten_goi',
            'goi_tap.gia',
            'dang_ky_goi_tap.ngay_bat_dau',
            'dang_ky_goi_tap.ngay_ket_thuc',
            'dang_ky_goi_tap.so_buoi_con_lai',
            'dang_ky_goi_tap.trang_thai'
        )
        ->orderByDesc('dang_ky_goi_tap.dang_ky_goi_tap_id')
        ->get();

    return view('admin.member-detail', compact('member', 'packages'));

});

Route::get('/admin/members/{id}/edit', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $member = DB::table('hoi_vien')
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('hoi_vien.hoi_vien_id', $id)
        ->select(
            'hoi_vien.hoi_vien_id',
            'hoi_vien.nguoi_dung_id',
            'hoi_vien.ngay_sinh',
            'hoi_vien.so_dien_thoai',
            'hoi_vien.dia_chi',
            'hoi_vien.ngay_tham_gia',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai'
        )
        ->first();

    if (!$member) {
        return redirect('/admin/members');
    }

    return view('admin.member-edit', compact('member'));

});


Route::post('/admin/members/{id}/edit', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $member = DB::table('hoi_vien')
        ->where('hoi_vien_id', $id)
        ->first();

    if (!$member) {
        return redirect('/admin/members');
    }

    $hoTen = trim(request('ho_ten'));
    $email = trim(request('email'));
    $soDienThoai = trim(request('so_dien_thoai'));
    $ngaySinh = request('ngay_sinh');
    $diaChi = trim(request('dia_chi'));
    $trangThai = request('trang_thai');

    if ($hoTen === '' || $email === '') {
        return back()
            ->withInput()
            ->with('error', 'Họ tên và email không được để trống.');
    }

    $emailExists = DB::table('nguoi_dung')
        ->where('email', $email)
        ->where('nguoi_dung_id', '!=', $member->nguoi_dung_id)
        ->exists();

    if ($emailExists) {
        return back()
            ->withInput()
            ->with('error', 'Email này đã được sử dụng.');
    }

    DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $member->nguoi_dung_id)
        ->update([
            'ho_ten' => $hoTen,
            'email' => $email,
            'trang_thai' => $trangThai,
        ]);

    DB::table('hoi_vien')
        ->where('hoi_vien_id', $id)
        ->update([
            'ngay_sinh' => $ngaySinh ?: null,
            'so_dien_thoai' => $soDienThoai ?: null,
            'dia_chi' => $diaChi ?: null,
        ]);

    return redirect('/admin/members/' . $id)
        ->with('success', 'Cập nhật thông tin hội viên thành công.');

});

Route::post('/admin/members/{id}/toggle-status', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $member = DB::table('hoi_vien')
        ->where('hoi_vien_id', $id)
        ->first();

    if (!$member) {
        return redirect('/admin/members')
            ->with('error', 'Không tìm thấy hội viên.');
    }

    $newStatus = $member->trang_thai === 'hoat_dong'
        ? 'bi_khoa'
        : 'hoat_dong';

    DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $member->nguoi_dung_id)
        ->update([
            'trang_thai' => $newStatus
        ]);

    $message = $newStatus === 'hoat_dong'
        ? 'Đã mở khóa tài khoản hội viên.'
        : 'Đã khóa tài khoản hội viên.';

    return redirect('/admin/members/' . $id)
        ->with('success', $message);

});


Route::get('/admin/packages', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $keyword = trim(request('q', ''));

    $query = DB::table('goi_tap')
        ->select(
            'goi_tap_id',
            'ten_goi',
            'gia',
            'thoi_han_ngay',
            'so_buoi',
            'trang_thai'
        );

    if ($keyword !== '') {
        $query->where('ten_goi', 'like', '%' . $keyword . '%');
    }

    $packages = $query
        ->orderByDesc('goi_tap_id')
        ->get();

    return view('admin.packages', compact('packages', 'keyword'));

});
// =====================================================
// ADMIN - THÊM GÓI TẬP
// =====================================================

Route::get('/admin/packages/create', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    return view('admin.package-create');

});


// =====================================================
// ADMIN - LƯU GÓI TẬP
// =====================================================

Route::post('/admin/packages', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $validated = request()->validate([
        'ten_goi' => 'required|string|max:100',
        'gia' => 'required|numeric|min:0',
        'thoi_han_ngay' => 'required|integer|min:1',
        'so_buoi' => 'nullable|integer|min:1',
        'trang_thai' => 'required|string|max:50',
    ], [
        'ten_goi.required' => 'Vui lòng nhập tên gói tập.',
        'gia.required' => 'Vui lòng nhập giá gói tập.',
        'gia.numeric' => 'Giá phải là số.',
        'gia.min' => 'Giá không được nhỏ hơn 0.',
        'thoi_han_ngay.required' => 'Vui lòng nhập thời hạn.',
        'thoi_han_ngay.integer' => 'Thời hạn phải là số nguyên.',
        'thoi_han_ngay.min' => 'Thời hạn phải lớn hơn 0.',
        'so_buoi.integer' => 'Số buổi phải là số nguyên.',
        'so_buoi.min' => 'Số buổi phải lớn hơn 0.',
    ]);

    DB::table('goi_tap')->insert([
        'ten_goi' => $validated['ten_goi'],
        'gia' => $validated['gia'],
        'thoi_han_ngay' => $validated['thoi_han_ngay'],
        'so_buoi' => $validated['so_buoi'] ?? null,
        'trang_thai' => $validated['trang_thai'],
    ]);

    return redirect('/admin/packages')
        ->with('success', 'Thêm gói tập thành công.');

});


// =====================================================
// ADMIN - FORM SỬA GÓI TẬP
// =====================================================

Route::get('/admin/packages/{id}/edit', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $package = DB::table('goi_tap')
        ->where('goi_tap_id', $id)
        ->first();

    if (!$package) {
        return redirect('/admin/packages')
            ->with('error', 'Không tìm thấy gói tập.');
    }

    return view('admin.package-edit', compact('package'));

});


// =====================================================
// ADMIN - CẬP NHẬT GÓI TẬP
// =====================================================

Route::post('/admin/packages/{id}/update', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $package = DB::table('goi_tap')
        ->where('goi_tap_id', $id)
        ->first();

    if (!$package) {
        return redirect('/admin/packages')
            ->with('error', 'Không tìm thấy gói tập.');
    }

    $validated = request()->validate([
        'ten_goi' => 'required|string|max:100',
        'gia' => 'required|numeric|min:0',
        'thoi_han_ngay' => 'required|integer|min:1',
        'so_buoi' => 'nullable|integer|min:1',
        'trang_thai' => 'required|string|max:50',
    ], [
        'ten_goi.required' => 'Vui lòng nhập tên gói tập.',
        'gia.required' => 'Vui lòng nhập giá gói tập.',
        'gia.numeric' => 'Giá phải là số.',
        'gia.min' => 'Giá không được nhỏ hơn 0.',
        'thoi_han_ngay.required' => 'Vui lòng nhập thời hạn.',
        'thoi_han_ngay.integer' => 'Thời hạn phải là số nguyên.',
        'thoi_han_ngay.min' => 'Thời hạn phải lớn hơn 0.',
        'so_buoi.integer' => 'Số buổi phải là số nguyên.',
        'so_buoi.min' => 'Số buổi phải lớn hơn 0.',
    ]);

    DB::table('goi_tap')
        ->where('goi_tap_id', $id)
        ->update([
            'ten_goi' => $validated['ten_goi'],
            'gia' => $validated['gia'],
            'thoi_han_ngay' => $validated['thoi_han_ngay'],
            'so_buoi' => $validated['so_buoi'] ?? null,
            'trang_thai' => $validated['trang_thai'],
        ]);

    return redirect('/admin/packages/' . $id)
        ->with('success', 'Cập nhật gói tập thành công.');

});

// =====================================================
// ADMIN - TẠM NGƯNG / KÍCH HOẠT GÓI TẬP
// =====================================================

Route::post('/admin/packages/{id}/toggle-status', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $package = DB::table('goi_tap')
        ->where('goi_tap_id', $id)
        ->first();

    if (!$package) {
        return redirect('/admin/packages')
            ->with('error', 'Không tìm thấy gói tập.');
    }

    // Nếu đang hoạt động → tạm ngưng
    if ($package->trang_thai === 'hoat_dong') {

        DB::table('goi_tap')
            ->where('goi_tap_id', $id)
            ->update([
                'trang_thai' => 'tam_ngung'
            ]);

        $message = 'Đã tạm ngưng gói tập.';

    } else {

        // Nếu đang tạm ngưng → kích hoạt
        DB::table('goi_tap')
            ->where('goi_tap_id', $id)
            ->update([
                'trang_thai' => 'hoat_dong'
            ]);

        $message = 'Đã kích hoạt lại gói tập.';
    }

    return redirect('/admin/packages/' . $id)
        ->with('success', $message);

});


Route::get('/admin/packages/{id}', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $package = DB::table('goi_tap')
        ->where('goi_tap_id', $id)
        ->first();

    if (!$package) {
        return redirect('/admin/packages')
            ->with('error', 'Không tìm thấy gói tập.');
    }

    $registrations = DB::table('dang_ky_goi_tap')
        ->join(
            'hoi_vien',
            'dang_ky_goi_tap.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('dang_ky_goi_tap.goi_tap_id', $id)
        ->select(
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'dang_ky_goi_tap.ngay_bat_dau',
            'dang_ky_goi_tap.ngay_ket_thuc',
            'dang_ky_goi_tap.so_buoi_con_lai',
            'dang_ky_goi_tap.trang_thai'
        )
        ->orderByDesc('dang_ky_goi_tap.dang_ky_goi_tap_id')
        ->get();

    return view(
        'admin.package-detail',
        compact('package', 'registrations')
    );

});



// =====================================================
// ADMIN - DANH SÁCH HUẤN LUYỆN VIÊN
// =====================================================

Route::get('/admin/trainers', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $keyword = trim(request('q', ''));

    $query = DB::table('huan_luyen_vien')
        ->join(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->select(
            'huan_luyen_vien.pt_id',
            'huan_luyen_vien.chuyen_mon',
            'huan_luyen_vien.so_dien_thoai',
            'nguoi_dung.nguoi_dung_id',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai'
        );

    if ($keyword !== '') {

        $query->where(function ($q) use ($keyword) {

            $q->where(
                'nguoi_dung.ho_ten',
                'like',
                '%' . $keyword . '%'
            )
            ->orWhere(
                'nguoi_dung.email',
                'like',
                '%' . $keyword . '%'
            )
            ->orWhere(
                'huan_luyen_vien.chuyen_mon',
                'like',
                '%' . $keyword . '%'
            )
            ->orWhere(
                'huan_luyen_vien.so_dien_thoai',
                'like',
                '%' . $keyword . '%'
            );

        });
    }

    $trainers = $query
        ->orderByDesc('huan_luyen_vien.pt_id')
        ->get();

    return view('admin.trainers', compact(
        'trainers',
        'keyword'
    ));

});


Route::get('/admin/trainers/{id}', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    // Lấy thông tin huấn luyện viên
    $trainer = DB::table('huan_luyen_vien')
        ->join(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('huan_luyen_vien.pt_id', $id)
        ->select(
            'huan_luyen_vien.pt_id',
            'huan_luyen_vien.nguoi_dung_id',
            'huan_luyen_vien.chuyen_mon',
            'huan_luyen_vien.so_dien_thoai',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai'
        )
        ->first();

    if (!$trainer) {
        abort(404);
    }

    // Lấy lịch PT của huấn luyện viên
    $schedules = DB::table('lich_pt')
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
        ->where('lich_pt.pt_id', $id)
        ->select(
            'lich_pt.*',
            'nguoi_dung.ho_ten as ten_hoi_vien',
            'nguoi_dung.email as email_hoi_vien'
        )
        ->orderByDesc('lich_pt.thoi_gian_bat_dau')
        ->get();

    return view('admin.trainer-detail', compact('trainer', 'schedules'));
});

// ===============================
// ADMIN - SỬA THÔNG TIN HUẤN LUYỆN VIÊN
// ===============================

Route::get('/admin/trainers/{id}/edit', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $trainer = DB::table('huan_luyen_vien')
        ->join(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('huan_luyen_vien.pt_id', $id)
        ->select(
            'huan_luyen_vien.pt_id',
            'huan_luyen_vien.nguoi_dung_id',
            'huan_luyen_vien.chuyen_mon',
            'huan_luyen_vien.so_dien_thoai',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email'
        )
        ->first();

    if (!$trainer) {
        abort(404);
    }

    return view('admin.trainer-edit', compact('trainer'));
});


Route::post('/admin/trainers/{id}/edit', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $trainer = DB::table('huan_luyen_vien')
        ->where('pt_id', $id)
        ->first();

    if (!$trainer) {
        abort(404);
    }

    $request = request();

    $request->validate([
        'ho_ten' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'so_dien_thoai' => 'nullable|string|max:20',
        'chuyen_mon' => 'required|string|max:255',
    ]);

    // Kiểm tra email có bị tài khoản khác sử dụng không
    $emailExists = DB::table('nguoi_dung')
        ->where('email', $request->email)
        ->where('nguoi_dung_id', '!=', $trainer->nguoi_dung_id)
        ->exists();

    if ($emailExists) {
        return back()
            ->withInput()
            ->with('error', 'Email này đã được sử dụng.');
    }

    // Cập nhật thông tin tài khoản
    DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $trainer->nguoi_dung_id)
        ->update([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
        ]);

    // Cập nhật thông tin huấn luyện viên
    DB::table('huan_luyen_vien')
        ->where('pt_id', $id)
        ->update([
            'so_dien_thoai' => $request->so_dien_thoai,
            'chuyen_mon' => $request->chuyen_mon,
        ]);

    return redirect('/admin/trainers/' . $id)
        ->with('success', 'Cập nhật thông tin huấn luyện viên thành công.');
});


// ===============================
// ADMIN - KHÓA / KÍCH HOẠT PT
// ===============================

Route::post('/admin/trainers/{id}/toggle-status', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $trainer = DB::table('huan_luyen_vien')
        ->where('pt_id', $id)
        ->first();

    if (!$trainer) {
        abort(404);
    }

    $user = DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $trainer->nguoi_dung_id)
        ->first();

    if (!$user) {
        abort(404);
    }

    // Nếu đang hoạt động → khóa
    if ($user->trang_thai === 'hoat_dong') {

        DB::table('nguoi_dung')
            ->where('nguoi_dung_id', $trainer->nguoi_dung_id)
            ->update([
                'trang_thai' => 'bi_khoa'
            ]);

        $message = 'Đã khóa tài khoản huấn luyện viên.';

    } else {

        // Nếu đang khóa → kích hoạt
        DB::table('nguoi_dung')
            ->where('nguoi_dung_id', $trainer->nguoi_dung_id)
            ->update([
                'trang_thai' => 'hoat_dong'
            ]);

        $message = 'Đã kích hoạt tài khoản huấn luyện viên.';
    }

    return redirect('/admin/trainers/' . $id)
        ->with('success', $message);
});

/*
|--------------------------------------------------------------------------
| ADMIN - LỊCH TẬP
|--------------------------------------------------------------------------
*/

Route::get('/admin/schedules', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $keyword = trim(request('q', ''));
    $status = trim(request('status', ''));

    $query = DB::table('lich_pt')
        ->join(
            'hoi_vien',
            'lich_pt.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung as member_user',
            'hoi_vien.nguoi_dung_id',
            '=',
            'member_user.nguoi_dung_id'
        )
        ->join(
            'huan_luyen_vien',
            'lich_pt.pt_id',
            '=',
            'huan_luyen_vien.pt_id'
        )
        ->join(
            'nguoi_dung as trainer_user',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'trainer_user.nguoi_dung_id'
        )
        ->leftJoin(
            'dang_ky_goi_pt',
            'lich_pt.dang_ky_goi_pt_id',
            '=',
            'dang_ky_goi_pt.dang_ky_goi_pt_id'
        )
        ->leftJoin(
            'goi_pt',
            'dang_ky_goi_pt.goi_pt_id',
            '=',
            'goi_pt.goi_pt_id'
        )
        ->select(
            'lich_pt.*',
            'member_user.ho_ten as hoi_vien_ten',
            'member_user.email as hoi_vien_email',
            'trainer_user.ho_ten as pt_ten',
            'trainer_user.email as pt_email',
            'goi_pt.ten_goi_pt'
        );

    if ($keyword !== '') {

        $query->where(function ($q) use ($keyword) {

            $q->where(
                'member_user.ho_ten',
                'like',
                "%{$keyword}%"
            )
            ->orWhere(
                'member_user.email',
                'like',
                "%{$keyword}%"
            )
            ->orWhere(
                'trainer_user.ho_ten',
                'like',
                "%{$keyword}%"
            )
            ->orWhere(
                'trainer_user.email',
                'like',
                "%{$keyword}%"
            );

        });
    }

    if ($status !== '') {
        $query->where(
            'lich_pt.trang_thai',
            $status
        );
    }

    $schedules = $query
        ->orderByDesc('lich_pt.thoi_gian_bat_dau')
        ->get();

    return view(
        'admin.schedules',
        compact(
            'schedules',
            'keyword',
            'status'
        )
    );

});


/*
|--------------------------------------------------------------------------
| ADMIN - CHI TIẾT LỊCH TẬP
|--------------------------------------------------------------------------
*/

Route::get('/admin/schedules/{id}', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $schedule = DB::table('lich_pt')
        ->join(
            'hoi_vien',
            'lich_pt.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung as member_user',
            'hoi_vien.nguoi_dung_id',
            '=',
            'member_user.nguoi_dung_id'
        )
        ->join(
            'huan_luyen_vien',
            'lich_pt.pt_id',
            '=',
            'huan_luyen_vien.pt_id'
        )
        ->join(
            'nguoi_dung as trainer_user',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'trainer_user.nguoi_dung_id'
        )
        ->leftJoin(
            'dang_ky_goi_pt',
            'lich_pt.dang_ky_goi_pt_id',
            '=',
            'dang_ky_goi_pt.dang_ky_goi_pt_id'
        )
        ->leftJoin(
            'goi_pt',
            'dang_ky_goi_pt.goi_pt_id',
            '=',
            'goi_pt.goi_pt_id'
        )
        ->select(
            'lich_pt.*',

            'member_user.ho_ten as hoi_vien_ten',
            'member_user.email as hoi_vien_email',

            'trainer_user.ho_ten as pt_ten',
            'trainer_user.email as pt_email',

            'huan_luyen_vien.so_dien_thoai as pt_so_dien_thoai',
            'huan_luyen_vien.chuyen_mon',

            'goi_pt.ten_goi_pt',
            'goi_pt.gia as goi_pt_gia',
            'goi_pt.so_buoi as goi_pt_so_buoi',
            'goi_pt.thoi_han_ngay'
        )
        ->where(
            'lich_pt.lich_pt_id',
            $id
        )
        ->first();

    if (!$schedule) {
        return redirect('/admin/schedules')
            ->with('error', 'Không tìm thấy lịch tập.');
    }

    return view(
        'admin.schedule-detail',
        compact('schedule')
    );

});


// ==================== ADMIN - THANH TOÁN ====================

Route::get('/admin/payments', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $payments = DB::table('hoa_don')
        ->join('hoi_vien', 'hoa_don.hoi_vien_id', '=', 'hoi_vien.hoi_vien_id')
        ->join('nguoi_dung', 'hoi_vien.nguoi_dung_id', '=', 'nguoi_dung.nguoi_dung_id')
        ->select(
            'hoa_don.*',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email'
        )
        ->orderByDesc('hoa_don.hoa_don_id')
        ->get();

    return view('admin.payments', compact('payments'));
});


Route::get('/admin/payments/{id}', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $payment = DB::table('hoa_don')
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
        ->where('hoa_don.hoa_don_id', $id)
        ->select(
            'hoa_don.*',
            'hoi_vien.hoi_vien_id',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email'
        )
        ->first();

    if (!$payment) {
        abort(404);
    }

    $details = DB::table('chi_tiet_hoa_don')
        ->leftJoin(
            'goi_tap',
            'chi_tiet_hoa_don.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->leftJoin(
            'goi_pt',
            'chi_tiet_hoa_don.goi_pt_id',
            '=',
            'goi_pt.goi_pt_id'
        )
        ->where('chi_tiet_hoa_don.hoa_don_id', $id)
        ->select(
            'chi_tiet_hoa_don.*',
            'goi_tap.ten_goi',
            'goi_pt.ten_goi_pt'
        )
        ->get();

    return view('admin.payment-detail', compact(
        'payment',
        'details'
    ));
});


// ==================== ADMIN - THỐNG KÊ ====================

Route::get('/admin/statistics', function (Illuminate\Http\Request $request) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $from = $request->input('from');
    $to = $request->input('to');

    // Mặc định: toàn bộ dữ liệu
    $invoiceQuery = DB::table('hoa_don')
        ->where('trang_thai', 'đã thanh toán');

    $checkInQuery = DB::table('check_in');

    $scheduleQuery = DB::table('lich_pt');

    // Lọc theo thời gian
    if ($from) {
        $invoiceQuery->whereDate('ngay_lap', '>=', $from);
        $checkInQuery->whereDate('thoi_gian', '>=', $from);
        $scheduleQuery->whereDate('thoi_gian_bat_dau', '>=', $from);
    }

    if ($to) {
        $invoiceQuery->whereDate('ngay_lap', '<=', $to);
        $checkInQuery->whereDate('thoi_gian', '<=', $to);
        $scheduleQuery->whereDate('thoi_gian_bat_dau', '<=', $to);
    }

    // Doanh thu
    $revenue = $invoiceQuery->sum('tong_tien');

    // Số hóa đơn đã thanh toán
    $paidInvoices = $invoiceQuery->count();

    // Số check-in
    $checkIns = $checkInQuery->count();

    // Số lịch PT
    $ptSchedules = $scheduleQuery->count();

    // Tổng hội viên
    $members = DB::table('hoi_vien')->count();

    // Hội viên đang hoạt động
    $activeMembers = DB::table('hoi_vien')
        ->join(
            'dang_ky_goi_tap',
            'hoi_vien.hoi_vien_id',
            '=',
            'dang_ky_goi_tap.hoi_vien_id'
        )
        ->where('dang_ky_goi_tap.trang_thai', 'đang hoạt động')
        ->whereDate('dang_ky_goi_tap.ngay_ket_thuc', '>=', now()->toDateString())
        ->distinct()
        ->count('hoi_vien.hoi_vien_id');

    // Lịch PT hoàn thành
    $completedSchedules = $scheduleQuery
        ->clone()
        ->where('trang_thai', 'đã hoàn thành')
        ->count();

    // Lịch PT đã hủy
    $cancelledSchedules = $scheduleQuery
        ->clone()
        ->where('trang_thai', 'đã hủy')
        ->count();

    return view('admin.statistics', compact(
        'from',
        'to',
        'revenue',
        'paidInvoices',
        'checkIns',
        'ptSchedules',
        'members',
        'activeMembers',
        'completedSchedules',
        'cancelledSchedules'
    ));
});

/*
|--------------------------------------------------------------------------
| DASHBOARD NHÂN VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/staff/dashboard', function () {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ Nhân viên
    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    /*
    |--------------------------------------------------------------------------
    | THỐNG KÊ
    |--------------------------------------------------------------------------
    */

    $tongHoiVien = DB::table('hoi_vien')
        ->count();

    $tongCheckIn = DB::table('check_in')
        ->count();

    $tongHoaDon = DB::table('hoa_don')
        ->count();

    $tongDangKyGoi = DB::table('dang_ky_goi_tap')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | HỘI VIÊN GẦN ĐÂY
    |--------------------------------------------------------------------------
    */

    $hoiVienGanDay = DB::table('hoi_vien')
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->select(
            'hoi_vien.hoi_vien_id',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email'
        )
        ->orderByDesc('hoi_vien.hoi_vien_id')
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | CHECK-IN GẦN ĐÂY
    |--------------------------------------------------------------------------
    */

    $checkInGanDay = DB::table('check_in')
        ->join(
            'hoi_vien',
            'check_in.hoi_vien_id',
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
            'check_in.check_in_id',
            'nguoi_dung.ho_ten',
            'check_in.thoi_gian'
        )
        ->orderByDesc('check_in.thoi_gian')
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | ĐĂNG KÝ GÓI GẦN ĐÂY
    |--------------------------------------------------------------------------
    */

    $dangKyGoiGanDay = DB::table('dang_ky_goi_tap')
        ->join(
            'hoi_vien',
            'dang_ky_goi_tap.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->join(
            'goi_tap',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->select(
            'dang_ky_goi_tap.dang_ky_goi_tap_id',
            'nguoi_dung.ho_ten',
            'goi_tap.ten_goi',
            'dang_ky_goi_tap.trang_thai'
        )
        ->orderByDesc(
            'dang_ky_goi_tap.dang_ky_goi_tap_id'
        )
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | HÓA ĐƠN GẦN ĐÂY
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
            'hoa_don.hoa_don_id',
            'nguoi_dung.ho_ten',
            'hoa_don.tong_tien',
            'hoa_don.ngay_lap',
            'hoa_don.trang_thai'
        )
        ->orderByDesc('hoa_don.ngay_lap')
        ->limit(10)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | HIỂN THỊ DASHBOARD
    |--------------------------------------------------------------------------
    */

    return view(
        'staff.dashboard',
        compact(
            'tongHoiVien',
            'tongCheckIn',
            'tongHoaDon',
            'tongDangKyGoi',
            'hoiVienGanDay',
            'checkInGanDay',
            'dangKyGoiGanDay',
            'hoaDonGanDay'
        )
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| QUẢN LÝ HÓA ĐƠN - NHÂN VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/staff/payments', function () {

    // =========================================================
    // KIỂM TRA ĐĂNG NHẬP
    // =========================================================

    if (!session()->has('user')) {
        return redirect('/login');
    }


    // =========================================================
    // CHỈ NHÂN VIÊN
    // =========================================================

    if (session('role_id') != 2) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }


    // =========================================================
    // LẤY DANH SÁCH HÓA ĐƠN
    // =========================================================

    $hoaDon = DB::table('hoa_don')
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
            'hoa_don.*',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email'
        )
        ->orderByDesc('hoa_don.ngay_lap')
        ->get();


    // =========================================================
    // LẤY CHI TIẾT HÓA ĐƠN
    // =========================================================

    $chiTietHoaDon = collect();

    if ($hoaDon->count() > 0) {

        $hoaDonIds = $hoaDon->pluck('hoa_don_id');

        $chiTietHoaDon = DB::table('chi_tiet_hoa_don')

            ->leftJoin(
                'goi_tap',
                'chi_tiet_hoa_don.goi_tap_id',
                '=',
                'goi_tap.goi_tap_id'
            )

            ->leftJoin(
                'goi_pt',
                'chi_tiet_hoa_don.goi_pt_id',
                '=',
                'goi_pt.goi_pt_id'
            )

            ->whereIn(
                'chi_tiet_hoa_don.hoa_don_id',
                $hoaDonIds
            )

            ->select(
                'chi_tiet_hoa_don.*',
                'goi_tap.ten_goi',
                'goi_pt.ten_goi_pt'
            )

            ->get()

            ->groupBy('hoa_don_id');

    }


    // =========================================================
    // HIỂN THỊ
    // =========================================================

    return view(
        'staff.payments',
        compact(
            'hoaDon',
            'chiTietHoaDon'
        )
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| XÁC NHẬN THANH TOÁN
|--------------------------------------------------------------------------
*/

Route::post('/staff/payments/{id}/confirm', function ($id) {

    // =========================================================
    // KIỂM TRA ĐĂNG NHẬP
    // =========================================================

    if (!session()->has('user')) {
        return redirect('/login');
    }


    // =========================================================
    // CHỈ NHÂN VIÊN
    // =========================================================

    if (session('role_id') != 2) {

        return redirect(
            '/' . session('dashboard_path')
        );

    }


    // =========================================================
    // KIỂM TRA PHƯƠNG THỨC THANH TOÁN
    // =========================================================

    $phuongThuc = request('phuong_thuc_thanh_toan');

    if (!in_array(
        $phuongThuc,
        [
            'Tiền mặt',
            'Chuyển khoản',
            'Thẻ'
        ]
    )) {

        return back()
            ->with(
                'error',
                'Vui lòng chọn phương thức thanh toán hợp lệ.'
            );

    }


    // =========================================================
    // TÌM HÓA ĐƠN
    // =========================================================

    $hoaDon = DB::table('hoa_don')
        ->where(
            'hoa_don_id',
            $id
        )
        ->first();


    if (!$hoaDon) {

        return back()
            ->with(
                'error',
                'Không tìm thấy hóa đơn.'
            );

    }


    // =========================================================
    // CHỈ XÁC NHẬN HÓA ĐƠN ĐANG CHỜ THANH TOÁN
    // =========================================================

    $trangThai = strtolower(
        trim($hoaDon->trang_thai)
    );

    if (!in_array(
        $trangThai,
        [
            'chờ thanh toán',
            'cho thanh toan',
            'cho_thanh_toan',
            'pending'
        ]
    )) {

        return back()
            ->with(
                'error',
                'Hóa đơn này đã được xử lý trước đó.'
            );

    }


    // =========================================================
    // TÌM NHÂN VIÊN ĐANG ĐĂNG NHẬP
    // =========================================================

    $nguoiDungId = session('user')->nguoi_dung_id;

    $nhanVien = DB::table('nhan_vien')
        ->where(
            'nguoi_dung_id',
            $nguoiDungId
        )
        ->first();


    if (!$nhanVien) {

        return back()
            ->with(
                'error',
                'Không tìm thấy hồ sơ nhân viên.'
            );

    }


    // =========================================================
    // CẬP NHẬT THANH TOÁN
    // =========================================================

    try {

        DB::transaction(function () use (
            $id,
            $hoaDon,
            $phuongThuc,
            $nhanVien
        ) {

            // -------------------------------------------------
            // 1. CẬP NHẬT HÓA ĐƠN
            // -------------------------------------------------

            DB::table('hoa_don')
                ->where(
                    'hoa_don_id',
                    $id
                )
                ->update([

                    'nhan_vien_id' =>
                        $nhanVien->nhan_vien_id,

                    'trang_thai' =>
                        'đã thanh toán',

                    'phuong_thuc_thanh_toan' =>
                        $phuongThuc,

                ]);


            // -------------------------------------------------
            // 2. TẠO THÔNG BÁO CHO HỘI VIÊN
            // -------------------------------------------------

            DB::table('thong_bao')
                ->insert([

                    'nguoi_dung_id' =>
                        DB::table('hoi_vien')
                            ->where(
                                'hoi_vien_id',
                                $hoaDon->hoi_vien_id
                            )
                            ->value(
                                'nguoi_dung_id'
                            ),

                    'tieu_de' =>
                        'Thanh toán thành công',

                    'noi_dung' =>
                        'Hóa đơn #HD'
                        . str_pad(
                            $id,
                            5,
                            '0',
                            STR_PAD_LEFT
                        )
                        . ' đã được xác nhận thanh toán bằng '
                        . $phuongThuc
                        . '.',

                    'da_doc' =>
                        0,

                    'tao_luc' =>
                        now(),

                ]);

        });


        // =====================================================
        // THÀNH CÔNG
        // =====================================================

        return redirect('/staff/payments')
            ->with(
                'success',
                'Xác nhận thanh toán thành công!'
            );


    } catch (\Exception $e) {

        return back()
            ->with(
                'error',
                'Không thể xác nhận thanh toán: '
                . $e->getMessage()
            );

    }

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| LỊCH PT CỦA HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/user/pt-schedule', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 3) {
        return redirect('/' . session('dashboard_path'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return view('user.pt-schedule', [
            'lichPT' => collect()
        ]);
    }

    $lichPT = DB::table('lich_pt')
        ->join(
            'dang_ky_goi_pt',
            'lich_pt.dang_ky_goi_pt_id',
            '=',
            'dang_ky_goi_pt.dang_ky_goi_pt_id'
        )
        ->join(
            'goi_pt',
            'dang_ky_goi_pt.goi_pt_id',
            '=',
            'goi_pt.goi_pt_id'
        )
        ->join(
            'huan_luyen_vien',
            'lich_pt.pt_id',
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
            'lich_pt.hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->select(
            'lich_pt.*',
            'goi_pt.ten_goi_pt',
            'nguoi_dung.ho_ten as ten_pt'
        )
        ->orderBy('lich_pt.thoi_gian_bat_dau', 'asc')
        ->get();
        

    return view(
        'user.pt-schedule',
        compact('lichPT')
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| HỦY LỊCH PT
|--------------------------------------------------------------------------
*/

Route::post('/user/pt-schedule/{id}/cancel', function ($id) {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên mới được hủy lịch
    if (session('role_id') != 3) {
        return redirect('/' . session('dashboard_path'));
    }

    // ID người dùng hiện tại
    $nguoiDungId = session('user')->nguoi_dung_id;

    // Tìm hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return redirect('/user/pt-schedule')
            ->with(
                'error',
                'Không tìm thấy hồ sơ hội viên.'
            );
    }

    // Tìm lịch PT
    // Đồng thời đảm bảo lịch này thuộc đúng hội viên
    $lich = DB::table('lich_pt')
        ->where('lich_pt_id', $id)
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->first();

    if (!$lich) {
        return redirect('/user/pt-schedule')
            ->with(
                'error',
                'Không tìm thấy lịch PT.'
            );
    }

    // Chuẩn hóa trạng thái
    $trangThai = mb_strtolower(
        $lich->trang_thai ?? ''
    );

    // Chỉ lịch "đã đặt" mới được hủy
    if (
        !str_contains($trangThai, 'đặt') &&
        !str_contains($trangThai, 'dat')
    ) {

        return redirect('/user/pt-schedule')
            ->with(
                'error',
                'Lịch này không thể hủy.'
            );
    }

    // Thời gian bắt đầu của lịch
    $batDau = \Carbon\Carbon::parse(
        $lich->thoi_gian_bat_dau
    );

    // Không cho hủy lịch đã bắt đầu
    if (!$batDau->isFuture()) {

        return redirect('/user/pt-schedule')
            ->with(
                'error',
                'Không thể hủy lịch đã bắt đầu hoặc đã qua.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HỦY LỊCH
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        // Cập nhật trạng thái lịch
        DB::table('lich_pt')
            ->where('lich_pt_id', $id)
            ->update([
                'trang_thai' => 'đã hủy'
            ]);

        /*
        |--------------------------------------------------------------------------
        | TẠO THÔNG BÁO
        |--------------------------------------------------------------------------
        */

        DB::table('thong_bao')->insert([
            'nguoi_dung_id' => $nguoiDungId,

            'tieu_de' => 'Hủy lịch PT',

            'noi_dung' =>
                'Bạn đã hủy lịch PT vào ngày ' .
                $batDau->format('d/m/Y H:i') .
                '.',

            'da_doc' => false,

            'tao_luc' => now(),
        ]);

        DB::commit();

        return redirect('/user/pt-schedule')
            ->with(
                'success',
                'Hủy lịch PT thành công!'
            );

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect('/user/pt-schedule')
            ->with(
                'error',
                'Không thể hủy lịch PT.'
            );
    }

})->middleware(NoCache::class);

/*
|--------------------------------------------------------------------------
| ĐẶT LỊCH PT - HIỂN THỊ FORM
|--------------------------------------------------------------------------
*/

Route::get('/user/pt-schedule/create', function () {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên
    if (session('role_id') != 3) {
        return redirect('/' . session('dashboard_path'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    // Tìm hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return redirect('/user/pt-schedule')
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    // Lấy các gói PT đang hoạt động và còn buổi
    $goiPT = DB::table('dang_ky_goi_pt')
        ->join(
            'goi_pt',
            'dang_ky_goi_pt.goi_pt_id',
            '=',
            'goi_pt.goi_pt_id'
        )
        ->leftJoin(
            'huan_luyen_vien',
            'dang_ky_goi_pt.pt_id',
            '=',
            'huan_luyen_vien.pt_id'
        )
        ->leftJoin(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where(
            'dang_ky_goi_pt.hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->whereIn(
            'dang_ky_goi_pt.trang_thai',
            [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ]
        )
        ->where(
            'dang_ky_goi_pt.so_buoi_con_lai',
            '>',
            0
        )
        ->whereDate(
            'dang_ky_goi_pt.ngay_ket_thuc',
            '>=',
            now()->toDateString()
        )
        ->select(
            'dang_ky_goi_pt.dang_ky_goi_pt_id',
            'dang_ky_goi_pt.goi_pt_id',
            'dang_ky_goi_pt.pt_id',
            'dang_ky_goi_pt.ngay_bat_dau',
            'dang_ky_goi_pt.ngay_ket_thuc',
            'dang_ky_goi_pt.so_buoi_con_lai',
            'goi_pt.ten_goi_pt',
            'goi_pt.so_buoi',
            'nguoi_dung.ho_ten as ten_pt'
        )
        ->orderByDesc('dang_ky_goi_pt.dang_ky_goi_pt_id')
        ->get();

    return view(
        'user.pt-booking',
        compact('goiPT')
    );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| ĐẶT LỊCH PT - XỬ LÝ
|--------------------------------------------------------------------------
*/

Route::post('/user/pt-schedule/create', function () {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên
    if (session('role_id') != 3) {
        return redirect('/' . session('dashboard_path'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    // Tìm hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return back()
            ->withInput()
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    // Validate dữ liệu
    $validated = request()->validate([

        'dang_ky_goi_pt_id' => [
            'required',
            'integer'
        ],

        'thoi_gian_bat_dau' => [
            'required',
            'date',
            'after_or_equal:now'
        ],

        'thoi_gian_ket_thuc' => [
            'required',
            'date',
            'after:thoi_gian_bat_dau'
        ],

    ], [

        'dang_ky_goi_pt_id.required' =>
            'Vui lòng chọn gói PT.',

        'thoi_gian_bat_dau.required' =>
            'Vui lòng chọn thời gian bắt đầu.',

        'thoi_gian_bat_dau.after_or_equal' =>
            'Thời gian bắt đầu không được ở trong quá khứ.',

        'thoi_gian_ket_thuc.required' =>
            'Vui lòng chọn thời gian kết thúc.',

        'thoi_gian_ket_thuc.after' =>
            'Thời gian kết thúc phải sau thời gian bắt đầu.',

    ]);

    /*
    |--------------------------------------------------------------------------
    | Tìm đăng ký gói PT
    |--------------------------------------------------------------------------
    */

    $dangKy = DB::table('dang_ky_goi_pt')
        ->join(
            'goi_pt',
            'dang_ky_goi_pt.goi_pt_id',
            '=',
            'goi_pt.goi_pt_id'
        )
        ->where(
            'dang_ky_goi_pt.dang_ky_goi_pt_id',
            $validated['dang_ky_goi_pt_id']
        )
        ->where(
            'dang_ky_goi_pt.hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->select(
            'dang_ky_goi_pt.*',
            'goi_pt.ten_goi_pt'
        )
        ->first();

    if (!$dangKy) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Gói PT không hợp lệ hoặc không thuộc tài khoản của bạn.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra trạng thái gói
    |--------------------------------------------------------------------------
    */

    if (
        !in_array(
            $dangKy->trang_thai,
            [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ]
        )
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Gói PT này không còn hoạt động.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra số buổi
    |--------------------------------------------------------------------------
    */

    if ($dangKy->so_buoi_con_lai <= 0) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Gói PT này đã hết số buổi.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra thời hạn gói
    |--------------------------------------------------------------------------
    */

    $batDau = \Carbon\Carbon::parse(
        $validated['thoi_gian_bat_dau']
    );

    $ketThuc = \Carbon\Carbon::parse(
        $validated['thoi_gian_ket_thuc']
    );

    $ngayKetThucGoi = \Carbon\Carbon::parse(
        $dangKy->ngay_ket_thuc
    )->endOfDay();

    if ($batDau->lt(
        \Carbon\Carbon::parse($dangKy->ngay_bat_dau)->startOfDay()
    )) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Thời gian tập không được trước ngày bắt đầu gói PT.'
            );

    }

    if ($ketThuc->gt($ngayKetThucGoi)) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Thời gian tập vượt quá thời hạn của gói PT.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra PT có lịch bị trùng
    |--------------------------------------------------------------------------
    */

    $lichPTBiTrung = DB::table('lich_pt')
        ->where(
            'pt_id',
            $dangKy->pt_id
        )
        ->whereNotIn(
            'trang_thai',
            [
                'đã hủy',
                'da_huy',
                'hủy',
                'huy'
            ]
        )
        ->where(
            'thoi_gian_bat_dau',
            '<',
            $validated['thoi_gian_ket_thuc']
        )
        ->where(
            'thoi_gian_ket_thuc',
            '>',
            $validated['thoi_gian_bat_dau']
        )
        ->exists();

    if ($lichPTBiTrung) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Huấn luyện viên đã có lịch trong khoảng thời gian này. Vui lòng chọn thời gian khác.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra hội viên có lịch bị trùng
    |--------------------------------------------------------------------------
    */

    $lichHoiVienBiTrung = DB::table('lich_pt')
        ->where(
            'hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->whereNotIn(
            'trang_thai',
            [
                'đã hủy',
                'da_huy',
                'hủy',
                'huy'
            ]
        )
        ->where(
            'thoi_gian_bat_dau',
            '<',
            $validated['thoi_gian_ket_thuc']
        )
        ->where(
            'thoi_gian_ket_thuc',
            '>',
            $validated['thoi_gian_bat_dau']
        )
        ->exists();

    if ($lichHoiVienBiTrung) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Bạn đã có một lịch PT khác trong khoảng thời gian này.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Tạo lịch PT
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        DB::table('lich_pt')->insert([

            'dang_ky_goi_pt_id' =>
                $dangKy->dang_ky_goi_pt_id,

            'hoi_vien_id' =>
                $hoiVien->hoi_vien_id,

            'pt_id' =>
                $dangKy->pt_id,

            'thoi_gian_bat_dau' =>
                $validated['thoi_gian_bat_dau'],

            'thoi_gian_ket_thuc' =>
                $validated['thoi_gian_ket_thuc'],

            'trang_thai' =>
                'đã đặt lịch',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Tạo thông báo
        |--------------------------------------------------------------------------
        */

        DB::table('thong_bao')->insert([

            'nguoi_dung_id' =>
                $nguoiDungId,

            'tieu_de' =>
                'Đặt lịch PT thành công',

            'noi_dung' =>
                'Bạn đã đặt lịch cho gói ' .
                $dangKy->ten_goi_pt .
                ' từ ' .
                $batDau->format('d/m/Y H:i') .
                ' đến ' .
                $ketThuc->format('H:i') .
                '.',

            'da_doc' =>
                false,

            'tao_luc' =>
                now(),

        ]);

        DB::commit();

        return redirect('/user/pt-schedule')
            ->with(
                'success',
                'Đặt lịch PT thành công!'
            );

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                'Không thể đặt lịch: ' .
                $e->getMessage()
            );

    }

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
| CHECK-IN NHÂN VIÊN - FORM
|--------------------------------------------------------------------------
*/

Route::get('/staff/check-in', function () {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ Nhân viên
    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }


    /*
    |--------------------------------------------------------------------------
    | Hội viên có gói đang hoạt động
    |--------------------------------------------------------------------------
    */

    $hoiVien = DB::table('dang_ky_goi_tap')
        ->join(
            'hoi_vien',
            'dang_ky_goi_tap.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->whereIn(
            'dang_ky_goi_tap.trang_thai',
            [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ]
        )
        ->whereDate(
            'dang_ky_goi_tap.ngay_bat_dau',
            '<=',
            now()->toDateString()
        )
        ->whereDate(
            'dang_ky_goi_tap.ngay_ket_thuc',
            '>=',
            now()->toDateString()
        )
        ->where(
            'dang_ky_goi_tap.so_buoi_con_lai',
            '>',
            0
        )
        ->select(
            'hoi_vien.hoi_vien_id',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email'
        )
        ->distinct()
        ->orderBy('nguoi_dung.ho_ten')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Gói tập đang hoạt động
    |--------------------------------------------------------------------------
    */

    $goiTap = DB::table('dang_ky_goi_tap')
        ->join(
            'hoi_vien',
            'dang_ky_goi_tap.hoi_vien_id',
            '=',
            'hoi_vien.hoi_vien_id'
        )
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->join(
            'goi_tap',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->whereIn(
            'dang_ky_goi_tap.trang_thai',
            [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ]
        )
        ->whereDate(
            'dang_ky_goi_tap.ngay_bat_dau',
            '<=',
            now()->toDateString()
        )
        ->whereDate(
            'dang_ky_goi_tap.ngay_ket_thuc',
            '>=',
            now()->toDateString()
        )
        ->where(
            'dang_ky_goi_tap.so_buoi_con_lai',
            '>',
            0
        )
        ->select(
            'dang_ky_goi_tap.dang_ky_goi_tap_id',
            'dang_ky_goi_tap.hoi_vien_id',
            'dang_ky_goi_tap.so_buoi_con_lai',
            'nguoi_dung.ho_ten',
            'goi_tap.ten_goi'
        )
        ->orderBy('nguoi_dung.ho_ten')
        ->get();


    return view(
        'staff.check-in',
        compact(
            'hoiVien',
            'goiTap'
        )
    );

})->middleware(NoCache::class);

/*
|--------------------------------------------------------------------------
| CHECK-IN NHÂN VIÊN - XỬ LÝ
|--------------------------------------------------------------------------
*/

Route::post('/staff/check-in', function () {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ Nhân viên
    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }


    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    $validated = request()->validate([

        'hoi_vien_id' => [
            'required',
            'integer'
        ],

        'dang_ky_goi_tap_id' => [
            'required',
            'integer'
        ],

    ], [

        'hoi_vien_id.required' =>
            'Vui lòng chọn hội viên.',

        'dang_ky_goi_tap_id.required' =>
            'Vui lòng chọn gói tập.',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra gói có thuộc hội viên
    |--------------------------------------------------------------------------
    */

    $goiTap = DB::table('dang_ky_goi_tap')
        ->where(
            'dang_ky_goi_tap_id',
            $validated['dang_ky_goi_tap_id']
        )
        ->where(
            'hoi_vien_id',
            $validated['hoi_vien_id']
        )
        ->first();


    if (!$goiTap) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Gói tập không thuộc hội viên đã chọn.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra trạng thái gói
    |--------------------------------------------------------------------------
    */

    $trangThai = mb_strtolower(
        $goiTap->trang_thai ?? ''
    );


    if (
        !in_array(
            $trangThai,
            [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ],
            true
        )
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Gói tập của hội viên không còn hoạt động.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra thời hạn
    |--------------------------------------------------------------------------
    */

    $homNay = now()->toDateString();


    if (
        $goiTap->ngay_bat_dau > $homNay ||
        $goiTap->ngay_ket_thuc < $homNay
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Gói tập của hội viên đã hết hạn hoặc chưa bắt đầu.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Kiểm tra số buổi
    |--------------------------------------------------------------------------
    */

    if ((int) $goiTap->so_buoi_con_lai <= 0) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Hội viên đã hết số buổi của gói tập.'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Người thực hiện
    |--------------------------------------------------------------------------
    */

    $nguoiThucHienId =
        session('user')->nguoi_dung_id;


    /*
    |--------------------------------------------------------------------------
    | Lưu Check-in
    |--------------------------------------------------------------------------
    */

    DB::table('check_in')->insert([

        'hoi_vien_id' =>
            $validated['hoi_vien_id'],

        'dang_ky_goi_tap_id' =>
            $validated['dang_ky_goi_tap_id'],

        'thoi_gian' =>
            now(),

        'nguoi_thuc_hien_id' =>
            $nguoiThucHienId,

    ]);


    /*
    |--------------------------------------------------------------------------
    | Thông báo
    |--------------------------------------------------------------------------
    */

    $hoiVien = DB::table('hoi_vien')
        ->where(
            'hoi_vien_id',
            $validated['hoi_vien_id']
        )
        ->first();


    if ($hoiVien) {

        DB::table('thong_bao')->insert([

            'nguoi_dung_id' =>
                $hoiVien->nguoi_dung_id,

            'tieu_de' =>
                'Check-in thành công',

            'noi_dung' =>
                'Bạn đã check-in Gym lúc ' .
                now()->format('H:i d/m/Y') .
                '.',

            'da_doc' =>
                false,

            'tao_luc' =>
                now(),

        ]);

    }


    return redirect('/staff/check-in')
        ->with(
            'success',
            'Check-in hội viên thành công!'
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
    ->join(
        'dang_ky_goi_pt',
        'lich_pt.dang_ky_goi_pt_id',
        '=',
        'dang_ky_goi_pt.dang_ky_goi_pt_id'
    )
    ->join(
        'goi_pt',
        'dang_ky_goi_pt.goi_pt_id',
        '=',
        'goi_pt.goi_pt_id'
    )
    ->where(
        'lich_pt.pt_id',
        $pt->pt_id
    )
    ->select(
        'lich_pt.lich_pt_id',
        'lich_pt.hoi_vien_id',
        'lich_pt.dang_ky_goi_pt_id',
        'nguoi_dung.ho_ten',
        'goi_pt.ten_goi_pt',
        'dang_ky_goi_pt.so_buoi_con_lai',
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
| HỌC VIÊN CỦA PT
|--------------------------------------------------------------------------
*/

$hocVien = DB::table('dang_ky_goi_pt')
    ->join(
        'hoi_vien',
        'dang_ky_goi_pt.hoi_vien_id',
        '=',
        'hoi_vien.hoi_vien_id'
    )
    ->join(
        'nguoi_dung',
        'hoi_vien.nguoi_dung_id',
        '=',
        'nguoi_dung.nguoi_dung_id'
    )
    ->join(
        'goi_pt',
        'dang_ky_goi_pt.goi_pt_id',
        '=',
        'goi_pt.goi_pt_id'
    )
    ->where(
        'dang_ky_goi_pt.pt_id',
        $pt->pt_id
    )
    ->select(
        'hoi_vien.hoi_vien_id',
        'nguoi_dung.ho_ten',
        'nguoi_dung.email',
        'goi_pt.ten_goi_pt',
        'dang_ky_goi_pt.so_buoi_con_lai',
        'dang_ky_goi_pt.trang_thai'
    )
    ->orderByDesc(
        'dang_ky_goi_pt.dang_ky_goi_pt_id'
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
            'lichPT',
            'hocVien'
        )
    );

})->middleware(NoCache::class);

/*
|--------------------------------------------------------------------------
| TRAINER HOÀN THÀNH BUỔI PT
|--------------------------------------------------------------------------
*/

Route::post('/trainer/pt-schedule/{id}/complete', function ($id) {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ Trainer
    if (session('role_id') != 4) {
        return redirect('/' . session('dashboard_path'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    // Tìm Trainer hiện tại
    $pt = DB::table('huan_luyen_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$pt) {
        return redirect('/trainer/dashboard')
            ->with(
                'error',
                'Không tìm thấy hồ sơ huấn luyện viên.'
            );
    }

    // Tìm lịch và đảm bảo lịch thuộc đúng Trainer
    $lich = DB::table('lich_pt')
        ->where('lich_pt_id', $id)
        ->where('pt_id', $pt->pt_id)
        ->first();

    if (!$lich) {
        return redirect('/trainer/dashboard')
            ->with(
                'error',
                'Không tìm thấy lịch PT.'
            );
    }

    // Chuẩn hóa trạng thái
    $trangThai = mb_strtolower(
        $lich->trang_thai ?? ''
    );

    // Chỉ lịch đã đặt mới được hoàn thành
    if (
        !str_contains($trangThai, 'đặt') &&
        !str_contains($trangThai, 'dat')
    ) {
        return redirect('/trainer/dashboard')
            ->with(
                'error',
                'Lịch này không thể hoàn thành.'
            );
    }

    // Thời gian kết thúc buổi tập
    $ketThuc = \Carbon\Carbon::parse(
        $lich->thoi_gian_ket_thuc
    );

    // Chưa kết thúc buổi tập thì chưa được xác nhận hoàn thành
    if ($ketThuc->isFuture()) {

        return redirect('/trainer/dashboard')
            ->with(
                'error',
                'Buổi tập chưa kết thúc nên chưa thể xác nhận hoàn thành.'
            );
    }

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Lấy đăng ký gói PT
        |--------------------------------------------------------------------------
        */

        $dangKy = DB::table('dang_ky_goi_pt')
            ->where(
                'dang_ky_goi_pt_id',
                $lich->dang_ky_goi_pt_id
            )
            ->lockForUpdate()
            ->first();

        if (!$dangKy) {

            DB::rollBack();

            return redirect('/trainer/dashboard')
                ->with(
                    'error',
                    'Không tìm thấy gói PT của hội viên.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra số buổi còn lại
        |--------------------------------------------------------------------------
        */

        if ((int) $dangKy->so_buoi_con_lai <= 0) {

            DB::rollBack();

            return redirect('/trainer/dashboard')
                ->with(
                    'error',
                    'Hội viên đã hết số buổi PT.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Đánh dấu hoàn thành
        |--------------------------------------------------------------------------
        */

        DB::table('lich_pt')
            ->where('lich_pt_id', $id)
            ->update([
                'trang_thai' => 'đã hoàn thành'
            ]);

        /*
        |--------------------------------------------------------------------------
        | Trừ 1 buổi PT
        |--------------------------------------------------------------------------
        */

        DB::table('dang_ky_goi_pt')
            ->where(
                'dang_ky_goi_pt_id',
                $lich->dang_ky_goi_pt_id
            )
            ->update([
                'so_buoi_con_lai' =>
                    DB::raw('so_buoi_con_lai - 1')
            ]);

        /*
        |--------------------------------------------------------------------------
        | Thông báo cho hội viên
        |--------------------------------------------------------------------------
        */

        $hoiVien = DB::table('hoi_vien')
            ->where(
                'hoi_vien_id',
                $lich->hoi_vien_id
            )
            ->first();

        if ($hoiVien) {

            DB::table('thong_bao')->insert([
                'nguoi_dung_id' => $hoiVien->nguoi_dung_id,

                'tieu_de' => 'Buổi PT đã hoàn thành',

                'noi_dung' =>
                    'Buổi tập PT ngày ' .
                    \Carbon\Carbon::parse(
                        $lich->thoi_gian_bat_dau
                    )->format('d/m/Y H:i') .
                    ' đã được xác nhận hoàn thành. ' .
                    'Số buổi PT còn lại đã giảm 1 buổi.',

                'da_doc' => false,

                'tao_luc' => now(),
            ]);
        }

        DB::commit();

        return redirect('/trainer/dashboard')
            ->with(
                'success',
                'Xác nhận hoàn thành buổi PT thành công!'
            );

    } catch (\Exception $e) {

        DB::rollBack();

        return redirect('/trainer/dashboard')
            ->with(
                'error',
                'Không thể hoàn thành buổi PT: ' .
                $e->getMessage()
            );
    }

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| THANH TOÁN / HÓA ĐƠN HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/user/payments', function () {

    // Chưa đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ hội viên
    if (session('role_id') != 3) {
        return redirect('/' . session('dashboard_path'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    // Tìm hội viên
    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return redirect('/user/dashboard')
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    // Lấy hóa đơn
    $hoaDon = DB::table('hoa_don')
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->orderByDesc('ngay_lap')
        ->get();

    // Lấy chi tiết hóa đơn
    $chiTietHoaDon = collect();

    if ($hoaDon->count() > 0) {

        $hoaDonIds = $hoaDon->pluck('hoa_don_id');

        $chiTietHoaDon = DB::table('chi_tiet_hoa_don')
            ->leftJoin(
                'goi_tap',
                'chi_tiet_hoa_don.goi_tap_id',
                '=',
                'goi_tap.goi_tap_id'
            )
            ->leftJoin(
                'goi_pt',
                'chi_tiet_hoa_don.goi_pt_id',
                '=',
                'goi_pt.goi_pt_id'
            )
            ->whereIn(
                'chi_tiet_hoa_don.hoa_don_id',
                $hoaDonIds
            )
            ->select(
                'chi_tiet_hoa_don.*',
                'goi_tap.ten_goi',
                'goi_pt.ten_goi_pt'
            )
            ->get()
            ->groupBy('hoa_don_id');
    }

    return view(
        'user.payments',
        compact(
            'hoaDon',
            'chiTietHoaDon'
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

