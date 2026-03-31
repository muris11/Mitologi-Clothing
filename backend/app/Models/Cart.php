<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalQuantityAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getCostAttribute(): array
    {
        $items = $this->items->load('variant');
        $subtotal = $items->sum(fn($item) => $item->variant->price * $item->quantity);
        $currency = $items->first()?->variant?->currency_code ?? 'IDR';

        return [
            'subtotalAmount' => ['amount' => (string) $subtotal, 'currencyCode' => $currency],
            'totalAmount' => ['amount' => (string) $subtotal, 'currencyCode' => $currency],
            'totalTaxAmount' => ['amount' => '0.0', 'currencyCode' => $currency],
        ];
    }
}
