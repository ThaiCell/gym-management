<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PackageLifecycleService
{
    public function xuLyGoiTapCuaHoiVien($hoiVienId)
    {

    $dangKyList = DB::table('dang_ky_goi_tap')
        ->join(
            'goi_tap',
            'dang_ky_goi_tap.goi_tap_id',
            '=',
            'goi_tap.goi_tap_id'
        )
        ->where('dang_ky_goi_tap.hoi_vien_id', $hoiVienId)
        ->whereNotIn('dang_ky_goi_tap.trang_thai', ['đã hủy', 'đã huỷ', 'đã hết hạn'])
        ->select(
            'dang_ky_goi_tap.*',
            'goi_tap.ten_goi'
        )
        ->get();

    foreach ($dangKyList as $dangKy) {

        /*
        |--------------------------------------------------------------------------
        | Tìm hóa đơn của lần đăng ký này
        |--------------------------------------------------------------------------
        */

        $hoaDon = DB::table('chi_tiet_hoa_don')
            ->join(
                'hoa_don',
                'chi_tiet_hoa_don.hoa_don_id',
                '=',
                'hoa_don.hoa_don_id'
            )
            ->where(
                'chi_tiet_hoa_don.goi_tap_id',
                $dangKy->goi_tap_id
            )
            ->where(
                'hoa_don.hoi_vien_id',
                $hoiVienId
            )
            ->whereDate(
                'hoa_don.ngay_lap',
                '>=',
                $dangKy->ngay_bat_dau
            )
            ->select(
                'hoa_don.*'
            )
            ->orderByDesc('hoa_don.ngay_lap')
            ->first();

        if (!$hoaDon) {
            continue;
        }

        $trangThaiHoaDon = mb_strtolower(
            trim($hoaDon->trang_thai ?? '')
        );

        /*
        |--------------------------------------------------------------------------
        | 1. CHƯA THANH TOÁN QUÁ 72 GIỜ
        |--------------------------------------------------------------------------
        */

        $choThanhToan = in_array(
            $trangThaiHoaDon,
            [
                'chờ thanh toán',
                'cho thanh toan',
                'cho_thanh_toan',
                'pending'
            ],
            true
        );

        if (
            $choThanhToan &&
            \Carbon\Carbon::parse($hoaDon->ngay_lap)
                ->addHours(72)
                ->lessThanOrEqualTo(now())
        ) {

            DB::transaction(function () use (
                $dangKy,
                $hoaDon
            ) {

                DB::table('hoa_don')
                    ->where('hoa_don_id', $hoaDon->hoa_don_id)
                    ->whereRaw('LOWER(TRIM(trang_thai)) IN (?, ?, ?, ?)', [
                        'chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending'
                    ])
                    ->update(['trang_thai' => 'đã hủy']);

                DB::table('dang_ky_goi_tap')
                    ->where('dang_ky_goi_tap_id', $dangKy->dang_ky_goi_tap_id)
                    ->where('hoi_vien_id', $dangKy->hoi_vien_id)
                    ->update(['trang_thai' => 'đã hủy']);

            });

            DB::table('thong_bao')->insert([
                'nguoi_dung_id' => DB::table('hoi_vien')
                    ->where(
                        'hoi_vien_id',
                        $hoiVienId
                    )
                    ->value('nguoi_dung_id'),

                'tieu_de' => 'Gói tập đã tự động hủy',

                'noi_dung' =>
                    'Gói "' .
                    $dangKy->ten_goi .
                    '" đã tự động hủy vì quá 72 giờ chưa thanh toán. Hóa đơn vẫn được lưu trong lịch sử với trạng thái đã hủy.',

                'da_doc' => 0,

                'tao_luc' => now(),
            ]);

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. ĐÃ THANH TOÁN NHƯNG HẾT HẠN
        |--------------------------------------------------------------------------
        */

        $daThanhToan = in_array(
            $trangThaiHoaDon,
            [
                'đã thanh toán',
                'da thanh toan',
                'da_thanh_toan',
                'paid'
            ],
            true
        );

        if (
            $daThanhToan &&
            $dangKy->ngay_ket_thuc &&
            \Carbon\Carbon::parse(
                $dangKy->ngay_ket_thuc
            )->startOfDay()->lessThan(
                now()->startOfDay()
            ) &&
            in_array(
                mb_strtolower(
                    trim($dangKy->trang_thai ?? '')
                ),
                [
                    'đang hoạt động',
                    'dang_hoat_dong',
                    'hoạt_động',
                    'hoat_dong'
                ],
                true
            )
        ) {

            DB::table('dang_ky_goi_tap')
                ->where(
                    'dang_ky_goi_tap_id',
                    $dangKy->dang_ky_goi_tap_id
                )
                ->update([
                    'trang_thai' => 'đã hết hạn'
                ]);

        }
    }

    }


    /**
     * Tự động hủy đăng ký gói PT chưa thanh toán sau 72 giờ.
     * Giữ lại hóa đơn và chi tiết để bảo toàn lịch sử giao dịch.
     */
    public function xuLyGoiPtCuaHoiVien($hoiVienId)
    {
        $dangKyList = DB::table('dang_ky_goi_pt')
            ->join('goi_pt', 'dang_ky_goi_pt.goi_pt_id', '=', 'goi_pt.goi_pt_id')
            ->where('dang_ky_goi_pt.hoi_vien_id', $hoiVienId)
            ->whereNotIn('dang_ky_goi_pt.trang_thai', ['đã hủy', 'đã huỷ', 'đã hết hạn'])
            ->select(
                'dang_ky_goi_pt.*',
                'goi_pt.ten_goi_pt'
            )
            ->get();

        foreach ($dangKyList as $dangKy) {
            // Chỉ xét hóa đơn chờ thanh toán của đúng hội viên, đúng gói và lần đăng ký.
            $hoaDon = DB::table('chi_tiet_hoa_don')
                ->join('hoa_don', 'chi_tiet_hoa_don.hoa_don_id', '=', 'hoa_don.hoa_don_id')
                ->where('chi_tiet_hoa_don.goi_pt_id', $dangKy->goi_pt_id)
                ->where('hoa_don.hoi_vien_id', $hoiVienId)
                ->whereDate('hoa_don.ngay_lap', '>=', $dangKy->ngay_bat_dau)
                ->whereRaw('LOWER(TRIM(hoa_don.trang_thai)) IN (?, ?, ?, ?)', [
                    'chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending'
                ])
                ->select('hoa_don.hoa_don_id', 'hoa_don.ngay_lap')
                ->orderByDesc('hoa_don.ngay_lap')
                ->first();

            if (!$hoaDon || !$hoaDon->ngay_lap) {
                continue;
            }

            if (\Carbon\Carbon::parse($hoaDon->ngay_lap)->addHours(72)->greaterThan(now())) {
                continue;
            }

            DB::transaction(function () use ($dangKy, $hoaDon, $hoiVienId) {
                $updated = DB::table('dang_ky_goi_pt')
                    ->where('dang_ky_goi_pt_id', $dangKy->dang_ky_goi_pt_id)
                    ->where('hoi_vien_id', $hoiVienId)
                    ->whereNotIn('trang_thai', ['đã hủy', 'đã huỷ', 'đã hết hạn'])
                    ->update(['trang_thai' => 'đã hủy']);

                if (!$updated) {
                    return;
                }

                // Không xóa hóa đơn/chi tiết: cập nhật trạng thái để lưu vết.
                DB::table('hoa_don')
                    ->where('hoa_don_id', $hoaDon->hoa_don_id)
                    ->whereRaw('LOWER(TRIM(trang_thai)) IN (?, ?, ?, ?)', [
                        'chờ thanh toán', 'cho thanh toan', 'cho_thanh_toan', 'pending'
                    ])
                    ->update(['trang_thai' => 'đã hủy']);

                $nguoiDungId = DB::table('hoi_vien')
                    ->where('hoi_vien_id', $hoiVienId)
                    ->value('nguoi_dung_id');

                if ($nguoiDungId) {
                    DB::table('thong_bao')->insert([
                        'nguoi_dung_id' => $nguoiDungId,
                        'tieu_de' => 'Gói PT đã tự động hủy',
                        'noi_dung' => 'Gói "' . $dangKy->ten_goi_pt . '" đã được tự động hủy vì quá 72 giờ chưa thanh toán.',
                        'da_doc' => 0,
                        'tao_luc' => now(),
                    ]);
                }
            });
        }
    }

}
