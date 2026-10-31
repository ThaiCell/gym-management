<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NotificationController extends Controller
{
    /** Route: POST /notifications/read-all */
    public function postNotificationsReadAll()
    {
            if (!session()->has('user')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa đăng nhập.'
                ], 401);
            }

            $nguoiDungId = session('user')->nguoi_dung_id;

            $soLuong = DB::table('thong_bao')
                ->where('nguoi_dung_id', $nguoiDungId)
                ->where('da_doc', 0)
                ->update([
                    'da_doc' => 1
                ]);

            return response()->json([
                'success' => true,
                'updated' => $soLuong
            ]);
    }

}
