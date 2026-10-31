<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\NoCache;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| XỬ LÝ VÒNG ĐỜI GÓI TẬP VÀ GÓI PT
|--------------------------------------------------------------------------
| - Hóa đơn chờ thanh toán quá 72 giờ: đăng ký chuyển sang "đã hủy".
| - Hóa đơn được giữ lại trong lịch sử với trạng thái "đã hủy".
| - Gói đã thanh toán và hết hạn được chuyển sang "đã hết hạn".
| - Dùng chung PackageLifecycleService để trạng thái nhất quán trên các trang.
|--------------------------------------------------------------------------
*/

$xuLyGoiTapCuaHoiVien = function ($hoiVienId) {
    // Dùng chung một service cho gói tập và gói PT để mọi trang
    // hiển thị cùng một trạng thái, không còn hai luồng xử lý khác nhau.
    $lifecycle = app(\App\Services\PackageLifecycleService::class);
    $lifecycle->xuLyGoiTapCuaHoiVien($hoiVienId);
    $lifecycle->xuLyGoiPtCuaHoiVien($hoiVienId);
};


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
        ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
        ->orderBy('gia', 'asc')
        ->get();

    return view('user.packages', compact('goiTap'));

});

Route::post('/packages/register/{goiTapId}', function ($goiTapId) use ($xuLyGoiTapCuaHoiVien) {

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


    /*
    |--------------------------------------------------------------------------
    | DỌN GÓI HẾT HẠN / HÓA ĐƠN QUÁ 72 GIỜ
    |--------------------------------------------------------------------------
    */


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
        ->whereDate(
            'ngay_ket_thuc',
            '>=',
            now()->toDateString()
        )
        ->exists();


    if ($dangKyDangHoatDong) {

        return redirect('/packages')
            ->with(
                'error',
                'Bạn đang có gói tập còn hiệu lực. Khi gói hiện tại hết hạn, bạn mới có thể đăng ký gói mới.'
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
                        'không hoạt động',

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
        ->where(function ($query) {
            $query->where('trang_thai', 'like', '%hoat_dong%')
                  ->orWhere('trang_thai', 'like', '%hoạt_động%');
        })
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
        ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
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
        ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
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
            ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
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

// =====================================================
// PUBLIC - DANH SÁCH HUẤN LUYỆN VIÊN
// =====================================================

Route::get('/trainers', function () {

    $today = now()->toDateString();

    $trainers = DB::table('huan_luyen_vien')
        ->join(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )

        // Đếm số học viên đang đăng ký PT
        ->leftJoinSub(
            DB::table('dang_ky_goi_pt')
                ->select(
                    'pt_id',
                    DB::raw('COUNT(*) as so_hoc_vien')
                )
                ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
                ->whereDate('ngay_ket_thuc', '>=', $today)
                ->groupBy('pt_id'),
            'pt_hoc_vien',
            'huan_luyen_vien.pt_id',
            '=',
            'pt_hoc_vien.pt_id'
        )

        // Đếm số lịch PT
        ->leftJoinSub(
            DB::table('lich_pt')
                ->select(
                    'pt_id',
                    DB::raw('COUNT(*) as so_lich')
                )
                ->whereNotIn('trang_thai', [
                    'đã hủy',
                    'đã huỷ',
                    'da_huy'
                ])
                ->groupBy('pt_id'),
            'pt_lich',
            'huan_luyen_vien.pt_id',
            '=',
            'pt_lich.pt_id'
        )

        ->where('nguoi_dung.vai_tro_id', 4)

        // Không hiển thị PT bị khóa
        ->where('nguoi_dung.trang_thai', '!=', 'bi_khoa')

        ->select(
            'huan_luyen_vien.pt_id',
            'huan_luyen_vien.nguoi_dung_id',
            'huan_luyen_vien.chuyen_mon',
            'huan_luyen_vien.thanh_tich',
            'huan_luyen_vien.so_dien_thoai',
            'huan_luyen_vien.anh',

            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai',

            DB::raw('COALESCE(pt_hoc_vien.so_hoc_vien, 0) as so_hoc_vien'),
            DB::raw('COALESCE(pt_lich.so_lich, 0) as so_lich')
        )

        ->orderBy('nguoi_dung.ho_ten')
        ->get();

    // QUAN TRỌNG:
    // File thật của mày nằm trong resources/views/user/
    return view('user.trainers', compact('trainers'));
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

    // Danh sách PT đang hoạt động để hội viên tự lựa chọn.
    $ptList = DB::table('huan_luyen_vien')
        ->join(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('nguoi_dung.vai_tro_id', 4)
        ->whereIn('nguoi_dung.trang_thai', [
            'hoat_dong', 'hoạt_động', 'hoạt động', 'dang_hoat_dong', 'đang hoạt động'
        ])
        ->select(
            'huan_luyen_vien.pt_id',
            'huan_luyen_vien.chuyen_mon',
            'huan_luyen_vien.anh',
            'nguoi_dung.ho_ten'
        )
        ->orderBy('nguoi_dung.ho_ten')
        ->get();

    return view('user.pt-packages', compact('goiPt', 'ptList'));
});


/*
|--------------------------------------------------------------------------
| ĐĂNG KÝ GÓI PT
|--------------------------------------------------------------------------
*/

// Route đăng ký gói PT được khai báo một lần ở phần xử lý đăng ký/hủy gói PT bên dưới.




/* --------------------------------------------------------------------------
 | ADMIN - QUẢN LÝ / XỬ LÝ LIÊN HỆ
 | Dùng đúng bảng lien_he và cấu trúc xử lý đang có của nhân viên.
 |-------------------------------------------------------------------------- */
Route::get('/admin/contacts', function () {
    if (!session()->has('user')) return redirect('/login');
    if ((int) session('role_id') !== 1) return redirect('/' . ltrim(session('dashboard_path', 'user/dashboard'), '/'));

    $lienHe = DB::table('lien_he')
        ->leftJoin('nhan_vien', 'lien_he.nhan_vien_id', '=', 'nhan_vien.nhan_vien_id')
        ->leftJoin('nguoi_dung as nd_nv', 'nhan_vien.nguoi_dung_id', '=', 'nd_nv.nguoi_dung_id')
        ->select('lien_he.*', 'nd_nv.ho_ten as ten_nhan_vien')
        ->orderByDesc('lien_he.tao_luc')
        ->get();

    return view('admin.contacts', compact('lienHe'));
})->middleware(NoCache::class);

Route::post('/admin/contacts/{id}/update', function (Request $request, $id) {
    if (!session()->has('user')) return redirect('/login');
    if ((int) session('role_id') !== 1) return redirect('/' . ltrim(session('dashboard_path', 'user/dashboard'), '/'));

    $request->validate([
        'trang_thai' => 'required|in:chưa xử lý,đang xử lý,đã xử lý',
        'phan_hoi' => 'nullable|string|max:2000',
    ], [
        'trang_thai.required' => 'Vui lòng chọn trạng thái xử lý.',
        'trang_thai.in' => 'Trạng thái xử lý không hợp lệ.',
        'phan_hoi.max' => 'Phản hồi không được vượt quá 2000 ký tự.',
    ]);

    $contactExists = DB::table('lien_he')->where('lien_he_id', $id)->exists();
    if (!$contactExists) {
        return redirect('/admin/contacts')->with('error', 'Không tìm thấy yêu cầu liên hệ.');
    }

    $nhanVien = DB::table('nhan_vien')
        ->where('nguoi_dung_id', session('user')->nguoi_dung_id)
        ->first();

    $duLieu = [
        'trang_thai' => $request->trang_thai,
        'phan_hoi' => $request->phan_hoi,
    ];
    if ($request->trang_thai === 'chưa xử lý') {
        $duLieu['xu_ly_luc'] = null;
    } elseif ($nhanVien) {
        $duLieu['nhan_vien_id'] = $nhanVien->nhan_vien_id;
        $duLieu['xu_ly_luc'] = now();
    } else {
        $duLieu['xu_ly_luc'] = now();
    }

    DB::table('lien_he')->where('lien_he_id', $id)->update($duLieu);
    return redirect('/admin/contacts')->with('success', 'Đã cập nhật xử lý liên hệ.');
})->middleware(NoCache::class);

/*
|--------------------------------------------------------------------------
| LIÊN HỆ
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| LIÊN HỆ
|--------------------------------------------------------------------------
*/

Route::get('/contact', function () {
    $settings = DB::table('cai_dat')->pluck('gia_tri', 'khoa');

    return view('user.contact', compact('settings'));
});


/*
|--------------------------------------------------------------------------
| GỬI LIÊN HỆ
|--------------------------------------------------------------------------
| Không cần đăng nhập
| Tin nhắn được lưu vào bảng lien_he
|--------------------------------------------------------------------------
*/

Route::post('/contact', function () {

    $validated = request()->validate([

        'ho_ten' => [
            'required',
            'string',
            'max:100'
        ],

        'email' => [
            'required',
            'email',
            'max:100'
        ],

        'so_dien_thoai' => [
            'required',
            'string',
            'max:20'
        ],

        'noi_dung' => [
            'required',
            'string',
            'max:1000'
        ],

    ], [

        'ho_ten.required' =>
            'Vui lòng nhập họ và tên.',

        'ho_ten.max' =>
            'Họ tên không được quá 100 ký tự.',

        'email.required' =>
            'Vui lòng nhập email.',

        'email.email' =>
            'Email không đúng định dạng.',

        'email.max' =>
            'Email không được quá 100 ký tự.',

        'so_dien_thoai.required' =>
            'Vui lòng nhập số điện thoại.',

        'noi_dung.required' =>
            'Vui lòng nhập nội dung cần tư vấn.',

        'noi_dung.max' =>
            'Nội dung không được quá 1000 ký tự.',

    ]);


    /*
    |--------------------------------------------------------------------------
    | LƯU TIN NHẮN VÀO DATABASE
    |--------------------------------------------------------------------------
    */

    DB::table('lien_he')->insert([

        'ho_ten' =>
            trim($validated['ho_ten']),

        'email' =>
            trim($validated['email']),

        'so_dien_thoai' =>
            trim($validated['so_dien_thoai']),

        'noi_dung' =>
            trim($validated['noi_dung']),

        'trang_thai' =>
            'chưa xử lý',

        'nhan_vien_id' =>
            null,

        'phan_hoi' =>
            null,

        'tao_luc' =>
            now(),

        'xu_ly_luc' =>
            null,

    ]);


    /*
    |--------------------------------------------------------------------------
    | THÔNG BÁO
    |--------------------------------------------------------------------------
    */

    return redirect('/contact')
        ->with(
            'success',
            'Gửi tin nhắn thành công! GYMFIT sẽ liên hệ với bạn sớm nhất.'
        );

});


/*
|--------------------------------------------------------------------------
| ĐĂNG NHẬP - HIỂN THỊ FORM
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('user.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('user.forgot-password');
})->name('password.request');


/*
|--------------------------------------------------------------------------
| QUÊN MẬT KHẨU - GỬI LINK ĐẶT LẠI
|--------------------------------------------------------------------------
| Dùng bảng password_reset_tokens đã có trong schema hiện tại.
| Token được hash trong DB, hết hạn sau 60 phút và chỉ dùng một lần.
*/
Route::post('/forgot-password', function (Request $request) {
    $validated = $request->validate([
        'email' => ['required', 'email', 'max:255'],
    ], [
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
    ]);

    $user = DB::table('nguoi_dung')->where('email', $validated['email'])->first();

    // Trả cùng một thông báo để tránh tiết lộ email có đăng ký hay không.
    $message = 'Nếu email tồn tại trong hệ thống, hướng dẫn đặt lại mật khẩu sẽ được gửi đến hộp thư của bạn.';
    if (!$user) {
        return redirect()->route('password.request')->with('status', $message);
    }

    $plainToken = \Illuminate\Support\Str::random(64);
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => Hash::make($plainToken), 'created_at' => now()]
    );

    $resetUrl = url('/reset-password/' . urlencode($user->email) . '/' . $plainToken);
    try {
        \Illuminate\Support\Facades\Mail::raw(
            "Xin chào {$user->ho_ten},\n\nBạn vừa yêu cầu đặt lại mật khẩu GYMFIT. Mở liên kết sau trong vòng 60 phút: {$resetUrl}\n\nNếu bạn không yêu cầu, hãy bỏ qua email này.",
            function ($mail) use ($user) {
                $mail->to($user->email, $user->ho_ten)->subject('Đặt lại mật khẩu GYMFIT');
            }
        );
    } catch (\Throwable $exception) {
        report($exception);
        return redirect()->route('password.request')->with('error', 'Không thể gửi email lúc này. Vui lòng thử lại sau hoặc liên hệ quản trị viên.');
    }

    return redirect()->route('password.request')->with('status', $message);
})->middleware('throttle:5,1')->name('password.email');

Route::get('/reset-password/{email}/{token}', function ($email, $token) {
    $record = DB::table('password_reset_tokens')->where('email', $email)->first();
    if (!$record || !$record->created_at || \Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast() || !Hash::check($token, $record->token)) {
        return redirect()->route('password.request')->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
    }
    return view('user.reset-password', ['email' => $email, 'token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $validated = $request->validate([
        'email' => ['required', 'email'],
        'token' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ], [
        'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
    ]);

    $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();
    if (!$record || !$record->created_at || \Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast() || !Hash::check($validated['token'], $record->token)) {
        return redirect()->route('password.request')->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
    }

    $updated = DB::transaction(function () use ($validated) {
        $changed = DB::table('nguoi_dung')->where('email', $validated['email'])->update([
            'mat_khau' => Hash::make($validated['password']),
        ]);
        if ($changed) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
        }
        return $changed;
    });

    if (!$updated) {
        return redirect()->route('password.request')->with('error', 'Không tìm thấy tài khoản cần cập nhật.');
    }
    return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Bạn có thể đăng nhập bằng mật khẩu mới.');
})->middleware('throttle:5,1')->name('password.update');


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
    if (!in_array(mb_strtolower(trim((string) $user->trang_thai), 'UTF-8'), [
        'hoat_dong',
        'hoạt_động',
        'hoạt động',
        'dang_hoat_dong',
        'đang hoạt động',
    ], true)) {
        return back()
            ->withInput()
            ->with('error', 'Tài khoản đang bị khóa hoặc ngừng hoạt động.');
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


    // Nâng cấp mật khẩu dạng chữ thường cũ sang bcrypt sau lần đăng nhập hợp lệ.
    if ($matKhau === $user->mat_khau) {
        DB::table('nguoi_dung')->where('nguoi_dung_id', $user->nguoi_dung_id)
            ->update(['mat_khau' => Hash::make($matKhau)]);
        $user->mat_khau = Hash::make($matKhau);
    }

    // Đổi session ID sau xác thực để giảm nguy cơ session fixation.
    request()->session()->regenerate();
    $sessionUser = clone $user;
    unset($sessionUser->mat_khau);

    /*
    |--------------------------------------------------------------------------
    | Lưu session
    |--------------------------------------------------------------------------
    */

    session([
        'user' => $sessionUser,
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

    $thangBatDau = now()->startOfMonth()->subMonths(5);
    $hoaDonTheoThang = DB::table('hoa_don')
        ->whereIn('trang_thai', ['đã thanh toán', 'da thanh toan'])
        ->where('ngay_lap', '>=', $thangBatDau->toDateString())
        ->select('ngay_lap', 'tong_tien')
        ->get()
        ->groupBy(fn ($hoaDon) => substr($hoaDon->ngay_lap, 0, 7));

    $doanhThuTheoThang = collect(range(0, 5))
        ->map(function ($offset) use ($thangBatDau, $hoaDonTheoThang) {
            $thang = $thangBatDau->copy()->addMonths($offset);

            return [
                'nhan' => $thang->format('m/Y'),
                'doanh_thu' => $hoaDonTheoThang
                    ->get($thang->format('Y-m'), collect())
                    ->sum('tong_tien'),
            ];
        });


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
        'admin.dashboard',
        compact(
            'tongHoiVien',
            'hoiVienDangHoatDong',
            'doanhThu',
            'tongGoiTap',
            'doanhThuTheoThang',
            'hoatDongGanDay'
        )
    );

});


/* =====================================
   ADMIN - CÀI ĐẶT HỆ THỐNG
===================================== */

Route::get('/admin/settings', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $settings = DB::table('cai_dat')
        ->pluck('gia_tri', 'khoa');

    return view('admin.settings', compact('settings'));
});

Route::post('/admin/settings/gym', function (Request $request) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $data = $request->validate([
        'ten_phong_gym' => 'required|string|max:150',
        'dia_chi' => 'nullable|string|max:255',
        'so_dien_thoai' => 'nullable|string|max:20',
        'email_lien_he' => 'nullable|email|max:150',
    ]);

    foreach ($data as $key => $value) {
        DB::table('cai_dat')->updateOrInsert(
            ['khoa' => $key],
            ['gia_tri' => $value ?? '', 'updated_at' => now(), 'created_at' => now()]
        );
    }

    return redirect('/admin/settings')
        ->with('success_gym', 'Đã lưu thông tin phòng gym.');
});

Route::post('/admin/settings/password', function (Request $request) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $request->validate([
        'mat_khau_hien_tai' => 'required|string',
        'mat_khau_moi' => 'required|string|min:8|confirmed',
    ], [
        'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
    ]);

    $user = DB::table('nguoi_dung')
        ->where('nguoi_dung_id', session('user')->nguoi_dung_id)
        ->first();

    if (!$user || !Hash::check($request->mat_khau_hien_tai, $user->mat_khau)) {
        return back()->withErrors([
            'mat_khau_hien_tai' => 'Mật khẩu hiện tại không chính xác.',
        ]);
    }

    DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $user->nguoi_dung_id)
        ->update([
            'mat_khau' => Hash::make($request->mat_khau_moi),
        ]);

    return redirect('/admin/settings')
        ->with('success_password', 'Đổi mật khẩu thành công.');
});



/*
|--------------------------------------------------------------------------
| ADMIN - CẤP LẠI MẬT KHẨU (KHÔNG DÙNG EMAIL)
|--------------------------------------------------------------------------
*/

Route::get('/admin/password-resets', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $keyword = trim((string) request('q', ''));

    $users = DB::table('nguoi_dung')
        ->select(
            'nguoi_dung_id',
            'ho_ten',
            'email',
            'vai_tro_id',
            'trang_thai'
        )
        ->when($keyword !== '', function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ho_ten', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        })
        ->orderByDesc('nguoi_dung_id')
        ->paginate(15)
        ->withQueryString();

    return view('admin.password-resets', compact('users', 'keyword'));
});

Route::post('/admin/password-resets/{id}', function (Request $request, $id) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    // Không cho Admin vô tình tự cấp lại mật khẩu cho chính mình qua chức năng này.
    if ((int) $id === (int) session('user')->nguoi_dung_id) {
        return redirect('/admin/password-resets')
            ->with('error', 'Mày không thể cấp lại mật khẩu cho chính tài khoản Admin đang đăng nhập.');
    }

    $user = DB::table('nguoi_dung')->where('nguoi_dung_id', $id)->first();
    if (!$user) {
        return redirect('/admin/password-resets')->with('error', 'Không tìm thấy tài khoản.');
    }

    // Sinh mật khẩu tạm thời ngẫu nhiên, rồi chỉ lưu bản băm vào database.
    $temporaryPassword = \Illuminate\Support\Str::random(16);

    DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $id)
        ->update(['mat_khau' => Hash::make($temporaryPassword)]);

    return redirect('/admin/password-resets')
        ->with('success', 'Đã cấp lại mật khẩu cho tài khoản: ' . $user->email)
        ->with('temporary_password', $temporaryPassword)
        ->with('temporary_password_user', $user->email);
})->name('admin.password-resets.reset');


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


/* ================================
   ADMIN - THÊM HỘI VIÊN
================================ */

Route::get('/admin/members/create', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    return view('admin.member-create');
});

Route::post('/admin/members/create', function (Request $request) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $request->validate([
        'ho_ten' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'mat_khau' => 'required|string|min:6',
        'ngay_sinh' => 'nullable|date',
        'so_dien_thoai' => 'nullable|string|max:20',
        'dia_chi' => 'nullable|string|max:255',
    ], [
        'ho_ten.required' => 'Vui lòng nhập họ tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
        'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
    ]);

    if (DB::table('nguoi_dung')->where('email', $request->email)->exists()) {
        return back()->withInput()->with('error', 'Email đã tồn tại.');
    }

    try {
        DB::transaction(function () use ($request) {
            $nguoiDungId = DB::table('nguoi_dung')->insertGetId([
                'vai_tro_id' => 3,
                'ho_ten' => trim($request->ho_ten),
                'email' => trim($request->email),
                'mat_khau' => Hash::make($request->mat_khau),
                'trang_thai' => 'hoat_dong',
            ]);

            DB::table('hoi_vien')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'ngay_sinh' => $request->ngay_sinh ?: null,
                'so_dien_thoai' => trim($request->so_dien_thoai ?? '') ?: null,
                'dia_chi' => trim($request->dia_chi ?? '') ?: null,
                'ngay_tham_gia' => now()->toDateString(),
            ]);
        });

        return redirect('/admin/members')
            ->with('success', 'Thêm hội viên thành công.');
    } catch (\Throwable $e) {
        return back()->withInput()
            ->with('error', 'Không thể thêm hội viên. Vui lòng kiểm tra dữ liệu.');
    }
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


/* ================================
   ADMIN - XÓA HỘI VIÊN
================================ */

Route::post('/admin/members/{id}/delete', function ($id) {
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

    $relatedChecks = [
        ['dang_ky_goi_tap', 'hoi_vien_id'],
        ['dang_ky_goi_pt', 'hoi_vien_id'],
        ['lich_pt', 'hoi_vien_id'],
        ['check_in', 'hoi_vien_id'],
        ['hoa_don', 'hoi_vien_id'],
        ['dang_ky_lop', 'hoi_vien_id'],
    ];

    foreach ($relatedChecks as [$table, $column]) {
        if (DB::table($table)->where($column, $id)->exists()) {
            return redirect('/admin/members/' . $id)
                ->with('error',
                    'Không thể xóa hội viên vì đã có lịch sử gói tập, lịch PT, check-in, lớp hoặc hóa đơn. Hãy khóa tài khoản thay thế.');
        }
    }

    try {
        DB::transaction(function () use ($member, $id) {
            DB::table('thong_bao')
                ->where('nguoi_dung_id', $member->nguoi_dung_id)
                ->delete();

            DB::table('hoi_vien')
                ->where('hoi_vien_id', $id)
                ->delete();

            DB::table('nguoi_dung')
                ->where('nguoi_dung_id', $member->nguoi_dung_id)
                ->delete();
        });

        return redirect('/admin/members')
            ->with('success', 'Đã xóa hội viên thành công.');
    } catch (\Throwable $e) {
        return redirect('/admin/members/' . $id)
            ->with('error', 'Không thể xóa hội viên do dữ liệu liên quan.');
    }
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


 // =====================================================
 // ADMIN - XÓA GÓI TẬP
 // =====================================================

Route::post('/admin/packages/{id}/delete', function ($id) {

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

    // Không xóa gói đã có đăng ký
    $hasRegistration = DB::table('dang_ky_goi_tap')
        ->where('goi_tap_id', $id)
        ->exists();

    if ($hasRegistration) {
        return redirect('/admin/packages')
            ->with(
                'error',
                'Không thể xóa gói tập vì đã có lịch sử đăng ký. Hãy tạm ngưng gói nếu không muốn tiếp tục sử dụng.'
            );
    }

    // Không xóa gói đã được ghi nhận trong chi tiết hóa đơn
    $hasInvoice = DB::table('chi_tiet_hoa_don')
        ->where('goi_tap_id', $id)
        ->exists();

    if ($hasInvoice) {
        return redirect('/admin/packages')
            ->with(
                'error',
                'Không thể xóa gói tập vì đã có hóa đơn liên quan.'
            );
    }

    try {
        DB::table('goi_tap')
            ->where('goi_tap_id', $id)
            ->delete();

        return redirect('/admin/packages')
            ->with('success', 'Đã xóa gói tập thành công.');

    } catch (\Throwable $e) {
        report($e);

        return redirect('/admin/packages')
            ->with(
                'error',
                'Không thể xóa gói tập. Vui lòng kiểm tra dữ liệu liên quan.'
            );
    }
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
            'huan_luyen_vien.anh',
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


Route::get('/admin/trainers/create', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    return view('admin.trainer-create');
});


Route::post('/admin/trainers/create', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $request = request();

    $request->validate([
        'ho_ten' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'so_dien_thoai' => 'nullable|string|max:20',
        'chuyen_mon' => 'required|string|max:255',
        'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if (DB::table('nguoi_dung')
        ->where('email', $request->email)
        ->exists()) {
        return back()
            ->withInput()
            ->with('error', 'Email này đã được sử dụng.');
    }

    $anh = null;
    $duongDanAnh = null;

    try {
        DB::transaction(function () use ($request, &$anh, &$duongDanAnh) {
            if ($request->hasFile('anh')) {
                $file = $request->file('anh');
                $tenFile = uniqid('trainer_', true) . '.' .
                    $file->getClientOriginalExtension();

                $file->move(public_path('img'), $tenFile);
                $anh = public_path('img/' . $tenFile);
                $duongDanAnh = 'img/' . $tenFile;
            }

            $nguoiDungId = DB::table('nguoi_dung')->insertGetId([
                'vai_tro_id' => 4,
                'ho_ten' => $request->ho_ten,
                'email' => $request->email,
                'mat_khau' => \Illuminate\Support\Facades\Hash::make('Hlv@123456'),
                'trang_thai' => 'hoat_dong',
            ]);

            DB::table('huan_luyen_vien')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'chuyen_mon' => $request->chuyen_mon,
                'so_dien_thoai' => $request->so_dien_thoai,
                'anh' => $duongDanAnh,
            ]);
        });
    } catch (\Throwable $e) {
        if ($anh && file_exists($anh)) {
            @unlink($anh);
        }

        report($e);

        return back()
            ->withInput()
            ->with('error', 'Chưa thêm được HLV. Hãy kiểm tra lỗi trong Laravel log.');
    }

    return redirect('/admin/trainers')
        ->with('success', 'Thêm huấn luyện viên thành công.');
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


Route::post('/admin/trainers/{id}/delete', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $trainer = DB::table('huan_luyen_vien')
        ->where('pt_id', $id)
        ->first();

    if (!$trainer) {
        return redirect('/admin/trainers')
            ->with('error', 'Không tìm thấy huấn luyện viên.');
    }

    // Không xóa nếu huấn luyện viên còn lịch PT
    $hasSchedule = DB::table('lich_pt')
        ->where('pt_id', $id)
        ->exists();

    if ($hasSchedule) {
        return redirect('/admin/trainers')
            ->with(
                'error',
                'Không thể xóa HLV vì vẫn còn lịch PT liên quan. Hãy xử lý lịch tập trước.'
            );
    }

    // Không xóa nếu còn đăng ký gói PT
    $hasRegistration = DB::table('dang_ky_goi_pt')
        ->where('pt_id', $id)
        ->exists();

    if ($hasRegistration) {
        return redirect('/admin/trainers')
            ->with(
                'error',
                'Không thể xóa HLV vì vẫn còn đăng ký gói PT liên quan.'
            );
    }

    try {
        DB::transaction(function () use ($trainer, $id) {
            DB::table('huan_luyen_vien')
                ->where('pt_id', $id)
                ->delete();

            DB::table('nguoi_dung')
                ->where('nguoi_dung_id', $trainer->nguoi_dung_id)
                ->where('vai_tro_id', 4)
                ->delete();
        });
    } catch (\Throwable $e) {
        report($e);

        return redirect('/admin/trainers')
            ->with(
                'error',
                'Chưa thể xóa HLV vì còn dữ liệu liên quan trong hệ thống.'
            );
    }

    return redirect('/admin/trainers')
        ->with('success', 'Xóa huấn luyện viên thành công.');

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

Route::get('/admin/statistics/export', function (Request $request) {
    if (!session()->has('user')) return redirect('/login');
    if ((int) session('role_id') !== 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

    $filters = $request->validate([
        'from' => ['nullable', 'date'],
        'to' => ['nullable', 'date', 'after_or_equal:from'],
    ]);
    $query = DB::table('hoa_don')
        ->join('hoi_vien', 'hoa_don.hoi_vien_id', '=', 'hoi_vien.hoi_vien_id')
        ->join('nguoi_dung', 'hoi_vien.nguoi_dung_id', '=', 'nguoi_dung.nguoi_dung_id')
        ->select('hoa_don.hoa_don_id', 'nguoi_dung.ho_ten', 'nguoi_dung.email', 'hoa_don.ngay_lap', 'hoa_don.tong_tien', 'hoa_don.trang_thai', 'hoa_don.phuong_thuc_thanh_toan')
        ->orderByDesc('hoa_don.ngay_lap');
    if (!empty($filters['from'])) $query->whereDate('hoa_don.ngay_lap', '>=', $filters['from']);
    if (!empty($filters['to'])) $query->whereDate('hoa_don.ngay_lap', '<=', $filters['to']);
    $rows = $query->get();

    return response()->streamDownload(function () use ($rows) {
        $out = fopen('php://output', 'w');
        // BOM để Excel đọc tiếng Việt UTF-8.
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['Mã hóa đơn', 'Hội viên', 'Email', 'Ngày lập', 'Tổng tiền', 'Trạng thái', 'Phương thức']);
        foreach ($rows as $row) {
            $safeText = static function ($value) {
                $value = (string) ($value ?? '');
                return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
            };
            fputcsv($out, [
                $row->hoa_don_id,
                $safeText($row->ho_ten),
                $safeText($row->email),
                $row->ngay_lap,
                $row->tong_tien,
                $safeText($row->trang_thai),
                $safeText($row->phuong_thuc_thanh_toan),
            ]);
        }
        fclose($out);
    }, 'gymfit-bao-cao-doanh-thu-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
})->name('admin.statistics.export');

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

    $trendEnd = $to
        ? Illuminate\Support\Carbon::parse($to)->endOfDay()
        : Illuminate\Support\Carbon::now()->endOfDay();
    $trendStart = $from
        ? Illuminate\Support\Carbon::parse($from)->startOfDay()
        : $trendEnd->copy()->startOfMonth()->subMonths(11);
    $trendByDay = $from && $to
        && Illuminate\Support\Carbon::parse($from)->diffInDays(Illuminate\Support\Carbon::parse($to)) <= 7;
    $trendByYear = $trendStart->diffInDays($trendEnd) > 730;

    $invoiceTrendQuery = DB::table('hoa_don')
        ->where('trang_thai', 'đã thanh toán')
        ->whereDate('ngay_lap', '>=', $trendStart->toDateString())
        ->whereDate('ngay_lap', '<=', $trendEnd->toDateString());
    $checkInTrendQuery = DB::table('check_in')
        ->whereDate('thoi_gian', '>=', $trendStart->toDateString())
        ->whereDate('thoi_gian', '<=', $trendEnd->toDateString());

    $revenueByDay = $invoiceTrendQuery
        ->selectRaw('DATE(ngay_lap) as ngay, SUM(tong_tien) as gia_tri')
        ->groupByRaw('DATE(ngay_lap)')
        ->orderBy('ngay')
        ->get()
        ->keyBy(fn ($row) => Illuminate\Support\Carbon::parse($row->ngay)->format('Y-m-d'));

    $checkInsByDay = $checkInTrendQuery
        ->selectRaw('DATE(thoi_gian) as ngay, COUNT(*) as gia_tri')
        ->groupByRaw('DATE(thoi_gian)')
        ->orderBy('ngay')
        ->get()
        ->keyBy(fn ($row) => Illuminate\Support\Carbon::parse($row->ngay)->format('Y-m-d'));

    $trendFormat = $trendByDay ? 'Y-m-d' : ($trendByYear ? 'Y' : 'Y-m');
    if ($trendByDay) {
        $trendStart = $trendStart->copy();
        $trendEnd = $trendEnd->copy();
    } elseif ($trendByYear) {
        $trendStart = $trendStart->copy()->startOfYear();
        $trendEnd = $trendEnd->copy()->startOfYear();
    } else {
        $trendStart = $trendStart->copy()->startOfMonth();
        $trendEnd = $trendEnd->copy()->startOfMonth();
    }
    $revenueByPeriod = $revenueByDay
        ->groupBy(fn ($row, $date) => Illuminate\Support\Carbon::parse($date)->format($trendFormat))
        ->map(fn ($rows) => $rows->sum('gia_tri'));
    $checkInsByPeriod = $checkInsByDay
        ->groupBy(fn ($row, $date) => Illuminate\Support\Carbon::parse($date)->format($trendFormat))
        ->map(fn ($rows) => $rows->sum('gia_tri'));
    $trends = collect();

    while ($trendStart->lte($trendEnd)) {
        $period = $trendStart->format($trendFormat);
        $trends->push([
            'label' => $trendStart->format($trendByDay ? 'd/m' : ($trendByYear ? 'Y' : 'm/Y')),
            'revenue' => $revenueByPeriod->get($period, 0),
            'check_ins' => $checkInsByPeriod->get($period, 0),
        ]);

        if ($trendByDay) {
            $trendStart->addDay();
        } elseif ($trendByYear) {
            $trendStart->addYear();
        } else {
            $trendStart->addMonth();
        }
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
        'cancelledSchedules',
        'trends'
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
| QUẢN LÝ HỘI VIÊN - NHÂN VIÊN
|--------------------------------------------------------------------------
*/


// =========================================================
// DANH SÁCH HỘI VIÊN
// =========================================================

Route::get('/staff/members', function (Request $request) {

    // Kiểm tra đăng nhập
    if (!session()->has('user')) {
        return redirect('/login');
    }

    // Chỉ Nhân viên
    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $query = DB::table('hoi_vien')
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
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
        );

    // Tìm kiếm
    if ($request->filled('q')) {

        $q = trim($request->q);

        $query->where(function ($search) use ($q) {

            $search->where('nguoi_dung.ho_ten', 'like', '%' . $q . '%')
                ->orWhere('nguoi_dung.email', 'like', '%' . $q . '%')
                ->orWhere('hoi_vien.so_dien_thoai', 'like', '%' . $q . '%');

        });
    }

    $hoiVien = $query
        ->orderByDesc('hoi_vien.hoi_vien_id')
        ->get();

    return view(
        'staff.members',
        compact('hoiVien')
    );

})->middleware(NoCache::class);


// =========================================================
// FORM THÊM HỘI VIÊN
// =========================================================

Route::get('/staff/members/create', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    return view('staff.member-create');

})->middleware(NoCache::class);


// =========================================================
// XỬ LÝ THÊM HỘI VIÊN
// =========================================================

Route::post('/staff/members', function (Request $request) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $request->validate([
        'ho_ten' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'mat_khau' => 'required|string|min:6',
        'ngay_sinh' => 'nullable|date',
        'so_dien_thoai' => 'nullable|string|max:20',
        'dia_chi' => 'nullable|string|max:255',
    ], [
        'ho_ten.required' => 'Vui lòng nhập họ tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
        'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
    ]);

    // Kiểm tra email
    $emailExists = DB::table('nguoi_dung')
        ->where('email', $request->email)
        ->exists();

    if ($emailExists) {
        return back()
            ->withInput()
            ->with('error', 'Email này đã tồn tại trong hệ thống.');
    }

    DB::beginTransaction();

    try {

        // Tạo tài khoản
        $nguoiDungId = DB::table('nguoi_dung')->insertGetId([
            'vai_tro_id' => 3,
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'mat_khau' => Hash::make($request->mat_khau),
            'trang_thai' => 'hoạt_động',
        ]);

        // Tạo hồ sơ hội viên
        DB::table('hoi_vien')->insert([
            'nguoi_dung_id' => $nguoiDungId,
            'ngay_sinh' => $request->ngay_sinh,
            'so_dien_thoai' => $request->so_dien_thoai,
            'dia_chi' => $request->dia_chi,
            'ngay_tham_gia' => now()->toDateString(),
        ]);

        DB::commit();

        return redirect('/staff/members')
            ->with('success', 'Thêm hội viên thành công.');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Không thể thêm hội viên: ' . $e->getMessage());
    }

})->middleware(NoCache::class);


// =========================================================
// FORM SỬA HỘI VIÊN
// =========================================================

Route::get('/staff/members/{id}/edit', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $hoiVien = DB::table('hoi_vien')
        ->join(
            'nguoi_dung',
            'hoi_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('hoi_vien.hoi_vien_id', $id)
        ->select(
            'hoi_vien.*',
            'nguoi_dung.ho_ten',
            'nguoi_dung.email',
            'nguoi_dung.trang_thai'
        )
        ->first();

    if (!$hoiVien) {
        return redirect('/staff/members')
            ->with('error', 'Không tìm thấy hội viên.');
    }

    return view(
        'staff.member-edit',
        compact('hoiVien')
    );

})->middleware(NoCache::class);


// =========================================================
// XỬ LÝ SỬA HỘI VIÊN
// =========================================================

Route::post('/staff/members/{id}/update', function (Request $request, $id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $hoiVien = DB::table('hoi_vien')
        ->where('hoi_vien_id', $id)
        ->first();

    if (!$hoiVien) {
        return redirect('/staff/members')
            ->with('error', 'Không tìm thấy hội viên.');
    }

    $nguoiDung = DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $hoiVien->nguoi_dung_id)
        ->first();

    if (!$nguoiDung) {
        return redirect('/staff/members')
            ->with('error', 'Không tìm thấy tài khoản hội viên.');
    }

    $request->validate([
        'ho_ten' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'ngay_sinh' => 'nullable|date',
        'so_dien_thoai' => 'nullable|string|max:20',
        'dia_chi' => 'nullable|string|max:255',
        'trang_thai' => 'required|string|max:30',
    ], [
        'ho_ten.required' => 'Vui lòng nhập họ tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
    ]);

    // Kiểm tra email trùng
    $emailExists = DB::table('nguoi_dung')
        ->where('email', $request->email)
        ->where(
            'nguoi_dung_id',
            '!=',
            $hoiVien->nguoi_dung_id
        )
        ->exists();

    if ($emailExists) {
        return back()
            ->withInput()
            ->with('error', 'Email này đã được sử dụng.');
    }

    DB::beginTransaction();

    try {

        // Cập nhật tài khoản
        DB::table('nguoi_dung')
            ->where(
                'nguoi_dung_id',
                $hoiVien->nguoi_dung_id
            )
            ->update([
                'ho_ten' => $request->ho_ten,
                'email' => $request->email,
                'trang_thai' => $request->trang_thai,
            ]);

        // Nếu nhập mật khẩu mới thì cập nhật
        if ($request->filled('mat_khau')) {

            DB::table('nguoi_dung')
                ->where(
                    'nguoi_dung_id',
                    $hoiVien->nguoi_dung_id
                )
                ->update([
                    'mat_khau' => Hash::make($request->mat_khau),
                ]);
        }

        // Cập nhật hồ sơ
        DB::table('hoi_vien')
            ->where(
                'hoi_vien_id',
                $id
            )
            ->update([
                'ngay_sinh' => $request->ngay_sinh,
                'so_dien_thoai' => $request->so_dien_thoai,
                'dia_chi' => $request->dia_chi,
            ]);

        DB::commit();

        return redirect('/staff/members')
            ->with('success', 'Cập nhật hội viên thành công.');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Không thể cập nhật hội viên: ' . $e->getMessage());
    }

})->middleware(NoCache::class);


// =========================================================
// XÓA HỘI VIÊN
// =========================================================

Route::post('/staff/members/{id}/delete', function ($id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $hoiVien = DB::table('hoi_vien')
        ->where('hoi_vien_id', $id)
        ->first();

    if (!$hoiVien) {
        return redirect('/staff/members')
            ->with('error', 'Không tìm thấy hội viên.');
    }

    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA DỮ LIỆU LIÊN QUAN
    |--------------------------------------------------------------------------
    | Nếu hội viên đã có lịch sử tập / hóa đơn / check-in
    | thì không xóa tài khoản thật để tránh mất dữ liệu.
    | Hệ thống sẽ khóa tài khoản.
    */

    $coDangKyGoi = DB::table('dang_ky_goi_tap')
        ->where('hoi_vien_id', $id)
        ->exists();

    $coDangKyPT = DB::table('dang_ky_goi_pt')
        ->where('hoi_vien_id', $id)
        ->exists();

    $coDangKyLop = DB::table('dang_ky_lop')
        ->where('hoi_vien_id', $id)
        ->exists();

    $coCheckIn = DB::table('check_in')
        ->where('hoi_vien_id', $id)
        ->exists();

    $coHoaDon = DB::table('hoa_don')
        ->where('hoi_vien_id', $id)
        ->exists();

    $coLichPT = DB::table('lich_pt')
        ->where('hoi_vien_id', $id)
        ->exists();

    if (
        $coDangKyGoi ||
        $coDangKyPT ||
        $coDangKyLop ||
        $coCheckIn ||
        $coHoaDon ||
        $coLichPT
    ) {

        DB::table('nguoi_dung')
            ->where(
                'nguoi_dung_id',
                $hoiVien->nguoi_dung_id
            )
            ->update([
                'trang_thai' => 'bị_khóa',
            ]);

        return redirect('/staff/members')
            ->with(
                'success',
                'Hội viên đã có dữ liệu liên quan nên hệ thống chuyển sang khóa tài khoản để giữ lịch sử.'
            );
    }

    DB::beginTransaction();

    try {

        // Xóa thông báo
        DB::table('thong_bao')
            ->where(
                'nguoi_dung_id',
                $hoiVien->nguoi_dung_id
            )
            ->delete();

        // Xóa hồ sơ hội viên
        DB::table('hoi_vien')
            ->where(
                'hoi_vien_id',
                $id
            )
            ->delete();

        // Xóa tài khoản
        DB::table('nguoi_dung')
            ->where(
                'nguoi_dung_id',
                $hoiVien->nguoi_dung_id
            )
            ->delete();

        DB::commit();

        return redirect('/staff/members')
            ->with('success', 'Xóa hội viên thành công.');

    } catch (\Throwable $e) {

        DB::rollBack();

        return redirect('/staff/members')
            ->with(
                'error',
                'Không thể xóa hội viên: ' . $e->getMessage()
            );
    }

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
            // Kich hoat dang ky goi tuong ung voi hoa don vua thanh toan.
            $chiTietHoaDon = DB::table('chi_tiet_hoa_don')
                ->where('hoa_don_id', $id)
                ->get();

            $ngayHoaDon = \Carbon\Carbon::parse($hoaDon->ngay_lap)->toDateString();

            foreach ($chiTietHoaDon as $chiTiet) {
                if (!empty($chiTiet->goi_tap_id)) {
                    $dangKyGoiTap = DB::table('dang_ky_goi_tap')
                        ->where('hoi_vien_id', $hoaDon->hoi_vien_id)
                        ->where('goi_tap_id', $chiTiet->goi_tap_id)
                        ->whereDate('ngay_bat_dau', $ngayHoaDon)
                        ->whereIn('trang_thai', [
                            'không hoạt động',
                            'chờ thanh toán',
                            'cho thanh toan',
                            'cho_thanh_toan',
                            'pending'
                        ])
                        ->orderByDesc('dang_ky_goi_tap_id')
                        ->first();

                    if ($dangKyGoiTap) {
                        DB::table('dang_ky_goi_tap')
                            ->where('dang_ky_goi_tap_id', $dangKyGoiTap->dang_ky_goi_tap_id)
                            ->update(['trang_thai' => 'đang hoạt động']);
                    }
                }

                if (!empty($chiTiet->goi_pt_id)) {
                    $dangKyPT = DB::table('dang_ky_goi_pt')
                        ->where('hoi_vien_id', $hoaDon->hoi_vien_id)
                        ->where('goi_pt_id', $chiTiet->goi_pt_id)
                        ->whereDate('ngay_bat_dau', $ngayHoaDon)
                        ->whereIn('trang_thai', [
                            'không hoạt động',
                            'chờ thanh toán',
                            'cho thanh toan',
                            'cho_thanh_toan',
                            'pending'
                        ])
                        ->orderByDesc('dang_ky_goi_pt_id')
                        ->first();

                    if ($dangKyPT) {
                        DB::table('dang_ky_goi_pt')
                            ->where('dang_ky_goi_pt_id', $dangKyPT->dang_ky_goi_pt_id)
                            ->update(['trang_thai' => 'đang hoạt động']);
                    }
                }
            }
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

    try {
        DB::transaction(function () use ($dangKy, $hoiVien, $validated, $nguoiDungId, $batDau, $ketThuc) {
            // Khóa bản ghi PT và gói đăng ký để các yêu cầu đồng thời được xử lý tuần tự.
            DB::table('huan_luyen_vien')->where('pt_id', $dangKy->pt_id)->lockForUpdate()->first();
            $lockedRegistration = DB::table('dang_ky_goi_pt')
                ->where('dang_ky_goi_pt_id', $dangKy->dang_ky_goi_pt_id)
                ->lockForUpdate()->first();
            if (!$lockedRegistration || (int) $lockedRegistration->so_buoi_con_lai <= 0 || !in_array($lockedRegistration->trang_thai, ['đang hoạt động', 'dang_hoat_dong', 'hoạt_động', 'hoat_dong'], true)) {
                throw new \RuntimeException('Gói PT không còn hiệu lực hoặc đã hết buổi.');
            }

            $overlap = DB::table('lich_pt')->whereNotIn('trang_thai', ['đã hủy', 'da_huy', 'hủy', 'huy'])
                ->where('thoi_gian_bat_dau', '<', $validated['thoi_gian_ket_thuc'])
                ->where('thoi_gian_ket_thuc', '>', $validated['thoi_gian_bat_dau'])
                ->where(function ($q) use ($dangKy, $hoiVien) {
                    $q->where('pt_id', $dangKy->pt_id)->orWhere('hoi_vien_id', $hoiVien->hoi_vien_id);
                })->exists();
            if ($overlap) throw new \RuntimeException('PT hoặc hội viên vừa phát sinh lịch trùng. Vui lòng chọn khung giờ khác.');

            DB::table('lich_pt')->insert([
                'dang_ky_goi_pt_id' => $dangKy->dang_ky_goi_pt_id,
                'hoi_vien_id' => $hoiVien->hoi_vien_id,
                'pt_id' => $dangKy->pt_id,
                'thoi_gian_bat_dau' => $validated['thoi_gian_bat_dau'],
                'thoi_gian_ket_thuc' => $validated['thoi_gian_ket_thuc'],
                'trang_thai' => 'đã đặt lịch',
            ]);

            DB::table('thong_bao')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'tieu_de' => 'Đặt lịch PT thành công',
                'noi_dung' => 'Bạn đã đặt lịch cho gói ' . $dangKy->ten_goi_pt . ' từ ' . $batDau->format('d/m/Y H:i') . ' đến ' . $ketThuc->format('H:i') . '.',
                'da_doc' => false,
                'tao_luc' => now(),
            ]);
        }, 3);

        return redirect('/user/pt-schedule')->with('success', 'Đặt lịch PT thành công!');
    } catch (\Throwable $e) {
        report($e);
        return back()->withInput()->with('error', $e instanceof \RuntimeException ? $e->getMessage() : 'Không thể đặt lịch lúc này. Vui lòng thử lại.');
    }

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| HỦY GÓI TẬP - HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::post('/user/packages/{id}/cancel', function ($id) {

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
        return redirect('/user/dashboard')
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    // Dọn dữ liệu quá hạn trước khi kiểm tra hủy.

    $dangKy = DB::table('dang_ky_goi_tap')
        ->join(
            'goi_tap',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->leftJoin(
            'chi_tiet_hoa_don',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'chi_tiet_hoa_don.goi_tap_id'
        )
        ->leftJoin(
            'hoa_don',
            'chi_tiet_hoa_don.hoa_don_id',
            '=',
            'hoa_don.hoa_don_id'
        )
        ->where(
            'dang_ky_goi_tap.dang_ky_goi_tap_id',
            $id
        )
        ->where(
            'dang_ky_goi_tap.hoi_vien_id',
            $hoiVien->hoi_vien_id
        )
        ->select(
            'dang_ky_goi_tap.*',
            'goi_tap.ten_goi',
            'hoa_don.hoa_don_id',
            'hoa_don.ngay_lap',
            'hoa_don.trang_thai as hoa_don_trang_thai'
        )
        ->orderByDesc('hoa_don.ngay_lap')
        ->first();

    if (!$dangKy) {
        return redirect('/user/dashboard')
            ->with('error', 'Gói tập này không còn tồn tại hoặc đã hết hạn.');
    }

    $trangThaiGoi = mb_strtolower(
        trim($dangKy->trang_thai ?? '')
    );

    if (!in_array($trangThaiGoi, [
        'đang hoạt động',
        'dang_hoat_dong',
        'hoạt_động',
        'hoat_dong'
    ], true)) {

        return redirect('/user/dashboard')
            ->with('error', 'Gói tập này không còn hoạt động.');
    }

    $trangThaiHoaDon = mb_strtolower(
        trim($dangKy->hoa_don_trang_thai ?? '')
    );

    // Đã thanh toán => tuyệt đối không cho hủy.
    if (in_array($trangThaiHoaDon, [
        'đã thanh toán',
        'da thanh toan',
        'da_thanh_toan',
        'paid'
    ], true)) {

        return redirect('/user/dashboard')
            ->with(
                'error',
                'Gói tập đã thanh toán nên không thể hủy.'
            );
    }

    // Chỉ cho hủy hóa đơn đang chờ thanh toán.
    if (!in_array($trangThaiHoaDon, [
        'chờ thanh toán',
        'cho thanh toan',
        'cho_thanh_toan',
        'pending'
    ], true)) {

        return redirect('/user/dashboard')
            ->with(
                'error',
                'Gói tập này không thể hủy ở trạng thái hiện tại.'
            );
    }

    // Kiểm tra 72 giờ.
    if (
        !$dangKy->ngay_lap ||
        now()->greaterThanOrEqualTo(
            \Carbon\Carbon::parse($dangKy->ngay_lap)->addHours(72)
        )
    ) {

        // Nếu vừa chạm quá hạn, xóa luôn dữ liệu.
        DB::transaction(function () use ($id, $dangKy) {

            if ($dangKy->hoa_don_id) {
                DB::table('chi_tiet_hoa_don')
                    ->where(
                        'hoa_don_id',
                        $dangKy->hoa_don_id
                    )
                    ->delete();

                DB::table('hoa_don')
                    ->where(
                        'hoa_don_id',
                        $dangKy->hoa_don_id
                    )
                    ->delete();
            }

            DB::table('dang_ky_goi_tap')
                ->where(
                    'dang_ky_goi_tap_id',
                    $id
                )
                ->delete();
        });

        return redirect('/packages')
            ->with(
                'error',
                'Gói đã quá 72 giờ chưa thanh toán nên hệ thống đã tự động hủy và xóa hóa đơn. Lịch sử gói vẫn được lưu.'
            );
    }

    // Còn trong 72 giờ => hủy và xóa hóa đơn.
    DB::transaction(function () use ($id, $dangKy, $nguoiDungId) {

        if ($dangKy->hoa_don_id) {

            DB::table('chi_tiet_hoa_don')
                ->where(
                    'hoa_don_id',
                    $dangKy->hoa_don_id
                )
                ->delete();

            DB::table('hoa_don')
                ->where(
                    'hoa_don_id',
                    $dangKy->hoa_don_id
                )
                ->delete();
        }

        DB::table('dang_ky_goi_tap')
            ->where(
                'dang_ky_goi_tap_id',
                $id
            )
            ->update([
                'trang_thai' => 'đã hủy'
            ]);

        DB::table('thong_bao')->insert([
            'nguoi_dung_id' => $nguoiDungId,
            'tieu_de' => 'Hủy gói tập thành công',
            'noi_dung' =>
                'Bạn đã hủy gói "' .
                $dangKy->ten_goi .
                '". Hóa đơn liên quan đã được xóa.',
            'da_doc' => false,
            'tao_luc' => now(),
        ]);
    });

    return redirect('/user/dashboard')
        ->with(
            'success',
            'Đã hủy gói "' . $dangKy->ten_goi . '". Hóa đơn đã được xóa.'
        );

})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| HỦY GÓI PT - HỘI VIÊN
|--------------------------------------------------------------------------
*/



// =====================================================
// HỘI VIÊN - CHỌN HUẤN LUYỆN VIÊN CHO GÓI PT
// =====================================================
Route::get('/pt-packages/{id}/choose', function ($id) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 3) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $goiPt = DB::table('goi_pt')
        ->where('goi_pt_id', $id)
        ->first();

    if (!$goiPt || !in_array(mb_strtolower(trim($goiPt->trang_thai ?? ''), 'UTF-8'), [
        'hoat_dong', 'hoạt_động', 'hoạt động', 'đang hoạt động', 'dang_hoat_dong'
    ], true)) {
        return redirect('/pt-packages')->with('error', 'Gói PT không tồn tại hoặc hiện không hoạt động.');
    }

    $ptList = DB::table('huan_luyen_vien')
        ->join('nguoi_dung', 'huan_luyen_vien.nguoi_dung_id', '=', 'nguoi_dung.nguoi_dung_id')
        ->where('nguoi_dung.vai_tro_id', 4)
        ->whereIn('nguoi_dung.trang_thai', [
            'hoat_dong', 'hoạt_động', 'hoạt động', 'dang_hoat_dong', 'đang hoạt động'
        ])
        ->select(
            'huan_luyen_vien.pt_id',
            'huan_luyen_vien.chuyen_mon',
            'huan_luyen_vien.thanh_tich',
            'huan_luyen_vien.anh',
            'nguoi_dung.ho_ten'
        )
        ->orderBy('nguoi_dung.ho_ten')
        ->get();

    return view('user.pt-choose', compact('goiPt', 'ptList'));
});

Route::post('/pt-packages/{id}/register', function ($id) {

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
        return back()->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    $goiPt = DB::table('goi_pt')
        ->where('goi_pt_id', $id)
        ->first();

    if (!$goiPt) {
        return back()->with('error', 'Gói PT không tồn tại.');
    }

    if (!in_array($goiPt->trang_thai, [
        'hoat_dong', 'hoạt_động', 'đang hoạt động', 'dang_hoat_dong'
    ], true)) {
        return back()->with('error', 'Gói PT này hiện không hoạt động.');
    }

    // Chỉ cho phép mỗi hội viên có một gói PT tại một thời điểm.
    // Gói đang hoạt động hoặc đang chờ thanh toán đều được tính;
    // gói đã hủy và gói đã hết hạn không chặn đăng ký mới.
    $goiPtHienTai = DB::table('dang_ky_goi_pt')
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->whereDate('ngay_ket_thuc', '>=', now()->toDateString())
        ->whereIn('trang_thai', [
            'đang hoạt động', 'dang_hoat_dong', 'hoạt_động', 'hoat_dong',
            'không hoạt động', 'khong hoat dong', 'không_hoạt_động',
            'khong_hoat_dong', 'inactive'
        ])
        ->orderByDesc('dang_ky_goi_pt_id')
        ->first();

    if ($goiPtHienTai) {
        $hoaDonChoThanhToan = DB::table('chi_tiet_hoa_don')
            ->join('hoa_don', 'chi_tiet_hoa_don.hoa_don_id', '=', 'hoa_don.hoa_don_id')
            ->where('hoa_don.hoi_vien_id', $hoiVien->hoi_vien_id)
            ->where('chi_tiet_hoa_don.goi_pt_id', $goiPtHienTai->goi_pt_id)
            ->whereIn('hoa_don.trang_thai', ['chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending'])
            ->exists();

        $thongBao = $hoaDonChoThanhToan
            ? 'Bạn đã đăng ký một gói PT và hóa đơn đang chờ thanh toán. Vui lòng thanh toán hoặc hủy đăng ký hiện tại trước khi đăng ký gói mới.'
            : 'Bạn đang có gói PT còn hiệu lực, chưa thể đăng ký gói mới.';

        return redirect('/pt-packages')->with('error', $thongBao);
    }

    // Lấy đúng huấn luyện viên mà hội viên đã chọn trên form.
    $ptId = request()->input('pt_id');

    if (!$ptId) {
        return back()->withInput()->with('error', 'Vui lòng chọn huấn luyện viên.');
    }

    // Chỉ chấp nhận PT có hồ sơ hợp lệ, đúng vai trò và đang hoạt động.
    $pt = DB::table('huan_luyen_vien')
        ->join(
            'nguoi_dung',
            'huan_luyen_vien.nguoi_dung_id',
            '=',
            'nguoi_dung.nguoi_dung_id'
        )
        ->where('huan_luyen_vien.pt_id', $ptId)
        ->where('nguoi_dung.vai_tro_id', 4)
        ->whereIn('nguoi_dung.trang_thai', [
            'hoat_dong', 'hoạt_động', 'hoạt động', 'dang_hoat_dong', 'đang hoạt động'
        ])
        ->select('huan_luyen_vien.*')
        ->first();

    if (!$pt) {
        return back()->withInput()->with(
            'error',
            'Huấn luyện viên không tồn tại hoặc đã bị khóa/ngừng hoạt động. Vui lòng chọn PT khác.'
        );
    }

    $nhanVien = DB::table('nhan_vien')
        ->orderBy('nhan_vien_id')
        ->first();

    if (!$nhanVien) {
        return back()->with(
            'error',
            'Hệ thống chưa có nhân viên để lập hóa đơn.'
        );
    }

    $ngayBatDau = now()->toDateString();
    $ngayKetThuc = now()
        ->addDays(max(1, (int) $goiPt->thoi_han_ngay) - 1)
        ->toDateString();

    try {
        DB::transaction(function () use (
            $hoiVien,
            $goiPt,
            $pt,
            $nhanVien,
            $nguoiDungId,
            $ngayBatDau,
            $ngayKetThuc
        ) {
            // 1. Tạo đăng ký PT.
            DB::table('dang_ky_goi_pt')->insert([
                'hoi_vien_id' => $hoiVien->hoi_vien_id,
                'goi_pt_id' => $goiPt->goi_pt_id,
                'pt_id' => $pt->pt_id,
                'ngay_bat_dau' => $ngayBatDau,
                'ngay_ket_thuc' => $ngayKetThuc,
                'so_buoi_con_lai' => $goiPt->so_buoi,
                'trang_thai' => 'không hoạt động',
            ]);

            // 2. Tạo hóa đơn chờ thanh toán.
            $hoaDonId = DB::table('hoa_don')->insertGetId([
                'hoi_vien_id' => $hoiVien->hoi_vien_id,
                'nhan_vien_id' => $nhanVien->nhan_vien_id,
                'ngay_lap' => now(),
                'tong_tien' => $goiPt->gia,
                'trang_thai' => 'chờ thanh toán',
                'phuong_thuc_thanh_toan' => null,
            ]);

            // 3. Gắn đúng gói PT vào chi tiết hóa đơn.
            DB::table('chi_tiet_hoa_don')->insert([
                'hoa_don_id' => $hoaDonId,
                'goi_tap_id' => null,
                'goi_pt_id' => $goiPt->goi_pt_id,
                'so_luong' => 1,
                'don_gia' => $goiPt->gia,
                'thanh_tien' => $goiPt->gia,
            ]);

            // 4. Thông báo cho hội viên.
            DB::table('thong_bao')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'tieu_de' => 'Đăng ký gói PT thành công',
                'noi_dung' => 'Đã tạo hóa đơn #HD'
                    . str_pad($hoaDonId, 5, '0', STR_PAD_LEFT)
                    . ' cho gói PT ' . $goiPt->ten_goi_pt
                    . '. Vui lòng liên hệ nhân viên để thanh toán trong 72 giờ.',
                'da_doc' => 0,
                'tao_luc' => now(),
            ]);
        });

        return redirect('/pt-packages')
    ->with(
        'success',
        'Đăng ký gói PT thành công! Hóa đơn đã được tạo và đang chờ thanh toán.'
    );

    } catch (\Throwable $e) {
        report($e);

        return redirect('/pt-packages')
    ->with('error', 'Không thể tạo đăng ký và hóa đơn. Vui lòng thử lại.');
    }

});


/*
|--------------------------------------------------------------------------
| THÔNG BÁO - BẤM VÀO = ĐÃ ĐỌC
|--------------------------------------------------------------------------
*/

Route::post('/notifications/read-all', function () {

    if (!session()->has('user')) {
        return response()->json([
            'success' => false,
            'message' => 'Chưa đăng nhập.'
        ], 401);
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    $soLuong = DB::table('thong_bao')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->where('da_doc', 0)
        ->update([
            'da_doc' => 1
        ]);

    return response()->json([
        'success' => true,
        'updated' => $soLuong
    ]);
})->middleware(NoCache::class);


/*
|--------------------------------------------------------------------------
| DASHBOARD HỘI VIÊN
|--------------------------------------------------------------------------
*/

Route::get('/user/dashboard', function () use ($xuLyGoiTapCuaHoiVien) {

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

                'goiPT' => collect(),

                'lopTap' => collect(),

                'hoaDon' => collect(),

                'thongBao' => collect(),

            ]
        );

    }




    /*
    |--------------------------------------------------------------------------
    | TỰ ĐỘNG XỬ LÝ GÓI HẾT HẠN / HÓA ĐƠN QUÁ 72 GIỜ
    |--------------------------------------------------------------------------
    */

    $xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);


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
            'dang_ky_goi_tap.dang_ky_goi_tap_id',
            'dang_ky_goi_tap.goi_tap_id',
            'goi_tap.ten_goi',
            'dang_ky_goi_tap.ngay_bat_dau',
            'dang_ky_goi_tap.ngay_ket_thuc',
            'dang_ky_goi_tap.so_buoi_con_lai',
            'dang_ky_goi_tap.trang_thai'
        )
        ->orderByDesc('dang_ky_goi_tap.dang_ky_goi_tap_id')
        ->limit(10)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | LẤY HÓA ĐƠN RIÊNG CHO TỪNG LẦN ĐĂNG KÝ
    | Không JOIN trực tiếp để tránh một gói bị lặp nhiều dòng.
    |--------------------------------------------------------------------------
    */

    foreach ($goiTap as $goi) {

        $hoaDonCuaGoi = DB::table('chi_tiet_hoa_don')
            ->join(
                'hoa_don',
                'chi_tiet_hoa_don.hoa_don_id',
                '=',
                'hoa_don.hoa_don_id'
            )
            ->where(
                'chi_tiet_hoa_don.goi_tap_id',
                $goi->goi_tap_id
            )
            ->where(
                'hoa_don.hoi_vien_id',
                $hoiVien->hoi_vien_id
            )
            ->whereDate(
                'hoa_don.ngay_lap',
                '>=',
                $goi->ngay_bat_dau
            )
            ->select(
                'hoa_don.hoa_don_id',
                'hoa_don.ngay_lap',
                'hoa_don.trang_thai'
            )
            ->orderByDesc('hoa_don.ngay_lap')
            ->first();

        $goi->hoa_don_id = $hoaDonCuaGoi->hoa_don_id ?? null;
        $goi->hoa_don_ngay_lap = $hoaDonCuaGoi->ngay_lap ?? null;
        $goi->hoa_don_trang_thai = $hoaDonCuaGoi->trang_thai ?? null;
    }


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

Route::get('/user/payments', function () use ($xuLyGoiTapCuaHoiVien) {

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
        return redirect('/user/dashboard')
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }


    // Tự động xóa gói/hóa đơn quá 72 giờ và cập nhật gói hết hạn.
    $xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);


    $hoaDon = DB::table('hoa_don')
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->orderByDesc('ngay_lap')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | NHẮC NHỞ THANH TOÁN
    |--------------------------------------------------------------------------
    */

    $hoaDonChoThanhToan = $hoaDon->filter(function ($hd) {

        return in_array(
            mb_strtolower(trim($hd->trang_thai ?? '')),
            [
                'chờ thanh toán',
                'cho thanh toan',
                'cho_thanh_toan',
                'pending'
            ],
            true
        );

    });

    foreach ($hoaDonChoThanhToan as $hd) {

        $daNhacGanDay = DB::table('thong_bao')
            ->where('nguoi_dung_id', $nguoiDungId)
            ->where('tieu_de', 'Nhắc nhở thanh toán')
            ->where(
                'noi_dung',
                'like',
                '%#HD' .
                str_pad(
                    $hd->hoa_don_id,
                    5,
                    '0',
                    STR_PAD_LEFT
                ) .
                '%'
            )
            ->where(
                'tao_luc',
                '>=',
                now()->subHours(24)
            )
            ->exists();

        if (!$daNhacGanDay) {

            $hanThanhToan = \Carbon\Carbon::parse(
                $hd->ngay_lap
            )->addHours(72);

            $conLai = now()->diffInHours(
                $hanThanhToan,
                false
            );

            $thoiGianConLai = $conLai > 0
                ? $conLai . ' giờ'
                : 'đã quá hạn 72 giờ';

            DB::table('thong_bao')->insert([
                'nguoi_dung_id' => $nguoiDungId,
                'tieu_de' => 'Nhắc nhở thanh toán',
                'noi_dung' =>
                    'Hóa đơn #HD' .
                    str_pad(
                        $hd->hoa_don_id,
                        5,
                        '0',
                        STR_PAD_LEFT
                    ) .
                    ' trị giá ' .
                    number_format(
                        $hd->tong_tien,
                        0,
                        ',',
                        '.'
                    ) .
                    ' VNĐ đang chờ thanh toán. ' .
                    'Thời gian còn lại: ' .
                    $thoiGianConLai .
                    '.',
                'da_doc' => 0,
                'tao_luc' => now(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHI TIẾT HÓA ĐƠN
    |--------------------------------------------------------------------------
    */

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
| THANH TOÁN HÓA ĐƠN HỘI VIÊN (MÔ PHỎNG)
|--------------------------------------------------------------------------
*/
Route::get('/user/payments/checkout/{id}', function ($id) use ($xuLyGoiTapCuaHoiVien) {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 3) return redirect('/' . session('dashboard_path'));

    $nguoiDungId = session('user')->nguoi_dung_id;
    $hoiVien = DB::table('hoi_vien')->where('nguoi_dung_id', $nguoiDungId)->first();
    if (!$hoiVien) return redirect('/user/payments')->with('error', 'Không tìm thấy hồ sơ hội viên.');

    $xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);
    $hoaDon = DB::table('hoa_don')->where('hoa_don_id', $id)
        ->where('hoi_vien_id', $hoiVien->hoi_vien_id)->first();
    if (!$hoaDon) return redirect('/user/payments')->with('error', 'Không tìm thấy hóa đơn của bạn.');
    if (!in_array(mb_strtolower(trim($hoaDon->trang_thai ?? ''), 'UTF-8'), ['chờ thanh toán','cho thanh toan','cho_thanh_toan','pending'], true)) {
        return redirect('/user/payments')->with('error', 'Hóa đơn này không còn chờ thanh toán.');
    }

    $hoaDonChoThanhToan = DB::table('hoa_don')->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->whereIn('trang_thai', ['chờ thanh toán','cho thanh toan','cho_thanh_toan','pending'])
        ->orderBy('ngay_lap')->get();
    $hoaDonIds = $hoaDonChoThanhToan->pluck('hoa_don_id');
    $chiTiet = DB::table('chi_tiet_hoa_don')
        ->leftJoin('goi_tap', 'chi_tiet_hoa_don.goi_tap_id', '=', 'goi_tap.goi_tap_id')
        ->leftJoin('goi_pt', 'chi_tiet_hoa_don.goi_pt_id', '=', 'goi_pt.goi_pt_id')
        ->whereIn('chi_tiet_hoa_don.hoa_don_id', $hoaDonIds)
        ->select('chi_tiet_hoa_don.*', 'goi_tap.ten_goi', 'goi_pt.ten_goi_pt')
        ->get()->groupBy('hoa_don_id');

    return view('user.payment-checkout', compact('hoaDon', 'hoaDonChoThanhToan', 'chiTiet'));
})->middleware(NoCache::class);

Route::post('/user/payments/checkout', function () use ($xuLyGoiTapCuaHoiVien) {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 3) return redirect('/' . session('dashboard_path'));

    $nguoiDungId = session('user')->nguoi_dung_id;
    $hoiVien = DB::table('hoi_vien')->where('nguoi_dung_id', $nguoiDungId)->first();
    if (!$hoiVien) return redirect('/user/payments')->with('error', 'Không tìm thấy hồ sơ hội viên.');

    $xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);
    $mode = request('mode', 'single');
    $invoiceId = (int) request('invoice_id');
    $method = request('phuong_thuc_thanh_toan');
    if (!in_array($mode, ['single', 'all'], true) || !in_array($method, ['Tiền mặt', 'Chuyển khoản', 'Thẻ'], true)) {
        return back()->withInput()->with('error', 'Vui lòng chọn cách thanh toán và phương thức hợp lệ.');
    }

    $pending = DB::table('hoa_don')->where('hoi_vien_id', $hoiVien->hoi_vien_id)
        ->whereIn('trang_thai', ['chờ thanh toán','cho thanh toan','cho_thanh_toan','pending']);
    if ($mode === 'single') $pending->where('hoa_don_id', $invoiceId);
    $invoices = $pending->get();
    if ($invoices->isEmpty()) return redirect('/user/payments')->with('error', 'Không có hóa đơn nào đang chờ thanh toán.');

    $paidIds = [];
    DB::transaction(function () use ($invoices, $method, &$paidIds) {
        foreach ($invoices as $hd) {
            $updated = DB::table('hoa_don')->where('hoa_don_id', $hd->hoa_don_id)
                ->whereIn('trang_thai', ['chờ thanh toán','cho thanh toan','cho_thanh_toan','pending'])
                ->update(['trang_thai' => 'đã thanh toán', 'phuong_thuc_thanh_toan' => $method]);
            if (!$updated) continue;
            $paidIds[] = $hd->hoa_don_id;
            $details = DB::table('chi_tiet_hoa_don')->where('hoa_don_id', $hd->hoa_don_id)->get();
            $invoiceDate = \Carbon\Carbon::parse($hd->ngay_lap)->toDateString();
            foreach ($details as $detail) {
                if (!empty($detail->goi_tap_id)) {
                    $reg = DB::table('dang_ky_goi_tap')->where('hoi_vien_id', $hd->hoi_vien_id)
                        ->where('goi_tap_id', $detail->goi_tap_id)->whereDate('ngay_bat_dau', $invoiceDate)
                        ->whereIn('trang_thai', ['không hoạt động','chờ thanh toán','cho thanh toan','cho_thanh_toan','pending'])
                        ->orderByDesc('dang_ky_goi_tap_id')->first();
                    if ($reg) DB::table('dang_ky_goi_tap')->where('dang_ky_goi_tap_id', $reg->dang_ky_goi_tap_id)->update(['trang_thai' => 'đang hoạt động']);
                }
                if (!empty($detail->goi_pt_id)) {
                    $reg = DB::table('dang_ky_goi_pt')->where('hoi_vien_id', $hd->hoi_vien_id)
                        ->where('goi_pt_id', $detail->goi_pt_id)->whereDate('ngay_bat_dau', $invoiceDate)
                        ->whereIn('trang_thai', ['không hoạt động','chờ thanh toán','cho thanh toan','cho_thanh_toan','pending'])
                        ->orderByDesc('dang_ky_goi_pt_id')->first();
                    if ($reg) DB::table('dang_ky_goi_pt')->where('dang_ky_goi_pt_id', $reg->dang_ky_goi_pt_id)->update(['trang_thai' => 'đang hoạt động']);
                }
            }
        }
    });

    if (empty($paidIds)) return redirect('/user/payments')->with('error', 'Hóa đơn đã được xử lý trước đó.');
    $tongTien = DB::table('hoa_don')->whereIn('hoa_don_id', $paidIds)->sum('tong_tien');
    return view('user.payment-success', ['soHoaDon' => count($paidIds), 'tongTien' => $tongTien, 'phuongThuc' => $method]);
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
| LỊCH SỬ HOẠT ĐỘNG HỘI VIÊN
|--------------------------------------------------------------------------
*/
Route::get('/user/history', function () {
    if (!session()->has('user')) return redirect('/login');
    if ((int) session('role_id') !== 3) return redirect('/' . session('dashboard_path', 'user/dashboard'));

    $userId = session('user')->nguoi_dung_id;
    $member = DB::table('hoi_vien')->where('nguoi_dung_id', $userId)->first();
    if (!$member) return redirect('/user/dashboard')->with('error', 'Không tìm thấy hồ sơ hội viên.');

    $invoices = DB::table('hoa_don')->where('hoi_vien_id', $member->hoi_vien_id)->orderByDesc('ngay_lap')->limit(30)->get();
    $checkIns = DB::table('check_in')->where('hoi_vien_id', $member->hoi_vien_id)->orderByDesc('thoi_gian')->limit(30)->get();
    $ptSchedules = DB::table('lich_pt')->leftJoin('huan_luyen_vien', 'lich_pt.pt_id', '=', 'huan_luyen_vien.pt_id')
        ->leftJoin('nguoi_dung', 'huan_luyen_vien.nguoi_dung_id', '=', 'nguoi_dung.nguoi_dung_id')
        ->where('lich_pt.hoi_vien_id', $member->hoi_vien_id)
        ->select('lich_pt.*', 'nguoi_dung.ho_ten as ten_pt')->orderByDesc('lich_pt.thoi_gian_bat_dau')->limit(30)->get();
    $gymPackages = DB::table('dang_ky_goi_tap')->join('goi_tap', 'dang_ky_goi_tap.goi_tap_id', '=', 'goi_tap.goi_tap_id')
        ->where('dang_ky_goi_tap.hoi_vien_id', $member->hoi_vien_id)
        ->select('dang_ky_goi_tap.*', 'goi_tap.ten_goi')->orderByDesc('dang_ky_goi_tap.dang_ky_goi_tap_id')->limit(20)->get();
    $ptPackages = DB::table('dang_ky_goi_pt')->join('goi_pt', 'dang_ky_goi_pt.goi_pt_id', '=', 'goi_pt.goi_pt_id')
        ->where('dang_ky_goi_pt.hoi_vien_id', $member->hoi_vien_id)
        ->select('dang_ky_goi_pt.*', 'goi_pt.ten_goi_pt')->orderByDesc('dang_ky_goi_pt.dang_ky_goi_pt_id')->limit(20)->get();

    return view('user.history', compact('invoices', 'checkIns', 'ptSchedules', 'gymPackages', 'ptPackages'));
})->middleware(NoCache::class)->name('user.history');

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

Route::get('/staff/contacts', function () {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $lienHe = DB::table('lien_he')
        ->leftJoin(
            'nhan_vien',
            'lien_he.nhan_vien_id',
            '=',
            'nhan_vien.nhan_vien_id'
        )
        ->leftJoin(
            'nguoi_dung as nd_nv',
            'nhan_vien.nguoi_dung_id',
            '=',
            'nd_nv.nguoi_dung_id'
        )
        ->select(
            'lien_he.*',
            'nd_nv.ho_ten as ten_nhan_vien'
        )
        ->orderByDesc('lien_he.tao_luc')
        ->get();

    return view('staff.contacts', compact('lienHe'));

})->middleware(NoCache::class);

Route::post('/staff/contacts/{id}/update', function (Request $request, $id) {

    if (!session()->has('user')) {
        return redirect('/login');
    }

    if (session('role_id') != 2) {
        return redirect('/' . session('dashboard_path'));
    }

    $request->validate([
        'trang_thai' => 'required|string|max:30',
        'phan_hoi' => 'nullable|string|max:2000',
    ]);

    $nhanVien = DB::table('nhan_vien')
        ->where(
            'nguoi_dung_id',
            session('user')->nguoi_dung_id
        )
        ->first();

    $duLieu = [
        'trang_thai' => $request->trang_thai,
        'phan_hoi' => $request->phan_hoi,
    ];

    if (
        $request->trang_thai != 'chưa xử lý' &&
        $nhanVien
    ) {
        $duLieu['nhan_vien_id'] = $nhanVien->nhan_vien_id;
        $duLieu['xu_ly_luc'] = now();
    }

    DB::table('lien_he')
        ->where('lien_he_id', $id)
        ->update($duLieu);

    return redirect('/staff/contacts')
        ->with('success', 'Đã cập nhật xử lý liên hệ.');

})->middleware(NoCache::class);

// ================================
// HỒ SƠ NHÂN VIÊN
// ================================
Route::get('/staff/profile', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 2) {
        return redirect('/' . ltrim(
            session('dashboard_path', 'user/dashboard'),
            '/'
        ));
    }

    $userSession = session('user');

    $userId = $userSession->nguoi_dung_id
        ?? $userSession->id
        ?? null;

    if (!$userId) {
        return redirect('/login')->with('error', 'Không tìm thấy tài khoản.');
    }

    $user = DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $userId)
        ->first();

    $nhanVien = DB::table('nhan_vien')
        ->where('nguoi_dung_id', $userId)
        ->first();

    if (!$user || !$nhanVien) {
        abort(404, 'Không tìm thấy hồ sơ nhân viên.');
    }

    return view('staff.profile', compact('user', 'nhanVien'));
});

// ================================
// HỒ SƠ HUẤN LUYỆN VIÊN
// ================================

Route::get('/trainer/profile', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 4) {
        return redirect('/' . ltrim(
            session('dashboard_path', 'user/dashboard'),
            '/'
        ));
    }

    $userSession = session('user');

    $userId = $userSession->nguoi_dung_id
        ?? $userSession->id
        ?? null;

    if (!$userId) {
        return redirect('/login')
            ->with('error', 'Không tìm thấy tài khoản.');
    }

    // Lấy tài khoản đăng nhập
    $user = DB::table('nguoi_dung')
        ->where('nguoi_dung_id', $userId)
        ->first();

    // Lấy hồ sơ huấn luyện viên
    $huanLuyenVien = DB::table('huan_luyen_vien')
        ->where('nguoi_dung_id', $userId)
        ->first();

    if (!$user || !$huanLuyenVien) {
        abort(404, 'Không tìm thấy hồ sơ huấn luyện viên.');
    }

    return view('trainer.profile', compact(
        'user',
        'huanLuyenVien'
    ));
});


    // ADMIN - QUẢN LÝ GÓI PT
Route::get('/admin/pt-packages', function () {
    if (!session()->has('user')) return redirect('/login');
    if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
    $keyword = trim(request('q',''));
    $query = DB::table('goi_pt');
    if ($keyword !== '') $query->where('ten_goi_pt','like','%'.$keyword.'%');
    $packages = $query->orderByDesc('goi_pt_id')->get();
    return view('admin.pt-packages', compact('packages','keyword'));
});
Route::get('/admin/pt-packages/create', function () {
    if (!session()->has('user')) return redirect('/login');
    if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
    return view('admin.pt-package-create');
});
Route::post('/admin/pt-packages', function (\Illuminate\Http\Request $request) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $data = $request->validate([
        'ten_goi_pt' => 'required|string|max:100',
        'gia' => 'required|numeric|min:0',
        'so_buoi' => 'required|integer|min:1',
        'thoi_han_ngay' => 'required|integer|min:1',
        'trang_thai' => 'required|in:hoat_dong,tam_ngung',
        'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'anh.image' => 'File tải lên phải là hình ảnh.',
        'anh.mimes' => 'Ảnh chỉ được có định dạng JPG, PNG hoặc WEBP.',
        'anh.max' => 'Ảnh không được vượt quá 2 MB.',
    ]);

    if ($request->hasFile('anh')) {
        $data['anh'] = $request->file('anh')->store('pt-packages', 'public');
    }

    DB::table('goi_pt')->insert($data);

    return redirect('/admin/pt-packages')
        ->with('success', 'Đã thêm gói PT.');
});
Route::get('/admin/pt-packages/{id}/edit', function ($id) {
    if (!session()->has('user')) return redirect('/login');
    if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
    $package=DB::table('goi_pt')->where('goi_pt_id',$id)->first();
    if (!$package) return redirect('/admin/pt-packages')->with('error','Không tìm thấy gói PT.');
    return view('admin.pt-package-edit',compact('package'));
});
Route::post('/admin/pt-packages/{id}/update', function (\Illuminate\Http\Request $request, $id) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $package = DB::table('goi_pt')->where('goi_pt_id', $id)->first();

    if (!$package) {
        return redirect('/admin/pt-packages')
            ->with('error', 'Không tìm thấy gói PT.');
    }

    $data = $request->validate([
        'ten_goi_pt' => 'required|string|max:100',
        'gia' => 'required|numeric|min:0',
        'so_buoi' => 'required|integer|min:1',
        'thoi_han_ngay' => 'required|integer|min:1',
        'trang_thai' => 'required|in:hoat_dong,tam_ngung',
        'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'anh.image' => 'File tải lên phải là hình ảnh.',
        'anh.mimes' => 'Ảnh chỉ được có định dạng JPG, PNG hoặc WEBP.',
        'anh.max' => 'Ảnh không được vượt quá 2 MB.',
    ]);

    if ($request->hasFile('anh')) {
        $newImage = $request->file('anh')->store('pt-packages', 'public');
        $data['anh'] = $newImage;

        if (!empty($package->anh)) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($package->anh);
        }
    } else {
        unset($data['anh']);
    }

    DB::table('goi_pt')
        ->where('goi_pt_id', $id)
        ->update($data);

    return redirect('/admin/pt-packages')
        ->with('success', 'Đã cập nhật gói PT.');
});
Route::post('/admin/pt-packages/{id}/delete', function ($id) {
    if (!session()->has('user')) return redirect('/login');
    if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
    if (!DB::table('goi_pt')->where('goi_pt_id',$id)->exists()) return redirect('/admin/pt-packages')->with('error','Không tìm thấy gói PT.');
    if (DB::table('dang_ky_goi_pt')->where('goi_pt_id',$id)->exists() ||
        DB::table('chi_tiet_hoa_don')->where('goi_pt_id',$id)->exists()) {
        return redirect('/admin/pt-packages')->with('error','Gói PT đã có đăng ký hoặc hóa đơn. Hãy chuyển sang Tạm ngưng để giữ lịch sử.');
    }
    DB::table('goi_pt')->where('goi_pt_id',$id)->delete();
    return redirect('/admin/pt-packages')->with('success','Đã xóa gói PT.');
});
        
// =====================================================
// ADMIN - QUẢN LÝ LỚP TẬP
// =====================================================

Route::get('/admin/classes', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }
    if (session('role_id') != 1) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $lopTap = DB::table('lop_tap')
        ->leftJoin('dang_ky_lop', function ($join) {
            $join->on('lop_tap.lop_tap_id', '=', 'dang_ky_lop.lop_tap_id')
                 ->whereNotIn('dang_ky_lop.trang_thai', ['đã hủy', 'da_huy', 'hủy', 'huy']);
        })
        ->select('lop_tap.*', DB::raw('COUNT(dang_ky_lop.dang_ky_lop_id) as so_dang_ky'))
        ->groupBy(
            'lop_tap.lop_tap_id',
            'lop_tap.ten_lop',
            'lop_tap.mo_ta',
            'lop_tap.lich_tap',
            'lop_tap.suc_chua',
            'lop_tap.anh',
            'lop_tap.trang_thai'
        )
        ->orderByDesc('lop_tap.lop_tap_id')
        ->get();

    return view('admin.classes', compact('lopTap'));
});

Route::get('/admin/classes/create', function () {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));
    return view('admin.class-create');
});

Route::post('/admin/classes/create', function (Request $request) {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

    $data = $request->validate([
        'ten_lop' => 'required|string|max:100',
        'mo_ta' => 'nullable|string',
        'lich_tap' => 'nullable|string|max:255',
        'suc_chua' => 'nullable|integer|min:1',
        'trang_thai' => 'required|string|max:30',
        'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    ]);

    unset($data['anh']);
    if ($request->hasFile('anh')) {
        $name = 'class_' . time() . '.' . $request->file('anh')->extension();
        $request->file('anh')->move(public_path('img'), $name);
        $data['anh'] = 'img/' . $name;
    }

    DB::table('lop_tap')->insert($data);

    return redirect('/admin/classes')->with('success', 'Đã thêm lớp tập thành công.');
});

Route::get('/admin/classes/{id}/edit', function ($id) {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

    $lop = DB::table('lop_tap')->where('lop_tap_id', $id)->first();
    if (!$lop) return redirect('/admin/classes')->with('error', 'Không tìm thấy lớp tập.');

    return view('admin.class-edit', compact('lop'));
});

Route::post('/admin/classes/{id}/edit', function (Request $request, $id) {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

    $lop = DB::table('lop_tap')->where('lop_tap_id', $id)->first();
    if (!$lop) return redirect('/admin/classes')->with('error', 'Không tìm thấy lớp tập.');

    $data = $request->validate([
        'ten_lop' => 'required|string|max:100',
        'mo_ta' => 'nullable|string',
        'lich_tap' => 'nullable|string|max:255',
        'suc_chua' => 'nullable|integer|min:1',
        'trang_thai' => 'required|string|max:30',
        'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    ]);

    unset($data['anh']);
    if ($request->hasFile('anh')) {
        $name = 'class_' . time() . '.' . $request->file('anh')->extension();
        $request->file('anh')->move(public_path('img'), $name);
        $data['anh'] = 'img/' . $name;
    }

    DB::table('lop_tap')->where('lop_tap_id', $id)->update($data);

    return redirect('/admin/classes')->with('success', 'Đã cập nhật lớp tập.');
});

Route::post('/admin/classes/{id}/delete', function ($id) {
    if (!session()->has('user')) return redirect('/login');
    if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

    $lop = DB::table('lop_tap')->where('lop_tap_id', $id)->first();
    if (!$lop) return redirect('/admin/classes')->with('error', 'Không tìm thấy lớp tập.');

    $coDangKy = DB::table('dang_ky_lop')->where('lop_tap_id', $id)->exists();
    if ($coDangKy) {
        return redirect('/admin/classes')->with('error', 'Lớp đã có đăng ký; hãy đổi trạng thái sang ngừng hoạt động để giữ lịch sử.');
    }

    DB::table('lop_tap')->where('lop_tap_id', $id)->delete();
    return redirect('/admin/classes')->with('success', 'Đã xóa lớp tập.');
});




// HỦY GÓI PT - HỘI VIÊN
Route::post('/user/pt-packages/{id}/cancel', function ($id) {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    if ((int) session('role_id') !== 3) {
        return redirect('/' . session('dashboard_path', 'user/dashboard'));
    }

    $nguoiDungId = session('user')->nguoi_dung_id;

    $hoiVien = DB::table('hoi_vien')
        ->where('nguoi_dung_id', $nguoiDungId)
        ->first();

    if (!$hoiVien) {
        return redirect('/user/dashboard')
            ->with('error', 'Không tìm thấy hồ sơ hội viên.');
    }

    // Đồng bộ các đăng ký PT đã quá hạn trước khi xử lý yêu cầu.
    app(\App\Services\PackageLifecycleService::class)
        ->xuLyGoiPtCuaHoiVien($hoiVien->hoi_vien_id);

    // Lấy đúng đăng ký của hội viên, không nối hóa đơn chỉ theo mã gói
    // vì một gói có thể được nhiều hội viên đăng ký nhiều lần.
    $dangKy = DB::table('dang_ky_goi_pt')
        ->join('goi_pt', 'dang_ky_goi_pt.goi_pt_id', '=', 'goi_pt.goi_pt_id')
        ->where('dang_ky_goi_pt.dang_ky_goi_pt_id', $id)
        ->where('dang_ky_goi_pt.hoi_vien_id', $hoiVien->hoi_vien_id)
        ->select('dang_ky_goi_pt.*', 'goi_pt.ten_goi_pt')
        ->first();

    if (!$dangKy) {
        return redirect('/user/dashboard')
            ->with('error', 'Không tìm thấy đăng ký gói PT.');
    }

    $trangThaiGoiHienTai = mb_strtolower(trim($dangKy->trang_thai ?? ''));
    if (in_array($trangThaiGoiHienTai, ['đã hủy', 'đã huỷ', 'da huy', 'da_huy'], true)) {
        return redirect('/user/dashboard')
            ->with('info', 'Gói PT này đã được hủy. Đăng ký quá 72 giờ chưa thanh toán sẽ tự động chuyển sang Đã hủy.');
    }

    // Tìm hóa đơn chờ thanh toán của hội viên cho gói PT này.
    $hoaDon = DB::table('chi_tiet_hoa_don')
        ->join('hoa_don', 'chi_tiet_hoa_don.hoa_don_id', '=', 'hoa_don.hoa_don_id')
        ->where('chi_tiet_hoa_don.goi_pt_id', $dangKy->goi_pt_id)
        ->where('hoa_don.hoi_vien_id', $hoiVien->hoi_vien_id)
        ->whereDate('hoa_don.ngay_lap', '>=', $dangKy->ngay_bat_dau)
        ->select('hoa_don.hoa_don_id', 'hoa_don.ngay_lap', 'hoa_don.trang_thai')
        ->orderByDesc('hoa_don.ngay_lap')
        ->first();

    $trangThaiGoi = mb_strtolower(trim($dangKy->trang_thai ?? ''));
    $trangThaiHoaDon = mb_strtolower(trim($hoaDon->trang_thai ?? ''));

    $trangThaiChoThanhToan = in_array($trangThaiHoaDon, [
        'chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending'
    ], true);
    $trangThaiDaThanhToan = in_array($trangThaiHoaDon, [
        'đã thanh toán', 'da thanh toan', 'da_thanh_toan', 'paid'
    ], true);
    $goiChuaXuLy = in_array($trangThaiGoi, [
        'không hoạt động', 'khong hoat dong', 'khong_hoat_dong', 'inactive'
    ], true);
    $goiDangHoatDong = in_array($trangThaiGoi, [
        'đang hoạt động', 'dang_hoat_dong', 'hoạt_động', 'hoat_dong'
    ], true);

    // Cho hủy khi đăng ký đang hoạt động hoặc chưa hoạt động vì chờ thanh toán.
    if (!$goiDangHoatDong && !($goiChuaXuLy && $trangThaiChoThanhToan)) {
        return redirect('/user/dashboard')
            ->with('error', 'Gói PT không còn ở trạng thái có thể hủy.');
    }

    if ($trangThaiDaThanhToan) {
        return redirect('/user/dashboard')
            ->with('error', 'Gói PT đã thanh toán nên không thể hủy tại đây. Vui lòng liên hệ nhân viên.');
    }

    if (!$trangThaiChoThanhToan || !$hoaDon) {
        return redirect('/user/dashboard')
            ->with('error', 'Không tìm thấy hóa đơn đang chờ thanh toán của gói PT này.');
    }


    DB::transaction(function () use ($id, $dangKy, $hoaDon, $nguoiDungId) {
        DB::table('chi_tiet_hoa_don')
            ->where('hoa_don_id', $hoaDon->hoa_don_id)
            ->where('goi_pt_id', $dangKy->goi_pt_id)
            ->delete();

        $conChiTiet = DB::table('chi_tiet_hoa_don')
            ->where('hoa_don_id', $hoaDon->hoa_don_id)
            ->exists();

        if (!$conChiTiet) {
            // Giữ lại hóa đơn để lịch sử giao dịch không bị mất.
            DB::table('hoa_don')
                ->where('hoa_don_id', $hoaDon->hoa_don_id)
                ->update(['trang_thai' => 'đã hủy']);
        } else {
            $tongTienConLai = DB::table('chi_tiet_hoa_don')
                ->where('hoa_don_id', $hoaDon->hoa_don_id)
                ->sum('thanh_tien');

            DB::table('hoa_don')
                ->where('hoa_don_id', $hoaDon->hoa_don_id)
                ->update(['tong_tien' => $tongTienConLai]);
        }

        DB::table('dang_ky_goi_pt')
            ->where('dang_ky_goi_pt_id', $id)
            ->where('hoi_vien_id', DB::table('hoi_vien')->where('nguoi_dung_id', $nguoiDungId)->value('hoi_vien_id'))
            ->update(['trang_thai' => 'đã hủy']);

        DB::table('thong_bao')->insert([
            'nguoi_dung_id' => $nguoiDungId,
            'tieu_de' => 'Hủy gói PT thành công',
            'noi_dung' => 'Bạn đã hủy đăng ký gói PT ' . $dangKy->ten_goi_pt . '.',
            'da_doc' => 0,
            'tao_luc' => now(),
        ]);
    });

    return redirect('/user/dashboard')
        ->with('success', 'Đã hủy gói PT ' . $dangKy->ten_goi_pt . ' thành công.');
})->middleware(NoCache::class);
