<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    /** Route: GET /trainers */
    public function getTrainers()
    {
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
    }

    /** Route: GET /admin/trainers */
    public function getAdminTrainers()
    {
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
    }

    /** Route: GET /admin/trainers/create */
    public function getAdminTrainersCreate()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if ((int) session('role_id') !== 1) {
                return redirect('/' . session('dashboard_path', 'user/dashboard'));
            }

            return view('admin.trainer-create');
    }

    /** Route: POST /admin/trainers/create */
    public function postAdminTrainersCreate()
    {
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
    }

    /** Route: GET /admin/trainers/{id} */
    public function getAdminTrainersId($id)
    {
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
    }

    /** Route: POST /admin/trainers/{id}/delete */
    public function postAdminTrainersIdDelete($id)
    {
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
    }

    /** Route: GET /admin/trainers/{id}/edit */
    public function getAdminTrainersIdEdit($id)
    {
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
    }

    /** Route: POST /admin/trainers/{id}/edit */
    public function postAdminTrainersIdEdit($id)
    {
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
    }

    /** Route: POST /admin/trainers/{id}/toggle-status */
    public function postAdminTrainersIdToggleStatus($id)
    {
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
    }

    /** Route: GET /trainer/profile */
    public function getTrainerProfile()
    {
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
    }

}
