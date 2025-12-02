<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;

// Halaman Depan (Menampilkan Form & List Pesan)
Route::get('/', [SuratController::class, 'index']);

// Proses Simpan Pesan
// Artinya: Maksimal 3 request per 1 menit per IP
// Tambahkan ->middleware('throttle:5,1') di ujungnya
Route::post('/kirim', [SuratController::class, 'store'])->middleware('throttle:5,1');
// Route untuk membalas pesan (Penting: ada {id} biar tau surat mana yg dibalas)
// Tambahkan middleware throttle juga di sini
Route::post('/reply/{id}', [SuratController::class, 'simpanBalasan'])->middleware('throttle:10,1');
// URL-nya: website.com/hapus-paksa/{id_surat}/{password_rahasia}
Route::get('/hapus-paksa/{id}/{kunci}', [\App\Http\Controllers\SuratController::class, 'hapusRahasia']);