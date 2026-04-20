<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $kategori_id
 * @property string $nama_uraian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kategori $kategori
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubUraian> $subUraian
 * @property-read int|null $sub_uraian_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian whereKategoriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian whereNamaUraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Uraian whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
