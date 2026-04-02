<?php

namespace App\Traits;

use App\Models\Product;

trait FormatsProduct
{
    /**
     * Standardized way to format a Product for API responses.
     */
    protected function formatProduct(Product $product): array
    {
        $images = $product->images;

        // Helper: resolve selectedOptions for a variant.
        // Priority: 1) DB relation  2) 'options' JSON column  3) parse title
        $resolveSelectedOptions = function ($variant) {
            // 1. Try the relational data (variant_options table)
            if ($variant->relationLoaded('selectedOptions') && $variant->selectedOptions->isNotEmpty()) {
                return $variant->selectedOptions->map(fn ($opt) => [
                    'name' => $opt->name,
                    'value' => $opt->value,
                ])->toArray();
            }

            // 2. Try the 'options' JSON column on the variant itself
            $optionsJson = $variant->options; // cast to array by model
            if (is_array($optionsJson) && ! empty($optionsJson)) {
                $result = [];
                foreach ($optionsJson as $key => $val) {
                    if (is_string($key) && is_string($val)) {
                        $result[] = ['name' => $key, 'value' => $val];
                    }
                }
                if (! empty($result)) {
                    return $result;
                }
            }

            // 3. Fallback: parse the variant title (e.g. "S / Black" → Ukuran: S, Warna: Black)
            $title = $variant->title ?? '';
            if ($title && $title !== 'Default Title') {
                $parts = array_map('trim', explode('/', $title));
                $optionNames = ['Ukuran', 'Warna', 'Material', 'Style'];
                $result = [];
                foreach ($parts as $i => $part) {
                    $result[] = [
                        'name' => $optionNames[$i] ?? 'Option '.($i + 1),
                        'value' => $part,
                    ];
                }

                return $result;
            }

            return [];
        };

        // 1. Extract Options dynamically from Variants
        $optionsMap = [];
        foreach ($product->variants as $variant) {
            $selectedOpts = $resolveSelectedOptions($variant);
            foreach ($selectedOpts as $option) {
                $name = $option['name'];
                $value = $option['value'];

                if (! isset($optionsMap[$name])) {
                    $optionsMap[$name] = [
                        'name' => $name,
                        'values' => [],
                    ];
                }

                if (! in_array($value, $optionsMap[$name]['values'])) {
                    $optionsMap[$name]['values'][] = $value;
                }
            }
        }

        // Format options for output
        $formattedOptions = [];
        foreach ($optionsMap as $name => $data) {
            $formattedOptions[] = [
                'id' => $name,
                'name' => $data['name'],
                'values' => $data['values'],
            ];
        }

        $priceRange = $product->price_range;

        // Calculate total stock across all variants (using available_stock)
        $totalStock = $product->variants->sum('available_stock');

        // Format variants with proper selectedOptions array
        $formattedVariants = $product->variants->map(function ($v) use ($resolveSelectedOptions) {
            return [
                'id' => (string) $v->id,
                'title' => $v->title,
                'availableForSale' => $v->available_for_sale,
                'selectedOptions' => $resolveSelectedOptions($v),
                'price' => [
                    'amount' => (string) $v->price,
                    'currencyCode' => $v->currency_code ?? 'IDR',
                ],
                'sku' => $v->sku,
                'stock' => $v->available_stock,
            ];
        })->toArray();

        // Build images array - include featured image + gallery images
        $formattedImages = [];
        $featuredUrl = $product->featured_image_url;

        // Add featured image as the first image if it exists
        if ($featuredUrl) {
            $formattedImages[] = [
                'url' => $featuredUrl,
                'altText' => $product->title,
                'width' => 800,
                'height' => 800,
            ];
        }

        // Add gallery images
        foreach ($images as $img) {
            // Avoid duplicating the featured image if it happens to be in the gallery
            if ($featuredUrl && $img->full_url === $featuredUrl) {
                continue;
            }

            $formattedImages[] = [
                'url' => $img->full_url,
                'altText' => $img->alt_text ?? $product->title,
                'width' => $img->width ?? 800,
                'height' => $img->height ?? 800,
            ];
        }

        return [
            'id' => (string) $product->id,
            'handle' => $product->handle,
            'availableForSale' => $product->available_for_sale,
            'title' => $product->title,
            'description' => $product->description ?? '',
            'descriptionHtml' => $product->description_html ?? '',
            'options' => $formattedOptions,
            'priceRange' => $priceRange,
            'variants' => $formattedVariants,
            'featuredImage' => $featuredUrl ? [
                'url' => $featuredUrl,
                'altText' => $product->title,
                'width' => 800,
                'height' => 800,
            ] : null,
            'images' => $formattedImages,
            'seo' => [
                'title' => $product->seo_title ?? $product->title,
                'description' => $product->seo_description ?? $product->description ?? '',
            ],
            'tags' => $product->tags ?? [],
            'totalStock' => $totalStock,
            'averageRating' => round($product->reviews_avg_rating ?? 0, 1),
            'totalReviews' => $product->reviews_count ?? 0,
            'updatedAt' => $product->updated_at?->toISOString(),
        ];
    }
}
