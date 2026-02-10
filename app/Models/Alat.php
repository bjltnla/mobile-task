<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'alat_id';
    public $timestamps = false;

    protected $fillable = [
        'alat_kategori_id',
        'alat_nama',
        'alat_deskripsi',
        'alat_hargaperhari',
        'alat_stok',
        'photo_path'
    ];

    // relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'alat_kategori_id', 'kategori_id');
    }

    // 🔥 WAJIB ADA (INI KUNCI)
    public function detailPenyewaan()
    {
        return $this->hasMany(
            PenyewaanDetail::class,
            'detail_alat_id',
            'alat_id'
        );
    }
}
