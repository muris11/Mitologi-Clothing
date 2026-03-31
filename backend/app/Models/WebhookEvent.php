<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'provider',
        'order_id',
        'transaction_status',
        'payload',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Helper check if event is duplicate
     */
    public static function isDuplicate(string $eventId): bool
    {
        return static::where('event_id', $eventId)->exists();
    }
}
