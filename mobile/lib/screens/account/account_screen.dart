import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../providers/auth_provider.dart';
import '../../providers/order_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/animations/shimmer_button.dart';
import '../../widgets/account/account_header_card.dart';
import '../../widgets/account/account_quick_actions.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class AccountScreen extends StatefulWidget {
  const AccountScreen({super.key});

  @override
  State<AccountScreen> createState() => _AccountScreenState();
}

class _AccountScreenState extends State<AccountScreen> {
  @override
  void initState() {
    super.initState();
    // Fetch orders to update counts
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (context.read<AuthProvider>().isAuthenticated) {
        context.read<OrderProvider>().fetchOrders();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final user = authProvider.user;
    final orderProvider = context.watch<OrderProvider>();
    final wishlistProvider = context.watch<WishlistProvider>();

    return MitologiScaffold(
      title: 'Akun Saya',
      subtitle: 'Kelola profil, pesanan, wishlist, dan pengaturan akun Anda.',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: !authProvider.isAuthenticated
          ? _buildGuestView(context)
          : CustomScrollView(
              slivers: [
                // Header profile section with gradient
                SliverToBoxAdapter(
                  child: FadeInUp(
                    delay: const Duration(milliseconds: 0),
                    child: AccountHeaderCard(
                      user: user,
                      orderCount: orderProvider.orders.length,
                      wishlistCount: wishlistProvider.itemCount,
                      addressCount: user?.addresses?.length ?? 0,
                    ),
                  ),
                ),

                // Order Status Cards
                SliverToBoxAdapter(
                  child: Padding(
                    padding: EdgeInsets.fromLTRB(
                      ResponsiveHelper.horizontalPadding(context),
                      16,
                      ResponsiveHelper.horizontalPadding(context),
                      0,
                    ),
                    child: FadeInUp(
                      delay: const Duration(milliseconds: 100),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: AppTheme.surfaceContainerLowest,
                              borderRadius: AppTheme.radius16,
                              border: Border.all(color: AppTheme.outlineLight),
                            ),
                            child: Row(
                              children: [
                                Container(
                                  padding: const EdgeInsets.all(10),
                                  decoration: BoxDecoration(
                                    color: AppTheme.primary.withValues(
                                      alpha: 0.08,
                                    ),
                                    borderRadius: AppTheme.radius12,
                                  ),
                                  child: const Icon(
                                    Icons.inventory_2_outlined,
                                    color: AppTheme.primary,
                                  ),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        orderProvider.pendingCount > 0
                                            ? 'Ada ${orderProvider.pendingCount} pesanan yang menunggu tindak lanjut'
                                            : 'Semua pesanan Anda terlihat aman dan terpantau',
                                        style: const TextStyle(
                                          color: AppTheme.onSurface,
                                          fontWeight: FontWeight.w700,
                                          fontSize: 13,
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      const Text(
                                        'Gunakan shortcut di bawah untuk cek status, alamat, dan pengaturan akun.',
                                        style: TextStyle(
                                          color: AppTheme.onSurfaceVariant,
                                          fontSize: 12,
                                          height: 1.45,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(height: 16),
                          AccountQuickActions(
                            pendingCount: orderProvider.pendingCount,
                            packedCount: orderProvider.packedCount,
                            shippedCount: orderProvider.shippedCount,
                            onOrderHistory: () =>
                                context.push('/shop/account/orders'),
                            onEditProfile: () =>
                                context.push('/shop/account/edit-profile'),
                            onAddresses: () =>
                                context.push('/shop/account/addresses'),
                            onWishlist: () => context.go('/wishlist'),
                            onSecurity: () =>
                                context.push('/shop/account/change-password'),
                            onHelp: () => context.push('/chatbot'),
                            onFaq: () => context.push('/shop/faq'),
                            onPromo: () => context.push('/shop/promo'),
                            onPanduanUkuran: () =>
                                context.push('/shop/panduan-ukuran'),
                            onKebijakanPengembalian: () =>
                                context.push('/shop/kebijakan-pengembalian'),
                            onKebijakanPrivasi: () =>
                                context.push('/shop/kebijakan-privasi'),
                            onSyaratKetentuan: () =>
                                context.push('/shop/syarat-ketentuan'),
                            onTentangKami: () =>
                                context.push('/shop/tentang-kami'),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),

                // Logout Button
                SliverToBoxAdapter(
                  child: Padding(
                    padding: EdgeInsets.fromLTRB(
                      ResponsiveHelper.horizontalPadding(context),
                      16,
                      ResponsiveHelper.horizontalPadding(context),
                      32,
                    ),
                    child: FadeInUp(
                      delay: const Duration(milliseconds: 200),
                      child: SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.surfaceContainerLow,
                            foregroundColor: AppTheme.error,
                            elevation: 0,
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            shape: RoundedRectangleBorder(
                              borderRadius: AppTheme.radius16,
                            ),
                          ),
                          onPressed: () async {
                            await authProvider.logout();
                            if (context.mounted) {
                              context.read<OrderProvider>().clearOrderData();
                              context.go('/shop/login');
                            }
                          },
                          child: const Text(
                            'Keluar Akun',
                            style: TextStyle(fontWeight: FontWeight.w600),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
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
            FadeInUp(
              delay: const Duration(milliseconds: 0),
              child: Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppTheme.surfaceContainerLow,
                  borderRadius: AppTheme.radius22,
                ),
                child: const Icon(
                  Icons.person_outline,
                  size: 64,
                  color: AppTheme.primary,
                ),
              ),
            ),
            const SizedBox(height: 24),
            const FadeInUp(
              delay: Duration(milliseconds: 100),
              child: Text(
                'Belum Login',
                style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.w800,
                  color: AppTheme.primary,
                ),
              ),
            ),
            const SizedBox(height: 12),
            const FadeInUp(
              delay: Duration(milliseconds: 200),
              child: Text(
                'Masuk untuk melihat status pesanan, wishlist, alamat, dan riwayat akun Anda di satu tempat.',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: AppTheme.onSurfaceVariant,
                  fontSize: 14,
                  height: 1.5,
                ),
              ),
            ),
            const SizedBox(height: 32),
            FadeInUp(
              delay: const Duration(milliseconds: 300),
              child: SizedBox(
                width: double.infinity,
                height: 56,
                child: ShimmerButton(
                  onPressed: () => context.go('/shop/login'),
                  background: AppTheme.primary,
                  borderRadius: 16,
                  child: const Center(
                    child: Text(
                      'Login / Daftar',
                      style: TextStyle(
                        fontWeight: FontWeight.w700,
                        fontSize: 16,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 12),
            FadeInUp(
              delay: const Duration(milliseconds: 350),
              child: Text(
                'Proses login aman dan memudahkan Anda melanjutkan checkout kapan saja.',
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: AppTheme.onSurfaceVariant,
                  height: 1.5,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
