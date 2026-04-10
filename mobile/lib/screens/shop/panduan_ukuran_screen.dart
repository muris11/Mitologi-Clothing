import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:url_launcher/url_launcher.dart';

import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_page_shell.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/animations/animations.dart';
import '../../widgets/animations/shimmer_button.dart';
import 'widgets/size_guide_widgets.dart';

class PanduanUkuranScreen extends StatefulWidget {
  const PanduanUkuranScreen({super.key});

  @override
  State<PanduanUkuranScreen> createState() => _PanduanUkuranScreenState();
}

class _PanduanUkuranScreenState extends State<PanduanUkuranScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  Future<void> _openWhatsApp() async {
    final url = Uri.parse(
      'https://wa.me/6281322170902?text=Halo%2C%20saya%20ingin%20konsultasi%20ukuran',
    );
    try {
      if (await canLaunchUrl(url)) {
        await launchUrl(url, mode: LaunchMode.externalApplication);
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Gagal membuka WhatsApp')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return MitologiScaffold(
      body: NestedScrollView(
        headerSliverBuilder: (context, innerBoxIsScrolled) {
          return [
            SliverAppBar(
              title: const Text('Panduan Ukuran'),
              centerTitle: true,
              pinned: true,
              forceElevated: innerBoxIsScrolled,
              backgroundColor: AppTheme.surface,
              surfaceTintColor: Colors.transparent,
            ),
            SliverPadding(
              padding: EdgeInsets.fromLTRB(horizontalPadding, 24, horizontalPadding, 16),
              sliver: SliverToBoxAdapter(
                child: FadeInDown(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Pilih Ukuran yang Tepat',
                        style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                              fontWeight: FontWeight.w900,
                              color: AppTheme.primary,
                            ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Gunakan standar ukuran Mitologi untuk mendapatkan kenyamanan maksimal saat mengenakan produk kami.',
                        style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                              color: AppTheme.onSurfaceVariant,
                              height: 1.5,
                            ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
            SliverPersistentHeader(
              pinned: true,
              delegate: _SliverAppBarDelegate(
                child: Container(
                  color: AppTheme.surface,
                  padding: EdgeInsets.symmetric(horizontal: horizontalPadding, vertical: 8),
                  child: Container(
                    padding: const EdgeInsets.all(4),
                    decoration: BoxDecoration(
                      color: AppTheme.primary.withValues(alpha: 0.05),
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: TabBar(
                      controller: _tabController,
                      indicator: BoxDecoration(
                        color: AppTheme.primary,
                        borderRadius: BorderRadius.circular(12),
                        boxShadow: AppTheme.shadowSoft,
                      ),
                      indicatorSize: TabBarIndicatorSize.tab,
                      labelColor: Colors.white,
                      unselectedLabelColor: AppTheme.onSurfaceVariant,
                      dividerColor: Colors.transparent,
                      labelStyle: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13),
                      tabs: const [
                        Tab(text: 'Kaos & Jersey'),
                        Tab(text: 'Kemeja'),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ];
        },
        body: TabBarView(
          controller: _tabController,
          children: [
            _buildTabContent(const KaosSizeGuide(), horizontalPadding),
            _buildTabContent(const KemejaSizeGuide(), horizontalPadding),
          ],
        ),
      ),
    );
  }

  Widget _buildTabContent(Widget sizeGuide, double horizontalPadding) {
    return SingleChildScrollView(
      padding: EdgeInsets.symmetric(horizontal: horizontalPadding, vertical: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          FadeInUp(child: sizeGuide),
          const Divider(height: 64, thickness: 1, color: Color(0xFFE2E8F0)),
          
          // Measurement Guide
          FadeInUp(child: const MeasurementGuideGrid()),
          const Divider(height: 64, thickness: 1, color: Color(0xFFE2E8F0)),
          
          // Tips
          FadeInUp(child: const SizeTipsSection()),
          const SizedBox(height: 48),
          
          // CTA Section
          FadeInUp(
            child: Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [AppTheme.primary, Color(0xFF1E293B)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(24),
                boxShadow: AppTheme.shadowMedium,
              ),
              child: Column(
                children: [
                  const Icon(Icons.support_agent, color: Colors.white, size: 48),
                  const SizedBox(height: 16),
                  const Text(
                    'Masih bingung dengan ukuran Anda?',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 18,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Konsultasikan pesanan Anda langsung dengan tim ahli kami di WhatsApp.',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: Colors.white70,
                      fontSize: 14,
                      height: 1.5,
                    ),
                  ),
                  const SizedBox(height: 24),
                  Semantics(
                    label: 'Hubungi tim ahli di WhatsApp',
                    button: true,
                    child: ShimmerButton(
                      onPressed: _openWhatsApp,
                      background: AppTheme.success,
                      borderRadius: 16,
                      child: const Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(Icons.chat_bubble_outline, size: 20, color: Colors.white),
                          SizedBox(width: 12),
                          Text(
                            'Konsultasi via WhatsApp',
                            style: TextStyle(fontWeight: FontWeight.w800, color: Colors.white),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 32),
        ],
      ),
    );
  }
}

class _SliverAppBarDelegate extends SliverPersistentHeaderDelegate {
  _SliverAppBarDelegate({required this.child});

  final Widget child;

  @override
  double get minExtent => 64.0;
  @override
  double get maxExtent => 64.0;

  @override
  Widget build(BuildContext context, double shrinkOffset, bool overlapsContent) {
    return child;
  }

  @override
  bool shouldRebuild(_SliverAppBarDelegate oldDelegate) {
    return false;
  }
}
