<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /** Route: GET /user/profile */
    public function getUserProfile()
    {
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
    }

    /** Route: POST /user/profile */
    public function postUserProfile()
    {
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
    }

    /** Route: GET /user/history */
    public function getUserHistory()
    {
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
    }

    /** Route: GET /staff/profile */
    public function getStaffProfile()
    {
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
    }

}
