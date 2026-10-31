<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /** Route: GET /dashboard */
    public function getDashboard()
    {
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
    }

    /** Route: GET /staff/dashboard */
    public function getStaffDashboard()
    {
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
    }

    /** Route: GET /user/dashboard */
    public function getUserDashboard()
    {
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

            app(\App\Services\PackageLifecycleService::class)->xuLyGoiTapCuaHoiVien($hoiVien->hoi_vien_id);
            app(\App\Services\PackageLifecycleService::class)->xuLyGoiPtCuaHoiVien($hoiVien->hoi_vien_id);


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
    }

    /** Route: GET /trainer/dashboard */
    public function getTrainerDashboard()
    {
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
    }

}
