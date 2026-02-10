<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    protected $table = 'admin'; // nama tabel di database
    protected $primaryKey = 'admin_id'; // ganti sesuai primary key di tabelmu
    public $timestamps = false; // jika tabel tidak ada timestamps

    protected $fillable = [
        'admin_username',
        'admin_password',
    ];

    protected $hidden = [
        'admin_password',
    ];

    // ===== JWT Methods =====
    public function getJWTIdentifier()
    {
        return $this->getKey(); // primary key
    }

    public function getJWTCustomClaims()
    {
        return []; // bisa dikosongkan
    }
}
