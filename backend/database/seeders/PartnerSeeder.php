<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'PT Brata Karya Indonesia',
                'logo' => 'partners/pt-brata-karya.png',
                'website_url' => 'https://www.bratakarya.co.id',
                'description' => 'Corporate uniform & merchandise partner. Mitra strategis untuk produksi seragam perusahaan dan merchandise korporat.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Honda Vario LED Indonesia',
                'logo' => 'partners/honda-vario-led.png',
                'website_url' => 'https://www.hondavarioled.id',
                'description' => 'Automotive community merchandise. Komunitas pecinta Honda Vario terbesar di Indonesia dengan anggota lebih dari 50.000 member.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'HVC Jakarta',
                'logo' => 'partners/hvc-jakarta.png',
                'website_url' => 'https://www.hvcjakarta.com',
                'description' => 'Honda Vario Club Jakarta official merch supplier. Supplier resmi merchandise untuk komunitas Honda Vario Club Jakarta.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Bali Screen Printing Community',
                'logo' => 'partners/bali-screen-printing.png',
                'website_url' => 'https://www.baliscreenprinting.com',
                'description' => 'Event partnership & production support. Komunitas screen printing Bali yang mendukung produksi dan event-event kreatif.',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($partners as $partner) {
            \App\Models\Partner::create($partner);
        }
    }
}
