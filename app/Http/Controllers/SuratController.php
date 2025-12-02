<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat; 

class SuratController extends Controller
{
    // 1. Menampilkan halaman utama & semua pesan
    public function index() {
        $pesan = Surat::latest()->get(); 
        return view('halaman-surat', compact('pesan'));
    }

    // 2. Menyimpan pesan baru (SUDAH DIAMANKAN 🛡️)
    public function store(Request $request) {
        
        // --- 🛡️ FITUR KEAMANAN: HONEYPOT ---
        // Kalau field tersembunyi ini diisi, berarti dia BOT/SCRIPT!
        if ($request->filled('bukan_robot')) {
            // Kita tipu bot-nya: Bilang sukses, tapi aslinya GAK disimpan.
            return redirect('/')->with('sukses', 'Surat berhasil terkirim! ;D');
        }
        // ------------------------------------\

        // 2. 🔥 BARU: CEK DUPLIKAT 🔥
    // Cek apakah IP ini sudah pernah kirim pesan yang ISINYA SAMA PERSIS hari ini?
    $isSpam = \App\Models\Surat::where('isi', $request->isi)
                ->where('created_at', '>', now()->subHours(24)) // Cek 24 jam terakhir
                ->exists();

    if ($isSpam) {
        // Kalau isinya sama persis, kita tolak halus
        return redirect()->back()->withErrors(['isi' => 'Pesan ini sudah pernah dikirim sebelumnya. Jangan nyepam ya! 😜']);
    }

        // Validasi Normal
        $request->validate([
            'pengirim' => 'required|max:30',
            'penerima' => 'required|max:30',
            'isi' => 'required',
        ], [
            'pengirim.required' => 'Nama pengirim harus diisi dong...',
            'penerima.required' => 'Suratnya buat siapa? Diisi ya...',
            'isi.required' => 'Jangan lupa tulis pesan manisnya di sini.',
        ]);

        // Simpan ke Database
        // Kita sebutkan satu-satu field-nya biar aman & rapi
        Surat::create([
            'pengirim' => $request->pengirim,
            'penerima' => $request->penerima,
            'isi'      => $request->isi,
            // 'bukan_robot' tidak kita masukkan ke sini
        ]);

        return redirect('/')->with('sukses', 'Surat berhasil terkirim! ;D');
    }

// Fungsi untuk menyimpan balasan (SUDAH DIAMANKAN 🛡️)
    public function simpanBalasan(Request $request, $id) {
        
        // --- 1. JEBAKAN HONEYPOT ---
        // Pastikan nama input di HTML nanti adalah 'bukan_robot_reply'
        if ($request->filled('bukan_robot_reply')) {
            // Fake Success: Bilang terkirim padahal enggak
            return redirect()->back()->with('sukses', 'Balasan terkirim! 💬');
        }

        // --- 2. CEK DUPLIKAT (Anti Spam Reply) ---
        // Cek apakah user/ip ini pernah kirim balasan yg isinya SAMA PERSIS ke surat INI hari ini?
        $isSpam = \App\Models\Reply::where('surat_id', $id)
                    ->where('isi_balasan', $request->isi_balasan)
                    ->where('nama', $request->nama_balas) // Cek nama juga
                    ->where('created_at', '>', now()->subHours(1)) // Dalam 1 jam terakhir
                    ->exists();

        if ($isSpam) {
            return redirect()->back()->withErrors(['isi_balasan' => 'Eits, balasan ini sudah pernah dikirim. Jangan nyepam ya! 😜']);
        }

        // --- 3. VALIDASI NORMAL ---
        $request->validate([
            'nama_balas' => 'required|max:20',
            'isi_balasan' => 'required|max:200',
        ]);

        // --- 4. SIMPAN ---
        \App\Models\Reply::create([
            'surat_id' => $id, 
            'nama' => $request->nama_balas,
            'isi_balasan' => $request->isi_balasan,
            // 'bukan_robot_reply' tidak ikut disimpan
        ]);

        return redirect()->back()->with('sukses', 'Balasan terkirim! 💬');
    }
}