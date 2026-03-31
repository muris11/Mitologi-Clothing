<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        if ($customers->isEmpty()) return;

        $completedOrders = Order::where('status', 'completed')->get();

        $reviewsData = [
            [
                'customer_email' => 'customer@demo.com',
                'product_handle' => 'garuda-oversize-tee',
                'rating' => 5,
                'comment' => 'Kualitas bahannya juara! Cotton combed 30s-nya lembut banget, sablon DTF-nya juga tahan lama. Sudah dicuci berkali-kali masih bagus.',
            ],
            [
                'customer_email' => 'anisa@demo.com',
                'product_handle' => 'naga-batak-heritage-hoodie',
                'rating' => 5,
                'comment' => 'Hoodie-nya tebal dan hangat, cocok banget buat di daerah Kaliurang. Desain Naga Batak-nya keren banget, banyak yang nanya beli dimana.',
            ],
            [
                'customer_email' => 'budi@demo.com',
                'product_handle' => 'barong-bali-graphic-tee',
                'rating' => 4,
                'comment' => 'Desainnya unik dan kualitas oke. Cuma size-nya agak kecil, next order mau ambil satu size lebih besar.',
            ],
            [
                'customer_email' => 'denny@demo.com',
                'product_handle' => 'rangda-dark-bomber-jacket',
                'rating' => 5,
                'comment' => 'Bomber jacket terbaik yang pernah saya punya! Bordir Rangda-nya super detail, dan bahannya water-resistant beneran. Worth every penny.',
            ],
            [
                'customer_email' => 'siti@demo.com',
                'product_handle' => 'dewi-sri-embroidered-tee',
                'rating' => 5,
                'comment' => 'Bordirannya halus banget, detailnya luar biasa. Kaosnya juga nyaman dipakai seharian. Recommended!',
            ],
            [
                'customer_email' => 'customer@demo.com',
                'product_handle' => 'hanoman-warrior-zip-hoodie',
                'rating' => 4,
                'comment' => 'Zip hoodie-nya keren, print Hanoman-nya detail. Kangaroo pocket-nya juga fungsional. Cuma ritsleting agak seret di awal.',
            ],
            [
                'customer_email' => 'anisa@demo.com',
                'product_handle' => 'jatayu-legend-oversized-tee',
                'rating' => 5,
                'comment' => 'Suka banget sama desain Jatayu-nya, artistik dan mythical. Oversize fit-nya pas, nggak kebesaran. Bahan adem juga.',
            ],
            [
                'customer_email' => 'budi@demo.com',
                'product_handle' => 'kala-makara-snapback-cap',
                'rating' => 4,
                'comment' => 'Topinya bagus, bordirannya rapi. Bahan topinya tebal dan kokoh. Adjustable strap-nya juga nyaman.',
            ],
        ];

        foreach ($reviewsData as $i => $reviewData) {
            $product = Product::where('handle', $reviewData['product_handle'])->first();
            $customer = $customers->where('email', $reviewData['customer_email'])->first() ?? $customers->random();
            
            if (!$product || !$customer) continue;

            ProductReview::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $customer->id,
                ],
                [
                    'order_id' => $completedOrders->count() > 0 ? $completedOrders[$i % $completedOrders->count()]->id : null,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                    'is_visible' => true,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }
    }
}
