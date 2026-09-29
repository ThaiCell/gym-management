<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyGoiTap extends Model
{
    protected $table = 'dang_ky_goi_tap';

    protected $primaryKey = 'dang_ky_goi_tap_id';

    public $timestamps = false;

    protected $fillable = [
        'hoi_vien_id',
        'goi_tap_id',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_buoi_con_lai',
        'trang_thai',
    ];
}