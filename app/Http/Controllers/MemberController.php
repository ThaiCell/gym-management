<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /** Route: GET /admin/members */
    public function getAdminMembers()
    {
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
    }

    /** Route: GET /admin/members/create */
    public function getAdminMembersCreate()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if (session('role_id') != 1) {
                return redirect('/' . session('dashboard_path', 'user/dashboard'));
            }

            return view('admin.member-create');
    }

    /** Route: POST /admin/members/create */
    public function postAdminMembersCreate(Request $request)
    {
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
    }

    /** Route: GET /admin/members/{id} */
    public function getAdminMembersId($id)
    {
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
    }

    /** Route: GET /admin/members/{id}/edit */
    public function getAdminMembersIdEdit($id)
    {
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
    }

    /** Route: POST /admin/members/{id}/edit */
    public function postAdminMembersIdEdit($id)
    {
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
    }

    /** Route: POST /admin/members/{id}/toggle-status */
    public function postAdminMembersIdToggleStatus($id)
    {
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
    }

    /** Route: POST /admin/members/{id}/delete */
    public function postAdminMembersIdDelete($id)
    {
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
    }

    /** Route: GET /staff/members */
    public function getStaffMembers(Request $request)
    {
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
    }

    /** Route: GET /staff/members/create */
    public function getStaffMembersCreate()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if (session('role_id') != 2) {
                return redirect('/' . session('dashboard_path'));
            }

            return view('staff.member-create');
    }

    /** Route: POST /staff/members */
    public function postStaffMembers(Request $request)
    {
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
    }

    /** Route: GET /staff/members/{id}/edit */
    public function getStaffMembersIdEdit($id)
    {
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
    }

    /** Route: POST /staff/members/{id}/update */
    public function postStaffMembersIdUpdate(Request $request, $id)
    {
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
    }

    /** Route: POST /staff/members/{id}/delete */
    public function postStaffMembersIdDelete($id)
    {
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
    }

}
