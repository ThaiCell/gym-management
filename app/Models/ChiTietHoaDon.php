<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_don';

    protected $primaryKey = 'chi_tiet_id';

    public $timestamps = false;

    protected $fillable = [
        'hoa_don_id',
        'goi_tap_id',
        'goi_pt_id',
        'so_luong',
        'don_gia',
        'thanh_tien',
    ];
}