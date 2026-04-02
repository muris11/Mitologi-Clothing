<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'url', 'alt_text', 'width', 'height', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFullUrlAttribute(): string
    {
        if (str_starts_with($this->url, 'http')) {
            return $this->url;
        }

        // Use url() instead of asset() to get the full URL without the /api prefix
        return url('storage/'.$this->url);
    }
}
