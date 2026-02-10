<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    /**
     * GET semua alat
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Alat::with('kategori')->get()
        ]);
    }

    /**
     * POST tambah alat
     */
    public function store(Request $request)
    {
        $request->validate([
            'alat_kategori_id' => 'required|exists:kategori,kategori_id',
            'alat_nama' => 'required|string|max:100',
            'alat_deskripsi' => 'nullable|string',
            'alat_hargaperhari' => 'required|integer',
            'alat_stok' => 'required|integer',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('alat', 'public');
        }

        $alat = Alat::create([
            'alat_kategori_id' => $request->alat_kategori_id,
            'alat_nama' => $request->alat_nama,
            'alat_deskripsi' => $request->alat_deskripsi,
            'alat_hargaperhari' => $request->alat_hargaperhari,
            'alat_stok' => $request->alat_stok,
            'photo_path' => $photoPath
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Alat berhasil ditambahkan',
            'data' => $alat->load('kategori')
        ], 201);
    }

    /**
     * GET alat by ID
     */
    public function show($id)
    {
        $alat = Alat::with('kategori')->find($id);

        if (!$alat) {
            return response()->json([
                'status' => false,
                'message' => 'Alat tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $alat
        ]);
    }

    /**
     * PUT update alat
     */
    public function update(Request $request, $id)
    {
        $alat = Alat::find($id);

        if (!$alat) {
            return response()->json([
                'status' => false,
                'message' => 'Alat tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'alat_kategori_id' => 'sometimes|exists:kategori,kategori_id',
            'alat_nama' => 'sometimes|string|max:100',
            'alat_deskripsi' => 'nullable|string',
            'alat_hargaperhari' => 'sometimes|integer',
            'alat_stok' => 'sometimes|integer',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            // hapus foto lama
            if ($alat->photo_path && Storage::disk('public')->exists($alat->photo_path)) {
                Storage::disk('public')->delete($alat->photo_path);
            }

            $alat->photo_path = $request->file('photo')->store('alat', 'public');
        }

        $alat->update($request->except('photo'));

        return response()->json([
            'status' => true,
            'message' => 'Alat berhasil diperbarui',
            'data' => $alat->load('kategori')
        ]);
    }

    /**
     * DELETE alat
     */
    public function destroy($id)
    {
        $alat = Alat::find($id);

        if (!$alat) {
            return response()->json([
                'status' => false,
                'message' => 'Alat tidak ditemukan'
            ], 404);
        }

        // hapus foto jika ada
        if ($alat->photo_path && Storage::disk('public')->exists($alat->photo_path)) {
            Storage::disk('public')->delete($alat->photo_path);
        }

        $alat->delete();

        return response()->json([
            'status' => true,
            'message' => 'Alat berhasil dihapus'
        ]);
    }
}
