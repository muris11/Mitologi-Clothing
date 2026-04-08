import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import 'package:carousel_slider/carousel_slider.dart';
import '../../models/collection.dart';
import '../../models/product.dart';
import '../../providers/content_provider.dart';
import '../../providers/product_provider.dart';
import '../../providers/auth_provider.dart';
import '../../config/theme.dart';
import '../../widgets/animations/animations.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/product/product_card.dart';
import '../../utils/storage_url.dart';
import '../../utils/responsive_helper.dart';
import 'package:cached_network_image/cached_network_image.dart';

/// Redesigned Home Screen - Premium E-commerce Experience
///
/// Features:
/// - Skeleton loading states (replaces CircularProgressIndicator)
/// - BlurFade animations with stagger
/// - Responsive layout using ResponsiveHelper
/// - Updated theme (22px radius, multi-layer shadows, Plus Jakarta Sans)
class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentCarouselIndex = 0;

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
                SliverToBoxAdapter(
                  child: FadeInUp(
                    delay: const Duration(milliseconds: 50),
                    child: _buildSearchBar(),
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
                          child: _buildHeroSlider(data.heroSlides),
                        ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Quick Links
                      FadeInUp(
                        delay: const Duration(milliseconds: 100),
                        child: _buildQuickLinks(),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // New Arrivals Section
                      FadeInUp(
                        delay: const Duration(milliseconds: 150),
                        child: _buildProductSection(
                          title: 'Produk Baru',
                          subtitle: 'Koleksi terbaru dari Mitologi',
                          products: productProvider.newArrivals,
                          onSeeAll: () => context.push('/shop?sort=latest'),
                        ),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Best Sellers Section
                      FadeInUp(
                        delay: const Duration(milliseconds: 200),
                        child: _buildProductSection(
                          title: 'Best Sellers',
                          subtitle: 'Produk paling populer minggu ini',
                          products: productProvider.bestSellers,
                          onSeeAll: () =>
                              context.push('/shop?sort=best-selling'),
                        ),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Collections
                      if (productProvider.collections.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 250),
                          child: _buildCollectionsSection(
                            productProvider.collections,
                          ),
                        ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // AI Recommendations
                      FadeInUp(
                        delay: const Duration(milliseconds: 300),
                        child: _buildRecommendationsSection(),
                      ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Materials
                      if (data.materials.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 350),
                          child: _buildMaterialsSection(data.materials),
                        ),

                      SizedBox(height: ResponsiveHelper.sectionGap(context)),

                      // Testimonials
                      if (data.testimonials.isNotEmpty)
                        FadeInUp(
                          delay: const Duration(milliseconds: 400),
                          child: _buildTestimonialsSection(data.testimonials),
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

  Widget _buildSearchBar() {
    return Padding(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      child: GestureDetector(
        onTap: () => context.push('/shop'),
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          decoration: BoxDecoration(
            color: AppTheme.surface,
            borderRadius: AppTheme.radius16,
            border: Border.all(color: AppTheme.outline),
            boxShadow: AppTheme.shadowSoft,
          ),
          child: Row(
            children: [
              Icon(Icons.search, color: AppTheme.onSurfaceVariant, size: 22),
              const SizedBox(width: 12),
              Text(
                'Cari produk...',
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: AppTheme.onSurfaceMuted,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeroSlider(List slides) {
    final heroHeight = ResponsiveHelper.heroHeight(context);

    return Stack(
      children: [
        CarouselSlider(
          options: CarouselOptions(
            height: heroHeight,
            viewportFraction: 1.0,
            autoPlay: true,
            autoPlayInterval: const Duration(seconds: 5),
            autoPlayAnimationDuration: const Duration(milliseconds: 800),
            autoPlayCurve: Curves.easeInOut,
            onPageChanged: (index, reason) {
              setState(() => _currentCarouselIndex = index);
            },
          ),
          items: slides.map((slide) {
            return Builder(
              builder: (BuildContext context) {
                return Stack(
                  fit: StackFit.expand,
                  children: [
                    // Image with blur-to-sharp loading
                    CachedNetworkImage(
                      imageUrl: StorageUrl.format(slide.image),
                      fit: BoxFit.cover,
                      alignment: Alignment.topCenter,
                      placeholder: (context, url) => Container(
                        color: AppTheme.muted,
                        child: const Center(
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: AppTheme.accent,
                          ),
                        ),
                      ),
                      errorWidget: (context, url, error) => Container(
                        color: AppTheme.muted,
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(
                              Icons.image_not_supported_outlined,
                              color: AppTheme.slate400,
                              size: 40,
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Gagal memuat gambar',
                              style: Theme.of(context).textTheme.bodySmall
                                  ?.copyWith(color: AppTheme.onSurfaceVariant),
                            ),
                          ],
                        ),
                      ),
                    ),
                    // Gradient overlay
                    Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          stops: const [0.3, 0.85],
                          colors: [
                            Colors.transparent,
                            Colors.black.withValues(alpha: 0.75),
                          ],
                        ),
                      ),
                    ),
                    // Content
                    Positioned(
                      bottom: 48,
                      left: ResponsiveHelper.horizontalPadding(context),
                      right: ResponsiveHelper.horizontalPadding(context),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            slide.title,
                            textAlign: TextAlign.center,
                            style: Theme.of(context).textTheme.displaySmall
                                ?.copyWith(
                                  color: Colors.white,
                                  fontWeight: FontWeight.w800,
                                  letterSpacing: -0.5,
                                ),
                          ),
                          const SizedBox(height: 12),
                          Text(
                            slide.subtitle,
                            textAlign: TextAlign.center,
                            style: Theme.of(context).textTheme.bodyLarge
                                ?.copyWith(
                                  color: Colors.white.withValues(alpha: 0.9),
                                  height: 1.5,
                                ),
                          ),
                          const SizedBox(height: 28),
                          PressableButton(
                            onPressed: () {
                              if (slide.target.isNotEmpty) {
                                context.push('/shop');
                              }
                            },
                            backgroundColor: AppTheme.accent,
                            foregroundColor: AppTheme.onAccent,
                            borderRadius: AppTheme.radiusMd,
                            padding: const EdgeInsets.symmetric(
                              horizontal: 32,
                              vertical: 16,
                            ),
                            child: Text(
                              slide.linkText.isNotEmpty
                                  ? slide.linkText
                                  : 'Beli Sekarang',
                              style: const TextStyle(
                                fontSize: 15,
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                );
              },
            );
          }).toList(),
        ),
        // Carousel indicators
        Positioned(
          bottom: 20,
          left: 0,
          right: 0,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: slides.asMap().entries.map((entry) {
              final isActive = _currentCarouselIndex == entry.key;
              return AnimatedContainer(
                duration: const Duration(milliseconds: 300),
                width: isActive ? 28.0 : 8.0,
                height: 8.0,
                margin: const EdgeInsets.symmetric(horizontal: 4.0),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(4),
                  color: isActive
                      ? Colors.white
                      : Colors.white.withValues(alpha: 0.4),
                ),
              );
            }).toList(),
          ),
        ),
      ],
    );
  }

  Widget _buildQuickLinks() {
    final spacing = ResponsiveHelper.gridSpacing(context);

    final quickLinks = [
      _QuickLink(
        icon: Icons.local_offer_outlined,
        label: 'Promo',
        onTap: () => context.push('/shop/promo'),
        color: AppTheme.primary,
      ),
      _QuickLink(
        icon: Icons.straighten,
        label: 'Panduan Ukuran',
        onTap: () => context.push('/shop/panduan-ukuran'),
        color: AppTheme.primary,
      ),
      _QuickLink(
        icon: Icons.trending_up,
        label: 'Populer',
        onTap: () => context.push('/shop?sort=best-selling'),
        color: AppTheme.primary,
      ),
      _QuickLink(
        icon: Icons.info_outline,
        label: 'Tentang Kami',
        onTap: () => context.push('/shop/tentang-kami'),
        color: AppTheme.onSurfaceVariant,
      ),
    ];

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context),
      ),
      child: Row(
        children: quickLinks.asMap().entries.map((entry) {
          final link = entry.value;
          final isFirst = entry.key == 0;

          return Padding(
            padding: EdgeInsets.only(left: isFirst ? 0 : spacing),
            child: _buildQuickLinkChip(link),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildQuickLinkChip(_QuickLink link) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: link.onTap,
        borderRadius: AppTheme.radius16,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
          decoration: BoxDecoration(
            color: AppTheme.surface,
            borderRadius: AppTheme.radius16,
            border: Border.all(color: AppTheme.outlineLight),
            boxShadow: AppTheme.shadowSoft,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: link.color.withValues(alpha: 0.1),
                  shape: BoxShape.circle,
                ),
                child: Icon(link.icon, color: link.color, size: 24),
              ),
              const SizedBox(height: 8),
              Text(
                link.label,
                style: Theme.of(context).textTheme.labelSmall?.copyWith(
                  fontWeight: FontWeight.w600,
                  color: AppTheme.onSurface,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProductSection({
    required String title,
    required String subtitle,
    required List<Product> products,
    required VoidCallback onSeeAll,
  }) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final sectionGap = ResponsiveHelper.sectionGap(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Header
        Padding(
          padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    subtitle,
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: AppTheme.onSurfaceVariant,
                    ),
                  ),
                ],
              ),
              TextButton(
                onPressed: onSeeAll,
                style: TextButton.styleFrom(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 12,
                    vertical: 8,
                  ),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      'Lihat Semua',
                      style: Theme.of(context).textTheme.labelMedium?.copyWith(
                        fontWeight: FontWeight.w700,
                        color: AppTheme.primary,
                      ),
                    ),
                    const SizedBox(width: 4),
                    const Icon(
                      Icons.arrow_forward,
                      size: 16,
                      color: AppTheme.primary,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),

        SizedBox(height: sectionGap * 0.6),

        // Product Grid
        if (products.isEmpty)
          _buildEmptyProductState()
        else
          _buildProductGrid(products),
      ],
    );
  }

  Widget _buildProductGrid(List<Product> products) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final columns = ResponsiveHelper.gridColumns(context);
    final spacing = ResponsiveHelper.gridSpacing(context);

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
      child: GridView.builder(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: columns,
          childAspectRatio: 0.68,
          crossAxisSpacing: spacing,
          mainAxisSpacing: spacing,
        ),
        itemCount: products.length,
        itemBuilder: (context, index) {
          return FadeInUp(
            delay: Duration(milliseconds: index * 50),
            child: ProductCard(product: products[index]),
          );
        },
      ),
    );
  }

  Widget _buildEmptyProductState() {
    return Container(
      margin: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context),
      ),
      padding: const EdgeInsets.all(32),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        borderRadius: AppTheme.radius22,
        border: Border.all(color: AppTheme.outlineLight),
      ),
      child: Column(
        children: [
          Icon(Icons.inventory_2_outlined, size: 48, color: AppTheme.muted),
          const SizedBox(height: 16),
          Text(
            'Belum ada produk',
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(color: AppTheme.onSurfaceVariant),
          ),
        ],
      ),
    );
  }

  Widget _buildCollectionsSection(List<Collection> collections) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final sectionGap = ResponsiveHelper.sectionGap(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Koleksi',
                style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                'Jelajahi koleksi pilihan kami',
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: AppTheme.onSurfaceVariant,
                ),
              ),
            ],
          ),
        ),

        SizedBox(height: sectionGap * 0.6),

        SizedBox(
          height: 180,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
            itemCount: collections.length,
            separatorBuilder: (_, index) => const SizedBox(width: 16),
            itemBuilder: (context, index) {
              final collection = collections[index];
              return FadeInUp(
                delay: Duration(milliseconds: index * 80),
                child: _buildCollectionCard(collection),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildCollectionCard(Collection collection) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: () => context.push('/shop/${collection.handle}'),
        borderRadius: AppTheme.radius22,
        child: Container(
          width: 160,
          decoration: BoxDecoration(
            borderRadius: AppTheme.radius22,
            boxShadow: AppTheme.shadowSoft,
          ),
          child: ClipRRect(
            borderRadius: AppTheme.radius22,
            child: Stack(
              fit: StackFit.expand,
              children: [
                // Image placeholder (Collection doesn't have imageUrl)
                Container(
                  decoration: BoxDecoration(
                    color: AppTheme.primary.withValues(alpha: 0.1),
                    borderRadius: AppTheme.radius22,
                  ),
                  child: Center(
                    child: Icon(
                      Icons.folder_outlined,
                      size: 48,
                      color: AppTheme.primary.withValues(alpha: 0.3),
                    ),
                  ),
                ),
                // Gradient overlay
                Container(
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      begin: Alignment.topCenter,
                      end: Alignment.bottomCenter,
                      colors: [
                        Colors.transparent,
                        Colors.black.withValues(alpha: 0.7),
                      ],
                    ),
                  ),
                ),
                // Title
                Positioned(
                  bottom: 16,
                  left: 16,
                  right: 16,
                  child: Text(
                    collection.title,
                    style: Theme.of(context).textTheme.titleSmall?.copyWith(
                      color: Colors.white,
                      fontWeight: FontWeight.w700,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildRecommendationsSection() {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final authProvider = context.watch<AuthProvider>();

    // Only show if user is authenticated
    if (!authProvider.isAuthenticated) {
      return const SizedBox.shrink();
    }

    return Container(
      margin: EdgeInsets.symmetric(horizontal: horizontalPadding),
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            AppTheme.primary.withValues(alpha: 0.05),
            AppTheme.accent.withValues(alpha: 0.05),
          ],
        ),
        borderRadius: AppTheme.radius22,
        border: Border.all(color: AppTheme.primary.withValues(alpha: 0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: AppTheme.primary.withValues(alpha: 0.1),
                  borderRadius: AppTheme.radius12,
                ),
                child: const Icon(
                  Icons.auto_awesome,
                  color: AppTheme.primary,
                  size: 20,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Rekomendasi Untuk Anda',
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    Text(
                      'Berdasarkan riwayat belanja Anda',
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        color: AppTheme.onSurfaceVariant,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          // Recommendations would be fetched here
          SizedBox(
            height: 280,
            child: FutureBuilder<List<Product>>(
              future: context.read<ProductProvider>().getRecommendations(),
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      color: AppTheme.primary,
                    ),
                  );
                }

                if (snapshot.hasError ||
                    !snapshot.hasData ||
                    snapshot.data!.isEmpty) {
                  return Center(
                    child: Text(
                      'Belum ada rekomendasi',
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: AppTheme.onSurfaceVariant,
                      ),
                    ),
                  );
                }

                final recommendations = snapshot.data!;
                return ListView.separated(
                  scrollDirection: Axis.horizontal,
                  itemCount: recommendations.length,
                  separatorBuilder: (_, index) => const SizedBox(width: 12),
                  itemBuilder: (context, index) {
                    return SizedBox(
                      width: 160,
                      child: ProductCard(product: recommendations[index]),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMaterialsSection(List materials) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
          child: Text(
            'Kualitas Material',
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w800),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 120,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
            itemCount: materials.length,
            separatorBuilder: (_, index) => const SizedBox(width: 12),
            itemBuilder: (context, index) {
              final material = materials[index];
              return Container(
                width: 200,
                decoration: BoxDecoration(
                  borderRadius: AppTheme.radius16,
                  image: DecorationImage(
                    image: CachedNetworkImageProvider(
                      StorageUrl.format(material.image),
                    ),
                    fit: BoxFit.cover,
                  ),
                ),
                child: Container(
                  decoration: BoxDecoration(
                    borderRadius: AppTheme.radius16,
                    gradient: LinearGradient(
                      begin: Alignment.bottomCenter,
                      end: Alignment.topCenter,
                      colors: [
                        Colors.black.withValues(alpha: 0.7),
                        Colors.transparent,
                      ],
                    ),
                  ),
                  padding: const EdgeInsets.all(16),
                  alignment: Alignment.bottomLeft,
                  child: Text(
                    material.name,
                    style: Theme.of(context).textTheme.titleSmall?.copyWith(
                      color: Colors.white,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildTestimonialsSection(List testimonials) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Container(
      color: AppTheme.surfaceContainerLow,
      padding: EdgeInsets.symmetric(
        vertical: 48,
        horizontal: horizontalPadding,
      ),
      child: Column(
        children: [
          Text(
            'Apa Kata Mereka',
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w800),
          ),
          const SizedBox(height: 8),
          Text(
            'Testimoni dari pelanggan kami',
            style: Theme.of(
              context,
            ).textTheme.bodyMedium?.copyWith(color: AppTheme.onSurfaceVariant),
          ),
          const SizedBox(height: 32),
          SizedBox(
            height: 200,
            child: ListView.separated(
              scrollDirection: Axis.horizontal,
              itemCount: testimonials.length,
              separatorBuilder: (_, index) => const SizedBox(width: 16),
              itemBuilder: (context, index) {
                final testimonial = testimonials[index];
                return Container(
                  width: 300,
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    color: AppTheme.surface,
                    borderRadius: AppTheme.radius22,
                    boxShadow: AppTheme.shadowSoft,
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Container(
                            width: 44,
                            height: 44,
                            decoration: BoxDecoration(
                              color: AppTheme.primary.withValues(alpha: 0.1),
                              shape: BoxShape.circle,
                            ),
                            child: Center(
                              child: Text(
                                testimonial.name[0].toUpperCase(),
                                style: Theme.of(context).textTheme.titleMedium
                                    ?.copyWith(
                                      color: AppTheme.primary,
                                      fontWeight: FontWeight.w700,
                                    ),
                              ),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  testimonial.name,
                                  style: Theme.of(context).textTheme.titleSmall
                                      ?.copyWith(fontWeight: FontWeight.w700),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                Row(
                                  children: List.generate(
                                    5,
                                    (i) => Icon(
                                      i < testimonial.rating
                                          ? Icons.star
                                          : Icons.star_border,
                                      size: 14,
                                      color: AppTheme.accent,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Expanded(
                        child: Text(
                          testimonial.content,
                          style: Theme.of(context).textTheme.bodyMedium
                              ?.copyWith(
                                color: AppTheme.onSurfaceVariant,
                                height: 1.5,
                              ),
                          maxLines: 3,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

class _QuickLink {
  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final Color color;

  _QuickLink({
    required this.icon,
    required this.label,
    required this.onTap,
    required this.color,
  });
}
