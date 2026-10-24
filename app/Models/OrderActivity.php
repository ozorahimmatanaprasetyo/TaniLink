<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderActivity extends Model
{
    use HasFactory;

    protected $table = 'order_activities';

    protected $fillable = [
        'pre_order_id',
        'activity_type',
        'title',
        'description',
        'user_id',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class, 'pre_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper static untuk mencatat aktivitas secara aman dan konsisten
     */
    public static function log($preOrderId, $type, $title, $description = null, $userId = null, $occurredAt = null)
    {
        return self::create([
            'pre_order_id' => $preOrderId,
            'activity_type' => $type,
            'title' => $title,
            'description' => $description,
            'user_id' => $userId ?? auth()->id(),
            'occurred_at' => $occurredAt ?? now('Asia/Jakarta'),
        ]);
    }
}

