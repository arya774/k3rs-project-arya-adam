<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $nama_kategori
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubUraian> $subUraian
 * @property-read int|null $sub_uraian_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Uraian> $uraian
 * @property-read int|null $uraian_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereNamaKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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