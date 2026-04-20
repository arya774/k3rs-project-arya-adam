<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $inspeksi_id
 * @property string $nilai
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sub_uraian_id
 * @property-read \App\Models\Inspeksi $inspeksi
 * @property-read \App\Models\Kategori|null $kategori
 * @property-read \App\Models\SubUraian $subUraian
 * @property-read \App\Models\Uraian|null $uraian
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereInspeksiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereSubUraianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailInspeksi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetailInspeksi extends Model
{
    use HasFactory;

    protected $table = 'detail_inspeksi';

    // 🔥 TAMBAH CATATAN
    protected $fillable = [
        'inspeksi_id',
        'sub_uraian_id',
        'nilai',
        'catatan'
    ];

    // ============================
    // RELASI KE INSPEKSI
    // ============================
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'inspeksi_id');
    }

    // ============================
    // RELASI KE SUB URAIAN
    // ============================
    public function subUraian()
    {
        return $this->belongsTo(SubUraian::class, 'sub_uraian_id');
    }

    // ============================
    // RELASI KE URAIAN MELALUI SUB URAIAN
    // ============================
    public function uraian()
    {
        return $this->hasOneThrough(
            Uraian::class,
            SubUraian::class,
            'id',           // FK di sub_uraian
            'id',           // FK di uraian
            'sub_uraian_id',// Local key di detail_inspeksi
            'uraian_id'     // Local key di sub_uraian
        );
    }

    // ============================
    // RELASI KE KATEGORI MELALUI URAIAN
    // ============================
    public function kategori()
    {
        return $this->hasOneThrough(
            Kategori::class,
            Uraian::class,
            'id',       // FK di uraian
            'id',       // FK di kategori
            'uraian_id',// Local key di sub_uraian → lewat uraian
            'kategori_id' // Local key di uraian
        );
    }
}