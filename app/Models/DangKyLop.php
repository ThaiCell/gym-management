<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyLop extends Model
{
    protected $table = 'dang_ky_lop';

    protected $primaryKey = 'dang_ky_lop_id';

    public $timestamps = false;

    protected $fillable = [
        'lop_tap_id',
        'hoi_vien_id',
        'ngay_dang_ky',
        'trang_thai',
    ];
}