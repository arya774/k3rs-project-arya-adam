<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Uraian extends Model
{
    use HasFactory;

    protected $table = 'uraian';
    protected $fillable = ['kategori_id', 'nama_uraian'];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    // Relasi ke SubUraian
    public function subUraian()
    {
        return $this->hasMany(SubUraian::class, 'uraian_id', 'id');
    }
}
