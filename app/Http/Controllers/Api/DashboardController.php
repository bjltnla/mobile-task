<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Penyewaan;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil total alat
        $totalAlat = Alat::count();

        // Ambil total stok (jumlah semua stok alat)
        $totalStok = Alat::sum('alat_stok');

        // Ambil total penyewaan
        $totalSewa = Penyewaan::count();

        // Data chart penyewaan per pelanggan
        $penyewaan = Penyewaan::select('penyewaan_pelanggan_id')
            ->get()
            ->groupBy('penyewaan_pelanggan_id')
            ->map(function($item) {
                return count($item);
            });

        // Kirim ke view
        return view('admin.dashboard', [
            'totalAlat' => $totalAlat,
            'totalStok' => $totalStok,
            'totalSewa' => $totalSewa,
            'chartLabels' => json_encode($penyewaan->keys()),
            'chartData' => json_encode($penyewaan->values()),
        ]);
    }
}
