import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Product card skeleton - Matches Next.js loading state
/// Used in: Shop grid, home sections, collections
class ProductCardSkeleton extends StatelessWidget {
  const ProductCardSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: Container(
        decoration: BoxDecoration(
          color: AppTheme.surface,
          borderRadius: AppTheme.radius22,
          border: Border.all(color: AppTheme.outlineLight),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image placeholder
            Expanded(
              flex: 5,
              child: ClipRRect(
                borderRadius: const BorderRadius.only(
                  topLeft: Radius.circular(AppTheme.radiusProductCard),
                  topRight: Radius.circular(AppTheme.radiusProductCard),
                ),
                child: Container(
                  color: AppTheme.slate200,
                  child: const Center(
                    child: Icon(
                      Icons.image_outlined,
                      color: AppTheme.slate300,
                      size: 40,
                    ),
                  ),
                ),
              ),
            ),
            // Content placeholder
            Expanded(
              flex: 3,
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Category badge placeholder
                    Container(
                      width: 60,
                      height: 16,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    const SizedBox(height: 8),
                    // Title placeholder
                    Container(
                      width: double.infinity,
                      height: 14,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    const SizedBox(height: 4),
                    // Title second line
                    Container(
                      width: MediaQuery.of(context).size.width * 0.3,
                      height: 14,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    const SizedBox(height: 8),
                    // Price placeholder
                    Container(
                      width: 80,
                      height: 18,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

/// Grid of product card skeletons
class ProductGridSkeleton extends StatelessWidget {
  final int itemCount;
  final EdgeInsets? padding;

  const ProductGridSkeleton({super.key, this.itemCount = 6, this.padding});

  @override
  Widget build(BuildContext context) {
    final columns = ResponsiveHelper.gridColumns(context);
    final spacing = ResponsiveHelper.gridSpacing(context);

    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      padding:
          padding ??
          EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: columns,
        childAspectRatio: 0.68,
        crossAxisSpacing: spacing,
        mainAxisSpacing: spacing,
      ),
      itemCount: itemCount,
      itemBuilder: (context, index) => const ProductCardSkeleton(),
    );
  }
}

/// Horizontal list of product card skeletons
class ProductListSkeleton extends StatelessWidget {
  final int itemCount;
  final double? cardWidth;

  const ProductListSkeleton({super.key, this.itemCount = 4, this.cardWidth});

  @override
  Widget build(BuildContext context) {
    final width = cardWidth ?? 180.0;
    final spacing = ResponsiveHelper.gridSpacing(context);

    return SizedBox(
      height: 280,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: EdgeInsets.symmetric(
          horizontal: ResponsiveHelper.horizontalPadding(context),
        ),
        itemCount: itemCount,
        separatorBuilder: (_, __) => SizedBox(width: spacing),
        itemBuilder: (context, index) =>
            SizedBox(width: width, child: const ProductCardSkeleton()),
      ),
    );
  }
}
