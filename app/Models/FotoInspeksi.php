<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class FotoInspeksi extends Model
{
    use HasFactory;

    protected $table = 'foto_inspeksi';

    protected $fillable = [
        'inspeksi_id',
        'sub_uraian_id',
        'path',
        'nama_file',
        'mime_type',
        'size',
        'disk',
        'keterangan',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'url',
        'size_kb',
        'exists',
        'full_path',
        'base64' // 🔥 INI KUNCI PDF
    ];

    // =========================
    // RELASI
    // =========================
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'inspeksi_id');
    }

    public function subUraian()
    {
        return $this->belongsTo(SubUraian::class, 'sub_uraian_id');
    }

    // =========================
    // URL (UNTUK WEB)
    // =========================
    public function getUrlAttribute(): ?string
    {
        if (!$this->path) return null;

        $disk = $this->disk ?? 'public';

        try {
            if (Storage::disk($disk)->exists($this->path)) {
                return Storage::disk($disk)->url($this->path);
            }
        } catch (\Exception $e) {}

        return asset('storage/' . ltrim($this->path, '/'));
    }

    // =========================
    // FULL PATH (UNTUK PDF)
    // =========================
    public function getFullPathAttribute(): ?string
    {
        if (!$this->path) return null;

        return public_path('storage/' . ltrim($this->path, '/'));
    }

    // =========================
    // BASE64 (🔥 UNTUK PDF)
    // =========================
    public function getBase64Attribute(): ?string
    {
        $path = $this->full_path;

        if (!$path || !file_exists($path)) {
            return null;
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    // =========================
    // SIZE KB
    // =========================
    public function getSizeKbAttribute(): float
    {
        return $this->size ? round($this->size / 1024, 2) : 0;
    }

    // =========================
    // FILE EXISTS
    // =========================
    public function getExistsAttribute(): bool
    {
        if (!$this->path) return false;

        try {
            return Storage::disk($this->disk ?? 'public')->exists($this->path);
        } catch (\Exception $e) {
            return false;
        }
    }

    // =========================
    // AUTO DISK
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