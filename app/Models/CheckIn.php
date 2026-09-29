<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $table = 'check_in';

    protected $primaryKey = 'check_in_id';

    public $timestamps = false;

    protected $fillable = [
        'hoi_vien_id',
        'dang_ky_goi_tap_id',
        'thoi_gian',
        'nguoi_thuc_hien_id',
    ];
}