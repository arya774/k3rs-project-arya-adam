<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['nama_kategori'];

    // Relasi ke Uraian (1 kategori bisa punya banyak uraian)
    public function uraian()
    {
        return $this->hasMany(Uraian::class, 'kategori_id', 'id');
    }

    // Relasi ke SubUraian melalui Uraian
    public function subUraian()
    {
        return $this->hasManyThrough(
            SubUraian::class, // Model akhir
            Uraian::class,    // Model perantara
            'kategori_id',    // FK di tabel uraian
            'uraian_id',      // FK di tabel sub_uraian
            'id',             // PK kategori
            'id'              // PK uraian
        );
    }
}