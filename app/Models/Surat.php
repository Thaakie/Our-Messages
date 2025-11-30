<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    // Tambahkan ini:
    protected $fillable = ['pengirim', 'penerima', 'isi'];

    // Relasi: Surat punya banyak balasan
public function replies()
{
    return $this->hasMany(Reply::class);
}
}