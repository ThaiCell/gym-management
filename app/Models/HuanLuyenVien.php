<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuanLuyenVien extends Model
{
    protected $table = 'huan_luyen_vien';

    protected $primaryKey = 'pt_id';

    public $timestamps = false;

    protected $fillable = [
        'nguoi_dung_id',
        'chuyen_mon',
        'thanh_tich',
        'so_dien_thoai',
        'anh',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function dangKyGoiPt()
    {
        return $this->hasMany(DangKyGoiPt::class, 'pt_id', 'pt_id');
    }

    public function lichPt()
    {
        return $this->hasMany(LichPt::class, 'pt_id', 'pt_id');
    }
}