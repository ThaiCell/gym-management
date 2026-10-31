<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContactController extends Controller
{
    /** Route: GET /contact */
    public function getContact()
    {
            $settings = DB::table('cai_dat')->pluck('gia_tri', 'khoa');

            return view('user.contact', compact('settings'));
    }

    /** Route: POST /contact */
    public function postContact()
    {
            $validated = request()->validate([

                'ho_ten' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'email' => [
                    'required',
                    'email',
                    'max:100'
                ],

                'so_dien_thoai' => [
                    'required',
                    'string',
                    'max:20'
                ],

                'noi_dung' => [
                    'required',
                    'string',
                    'max:1000'
                ],

            ], [

                'ho_ten.required' =>
                    'Vui lòng nhập họ và tên.',

                'ho_ten.max' =>
                    'Họ tên không được quá 100 ký tự.',

                'email.required' =>
                    'Vui lòng nhập email.',

                'email.email' =>
                    'Email không đúng định dạng.',

                'email.max' =>
                    'Email không được quá 100 ký tự.',

                'so_dien_thoai.required' =>
                    'Vui lòng nhập số điện thoại.',

                'noi_dung.required' =>
                    'Vui lòng nhập nội dung cần tư vấn.',

                'noi_dung.max' =>
                    'Nội dung không được quá 1000 ký tự.',

            ]);


            /*
            |--------------------------------------------------------------------------
            | LƯU TIN NHẮN VÀO DATABASE
            |--------------------------------------------------------------------------
            */

            DB::table('lien_he')->insert([

                'ho_ten' =>
                    trim($validated['ho_ten']),

                'email' =>
                    trim($validated['email']),

                'so_dien_thoai' =>
                    trim($validated['so_dien_thoai']),

                'noi_dung' =>
                    trim($validated['noi_dung']),

                'trang_thai' =>
                    'chưa xử lý',

                'nhan_vien_id' =>
                    null,

                'phan_hoi' =>
                    null,

                'tao_luc' =>
                    now(),

                'xu_ly_luc' =>
                    null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | THÔNG BÁO
            |--------------------------------------------------------------------------
            */

            return redirect('/contact')
                ->with(
                    'success',
                    'Gửi tin nhắn thành công! GYMFIT sẽ liên hệ với bạn sớm nhất.'
                );
    }

    /** Route: GET /staff/contacts */
    public function getStaffContacts()
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if (session('role_id') != 2) {
                return redirect('/' . session('dashboard_path'));
            }

            $lienHe = DB::table('lien_he')
                ->leftJoin(
                    'nhan_vien',
                    'lien_he.nhan_vien_id',
                    '=',
                    'nhan_vien.nhan_vien_id'
                )
                ->leftJoin(
                    'nguoi_dung as nd_nv',
                    'nhan_vien.nguoi_dung_id',
                    '=',
                    'nd_nv.nguoi_dung_id'
                )
                ->select(
                    'lien_he.*',
                    'nd_nv.ho_ten as ten_nhan_vien'
                )
                ->orderByDesc('lien_he.tao_luc')
                ->get();

            return view('staff.contacts', compact('lienHe'));
    }

    /** Route: POST /staff/contacts/{id}/update */
    public function postStaffContactsIdUpdate(Request $request, $id)
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if (session('role_id') != 2) {
                return redirect('/' . session('dashboard_path'));
            }

            $request->validate([
                'trang_thai' => 'required|string|max:30',
                'phan_hoi' => 'nullable|string|max:2000',
            ]);

            $nhanVien = DB::table('nhan_vien')
                ->where(
                    'nguoi_dung_id',
                    session('user')->nguoi_dung_id
                )
                ->first();

            $duLieu = [
                'trang_thai' => $request->trang_thai,
                'phan_hoi' => $request->phan_hoi,
            ];

            if (
                $request->trang_thai != 'chưa xử lý' &&
                $nhanVien
            ) {
                $duLieu['nhan_vien_id'] = $nhanVien->nhan_vien_id;
                $duLieu['xu_ly_luc'] = now();
            }

            DB::table('lien_he')
                ->where('lien_he_id', $id)
                ->update($duLieu);

            return redirect('/staff/contacts')
                ->with('success', 'Đã cập nhật xử lý liên hệ.');
    }



    /**
     * Admin: danh sách yêu cầu liên hệ.
     * Chỉ tài khoản quản trị (role_id = 1) được truy cập.
     */
    public function getAdminContacts()
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        if ((int) session('role_id') !== 1) {
            return redirect('/' . session('dashboard_path', 'user/dashboard'));
        }

        $lienHe = DB::table('lien_he')
            ->leftJoin('nhan_vien', 'lien_he.nhan_vien_id', '=', 'nhan_vien.nhan_vien_id')
            ->leftJoin('nguoi_dung as nd_nv', 'nhan_vien.nguoi_dung_id', '=', 'nd_nv.nguoi_dung_id')
            ->select('lien_he.*', 'nd_nv.ho_ten as ten_nhan_vien')
            ->orderByRaw("CASE
                WHEN LOWER(TRIM(lien_he.trang_thai)) IN ('chưa xử lý','chua xu ly','chua_xu_ly') THEN 0
                WHEN LOWER(TRIM(lien_he.trang_thai)) IN ('đang xử lý','dang xu ly','dang_xu_ly') THEN 1
                ELSE 2 END")
            ->orderByDesc('lien_he.tao_luc')
            ->get();

        return view('admin.contacts', compact('lienHe'));
    }

    /**
     * Admin: cập nhật trạng thái và phản hồi liên hệ.
     */
    public function postAdminContactsIdUpdate(Request $request, $id)
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        if ((int) session('role_id') !== 1) {
            return redirect('/' . session('dashboard_path', 'user/dashboard'));
        }

        $validated = $request->validate([
            'trang_thai' => 'required|in:chưa xử lý,đang xử lý,đã xử lý',
            'phan_hoi' => 'nullable|string|max:2000',
        ], [
            'trang_thai.required' => 'Vui lòng chọn trạng thái xử lý.',
            'trang_thai.in' => 'Trạng thái xử lý không hợp lệ.',
            'phan_hoi.max' => 'Phản hồi không được vượt quá 2000 ký tự.',
        ]);

        $contact = DB::table('lien_he')->where('lien_he_id', $id)->first();
        if (!$contact) {
            return redirect('/admin/contacts')->with('error', 'Không tìm thấy yêu cầu liên hệ.');
        }

        $update = [
            'trang_thai' => $validated['trang_thai'],
            'phan_hoi' => isset($validated['phan_hoi']) ? trim($validated['phan_hoi']) : null,
            'xu_ly_luc' => $validated['trang_thai'] === 'chưa xử lý' ? null : now(),
        ];

        // Nếu admin chỉ định xử lý, giữ nguyên nhân viên đã tiếp nhận trước đó.
        DB::table('lien_he')->where('lien_he_id', $id)->update($update);

        return redirect('/admin/contacts')->with('success', 'Đã cập nhật trạng thái và phản hồi liên hệ.');
    }

}
