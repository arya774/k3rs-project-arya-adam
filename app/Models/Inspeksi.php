<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspeksi extends Model
{
    use HasFactory;

    protected $table = 'inspeksi';

    protected $fillable = [
        'tanggal',
        'ruangan',
        'nama_petugas_k3rs',
        'paraf_petugas_k3rs',
        'nama_petugas_ruangan',
        'paraf_petugas_ruangan'
    ];

    // =========================
    // RELASI DETAIL INSPEKSI
    // =========================
    public function detailInspeksi()
    {
        return $this->hasMany(DetailInspeksi::class, 'inspeksi_id');
    }

    // =========================
    // RELASI FOTO (FINAL: fotos)
    // =========================
    public function fotos()
    {
        return $this->hasMany(FotoInspeksi::class, 'inspeksi_id');
    }

    // =========================
    // RELASI SUB URAIAN
    // =========================
    public function subUraian()
    {
        return $this->hasManyThrough(
            SubUraian::class,
            DetailInspeksi::class,
            'inspeksi_id',
            'id',
            'id',
            'sub_uraian_id'
        );
    }

    // =========================
    // HELPER (ANTI ERROR NULL)
    // =========================
    public function getJumlahYaAttribute()
    {
        return $this->detailInspeksi
            ? $this->detailInspeksi->where('nilai', 'ya')->count()
            : 0;
    }

    public function getJumlahTidakAttribute()
    {
        return $this->detailInspeksi
            ? $this->detailInspeksi->where('nilai', 'tidak')->count()
            : 0;
    }

    public function getTotalAttribute()
    {
        return $this->detailInspeksi
            ? $this->detailInspeksi->count()
            : 0;
    }

    public function getPersentaseAttribute()
    {
        $total = $this->total;

        return $total > 0
            ? ($this->jumlah_ya / $total) * 100
            : 0;
    }
}