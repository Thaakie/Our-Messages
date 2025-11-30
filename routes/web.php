<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;

// Halaman Depan (Menampilkan Form & List Pesan)
Route::get('/', [SuratController::class, 'index']);

// Proses Simpan Pesan
Route::post('/kirim', [SuratController::class, 'store'])->name('kirim.pesan');
// Route untuk membalas pesan (Penting: ada {id} biar tau surat mana yg dibalas)
Route::post('/reply/{id}', [SuratController::class, 'simpanBalasan'])->name('kirim.balasan');