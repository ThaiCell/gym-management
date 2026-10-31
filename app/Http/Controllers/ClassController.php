<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClassController extends Controller
{
    /** Route: GET /classes */
    public function getClasses()
    {
            // Danh sách lớp đang hoạt động
            $lopTap = DB::table('lop_tap')
                ->where(function ($query) {
                    $query->where('trang_thai', 'like', '%hoat_dong%')
                          ->orWhere('trang_thai', 'like', '%hoạt_động%');
                })
                ->orderBy('lop_tap_id', 'asc')
                ->get();


            // Mặc định chưa đăng nhập
            $hoiVien = null;
            $lopDaDangKy = [];
            $dangKyLop = [];


            // Nếu là hội viên
            if (
                session()->has('user') &&
                session('role_id') == 3
            ) {

                $hoiVien = DB::table('hoi_vien')
                    ->where(
                        'nguoi_dung_id',
                        session('user')->nguoi_dung_id
                    )
                    ->first();


                if ($hoiVien) {

                   $dangKyLop = DB::table('dang_ky_lop')
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
            ->where(
                'dang_ky_lop.trang_thai',
                '!=',
                'đã hủy'
            )
            ->select(
                'dang_ky_lop.dang_ky_lop_id',
                'dang_ky_lop.lop_tap_id',
                'dang_ky_lop.ngay_dang_ky',
                'dang_ky_lop.trang_thai',
                'lop_tap.ten_lop',
                'lop_tap.lich_tap'
            )
            ->orderByDesc('dang_ky_lop.ngay_dang_ky')
            ->get();

        $lopDaDangKy = $dangKyLop
            ->pluck('lop_tap_id')
            ->toArray();

                }

            }


            return view(
                'user.classes',
                compact(
                    'lopTap',
                    'lopDaDangKy',
                    'dangKyLop'
                )
            );
    }

    /** Route: POST /classes/register/{lopTapId} */
    public function postClassesRegisterLopTapId($lopTapId)
    {
            // Chưa đăng nhập
            if (!session()->has('user')) {

                return redirect('/login');

            }


            // Chỉ hội viên
            if (session('role_id') != 3) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Chỉ hội viên mới có thể đăng ký lớp tập.'
                    );

            }


            // Tìm hội viên
            $hoiVien = DB::table('hoi_vien')
                ->where(
                    'nguoi_dung_id',
                    session('user')->nguoi_dung_id
                )
                ->first();


            if (!$hoiVien) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Không tìm thấy hồ sơ hội viên.'
                    );

            }


            // Tìm lớp
            $lop = DB::table('lop_tap')
                ->where(
                    'lop_tap_id',
                    $lopTapId
                )
                ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
                ->first();


            if (!$lop) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Lớp tập không tồn tại hoặc đã ngừng hoạt động.'
                    );

            }


            // Kiểm tra đăng ký trùng
            $daDangKy = DB::table('dang_ky_lop')
                ->where(
                    'lop_tap_id',
                    $lop->lop_tap_id
                )
                ->where(
                    'hoi_vien_id',
                    $hoiVien->hoi_vien_id
                )
                ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
                ->exists();


            if ($daDangKy) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Bạn đã đăng ký lớp này rồi.'
                    );

            }


            // Kiểm tra sức chứa
            if ($lop->suc_chua) {

                $soNguoiDangKy = DB::table('dang_ky_lop')
                    ->where(
                        'lop_tap_id',
                        $lop->lop_tap_id
                    )
                    ->whereIn('trang_thai', ['hoạt_động', 'hoat_dong', 'đang hoạt động', 'dang_hoat_dong'])
                    ->count();


                if ($soNguoiDangKy >= $lop->suc_chua) {

                    return redirect('/classes')
                        ->with(
                            'error',
                            'Lớp tập đã đủ số lượng. Vui lòng chọn lớp khác.'
                        );

                }

            }


            // Tạo đăng ký
            DB::table('dang_ky_lop')
                ->insert([

                    'lop_tap_id' =>
                        $lop->lop_tap_id,

                    'hoi_vien_id' =>
                        $hoiVien->hoi_vien_id,

                    'ngay_dang_ky' =>
                        now(),

                    'trang_thai' =>
                        'đang hoạt động',

                ]);


            // Tạo thông báo
            DB::table('thong_bao')
                ->insert([

                    'nguoi_dung_id' =>
                        $hoiVien->nguoi_dung_id,

                    'tieu_de' =>
                        'Đăng ký lớp thành công',

                    'noi_dung' =>
                        'Bạn đã đăng ký lớp "'
                        . $lop->ten_lop
                        . '" thành công.',

                    'da_doc' =>
                        0,

                    'tao_luc' =>
                        now(),

                ]);


            return redirect('/classes')
                ->with(
                    'success',
                    'Đăng ký lớp "'
                    . $lop->ten_lop
                    . '" thành công!'
                );
    }

    /** Route: POST /classes/cancel/{id} */
    public function postClassesCancelId($id)
    {
            // Chưa đăng nhập
            if (!session()->has('user')) {

                return redirect('/login');

            }


            // Chỉ hội viên
            if (session('role_id') != 3) {

                return redirect('/classes');

            }


            // Tìm hội viên
            $hoiVien = DB::table('hoi_vien')
                ->where(
                    'nguoi_dung_id',
                    session('user')->nguoi_dung_id
                )
                ->first();


            if (!$hoiVien) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Không tìm thấy hồ sơ hội viên.'
                    );

            }


            // Tìm đăng ký
            $dangKy = DB::table('dang_ky_lop')
                ->join(
                    'lop_tap',
                    'dang_ky_lop.lop_tap_id',
                    '=',
                    'lop_tap.lop_tap_id'
                )
                ->where(
                    'dang_ky_lop.dang_ky_lop_id',
                    $id
                )
                ->where(
                    'dang_ky_lop.hoi_vien_id',
                    $hoiVien->hoi_vien_id
                )
                ->select(
                    'dang_ky_lop.*',
                    'lop_tap.ten_lop'
                )
                ->first();


            if (!$dangKy) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Không tìm thấy đăng ký lớp.'
                    );

            }


            // Chỉ hủy đăng ký đang hoạt động
            if (!in_array(
                strtolower(trim($dangKy->trang_thai)),
                [
                    'đang hoạt động',
                    'dang_hoat_dong',
                    'hoạt động',
                    'hoat_dong'
                ]
            )) {

                return redirect('/classes')
                    ->with(
                        'error',
                        'Đăng ký lớp này đã được xử lý.'
                    );

            }


            // Cập nhật trạng thái
            DB::table('dang_ky_lop')
                ->where(
                    'dang_ky_lop_id',
                    $id
                )
                ->update([

                    'trang_thai' =>
                        'đã hủy',

                ]);


            // Thông báo
            DB::table('thong_bao')
                ->insert([

                    'nguoi_dung_id' =>
                        $hoiVien->nguoi_dung_id,

                    'tieu_de' =>
                        'Hủy đăng ký lớp',

                    'noi_dung' =>
                        'Bạn đã hủy đăng ký lớp "'
                        . $dangKy->ten_lop
                        . '".',

                    'da_doc' =>
                        0,

                    'tao_luc' =>
                        now(),

                ]);


            return redirect('/classes')
                ->with(
                    'success',
                    'Đã hủy đăng ký lớp "'
                    . $dangKy->ten_lop
                    . '".'
                );
    }

    /** Route: GET /admin/classes */
    public function getAdminClasses()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }
            if (session('role_id') != 1) {
                return redirect('/' . session('dashboard_path', 'user/dashboard'));
            }

            $lopTap = DB::table('lop_tap')
                ->leftJoin('dang_ky_lop', function ($join) {
                    $join->on('lop_tap.lop_tap_id', '=', 'dang_ky_lop.lop_tap_id')
                         ->whereNotIn('dang_ky_lop.trang_thai', ['đã hủy', 'da_huy', 'hủy', 'huy']);
                })
                ->select('lop_tap.*', DB::raw('COUNT(dang_ky_lop.dang_ky_lop_id) as so_dang_ky'))
                ->groupBy(
                    'lop_tap.lop_tap_id',
                    'lop_tap.ten_lop',
                    'lop_tap.mo_ta',
                    'lop_tap.lich_tap',
                    'lop_tap.suc_chua',
                    'lop_tap.anh',
                    'lop_tap.trang_thai'
                )
                ->orderByDesc('lop_tap.lop_tap_id')
                ->get();

            return view('admin.classes', compact('lopTap'));
    }

    /** Route: GET /admin/classes/create */
    public function getAdminClassesCreate()
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));
            return view('admin.class-create');
    }

    /** Route: POST /admin/classes/create */
    public function postAdminClassesCreate(Request $request)
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

            $data = $request->validate([
                'ten_lop' => 'required|string|max:100',
                'mo_ta' => 'nullable|string',
                'lich_tap' => 'nullable|string|max:255',
                'suc_chua' => 'nullable|integer|min:1',
                'trang_thai' => 'required|string|max:30',
                'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            unset($data['anh']);
            if ($request->hasFile('anh')) {
                $name = 'class_' . time() . '.' . $request->file('anh')->extension();
                $request->file('anh')->move(public_path('img'), $name);
                $data['anh'] = 'img/' . $name;
            }

            DB::table('lop_tap')->insert($data);

            return redirect('/admin/classes')->with('success', 'Đã thêm lớp tập thành công.');
    }

    /** Route: GET /admin/classes/{id}/edit */
    public function getAdminClassesIdEdit($id)
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

            $lop = DB::table('lop_tap')->where('lop_tap_id', $id)->first();
            if (!$lop) return redirect('/admin/classes')->with('error', 'Không tìm thấy lớp tập.');

            return view('admin.class-edit', compact('lop'));
    }

    /** Route: POST /admin/classes/{id}/edit */
    public function postAdminClassesIdEdit(Request $request, $id)
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

            $lop = DB::table('lop_tap')->where('lop_tap_id', $id)->first();
            if (!$lop) return redirect('/admin/classes')->with('error', 'Không tìm thấy lớp tập.');

            $data = $request->validate([
                'ten_lop' => 'required|string|max:100',
                'mo_ta' => 'nullable|string',
                'lich_tap' => 'nullable|string|max:255',
                'suc_chua' => 'nullable|integer|min:1',
                'trang_thai' => 'required|string|max:30',
                'anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            unset($data['anh']);
            if ($request->hasFile('anh')) {
                $name = 'class_' . time() . '.' . $request->file('anh')->extension();
                $request->file('anh')->move(public_path('img'), $name);
                $data['anh'] = 'img/' . $name;
            }

            DB::table('lop_tap')->where('lop_tap_id', $id)->update($data);

            return redirect('/admin/classes')->with('success', 'Đã cập nhật lớp tập.');
    }

    /** Route: POST /admin/classes/{id}/delete */
    public function postAdminClassesIdDelete($id)
    {
            if (!session()->has('user')) return redirect('/login');
            if (session('role_id') != 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

            $lop = DB::table('lop_tap')->where('lop_tap_id', $id)->first();
            if (!$lop) return redirect('/admin/classes')->with('error', 'Không tìm thấy lớp tập.');

            $coDangKy = DB::table('dang_ky_lop')->where('lop_tap_id', $id)->exists();
            if ($coDangKy) {
                return redirect('/admin/classes')->with('error', 'Lớp đã có đăng ký; hãy đổi trạng thái sang ngừng hoạt động để giữ lịch sử.');
            }

            DB::table('lop_tap')->where('lop_tap_id', $id)->delete();
            return redirect('/admin/classes')->with('success', 'Đã xóa lớp tập.');
    }

}
