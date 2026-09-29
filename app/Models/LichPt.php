<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichPt extends Model
{
    protected $table = 'lich_pt';

    protected $primaryKey = 'lich_pt_id';

    public $timestamps = false;

    protected $fillable = [
        'dang_ky_goi_pt_id',
        'hoi_vien_id',
        'pt_id',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'trang_thai',
    ];
}