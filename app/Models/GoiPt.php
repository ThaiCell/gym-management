<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoiPt extends Model
{
    protected $table = 'goi_pt';

    protected $primaryKey = 'goi_pt_id';

    public $timestamps = false;

    protected $fillable = [
        'ten_goi_pt',
        'gia',
        'so_buoi',
        'thoi_han_ngay',
        'trang_thai',
    ];
}