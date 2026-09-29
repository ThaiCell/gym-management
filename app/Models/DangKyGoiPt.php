<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyGoiPt extends Model
{
    protected $table = 'dang_ky_goi_pt';

    protected $primaryKey = 'dang_ky_goi_pt_id';

    public $timestamps = false;

    protected $fillable = [
        'hoi_vien_id',
        'goi_pt_id',
        'pt_id',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_buoi_con_lai',
        'trang_thai',
    ];
}