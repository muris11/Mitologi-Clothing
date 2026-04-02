import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import '../../config/theme.dart';
import '../../providers/auth_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../widgets/product/product_card.dart';
import '../../widgets/common/animated_empty_state.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/animations/shimmer_button.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../utils/responsive_helper.dart';

class WishlistScreen extends StatefulWidget {
  const WishlistScreen({super.key});

  @override
  State<WishlistScreen> createState() => _WishlistScreenState();
}

class _WishlistScreenState extends State<WishlistScreen> {
  @override
  void initState() {
    super.initState();
    // Refresh wishlist on screen load
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (context.read<AuthProvider>().isAuthenticated) {
        context.read<WishlistProvider>().fetchWishlist();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Wishlist',
      subtitle: 'Produk favorit yang ingin Anda simpan untuk dibeli nanti.',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: Consumer<AuthProvider>(
        builder: (context, authProvider, _) {
          if (!authProvider.isAuthenticated) {
            return _buildGuestView(context);
          }

          return Consumer<WishlistProvider>(
            builder: (context, provider, child) {
              if (provider.isLoading && provider.items.isEmpty) {
                return const WishlistSkeleton();
              }

              if (provider.error != null && provider.items.isEmpty) {
                return Center(
                  child: FadeInUp(
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
                        const SizedBox(height: 16),
                        Text(
                          'Gagal memuat wishlist: ${provider.error}',
                          textAlign: TextAlign.center,
                          style: Theme.of(context).textTheme.bodyMedium
                              ?.copyWith(color: AppTheme.slate700),
                        ),
                        const SizedBox(height: 16),
                        ElevatedButton.icon(
                          onPressed: () => provider.fetchWishlist(),
                          icon: const Icon(Icons.refresh),
                          label: const Text('Coba Lagi'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.primary,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: AppTheme.radius16,
                            ),
                            padding: const EdgeInsets.symmetric(
                              horizontal: 24,
                              vertical: 12,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              }

              if (provider.items.isEmpty) {
                return _buildEmptyState(context);
              }

              return RefreshIndicator(
                onRefresh: () => provider.fetchWishlist(),
                child: LayoutBuilder(
                  builder: (context, constraints) {
                    int crossAxisCount = 2;
                    double childAspectRatio = 0.55; // Match Shop ratio
                    if (constraints.maxWidth > 900) {
                      crossAxisCount = 4;
                      childAspectRatio = 0.65;
                    } else if (constraints.maxWidth > 600) {
                      crossAxisCount = 3;
                      childAspectRatio = 0.62;
                    }

                    return GridView.builder(
                      padding: EdgeInsets.all(
                        ResponsiveHelper.horizontalPadding(context),
                      ),
                      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: crossAxisCount,
                        childAspectRatio: childAspectRatio,
                        crossAxisSpacing: 16,
                        mainAxisSpacing: 16,
                      ),
                      itemCount: provider.items.length,
                      itemBuilder: (context, index) {
                        final product = provider.items[index];
                        return BlurFade(
                          delay: Duration(milliseconds: 50 * (index % 10)),
                          child: ProductCard(product: product),
                        );
                      },
                    );
                  },
                ),
              );
            },
          );
        },
      ),
    );
  }

  Widget _buildGuestView(BuildContext context) {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(
          ResponsiveHelper.horizontalPadding(context) * 2,
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            BlurFade(
              delay: const Duration(milliseconds: 0),
              child: const Icon(
                Icons.favorite_border,
                size: 80,
                color: AppTheme.slate300,
              ),
            ),
            const SizedBox(height: 24),
            BlurFade(
              delay: const Duration(milliseconds: 100),
              child: Text(
                'Belum Login',
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: AppTheme.primary,
                ),
              ),
            ),
            const SizedBox(height: 8),
            BlurFade(
              delay: const Duration(milliseconds: 200),
              child: Text(
                'Silahkan login untuk melihat dan mengelola produk favorit Anda.',
                textAlign: TextAlign.center,
                style: Theme.of(
                  context,
                ).textTheme.bodyMedium?.copyWith(color: AppTheme.slate500),
              ),
            ),
            const SizedBox(height: 32),
            BlurFade(
              delay: const Duration(milliseconds: 300),
              child: SizedBox(
                width: double.infinity,
                height: 48,
                child: ShimmerButton(
                  onPressed: () => context.go('/shop/login'),
                  background: AppTheme.primary,
                  borderRadius: 16,
                  child: Center(
                    child: Text(
                      'Login / Daftar',
                      style: Theme.of(context).textTheme.labelLarge?.copyWith(
                        fontWeight: FontWeight.w600,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return AnimatedEmptyState(
      icon: Icons.favorite_border,
      title: 'Belum Ada Produk Favorit',
      subtitle: 'Simpan produk yang Anda suka di sini\nuntuk membelinya nanti.',
      buttonText: 'Cari Produk',
      onAction: () {
        context.go('/');
      },
    );
  }
}
