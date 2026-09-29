<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_don';

    protected $primaryKey = 'hoa_don_id';

    public $timestamps = false;

    protected $fillable = [
        'hoi_vien_id',
        'nhan_vien_id',
        'ngay_lap',
        'tong_tien',
        'trang_thai',
        'phuong_thuc_thanh_toan',
    ];
}