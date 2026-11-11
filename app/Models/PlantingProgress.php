<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantingProgress extends Model
{
    use HasFactory;

    protected $table = 'planting_progress';

    protected $fillable = [
        'harvest_batch_id',
        'pre_order_id',
        'stage_name',
        'stage_status',
        'note',
        'photo_url',
        'location_tag',
        'reported_at',
        'is_verified',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function harvestBatch()
    {
        return $this->belongsTo(HarvestBatch::class, 'harvest_batch_id');
    }

    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class, 'pre_order_id');
    }

    public function getResolvedPhotoUrlAttribute(): string
    {
        $raw = trim((string) $this->photo_url);

        // Jika file lokal valid
        if ($raw !== '' && !str_starts_with($raw, 'http')) {
            $clean = ltrim($raw, '/');
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
            if (str_starts_with($clean, 'storage/') && file_exists(storage_path('app/public/' . substr($clean, 8)))) {
                return asset($clean);
            }
        }

        // Resolusi berdasarkan nama tahap budidaya tanam
        $stage = strtolower($this->stage_name . ' ' . ($this->note ?? ''));

        if (str_contains($stage, 'persemaian') || str_contains($stage, 'semai') || str_contains($stage, 'bibit')) {
            return asset('images/phases/fase-2-persemaian.jpg');
        } elseif (str_contains($stage, 'persiapan') || str_contains($stage, 'lahan') || str_contains($stage, 'bedengan') || str_contains($stage, 'bajak')) {
            return asset('images/phases/fase-1-persiapan-lahan.jpg');
        } elseif (str_contains($stage, 'penanaman') || str_contains($stage, 'tanam bibit')) {
            return asset('images/phases/fase-3-penanaman.jpg');
        } elseif (str_contains($stage, 'vegetatif') || str_contains($stage, 'pertumbuhan')) {
            return asset('images/phases/fase-4-vegetatif.jpg');
        } elseif (str_contains($stage, 'bunga') || str_contains($stage, 'buah') || str_contains($stage, 'menjelang panen')) {
            return asset('images/phases/fase-5-pembuahan.jpg');
        } elseif (str_contains($stage, 'panen') || str_contains($stage, 'siap panen') || str_contains($stage, 'petik')) {
            return asset('images/phases/fase-6-panen.jpg');
        }

        // Fallback jika ada URL eksternal
        if ($raw !== '' && str_starts_with($raw, 'http')) {
            return $raw;
        }

        return asset('images/phases/fase-4-vegetatif.jpg');
    }
}


