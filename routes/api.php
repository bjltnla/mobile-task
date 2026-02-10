<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\AlatController;
use App\Http\Controllers\Api\PenyewaanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PelangganController;

Route::apiResource('penyewaan', PenyewaanController::class);
Route::get('/pelanggan', [PelangganController::class, 'index']);


Route::post('auth/login', [AuthController::class, 'login']);
Route::post('pelanggan/login', [PelangganController::class, 'login']);
Route::post('pelanggan/register', [PelangganController::class, 'store']);


// Protect admin routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    Route::resource('/admin/kategori', KategoriController::class);
    Route::resource('/admin/alat', AlatController::class);
    Route::resource('/admin/penyewaan', PenyewaanController::class);
});


Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
});


Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori',[KategoriController::class, 'store']);
Route::put('/kategori/{id}',[KategoriController::class, 'update']);
Route::get('/kategori/{id}',[KategoriController::class, 'show']);
Route::delete('/kategori/{id}',[KategoriController::class, 'destroy']);

Route::get('/alat', [AlatController::class, 'index']);
Route::post('/alat', [AlatController::class, 'store']);
Route::get('/alat/{id}', [AlatController::class, 'show']);
Route::put('/alat/{id}', [AlatController::class, 'update']);
Route::delete('/alat/{id}', [AlatController::class, 'destroy']);

Route::get('/penyewaan', [PenyewaanController::class, 'index']);
Route::get('/penyewaan/{id}', [PenyewaanController::class, 'show']);
Route::post('/penyewaan', [PenyewaanController::class, 'store']);
Route::put('/penyewaan/{id}/status', [PenyewaanController::class, 'updateStatus']);
Route::delete('/penyewaan/{id}', [PenyewaanController::class, 'destroy']);


// Dashboard API
Route::get('/dashboard', [DashboardController::class, 'dashboard']);

Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::post('/pelanggan', [PelangganController::class, 'store']);
Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);
Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);



// Public Routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (butuh token JWT)
Route::middleware(['jwt.auth'])->prefix('admin')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/penyewaan/{id}', [PenyewaanController::class, 'show']);

Route::get('/test', function () {
    return response()->json([
        'message' => 'API Laravel connect'
    ]);
});

Route::post('/pelanggan/login', [PelangganController::class, 'login']);
Route::apiResource('/pelanggan', PelangganController::class);
