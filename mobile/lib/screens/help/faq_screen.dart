import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class FaqScreen extends StatefulWidget {
  const FaqScreen({super.key});

  @override
  State<FaqScreen> createState() => _FaqScreenState();
}

class _FaqScreenState extends State<FaqScreen> {
  final List<Map<String, String>> categories = [
    {'key': 'umum', 'label': 'Umum'},
    {'key': 'pemesanan', 'label': 'Pemesanan'},
    {'key': 'pengiriman', 'label': 'Pengiriman'},
    {'key': 'pengembalian', 'label': 'Pengembalian & Penukaran'},
    {'key': 'pembayaran', 'label': 'Pembayaran'},
    {'key': 'akun', 'label': 'Akun'},
  ];

  final Map<String, List<Map<String, String>>> faqData = {
    'umum': [
      {
        'q': 'Apa itu Mitologi Clothing?',
        'a':
            'Mitologi Clothing adalah brand pakaian premium yang memproduksi berbagai jenis pakaian seperti kaos, kemeja, jaket, dan merchandise dengan sentuhan budaya Indonesia dan kualitas terbaik. Kami berlokasi di Indramayu, Jawa Barat.',
      },
      {
        'q': 'Apakah produk Mitologi Clothing dibuat secara handmade?',
        'a':
            'Sebagian besar produk kami diproduksi dengan kombinasi teknik modern dan sentuhan tangan terampil. Setiap produk melewati quality control yang ketat untuk memastikan kualitas terbaik sampai ke tangan Anda.',
      },
      {
        'q': 'Apakah Mitologi Clothing menerima pesanan custom/partai besar?',
        'a':
            'Ya, kami menerima pesanan custom untuk kebutuhan instansi, seragam, komunitas, atau event. Silakan hubungi kami melalui WhatsApp atau halaman Hubungi Kami untuk diskusi lebih lanjut mengenai desain, bahan, dan minimum order.',
      },
      {
        'q': 'Apakah ukuran produk sesuai dengan standar lokal?',
        'a':
            'Produk kami menggunakan standar ukuran lokal (Reguler Fit). Kami sangat menyarankan Anda melihat tabel "Panduan Ukuran" yang tersedia di setiap produk atau mencoba Kalkulator Ukuran kami sebelum membeli.',
      },
    ],
    'pemesanan': [
      {
        'q': 'Bagaimana cara melakukan pemesanan?',
        'a':
            'Anda dapat melakukan pemesanan melalui aplikasi/website kami. Pilih produk yang diinginkan, tambahkan ke keranjang, lalu ikuti proses checkout. Anda juga bisa memesan langsung melalui WhatsApp kami.',
      },
      {
        'q': 'Apakah saya perlu membuat akun untuk berbelanja?',
        'a':
            'Tidak wajib saat menelusuri, namun Anda memerlukannya saat checkout untuk menikmati keuntungan seperti riwayat pesanan, pelacakan pengiriman yang lebih mudah, dan penawaran khusus member.',
      },
      {
        'q': 'Bisakah saya mengubah atau membatalkan pesanan?',
        'a':
            'Perubahan atau pembatalan pesanan dapat dilakukan selama pesanan belum diproses. Hubungi customer service kami sesegera mungkin melalui WhatsApp atau email. Pesanan yang sudah dikirim tidak dapat dibatalkan.',
      },
    ],
    'pengiriman': [
      {
        'q': 'Berapa lama proses pengiriman?',
        'a':
            'Proses pengiriman biasanya memakan waktu 2-5 hari kerja untuk wilayah Jawa dan 5-10 hari kerja untuk luar Jawa, tergantung pada jasa pengiriman yang dipilih. Pesanan akan diproses dalam 1-2 hari kerja setelah pembayaran dikonfirmasi.',
      },
      {
        'q': 'Jasa pengiriman apa yang digunakan?',
        'a':
            'Kami bekerja sama dengan berbagai jasa pengiriman terpercaya seperti JNE, J&T Express, SiCepat, dan Anteraja. Anda dapat memilih jasa pengiriman yang sesuai saat checkout.',
      },
      {
        'q': 'Apakah tersedia gratis ongkir?',
        'a':
            'Ya! Kami menyediakan gratis ongkir untuk pembelian dengan minimum Rp 200.000 ke seluruh wilayah Indonesia. Promo ini dapat berubah sewaktu-waktu, jadi pastikan untuk mengecek halaman promo kami.',
      },
      {
        'q': 'Bagaimana cara melacak pesanan saya?',
        'a':
            'Setelah pesanan dikirim, Anda akan mendapatkan nomor resi melalui email atau WhatsApp. Anda juga dapat melihat status pesanan melalui halaman "Pesanan Saya" di menu akun Anda di aplikasi ini.',
      },
    ],
    'pengembalian': [
      {
        'q': 'Apakah bisa melakukan pengembalian barang?',
        'a':
            'Ya, kami menerima pengembalian barang dalam waktu 7 hari setelah barang diterima, dengan syarat barang masih dalam kondisi original (belum dicuci, belum dipakai, tag masih terpasang). Baca kebijakan pengembalian kami untuk detail lengkap.',
      },
      {
        'q': 'Bagaimana proses penukaran ukuran?',
        'a':
            'Jika ukuran tidak sesuai, Anda dapat mengajukan penukaran dalam waktu 7 hari. Hubungi customer service kami, kirimkan barang kembali, dan kami akan mengirimkan ukuran yang sesuai setelah barang diterima dan diverifikasi.',
      },
      {
        'q': 'Siapa yang menanggung biaya pengiriman pengembalian?',
        'a':
            'Jika pengembalian disebabkan oleh kesalahan kami (barang cacat, salah kirim), biaya pengiriman ditanggung oleh Mitologi Clothing. Untuk pengembalian dengan alasan lain (tidak cocok ukuran, berubah pikiran), biaya pengiriman ditanggung oleh pembeli.',
      },
    ],
    'pembayaran': [
      {
        'q': 'Metode pembayaran apa saja yang tersedia?',
        'a':
            'Kami menerima pembayaran melalui Transfer Bank (BCA, BRI, Mandiri, BNI), E-Wallet (GoPay, OVO, Dana, ShopeePay), Virtual Account, dan pembayaran melalui Indomaret/Alfamart. Semua transaksi diproses secara aman melalui Midtrans.',
      },
      {
        'q': 'Apakah pembayaran di aplikasi ini aman?',
        'a':
            'Sangat aman. Kami menggunakan Midtrans sebagai payment gateway yang sudah tersertifikasi PCI DSS. Data pembayaran Anda dienkripsi dan tidak tersimpan di server kami.',
      },
      {
        'q': 'Berapa lama batas waktu pembayaran?',
        'a':
            'Batas waktu pembayaran untuk Transfer Bank dan Virtual Account adalah 24 jam setelah pesanan dibuat. Jika pembayaran tidak diterima dalam waktu tersebut, pesanan akan otomatis dibatalkan.',
      },
    ],
    'akun': [
      {
        'q': 'Bagaimana cara merubah informasi akun saya?',
        'a':
            'Anda dapat merubah nama, email, dan password Anda melalui menu "Akun" -> "Edit Profile" atau "Ubah Password" di aplikasi Anda.',
      },
      {
        'q': 'Apa fungsi fitur Wishlist?',
        'a':
            'Fitur Wishlist memungkinkan Anda menyimpan produk-produk yang Anda sukai untuk dibeli nanti tanpa harus mencarinya kembali di katalog.',
      },
    ],
  };

  String activeCategory = 'umum';
  Set<int> expandedIndexes = {};

  void _toggleExpanded(int index) {
    setState(() {
      if (expandedIndexes.contains(index)) {
        expandedIndexes.remove(index);
      } else {
        expandedIndexes.add(index);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final activeFaqs = faqData[activeCategory] ?? [];

    return MitologiScaffold(
      title: 'FAQ',
      subtitle: 'Pertanyaan yang Sering Diajukan',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => context.popOrGoHome(),
      ),
      body: SingleChildScrollView(
        child: MitologiPageShell(
          title: 'Pertanyaan yang Sering Diajukan',
          eyebrow: 'Bantuan',
          subtitle:
              'Temukan jawaban atas pertanyaan umum tentang produk, pemesanan, pengiriman, dan lainnya.',
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Category Tabs
              SizedBox(
                height: 40,
                child: ListView.separated(
                  scrollDirection: Axis.horizontal,
                  physics: const BouncingScrollPhysics(),
                  itemCount: categories.length,
                  separatorBuilder: (context, index) =>
                      const SizedBox(width: 8),
                  itemBuilder: (context, index) {
                    final cat = categories[index];
                    final isActive = activeCategory == cat['key'];
                    return GestureDetector(
                      onTap: () {
                        setState(() {
                          activeCategory = cat['key']!;
                          expandedIndexes
                              .clear(); // reset expansions on category change
                        });
                      },
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        decoration: BoxDecoration(
                          color: isActive
                              ? AppTheme.primary
                              : AppTheme.surfaceContainerLowest,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        alignment: Alignment.center,
                        child: Text(
                          cat['label']!,
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 13,
                            color: isActive ? Colors.white : AppTheme.slate600,
                          ),
                        ),
                      ),
                    );
                  },
                ),
              ),
              const SizedBox(height: 24),

              // Accordion List
              ListView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: activeFaqs.length,
                itemBuilder: (context, index) {
                  final faq = activeFaqs[index];
                  final isExpanded = expandedIndexes.contains(index);

                  return Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    decoration: BoxDecoration(
                      color: isExpanded
                          ? AppTheme.surfaceContainerLow
                          : AppTheme.surfaceContainerLowest,
                      borderRadius: AppTheme.radius16,
                    ),
                    clipBehavior: Clip.antiAlias,
                    child: Theme(
                      data: Theme.of(
                        context,
                      ).copyWith(dividerColor: Colors.transparent),
                      child: ExpansionTile(
                        key: PageStorageKey('${activeCategory}_$index'),
                        initiallyExpanded: isExpanded,
                        onExpansionChanged: (expanded) {
                          _toggleExpanded(index);
                        },
                        title: Text(
                          faq['q']!,
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 14,
                            color: isExpanded
                                ? AppTheme.primary
                                : AppTheme.slate800,
                          ),
                        ),
                        iconColor: AppTheme.primary,
                        collapsedIconColor: AppTheme.slate400,
                        tilePadding: const EdgeInsets.symmetric(
                          horizontal: 20,
                          vertical: 4,
                        ),
                        childrenPadding: const EdgeInsets.only(
                          left: 20,
                          right: 20,
                          bottom: 20,
                        ),
                        children: [
                          Text(
                            faq['a']!,
                            style: const TextStyle(
                              fontWeight: FontWeight.w500,
                              fontSize: 13,
                              color: AppTheme.slate600,
                              height: 1.6,
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),

              const SizedBox(height: 48),

              // CTA
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppTheme.surfaceContainerLowest,
                  borderRadius: AppTheme.radius16,
                ),
                child: Column(
                  children: [
                    const Text(
                      'Masih punya pertanyaan?',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 20,
                        color: AppTheme.accent,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Jika Anda tidak menemukan jawaban yang dicari, jangan ragu untuk menghubungi tim kami.',
                      style: TextStyle(
                        fontWeight: FontWeight.w500,
                        fontSize: 13,
                        color: AppTheme.slate500,
                        height: 1.5,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          // TODO: Launch WhatsApp or Help contact
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
                          style: TextStyle(fontWeight: FontWeight.bold),
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
