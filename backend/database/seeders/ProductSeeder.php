<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductVariant;
use App\Models\VariantOption;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'Garuda Oversize Tee',
                'handle' => 'garuda-oversize-tee',
                'description' => 'Kaos oversize premium dengan desain Garuda yang ikonik. Dibuat dari bahan cotton combed 30s yang lembut dan nyaman.',
                'description_html' => '<p>Kaos oversize premium dengan desain <strong>Garuda</strong> yang ikonik. Dibuat dari bahan cotton combed 30s yang lembut dan nyaman.</p><ul><li>Cotton Combed 30s</li><li>Oversize Fit</li><li>Sablon DTF Premium</li></ul>',
                'tags' => ['t-shirt', 'oversize', 'garuda', 'mythology'],
                'categories' => ['t-shirt', 'new-arrivals', 'best-sellers'],
                'options' => [
                    ['name' => 'Size', 'values' => ['S', 'M', 'L', 'XL', 'XXL']],
                    ['name' => 'Color', 'values' => ['Black', 'White']],
                ],
                'base_price' => 189000,
            ],
            [
                'title' => 'Naga Batak Heritage Hoodie',
                'handle' => 'naga-batak-heritage-hoodie',
                'description' => 'Hoodie tebal dengan desain Naga Batak yang megah. Bahan fleece premium anti-pilling.',
                'description_html' => '<p>Hoodie tebal dengan desain <strong>Naga Batak</strong> yang megah. Bahan fleece premium anti-pilling.</p>',
                'tags' => ['hoodie', 'naga', 'batak', 'heritage'],
                'categories' => ['hoodie', 'new-arrivals'],
                'options' => [
                    ['name' => 'Size', 'values' => ['M', 'L', 'XL', 'XXL']],
                    ['name' => 'Color', 'values' => ['Navy', 'Black', 'Maroon']],
                ],
                'base_price' => 349000,
            ],
            [
                'title' => 'Barong Bali Graphic Tee',
                'handle' => 'barong-bali-graphic-tee',
                'description' => 'Desain eksklusif terinspirasi dari Barong Bali, simbol kebaikan dalam mitologi Bali.',
                'description_html' => '<p>Desain eksklusif terinspirasi dari <strong>Barong Bali</strong>, simbol kebaikan dalam mitologi Bali.</p>',
                'tags' => ['t-shirt', 'barong', 'bali', 'mythology'],
                'categories' => ['t-shirt', 'best-sellers'],
                'options' => [
                    ['name' => 'Size', 'values' => ['S', 'M', 'L', 'XL']],
                    ['name' => 'Color', 'values' => ['Black', 'Dark Green']],
                ],
                'base_price' => 169000,
            ],
            [
                'title' => 'Rangda Dark Bomber Jacket',
                'handle' => 'rangda-dark-bomber-jacket',
                'description' => 'Bomber jacket dengan bordir Rangda yang detail. Material parasut premium, water-resistant.',
                'description_html' => '<p>Bomber jacket dengan bordir <strong>Rangda</strong> yang detail. Material parasut premium, water-resistant.</p>',
                'tags' => ['jacket', 'rangda', 'bomber', 'premium'],
                'categories' => ['jacket', 'new-arrivals'],
                'options' => [
                    ['name' => 'Size', 'values' => ['M', 'L', 'XL']],
                ],
                'base_price' => 489000,
            ],
            [
                'title' => 'Kala Makara Snapback Cap',
                'handle' => 'kala-makara-snapback-cap',
                'description' => 'Topi snapback dengan bordir Kala Makara. Adjustable strap, one size fits all.',
                'description_html' => '<p>Topi snapback dengan bordir <strong>Kala Makara</strong>. Adjustable strap, one size fits all.</p>',
                'tags' => ['cap', 'accessories', 'kala-makara'],
                'categories' => ['accessories'],
                'options' => [
                    ['name' => 'Color', 'values' => ['Black', 'Navy']],
                ],
                'base_price' => 129000,
            ],
            [
                'title' => 'Dewi Sri Embroidered Tee',
                'handle' => 'dewi-sri-embroidered-tee',
                'description' => 'Kaos dengan bordir halus Dewi Sri, dewi padi dan kesuburan dalam mitologi Jawa.',
                'description_html' => '<p>Kaos dengan bordir halus <strong>Dewi Sri</strong>, dewi padi dan kesuburan dalam mitologi Jawa.</p>',
                'tags' => ['t-shirt', 'embroidered', 'dewi-sri', 'mythology'],
                'categories' => ['t-shirt', 'best-sellers'],
                'options' => [
                    ['name' => 'Size', 'values' => ['S', 'M', 'L', 'XL']],
                    ['name' => 'Color', 'values' => ['White', 'Cream']],
                ],
                'base_price' => 219000,
            ],
            [
                'title' => 'Hanoman Warrior Zip Hoodie',
                'handle' => 'hanoman-warrior-zip-hoodie',
                'description' => 'Zip hoodie premium dengan print Hanoman sang ksatria kera. Full-zip dengan kangaroo pocket.',
                'description_html' => '<p>Zip hoodie premium dengan print <strong>Hanoman</strong> sang ksatria kera. Full-zip dengan kangaroo pocket.</p>',
                'tags' => ['hoodie', 'hanoman', 'zip', 'premium'],
                'categories' => ['hoodie'],
                'options' => [
                    ['name' => 'Size', 'values' => ['M', 'L', 'XL', 'XXL']],
                    ['name' => 'Color', 'values' => ['Black', 'Grey']],
                ],
                'base_price' => 389000,
            ],
            [
                'title' => 'Jatayu Legend Oversized Tee',
                'handle' => 'jatayu-legend-oversized-tee',
                'description' => 'Kaos oversize dengan ilustrasi epik Jatayu, burung mistis dalam epos Ramayana.',
                'description_html' => '<p>Kaos oversize dengan ilustrasi epik <strong>Jatayu</strong>, burung mistis dalam epos Ramayana.</p>',
                'tags' => ['t-shirt', 'oversize', 'jatayu', 'legend'],
                'categories' => ['t-shirt', 'new-arrivals'],
                'options' => [
                    ['name' => 'Size', 'values' => ['M', 'L', 'XL', 'XXL']],
                    ['name' => 'Color', 'values' => ['Black', 'Charcoal']],
                ],
                'base_price' => 199000,
            ],
        ];

        // Ensure clean products table if re-seeding
        // (Assuming DatabaseSeeder or empty_db handled Truncation beforehand, if not use Product::truncate() with disableForeignKeyConstraints)

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['handle' => $productData['handle']],
                [
                    'title' => $productData['title'],
                    'description' => $productData['description'],
                    'description_html' => $productData['description_html'],
                    'available_for_sale' => true,
                    'tags' => $productData['tags'],
                    'is_hidden' => false,
                ]
            );

            // Attach categories
            $categoryIds = Category::whereIn('handle', $productData['categories'])->pluck('id');
            $product->categories()->sync($categoryIds);

            // Create options if empty
            if ($product->options()->count() === 0) {
                foreach ($productData['options'] as $opt) {
                    ProductOption::create([
                        'product_id' => $product->id,
                        'name' => $opt['name'],
                        'values' => $opt['values'],
                    ]);
                }
            }

            // Create variants
            if ($product->variants()->count() === 0) {
                $this->createVariants($product, $productData['options'], $productData['base_price']);
            }

            // Create placeholder images
            if ($product->images()->count() === 0) {
                for ($i = 1; $i <= 3; $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'url' => 'https://placehold.co/800x800/1a1a2e/e0e0e0?text='.urlencode($product->title)."+{$i}",
                        'alt_text' => $product->title." - Image {$i}",
                        'width' => 800,
                        'height' => 800,
                        'sort_order' => $i,
                    ]);
                }
            }

            $product->update(['featured_image' => null]);
        }

        // Attach specific products to homepage layouts
        $carouselCategory = Category::where('handle', 'hidden-homepage-carousel')->first();
        if ($carouselCategory && $carouselCategory->products()->count() === 0) {
            $carouselCategory->products()->attach(Product::take(4)->pluck('id'));
        }

        $featuredCategory = Category::where('handle', 'hidden-homepage-featured-items')->first();
        if ($featuredCategory && $featuredCategory->products()->count() === 0) {
            $featuredCategory->products()->attach(Product::take(3)->pluck('id'));
        }
    }

    private function createVariants(Product $product, array $options, int $basePrice): void
    {
        // Generate base SKU, e.g. "MTG-JATAYU"
        $baseSku = 'MTG-'.strtoupper(explode('-', $product->handle)[0]);

        if (count($options) === 1) {
            foreach ($options[0]['values'] as $value) {
                $sku = $baseSku.'-'.strtoupper($value);

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'title' => $value,
                    'sku' => $sku,
                    'available_for_sale' => true,
                    'price' => $basePrice,
                    'currency_code' => 'IDR',
                    'stock' => rand(5, 50),
                ]);
                VariantOption::create([
                    'variant_id' => $variant->id,
                    'name' => $options[0]['name'],
                    'value' => $value,
                ]);
            }
        } elseif (count($options) === 2) {
            foreach ($options[0]['values'] as $val1) {
                foreach ($options[1]['values'] as $val2) {
                    // Extract color code (first 3 chars)
                    $colorCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $val2), 0, 3));
                    $sizeCode = strtoupper($val1);
                    $sku = "{$baseSku}-{$colorCode}-{$sizeCode}";

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'title' => "{$val1} / {$val2}",
                        'sku' => $sku,
                        'available_for_sale' => true,
                        'price' => $basePrice,
                        'currency_code' => 'IDR',
                        'stock' => rand(5, 50),
                    ]);
                    VariantOption::create([
                        'variant_id' => $variant->id,
                        'name' => $options[0]['name'],
                        'value' => $val1,
                    ]);
                    VariantOption::create([
                        'variant_id' => $variant->id,
                        'name' => $options[1]['name'],
                        'value' => $val2,
                    ]);
                }
            }
        }
    }
}
