<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Route: GET /login */
    public function getLogin()
    {
            return view('user.login');
    }

    /** Route: GET /forgot-password */
    public function getForgotPassword()
    {
            return view('user.forgot-password');
    }

    /** Route: POST /forgot-password */
    public function postForgotPassword(Request $request)
    {
            $validated = $request->validate([
                'email' => ['required', 'email', 'max:255'],
            ], [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
            ]);

            $user = DB::table('nguoi_dung')->where('email', $validated['email'])->first();

            // Trả cùng một thông báo để tránh tiết lộ email có đăng ký hay không.
            $message = 'Nếu email tồn tại trong hệ thống, hướng dẫn đặt lại mật khẩu sẽ được gửi đến hộp thư của bạn.';
            if (!$user) {
                return redirect()->route('password.request')->with('status', $message);
            }

            $plainToken = \Illuminate\Support\Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($plainToken), 'created_at' => now()]
            );

            $resetUrl = url('/reset-password/' . urlencode($user->email) . '/' . $plainToken);
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Xin chào {$user->ho_ten},\n\nBạn vừa yêu cầu đặt lại mật khẩu GYMFIT. Mở liên kết sau trong vòng 60 phút: {$resetUrl}\n\nNếu bạn không yêu cầu, hãy bỏ qua email này.",
                    function ($mail) use ($user) {
                        $mail->to($user->email, $user->ho_ten)->subject('Đặt lại mật khẩu GYMFIT');
                    }
                );
            } catch (\Throwable $exception) {
                report($exception);
                return redirect()->route('password.request')->with('error', 'Không thể gửi email lúc này. Vui lòng thử lại sau hoặc liên hệ quản trị viên.');
            }

            return redirect()->route('password.request')->with('status', $message);
    }

    /** Route: GET /reset-password/{email}/{token} */
    public function getResetPasswordEmailToken($email, $token)
    {
            $record = DB::table('password_reset_tokens')->where('email', $email)->first();
            if (!$record || !$record->created_at || \Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast() || !Hash::check($token, $record->token)) {
                return redirect()->route('password.request')->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
            }
            return view('user.reset-password', ['email' => $email, 'token' => $token]);
    }

    /** Route: POST /reset-password */
    public function postResetPassword(Request $request)
    {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'token' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]);

            $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();
            if (!$record || !$record->created_at || \Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast() || !Hash::check($validated['token'], $record->token)) {
                return redirect()->route('password.request')->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
            }

            $updated = DB::transaction(function () use ($validated) {
                $changed = DB::table('nguoi_dung')->where('email', $validated['email'])->update([
                    'mat_khau' => Hash::make($validated['password']),
                ]);
                if ($changed) {
                    DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
                }
                return $changed;
            });

            if (!$updated) {
                return redirect()->route('password.request')->with('error', 'Không tìm thấy tài khoản cần cập nhật.');
            }
            return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Bạn có thể đăng nhập bằng mật khẩu mới.');
    }

    /** Route: POST /login */
    public function postLogin()
    {
            $email = request('email');
            $matKhau = request('mat_khau');


            /*
            |--------------------------------------------------------------------------
            | Tìm tài khoản
            |--------------------------------------------------------------------------
            */

            $user = DB::table('nguoi_dung')
                ->where('email', $email)
                ->first();


            if (!$user) {

                return back()
                    ->withInput()
                    ->with('error', 'Email hoặc mật khẩu không đúng.');

            }


            /*
            |--------------------------------------------------------------------------
            | Kiểm tra trạng thái tài khoản
            |--------------------------------------------------------------------------
            */
            if (!in_array(mb_strtolower(trim((string) $user->trang_thai), 'UTF-8'), [
                'hoat_dong',
                'hoạt_động',
                'hoạt động',
                'dang_hoat_dong',
                'đang hoạt động',
            ], true)) {
                return back()
                    ->withInput()
                    ->with('error', 'Tài khoản đang bị khóa hoặc ngừng hoạt động.');
            }


            /*
            |--------------------------------------------------------------------------
            | Kiểm tra mật khẩu
            |--------------------------------------------------------------------------
            |
            | Hỗ trợ:
            | 1. Mật khẩu thường trong database
            | 2. Mật khẩu Bcrypt
            |
            */

            $matKhauDung = false;


            // Mật khẩu thường
            if ($matKhau === $user->mat_khau) {

                $matKhauDung = true;

            }


            // Mật khẩu Bcrypt
            else if (
                str_starts_with($user->mat_khau, '$2y$') ||
                str_starts_with($user->mat_khau, '$2a$') ||
                str_starts_with($user->mat_khau, '$2b$')
            ) {

                if (Hash::check($matKhau, $user->mat_khau)) {

                    $matKhauDung = true;

                }

            }


            if (!$matKhauDung) {

                return back()
                    ->withInput()
                    ->with('error', 'Email hoặc mật khẩu không đúng.');

            }


            // Nâng cấp mật khẩu dạng chữ thường cũ sang bcrypt sau lần đăng nhập hợp lệ.
            if ($matKhau === $user->mat_khau) {
                DB::table('nguoi_dung')->where('nguoi_dung_id', $user->nguoi_dung_id)
                    ->update(['mat_khau' => Hash::make($matKhau)]);
                $user->mat_khau = Hash::make($matKhau);
            }

            // Đổi session ID sau xác thực để giảm nguy cơ session fixation.
            request()->session()->regenerate();
            $sessionUser = clone $user;
            unset($sessionUser->mat_khau);

            /*
            |--------------------------------------------------------------------------
            | Lưu session
            |--------------------------------------------------------------------------
            */

            session([
                'user' => $sessionUser,
                'role_id' => $user->vai_tro_id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Chuyển dashboard theo vai trò
            |--------------------------------------------------------------------------
            */

            switch ($user->vai_tro_id) {

                // Admin
                case 1:

                    session([
                        'dashboard_path' => 'dashboard'
                    ]);

                    return redirect('/dashboard');


                // Nhân viên
                case 2:

                    session([
                        'dashboard_path' => 'staff/dashboard'
                    ]);

                    return redirect('/staff/dashboard');


                // Hội viên
                case 3:

                    session([
                        'dashboard_path' => 'user/dashboard'
                    ]);

                    return redirect('/user/dashboard');


                // Huấn luyện viên
                case 4:

                    session([
                        'dashboard_path' => 'trainer/dashboard'
                    ]);

                    return redirect('/trainer/dashboard');


                default:

                    session()->flush();

                    return redirect('/login')
                        ->with(
                            'error',
                            'Tài khoản chưa được phân quyền.'
                        );
            }
    }

    /** Route: GET /register */
    public function getRegister()
    {
            return view('user.register');
    }

    /** Route: POST /register */
    public function postRegister()
    {
            $hoTen = request('ho_ten');
            $email = request('email');
            $matKhau = request('mat_khau');
            $xacNhanMatKhau = request('mat_khau_confirmation');


            /*
            |--------------------------------------------------------------------------
            | Kiểm tra dữ liệu
            |--------------------------------------------------------------------------
            */

            if (!$hoTen || !$email || !$matKhau) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Vui lòng nhập đầy đủ thông tin.'
                    );

            }


            if ($matKhau !== $xacNhanMatKhau) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Mật khẩu xác nhận không giống nhau.'
                    );

            }


            if (strlen($matKhau) < 6) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Mật khẩu phải có ít nhất 6 ký tự.'
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Kiểm tra email đã tồn tại
            |--------------------------------------------------------------------------
            */

            $emailTonTai = DB::table('nguoi_dung')
                ->where('email', $email)
                ->exists();


            if ($emailTonTai) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Email này đã được sử dụng.'
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Tạo tài khoản
            |--------------------------------------------------------------------------
            */

            DB::beginTransaction();


            try {

                /*
                |--------------------------------------------------------------------------
                | Tạo người dùng
                |--------------------------------------------------------------------------
                */

                $nguoiDungId = DB::table('nguoi_dung')->insertGetId([

                    // Đăng ký mới mặc định là Hội viên
                    'vai_tro_id' => 3,

                    'ho_ten' => $hoTen,

                    'email' => $email,

                    'mat_khau' => Hash::make($matKhau),

                    'trang_thai' => 'hoạt_động',

                ]);


                /*
                |--------------------------------------------------------------------------
                | Tạo hồ sơ hội viên
                |--------------------------------------------------------------------------
                */

                DB::table('hoi_vien')->insert([

                    'nguoi_dung_id' => $nguoiDungId,

                    'ngay_tham_gia' => now()->toDateString(),

                ]);


                DB::commit();


                return redirect('/login')
                    ->with(
                        'success',
                        'Đăng ký thành công. Vui lòng đăng nhập.'
                    );


            } catch (\Exception $e) {

                DB::rollBack();


                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Không thể tạo tài khoản: ' . $e->getMessage()
                    );

            }
    }

    /** Route: POST /logout */
    public function postLogout()
    {
            session()->invalidate();

            session()->regenerateToken();

            return redirect('/');
    }

}
