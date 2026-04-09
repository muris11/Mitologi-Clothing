import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class KebijakanPengembalianScreen extends StatelessWidget {
  const KebijakanPengembalianScreen({super.key});

  final List<Map<String, dynamic>> steps = const [
    {
      'icon': Icons.chat_bubble_outline,
      'title': 'Hubungi CS',
      'description':
          'Hubungi customer service kami melalui WhatsApp atau email untuk mengajukan pengembalian.',
    },
    {
      'icon': Icons.local_shipping_outlined,
      'title': 'Kirim Barang',
      'description':
          'Kemas barang dengan rapi dan kirimkan ke alamat workshop kami menggunakan jasa pengiriman.',
    },
    {
      'icon': Icons.verified_outlined,
      'title': 'Verifikasi',
      'description':
          'Tim kami akan memeriksa kondisi barang dalam 1-3 hari kerja setelah barang diterima.',
    },
    {
      'icon': Icons.attach_money_outlined,
      'title': 'Refund / Tukar',
      'description':
          'Dana akan dikembalikan atau produk pengganti dikirimkan dalam 5-14 hari kerja.',
    },
  ];

  final List<Map<String, String>> faqs = const [
    {
      'q': 'Berapa lama batas waktu pengajuan pengembalian?',
      'a':
          'Anda memiliki waktu 7 hari kalender setelah barang diterima untuk mengajukan pengembalian. Pengajuan yang melewati batas waktu tersebut tidak dapat diproses.',
    },
    {
      'q': 'Apakah biaya pengiriman pengembalian ditanggung Mitologi?',
      'a':
          'Jika pengembalian disebabkan oleh kesalahan kami (barang cacat, salah produk, salah ukuran dari sisi produksi), biaya pengiriman kami tanggung. Untuk alasan lain, biaya menjadi tanggung jawab pembeli.',
    },
    {
      'q': 'Bagaimana proses refund dilakukan?',
      'a':
          'Refund akan dilakukan melalui Transfer Bank ke rekening yang Anda informasikan. Proses memakan waktu 5-14 hari kerja setelah verifikasi selesai. Kami akan menginformasikan progress melalui WhatsApp atau email.',
    },
    {
      'q': 'Apakah bisa tukar produk saja tanpa refund?',
      'a':
          'Ya, kami menyediakan opsi penukaran produk (misalnya tukar ukuran atau warna). Penukaran akan diproses setelah barang asli diterima dan diverifikasi oleh tim kami.',
    },
  ];

  Widget buildHtmlLikeHeading(String text) {
    return Padding(
      padding: const EdgeInsets.only(top: 32, bottom: 16),
      child: Text(
        text,
        style: const TextStyle(
          fontWeight: FontWeight.w800,
          fontSize: 18,
          color: AppTheme.onSurface,
        ),
      ),
    );
  }

  Widget buildHtmlLikeBullet(String text, BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(
        bottom: 8,
        left: ResponsiveHelper.horizontalPadding(context) / 2,
        right: ResponsiveHelper.horizontalPadding(context) / 2,
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(
            padding: EdgeInsets.only(top: 6, right: 12),
            child: Icon(Icons.circle, size: 6, color: AppTheme.onSurfaceMuted),
          ),
          Expanded(
            child: Text(
              text,
              style: const TextStyle(
                fontWeight: FontWeight.w500,
                fontSize: 14,
                color: AppTheme.onSurfaceVariant,
                height: 1.6,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget buildHtmlLikeNumber(int index, String text, {List<Widget>? children}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12, left: 8, right: 8),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Padding(
                padding: const EdgeInsets.only(right: 12),
                child: Text(
                  '$index.',
                  style: const TextStyle(
                    fontWeight: FontWeight.w700,
                    fontSize: 14,
                    color: AppTheme.onSurfaceVariant,
                  ),
                ),
              ),
              Expanded(
                child: Text(
                  text,
                  style: const TextStyle(
                    fontWeight: FontWeight.w500,
                    fontSize: 14,
                    color: AppTheme.onSurfaceVariant,
                    height: 1.6,
                  ),
                ),
              ),
            ],
          ),
          if (children != null)
            Padding(
              padding: const EdgeInsets.only(left: 24, top: 4),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: children,
              ),
            ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Kebijakan Pengembalian',
      subtitle: 'Informasi pengembalian barang',
      showLogo: false,
      leading: IconButton(
        tooltip: 'Back',
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => context.popOrGoHome(),
      ),
      body: SingleChildScrollView(
        child: MitologiPageShell(
          title: 'Kebijakan Pengembalian',
          eyebrow: 'Kebijakan',
          subtitle:
              'Kami ingin Anda puas dengan setiap pembelian. Jika tidak sesuai, kami siap membantu.',
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Return Steps
              const Padding(
                padding: EdgeInsets.only(bottom: 24),
                child: Row(
                  children: [
                    Icon(Icons.sync, color: AppTheme.primary, size: 28),
                    SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Langkah Pengembalian',
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 22,
                              color: AppTheme.primary,
                            ),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Proses pengembalian barang di Mitologi Clothing mudah dan transparan.',
                            style: TextStyle(
                              fontWeight: FontWeight.w500,
                              fontSize: 13,
                              color: AppTheme.onSurfaceVariant,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              GridView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  crossAxisSpacing: 12,
                  mainAxisSpacing: 12,
                  childAspectRatio: 0.75,
                ),
                itemCount: steps.length,
                itemBuilder: (context, index) {
                  final step = steps[index];
                  return Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withValues(alpha: 0.03),
                          blurRadius: 15,
                          offset: const Offset(0, 8),
                        ),
                      ],
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Align(
                          alignment: Alignment.topLeft,
                          child: Text(
                            '0${index + 1}',
                            style: TextStyle(
                              fontWeight: FontWeight.w900,
                              fontSize: 10,
                              color: Colors.grey.shade300,
                            ),
                          ),
                        ),
                        Icon(
                          step['icon'] as IconData,
                          color: AppTheme.primary,
                          size: 28,
                        ),
                        const SizedBox(height: 10),
                        Text(
                          step['title'] as String,
                          textAlign: TextAlign.center,
                          style: const TextStyle(
                            fontWeight: FontWeight.w800,
                            fontSize: 13,
                            color: AppTheme.onSurface,
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          step['description'] as String,
                          textAlign: TextAlign.center,
                          maxLines: 4,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(
                            fontWeight: FontWeight.w500,
                            fontSize: 11,
                            color: AppTheme.onSurfaceVariant,
                            height: 1.4,
                          ),
                        ),
                      ],
                    ),
                  );
                },
              ),

              const Padding(
                padding: EdgeInsets.symmetric(vertical: 32),
                child: Divider(color: AppTheme.muted, thickness: 1),
              ),

              // Main Content
              const Text(
                'Syarat Pengembalian',
                style: TextStyle(
                  fontWeight: FontWeight.w800,
                  fontSize: 24,
                  color: AppTheme.primary,
                ),
              ),

              buildHtmlLikeHeading('Produk yang Dapat Dikembalikan'),
              buildHtmlLikeBullet(
                'Produk yang diterima dalam kondisi cacat/rusak dari pabrik',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk yang tidak sesuai dengan pesanan (salah warna, salah model, salah ukuran karena kesalahan produksi)',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk yang berbeda dari deskripsi secara signifikan',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk yang masih dalam kondisi baru, belum dicuci, belum dipakai, dan tag masih melekat',
                context,
              ),

              buildHtmlLikeHeading('Produk yang Tidak Dapat Dikembalikan'),
              buildHtmlLikeBullet(
                'Produk yang sudah dicuci, dipakai, atau dimodifikasi dengan cara apapun',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk dengan tag yang sudah dilepas atau rusak',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk yang dikembalikan setelah melewati batas waktu 7 hari',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk custom atau pesanan khusus yang dibuat sesuai permintaan spesifik',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk yang rusak akibat kelalaian pembeli (bukan dari pabrik)',
                context,
              ),
              buildHtmlLikeBullet(
                'Produk sale/clearance dengan keterangan "tanpa pengembalian"',
                context,
              ),

              buildHtmlLikeHeading('Batas Waktu Pengembalian'),
              Container(
                padding: const EdgeInsets.all(20),
                margin: const EdgeInsets.only(bottom: 24),
                decoration: BoxDecoration(
                  color: AppTheme.sectionBackground,
                  borderRadius: AppTheme.radius16,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.02),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: const BoxDecoration(
                        color: Colors.white,
                        shape: BoxShape.circle,
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black12,
                            blurRadius: 4,
                            offset: Offset(0, 2),
                          ),
                        ],
                      ),
                      child: const Icon(
                        Icons.sync,
                        color: AppTheme.onSurfaceVariant,
                        size: 20,
                      ),
                    ),
                    const SizedBox(width: 16),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            '7 Hari Kalender',
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 16,
                              color: AppTheme.onSurface,
                            ),
                          ),
                          SizedBox(height: 8),
                          Text(
                            'Pengajuan pengembalian harus dilakukan dalam waktu 7 hari kalender setelah barang diterima. Tanggal penerimaan dihitung berdasarkan konfirmasi penerimaan dari jasa pengiriman.',
                            style: TextStyle(
                              fontWeight: FontWeight.w500,
                              fontSize: 13,
                              color: AppTheme.onSurfaceVariant,
                              height: 1.6,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              buildHtmlLikeHeading('Prosedur Pengembalian'),
              buildHtmlLikeNumber(
                1,
                'Hubungi customer service kami melalui WhatsApp atau email dengan menyertakan:',
                children: [
                  buildHtmlLikeBullet('Nomor pesanan (Order ID)', context),
                  buildHtmlLikeBullet(
                    'Foto produk yang menunjukkan kondisi/masalah',
                    context,
                  ),
                  buildHtmlLikeBullet('Alasan pengembalian', context),
                ],
              ),
              buildHtmlLikeNumber(
                2,
                'Tunggu konfirmasi dari tim kami (maksimal 1×24 jam pada hari kerja)',
              ),
              buildHtmlLikeNumber(
                3,
                'Kemas produk dengan rapi menggunakan kemasan yang aman untuk pengiriman',
              ),
              buildHtmlLikeNumber(
                4,
                'Kirimkan produk ke alamat yang diberikan oleh tim kami beserta salinan nomor pesanan',
              ),
              buildHtmlLikeNumber(
                5,
                'Konfirmasi pengiriman dengan mengirimkan nomor resi kepada tim kami',
              ),

              buildHtmlLikeHeading('Proses Pengembalian Dana'),
              buildHtmlLikeBullet(
                'Refund diproses setelah barang diterima dan lolos verifikasi oleh tim kami (1-3 hari kerja)',
                context,
              ),
              buildHtmlLikeBullet(
                'Dana akan dikembalikan melalui Transfer Bank ke rekening yang Anda informasikan',
                context,
              ),
              buildHtmlLikeBullet(
                'Proses transfer refund memakan waktu 5-14 hari kerja setelah verifikasi selesai',
                context,
              ),
              buildHtmlLikeBullet(
                'Jumlah refund mencakup harga produk. Biaya pengiriman awal tidak dikembalikan kecuali pengembalian disebabkan oleh kesalahan kami',
                context,
              ),

              buildHtmlLikeHeading('Penukaran Produk'),
              Padding(
                padding: EdgeInsets.only(
                  left: ResponsiveHelper.horizontalPadding(context) / 2,
                  right: ResponsiveHelper.horizontalPadding(context) / 2,
                  bottom: 24,
                ),
                child: const Text(
                  'Jika Anda memilih untuk menukar produk (misalnya tukar ukuran atau warna), proses pengiriman produk pengganti akan dilakukan setelah barang asli diterima dan diverifikasi. Biaya pengiriman produk pengganti ditanggung oleh Mitologi Clothing jika penukaran disebabkan oleh kesalahan kami.',
                  style: TextStyle(
                    fontWeight: FontWeight.w500,
                    fontSize: 14,
                    color: AppTheme.onSurfaceVariant,
                    height: 1.6,
                  ),
                ),
              ),

              const Padding(
                padding: EdgeInsets.symmetric(vertical: 32),
                child: Divider(color: AppTheme.muted, thickness: 1),
              ),

              // Mini FAQ
              const Padding(
                padding: EdgeInsets.only(bottom: 24),
                child: Row(
                  children: [
                    Icon(
                      Icons.chat_outlined,
                      color: AppTheme.primary,
                      size: 28,
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Pertanyaan Umum',
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 22,
                              color: AppTheme.primary,
                            ),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Terkait proses pengembalian.',
                            style: TextStyle(
                              fontWeight: FontWeight.w500,
                              fontSize: 13,
                              color: AppTheme.onSurfaceVariant,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              ...faqs.map(
                (faq) => Container(
                  margin: const EdgeInsets.only(bottom: 12),
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(20),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.03),
                        blurRadius: 15,
                        offset: const Offset(0, 8),
                      ),
                    ],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        faq['q'] as String,
                        style: const TextStyle(
                          fontWeight: FontWeight.w800,
                          fontSize: 15,
                          color: AppTheme.onSurface,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        faq['a'] as String,
                        style: const TextStyle(
                          fontWeight: FontWeight.w500,
                          fontSize: 13,
                          color: AppTheme.onSurfaceVariant,
                          height: 1.5,
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 48),

              // CTA
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(32),
                decoration: BoxDecoration(
                  color: AppTheme.primary,
                  borderRadius: AppTheme.radius16,
                  boxShadow: [
                    BoxShadow(
                      color: AppTheme.primary.withAlpha(51),
                      blurRadius: 24,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    const Text(
                      'Butuh Bantuan dengan Pengembalian?',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontWeight: FontWeight.w800,
                        fontSize: 20,
                        color: Colors.white,
                      ),
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Tim customer service kami siap membantu Anda. Hubungi kami untuk proses pengembalian yang cepat dan mudah.',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontWeight: FontWeight.w500,
                        fontSize: 13,
                        color: Colors.white70,
                        height: 1.6,
                      ),
                    ),
                    const SizedBox(height: 32),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          // TODO WhatsApp Launch
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.accent,
                          foregroundColor: Colors.white,
                          elevation: 0,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(30),
                          ),
                        ),
                        child: const Text(
                          'Chat WhatsApp',
                          style: TextStyle(
                            fontWeight: FontWeight.w800,
                            fontSize: 15,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }
}
