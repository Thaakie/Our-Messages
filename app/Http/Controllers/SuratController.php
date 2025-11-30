<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat; // Jangan lupa panggil Modelnya

class SuratController extends Controller
{
    // 1. Menampilkan halaman utama & semua pesan
    public function index() {
        // Ambil data dari terbaru ke terlama
        $pesan = Surat::latest()->get(); 
        return view('halaman-surat', compact('pesan'));
    }

    // 2. Menyimpan pesan baru
// Ganti function store yang lama dengan yang ini
    public function store(Request $request) {
        // Validasi dengan pesan custom
        $request->validate([
            'pengirim' => 'required|max:30',
            'penerima' => 'required|max:30',
            'isi' => 'required',
        ], [
            // Ini pesan error custom-nya
            'pengirim.required' => 'Nama pengirim harus diisi dong...',
            'penerima.required' => 'Suratnya buat siapa? Diisi ya...',
            'isi.required' => 'Jangan lupa tulis pesan manisnya di sini.',
        ]);

        Surat::create($request->all());

        return redirect('/')->with('sukses', 'Surat berhasil terkirim! ;D');
    }

    // Fungsi untuk menyimpan balasan
    public function simpanBalasan(Request $request, $id) {
        $request->validate([
            'nama_balas' => 'required',
            'isi_balasan' => 'required',
        ]);

        // Simpan ke tabel replies
        \App\Models\Reply::create([
            'surat_id' => $id, // ID surat yang sedang dibalas
            'nama' => $request->nama_balas,
            'isi_balasan' => $request->isi_balasan,
        ]);

        return redirect()->back()->with('sukses', 'Balasan terkirim! 💬');
    }
}