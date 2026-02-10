<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    protected $table = 'penyewaan';
    protected $primaryKey = 'penyewaan_id';
    public $timestamps = false;

    protected $fillable = [
        'penyewaan_pelanggan_id',
        'penyewaan_tglsewa',
        'penyewaan_tglkembali',
        'penyewaan_totalharga',
        'penyewaan_sttpembayaran',
        'penyewaan_sttkembali'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'penyewaan_pelanggan_id');
    }

    public function detail()
    {
        return $this->hasMany(PenyewaanDetail::class, 'detail_penyewaan_id');
    }
}
