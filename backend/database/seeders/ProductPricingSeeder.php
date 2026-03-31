<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Kaos Custom (T-Shirt)',
                'min_order' => '12 Pcs',
                'notes' => 'Tergantung kuantitas pesanan, kesulitan sablon, dan jenis bahan. Semakin banyak harga lebih bisa disesuaikan. Harga sudah termasuk sablon 1 warna 1 sisi.',
                'items' => [
                    ['name' => 'Cotton Combed 24s/30s (Basic)', 'price_range' => 'Rp 45.000 - Rp 55.000 / pcs'],
                    ['name' => 'Cotton Bamboo (Premium)', 'price_range' => 'Rp 65.000 - Rp 75.000+ / pcs'],
                    ['name' => 'Heavyweight Cotton (Streetwear)', 'price_range' => 'Rp 70.000 - Rp 90.000 / pcs'],
                    ['name' => 'Polyester PE (Ekonomis)', 'price_range' => 'Rp 35.000 - Rp 45.000 / pcs'],
                    ['name' => 'CVC (Cotton Viscose)', 'price_range' => 'Rp 50.000 - Rp 60.000 / pcs'],
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'category_name' => 'Hoodie & Sweater',
                'min_order' => '12 Pcs',
                'notes' => 'Termasuk sablon/bordir dasar. Harga dapat berubah tergantung kompleksitas desain dan jumlah warna.',
                'items' => [
                    ['name' => 'Cotton Fleece 240gsm', 'price_range' => 'Rp 110.000 - Rp 135.000 / pcs'],
                    ['name' => 'Cotton Fleece 280-330gsm (Premium)', 'price_range' => 'Rp 135.000 - Rp 150.000+ / pcs'],
                    ['name' => 'Crewneck Sweater', 'price_range' => 'Rp 100.000 - Rp 120.000 / pcs'],
                    ['name' => 'Zip Hoodie (Full Zipper)', 'price_range' => 'Rp 140.000 - Rp 165.000 / pcs'],
                    ['name' => 'Oversized Hoodie', 'price_range' => 'Rp 145.000 - Rp 170.000 / pcs'],
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'category_name' => 'Jersey Fullprinting',
                'min_order' => '12 Pcs',
                'notes' => 'Pilihan bahan: Dry-fit Benzema, MTis, Pique, Brazil. Harga sudah termasuk full print sublimation.',
                'items' => [
                    ['name' => 'Jersey Lengan Pendek', 'price_range' => 'Rp 50.000 - Rp 65.000 / pcs'],
                    ['name' => 'Jersey Lengan Panjang', 'price_range' => 'Rp 60.000 - Rp 70.000 / pcs'],
                    ['name' => 'Satu Set (Baju + Celana)', 'price_range' => 'Rp 85.000 - Rp 110.000 / set'],
                    ['name' => 'Jersey Kiper (Long Sleeve)', 'price_range' => 'Rp 65.000 - Rp 75.000 / pcs'],
                    ['name' => 'Jersey Training (Rompi)', 'price_range' => 'Rp 35.000 - Rp 45.000 / pcs'],
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'category_name' => 'Kemeja & Wearpack',
                'min_order' => '12 Pcs',
                'notes' => 'Pilihan bahan: Nagata Drill, American Drill, Japan Drill. Include bordir (punggung & 2 emblem).',
                'items' => [
                    ['name' => 'Kemeja PDH/PDL', 'price_range' => 'Rp 130.000 - Rp 165.000 / pcs'],
                    ['name' => 'Wearpack Safety (Scotlight)', 'price_range' => 'Rp 250.000 - Rp 270.000 / pcs'],
                    ['name' => 'Rompi / Semi-Jacket', 'price_range' => 'Rp 120.000 - Rp 150.000 / pcs'],
                    ['name' => 'Kemeja Kerja (American Drill)', 'price_range' => 'Rp 125.000 - Rp 145.000 / pcs'],
                    ['name' => 'Jaket Bomber', 'price_range' => 'Rp 180.000 - Rp 220.000 / pcs'],
                    ['name' => 'Jaket Parka', 'price_range' => 'Rp 200.000 - Rp 250.000 / pcs'],
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'category_name' => 'Polo Shirt (Kerah)',
                'min_order' => '12 Pcs',
                'notes' => 'Cocok untuk seragam kantor, komunitas, atau event formal. Include bordir logo dada.',
                'items' => [
                    ['name' => 'Lacoste PE (Basic)', 'price_range' => 'Rp 75.000 - Rp 85.000 / pcs'],
                    ['name' => 'Lacoste CVC (Premium)', 'price_range' => 'Rp 85.000 - Rp 95.000 / pcs'],
                    ['name' => 'Lacoste Cotton 100%', 'price_range' => 'Rp 90.000 - Rp 105.000 / pcs'],
                    ['name' => 'Pique Polo', 'price_range' => 'Rp 80.000 - Rp 95.000 / pcs'],
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'category_name' => 'Merchandise & Aksesoris',
                'min_order' => 'Varies',
                'notes' => 'Minimum order berbeda untuk setiap produk. Harga dapat berubah tergantung bahan dan kompleksitas.',
                'items' => [
                    ['name' => 'Topi Custom (Min. 20 pcs)', 'price_range' => 'Rp 20.000 - Rp 35.000 / pcs'],
                    ['name' => 'Tas Canvas (Min. 50 pcs)', 'price_range' => 'Rp 40.000 - Rp 60.000 / pcs'],
                    ['name' => 'Waistbag (Min. 50 pcs)', 'price_range' => 'Rp 55.000 - Rp 75.000 / pcs'],
                    ['name' => 'Lanyard (Min. 50 pcs)', 'price_range' => 'Rp 15.000 - Rp 25.000 / pcs'],
                    ['name' => 'Tote Bag (Min. 30 pcs)', 'price_range' => 'Rp 25.000 - Rp 40.000 / pcs'],
                ],
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\ProductPricing::create($category);
        }
    }
}
