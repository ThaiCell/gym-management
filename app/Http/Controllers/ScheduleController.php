<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ScheduleController extends Controller
{
    /** Route: GET /admin/schedules */
    public function getAdminSchedules()
    {
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
    }

    /** Route: GET /admin/schedules/{id} */
    public function getAdminSchedulesId($id)
    {
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
    }

    /** Route: GET /user/pt-schedule */
    public function getUserPtSchedule()
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
    }

    /** Route: POST /user/pt-schedule/{id}/cancel */
    public function postUserPtScheduleIdCancel($id)
    {
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
    }

    /** Route: GET /user/pt-schedule/create */
    public function getUserPtScheduleCreate()
    {
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
    }

    /** Route: POST /user/pt-schedule/create */
    public function postUserPtScheduleCreate()
    {
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
    }

    /** Route: POST /trainer/pt-schedule/{id}/complete */
    public function postTrainerPtScheduleIdComplete($id)
    {
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
    }

}
