<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Message</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('ICONCAT.PNG') }}" type="image/x-icon">
    
    <style>
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-rose-50 min-h-screen text-gray-700">

    <header class="bg-white shadow-sm border-b border-rose-100 py-10 mb-8">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-rose-500 mb-3">Ruang Bercerita Kita :3</h2>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">
                Tuliskan apa yang ingin kamu sampaikan. Sederhana, namun bermakna. <br>
                Pesan ini akan tersimpan abadi di sini. <br> Another Personal Project / Test Deploy
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-6 pb-20">
        
        @if(session('sukses'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center">
                {{ session('sukses') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-rose-100 sticky top-10">
                    <h3 class="text-xl font-bold mb-4 text-rose-600 flex items-center gap-2">
                         Tulis Surat
                    </h3>
                    
                    <form action="https://our-messages-production.up.railway.app/kirim" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2 text-gray-600">Dari Siapa?</label>
                            <input type="text" name="pengirim" 
                                class="w-full border-2 border-rose-100 p-3 rounded-xl focus:outline-none focus:border-rose-400 transition"
                                placeholder="Nama kamu / Anonim" value="{{ old('pengirim') }}">
                            @error('pengirim') 
                                <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2 text-gray-600">Untuk Siapa?</label>
                            <input type="text" name="penerima" 
                                class="w-full border-2 border-rose-100 p-3 rounded-xl focus:outline-none focus:border-rose-400 transition"
                                placeholder="Nama dia..." value="{{ old('penerima') }}">
                            @error('penerima') 
                                <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold mb-2 text-gray-600">Isi Pesan</label>
                            <textarea name="isi" rows="4" 
                                class="w-full border-2 border-rose-100 p-3 rounded-xl focus:outline-none focus:border-rose-400 transition"
                                placeholder="Tuliskan sesuatu yang manis..."></textarea>
                            @error('isi') 
                                <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p> 
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-rose-500 text-white font-bold py-3 rounded-xl hover:bg-rose-600 shadow-md transform hover:scale-105 transition duration-200">
                            Kirim Surat 🚀
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-xl font-bold mb-4 text-gray-600"> Pesan Terkumpul</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
@foreach($pesan as $p)
                    <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition border border-rose-50 flex flex-col h-full">
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-xs text-rose-400 font-bold uppercase tracking-wider">Dari</p>
                                    <p class="font-bold text-gray-800">{{ $p->pengirim }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-purple-400 font-bold uppercase tracking-wider">Untuk</p>
                                    <p class="font-bold text-gray-800">{{ $p->penerima }}</p>
                                </div>
                            </div>
                            <hr class="border-rose-50 my-2">
                            <p class="text-gray-600 italic leading-relaxed text-lg font-medium">
                                "{{ $p->isi }}"
                            </p>
                            <div class="text-right mt-2">
                                <span class="text-xs text-gray-300">{{ $p->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3 flex-grow overflow-y-auto max-h-60 mb-3 space-y-3">
                            @if($p->replies->count() > 0)
                                @foreach($p->replies as $reply)
                                    <div class="bg-white p-2 rounded-lg shadow-sm text-sm border-l-2 border-rose-300">
                                        <span class="font-bold text-rose-500 text-xs block mb-1">{{ $reply->nama }}:</span>
                                        <span class="text-gray-600">{{ $reply->isi_balasan }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-xs text-gray-400 py-2">Belum ada balasan. Mulai obrolan? 😊</p>
                            @endif
                        </div>

                        <form action="https://our-messages-production.up.railway.app/reply/{{ $p->id }}" method="POST" class="mt-auto pt-2 border-t border-gray-100">
                            @csrf
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="nama_balas" placeholder="Aku/Kamu" class="w-1/3 text-xs border p-2 rounded focus:outline-none focus:border-rose-400" required>
                                <input type="text" name="isi_balasan" placeholder="Balas pesan ini..." class="w-2/3 text-xs border p-2 rounded focus:outline-none focus:border-rose-400" required>
                            </div>
                            <button type="submit" class="w-full bg-rose-100 text-rose-500 text-xs font-bold py-2 rounded hover:bg-rose-200 transition">
                                Kirim Balasan ↩
                            </button>
                        </form>

                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <footer class="bg-white border-t border-rose-100 mt-12 py-8">
        <div class="max-w-6xl mx-auto px-6 text-center">
            
            <p class="text-gray-600 font-medium">
                Dibuat dengan sepenuh 💖 oleh <span class="text-rose-500 font-bold">Blackieeeee hehe ;D</span>
            </p>
            
            <p class="text-xs text-gray-400 mt-2">
                &copy; {{ date('Y') }} Ruang Bercerita. Jangan lupa senyum hari ini!
            </p>

            <div class="flex justify-center gap-4 mt-4">
                <a href="#" class="text-gray-400 hover:text-rose-500 transition">Instagram</a>
                <span class="text-gray-300">•</span>
                <a href="#" class="text-gray-400 hover:text-rose-500 transition">Twitter</a>
                <span class="text-gray-300">•</span>
                <a href="#" class="text-gray-400 hover:text-rose-500 transition">Github</a>
            </div>

        </div>
    </footer>

</body>
</html>
</body>
</html>