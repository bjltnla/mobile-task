<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PenyewaanDetail;
use Illuminate\Support\Facades\DB;

class PenyewaanController extends Controller
{
    /* =====================================================
       GET SEMUA DATA PENYEWAAN
       (UNTUK TABLE + PAGINATION + SEARCH)
    ===================================================== */
    public function index(Request $request)
    {
        $query = Penyewaan::with('pelanggan')->orderByDesc('penyewaan_id');

        // ================== SEARCH ==================
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pelanggan', function($q) use ($search) {
                $q->where('pelanggan_nama', 'like', "%$search%");
            });
        }

        // ================== PAGINATION ==================
        $perPage = 5; // jumlah data per halaman
        $page = $request->get('page', 1);
        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => true,
            'data'   => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ]
        ]);
    }

    /* =====================================================
       DETAIL PENYEWAAN
       (UNTUK MODAL DETAIL)
    ===================================================== */
    public function show($id)
    {
        $penyewaan = Penyewaan::with(['pelanggan', 'detail.alat'])
            ->where('penyewaan_id', $id)
            ->first();

        if (!$penyewaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Data penyewaan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'pelanggan' => [
                    'pelanggan_nama'   => $penyewaan->pelanggan->pelanggan_nama,
                    'pelanggan_telp'   => $penyewaan->pelanggan->pelanggan_telp,
                    'pelanggan_alamat' => $penyewaan->pelanggan->pelanggan_alamat,
                ],
                'penyewaan_tglsewa'       => $penyewaan->penyewaan_tglsewa,
                'penyewaan_tglkembali'    => $penyewaan->penyewaan_tglkembali,
                'penyewaan_sttpembayaran' => $penyewaan->penyewaan_sttpembayaran,
                'penyewaan_sttkembali'    => $penyewaan->penyewaan_sttkembali,
                'penyewaan_totalharga'    => $penyewaan->penyewaan_totalharga,
                'detail' => $penyewaan->detail->map(function ($d) {
                    return [
                        'alat_id'   => $d->alat_id,
                        'nama_alat' => $d->alat->alat_nama,
                        'jumlah'    => $d->detail_jumlah,
                        'harga'     => $d->detail_harga,
                        'total'     => $d->detail_total,
                    ];
                })
            ]
        ]);
    }

    /* =====================================================
       SIMPAN PENYEWAAN BARU
    ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'penyewaan_pelanggan_id' => 'required',
            'penyewaan_tglsewa'      => 'required|date',
            'penyewaan_tglkembali'   => 'required|date',
            'penyewaan_totalharga'   => 'required|numeric',
        ]);

        $penyewaan = Penyewaan::create([
            'penyewaan_pelanggan_id' => $request->penyewaan_pelanggan_id,
            'penyewaan_tglsewa'      => $request->penyewaan_tglsewa,
            'penyewaan_tglkembali'   => $request->penyewaan_tglkembali,
            'penyewaan_totalharga'   => $request->penyewaan_totalharga,
            'penyewaan_sttpembayaran'=> 'belum_lunas',
            'penyewaan_sttkembali'   => 'disewa',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Penyewaan berhasil ditambahkan',
            'data'    => $penyewaan
        ]);
    }

    public function sewa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.penyewaan_pelanggan_id' => 'required|integer|exists:pelanggan,pelanggan_id',
            'data.penyewaan_tglsewa'      => 'required|date',
            'data.penyewaan_tglkembali'      => 'required|date',
            'data.penyewaan_totalharga'  => 'required|numeric|min:0',
            'items'                      => 'required|array|min:1',
            'items.*.id'                 => 'required|integer|exists:alat,alat_id',
            'items.*.qty'                => 'required|integer|min:1',
            'items.*.price'              => 'required|numeric|min:0',
            'items.*.total'              => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // 1. Simpan penyewaan (header)
            $penyewaan = Penyewaan::create([
                'penyewaan_pelanggan_id' => $request->data['penyewaan_pelanggan_id'],
                'penyewaan_tglsewa'      => $request->data['penyewaan_tglsewa'],
                'penyewaan_tglkembali'   => $request->data['penyewaan_tglkembali'],
                'penyewaan_totalharga'  => $request->data['penyewaan_totalharga'],
                'penyewaan_sttpembayaran'=> 'belum',
                'penyewaan_sttkembali'   => 'belum',
            ]);

            // 2. Simpan detail penyewaan
            foreach ($request->items as $item) {
                PenyewaanDetail::create([
                    'detail_penyewaan_id' => $penyewaan->penyewaan_id,
                    'detail_alat_id'      => $item['id'],
                    'detail_jumlah'       => $item['qty'],
                    'detail_harga'        => $item['price'],
                    'detail_total'        => $item['total'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Penyewaan berhasil disimpan',
                'data'   => $penyewaan->load('detail')
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan penyewaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function riwayat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required|integer|exists:pelanggan,pelanggan_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $riwayat = Penyewaan::with([
                'detail.alat'
            ])
            ->where('penyewaan_pelanggan_id', $request->pelanggan_id)
            ->orderBy('penyewaan_tglsewa', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $riwayat
        ]);
    }

    /* =====================================================
       UPDATE STATUS PEMBAYARAN & PENGEMBALIAN
    ===================================================== */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'penyewaan_sttpembayaran' => 'required|in:lunas,belum_lunas',
            'penyewaan_sttkembali'    => 'required|in:dikembalikan,disewa',
        ]);

        $penyewaan = Penyewaan::where('penyewaan_id', $id)->first();

        if (!$penyewaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Data penyewaan tidak ditemukan'
            ], 404);
        }

        $penyewaan->penyewaan_sttpembayaran = $request->penyewaan_sttpembayaran;
        $penyewaan->penyewaan_sttkembali    = $request->penyewaan_sttkembali;
        $penyewaan->save();

        return response()->json([
            'status'  => true,
            'message' => 'Status penyewaan berhasil diupdate',
            'data'    => $penyewaan
        ]);
    }

    /* =====================================================
       DELETE PENYEWAAN
       sekaligus kembalikan stok alat
    ===================================================== */
    public function destroy($id)
    {
        $penyewaan = Penyewaan::with('detail')->find($id);

        if (!$penyewaan) {
            return response()->json([
                'status' => false,
                'message'=> 'Data penyewaan tidak ditemukan'
            ], 404);
        }

        // kembalikan stok alat
        foreach ($penyewaan->detail as $item) {
            $alat = Alat::find($item->alat_id);
            if ($alat) {
                $alat->alat_stok += $item->detail_jumlah;
                $alat->save();
            }
        }

        $penyewaan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Penyewaan berhasil dihapus'
        ]);
    }
}
