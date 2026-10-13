<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required|string',
        ]);

        $user = NguoiDung::where(
            'email',
            $data['email']
        )->first();

        
        if (!$user) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng.'
            ], 401);
        }

        $storedPassword = (string) $user->mat_khau;
        $inputPassword = $data['mat_khau'];

        if (Hash::isHashed($storedPassword)) {
            if (!Hash::check($inputPassword, $storedPassword)) {
                return response()->json([
                    'message' => 'Email hoặc mật khẩu không đúng.'
                ], 401);
            }
        } else {
            // Tương thích tạm thời với tài khoản cũ.
            if (!hash_equals($storedPassword, $inputPassword)) {
                return response()->json([
                    'message' => 'Email hoặc mật khẩu không đúng.'
                ], 401);
            }

            // Băm lại mật khẩu ngay sau khi xác thực thành công.
            $user->mat_khau = Hash::make($inputPassword);
            $user->save();
        }

        if (!in_array($user->trang_thai, [
            'hoat_dong',
            'hoạt_động',
            'hoạt động'
        ], true)) {
            return response()->json([
                'message' => 'Tài khoản đã bị khóa hoặc ngừng hoạt động.'
            ], 403);
        }

        $token = $user->createToken('gym-api')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công.'
        ]);
    }
}
