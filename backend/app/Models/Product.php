<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'handle', 'description', 'description_html',
        'available_for_sale', 'featured_image', 'seo_title',
        'seo_description', 'tags', 'is_hidden',
    ];

    protected $casts = [
        'available_for_sale' => 'boolean',
        'is_hidden' => 'boolean',
        'tags' => 'array',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function interactions()
    {
        return $this->hasMany(UserInteraction::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        // 1. Check direct column 'featured_image'
        if ($this->featured_image) {
            if (str_starts_with($this->featured_image, 'http')) {
                return $this->featured_image;
            }

            // Check if file sits in 'products/' directory if not explicitly correctly path'd
            if (! str_contains($this->featured_image, 'products/') && \Illuminate\Support\Facades\Storage::disk('public')->exists('products/'.$this->featured_image)) {
                return url('storage/products/'.$this->featured_image);
            }

            return url('storage/'.$this->featured_image);
        }

        // 2. Fallback to first image in 'images' relationship
        $firstImage = $this->images->first();
        if ($firstImage) {
            if (str_starts_with($firstImage->url, 'http')) {
                return $firstImage->url;
            }

            return url('storage/'.$firstImage->url);
        }

        return null;
    }

    public function getPriceRangeAttribute(): array
    {
        $variants = $this->variants;
        $prices = $variants->pluck('price');
        $currency = $variants->first()?->currency_code ?? 'IDR';

        return [
            'minVariantPrice' => [
                'amount' => (string) ($prices->min() ?? '0'),
                'currencyCode' => $currency,
            ],
            'maxVariantPrice' => [
                'amount' => (string) ($prices->max() ?? '0'),
                'currencyCode' => $currency,
            ],
        ];
    }

    public function getRouteKeyName()
    {
        return 'handle';
    }
}
