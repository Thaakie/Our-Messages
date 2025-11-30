<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('replies', function (Blueprint $table) {
        $table->id();
        // Ini kuncinya: Menghubungkan balasan ke ID Surat tertentu
        $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
        
        $table->string('nama'); // Siapa yang balas (Aku/Kamu)
        $table->text('isi_balasan'); // Isi chatnya
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
