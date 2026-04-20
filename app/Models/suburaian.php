<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $uraian_id
 * @property string $nama_sub_uraian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailInspeksi> $detailInspeksi
 * @property-read int|null $detail_inspeksi_count
 * @property-read \App\Models\Kategori|null $kategori
 * @property-read \App\Models\Uraian $uraian
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian whereNamaSubUraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubUraian whereUraianId($value)
 * @mixin \Eloquent
 */
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
