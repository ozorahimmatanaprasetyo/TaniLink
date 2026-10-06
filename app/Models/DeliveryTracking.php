<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'pre_order_id',
        'pickup_address',
        'destination_address',
        'distance_km',
        'estimated_time',
        'fuel_saving_percentage',
        'status',
        'pickup_time',
        'driver_name',
        'driver_vehicle',
        'cargo_crates',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'distance_km' => 'decimal:2',
    ];

    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class, 'pre_order_id');
    }
}

