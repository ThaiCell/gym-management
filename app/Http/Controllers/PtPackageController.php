<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PtPackageController extends Controller
{
    /** Route: GET /pt-packages */
    public function getPtPackages()
    {
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
    }

    /** Route: GET /pt-packages/{id}/choose */
    public function getPtPackagesIdChoose($id)
    {
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
    }

    /** Route: POST /pt-packages/{id}/register */
    public function postPtPackagesIdRegister($id)
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
    }

    /** Route: GET /admin/pt-packages */
    public function getAdminPtPackages()
    {
            if (!session()->has('user')) return redirect('/login');
            if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
            $keyword = trim(request('q',''));
            $query = DB::table('goi_pt');
            if ($keyword !== '') $query->where('ten_goi_pt','like','%'.$keyword.'%');
            $packages = $query->orderByDesc('goi_pt_id')->get();
            return view('admin.pt-packages', compact('packages','keyword'));
    }

    /** Route: GET /admin/pt-packages/create */
    public function getAdminPtPackagesCreate()
    {
            if (!session()->has('user')) return redirect('/login');
            if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
            return view('admin.pt-package-create');
    }

    /** Route: POST /admin/pt-packages */
    public function postAdminPtPackages(\Illuminate\Http\Request $request)
    {
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
    }

    /** Route: GET /admin/pt-packages/{id}/edit */
    public function getAdminPtPackagesIdEdit($id)
    {
            if (!session()->has('user')) return redirect('/login');
            if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
            $package=DB::table('goi_pt')->where('goi_pt_id',$id)->first();
            if (!$package) return redirect('/admin/pt-packages')->with('error','Không tìm thấy gói PT.');
            return view('admin.pt-package-edit',compact('package'));
    }

    /** Route: POST /admin/pt-packages/{id}/update */
    public function postAdminPtPackagesIdUpdate(\Illuminate\Http\Request $request, $id)
    {
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
    }

    /** Route: POST /admin/pt-packages/{id}/delete */
    public function postAdminPtPackagesIdDelete($id)
    {
            if (!session()->has('user')) return redirect('/login');
            if ((int)session('role_id') !== 1) return redirect('/'.session('dashboard_path','user/dashboard'));
            if (!DB::table('goi_pt')->where('goi_pt_id',$id)->exists()) return redirect('/admin/pt-packages')->with('error','Không tìm thấy gói PT.');
            if (DB::table('dang_ky_goi_pt')->where('goi_pt_id',$id)->exists() ||
                DB::table('chi_tiet_hoa_don')->where('goi_pt_id',$id)->exists()) {
                return redirect('/admin/pt-packages')->with('error','Gói PT đã có đăng ký hoặc hóa đơn. Hãy chuyển sang Tạm ngưng để giữ lịch sử.');
            }
            DB::table('goi_pt')->where('goi_pt_id',$id)->delete();
            return redirect('/admin/pt-packages')->with('success','Đã xóa gói PT.');
    }

    /** Route: POST /user/pt-packages/{id}/cancel */
    public function postUserPtPackagesIdCancel($id)
    {
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

            if (in_array(mb_strtolower(trim($dangKy->trang_thai ?? '')), ['đã hủy', 'đã huỷ', 'da huy', 'da_huy'], true)) {
                return redirect('/user/dashboard')
                    ->with('info', 'Gói PT đã được hủy hoặc tự động hủy do quá 72 giờ chưa thanh toán.');
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
    }

}
