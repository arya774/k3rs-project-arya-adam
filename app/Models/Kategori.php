<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Uraian;
use App\Models\SubUraian;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori'
    ];

    /**
     * Kategori -> Uraian
     */
    public function uraian()
    {
        return $this->hasMany(Uraian::class, 'kategori_id', 'id');
    }

    /**
     * Kategori -> SubUraian (via Uraian)
     */
    public function subUraian()
    {
        return $this->hasManyThrough(
            SubUraian::class,
            Uraian::class,
            'kategori_id', // FK di uraian
            'uraian_id',   // FK di sub_uraian
            'id',          // PK kategori
            'id'           // PK uraian
        );
    }
}