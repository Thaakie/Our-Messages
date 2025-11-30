<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['surat_id', 'nama', 'isi_balasan'];

    // Relasi kebalikan: Balasan milik sebuah surat
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}