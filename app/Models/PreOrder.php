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
        'stage_percentage',
    ];

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
}

