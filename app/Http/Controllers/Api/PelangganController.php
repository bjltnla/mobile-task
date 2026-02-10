<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PelangganController extends Controller
{
    /**
     * Tampilkan semua pelanggan
     */
    public function index()
    {
        $pelanggan = Pelanggan::all();

        return response()->json([
            'status' => true,
            'data'   => $pelanggan
        ]);
    }

    /**
     * Simpan pelanggan baru (Register)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_nama'     => 'required',
            'pelanggan_alamat'   => 'required',
            'pelanggan_notelp'   => 'required',
            'pelanggan_email'    => 'required|email|unique:pelanggan,pelanggan_email',
            'pelanggan_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $pelanggan = Pelanggan::create([
            'pelanggan_nama'     => $request->pelanggan_nama,
            'pelanggan_alamat'   => $request->pelanggan_alamat,
            'pelanggan_notelp'   => $request->pelanggan_notelp,
            'pelanggan_email'    => $request->pelanggan_email,
            'pelanggan_password' => Hash::make($request->pelanggan_password),
            'photo_path'         => $request->photo_path ?? null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pelanggan berhasil didaftarkan',
            'data'    => $pelanggan
        ], 201);
    }

    /**
     * Login pelanggan
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_email'    => 'required|email',
            'pelanggan_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $pelanggan = Pelanggan::where('pelanggan_email', $request->pelanggan_email)->first();

        if (!$pelanggan) {
            return response()->json([
                'status'  => false,
                'message' => 'Email tidak terdaftar'
            ], 404);
        }

        if (!Hash::check($request->pelanggan_password, $pelanggan->pelanggan_password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Password salah'
            ], 401);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => $pelanggan,
            'token' =>  JWTAuth::fromUser($pelanggan)
        ]);
    }

    /**
     * Tampilkan detail pelanggan
     */
    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'status'  => false,
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $pelanggan
        ]);
    }

    /**
     * Update data pelanggan
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'status'  => false,
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'pelanggan_nama'     => 'sometimes|required',
            'pelanggan_alamat'   => 'sometimes|required',
            'pelanggan_notelp'   => 'sometimes|required',
            'pelanggan_email'    => 'sometimes|required|email|unique:pelanggan,pelanggan_email,' . $id,
            'pelanggan_password' => 'sometimes|required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        if ($request->pelanggan_nama) {
            $pelanggan->pelanggan_nama = $request->pelanggan_nama;
        }

        if ($request->pelanggan_alamat) {
            $pelanggan->pelanggan_alamat = $request->pelanggan_alamat;
        }

        if ($request->pelanggan_notelp) {
            $pelanggan->pelanggan_notelp = $request->pelanggan_notelp;
        }

        if ($request->pelanggan_email) {
            $pelanggan->pelanggan_email = $request->pelanggan_email;
        }

        if ($request->pelanggan_password) {
            $pelanggan->pelanggan_password = Hash::make($request->pelanggan_password);
        }

        if ($request->photo_path) {
            $pelanggan->photo_path = $request->photo_path;
        }

        $pelanggan->save();

        return response()->json([
            'status'  => true,
            'message' => 'Data pelanggan berhasil diupdate',
            'data'    => $pelanggan
        ]);
    }

    /**
     * Hapus pelanggan
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'status'  => false,
                'message' => 'Pelanggan tidak ditemukan'
            ], 404);
        }

        $pelanggan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Pelanggan berhasil dihapus'
        ]);
    }
}
