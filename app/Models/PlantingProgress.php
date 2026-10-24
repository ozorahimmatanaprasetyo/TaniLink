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
}

