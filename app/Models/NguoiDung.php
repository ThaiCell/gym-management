<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NguoiDung extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    public function huanLuyenVien()
    {
        return $this->hasOne(HuanLuyenVien::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function hoiVien()
    {
        return $this->hasOne(HoiVien::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function nhanVien()
    {
        return $this->hasOne(NhanVien::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }
}
