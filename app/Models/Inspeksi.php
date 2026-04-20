<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $tanggal
 * @property string $ruangan
 * @property string|null $nama_petugas_k3rs
 * @property string|null $paraf_petugas_k3rs
 * @property string|null $nama_petugas_ruangan
 * @property string|null $paraf_petugas_ruangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailInspeksi> $detailInspeksi
 * @property-read int|null $detail_inspeksi_count
 * @property-read mixed $jumlah_tidak
 * @property-read mixed $jumlah_ya
 * @property-read mixed $persentase
 * @property-read mixed $total
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubUraian> $subUraian
 * @property-read int|null $sub_uraian_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereNamaPetugasK3rs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereNamaPetugasRuangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereParafPetugasK3rs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereParafPetugasRuangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereRuangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspeksi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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