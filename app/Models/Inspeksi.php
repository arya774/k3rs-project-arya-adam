<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspeksi extends Model
{
    use HasFactory;

    protected $table = 'inspeksi';

    // 🔥 TAMBAH FIELD PETUGAS
    protected $fillable = [
        'tanggal',
        'ruangan',
        'nama_petugas_k3rs',
        'paraf_petugas_k3rs',
        'nama_petugas_ruangan',
        'paraf_petugas_ruangan'
    ];

    // ============================
    // RELASI KE DETAIL INSPEKSI
    // ============================
    public function detailInspeksi()
    {
        return $this->hasMany(DetailInspeksi::class, 'inspeksi_id', 'id');
    }

    // ============================
    // RELASI KE SUB URAIAN (LEWAT DETAIL)
    // ============================
    public function subUraian()
    {
        return $this->hasManyThrough(
            SubUraian::class,
            DetailInspeksi::class,
            'inspeksi_id',     // FK di detail_inspeksi
            'id',              // PK di sub_uraian
            'id',              // PK di inspeksi
            'sub_uraian_id'    // FK ke sub_uraian di detail_inspeksi
        );
    }

    // ============================
    // 🔥 HELPER: HITUNG JUMLAH YA
    // ============================
    public function getJumlahYaAttribute()
    {
        return $this->detailInspeksi->where('nilai', 'ya')->count();
    }

    // ============================
    // 🔥 HELPER: HITUNG JUMLAH TIDAK
    // ============================
    public function getJumlahTidakAttribute()
    {
        return $this->detailInspeksi->where('nilai', 'tidak')->count();
    }

    // ============================
    // 🔥 HELPER: TOTAL
    // ============================
    public function getTotalAttribute()
    {
        return $this->detailInspeksi->count();
    }

    // ============================
    // 🔥 HELPER: PERSENTASE
    // ============================
    public function getPersentaseAttribute()
    {
        $total = $this->total;
        return $total > 0 ? ($this->jumlah_ya / $total) * 100 : 0;
    }
}