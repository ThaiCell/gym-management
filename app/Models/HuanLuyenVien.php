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
        'so_dien_thoai',
    ];
}