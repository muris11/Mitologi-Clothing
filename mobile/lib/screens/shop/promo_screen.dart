import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class PromoScreen extends StatelessWidget {
  const PromoScreen({super.key});

  final List<Map<String, dynamic>> benefits = const [
    {
      'icon': Icons.local_shipping_outlined,
      'title': "Gratis Ongkir",
      'description':
          "Nikmati gratis ongkir untuk setiap pembelian minimum Rp 200.000 ke seluruh Indonesia.",
    },
    {
      'icon': Icons.local_offer_outlined,
      'title': "Diskon Member",
      'description':
          "Daftar sebagai member dan dapatkan diskon hingga 15% untuk setiap pembelian berikutnya.",
    },
    {
      'icon': Icons.card_giftcard_outlined,
      'title': "Hadiah Spesial",
      'description':
          "Dapatkan hadiah spesial di hari ulang tahun Anda dan bonus poin setiap transaksi.",
    },
  ];

  final List<Map<String, dynamic>> promos = const [
    {
      'title': "Flash Sale Mingguan",
      'description':
          "Setiap hari Jumat, nikmati diskon hingga 30% untuk produk-produk pilihan. Stok terbatas, jangan sampai kehabisan!",
      'badge': "Setiap Jumat",
      'icon': Icons.calendar_today_outlined,
    },
    {
      'title': "Beli 2 Gratis 1",
      'description':
          "Berlaku untuk kategori kaos dan t-shirt pilihan. Pilih 3 produk favorit Anda dan bayar hanya 2!",
      'badge': "Syarat & Ketentuan Berlaku",
      'icon': Icons.people_outline,
    },
    {
      'title': "Diskon Koleksi Baru",
      'description':
          "Dapatkan potongan 10% untuk pre-order koleksi terbaru kami. Jadilah yang pertama memiliki desain eksklusif.",
      'badge': "Pre-Order",
      'icon': Icons.sell_outlined,
    },
  ];

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Promo',
      subtitle: 'Promo & Penawaran Spesial',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => context.popOrGoShop(),
      ),
      body: SingleChildScrollView(
        child: MitologiPageShell(
          title: 'Promo & Penawaran Spesial',
          eyebrow: 'Promo',
          subtitle:
              'Nikmati berbagai penawaran menarik dan keuntungan eksklusif dari Mitologi Clothing.',
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Active Promos
              const Padding(
                padding: EdgeInsets.only(bottom: 24),
                child: Row(
                  children: [
                    Icon(
                      Icons.sell_outlined,
                      color: AppTheme.primary,
                      size: 28,
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Promo Aktif',
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 18,
                              color: AppTheme.primary,
                            ),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Penawaran terbatas, segera manfaatkan!',
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

              ...promos.map(
                (promo) => Container(
                  margin: const EdgeInsets.only(bottom: 16),
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: AppTheme.surfaceContainerLow,
                    borderRadius: AppTheme.radius16,
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Icon(
                            promo['icon'] as IconData,
                            color: AppTheme.primary,
                            size: 28,
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 12,
                              vertical: 6,
                            ),
                            decoration: BoxDecoration(
                              color: AppTheme.primary.withAlpha(26),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              promo['badge'] as String,
                              style: TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 10,
                                color: AppTheme.primary.withAlpha(
                                  204,
                                ), // A slightly darker gold for readability
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Text(
                        promo['title'] as String,
                        style: const TextStyle(
                          fontWeight: FontWeight.w800,
                          fontSize: 18,
                          color: AppTheme.onSurface,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        promo['description'] as String,
                        style: const TextStyle(
                          fontWeight: FontWeight.w500,
                          fontSize: 13,
                          color: AppTheme.onSurfaceVariant,
                          height: 1.6,
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              const Padding(
                padding: EdgeInsets.symmetric(vertical: 32),
                child: Divider(color: AppTheme.muted, thickness: 1),
              ),

              // Benefits
              const Padding(
                padding: EdgeInsets.only(bottom: 24),
                child: Row(
                  children: [
                    Icon(
                      Icons.card_giftcard_outlined,
                      color: AppTheme.primary,
                      size: 28,
                    ),
                    SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Keuntungan Berbelanja',
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 18,
                              color: AppTheme.primary,
                            ),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Lebih hemat dengan keuntungan eksklusif untuk pelanggan kami.',
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

              ...benefits.map(
                (benefit) => Container(
                  margin: const EdgeInsets.only(bottom: 16),
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: AppTheme.surfaceContainerLow,
                    borderRadius: AppTheme.radius16,
                  ),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: const BoxDecoration(
                          color: AppTheme.sectionBackground,
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          benefit['icon'] as IconData,
                          color: AppTheme.primary,
                          size: 24,
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              benefit['title'] as String,
                              style: const TextStyle(
                                fontWeight: FontWeight.w800,
                                fontSize: 16,
                                color: AppTheme.onSurface,
                              ),
                            ),
                            const SizedBox(height: 6),
                            Text(
                              benefit['description'] as String,
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
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 32),

              // CTA Registration
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
                      'Jangan Lewatkan Penawaran Eksklusif!',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontWeight: FontWeight.w800,
                        fontSize: 18,
                        color: Colors.white,
                      ),
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Daftar sekarang untuk mendapatkan notifikasi promo terbaru dan akses ke penawaran khusus member.',
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
                          context.push('/shop/register');
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
                          'Daftar Sekarang',
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
