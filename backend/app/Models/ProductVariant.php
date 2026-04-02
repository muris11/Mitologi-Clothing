<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'title', 'available_for_sale',
        'price', 'currency_code', 'stock', 'reserved_stock', 'sku',
        'options',
    ];

    protected $appends = ['available_stock'];

    public function getAvailableStockAttribute()
    {
        return max(0, $this->stock - $this->reserved_stock);
    }

    protected $casts = [
        'available_for_sale' => 'boolean',
        'price' => 'decimal:2',
        'options' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function selectedOptions()
    {
        return $this->hasMany(VariantOption::class, 'variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }
}
