<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\HeroSlide;
use App\Models\Material;
use App\Models\OrderStep;
use App\Models\PortfolioItem;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // HERO SLIDES
        // =============================================
        $heroSlides = [
            [
                'title' => 'Mitologi Clothing',
                'subtitle' => 'Vendor Konveksi Terpercaya di Yogyakarta. Spesialis Kaos, Kemeja, Jaket, dan Merchandise Komunitas.',
                'image_url' => 'hero/slide1.jpg',
                'cta_text' => 'Konsultasi Gratis',
                'cta_link' => 'https://wa.me/628123456789',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Custom Apparel Berkualitas',
                'subtitle' => 'Buat desain impianmu jadi nyata. Mulai dari 12 pcs, bebas custom desain dan bahan.',
                'image_url' => 'hero/slide2.jpg',
                'cta_text' => 'Lihat Katalog',
                'cta_link' => '/search',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Promo Akhir Bulan',
                'subtitle' => 'Diskon hingga 20% untuk pemesanan paket komunitas & organisasi. Berlaku sampai akhir bulan ini.',
                'image_url' => 'hero/slide3.jpg',
                'cta_text' => 'Pesan Sekarang',
                'cta_link' => 'https://wa.me/628123456789?text=Halo,%20saya%20mau%20tanya%20promo',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($heroSlides as $slide) {
            HeroSlide::create($slide);
        }

        // =============================================
        // FEATURES (Mengapa Memilih Kami - PDF Page 6)
        // =============================================
        $features = [
            [
                'title' => 'Garansi Tepat Waktu',
                'description' => 'Tim kami berkomitmen menyelesaikan pesanan sesuai deadline yang telah disepakati.',
                'icon' => 'heroicon-o-clock',
                'is_active' => true,
            ],
            [
                'title' => 'Konsultasi Desain',
                'description' => 'Tim desainer kami siap membantu dari tahap konsep sampai hasil akhir tanpa biaya tambahan.',
                'icon' => 'heroicon-o-pencil-square',
                'is_active' => true,
            ],
            [
                'title' => 'Garansi Bebas Pengembalian',
                'description' => 'Jika produk tidak sesuai spesifikasi, klaim garansi pengembalian bebas berlaku 7 hari.',
                'icon' => 'heroicon-o-shield-check',
                'is_active' => true,
            ],
            [
                'title' => 'Mesin Produksi Sangat Cepat',
                'description' => 'Didukung oleh mesin produksi terkini berstandar operasional tinggi untuk kerja cerdas dan cepat.',
                'icon' => 'heroicon-o-bolt',
                'is_active' => true,
            ],
            [
                'title' => 'Pengerjaan Tim Profesional',
                'description' => 'Dikerjakan oleh tim ahli dan berpengalaman untuk memastikan kualitas jahitan dan sablon terbaik.',
                'icon' => 'heroicon-o-users',
                'is_active' => true,
            ],
            [
                'title' => 'Bonus Lebih',
                'description' => 'Order di atas ketentuan akan mendapatkan bonus gratis produk tambahan atau prioritas produksi.',
                'icon' => 'heroicon-o-gift',
                'is_active' => true,
            ],
        ];

        foreach ($features as $index => $feature) {
            Feature::create(array_merge($feature, ['sort_order' => $index + 1]));
        }

        // =============================================
        // MATERIALS (PDF Page 7)
        // =============================================
        $materials = [
            [
                'name' => 'Cotton Combed (24s & 30s)',
                'description' => 'Bahan adem, menyerap keringat. Standar distro untuk kenyamanan harian.',
                'color_theme' => 'bg-green-100 text-green-800',
            ],
            [
                'name' => 'Heavy Cotton (16s & 20s)',
                'description' => 'Tebal, solid, dan tahan lama. Cocok untuk pakaian oversized atau streetwear.',
                'color_theme' => 'bg-slate-100 text-slate-800',
            ],
            [
                'name' => 'Cotton Fleece (280gsm & 330gsm)',
                'description' => 'Lembut di dalam, hangat dipakai. Bahan ideal untuk hoodie, crewneck, atau jaket ringan.',
                'color_theme' => 'bg-indigo-100 text-indigo-800',
            ],
            [
                'name' => 'Baby Terry (Gramasi Menengah)',
                'description' => 'Tekstur unik yang nyaman, tidak setebal fleece namun tetap memberi kehangatan.',
                'color_theme' => 'bg-blue-100 text-blue-800',
            ],
            [
                'name' => 'Drill (Nagata, American, dll)',
                'description' => 'Kuat, rapi, dan awet. Sering digunakan untuk kemeja PDH/PDL dan seragam kerja.',
                'color_theme' => 'bg-amber-100 text-amber-800',
            ],
            [
                'name' => 'Lacoste (CVC & PE)',
                'description' => 'Bahan ikonik bertekstur pori untuk polo shirt, memberikan tampilan kasual dan profesional.',
                'color_theme' => 'bg-teal-100 text-teal-800',
            ],
        ];

        foreach ($materials as $index => $material) {
            Material::create(array_merge($material, ['sort_order' => $index + 1]));
        }

        // =============================================
        // TESTIMONIALS
        // =============================================
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'role' => 'Ketua Panitia Event Kampus',
                'content' => 'Hasil sablonnya awet banget, bahannya juga adem. Rekomen buat yang mau bikin kaos panitia! Pengerjaan cepat dan sesuai deadline acara kami.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'role' => 'Owner Brand Lokal "Nusantara Wear"',
                'content' => 'Jahitannya rapi, deadline tepat waktu. Puas banget kerjasama sama Mitologi Clothing. Sudah 3 kali repeat order dan hasilnya selalu konsisten.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Rizky Pratama',
                'role' => 'Mahasiswa UGM — Angkatan 2023',
                'content' => 'Pesen PDH angkatan disini, hasilnya memuaskan. Adminnya ramah dan fast respon. Harganya juga paling murah dibanding vendor lain yang kami survey.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Anisa Rahmawati',
                'role' => 'Koordinator Komunitas Hiking Jogja',
                'content' => 'Jersey komunitas kami bikin di Mitologi Clothing. Bahannya dryfit berkualitas, sablon DTF tahan lama meski sering dicuci setelah kegiatan outdoor.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Denny Kurniawan',
                'role' => 'Manager HRD PT. Karya Mandiri',
                'content' => 'Seragam kantor karyawan kami pesan di sini. Hasilnya profesional, bahan American Drill tebal dan nyaman untuk kerja harian. Akan order lagi tahun depan.',
                'rating' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // =============================================
        // ORDER STEPS
        // =============================================
        $steps = [
            // === Order Langsung ===
            [
                'step_number' => 1,
                'title' => 'Konsultasi Desain & Penawaran',
                'description' => 'Diskusikan desain, bahan, dan budget Anda dengan tim kami melalui WhatsApp atau datang langsung ke workshop.',
                'type' => 'langsung',
            ],
            [
                'step_number' => 2,
                'title' => 'DP & Produksi Dimulai',
                'description' => 'Pembayaran DP 50% untuk memulai proses produksi. Estimasi pengerjaan 7-14 hari kerja tergantung jumlah pesanan.',
                'type' => 'langsung',
            ],
            [
                'step_number' => 3,
                'title' => 'QC & Pelunasan',
                'description' => 'Quality Control setiap produk sebelum dikirim. Kami kirimkan foto hasil produksi untuk approval sebelum pelunasan.',
                'type' => 'langsung',
            ],
            [
                'step_number' => 4,
                'title' => 'Pengiriman / Ambil di Workshop',
                'description' => 'Pesanan dikirim ke alamat Anda via JNE/J&T/SiCepat atau bisa diambil langsung di workshop kami di Yogyakarta.',
                'type' => 'langsung',
            ],
            // === Via E-Commerce ===
            [
                'step_number' => 1,
                'title' => 'Pilih Produk & Ukuran',
                'description' => 'Jelajahi katalog kami, pilih produk favorit, tentukan ukuran dan warna, lalu tambahkan ke keranjang.',
                'type' => 'ecommerce',
            ],
            [
                'step_number' => 2,
                'title' => 'Checkout & Pembayaran',
                'description' => 'Isi alamat pengiriman, pilih metode pembayaran (Transfer Bank, GoPay, QRIS), dan selesaikan pembayaran.',
                'type' => 'ecommerce',
            ],
            [
                'step_number' => 3,
                'title' => 'Konfirmasi & Packing',
                'description' => 'Pesanan diverifikasi otomatis, produk dipacking dengan aman menggunakan bubble wrap dan kardus premium.',
                'type' => 'ecommerce',
            ],
            [
                'step_number' => 4,
                'title' => 'Pengiriman & Tracking',
                'description' => 'Pesanan dikirim via JNE/J&T/SiCepat. Anda akan mendapat nomor resi untuk tracking pesanan secara real-time.',
                'type' => 'ecommerce',
            ],
        ];

        foreach ($steps as $index => $step) {
            OrderStep::create(array_merge($step, ['sort_order' => $index + 1]));
        }

        // =============================================
        // PORTFOLIO ITEMS
        // =============================================
        $portfolioItems = [
            [
                'title' => 'Kaos Panitia Festival Budaya UGM 2025',
                'slug' => 'kaos-panitia-festival-budaya-ugm-2025',
                'category' => 'Kaos Event',
                'image_url' => 'portfolio/sample1.jpg',
                'description' => 'Produksi 200 pcs kaos Cotton Combed 30s dengan sablon DTF full color untuk panitia Festival Budaya UGM. Warna dasar hitam dengan desain motif batik modern.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Hoodie Komunitas Pendaki Merapi',
                'slug' => 'hoodie-komunitas-pendaki-merapi',
                'category' => 'Hoodie Komunitas',
                'image_url' => 'portfolio/sample2.jpg',
                'description' => 'Produksi 50 pcs hoodie Cotton Fleece 330gsm untuk Komunitas Pendaki Merapi. Desain bordir logo di dada kiri dan sablon punggung.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Jersey Tim Futsal "Garuda Muda FC"',
                'slug' => 'jersey-tim-futsal-garuda-muda-fc',
                'category' => 'Jersey Olahraga',
                'image_url' => 'portfolio/sample3.jpg',
                'description' => 'Produksi 30 pcs jersey Dryfit Benzema sublimation print untuk Tim Futsal Garuda Muda FC. Full print desain custom dengan nama dan nomor punggung.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Kemeja PDH Angkatan 2024 FT UNY',
                'slug' => 'kemeja-pdh-angkatan-2024-ft-uny',
                'category' => 'Kemeja Organisasi',
                'image_url' => 'portfolio/sample4.jpg',
                'description' => 'Produksi 150 pcs kemeja PDH American Drill untuk angkatan 2024 Fakultas Teknik UNY. Bordir logo kampus dan nama di dada, warna krem.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Jaket Bomber Crew Event Musik Jogja',
                'slug' => 'jaket-bomber-crew-event-musik-jogja',
                'category' => 'Jaket Custom',
                'image_url' => 'portfolio/sample5.jpg',
                'description' => 'Produksi 25 pcs jaket bomber parasut untuk crew event musik di Jogja. Bordir logo di dada dan punggung, warna hitam-gold.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Polo Shirt Seragam PT. Nusantara Jaya',
                'slug' => 'polo-shirt-seragam-pt-nusantara-jaya',
                'category' => 'Seragam Perusahaan',
                'image_url' => 'portfolio/sample6.jpg',
                'description' => 'Produksi 100 pcs polo shirt Lacoste CVC untuk seragam karyawan PT. Nusantara Jaya. Bordir logo perusahaan di dada kiri, warna navy dan putih.',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($portfolioItems as $item) {
            PortfolioItem::create($item);
        }
    }
}
