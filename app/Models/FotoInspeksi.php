<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class FotoInspeksi extends Model
{
    use HasFactory;

    // =========================
    // TABLE
    // =========================
    protected $table = 'foto_inspeksi';

    // =========================
    // MASS ASSIGNMENT
    // =========================
    protected $fillable = [
        'inspeksi_id',
        'path',
        'nama_file',
        'mime_type',
        'size',
        'disk',
        'keterangan',
    ];

    // =========================
    // CASTING
    // =========================
    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =========================
    // APPENDS (AUTO KE BLADE)
    // =========================
    protected $appends = [
        'url',
        'size_kb',
        'exists'
    ];

    // =========================
    // RELASI KE INSPEKSI
    // =========================
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'inspeksi_id');
    }

    // =========================
    // ACCESSOR: URL FOTO (ANTI ERROR 🔥)
    // =========================
    public function getUrlAttribute(): ?string
    {
        if (!$this->path) {
            return null;
        }

        $disk = $this->disk ?? 'public';

        try {
            if (Storage::disk($disk)->exists($this->path)) {
                return Storage::disk($disk)->url($this->path);
            }
        } catch (\Exception $e) {
            // kalau disk error, fallback
        }

        // fallback aman
        return asset('storage/' . ltrim($this->path, '/'));
    }

    // =========================
    // ACCESSOR: SIZE KB
    // =========================
    public function getSizeKbAttribute(): float
    {
        return $this->size
            ? round($this->size / 1024, 2)
            : 0;
    }

    // =========================
    // ACCESSOR: FILE EXISTS
    // =========================
    public function getExistsAttribute(): bool
    {
        if (!$this->path) {
            return false;
        }

        try {
            return Storage::disk($this->disk ?? 'public')->exists($this->path);
        } catch (\Exception $e) {
            return false;
        }
    }

    // =========================
    // BOOT (DEFAULT DISK)
    // =========================
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->disk) {
                $model->disk = 'public';
            }
        });
    }
}