<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast atribut ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * --- Implementasi JWTSubject ---
     */

    public function getJWTIdentifier()
    {
        // Mengembalikan primary key user (ID)
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // Mengembalikan array klaim kustom jika dibutuhkan
        return [];
    }
}