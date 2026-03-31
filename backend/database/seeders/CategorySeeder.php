<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // General Categories
        $categories = [
            ['name' => 'T-Shirt', 'handle' => 't-shirt', 'description' => 'Koleksi kaos dengan desain mitologi Indonesia'],
            ['name' => 'Hoodie', 'handle' => 'hoodie', 'description' => 'Hoodie premium dengan motif kustom'],
            ['name' => 'Jacket', 'handle' => 'jacket', 'description' => 'Jaket berkualitas tinggi'],
            ['name' => 'Accessories', 'handle' => 'accessories', 'description' => 'Aksesoris pelengkap gaya Anda'],
            ['name' => 'New Arrivals', 'handle' => 'new-arrivals', 'description' => 'Koleksi terbaru'],
            ['name' => 'Best Sellers', 'handle' => 'best-sellers', 'description' => 'Produk terlaris'],
        ];

        foreach ($categories as $i => $cat) {
            Category::updateOrCreate(
                ['handle' => $cat['handle']],
                array_merge($cat, [
                    'slug' => $cat['handle'],
                    'is_active' => true,
                    'sort_order' => $i,
                ])
            );
        }

        // Hidden categories for Homepage layouts
        Category::updateOrCreate(
            ['handle' => 'hidden-homepage-carousel'],
            [
                'name' => 'Homepage Carousel',
                'slug' => 'hidden-homepage-carousel',
                'description' => 'Products shown in homepage carousel',
                'is_active' => true,
                'sort_order' => 99,
            ]
        );

        Category::updateOrCreate(
            ['handle' => 'hidden-homepage-featured-items'],
            [
                'name' => 'Homepage Featured',
                'slug' => 'hidden-homepage-featured-items',
                'description' => 'Featured products on homepage',
                'is_active' => true,
                'sort_order' => 98,
            ]
        );
    }
}
