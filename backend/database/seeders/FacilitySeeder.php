<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Workshop Pusat',
                'description' => 'Berlokasi di Indramayu, pusat operasi dan kontrol kualitas seluruh pesanan produksi pakaian. Dilengkapi dengan area produksi yang luas dan tim profesional.',
                'image' => 'facilities/workshop-pusat.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sablon Manual Presisi',
                'description' => 'Meja sablon banting presisi tinggi untuk menangani pesanan ribuan pcs dengan akurasi dan kecepatan maksimal. Mampu memproduksi hingga 5000 pcs per hari.',
                'image' => 'facilities/sablon-manual.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mesin Sublimasi Digital',
                'description' => 'Printer sublimasi format besar untuk produksi jersey dan kain full pattern kualitas HD. Mendukung printing hingga lebar 1.6 meter dengan resolusi 1440 DPI.',
                'image' => 'facilities/mesin-sublimasi.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Quality Control & Finishing',
                'description' => 'Area khusus untuk pengecekan detail pakaian (QC ketat), steam uap, dan packaging aman. Setiap produk melalui 3 tahap QC sebelum dikirim ke customer.',
                'image' => 'facilities/qc-finishing.jpg',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Gudang Bahan Baku',
                'description' => 'Storage dengan kontrol kelembaban untuk menyimpan berbagai jenis kain dan bahan baku. Kapasitas penyimpanan hingga 10.000 meter kain.',
                'image' => 'facilities/gudang-bahan.jpg',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Ruang Desain & Pre-Production',
                'description' => 'Area khusus untuk proses desain, pembuatan film sablon, dan persiapan produksi. Dilengkapi dengan komputer desain dan mesin output film.',
                'image' => 'facilities/ruang-desain.jpg',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($facilities as $facility) {
            \App\Models\Facility::create($facility);
        }
    }
}
