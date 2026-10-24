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
        'grade',
        'land_area_m2',
        'estimated_harvest_kg',
        'available_kg',
        'price_per_kg',
        'harvest_estimated_date',
        'location',
        'latitude',
        'longitude',
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

    public function harvestBatches()
    {
        return $this->hasMany(HarvestBatch::class, 'commodity_listing_id');
    }

    public function activeBatch()
    {
        return $this->hasOne(HarvestBatch::class, 'commodity_listing_id')->where('status', '!=', 'selesai')->latest();
    }

    public function getGradeLabelAttribute()
    {
        $map = [
            'Grade A' => 'Kualitas Pilihan',
            'Grade A (Ekspor & Supermarket)' => 'Kualitas Pilihan',
            'Grade B' => 'Kualitas Standar',
            'Grade B (Pasar Swalayan & Resto)' => 'Kualitas Standar',
            'Grade C' => 'Kualitas Campuran',
            'Grade C (Lokal Pasar)' => 'Kualitas Campuran',
            'Kualitas Pilihan' => 'Kualitas Pilihan',
            'Kualitas Standar' => 'Kualitas Standar',
            'Kualitas Campuran' => 'Kualitas Campuran',
            'Belum Ditentukan' => 'Belum Ditentukan',
        ];
        return $map[$this->grade] ?? ($this->grade ?: 'Belum Ditentukan');
    }
}

