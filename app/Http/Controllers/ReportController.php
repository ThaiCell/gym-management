<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /** Route: GET /admin/statistics/export */
    public function getAdminStatisticsExport(Request $request)
    {
            if (!session()->has('user')) return redirect('/login');
            if ((int) session('role_id') !== 1) return redirect('/' . session('dashboard_path', 'user/dashboard'));

            $filters = $request->validate([
                'from' => ['nullable', 'date'],
                'to' => ['nullable', 'date', 'after_or_equal:from'],
            ]);
            $query = DB::table('hoa_don')
                ->join('hoi_vien', 'hoa_don.hoi_vien_id', '=', 'hoi_vien.hoi_vien_id')
                ->join('nguoi_dung', 'hoi_vien.nguoi_dung_id', '=', 'nguoi_dung.nguoi_dung_id')
                ->select('hoa_don.hoa_don_id', 'nguoi_dung.ho_ten', 'nguoi_dung.email', 'hoa_don.ngay_lap', 'hoa_don.tong_tien', 'hoa_don.trang_thai', 'hoa_don.phuong_thuc_thanh_toan')
                ->orderByDesc('hoa_don.ngay_lap');
            if (!empty($filters['from'])) $query->whereDate('hoa_don.ngay_lap', '>=', $filters['from']);
            if (!empty($filters['to'])) $query->whereDate('hoa_don.ngay_lap', '<=', $filters['to']);
            $rows = $query->get();

            return response()->streamDownload(function () use ($rows) {
                $out = fopen('php://output', 'w');
                // BOM để Excel đọc tiếng Việt UTF-8.
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, ['Mã hóa đơn', 'Hội viên', 'Email', 'Ngày lập', 'Tổng tiền', 'Trạng thái', 'Phương thức']);
                foreach ($rows as $row) {
                    $safeText = static function ($value) {
                        $value = (string) ($value ?? '');
                        return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
                    };
                    fputcsv($out, [
                        $row->hoa_don_id,
                        $safeText($row->ho_ten),
                        $safeText($row->email),
                        $row->ngay_lap,
                        $row->tong_tien,
                        $safeText($row->trang_thai),
                        $safeText($row->phuong_thuc_thanh_toan),
                    ]);
                }
                fclose($out);
            }, 'gymfit-bao-cao-doanh-thu-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Route: GET /admin/statistics */
    public function getAdminStatistics(Request $request)
    {
            if (!session()->has('user')) {
                return redirect('/login');
            }

            if (session('role_id') != 1) {
                return redirect('/' . session('dashboard_path', 'user/dashboard'));
            }

            $from = $request->input('from');
            $to = $request->input('to');

            // Mặc định: toàn bộ dữ liệu
            $invoiceQuery = DB::table('hoa_don')
                ->where('trang_thai', 'đã thanh toán');

            $checkInQuery = DB::table('check_in');

            $scheduleQuery = DB::table('lich_pt');

            // Lọc theo thời gian
            if ($from) {
                $invoiceQuery->whereDate('ngay_lap', '>=', $from);
                $checkInQuery->whereDate('thoi_gian', '>=', $from);
                $scheduleQuery->whereDate('thoi_gian_bat_dau', '>=', $from);
            }

            if ($to) {
                $invoiceQuery->whereDate('ngay_lap', '<=', $to);
                $checkInQuery->whereDate('thoi_gian', '<=', $to);
                $scheduleQuery->whereDate('thoi_gian_bat_dau', '<=', $to);
            }

            $trendEnd = $to
                ? Illuminate\Support\Carbon::parse($to)->endOfDay()
                : Illuminate\Support\Carbon::now()->endOfDay();
            $trendStart = $from
                ? Illuminate\Support\Carbon::parse($from)->startOfDay()
                : $trendEnd->copy()->startOfMonth()->subMonths(11);
            $trendByDay = $from && $to
                && Illuminate\Support\Carbon::parse($from)->diffInDays(Illuminate\Support\Carbon::parse($to)) <= 7;
            $trendByYear = $trendStart->diffInDays($trendEnd) > 730;

            $invoiceTrendQuery = DB::table('hoa_don')
                ->where('trang_thai', 'đã thanh toán')
                ->whereDate('ngay_lap', '>=', $trendStart->toDateString())
                ->whereDate('ngay_lap', '<=', $trendEnd->toDateString());
            $checkInTrendQuery = DB::table('check_in')
                ->whereDate('thoi_gian', '>=', $trendStart->toDateString())
                ->whereDate('thoi_gian', '<=', $trendEnd->toDateString());

            $revenueByDay = $invoiceTrendQuery
                ->selectRaw('DATE(ngay_lap) as ngay, SUM(tong_tien) as gia_tri')
                ->groupByRaw('DATE(ngay_lap)')
                ->orderBy('ngay')
                ->get()
                ->keyBy(fn ($row) => Illuminate\Support\Carbon::parse($row->ngay)->format('Y-m-d'));

            $checkInsByDay = $checkInTrendQuery
                ->selectRaw('DATE(thoi_gian) as ngay, COUNT(*) as gia_tri')
                ->groupByRaw('DATE(thoi_gian)')
                ->orderBy('ngay')
                ->get()
                ->keyBy(fn ($row) => Illuminate\Support\Carbon::parse($row->ngay)->format('Y-m-d'));

            $trendFormat = $trendByDay ? 'Y-m-d' : ($trendByYear ? 'Y' : 'Y-m');
            if ($trendByDay) {
                $trendStart = $trendStart->copy();
                $trendEnd = $trendEnd->copy();
            } elseif ($trendByYear) {
                $trendStart = $trendStart->copy()->startOfYear();
                $trendEnd = $trendEnd->copy()->startOfYear();
            } else {
                $trendStart = $trendStart->copy()->startOfMonth();
                $trendEnd = $trendEnd->copy()->startOfMonth();
            }
            $revenueByPeriod = $revenueByDay
                ->groupBy(fn ($row, $date) => Illuminate\Support\Carbon::parse($date)->format($trendFormat))
                ->map(fn ($rows) => $rows->sum('gia_tri'));
            $checkInsByPeriod = $checkInsByDay
                ->groupBy(fn ($row, $date) => Illuminate\Support\Carbon::parse($date)->format($trendFormat))
                ->map(fn ($rows) => $rows->sum('gia_tri'));
            $trends = collect();

            while ($trendStart->lte($trendEnd)) {
                $period = $trendStart->format($trendFormat);
                $trends->push([
                    'label' => $trendStart->format($trendByDay ? 'd/m' : ($trendByYear ? 'Y' : 'm/Y')),
                    'revenue' => $revenueByPeriod->get($period, 0),
                    'check_ins' => $checkInsByPeriod->get($period, 0),
                ]);

                if ($trendByDay) {
                    $trendStart->addDay();
                } elseif ($trendByYear) {
                    $trendStart->addYear();
                } else {
                    $trendStart->addMonth();
                }
            }

            // Doanh thu
            $revenue = $invoiceQuery->sum('tong_tien');

            // Số hóa đơn đã thanh toán
            $paidInvoices = $invoiceQuery->count();

            // Số check-in
            $checkIns = $checkInQuery->count();

            // Số lịch PT
            $ptSchedules = $scheduleQuery->count();

            // Tổng hội viên
            $members = DB::table('hoi_vien')->count();

            // Hội viên đang hoạt động
            $activeMembers = DB::table('hoi_vien')
                ->join(
                    'dang_ky_goi_tap',
                    'hoi_vien.hoi_vien_id',
                    '=',
                    'dang_ky_goi_tap.hoi_vien_id'
                )
                ->where('dang_ky_goi_tap.trang_thai', 'đang hoạt động')
                ->whereDate('dang_ky_goi_tap.ngay_ket_thuc', '>=', now()->toDateString())
                ->distinct()
                ->count('hoi_vien.hoi_vien_id');

            // Lịch PT hoàn thành
            $completedSchedules = $scheduleQuery
                ->clone()
                ->where('trang_thai', 'đã hoàn thành')
                ->count();

            // Lịch PT đã hủy
            $cancelledSchedules = $scheduleQuery
                ->clone()
                ->where('trang_thai', 'đã hủy')
                ->count();

            return view('admin.statistics', compact(
                'from',
                'to',
                'revenue',
                'paidInvoices',
                'checkIns',
                'ptSchedules',
                'members',
                'activeMembers',
                'completedSchedules',
                'cancelledSchedules',
                'trends'
            ));
    }

}
