<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // GET semua kategori
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Kategori::all()
        ]);
    }

    // POST tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'kategori_nama' => 'required|string'
        ]);

        $kategori = Kategori::create([
            'kategori_nama' => $request->kategori_nama
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ]);
    }

    // GET by ID
    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $kategori
        ]);
    }

    // PUT update
    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $kategori->update([
            'kategori_nama' => $request->kategori_nama
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => $kategori
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
