<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerWorkspaceController extends Controller
{
    private function trainer()
    {
        abort_unless(session()->has('user') && (int) session('role_id') === 4, 403);
        $pt = DB::table('huan_luyen_vien')->where('nguoi_dung_id', session('user')->nguoi_dung_id)->first();
        abort_unless($pt, 403, 'Không tìm thấy hồ sơ huấn luyện viên.');
        return $pt;
    }

    public function students(Request $request)
    {
        $pt = $this->trainer();
        $q = trim($request->query('q', ''));
        $students = DB::table('dang_ky_goi_pt as dk')
            ->join('hoi_vien as hv', 'dk.hoi_vien_id', '=', 'hv.hoi_vien_id')
            ->join('nguoi_dung as nd', 'hv.nguoi_dung_id', '=', 'nd.nguoi_dung_id')
            ->join('goi_pt as gp', 'dk.goi_pt_id', '=', 'gp.goi_pt_id')
            ->where('dk.pt_id', $pt->pt_id)
            ->when($q !== '', fn($b) => $b->where(fn($w) => $w->where('nd.ho_ten','like',"%$q%")
                ->orWhere('nd.email','like',"%$q%") ->orWhere('hv.so_dien_thoai','like',"%$q%")))
            ->select('dk.dang_ky_goi_pt_id','dk.hoi_vien_id','dk.ngay_bat_dau','dk.ngay_ket_thuc','dk.so_buoi_con_lai','dk.trang_thai',
                'nd.ho_ten','nd.email','hv.so_dien_thoai','gp.ten_goi_pt')
            ->orderByDesc('dk.dang_ky_goi_pt_id')->paginate(15)->withQueryString();
        return view('trainer.students', compact('students','q'));
    }

    public function schedules(Request $request)
    {
        $pt = $this->trainer();
        $schedules = DB::table('lich_pt as l')->join('hoi_vien as hv','l.hoi_vien_id','=','hv.hoi_vien_id')
            ->join('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')
            ->join('dang_ky_goi_pt as dk','l.dang_ky_goi_pt_id','=','dk.dang_ky_goi_pt_id')
            ->join('goi_pt as gp','dk.goi_pt_id','=','gp.goi_pt_id')
            ->where('l.pt_id',$pt->pt_id)
            ->when($request->filled('status'), fn($b)=>$b->where('l.trang_thai',$request->status))
            ->select('l.*','nd.ho_ten','gp.ten_goi_pt','dk.so_buoi_con_lai')
            ->orderByDesc('l.thoi_gian_bat_dau')->paginate(20)->withQueryString();
        return view('trainer.schedules', compact('schedules'));
    }

    public function plans()
    {
        $pt = $this->trainer();
        $students = DB::table('dang_ky_goi_pt as dk')->join('hoi_vien as hv','dk.hoi_vien_id','=','hv.hoi_vien_id')
            ->join('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')->where('dk.pt_id',$pt->pt_id)
            ->select('dk.hoi_vien_id','nd.ho_ten')->distinct()->orderBy('nd.ho_ten')->get();
        $plans = DB::table('giao_an_pt as p')->leftJoin('hoi_vien as hv','p.hoi_vien_id','=','hv.hoi_vien_id')
            ->leftJoin('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')->where('p.pt_id',$pt->pt_id)
            ->select('p.*','nd.ho_ten')->orderByDesc('p.created_at')->get();
        return view('trainer.plans', compact('students','plans'));
    }

    public function savePlan(Request $request)
    {
        $pt = $this->trainer();
        $data = $request->validate(['hoi_vien_id'=>'required|integer','ten_giao_an'=>'required|string|max:150','muc_tieu'=>'nullable|string|max:1000','ghi_chu'=>'nullable|string|max:2000','bai_tap'=>'required|array|min:1','bai_tap.*.ten'=>'required|string|max:150','bai_tap.*.so_hiep'=>'nullable|integer|min:1|max:30','bai_tap.*.so_lan'=>'nullable|string|max:50','bai_tap.*.ghi_chu'=>'nullable|string|max:500']);
        $owns = DB::table('dang_ky_goi_pt')->where('pt_id',$pt->pt_id)->where('hoi_vien_id',$data['hoi_vien_id'])->exists();
        abort_unless($owns,403,'Học viên không thuộc danh sách phụ trách của bạn.');
        DB::transaction(function() use($pt,$data) {
            $id=DB::table('giao_an_pt')->insertGetId(['pt_id'=>$pt->pt_id,'hoi_vien_id'=>$data['hoi_vien_id'],'ten_giao_an'=>$data['ten_giao_an'],'muc_tieu'=>$data['muc_tieu']??null,'ghi_chu'=>$data['ghi_chu']??null,'created_at'=>now(),'updated_at'=>now()]);
            foreach($data['bai_tap'] as $i=>$e) DB::table('bai_tap_giao_an_pt')->insert(['plan_id'=>$id,'ten_bai_tap'=>$e['ten'],'so_hiep'=>$e['so_hiep']??null,'so_lan'=>$e['so_lan']??null,'ghi_chu'=>$e['ghi_chu']??null,'thu_tu'=>$i+1]);
        });
        return back()->with('success','Đã tạo giáo án cho học viên.');
    }

    public function logs()
    {
        $pt=$this->trainer();
        $students=DB::table('dang_ky_goi_pt as dk')->join('hoi_vien as hv','dk.hoi_vien_id','=','hv.hoi_vien_id')->join('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')->where('dk.pt_id',$pt->pt_id)->select('dk.hoi_vien_id','nd.ho_ten')->distinct()->orderBy('nd.ho_ten')->get();
        $logs=DB::table('nhat_ky_buoi_tap_pt as n')->join('hoi_vien as hv','n.hoi_vien_id','=','hv.hoi_vien_id')->join('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')->where('n.pt_id',$pt->pt_id)->select('n.*','nd.ho_ten')->orderByDesc('n.ngay_tap')->paginate(15);
        return view('trainer.logs',compact('students','logs'));
    }

    public function saveLog(Request $request)
    {
        $pt=$this->trainer();
        $d=$request->validate(['hoi_vien_id'=>'required|integer','lich_pt_id'=>'nullable|integer','ngay_tap'=>'required|date','noi_dung'=>'required|string|max:3000','ket_qua'=>'nullable|string|max:2000','ghi_chu'=>'nullable|string|max:2000']);
        $owns=DB::table('dang_ky_goi_pt')->where('pt_id',$pt->pt_id)->where('hoi_vien_id',$d['hoi_vien_id'])->exists(); abort_unless($owns,403);
        if(!empty($d['lich_pt_id'])) abort_unless(DB::table('lich_pt')->where('lich_pt_id',$d['lich_pt_id'])->where('pt_id',$pt->pt_id)->where('hoi_vien_id',$d['hoi_vien_id'])->exists(),403);
        DB::table('nhat_ky_buoi_tap_pt')->insert(['pt_id'=>$pt->pt_id,'hoi_vien_id'=>$d['hoi_vien_id'],'lich_pt_id'=>$d['lich_pt_id']??null,'ngay_tap'=>$d['ngay_tap'],'noi_dung'=>$d['noi_dung'],'ket_qua'=>$d['ket_qua']??null,'ghi_chu'=>$d['ghi_chu']??null,'created_at'=>now()]);
        return back()->with('success','Đã lưu nhật ký buổi tập.');
    }

    public function progress()
    {
        $pt=$this->trainer();
        $students=DB::table('dang_ky_goi_pt as dk')->join('hoi_vien as hv','dk.hoi_vien_id','=','hv.hoi_vien_id')->join('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')->where('dk.pt_id',$pt->pt_id)->select('dk.hoi_vien_id','nd.ho_ten')->distinct()->orderBy('nd.ho_ten')->get();
        $records=DB::table('tien_do_hoc_vien_pt as p')->join('hoi_vien as hv','p.hoi_vien_id','=','hv.hoi_vien_id')->join('nguoi_dung as nd','hv.nguoi_dung_id','=','nd.nguoi_dung_id')->where('p.pt_id',$pt->pt_id)->select('p.*','nd.ho_ten')->orderByDesc('p.ngay_ghi_nhan')->paginate(20);
        return view('trainer.progress',compact('students','records'));
    }

    public function saveProgress(Request $request)
    {
        $pt=$this->trainer();
        $d=$request->validate(['hoi_vien_id'=>'required|integer','ngay_ghi_nhan'=>'required|date','can_nang'=>'nullable|numeric|min:1|max:500','chieu_cao'=>'nullable|numeric|min:50|max:250','vong_nguc'=>'nullable|numeric|min:0|max:300','vong_eo'=>'nullable|numeric|min:0|max:300','vong_mong'=>'nullable|numeric|min:0|max:300','ghi_chu'=>'nullable|string|max:2000']);
        abort_unless(DB::table('dang_ky_goi_pt')->where('pt_id',$pt->pt_id)->where('hoi_vien_id',$d['hoi_vien_id'])->exists(),403);
        DB::table('tien_do_hoc_vien_pt')->insert($d+['pt_id'=>$pt->pt_id,'created_at'=>now()]);
        return back()->with('success','Đã cập nhật tiến độ học viên.');
    }
}
