import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';

/// Base shimmer widget for consistent loading states
/// Matches Next.js skeleton styling
class Skeleton extends StatelessWidget {
  final double width;
  final double height;
  final double? borderRadius;
  final EdgeInsets? margin;
  final Color? baseColor;
  final Color? highlightColor;

  const Skeleton({
    super.key,
    this.width = double.infinity,
    this.height = 16,
    this.borderRadius,
    this.margin,
    this.baseColor,
    this.highlightColor,
  });

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: baseColor ?? AppTheme.slate200,
      highlightColor: highlightColor ?? AppTheme.slate100,
      child: Container(
        width: width,
        height: height,
        margin: margin,
        decoration: BoxDecoration(
          color: AppTheme.slate200,
          borderRadius: BorderRadius.circular(borderRadius ?? 8.0),
        ),
      ),
    );
  }
}

/// Shimmer card with Next.js style
class SkeletonCard extends StatelessWidget {
  final double? width;
  final double? height;
  final EdgeInsets? padding;
  final Widget child;
  final double? borderRadius;

  const SkeletonCard({
    super.key,
    this.width,
    this.height,
    this.padding,
    required this.child,
    this.borderRadius,
  });

  @override
  Widget build(BuildContext context) {
    final double effectiveRadius;
    if (borderRadius != null) {
      effectiveRadius = borderRadius!;
    } else {
      effectiveRadius = 22.0;
    }
    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: Container(
        width: width,
        height: height,
        padding: padding,
        decoration: BoxDecoration(
          color: AppTheme.surface,
          borderRadius: BorderRadius.circular(effectiveRadius),
          border: Border.all(color: AppTheme.outlineLight),
        ),
        child: child,
      ),
    );
  }
}
