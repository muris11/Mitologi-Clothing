import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class KebijakanPrivasiScreen extends StatelessWidget {
  const KebijakanPrivasiScreen({super.key});

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

  Widget buildBullet(String text, BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(
        bottom: 8,
        left: ResponsiveHelper.horizontalPadding(context) / 2,
        right: ResponsiveHelper.horizontalPadding(context) / 2,
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
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
      title: 'Kebijakan Privasi',
      subtitle: 'Informasi perlindungan data pengguna',
      showLogo: false,
      leading: IconButton(
        tooltip: 'Back',
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => context.popOrGoHome(),
      ),
      body: SingleChildScrollView(
        child: MitologiPageShell(
          title: 'Kebijakan Privasi Mitologi Clothing',
          eyebrow: 'Privasi',
          subtitle:
              'Terakhir diperbarui: 15 Maret 2024\n\nKami di Mitologi Clothing ("kami", "milik kami", atau "Mitologi") sangat menghargai privasi Anda dan berkomitmen untuk melindungi informasi pribadi Anda.',
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              buildHeading('1. Informasi yang Kami Kumpulkan'),
              buildParagraph(
                'Kami mengumpulkan beberapa jenis informasi saat Anda berinteraksi dengan layanan kami:',
              ),
              buildBullet(
                'Informasi Identitas Diri: Nama lengkap, alamat email, nomor telepon, dan tanggal lahir saat Anda mendaftar akun.',
                context,
              ),
              buildBullet(
                'Informasi Pengiriman: Alamat lengkap untuk pengiriman barang pesanan Anda.',
                context,
              ),
              buildBullet(
                'Informasi Transaksi: Rincian pesanan, riwayat pembelian, dan status pembayaran. (Catatan: Kami tidak menyimpan detail kartu kredit/debit Anda di server kami).',
                context,
              ),
              buildBullet(
                'Data Interaksi: Informasi tentang cara Anda menggunakan aplikasi kami, produk yang dilihat, dan waktu kunjungan.',
                context,
              ),

              buildHeading('2. Bagaimana Kami Menggunakan Informasi Anda'),
              buildParagraph(
                'Informasi yang kami kumpulkan digunakan untuk tujuan berikut:',
              ),
              buildBullet(
                'Memproses dan memenuhi pesanan Anda secara efisien.',
                context,
              ),
              buildBullet(
                'Berkomunikasi dengan Anda mengenai status pesanan, pengiriman, atau kendala layanan.',
                context,
              ),
              buildBullet(
                'Memberikan dukungan pelanggan yang lebih baik dan dipersonalisasi.',
                context,
              ),
              buildBullet(
                'Meningkatkan kualitas produk, layanan pelanggan, dan pengalaman pengguna aplikasi kami.',
                context,
              ),
              buildBullet(
                'Mengirimkan informasi promo, penawaran khusus, dan update koleksi terbaru (jika Anda berlangganan buletin kami).',
                context,
              ),

              buildHeading('3. Perlindungan Data Anda'),
              buildParagraph(
                'Kami menerapkan berbagai langkah keamanan untuk menjaga keamanan informasi pribadi Anda saat Anda melakukan pemesanan, memasukkan, atau mengakses informasi pribadi Anda. Sistem kami menggunakan enkripsi Secure Socket Layer (SSL) standar industri dan metode pengamanan tingkat lanjut lainnya. Data Anda disimpan di balik jaringan yang aman dan hanya dapat diakses oleh sejumlah orang terbatas yang memiliki hak akses khusus.',
              ),

              buildHeading('4. Berbagi Informasi dengan Pihak Ketiga'),
              buildParagraph(
                'Kami tidak menjual, memperdagangkan, atau menyewakan informasi identitas pribadi Anda kepada pihak lain. Kami mungkin membagikan informasi dengan pihak ketiga yang terpercaya demi kelancaran operasi kami, meliputi:',
              ),
              buildBullet(
                'Mitra Logistik: Untuk keperluan pengiriman pesanan Anda (seperti JNE, J&T, SiCepat).',
                context,
              ),
              buildBullet(
                'Payment Gateway: Untuk memproses pembayaran dengan aman (seperti Midtrans).',
                context,
              ),
              buildParagraph(
                'Pihak ketiga ini setuju untuk menjaga kerahasiaan informasi ini dan hanya menggunakannya untuk tujuan layanan yang mereka berikan.',
              ),

              buildHeading('5. Cookie dan Teknologi Pelacakan'),
              buildParagraph(
                'Kami menggunakan "cookies" pada aplikasi kami untuk meningkatkan pengalaman pengguna. Anda dapat memilih untuk menonaktifkan cookie melalui pengaturan perangkat Anda, namun beberapa fitur aplikasi kami mungkin tidak berfungsi dengan maksimal.',
              ),

              buildHeading('6. Hak Anda Atas Data Anda'),
              buildParagraph(
                'Anda memiliki hak atas data pribadi Anda, termasuk:',
              ),
              buildBullet(
                'Hak Mengakses: Anda dapat melihat dan memperbarui informasi akun Anda kapan saja.',
                context,
              ),
              buildBullet(
                'Hak Penghapusan: Anda berhak meminta penghapusan akun dan data pribadi Anda dari sistem kami.',
                context,
              ),
              buildBullet(
                'Berhenti Berlangganan: Anda dapat memilih untuk tidak menerima email pemasaran dari kami kapan saja melalui tautan di bagian bawah email kami.',
                context,
              ),

              const SizedBox(height: 24),
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
                      Icons.shield_outlined,
                      color: AppTheme.primary,
                      size: 40,
                    ),
                    const SizedBox(height: 16),
                    const Text(
                      'Pertanyaan tentang Privasi?',
                      style: TextStyle(
                        fontWeight: FontWeight.w800,
                        fontSize: 18,
                        color: Colors.white,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini, silakan hubungi tim kami.',
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
              const SizedBox(height: 32),
            ],
          ),
        ),
      ),
    );
  }
}
