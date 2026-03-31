<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // MENUS
        // =============================================
        $mainMenuItems = [
            ['title' => 'All', 'path' => '/search', 'sort_order' => 0],
            ['title' => 'T-Shirt', 'path' => '/search/t-shirt', 'sort_order' => 1],
            ['title' => 'Hoodie', 'path' => '/search/hoodie', 'sort_order' => 2],
            ['title' => 'Jacket', 'path' => '/search/jacket', 'sort_order' => 3],
            ['title' => 'Accessories', 'path' => '/search/accessories', 'sort_order' => 4],
        ];

        $footerMenuItems = [
            ['title' => 'Home', 'path' => '/', 'sort_order' => 0],
            ['title' => 'About', 'path' => '/about', 'sort_order' => 1],
            ['title' => 'Terms & Conditions', 'path' => '/terms-conditions', 'sort_order' => 2],
            ['title' => 'Privacy Policy', 'path' => '/privacy-policy', 'sort_order' => 3],
            ['title' => 'FAQ', 'path' => '/faq', 'sort_order' => 4],
        ];

        // Ensure clear items for these handles before seeding if re-running
        Menu::where('handle', 'next-js-frontend-header-menu')->delete();
        foreach ($mainMenuItems as $item) {
            Menu::create(array_merge($item, ['handle' => 'next-js-frontend-header-menu', 'is_active' => true]));
        }

        Menu::where('handle', 'next-js-frontend-footer-menu')->delete();
        foreach ($footerMenuItems as $item) {
            Menu::create(array_merge($item, ['handle' => 'next-js-frontend-footer-menu', 'is_active' => true]));
        }

        // =============================================
        // PAGES (Complete content from PDF)
        // =============================================
        $pages = [
            [
                'title' => 'About',
                'handle' => 'about',
                'body' => '<h1>Tentang Mitologi Clothing</h1><p>Mitologi Clothing adalah vendor clothing asal Indramayu yang berdiri sejak 2022 dan bergerak dalam produksi berbagai jenis seragam dan merchandise untuk organisasi maupun instansi.</p><h2>Sejarah Kami</h2><p>Pada tahun 2015 terinspirasi oleh seorang teman yang sebagai afiliator konveksi yang sukses, terbentuklah RROArt. Lalu tanggal 20 November 2022 memulai bisnis Mitologi Clothing dari maklon sampai sekarang buka usaha sendiri.</p><h2>Visi</h2><p>Menjadi Brand yang dikenal dengan nuansa budaya dengan kualitas & Mutu terbaik</p><h2>Misi</h2><ol><li>Meningkatkan mutu dan standar pekerjaan</li><li>Menerapkan quality control yang tinggi</li><li>Memberikan pelayanan yang cepat dan mudah</li><li>Melakukan perniagaan sesuai aturan bernegara</li></ol><h2>Nilai-Nilai</h2><ul><li>Kejujuran</li><li>Produk yang berkualitas</li><li>Ketepatan waktu</li><li>Keberkahan usaha</li><li>Mengangkat budaya dan pariwisata di Indonesia</li></ul>',
                'body_summary' => 'Vendor clothing terpercaya asal Indramayu sejak 2022, mengangkat budaya Nusantara melalui kualitas terbaik.',
            ],
            [
                'title' => 'Terms & Conditions',
                'handle' => 'terms-conditions',
                'body' => '<h1>Syarat & Ketentuan</h1><p>Dengan menggunakan layanan Mitologi Clothing, Anda menyetujui syarat dan ketentuan berikut:</p><h2>1. Pemesanan</h2><ul><li>Minimum order 12 pcs untuk custom production (kecuali produk satuan)</li><li>DP 50% diperlukan untuk memulai produksi</li><li>Pelunasan dilakukan setelah QC dan sebelum pengiriman</li></ul><h2>2. Produksi</h2><ul><li>Estimasi pengerjaan 7-14 hari kerja tergantung jumlah pesanan</li><li>Revisi desain hanya dilayani maksimal 3x</li><li>Produk yang sudah diproduksi tidak dapat dibatalkan</li></ul><h2>3. Garansi</h2><ul><li>Garansi tepat waktu: voucher 1 kaos gratis jika terlambat dari deadline</li><li>Garansi kualitas: klaim pengembalian berlaku 7 hari setelah terima barang</li><li>Garansi tidak berlaku untuk kerusakan akibat kesalahan pengguna</li></ul><h2>4. Pengiriman</h2><ul><li>Pengiriman via JNE/J&T/SiCepat atau ekspedisi lainnya</li><li>Ongkos kirim ditanggung pembeli</li><li>Resiko pengiriman menjadi tanggung jawab bersama hingga paket diterima</li></ul>',
                'body_summary' => 'Syarat dan ketentuan penggunaan layanan Mitologi Clothing.',
            ],
            [
                'title' => 'Privacy Policy',
                'handle' => 'privacy-policy',
                'body' => '<h1>Kebijakan Privasi</h1><p>Mitologi Clothing berkomitmen menjaga privasi dan keamanan data Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p><h2>Informasi yang Kami Kumpulkan</h2><ul><li>Nama lengkap</li><li>Alamat email</li><li>Nomor telepon</li><li>Alamat pengiriman</li><li>Data pesanan dan riwayat transaksi</li></ul><h2>Penggunaan Informasi</h2><p>Informasi yang kami kumpulkan digunakan untuk:</p><ul><li>Memproses pesanan Anda</li><li>Menghubungi Anda terkait pesanan</li><li>Mengirimkan notifikasi promosi (dengan persetujuan Anda)</li><li>Meningkatkan kualitas layanan</li></ul><h2>Keamanan Data</h2><p>Kami menggunakan langkah-langkah keamanan yang sesuai untuk melindungi informasi pribadi Anda dari akses yang tidak sah, perubahan, pengungkapan, atau penghancuran.</p><h2>Pembagian Informasi</h2><p>Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p>',
                'body_summary' => 'Kebijakan privasi dan perlindungan data pelanggan Mitologi Clothing.',
            ],
            [
                'title' => 'FAQ',
                'handle' => 'faq',
                'body' => '<h1>Pertanyaan yang Sering Diajukan</h1><h2>Pemesanan & Produksi</h2><dl><dt>Berapa minimum order?</dt><dd>Minimum order 12 pcs untuk custom production. Untuk produk satuan tersedia di katalog e-commerce.</dd><dt>Berapa lama proses produksi?</dt><dd>Estimasi 7-14 hari kerja tergantung jumlah pesanan dan kompleksitas desain.</dd><dt>Apakah bisa order sampel dulu?</dt><dd>Ya, kami menyediakan layanan pembuatan sampel dengan biaya yang dapat disesuaikan.</dd><dt>Bahan apa saja yang tersedia?</dt><dd>Kami menyediakan berbagai bahan: Cotton Combed (24s/30s), Cotton Fleece, Heavy Cotton, Baby Terry, Drill (American/Nagata/Japan), Lacoste (CVC/PE), dan Dry-fit untuk jersey.</dd></dl><h2>Pembayaran</h2><dl><dt>Metode pembayaran apa yang tersedia?</dt><dd>Kami menerima transfer bank (BRI, BJB), e-wallet (OVO, ShopeePay, DANA), dan QRIS.</dt><dt>Apakah wajib DP?</dt><dd>Ya, DP 50% diperlukan untuk memulai produksi.</dd></dl><h2>Pengiriman</h2><dl><dt>Ekspedisi apa yang digunakan?</dt><dd>Kami bekerja sama dengan JNE, J&T Express, SiCepat, AnterAja, dan Ninja Express.</dd><dt>Berapa lama pengiriman?</dt><dd>Jawa: 2-5 hari kerja. Luar Jawa: 5-10 hari kerja.</dd><dt>Apakah bisa ambil langsung di workshop?</dt><dd>Ya, Anda bisa mengambil pesanan langsung di workshop kami di Desa Leuwigede, Kec. Widasari, Kab. Indramayu.</dd></dl><h2>Garansi</h2><dl><dt>Apa saja garansi yang diberikan?</dt><dd>Garansi tepat waktu (voucher 1 kaos jika terlambat), garansi kualitas (klaim 7 hari), dan garansi layanan (voucher jika tidak dilayani dalam 1x24 jam).</dd><dt>Bagaimana cara klaim garansi?</dt><dd>Hubungi kami via WhatsApp dengan menyertakan foto produk dan bukti pembelian dalam waktu 7 hari setelah diterima.</dd></dl>',
                'body_summary' => 'Pertanyaan yang sering diajukan tentang pemesanan, produksi, pembayaran, dan pengiriman.',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['handle' => $page['handle']], $page);
        }
    }
}
