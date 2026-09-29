<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LopTap extends Model
{
    protected $table = 'lop_tap';

    protected $primaryKey = 'lop_tap_id';

    public $timestamps = false;

    protected $fillable = [
        'ten_lop',
        'mo_ta',
        'lich_tap',
        'suc_chua',
        'trang_thai',
    ];
}