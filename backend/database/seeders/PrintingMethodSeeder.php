<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrintingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Sablon Plastisol',
                'slug' => 'sablon-plastisol',
                'description' => 'Tinta sablon oil-based standar internasional dengan warna pekat, daya tahan tinggi, dan efek raster detail. Cocok untuk produksi massal dengan kualitas distro.',
                'image' => 'printing-methods/plastisol.jpg',
                'pros' => ['Warna cerah dan tajam', 'Sangat awet (tidak mudah pecah/luntur)', 'Standar distro brand besar', 'Tahan hingga 50+ kali cuci', 'Bisa efek khusus (glow in dark, puff, foil)'],
                'price_range' => 'Mulai dari Rp 45.000/pcs',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sablon Discharge',
                'slug' => 'sablon-discharge',
                'description' => 'Tinta cabut warna yang menyerap dan menghilangkan warna asli kain, memberikan handfeel super lembut menyatu dengan kain. Ideal untuk kaos premium dengan kesan vintage.',
                'image' => 'printing-methods/discharge.jpg',
                'pros' => ['Handfeel sangat lembut', 'Tidak panas saat dipakai (breathable)', 'Warna natural dan pudar menyatu (vintage look)', 'Menyatu dengan serat kain', 'Cocok untuk bahan Cotton Combed'],
                'price_range' => 'Mulai dari Rp 55.000/pcs',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'DTF (Direct to Film)',
                'slug' => 'dtf-printing',
                'description' => 'Teknologi printing digital modern tanpa batasan warna. Cocok untuk desain full color seperti foto atau gradasi kompleks. Bisa diterapkan di berbagai jenis bahan.',
                'image' => 'printing-methods/dtf.jpg',
                'pros' => ['Bisa cetak desain full printing/foto', 'Warna solid dan presisi tinggi', 'Bisa order satuan tidak harus lusinan', 'Tanpa minimal order', 'Cocok untuk desain kompleks'],
                'price_range' => 'Mulai Rp 50.000 (Tergantung ukuran)',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sublimation Printing',
                'slug' => 'sublimation-printing',
                'description' => 'Teknik cetak khusus polyester/dry-fit di mana tinta menyublim ke dalam serat kain. Ideal untuk jersey basket/futsal, pakaian olahraga, dan produk dengan full print pattern.',
                'image' => 'printing-methods/sublimation.jpg',
                'pros' => ['Warna permanen masuk ke serat kain', 'Full print seluruh bidang (all-over print)', 'Anti luntur dan pudar', 'Cocok untuk jersey olahraga', 'Tidak terasa saat dipakai'],
                'price_range' => 'Mulai dari Rp 65.000 (Fullprint)',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($methods as $method) {
            \App\Models\PrintingMethod::create($method);
        }
    }
}
