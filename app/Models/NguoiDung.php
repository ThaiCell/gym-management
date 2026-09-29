<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';

    protected $primaryKey = 'nguoi_dung_id';

    public $timestamps = false;

    protected $fillable = [
        'vai_tro_id',
        'ho_ten',
        'email',
        'mat_khau',
        'trang_thai',
    ];
}