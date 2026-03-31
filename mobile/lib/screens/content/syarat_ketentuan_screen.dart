import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class SyaratKetentuanScreen extends StatelessWidget {
  const SyaratKetentuanScreen({super.key});

  Widget buildHeading(String text) {
    return Padding(
      padding: const EdgeInsets.only(top: 32, bottom: 16),
      child: Text(
        text,
        style: const TextStyle(
          fontWeight: FontWeight.w800,
          fontSize: 18,
          color: AppTheme.slate800,
        ),
      ),
    );
  }

  Widget buildParagraph(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Text(
        text,
        style: const TextStyle(
          fontWeight: FontWeight.w500,
          fontSize: 14,
          color: AppTheme.slate600,
          height: 1.6,
        ),
      ),
    );
  }

  Widget buildBullet(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8, left: 8, right: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(
            padding: EdgeInsets.only(top: 6, right: 12),
            child: Icon(Icons.circle, size: 6, color: AppTheme.slate400),
          ),
          Expanded(
            child: Text(
              text,
              style: const TextStyle(
                fontWeight: FontWeight.w500,
                fontSize: 14,
                color: AppTheme.slate600,
                height: 1.6,
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Syarat & Ketentuan',
      subtitle: 'Ketentuan layanan Mitologi Clothing',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => context.popOrGoHome(),
      ),
      body: SingleChildScrollView(
        child: MitologiPageShell(
          title: 'Syarat & Ketentuan Layanan',
          eyebrow: 'Legal',
          subtitle:
              'Terakhir diperbarui: 15 Maret 2024\n\nSelamat datang di Mitologi Clothing. Harap baca Syarat dan Ketentuan berikut dengan saksama karena penggunaan layanan ini menandakan persetujuan Anda.',
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              buildHeading('1. Definisi Pihak'),
              buildParagraph(
                '"Kami", "Mitologi Clothing", merujuk pada pemilik aplikasi/website. "Anda" merujuk pada pengguna atau pelihat aplikasi kami.',
              ),

              buildHeading('2. Penggunaan Aplikasi'),
              buildParagraph(
                'Penggunaan Anda atas informasi atau materi apa pun di aplikasi ini sepenuhnya merupakan risiko Anda sendiri, di mana kami tidak akan bertanggung jawab.',
              ),
              buildBullet(
                'Aplikasi ini mengandung materi yang dimiliki oleh atau dilisensikan kepada kami. Materi ini termasuk, namun tidak terbatas pada, desain, tata letak, tampilan, dan grafis.',
              ),
              buildBullet(
                'Penggunaan tidak sah atas aplikasi ini dapat menimbulkan klaim ganti rugi dan/atau merupakan tindak pidana.',
              ),

              buildHeading('3. Akun Pengguna'),
              buildParagraph(
                'Anda bertanggung jawab untuk menjaga kerahasiaan kata sandi Anda dan akun Anda. Anda juga setuju untuk:',
              ),
              buildBullet(
                'Memberikan informasi yang akurat, valid, terkini, dan lengkap.',
              ),
              buildBullet(
                'Segera memberi tahu kami tentang penggunaan kata sandi atau akun Anda yang tidak sah.',
              ),
              buildBullet(
                'Bertanggung jawab atas semua aktivitas yang terjadi di bawah akun Anda.',
              ),

              buildHeading('4. Informasi Produk dan Ketersediaan'),
              buildParagraph(
                'Kami berusaha menampilkan produk kami seakurat mungkin. Namun, warna yang Anda lihat akan bergantung pada layar Anda, dan kami tidak dapat menjamin bahwa tampilan warna pada layar Anda akan 100% akurat.',
              ),
              buildParagraph(
                'Semua produk tunduk pada ketersediaan stok. Kami berhak membatasi jumlah produk yang dapat dibeli, dan berhak untuk tidak menerima atau membatalkan pesanan apa pun dengan alasan apa pun.',
              ),

              buildHeading('5. Harga dan Pembayaran'),
              buildBullet(
                'Semua harga tercantum dalam Rupiah Indonesia (IDR) dan termasuk pajak yang berlaku jika ada.',
              ),
              buildBullet(
                'Kami berhak mengubah harga produk kapan saja tanpa pemberitahuan sebelumnya.',
              ),
              buildBullet(
                'Pembayaran harus dilakukan secara penuh dan terkonfirmasi sebelum produk dikirim.',
              ),

              buildHeading('6. Pengiriman dan Risiko Kerugian'),
              buildParagraph(
                'Estimasi pengiriman hanya merupakan perkiraan. Risiko kehilangan dan kerusakan beralih kepada Anda sejak pesanan diserahkan kepada jasa ekspedisi.',
              ),

              buildHeading('7. Pembatalan Pesanan oleh Pihak Kami'),
              buildParagraph(
                'Kami berhak membatalkan pesanan jika produk tidak tersedia, ada kesalahan harga, pesanan mencurigakan, atau batas waktu pembayaran berakhir.',
              ),

              buildHeading('8. Kebijakan Pengembalian'),
              buildParagraph(
                'Pengembalian barang tunduk pada Kebijakan Pengembalian kami yang merupakan bagian terpisahkan dari Syarat dan Ketentuan ini.',
              ),

              buildHeading('9. Modifikasi Syarat'),
              buildParagraph(
                'Kami berhak merevisi Syarat dan Ketentuan ini kapan saja. Perubahan akan berlaku segera setelah diposting.',
              ),

              const SizedBox(height: 32),
              // Support Card
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppTheme.primary,
                  borderRadius: AppTheme.radius16,
                  boxShadow: [
                    BoxShadow(
                      color: AppTheme.primary.withAlpha(38),
                      blurRadius: 20,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    const Icon(
                      Icons.help_outline,
                      color: AppTheme.primary,
                      size: 40,
                    ),
                    const SizedBox(height: 16),
                    const Text(
                      'Butuh Penjelasan Lebih Lanjut?',
                      style: TextStyle(
                        fontWeight: FontWeight.w800,
                        fontSize: 18,
                        color: Colors.white,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Jika Anda memiliki pertanyaan mengenai Syarat & Ketentuan ini, silakan hubungi tim kami.',
                      style: TextStyle(
                        fontWeight: FontWeight.w500,
                        fontSize: 13,
                        color: Colors.white70,
                        height: 1.5,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {},
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.white,
                          foregroundColor: AppTheme.primary,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(30),
                          ),
                        ),
                        child: const Text(
                          'Hubungi Kami',
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 48),
            ],
          ),
        ),
      ),
    );
  }
}
