import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/theme.dart';
import '../../providers/cart_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../utils/responsive_helper.dart';

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
      phone: 8,
      tablet: 20,
      desktop: 32,
    );
    final verticalPadding = ResponsiveHelper.responsiveValue<double>(
      context,
      phone: 8,
      tablet: 14,
      desktop: 16,
    );

    // Get screen width for responsive calculations
    final screenWidth = MediaQuery.of(context).size.width;
    final isSmallScreen = screenWidth < 360;

    return Scaffold(
      body: navigationShell,
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          boxShadow: [
            BoxShadow(
              color: AppTheme.primary.withValues(alpha: 0.08),
              blurRadius: 20,
              offset: const Offset(0, -4),
              spreadRadius: -2,
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 12, sigmaY: 12),
            child: Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Colors.white.withValues(alpha: 0.95),
                    Colors.white.withValues(alpha: 0.98),
                  ],
                ),
                border: Border(
                  top: BorderSide(
                    color: AppTheme.slate200.withValues(alpha: 0.6),
                    width: 1,
                  ),
                ),
              ),
              child: SafeArea(
                top: false,
                minimum: const EdgeInsets.only(bottom: 4),
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
                        activeIcon: Icons.home_rounded,
                        label: 'Beranda',
                        isSmallScreen: isSmallScreen,
                      ),
                      _buildNavItem(
                        context: context,
                        index: 1,
                        inactiveIcon: Icons.storefront_outlined,
                        activeIcon: Icons.storefront_rounded,
                        label: 'Toko',
                        isSmallScreen: isSmallScreen,
                      ),
                      _buildNavItem(
                        context: context,
                        index: 2,
                        inactiveIcon: Icons.favorite_outline,
                        activeIcon: Icons.favorite_rounded,
                        label: 'Wishlist',
                        badgeCount: _getWishlistCount(context),
                        isSmallScreen: isSmallScreen,
                      ),
                      _buildNavItem(
                        context: context,
                        index: 3,
                        inactiveIcon: Icons.shopping_bag_outlined,
                        activeIcon: Icons.shopping_bag_rounded,
                        label: 'Keranjang',
                        badgeCount: _getCartCount(context),
                        isSmallScreen: isSmallScreen,
                      ),
                      _buildNavItem(
                        context: context,
                        index: 4,
                        inactiveIcon: Icons.person_outline,
                        activeIcon: Icons.person_rounded,
                        label: 'Akun',
                        isSmallScreen: isSmallScreen,
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
    required bool isSmallScreen,
  }) {
    final isSelected = navigationShell.currentIndex == index;

    return Expanded(
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () => _onTap(context, index),
          borderRadius: BorderRadius.circular(12),
          splashColor: AppTheme.primary.withValues(alpha: 0.1),
          highlightColor: AppTheme.primary.withValues(alpha: 0.05),
          child: Container(
            padding: EdgeInsets.symmetric(
              horizontal: isSmallScreen ? 4 : 8,
              vertical: isSmallScreen ? 6 : 8,
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                // Icon with badge
                Container(
                  padding: EdgeInsets.all(isSmallScreen ? 6 : 8),
                  decoration: isSelected
                      ? BoxDecoration(
                          color: AppTheme.primary.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(10),
                        )
                      : null,
                  child: Stack(
                    clipBehavior: Clip.none,
                    children: [
                      Icon(
                        isSelected ? activeIcon : inactiveIcon,
                        size: isSmallScreen ? 22 : 24,
                        color: isSelected
                            ? AppTheme.primary
                            : AppTheme.slate500,
                      ),
                      if (badgeCount > 0)
                        Positioned(
                          right: -6,
                          top: -6,
                          child: Container(
                            padding: const EdgeInsets.all(2),
                            decoration: BoxDecoration(
                              color: AppTheme.accent,
                              shape: BoxShape.circle,
                              border: Border.all(
                                color: Colors.white,
                                width: 1.5,
                              ),
                              boxShadow: [
                                BoxShadow(
                                  color: AppTheme.accent.withValues(alpha: 0.3),
                                  blurRadius: 4,
                                  offset: const Offset(0, 1),
                                ),
                              ],
                            ),
                            constraints: BoxConstraints(
                              minWidth: isSmallScreen ? 16 : 18,
                              minHeight: isSmallScreen ? 16 : 18,
                            ),
                            child: Center(
                              child: Text(
                                badgeCount > 99 ? '99+' : badgeCount.toString(),
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: isSmallScreen ? 8 : 9,
                                  fontWeight: FontWeight.w800,
                                  height: 1,
                                ),
                              ),
                            ),
                          ),
                        ),
                    ],
                  ),
                ),
                const SizedBox(height: 2),
                // Label
                Text(
                  label,
                  style: TextStyle(
                    fontSize: isSmallScreen ? 10 : 11,
                    fontWeight: isSelected ? FontWeight.w700 : FontWeight.w500,
                    color: isSelected ? AppTheme.primary : AppTheme.slate500,
                    height: 1.2,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
