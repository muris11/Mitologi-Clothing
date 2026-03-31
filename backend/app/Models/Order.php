<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'order_number', 'status', 'tracking_number', 'subtotal', 'tax',
        'shipping_cost', 'total', 'currency_code', 'payment_method',
        'midtrans_transaction_id', 'midtrans_order_id',
        'midtrans_payment_type', 'midtrans_status',
        'midtrans_response', 'notes', 'paid_at',
        'refund_reason', 'refund_requested_at', 'refund_admin_note',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
        'refund_requested_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'MC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getStatusLabelAttribute(): string
    {
        $translations = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
        ];

        return $translations[$this->status] ?? ucfirst($this->status);
    }

    protected static function booted()
    {
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;
                
                // Logic for stock updates when status transitions
                if ($oldStatus === 'pending' && $newStatus === 'processing') {
                    // Paid: Decrement actual stock and clear reservation
                    foreach ($order->items as $item) {
                        $variant = \App\Models\ProductVariant::lockForUpdate()->find($item->variant_id);
                        if ($variant) {
                            $variant->decrement('stock', $item->quantity);
                            $variant->decrement('reserved_stock', $item->quantity);
                        }
                    }
                } elseif ($oldStatus === 'pending' && $newStatus === 'cancelled') {
                    // Cancelled before paid: Clear reservation only
                    foreach ($order->items as $item) {
                        $variant = \App\Models\ProductVariant::lockForUpdate()->find($item->variant_id);
                        if ($variant) {
                            $variant->decrement('reserved_stock', $item->quantity);
                        }
                    }
                } elseif (in_array($oldStatus, ['processing', 'shipped', 'completed']) && in_array($newStatus, ['cancelled', 'refunded'])) {
                    // Cancelled/Refunded after paid: Restore actual stock
                    foreach ($order->items as $item) {
                        $variant = \App\Models\ProductVariant::lockForUpdate()->find($item->variant_id);
                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    }
                }
            }
        });

        static::updated(function ($order) {
            if ($order->wasChanged('status')) {
                try {
                    \Illuminate\Support\Facades\Mail::to($order->user->email)->queue(new \App\Mail\OrderStatusMail($order));
                    \Illuminate\Support\Facades\Log::info('Order status email sent to ' . $order->user->email . ' for status ' . $order->status);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send order status email: ' . $e->getMessage());
                }
            }
        });
    }
}
