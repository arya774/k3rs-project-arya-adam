<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubUraian extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai database
    protected $table = 'sub_uraian'; // jika di DB pakai sub_uraian
    protected $fillable = ['uraian_id', 'nama_sub_uraian'];

    // ============================
    // Relasi ke Uraian
    // ============================
    public function uraian()
    {
        return $this->belongsTo(Uraian::class, 'uraian_id', 'id');
    }

    // ============================
    // Relasi ke DetailInspeksi
    // ============================
    public function detailInspeksi()
    {
        return $this->hasMany(DetailInspeksi::class, 'sub_uraian_id', 'id');
    }

    // ============================
    // Relasi shortcut ke Kategori melalui Uraian
    // ============================
    public function kategori()
    {
        return $this->hasOneThrough(
            Kategori::class,
            Uraian::class,
            'id',          // FK Uraian -> Kategori (uraian.id)
            'id',          // PK Kategori
            'uraian_id',   // FK SubUraian -> Uraian
            'kategori_id'  // FK Uraian -> Kategori
        );
    }
}