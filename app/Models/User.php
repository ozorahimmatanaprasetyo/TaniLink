<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role', // 'petani', 'pembeli', 'admin'
        'phone',
        'location',
        'organization',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPetani(): bool
    {
        return $this->role === 'petani';
    }

    public function isPembeli(): bool
    {
        return $this->role === 'pembeli';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function commodityListings()
    {
        return $this->hasMany(CommodityListing::class, 'user_id');
    }

    public function farmerOrders()
    {
        return $this->hasMany(PreOrder::class, 'farmer_id');
    }

    public function buyerOrders()
    {
        return $this->hasMany(PreOrder::class, 'buyer_id');
    }
}
