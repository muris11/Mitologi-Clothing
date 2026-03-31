import 'package:flutter/material.dart';

/// Custom page transitions matching Next.js navigation
///
/// Smooth fade + slide transitions for premium navigation feel
class PageTransitions {
  /// Fade transition - Default page transition
  /// Duration: 300ms
  static Widget fadeTransition(
    BuildContext context,
    Animation<double> animation,
    Animation<double> secondaryAnimation,
    Widget child,
  ) {
    const curve = Curves.easeInOut;
    final tween = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).chain(CurveTween(curve: curve));

    return FadeTransition(opacity: animation.drive(tween), child: child);
  }

  /// Slide up transition - For modals, bottom sheets
  /// Duration: 400ms
  static Widget slideUpTransition(
    BuildContext context,
    Animation<double> animation,
    Animation<double> secondaryAnimation,
    Widget child,
  ) {
    const curve = Cubic(0.25, 1, 0.5, 1); // easeOutExpo
    final tween = Tween<Offset>(
      begin: const Offset(0, 0.3),
      end: Offset.zero,
    ).chain(CurveTween(curve: curve));

    final fadeTween = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).chain(CurveTween(curve: curve));

    return FadeTransition(
      opacity: animation.drive(fadeTween),
      child: SlideTransition(position: animation.drive(tween), child: child),
    );
  }

  /// Slide from right - For navigation push
  /// Duration: 300ms
  static Widget slideRightTransition(
    BuildContext context,
    Animation<double> animation,
    Animation<double> secondaryAnimation,
    Widget child,
  ) {
    const curve = Curves.easeInOut;
    final tween = Tween<Offset>(
      begin: const Offset(0.1, 0),
      end: Offset.zero,
    ).chain(CurveTween(curve: curve));

    final fadeTween = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).chain(CurveTween(curve: curve));

    return FadeTransition(
      opacity: animation.drive(fadeTween),
      child: SlideTransition(position: animation.drive(tween), child: child),
    );
  }

  /// Scale transition - For dialogs, important content
  /// Duration: 400ms
  static Widget scaleTransition(
    BuildContext context,
    Animation<double> animation,
    Animation<double> secondaryAnimation,
    Widget child,
  ) {
    const curve = Cubic(0.25, 1, 0.5, 1);
    final scaleTween = Tween<double>(
      begin: 0.9,
      end: 1.0,
    ).chain(CurveTween(curve: curve));
    final fadeTween = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).chain(CurveTween(curve: curve));

    return FadeTransition(
      opacity: animation.drive(fadeTween),
      child: ScaleTransition(scale: animation.drive(scaleTween), child: child),
    );
  }

  /// Shared axis transition - For related content
  /// Duration: 300ms
  static Widget sharedAxisTransition(
    BuildContext context,
    Animation<double> animation,
    Animation<double> secondaryAnimation,
    Widget child,
  ) {
    const curve = Curves.easeInOut;
    final tween = Tween<Offset>(
      begin: const Offset(0, 0.05),
      end: Offset.zero,
    ).chain(CurveTween(curve: curve));

    final fadeTween = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).chain(CurveTween(curve: curve));

    return FadeTransition(
      opacity: animation.drive(fadeTween),
      child: SlideTransition(position: animation.drive(tween), child: child),
    );
  }
}

/// Custom page route with transitions
class CustomPageRoute<T> extends PageRouteBuilder<T> {
  final Widget child;
  final RouteTransitionsBuilder transitionBuilder;
  final Duration duration;

  CustomPageRoute({
    required this.child,
    this.transitionBuilder = PageTransitions.fadeTransition,
    this.duration = const Duration(milliseconds: 300),
    super.settings,
  }) : super(
         pageBuilder: (context, animation, secondaryAnimation) => child,
         transitionsBuilder: transitionBuilder,
         transitionDuration: duration,
         reverseTransitionDuration: duration,
       );
}
