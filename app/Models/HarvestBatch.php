<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarvestBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'commodity_listing_id',
        'farmer_id',
        'batch_code',
        'batch_name',
        'estimated_harvest_date',
        'estimated_harvest_kg',
        'allocated_kg',
        'current_phase',
        'current_phase_status',
        'status',
        'phase1_completed_at',
        'phase2_completed_at',
        'phase3_completed_at',
        'phase4_completed_at',
    ];

    protected $casts = [
        'estimated_harvest_date' => 'date',
        'phase1_completed_at' => 'datetime',
        'phase2_completed_at' => 'datetime',
        'phase3_completed_at' => 'datetime',
        'phase4_completed_at' => 'datetime',
    ];

    public static $phases = [
        1 => [
            'name' => 'Persiapan & Penanaman',
            'short' => 'Persiapan Lahan',
            'desc' => 'Pengolahan lahan, pemupukan dasar, dan penanaman bibit unggul.'
        ],
        2 => [
            'name' => 'Pertumbuhan Tanaman',
            'short' => 'Pertumbuhan Vegetatif',
            'desc' => 'Perawatan rutin, pengairan, dan pemupukan fase vegetatif.'
        ],
        3 => [
            'name' => 'Menjelang Panen',
            'short' => 'Pembungaan & Buah',
            'desc' => 'Pembentukan buah/umbi dan perlindungan tanaman menjelang panen.'
        ],
        4 => [
            'name' => 'Siap Panen',
            'short' => 'Panen',
            'desc' => 'Kematangan optimal tercapai, siap dipetik dan dikemas.'
        ],
    ];

    public function commodityListing()
    {
        return $this->belongsTo(CommodityListing::class, 'commodity_listing_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function preOrders()
    {
        return $this->hasMany(PreOrder::class, 'harvest_batch_id');
    }

    public function plantingProgress()
    {
        return $this->hasMany(PlantingProgress::class, 'harvest_batch_id')->orderBy('reported_at', 'asc');
    }

    public function getAvailableKgAttribute()
    {
        return max(0, $this->estimated_harvest_kg - $this->ordered_kg);
    }

    public function getOrderedKgAttribute()
    {
        // Hitung akumulasi pesanan riil dari preOrders (tidak termasuk yang dibatalkan/ditolak)
        if ($this->relationLoaded('preOrders')) {
            $actualSum = (float) $this->preOrders->whereNotIn('status', ['dibatalkan', 'ditolak'])->sum('quantity_kg');
        } else {
            $actualSum = (float) $this->preOrders()->whereNotIn('status', ['dibatalkan', 'ditolak'])->sum('quantity_kg');
        }
        return max($actualSum, (float) ($this->allocated_kg ?? 0));
    }

    public function getCurrentPhaseIndexAttribute()
    {
        foreach (self::$phases as $idx => $p) {
            if ($this->current_phase === $p['name']) {
                return $idx;
            }
        }
        return 1;
    }

    public function getPhaseTimelineAttribute()
    {
        $currentPhaseIndex = $this->current_phase_index;
        $currentStatus = $this->current_phase_status ?? 'berlangsung';

        $timeline = [];
        foreach (self::$phases as $idx => $p) {
            if ($idx < $currentPhaseIndex) {
                $status = 'selesai';
            } elseif ($idx === $currentPhaseIndex) {
                $status = $currentStatus;
            } else {
                $status = ($currentPhaseIndex === 4 && $currentStatus === 'selesai') ? 'selesai' : 'menunggu';
            }

            $completedAtCol = "phase{$idx}_completed_at";

            $timeline[] = [
                'index' => $idx,
                'name' => $p['name'],
                'short' => $p['short'],
                'desc' => $p['desc'],
                'status' => $status,
                'completed_at' => $this->{$completedAtCol},
            ];
        }

        return $timeline;
    }
}

