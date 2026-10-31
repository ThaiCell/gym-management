<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{
    /** Route: GET /admin/payments */
    public function getAdminPayments()
    {
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
    }

    /** Route: GET /admin/payments/{id} */
    public function getAdminPaymentsId($id)
    {
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
    }

    /** Route: GET /staff/payments */
    public function getStaffPayments()
    {
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
    }

    /** Route: POST /staff/payments/{id}/confirm */
    public function postStaffPaymentsIdConfirm($id)
    {
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
    }

    /** Route: GET /user/payments */
    public function getUserPayments()
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


            // Tự động xóa gói/hóa đơn quá 72 giờ và cập nhật gói hết hạn.
            app(\App\Services\PackageLifecycleService::class)->xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);
            app(\App\Services\PackageLifecycleService::class)->xuLyGoiPtCuaHoiVien($hoiVien->hoi_vien_id);


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


    }

    /** Route: GET /user/payments/checkout/{id} */
    public function getUserPaymentsCheckoutId($id)
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 3) return redirect('/' . session('dashboard_path'));

            $nguoiDungId = session('user')->nguoi_dung_id;
            $hoiVien = DB::table('hoi_vien')->where('nguoi_dung_id', $nguoiDungId)->first();
            if (!$hoiVien) return redirect('/user/payments')->with('error', 'Không tìm thấy hồ sơ hội viên.');

            app(\App\Services\PackageLifecycleService::class)->xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);
            app(\App\Services\PackageLifecycleService::class)->xuLyGoiPtCuaHoiVien($hoiVien->hoi_vien_id);
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
    }

    /** Route: POST /user/payments/checkout */
    public function postUserPaymentsCheckout()
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 3) return redirect('/' . session('dashboard_path'));

            $nguoiDungId = session('user')->nguoi_dung_id;
            $hoiVien = DB::table('hoi_vien')->where('nguoi_dung_id', $nguoiDungId)->first();
            if (!$hoiVien) return redirect('/user/payments')->with('error', 'Không tìm thấy hồ sơ hội viên.');

            app(\App\Services\PackageLifecycleService::class)->xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);
            app(\App\Services\PackageLifecycleService::class)->xuLyGoiPtCuaHoiVien($hoiVien->hoi_vien_id);
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
    }

}
