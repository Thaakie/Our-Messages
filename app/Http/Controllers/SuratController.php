<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Reply; // Tambahkan ini biar rapi
use Illuminate\Support\Facades\Http; // Wajib ada untuk kirim ke Discord

class SuratController extends Controller
{
    // 1. Menampilkan halaman utama & semua pesan
    public function index() {
        $pesan = Surat::latest()->get(); 
        return view('halaman-surat', compact('pesan'));
    }

    // 2. Menyimpan pesan baru (Full Security + Discord 🛡️)
    public function store(Request $request) {
        
        // --- TAHAP 1: CEK HONEYPOT (BOT BODOH) ---
        if ($request->filled('bukan_robot')) {
            // 🔥 Kirim Laporam ke Discord
            try {
                Http::post('Mhttps://discord.com/api/webhooks/1445421976326766722/Wf8TKiH14vMAgaVrJMxL65TOiFCw9LObwZYUmMU1DeWWJpf8LdOG0oVT_9sM8aNQeOCI', [
                    'content' => "🚨 **ALARM HONEYPOT!**\nAda Bot (IP: {$request->ip()}) kena jebakan input tersembunyi! 🍯"
                ]);
            } catch (\Exception $e) { } // Abaikan jika Discord error

            // Fake Success
            return redirect('/')->with('sukses', 'Surat berhasil terkirim! ;D');
        }

        // --- TAHAP 2: CEK DUPLIKAT (BOT PINTAR) ---
        $isSpam = Surat::where('isi', $request->isi)
                    ->where('created_at', '>', now()->subHours(24))
                    ->exists();

        if ($isSpam) {
            // 🔥 Kirim Laporam ke Discord
            try {
                Http::post('MASUKKAN_URL_WEBHOOK_DISCORD_KAMU_DISINI', [
                    'content' => "⚠️ **ALARM SPAM DUPLIKAT!**\nIP: {$request->ip()} mencoba kirim pesan yang sama persis! 👯"
                ]);
            } catch (\Exception $e) { }

            return redirect()->back()->withErrors(['isi' => 'Pesan ini sudah pernah dikirim. Jangan nyepam ya! 😜']);
        }

        // --- TAHAP 3: VALIDASI DATA ---
        $request->validate([
            'pengirim' => 'required|max:30',
            'penerima' => 'required|max:30',
            'isi' => 'required',
        ], [
            'pengirim.required' => 'Nama pengirim harus diisi dong...',
            'penerima.required' => 'Suratnya buat siapa? Diisi ya...',
            'isi.required' => 'Jangan lupa tulis pesan manisnya di sini.',
        ]);

        // --- TAHAP 4: SIMPAN KE DATABASE ---
        Surat::create([
            'pengirim' => $request->pengirim,
            'penerima' => $request->penerima,
            'isi'      => $request->isi,
            // 'bukan_robot' otomatis dibuang karena tidak disebut disini
        ]);

        return redirect('/')->with('sukses', 'Surat berhasil terkirim! ;D');
    }

    // 3. Menyimpan balasan (Full Security 🛡️)
    public function simpanBalasan(Request $request, $id) {
        
        // --- 1. JEBAKAN HONEYPOT REPLY ---
        if ($request->filled('bukan_robot_reply')) {
            return redirect()->back()->with('sukses', 'Balasan terkirim! 💬');
        }

        // --- 2. CEK DUPLIKAT REPLY ---
        $isSpam = Reply::where('surat_id', $id)
                    ->where('isi_balasan', $request->isi_balasan)
                    ->where('nama', $request->nama_balas)
                    ->where('created_at', '>', now()->subHours(1))
                    ->exists();

        if ($isSpam) {
            return redirect()->back()->withErrors(['isi_balasan' => 'Eits, balasan ini sudah pernah dikirim. Jangan nyepam ya! 😜']);
        }

        // --- 3. VALIDASI NORMAL ---
        $request->validate([
            'nama_balas' => 'required|max:20',
            'isi_balasan' => 'required|max:200',
        ]);

        // --- 4. SIMPAN REPLY ---
        Reply::create([
            'surat_id' => $id, 
            'nama' => $request->nama_balas,
            'isi_balasan' => $request->isi_balasan,
        ]);

        return redirect()->back()->with('sukses', 'Balasan terkirim! 💬');
    }
}