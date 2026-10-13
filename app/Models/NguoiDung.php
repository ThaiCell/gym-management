<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class NguoiDung extends Authenticatable
{
    use HasApiTokens;

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

    protected $hidden = [
        'mat_khau',
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }
}
