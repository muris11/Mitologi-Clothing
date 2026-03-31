import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/theme.dart';
import '../../providers/cart_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../utils/responsive_helper.dart';
import 'animated_nav_icon.dart';

/// Wrapper untuk StatefulNavigationShell dengan bottom navigation bar
/// Digunakan oleh GoRouter StatefulShellRoute.indexedStack
class ScaffoldWithNavBar extends StatelessWidget {
  const ScaffoldWithNavBar({Key? key, required this.navigationShell})
    : super(key: key ?? const ValueKey('ScaffoldWithNavBar'));

  final StatefulNavigationShell navigationShell;

  @override
  Widget build(BuildContext context) {
    return AppBottomNav(navigationShell: navigationShell);
  }
}

/// Widget utama untuk bottom navigation dengan StatefulNavigationShell
class AppBottomNav extends StatelessWidget {
  const AppBottomNav({Key? key, required this.navigationShell})
    : super(key: key ?? const ValueKey('AppBottomNav'));

  final StatefulNavigationShell navigationShell;

  void _onTap(BuildContext context, int index) {
    HapticFeedback.lightImpact();
    navigationShell.goBranch(
      index,
      initialLocation: index == navigationShell.currentIndex,
    );
  }

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.responsiveValue<double>(
      context,
      phone: 12,
      tablet: 20,
      desktop: 32,
    );
    final verticalPadding = ResponsiveHelper.responsiveValue<double>(
      context,
      phone: 10,
      tablet: 14,
      desktop: 16,
    );

    return Scaffold(
      body: navigationShell,
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          boxShadow: [
            BoxShadow(
              color: AppTheme.primary.withValues(alpha: 0.06),
              blurRadius: 24,
              offset: const Offset(0, -4),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: const BorderRadius.vertical(top: Radius.circular(19)),
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 16, sigmaY: 16),
            child: Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Colors.white.withValues(alpha: 0.97),
                    Colors.white.withValues(alpha: 0.94),
                  ],
                ),
                border: Border(
                  top: BorderSide(
                    color: AppTheme.slate200.withValues(alpha: 0.8),
                    width: 1,
                  ),
                ),
              ),
              child: SafeArea(
                child: Padding(
                  padding: EdgeInsets.symmetric(
                    horizontal: horizontalPadding,
                    vertical: verticalPadding,
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      _buildNavItem(
                        context: context,
                        index: 0,
                        inactiveIcon: Icons.home_outlined,
                        activeIcon: Icons.home,
                        label: 'Beranda',
                      ),
                      _buildNavItem(
                        context: context,
                        index: 1,
                        inactiveIcon: Icons.storefront_outlined,
                        activeIcon: Icons.storefront,
                        label: 'Toko',
                      ),
                      _buildNavItem(
                        context: context,
                        index: 2,
                        inactiveIcon: Icons.favorite_border,
                        activeIcon: Icons.favorite,
                        label: 'Wishlist',
                        badgeCount: _getWishlistCount(context),
                      ),
                      _buildNavItem(
                        context: context,
                        index: 3,
                        inactiveIcon: Icons.shopping_bag_outlined,
                        activeIcon: Icons.shopping_bag,
                        label: 'Keranjang',
                        badgeCount: _getCartCount(context),
                      ),
                      _buildNavItem(
                        context: context,
                        index: 4,
                        inactiveIcon: Icons.person_outline,
                        activeIcon: Icons.person,
                        label: 'Akun',
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  int _getWishlistCount(BuildContext context) {
    try {
      return context.watch<WishlistProvider>().itemCount;
    } catch (_) {
      return 0;
    }
  }

  int _getCartCount(BuildContext context) {
    try {
      return context.watch<CartProvider>().itemCount;
    } catch (_) {
      return 0;
    }
  }

  Widget _buildNavItem({
    required BuildContext context,
    required int index,
    required IconData inactiveIcon,
    required IconData activeIcon,
    required String label,
    int badgeCount = 0,
  }) {
    final isSelected = navigationShell.currentIndex == index;

    return AnimatedNavIconStateful(
      inactiveIcon: inactiveIcon,
      activeIcon: activeIcon,
      label: label,
      isSelected: isSelected,
      badgeCount: badgeCount,
      onTap: () => _onTap(context, index),
    );
  }
}
