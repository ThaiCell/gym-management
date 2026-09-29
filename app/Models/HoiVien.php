<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoiVien extends Model
{
    protected $table = 'hoi_vien';

    protected $primaryKey = 'hoi_vien_id';

    public $timestamps = false;

    protected $fillable = [
        'nguoi_dung_id',
        'ngay_sinh',
        'so_dien_thoai',
        'dia_chi',
        'ngay_tham_gia',
    ];
}