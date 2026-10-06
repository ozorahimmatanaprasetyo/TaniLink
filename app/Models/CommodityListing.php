<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commodity_name',
        'category',
        'variety',
        'land_area_m2',
        'estimated_harvest_kg',
        'available_kg',
        'price_per_kg',
        'harvest_estimated_date',
        'location',
        'image_url',
        'description',
        'status',
    ];

    protected $casts = [
        'harvest_estimated_date' => 'date',
        'price_per_kg' => 'decimal:2',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function preOrders()
    {
        return $this->hasMany(PreOrder::class, 'commodity_listing_id');
    }
}

