<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PackageController extends Controller
{
    /** Route: GET /packages */
    public function getPackages()
    {
            $goiTap = DB::table('goi_tap')
                ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
                ->orderBy('gia', 'asc')
                ->get();

            return view('user.packages', compact('goiTap'));
    }

    /** Route: POST /packages/register/{goiTapId} */
    public function postPackagesRegisterGoiTapId($goiTapId)
    {
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
    }

    /** Route: GET /admin/packages */
    public function getAdminPackages()
    {
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
    }

    /** Route: GET /admin/packages/create */
    public function getAdminPackagesCreate()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if (session('role_id') != 1) {
                return redirect('/' . session('dashboard_path', 'user/dashboard'));
            }

            return view('admin.package-create');
    }

    /** Route: POST /admin/packages */
    public function postAdminPackages()
    {
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
    }

    /** Route: GET /admin/packages/{id}/edit */
    public function getAdminPackagesIdEdit($id)
    {
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
    }

    /** Route: POST /admin/packages/{id}/update */
    public function postAdminPackagesIdUpdate($id)
    {
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
    }

    /** Route: POST /admin/packages/{id}/toggle-status */
    public function postAdminPackagesIdToggleStatus($id)
    {
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
    }

    /** Route: POST /admin/packages/{id}/delete */
    public function postAdminPackagesIdDelete($id)
    {
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
    }

    /** Route: GET /admin/packages/{id} */
    public function getAdminPackagesId($id)
    {
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
    }

    /** Route: POST /user/packages/{id}/cancel */
    public function postUserPackagesIdCancel($id)
    {
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
    }

}
