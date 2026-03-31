import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

/// Utility untuk safe navigation dengan fallback
class NavigationHelper {
  /// Safe pop dengan fallback ke route tertentu
  ///
  /// Usage:
  /// ```dart
  /// NavigationHelper.safePop(context, fallbackRoute: '/');
  /// ```
  static void safePop(
    BuildContext context, {
    String? fallbackRoute,
    VoidCallback? onFallback,
  }) {
    // Cek apakah bisa pop
    if (Navigator.of(context).canPop()) {
      context.pop();
    } else if (fallbackRoute != null) {
      // Kalau tidak bisa pop, navigasi ke fallback route
      context.go(fallbackRoute);
    } else if (onFallback != null) {
      // Kalau ada callback, jalankan
      onFallback();
    }
  }

  /// Pop dengan default fallback ke home
  static void popOrGoHome(BuildContext context) {
    safePop(context, fallbackRoute: '/');
  }

  /// Pop dengan default fallback ke shop
  static void popOrGoShop(BuildContext context) {
    safePop(context, fallbackRoute: '/shop');
  }

  /// Check apakah bisa pop
  static bool canPop(BuildContext context) {
    return Navigator.of(context).canPop();
  }
}

/// Extension untuk BuildContext
extension NavigationContext on BuildContext {
  /// Safe pop dengan fallback
  void safePop({String? fallbackRoute, VoidCallback? onFallback}) {
    NavigationHelper.safePop(
      this,
      fallbackRoute: fallbackRoute,
      onFallback: onFallback,
    );
  }

  /// Pop atau ke home
  void popOrGoHome() {
    NavigationHelper.popOrGoHome(this);
  }

  /// Pop atau ke shop
  void popOrGoShop() {
    NavigationHelper.popOrGoShop(this);
  }

  /// Check bisa pop
  bool get canPop => NavigationHelper.canPop(this);
}
