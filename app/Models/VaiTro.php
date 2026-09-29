<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    protected $table = 'vai_tro';

    protected $primaryKey = 'vai_tro_id';

    public $timestamps = false;

    protected $fillable = [
        'ten_vai_tro',
    ];
}