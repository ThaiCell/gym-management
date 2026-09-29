<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table = 'nhan_vien';

    protected $primaryKey = 'nhan_vien_id';

    public $timestamps = false;

    protected $fillable = [
        'nguoi_dung_id',
        'chuc_vu',
    ];
}