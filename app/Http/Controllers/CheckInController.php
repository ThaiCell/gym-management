<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckInController extends Controller
{
    /** Route: GET /staff/check-in */
    public function getStaffCheckIn()
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
    }

    /** Route: POST /staff/check-in */
    public function postStaffCheckIn()
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
    }

}
