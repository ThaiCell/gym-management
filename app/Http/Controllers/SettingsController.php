<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /** Route: GET /admin/settings */
    public function getAdminSettings()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if ((int) session('role_id') !== 1) {
                return redirect('/' . session('dashboard_path', 'user/dashboard'));
            }

            $settings = DB::table('cai_dat')
                ->pluck('gia_tri', 'khoa');

            return view('admin.settings', compact('settings'));
    }

    /** Route: POST /admin/settings/gym */
    public function postAdminSettingsGym(Request $request)
    {
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
    }

    /** Route: POST /admin/settings/password */
    public function postAdminSettingsPassword(Request $request)
    {
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
    }

    /** Route: GET /admin/password-resets */
    public function getAdminPasswordResets()
    {
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
    }

    /** Route: POST /admin/password-resets/{id} */
    public function postAdminPasswordResetsId(Request $request, $id)
    {
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
    }

}
