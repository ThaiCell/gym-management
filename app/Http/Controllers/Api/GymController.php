<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GymController extends Controller
{
    // Kiểm tra tài khoản và lấy hồ sơ hội viên
    private function member(Request $request)
    {
        $user = $request->user();

        abort_unless(
            (int) $user->vai_tro_id === 3,
            403,
            'Chỉ hội viên mới được sử dụng chức năng này.'
        );

        $member = DB::table('hoi_vien')
            ->where('nguoi_dung_id', $user->nguoi_dung_id)
            ->first();

        abort_unless($member !== null, 404, 'Không tìm thấy hồ sơ hội viên.');

        return [$user, $member];
    }

    // 1. Danh sách gói tập
    public function packages()
    {
        $packages = DB::table('goi_tap')
            ->whereIn('trang_thai', [
                'hoat_dong',
                'hoạt_động',
                'đang hoạt động'
            ])
            ->orderBy('gia')
            ->get();

        return response()->json([
            'data' => $packages
        ]);
    }

    // 2. Danh sách gói PT
    public function ptPackages()
    {
        $packages = DB::table('goi_pt')
            ->whereIn('trang_thai', [
                'hoat_dong',
                'hoạt_động',
                'đang hoạt động'
            ])
            ->orderBy('gia')
            ->get();

        return response()->json([
            'data' => $packages
        ]);
    }

    // 3. Xem các gói PT đã đăng ký
    public function myPtRegistrations(Request $request)
    {
        [, $member] = $this->member($request);

        $rows = DB::table('dang_ky_goi_pt as d')
            ->join(
                'goi_pt as g',
                'd.goi_pt_id',
                '=',
                'g.goi_pt_id'
            )
            ->leftJoin(
                'huan_luyen_vien as p',
                'd.pt_id',
                '=',
                'p.pt_id'
            )
            ->leftJoin(
                'nguoi_dung as u',
                'p.nguoi_dung_id',
                '=',
                'u.nguoi_dung_id'
            )
            ->where('d.hoi_vien_id', $member->hoi_vien_id)
            ->select(
                'd.*',
                'g.ten_goi_pt',
                'g.so_buoi',
                'u.ho_ten as ten_pt'
            )
            ->orderByDesc('d.dang_ky_goi_pt_id')
            ->get();

        return response()->json([
            'data' => $rows
        ]);
    }

    // 4. Xem lịch PT bận theo ngày
    public function availablePt(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'pt_id' => 'nullable|integer|exists:huan_luyen_vien,pt_id',
        ]);

        $query = DB::table('huan_luyen_vien as p')
            ->join(
                'nguoi_dung as u',
                'p.nguoi_dung_id',
                '=',
                'u.nguoi_dung_id'
            )
            ->where('u.vai_tro_id', 4)
            ->whereIn('u.trang_thai', [
                'hoat_dong',
                'hoạt_động',
                'hoạt động',
                'dang_hoat_dong',
                'đang hoạt động'
            ])
            ->select(
                'p.pt_id',
                'p.chuyen_mon',
                'u.ho_ten'
            );

        if (!empty($data['pt_id'])) {
            $query->where('p.pt_id', $data['pt_id']);
        }

        $trainers = $query->orderBy('u.ho_ten')->get();

        $result = $trainers->map(function ($pt) use ($data) {

            $busy = DB::table('lich_pt')
                ->where('pt_id', $pt->pt_id)
                ->whereNotIn('trang_thai', [
                    'đã hủy',
                    'da_huy',
                    'hủy',
                    'huy'
                ])
                ->whereDate(
                    'thoi_gian_bat_dau',
                    $data['date']
                )
                ->orderBy('thoi_gian_bat_dau')
                ->get([
                    'thoi_gian_bat_dau',
                    'thoi_gian_ket_thuc'
                ]);

            return [
                'pt_id' => $pt->pt_id,
                'ten_pt' => $pt->ho_ten,
                'chuyen_mon' => $pt->chuyen_mon,
                'lich_ban' => $busy
            ];
        });

        return response()->json([
            'date' => $data['date'],
            'data' => $result
        ]);
    }

    // 5. Đặt lịch PT
    public function bookPt(Request $request)
    {
        [, $member] = $this->member($request);

        $data = $request->validate([
            'dang_ky_goi_pt_id' => 'required|integer',
            'thoi_gian_bat_dau' => 'required|date|after:now',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
        ]);

        // Kiểm tra gói PT có thuộc hội viên không
        $registration = DB::table('dang_ky_goi_pt')
            ->join(
                'goi_pt',
                'dang_ky_goi_pt.goi_pt_id',
                '=',
                'goi_pt.goi_pt_id'
            )
            ->where(
                'dang_ky_goi_pt.dang_ky_goi_pt_id',
                $data['dang_ky_goi_pt_id']
            )
            ->where(
                'dang_ky_goi_pt.hoi_vien_id',
                $member->hoi_vien_id
            )
            ->select(
                'dang_ky_goi_pt.*',
                'goi_pt.ten_goi_pt'
            )
            ->first();

        if (
            !$registration ||
            !in_array($registration->trang_thai, [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ], true) ||
            (int) $registration->so_buoi_con_lai < 1 ||
            Carbon::parse($registration->ngay_ket_thuc)
                ->lt(Carbon::parse($data['thoi_gian_ket_thuc'])->startOfDay()) ||
            Carbon::parse($registration->ngay_bat_dau)
                ->gt(Carbon::parse($data['thoi_gian_bat_dau'])->startOfDay())
        ) {
            return response()->json([
                'message' => 'Gói PT không hợp lệ, hết hạn hoặc đã hết buổi.'
            ], 422);
        }

        try {
            $id = DB::transaction(function () use (
                $registration,
                $member,
                $data,
                $request
            ) {
                DB::table('huan_luyen_vien')
                    ->where('pt_id', $registration->pt_id)
                    ->lockForUpdate()
                    ->first();

                $locked = DB::table('dang_ky_goi_pt')
                    ->where(
                        'dang_ky_goi_pt_id',
                        $registration->dang_ky_goi_pt_id
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$locked || (int) $locked->so_buoi_con_lai < 1) {
                    throw new \RuntimeException('Gói PT đã hết buổi.');
                }

                // Kiểm tra lịch trùng của PT và hội viên
                $overlap = DB::table('lich_pt')
                    ->whereNotIn('trang_thai', [
                        'đã hủy',
                        'da_huy',
                        'hủy',
                        'huy'
                    ])
                    ->where(
                        'thoi_gian_bat_dau',
                        '<',
                        $data['thoi_gian_ket_thuc']
                    )
                    ->where(
                        'thoi_gian_ket_thuc',
                        '>',
                        $data['thoi_gian_bat_dau']
                    )
                    ->where(function ($q) use ($registration, $member) {
                        $q->where('pt_id', $registration->pt_id)
                            ->orWhere('hoi_vien_id', $member->hoi_vien_id);
                    })
                    ->exists();

                if ($overlap) {
                    throw new \RuntimeException(
                        'PT hoặc hội viên đã có lịch trùng giờ.'
                    );
                }

                // Tạo lịch PT
                $id = DB::table('lich_pt')->insertGetId([
                    'dang_ky_goi_pt_id' => $registration->dang_ky_goi_pt_id,
                    'hoi_vien_id' => $member->hoi_vien_id,
                    'pt_id' => $registration->pt_id,
                    'thoi_gian_bat_dau' => $data['thoi_gian_bat_dau'],
                    'thoi_gian_ket_thuc' => $data['thoi_gian_ket_thuc'],
                    'trang_thai' => 'đã đặt lịch',
                ]);

                // Thông báo cho hội viên
                DB::table('thong_bao')->insert([
                    'nguoi_dung_id' => $request->user()->nguoi_dung_id,
                    'tieu_de' => 'Đặt lịch PT thành công',
                    'noi_dung' => 'Bạn đã đặt lịch PT ' .
                        Carbon::parse($data['thoi_gian_bat_dau'])
                            ->format('d/m/Y H:i') . '.',
                    'da_doc' => false,
                    'tao_luc' => now(),
                ]);

                return $id;
            }, 3);

            return response()->json([
                'message' => 'Đặt lịch PT thành công.',
                'lich_pt_id' => $id
            ], 201);

        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    // 6. Xem lịch PT của hội viên
    public function myPtSchedules(Request $request)
    {
        [, $member] = $this->member($request);

        $rows = DB::table('lich_pt as l')
            ->join(
                'dang_ky_goi_pt as d',
                'l.dang_ky_goi_pt_id',
                '=',
                'd.dang_ky_goi_pt_id'
            )
            ->join(
                'goi_pt as g',
                'd.goi_pt_id',
                '=',
                'g.goi_pt_id'
            )
            ->join(
                'huan_luyen_vien as p',
                'l.pt_id',
                '=',
                'p.pt_id'
            )
            ->join(
                'nguoi_dung as u',
                'p.nguoi_dung_id',
                '=',
                'u.nguoi_dung_id'
            )
            ->where('l.hoi_vien_id', $member->hoi_vien_id)
            ->select(
                'l.*',
                'g.ten_goi_pt',
                'u.ho_ten as ten_pt'
            )
            ->orderBy('l.thoi_gian_bat_dau')
            ->get();

        return response()->json([
            'data' => $rows
        ]);
    }

    // 7. Hủy lịch PT
    public function cancelPtSchedule(Request $request, $id)
    {
        [, $member] = $this->member($request);

        $schedule = DB::table('lich_pt')
            ->where('lich_pt_id', $id)
            ->where('hoi_vien_id', $member->hoi_vien_id)
            ->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'Không tìm thấy lịch PT.'
            ], 404);
        }

        if (
            !str_contains(mb_strtolower($schedule->trang_thai), 'đặt') &&
            !str_contains(mb_strtolower($schedule->trang_thai), 'dat')
        ) {
            return response()->json([
                'message' => 'Lịch này không thể hủy.'
            ], 422);
        }

        if (!Carbon::parse($schedule->thoi_gian_bat_dau)->isFuture()) {
            return response()->json([
                'message' => 'Không thể hủy lịch đã bắt đầu hoặc đã qua.'
            ], 422);
        }

        DB::transaction(function () use ($schedule, $request) {

            DB::table('lich_pt')
                ->where('lich_pt_id', $schedule->lich_pt_id)
                ->update([
                    'trang_thai' => 'đã hủy'
                ]);

            DB::table('thong_bao')->insert([
                'nguoi_dung_id' => $request->user()->nguoi_dung_id,
                'tieu_de' => 'Hủy lịch PT',
                'noi_dung' => 'Bạn đã hủy lịch PT lúc ' .
                    Carbon::parse($schedule->thoi_gian_bat_dau)
                        ->format('d/m/Y H:i') . '.',
                'da_doc' => false,
                'tao_luc' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Hủy lịch PT thành công.'
        ]);
    }

    // 8. Check-in hội viên tại quầy
    public function checkIn(Request $request)
    {
        abort_unless(
            (int) $request->user()->vai_tro_id === 2,
            403,
            'Chỉ nhân viên mới được check-in.'
        );

        $data = $request->validate([
            'hoi_vien_id' => 'required|integer|exists:hoi_vien,hoi_vien_id',
            'dang_ky_goi_tap_id' => 'required|integer',
        ]);

        $package = DB::table('dang_ky_goi_tap')
            ->where(
                'dang_ky_goi_tap_id',
                $data['dang_ky_goi_tap_id']
            )
            ->where('hoi_vien_id', $data['hoi_vien_id'])
            ->first();

        if (!$package) {
            return response()->json([
                'message' => 'Gói tập không thuộc hội viên đã chọn.'
            ], 422);
        }

        if (
            !in_array($package->trang_thai, [
                'đang hoạt động',
                'dang_hoat_dong',
                'hoạt_động',
                'hoat_dong'
            ], true) ||
            $package->ngay_bat_dau > now()->toDateString() ||
            $package->ngay_ket_thuc < now()->toDateString() ||
            (int) $package->so_buoi_con_lai <= 0
        ) {
            return response()->json([
                'message' => 'Gói tập chưa hoạt động, hết hạn hoặc đã hết buổi.'
            ], 422);
        }

        $id = DB::table('check_in')->insertGetId([
            'hoi_vien_id' => $data['hoi_vien_id'],
            'dang_ky_goi_tap_id' => $data['dang_ky_goi_tap_id'],
            'thoi_gian' => now(),
            'nguoi_thuc_hien_id' => $request->user()->nguoi_dung_id,
        ]);

        $member = DB::table('hoi_vien')
            ->where('hoi_vien_id', $data['hoi_vien_id'])
            ->first();

        DB::table('thong_bao')->insert([
            'nguoi_dung_id' => $member->nguoi_dung_id,
            'tieu_de' => 'Check-in thành công',
            'noi_dung' => 'Bạn đã check-in Gym lúc ' .
                now()->format('H:i d/m/Y') . '.',
            'da_doc' => false,
            'tao_luc' => now(),
        ]);

        return response()->json([
            'message' => 'Check-in thành công.',
            'check_in_id' => $id
        ], 201);
    }

    // 9. Xem hóa đơn của hội viên
    public function myInvoices(Request $request)
    {
        [, $member] = $this->member($request);

        $rows = DB::table('hoa_don')
            ->where('hoi_vien_id', $member->hoi_vien_id)
            ->orderByDesc('ngay_lap')
            ->get();

        return response()->json([
            'data' => $rows
        ]);
    }
}