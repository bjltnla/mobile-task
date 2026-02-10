<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';

    public $incrementing = true;   // 🔥 WAJIB SQL SERVER
    protected $keyType = 'int';    // 🔥 WAJIB SQL SERVER
    public $timestamps = false;

    protected $fillable = [
        'kategori_nama'
    ];
}
