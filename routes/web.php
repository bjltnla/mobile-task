<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard');
    Route::view('/kategori', 'admin.kategori');
    Route::view('/alat', 'admin.alat');
    Route::view('/penyewaan', 'admin.penyewaan');
    Route::view('/pelanggan', 'admin.pelanggan');
});

Route::get('/admin/login', function () {
    return view('admin.login');
});



