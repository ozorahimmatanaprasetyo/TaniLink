<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'commodity_listing_id',
        'farmer_id',
        'buyer_id',
        'quantity_kg',
        'price_per_kg',
        'total_amount',
        'dp_amount',
        'escrow_locked_amount',
        'status',
        'current_stage',
        'current_stage_status',
        'stage_percentage',
    ];

    public function getPhaseTimelineAttribute()
    {
        $phases = [
            1 => [
                'name' => 'Fase Benih & Penyemaian',
                'short' => 'Penyemaian & Benih',
                'icon' => '🌱',
                'desc' => 'Bibit disemai dengan media organik dan persiapan lahan tanam.'
            ],
            2 => [
                'name' => 'Fase Vegetatif & Pertunasan',
                'short' => 'Pertumbuhan Vegetatif',
                'icon' => '🌿',
                'desc' => 'Pertumbuhan daun dan cabang aktif, irigasi dan nutrisi terjaga.'
            ],
            3 => [
                'name' => 'Fase Pembungaan & Bakal Buah',
                'short' => 'Pembungaan & Buah',
                'icon' => '🌼',
                'desc' => 'Bunga mekar serempak dan bakal buah mulai terbentuk merata.'
            ],
            4 => [
                'name' => 'Fase Pematangan & Siap Panen',
                'short' => 'Panen & Sortir',
                'icon' => '🌶️',
                'desc' => 'Hasil panen matang sempurna, siap petik dan grading kualitas.'
            ],
        ];

        $currentPhaseIndex = 1;
        foreach ($phases as $idx => $p) {
            if ($this->current_stage === $p['name']) {
                $currentPhaseIndex = $idx;
                break;
            }
        }

        $currentStatus = $this->current_stage_status ?? 'berlangsung';

        $timeline = [];
        foreach ($phases as $idx => $p) {
            if ($idx < $currentPhaseIndex) {
                $status = 'selesai';
            } elseif ($idx === $currentPhaseIndex) {
                $status = $currentStatus;
            } else {
                $status = ($currentPhaseIndex === 4 && $currentStatus === 'selesai') ? 'selesai' : 'menunggu';
            }

            $timeline[] = [
                'index' => $idx,
                'name' => $p['name'],
                'short' => $p['short'],
                'icon' => $p['icon'],
                'desc' => $p['desc'],
                'status' => $status,
            ];
        }

        return $timeline;
    }

    public function commodityListing()
    {
        return $this->belongsTo(CommodityListing::class, 'commodity_listing_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function plantingProgress()
    {
        return $this->hasMany(PlantingProgress::class, 'pre_order_id')->orderBy('reported_at', 'desc');
    }

    public function deliveryTracking()
    {
        return $this->hasOne(DeliveryTracking::class, 'pre_order_id');
    }

    public function getDistanceKmAttribute()
    {
        $farmerLat = $this->commodityListing->latitude ?? ($this->farmer->latitude ?? null);
        $farmerLng = $this->commodityListing->longitude ?? ($this->farmer->longitude ?? null);
        $buyerLat = $this->buyer->latitude ?? null;
        $buyerLng = $this->buyer->longitude ?? null;

        if ($farmerLat && $farmerLng && $buyerLat && $buyerLng) {
            $earthRadius = 6371; // radius bumi dalam km
            $latFrom = deg2rad((float)$farmerLat);
            $lonFrom = deg2rad((float)$farmerLng);
            $latTo = deg2rad((float)$buyerLat);
            $lonTo = deg2rad((float)$buyerLng);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return round($angle * $earthRadius, 1);
        }

        return null;
    }
}

