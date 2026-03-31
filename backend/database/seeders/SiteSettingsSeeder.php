<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ===================================================================
            // 1. GENERAL SETTINGS
            // ===================================================================
            ['key' => 'site_name', 'value' => 'Mitologi Clothing', 'group' => 'general', 'type' => 'text', 'label' => 'Nama Situs'],
            ['key' => 'site_tagline', 'value' => 'Vendor Konveksi Terpercaya', 'group' => 'general', 'type' => 'text', 'label' => 'Tagline'],
            ['key' => 'site_description', 'value' => 'Vendor clothing terpercaya asal Indramayu, memproduksi kaos, kemeja, jaket, dan merchandise lainnya sejak 2022.', 'group' => 'general', 'type' => 'textarea', 'label' => 'Deskripsi Situs'],
            ['key' => 'site_logo', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'Logo Situs'],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'general', 'type' => 'image', 'label' => 'Favicon'],
            ['key' => 'company_founded_year', 'value' => '2022', 'group' => 'general', 'type' => 'text', 'label' => 'Tahun Berdiri'],

            // ===================================================================
            // 2. ABOUT US (Tentang Kami - PDF pages 1-3)
            // ===================================================================
            // Main Description
            ['key' => 'about_description_1', 'value' => 'Mitologi Clothing adalah vendor clothing asal Indramayu yang berdiri sejak 2022 dan bergerak dalam produksi berbagai jenis seragam dan merchandise untuk organisasi maupun instansi.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Deskripsi Tentang Kami 1'],
            ['key' => 'about_description_2', 'value' => 'Dengan dukungan tim berpengalaman, peralatan berstandar operasional, serta komitmen pada kerja keras, kerja cerdas, dan evaluasi berkelanjutan, Mitologi terus bertransformasi menjadi entitas yang profesional dan terpercaya.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Deskripsi Tentang Kami 2'],

            // Short History
            ['key' => 'about_short_history', 'value' => 'Pada tahun 2015 terinspirasi oleh seorang teman yang sebagai afiliator konveksi yang sukses, terbentuklah RROArt. Lalu tanggal 20 November 2022 memulai bisnis Mitologi Clothing dari maklon sampai sekarang buka usaha sendiri.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Sejarah Singkat'],

            // Logo Meaning
            ['key' => 'about_logo_meaning', 'value' => 'Bentuk biru dari shape warna biru mengartikan huruf M yaitu awal kata dari Mitologi.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Arti Logo'],
            ['key' => 'about_logo_meaning_detailed', 'value' => json_encode([
                ['letter' => 'M', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf M yaitu awal kata dari Mitologi.'],
                ['letter' => 'i', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf i yaitu huruf kedua dari Mitologi.'],
                ['letter' => 't', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf t yaitu huruf ketiga dari Mitologi.'],
                ['letter' => 'o', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf o yaitu huruf keempat dari Mitologi.'],
                ['letter' => 'L', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf L dan L mirror yaitu huruf kelima dari Mitologi.'],
                ['letter' => 'o', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf o yaitu huruf keenam dari Mitologi.'],
                ['letter' => 'g', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf g dan g mirror yaitu huruf ketujuh dari Mitologi.'],
                ['letter' => 'i', 'description' => 'Bentuk biru dari shape warna biru mengartikan huruf i yaitu huruf kedelapan dari Mitologi.'],
            ]), 'group' => 'about', 'type' => 'json', 'label' => 'Arti Logo Detail per Huruf'],
            ['key' => 'about_image', 'value' => null, 'group' => 'about', 'type' => 'image', 'label' => 'Gambar Tentang Kami'],

            // Founder Story
            ['key' => 'founder_name', 'value' => 'Rizky Rafalda Oktaviandri', 'group' => 'about', 'type' => 'text', 'label' => 'Nama Founder'],
            ['key' => 'founder_role', 'value' => 'Founder Mitologi Clothing', 'group' => 'about', 'type' => 'text', 'label' => 'Peran / Jabatan'],
            ['key' => 'founder_story', 'value' => 'Perjalanan kami dimulai dengan sebuah visi sederhana: menciptakan pakaian berkualitas yang tidak hanya nyaman dipakai, tetapi juga membawa nilai kebanggaan bagi setiap pemakainya. Kesuksesan awal kami memacu kami untuk membangun identitas vendor yang lebih besar dan profesional. Dengan komitmen, dedikasi, dan dukungan tim yang solid, Mitologi Clothing lahir untuk memberikan karya terbaik kepada pelanggan di seluruh Indonesia.', 'group' => 'about', 'type' => 'textarea', 'label' => 'Kisah Pendiri'],
            ['key' => 'founder_photo', 'value' => null, 'group' => 'about', 'type' => 'image', 'label' => 'Foto Founder'],

            // ===================================================================
            // 3. VISION, MISSION & VALUES (PDF page 5)
            // ===================================================================
            ['key' => 'vision_text', 'value' => 'Menjadi Brand yang dikenal dengan nuansa budaya dengan kualitas & Mutu terbaik', 'group' => 'vision_mission', 'type' => 'textarea', 'label' => 'Visi Perusahaan'],

            ['key' => 'mission_text', 'value' => "1. Meningkatkan mutu dan standar pekerjaan\n2. Menerapkan quality control yang tinggi\n3. Memberikan pelayanan yang cepat dan mudah\n4. Melakukan perniagaan sesuai aturan bernegara", 'group' => 'vision_mission', 'type' => 'textarea', 'label' => 'Misi Perusahaan'],

            ['key' => 'values_text', 'value' => "1. Kejujuran\n2. Produk yang berkualitas\n3. Ketepatan waktu\n4. Keberkahan usaha\n5. Mengangkat budaya dan pariwisata di Indonesia", 'group' => 'vision_mission', 'type' => 'textarea', 'label' => 'Nilai-Nilai Perusahaan'],

            // Company Values Data (structured JSON)
            ['key' => 'company_values_data', 'value' => json_encode([
                ['title' => 'Kejujuran', 'desc' => 'Menjaga integritas dalam setiap aspek bisnis', 'icon' => 'heroicon-o-shield-check'],
                ['title' => 'Produk Berkualitas', 'desc' => 'Komitmen pada standar kualitas tinggi', 'icon' => 'heroicon-o-star'],
                ['title' => 'Ketepatan Waktu', 'desc' => 'Menghormati deadline dan komitmen', 'icon' => 'heroicon-o-clock'],
                ['title' => 'Keberkahan Usaha', 'desc' => 'Berkah dalam setiap transaksi', 'icon' => 'heroicon-o-heart'],
                ['title' => 'Budaya Indonesia', 'desc' => 'Mengangkat budaya dan pariwisata Indonesia', 'icon' => 'heroicon-o-globe-alt'],
            ]), 'group' => 'vision_mission', 'type' => 'json', 'label' => 'Data Nilai Perusahaan'],

            // ===================================================================
            // 4. LEGALITY (PDF page 4)
            // ===================================================================
            ['key' => 'legal_company_name', 'value' => 'Mitologi Clothing', 'group' => 'legality', 'type' => 'text', 'label' => 'Nama Perusahaan'],
            ['key' => 'legal_address', 'value' => 'Desa Leuwigede Kec. Widasari Kab. Indramayu 45271', 'group' => 'legality', 'type' => 'text', 'label' => 'Alamat Legal'],
            ['key' => 'legal_business_field', 'value' => 'Vendor Konveksi / Broker / Brand', 'group' => 'legality', 'type' => 'text', 'label' => 'Bidang Usaha'],
            ['key' => 'legal_npwp', 'value' => '99.149.537.5-437.000', 'group' => 'legality', 'type' => 'text', 'label' => 'NPWP'],
            ['key' => 'legal_nib', 'value' => '0910240041097', 'group' => 'legality', 'type' => 'text', 'label' => 'NIB'],
            ['key' => 'legal_nmid', 'value' => 'ID1024309638855', 'group' => 'legality', 'type' => 'text', 'label' => 'NMID'],

            // ===================================================================
            // 5. SERVICES / PROGRAM KERJA (PDF pages 8-10)
            // ===================================================================
            ['key' => 'services_data', 'value' => json_encode([
                [
                    'title' => 'Produksi Kaos',
                    'desc' => 'Memproduksi berbagai macam bahan jenis kaos mulai dari Cotton Combed, Polyester hingga Hyget dan menyediakan pola mulai dari croptop, reguler, oversize hingga wangki.',
                    'image' => 'services/produksi-kaos.jpg',
                    'materials' => 'Cotton Combed 30s, 24s, Polyester, Hyget',
                    'keunggulan' => 'Jahitan Rapi, Sablon Awet, Bahan Nyaman',
                    'min_order' => '12 pcs (bisa 1 pcs untuk satuan)',
                ],
                [
                    'title' => 'Produksi Jaket',
                    'desc' => 'Memproduksi berbagai macam bahan jenis jaket mulai dari bahan PE Fleece, Cotton Fleece, Taslan, Scuba hingga Tracktop dan menyediakan pola Hoodie, Crewneck, Croptop, Varsity.',
                    'image' => 'services/produksi-jaket.jpg',
                    'materials' => 'PE Fleece, Cotton Fleece, Taslan, Scuba, Parasut',
                    'keunggulan' => 'Bahan Hangat, Resleting YKK, Tahan Angin',
                    'min_order' => '12 pcs',
                ],
                [
                    'title' => 'Produksi Kemeja',
                    'desc' => 'Memproduksi berbagai macam bahan jenis kemeja mulai dari Nagata Drill, American Drill hingga Japan Drill dan menyediakan pola mulai dari kemeja, semi jaket, hingga rompi.',
                    'image' => 'services/produksi-kemeja.jpg',
                    'materials' => 'Nagata Drill, American Drill, Japan Drill',
                    'keunggulan' => 'Bahan Kuat, Pola Presisi, Nyaman Dipakai',
                    'min_order' => '12 pcs',
                ],
                [
                    'title' => 'Produksi Hoodie & Sweater',
                    'desc' => 'Memproduksi hoodie dan sweater dengan bahan Cotton Fleece premium berbagai gramasi. Tersedia model Hoodie, Crewneck, Zip Hoodie, dan Oversized.',
                    'image' => 'services/produksi-hoodie.jpg',
                    'materials' => 'Cotton Fleece 240gsm, 280gsm, 330gsm',
                    'keunggulan' => 'Bahan Tebal, Hangat, Jahitan Rapi',
                    'min_order' => '12 pcs',
                ],
                [
                    'title' => 'Produksi Jersey',
                    'desc' => 'Memproduksi jersey olahraga dengan teknik sublimation printing full color. Cocok untuk jersey futsal, basket, sepak bola, dan jersey komunitas.',
                    'image' => 'services/produksi-jersey.jpg',
                    'materials' => 'Dry-fit Benzema, MTis, Pique, Brazil',
                    'keunggulan' => 'Full Print, Warna Tajam, Bahan Adem',
                    'min_order' => '12 pcs',
                ],
                [
                    'title' => 'Produksi Seragam (PDH/PDL)',
                    'desc' => 'Memproduksi seragam kerja, PDH, PDL, dan wearpack untuk instansi, perusahaan, atau organisasi. Include bordir logo dan nama.',
                    'image' => 'services/produksi-seragam.jpg',
                    'materials' => 'American Drill, Nagata Drill, Japan Drill',
                    'keunggulan' => 'Bahan Kuat, Bordir Rapi, Fit Sempurna',
                    'min_order' => '12 pcs',
                ],
            ]), 'group' => 'services', 'type' => 'json', 'label' => 'Data Layanan'],

            // ===================================================================
            // 6. GUARANTEE / KEUNGGULAN (PDF pages 11-13)
            // ===================================================================
            ['key' => 'guarantee_1_title', 'value' => 'GARANSI TEPAT WAKTU', 'group' => 'guarantee', 'type' => 'text', 'label' => 'Judul Garansi 1'],
            ['key' => 'guarantee_1_desc', 'value' => 'Dapatkan voucher cashback 1 kaos gratis apabila pesananmu melebihi tanggal deadline.', 'group' => 'guarantee', 'type' => 'textarea', 'label' => 'Deskripsi Garansi 1'],
            ['key' => 'guarantee_2_title', 'value' => 'GARANSI KUALITAS LAYANAN', 'group' => 'guarantee', 'type' => 'text', 'label' => 'Judul Garansi 2'],
            ['key' => 'guarantee_2_desc', 'value' => 'Dapatkan voucher cashback 1 kaos gratis apabila tidak dilayani dalam 1x24 jam.', 'group' => 'guarantee', 'type' => 'textarea', 'label' => 'Deskripsi Garansi 2'],
            ['key' => 'guarantee_3_title', 'value' => 'GARANSI BEBAS PENGEMBALIAN', 'group' => 'guarantee', 'type' => 'text', 'label' => 'Judul Garansi 3'],
            ['key' => 'guarantee_3_desc', 'value' => 'Barang tidak sesuai spesifikasi? Produk reject? KLAIM GARANSI BERLAKU 7 HARI.', 'group' => 'guarantee', 'type' => 'textarea', 'label' => 'Deskripsi Garansi 3'],

            // Guarantees Data (structured JSON for "Mengapa Memilih Kami")
            ['key' => 'guarantees_data', 'value' => json_encode([
                ['title' => 'Pengerjaan Tepat Waktu', 'description' => 'Tim kami berkomitmen menyelesaikan pesanan sesuai deadline yang telah disepakati bersama.', 'icon' => 'heroicon-o-clock'],
                ['title' => 'Bahan Premium Berkualitas', 'description' => 'Kami hanya menggunakan bahan-bahan pilihan berstandar tinggi untuk setiap produksi.', 'icon' => 'heroicon-o-star'],
                ['title' => 'Desain Custom Bebas', 'description' => 'Tidak ada batasan kreativitas — wujudkan desain apapun yang Anda inginkan bersama tim kami.', 'icon' => 'heroicon-o-pencil-square'],
                ['title' => 'Harga Transparan & Kompetitif', 'description' => 'Harga resmi tertera jelas tanpa biaya tersembunyi, cocok untuk komunitas, organisasi, dan UMKM.', 'icon' => 'heroicon-o-currency-dollar'],
                ['title' => 'Garansi Pengembalian 7 Hari', 'description' => 'Jika produk tidak sesuai spesifikasi, kami siap melakukan perbaikan atau penggantian produk.', 'icon' => 'heroicon-o-shield-check'],
                ['title' => 'Konsultasi Desain Gratis', 'description' => 'Tim desainer kami siap membantu dari tahap konsep sampai hasil akhir tanpa biaya tambahan.', 'icon' => 'heroicon-o-chat-bubble-left-right'],
            ]), 'group' => 'beranda', 'type' => 'json', 'label' => 'Mengapa Memilih Kami (Data)'],

            // Garansi & Bonus Data
            ['key' => 'garansi_bonus_data', 'value' => json_encode([
                ['title' => 'Garansi Tepat Waktu', 'description' => 'Dapatkan voucher cashback 1 kaos gratis apabila pesananmu melebihi tanggal deadline yang telah disepakati.', 'icon' => 'heroicon-o-clock'],
                ['title' => 'Garansi Kualitas', 'description' => 'Produk cacat produksi atau tidak sesuai spesifikasi? Klaim garansi bebas pengembalian berlaku 7 hari setelah terima barang.', 'icon' => 'heroicon-o-shield-check'],
                ['title' => 'Bonus Order > 100 pcs', 'description' => 'Order di atas 100 pcs: GRATIS 1 pcs kaos sablon + free sticker pack eksklusif + prioritas antrean produksi.', 'icon' => 'heroicon-o-gift'],
            ]), 'group' => 'beranda', 'type' => 'json', 'label' => 'Garansi & Bonus (Data)'],

            // ===================================================================
            // 7. CTA (Call to Action)
            // ===================================================================
            ['key' => 'cta_title', 'value' => 'Siap Memesan Seragam Impian Anda?', 'group' => 'cta', 'type' => 'text', 'label' => 'Judul CTA'],
            ['key' => 'cta_subtitle', 'value' => 'Konsultasikan kebutuhan seragam Anda dengan tim ahli kami sekarang juga.', 'group' => 'cta', 'type' => 'textarea', 'label' => 'Subjudul CTA'],
            ['key' => 'cta_button_text', 'value' => 'Hubungi Kami via WhatsApp', 'group' => 'cta', 'type' => 'text', 'label' => 'Teks Tombol CTA'],
            ['key' => 'cta_button_link', 'value' => 'https://wa.me/6281322170902', 'group' => 'cta', 'type' => 'text', 'label' => 'Link Tombol CTA'],

            // ===================================================================
            // 8. CONTACT & SOCIAL MEDIA (PDF pages 29-30)
            // ===================================================================
            ['key' => 'contact_email', 'value' => 'mitologiclothing@gmail.com', 'group' => 'contact', 'type' => 'text', 'label' => 'Email Kontak'],
            ['key' => 'contact_phone', 'value' => '+62 813-2217-0902', 'group' => 'contact', 'type' => 'text', 'label' => 'Nomor Telepon'],
            ['key' => 'contact_whatsapp', 'value' => '+62 813-2217-0902', 'group' => 'contact', 'type' => 'text', 'label' => 'Nomor WhatsApp'],
            ['key' => 'contact_address', 'value' => 'Desa Leuwigede Kec. Widasari Kab. Indramayu 45271', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Alamat Lengkap'],
            ['key' => 'contact_maps_embed', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63443.82!2d108.3127!3d-6.3301!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb962174360e5%3A0x6b8014588df8e530!2sWidasari%2C%20Indramayu%20Regency%2C%20West%20Java!5e0!3m2!1sen!2sid', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Google Maps Embed'],

            // Operating Hours
            ['key' => 'operating_hours_weekday_label', 'value' => 'Senin - Sabtu', 'group' => 'contact', 'type' => 'text', 'label' => 'Label Hari Kerja'],
            ['key' => 'operating_hours_weekday', 'value' => '08.00 - 16.00 WIB', 'group' => 'contact', 'type' => 'text', 'label' => 'Jam Kerja'],
            ['key' => 'operating_hours_weekend_label', 'value' => 'Minggu', 'group' => 'contact', 'type' => 'text', 'label' => 'Label Hari Libur'],
            ['key' => 'operating_hours_weekend', 'value' => 'Tutup (Online Chat Only)', 'group' => 'contact', 'type' => 'text', 'label' => 'Jam Libur'],

            // Social Media
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/mitologiclothing', 'group' => 'contact', 'type' => 'text', 'label' => 'Instagram URL'],
            ['key' => 'social_instagram_enabled', 'value' => '1', 'group' => 'contact', 'type' => 'boolean', 'label' => 'Tampilkan Instagram'],

            ['key' => 'social_tiktok', 'value' => 'https://tiktok.com/@mitologiclothing', 'group' => 'contact', 'type' => 'text', 'label' => 'TikTok URL'],
            ['key' => 'social_tiktok_enabled', 'value' => '1', 'group' => 'contact', 'type' => 'boolean', 'label' => 'Tampilkan TikTok'],

            ['key' => 'social_facebook', 'value' => 'https://facebook.com/mitologiclothing', 'group' => 'contact', 'type' => 'text', 'label' => 'Facebook URL'],
            ['key' => 'social_facebook_enabled', 'value' => '1', 'group' => 'contact', 'type' => 'boolean', 'label' => 'Tampilkan Facebook'],

            ['key' => 'social_shopee', 'value' => 'https://shopee.co.id/rizky_rafalda_oktaviandri', 'group' => 'contact', 'type' => 'text', 'label' => 'Shopee URL'],
            ['key' => 'social_shopee_enabled', 'value' => '1', 'group' => 'contact', 'type' => 'boolean', 'label' => 'Tampilkan Shopee'],

            ['key' => 'social_twitter', 'value' => '', 'group' => 'contact', 'type' => 'text', 'label' => 'Twitter/X URL'],
            ['key' => 'social_twitter_enabled', 'value' => '0', 'group' => 'contact', 'type' => 'boolean', 'label' => 'Tampilkan Twitter/X'],

            ['key' => 'whatsapp_number', 'value' => '6281322170902', 'group' => 'contact', 'type' => 'text', 'label' => 'Nomor WhatsApp (Format: 628...)'],

            // ===================================================================
            // 9. PAYMENT METHODS (PDF page 27)
            // ===================================================================
            ['key' => 'payment_methods_data', 'value' => json_encode([
                ['bank' => 'Bank Rakyat Indonesia (BRI)', 'account_number' => '0165-0106-9217-507', 'account_name' => 'RIZKY RAFALDA OKTAVIANDRI'],
                ['bank' => 'Bank BJB', 'account_number' => '0124211182100', 'account_name' => 'RIZKY RAFALDA OKTAVIANDRI'],
                ['bank' => 'OVO', 'account_number' => '0813-2217-0902', 'account_name' => 'RIZKY RAFALDA OKTAVIANDRI'],
                ['bank' => 'ShopeePay', 'account_number' => '0813-2217-0902', 'account_name' => 'RIZKY RAFALDA OKTAVIANDRI'],
                ['bank' => 'DANA', 'account_number' => '0813-2217-0902', 'account_name' => 'RIZKY RAFALDA OKTAVIANDRI'],
                ['bank' => 'QRIS', 'account_number' => 'Tersedia', 'account_name' => 'Tersedia via Chat'],
            ]), 'group' => 'payment', 'type' => 'json', 'label' => 'Data Metode Pembayaran'],

            // ===================================================================
            // 10. PRICELIST & MOQ (PDF pages 21-25, 28)
            // ===================================================================
            // Plastisol Pricing
            ['key' => 'pricing_plastisol_data', 'value' => json_encode([
                ['title' => '1 Sisi, 2 Warna', 'short' => '60K', 'long' => '70K', 'popular' => true],
                ['title' => '1 Sisi, 3-4 Warna', 'short' => '65K', 'long' => '75K', 'popular' => false],
                ['title' => '2 Sisi, 2 Warna', 'short' => '70K', 'long' => '80K', 'popular' => false],
                ['title' => '2 Sisi, 3-4 Warna', 'short' => '75K', 'long' => '85K', 'popular' => false],
            ]), 'group' => 'pricing', 'type' => 'json', 'label' => 'Data Harga Sablon Plastisol'],

            // Kemeja & Polo Pricing
            ['key' => 'pricing_kemeja_data', 'value' => json_encode([
                ['title' => 'Kemeja PDH / PDL (American Drill)', 'price' => '120K'],
                ['title' => 'Kemeja PDH / PDL (Nagata Drill)', 'price' => '130K'],
                ['title' => 'Polo Shirt (Lacoste PE)', 'price' => '80K'],
                ['title' => 'Polo Shirt (Lacoste CVC)', 'price' => '90K'],
                ['title' => 'Wearpack / PDL / Korsa', 'price' => '140K'],
            ]), 'group' => 'pricing', 'type' => 'json', 'label' => 'Data Harga Kemeja & Polo'],

            // Merchandise Pricing
            ['key' => 'pricing_merchandise_data', 'value' => json_encode([
                ['title' => 'Topi Custom (Min. 20 pcs)', 'price' => '20K'],
                ['title' => 'Tas Canvas (Min. 50 pcs)', 'price' => '40K'],
                ['title' => 'Waistbag (Min. 50 pcs)', 'price' => '55K'],
                ['title' => 'Lanyard (Min. 50 pcs)', 'price' => '15K'],
            ]), 'group' => 'pricing', 'type' => 'json', 'label' => 'Data Harga Merchandise'],

            // Add-ons
            ['key' => 'pricing_addons_data', 'value' => json_encode([
                ['name' => 'Sablon DTF', 'price' => '+ Rp 15.000/pcs'],
                ['name' => 'Bordir Komputer', 'price' => '+ Rp 20.000/pcs'],
                ['name' => 'Label Woven', 'price' => '+ Rp 5.000/pcs'],
                ['name' => 'Packaging Custom', 'price' => '+ Rp 10.000/pcs'],
            ]), 'group' => 'pricing', 'type' => 'json', 'label' => 'Data Add-ons'],

            // MOQ Data
            ['key' => 'moq_data', 'value' => json_encode([
                ['product' => 'Kaos Satuan (+Sablon)', 'moq' => '1 pcs', 'materials' => 'PE, Cotton'],
                ['product' => 'Hoodie/Crewneck Satuan', 'moq' => '1 pcs', 'materials' => 'PE Fleece, Fleece Cotton 280gsm/330gsm'],
                ['product' => 'Jersey Printing', 'moq' => '12 pcs', 'materials' => 'Dry-fit, Emboss'],
                ['product' => 'Kemeja / PDH / Jaket', 'moq' => '12 pcs', 'materials' => 'Drill, Fleece, Parasut'],
                ['product' => 'Topi Custom', 'moq' => '20 pcs', 'materials' => 'Rafel, Drill'],
                ['product' => 'Merchandise (Tas, Lanyard)', 'moq' => '50 pcs', 'materials' => 'Canvas, Lanyard Tissue'],
            ]), 'group' => 'pricing', 'type' => 'json', 'label' => 'Minimum Order Qty'],

            // ===================================================================
            // 11. SEO SETTINGS
            // ===================================================================
            ['key' => 'seo_meta_title', 'value' => 'Mitologi Clothing - Vendor Konveksi Indramayu', 'group' => 'seo', 'type' => 'text', 'label' => 'Meta Title'],
            ['key' => 'seo_meta_description', 'value' => 'Vendor konveksi terpercaya di Indramayu. Melayani pembuatan kaos, kemeja, jaket, jersey, dan merchandise dengan kualitas terbaik.', 'group' => 'seo', 'type' => 'textarea', 'label' => 'Meta Description'],
            ['key' => 'seo_keywords', 'value' => 'konveksi indramayu, vendor kaos, pembuatan seragam, konveksi murah, sablon kaos, vendor hoodie, pembuatan jersey', 'group' => 'seo', 'type' => 'textarea', 'label' => 'SEO Keywords'],

            // ===================================================================
            // 12. SHIPPING INFO (PDF page 26)
            // ===================================================================
            ['key' => 'shipping_partners', 'value' => json_encode(['JNE', 'J&T Express', 'SiCepat', 'AnterAja', 'Ninja Express']), 'group' => 'shipping', 'type' => 'json', 'label' => 'Ekspedisi Pengiriman'],
            ['key' => 'shipping_coverage', 'value' => 'Seluruh Indonesia', 'group' => 'shipping', 'type' => 'text', 'label' => 'Jangkauan Pengiriman'],
            ['key' => 'shipping_estimate_java', 'value' => '2-5 hari kerja', 'group' => 'shipping', 'type' => 'text', 'label' => 'Estimasi Jawa'],
            ['key' => 'shipping_estimate_outer', 'value' => '5-10 hari kerja', 'group' => 'shipping', 'type' => 'text', 'label' => 'Estimasi Luar Jawa'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
