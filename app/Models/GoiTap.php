<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoiTap extends Model
{
    protected $table = 'goi_tap';

    protected $primaryKey = 'goi_tap_id';

    public $timestamps = false;

    protected $fillable = [
        'ten_goi',
        'gia',
        'thoi_han_ngay',
        'so_buoi',
        'trang_thai',
    ];
}