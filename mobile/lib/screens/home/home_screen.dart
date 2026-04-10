import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../config/theme.dart';
import '../../providers/content_provider.dart';
import '../../providers/product_provider.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/animations/animations.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';
import 'widgets/home_collections_section.dart';
import 'widgets/home_hero_slider.dart';
import 'widgets/home_materials_section.dart';
import 'widgets/home_product_section.dart';
import 'widgets/home_quick_links.dart';
import 'widgets/home_recommendations_section.dart';
import 'widgets/home_search_bar.dart';
import 'widgets/home_testimonials_section.dart';

/// Redesigned Home Screen - Premium E-commerce Experience
///
/// Refactored to use modular widgets for better maintainability and performance.
class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ProductProvider>().loadHomeData();
      context.read<ContentProvider>().fetchLandingPage();
    });
  }

  Future<void> _handleRefresh() async {
    context.read<ProductProvider>().loadHomeData();
    if (mounted) {
      await context.read<ContentProvider>().fetchLandingPage();
    }
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Mitologi Clothing',
      subtitle: 'Premium clothing dengan kurasi koleksi dan inspirasi belanja.',
      bodyPadding: EdgeInsets.zero,
      actions: [
        IconButton(
          icon: const Icon(
            Icons.support_agent,
            color: AppTheme.primary,
            size: 24,
          ),
          onPressed: () => context.push('/chatbot'),
        ),
      ],
      body: Consumer2<ContentProvider, ProductProvider>(
        builder: (context, contentProvider, productProvider, child) {
          // Show skeleton while loading
          if (contentProvider.isLoadingLanding ||
              productProvider.isLoadingHomeData) {
            return const HomeSkeleton();
          }

          if (contentProvider.landingError != null) {
            return _buildErrorState(contentProvider);
          }

          final data = contentProvider.landingPage;
          if (data == null) return const SizedBox.shrink();

          return RefreshIndicator(
            onRefresh: _handleRefresh,
            color: AppTheme.primary,
            backgroundColor: AppTheme.pageBackground,
            strokeWidth: 2.5,
            child: CustomScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              slivers: [
                // Search Bar
                const SliverToBoxAdapter(
                  child: FadeInUp(
                    delay: Duration(milliseconds: 50),
                    child: HomeSearchBar(),
                  ),
                ),

                // Main Content
                SliverToBoxAdapter(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Hero Slider
                      if (data.heroSlides.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 0),
                          child: HomeHeroSlider(slides: data.heroSlides),
                        ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Quick Links
                      const FadeInUp(
                        delay: Duration(milliseconds: 100),
                        child: HomeQuickLinks(),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // New Arrivals Section
                      FadeInUp(
                        delay: const Duration(milliseconds: 150),
                        child: HomeProductSection(
                          title: 'Produk Baru',
                          subtitle: 'Koleksi terbaru dari Mitologi',
                          description: 'Dipilih untuk membantu Anda langsung menemukan koleksi yang paling relevan hari ini.',
                          products: productProvider.newArrivals,
                          onSeeAll: () => context.push('/shop?sort=latest'),
                          staggerIndex: 1,
                        ),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Best Sellers Section
                      FadeInUp(
                        delay: const Duration(milliseconds: 200),
                        child: HomeProductSection(
                          title: 'Best Sellers',
                          subtitle: 'Produk paling populer minggu ini',
                          description: 'Koleksi terlaris yang menjadi favorit pelanggan kami di seluruh Indonesia.',
                          icon: Icons.trending_up,
                          products: productProvider.bestSellers,
                          onSeeAll: () =>
                              context.push('/shop?sort=best-selling'),
                          staggerIndex: 2,
                        ),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Collections
                      if (productProvider.collections.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 250),
                          child: HomeCollectionsSection(
                            collections: productProvider.collections,
                            staggerIndex: 3,
                          ),
                        ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // AI Recommendations
                      const FadeInUp(
                        delay: Duration(milliseconds: 300),
                        child: HomeRecommendationsSection(),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Materials
                      if (data.materials.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 350),
                          child: HomeMaterialsSection(materials: data.materials),
                        ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Testimonials
                      if (data.testimonials.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 400),
                          child: HomeTestimonialsSection(
                            testimonials: data.testimonials,
                          ),
                        ),

                      // Bottom spacing
                      SizedBox(
                        height: ResponsiveHelper.sectionGap(context) * 2,
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildErrorState(ContentProvider provider) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: AppTheme.errorLight,
              borderRadius: AppTheme.radius22,
            ),
            child: const Icon(
              Icons.error_outline,
              size: 48,
              color: AppTheme.error,
            ),
          ),
          const SizedBox(height: 24),
          Text(
            'Gagal memuat halaman utama',
            style: Theme.of(context).textTheme.titleMedium,
          ),
          const SizedBox(height: 8),
          Text(
            'Silakan periksa koneksi internet Anda',
            style: Theme.of(
              context,
            ).textTheme.bodyMedium?.copyWith(color: AppTheme.onSurfaceVariant),
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            onPressed: provider.fetchLandingPage,
            icon: const Icon(Icons.refresh),
            label: const Text('Coba Lagi'),
          ),
        ],
      ),
    );
  }
}
