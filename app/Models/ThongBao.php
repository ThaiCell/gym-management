<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'thong_bao';

    protected $primaryKey = 'thong_bao_id';

    public $timestamps = false;

    protected $fillable = [
        'nguoi_dung_id',
        'tieu_de',
        'noi_dung',
        'da_doc',
        'tao_luc',
    ];
}