<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Pelanggan extends Authenticatable implements JWTSubject
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';

    public $incrementing = true;     // ⬅️ WAJIB
    protected $keyType = 'int';       // ⬅️ WAJIB

    public $timestamps = false; // ⬅️ PENTING

    protected $fillable = [
        'pelanggan_nama',
        'pelanggan_alamat',
        'pelanggan_notelp',
        'pelanggan_email',
        'pelanggan_password',
        'photo_path',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // primary key
    }

    public function getJWTCustomClaims()
    {
        return []; // bisa dikosongkan
    }
}
