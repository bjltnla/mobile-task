<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyewaanDetail extends Model
{
    protected $table = 'penyewaan_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'detail_penyewaan_id',
        'detail_alat_id',
        'detail_jumlah',
        'detail_harga',
        'detail_total'
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'detail_alat_id');
    }

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'detail_penyewaan_id');
    }
}
