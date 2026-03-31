import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Home screen skeleton - Matches Next.js landing page loading
class HomeSkeleton extends StatelessWidget {
  const HomeSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Hero skeleton
            _HeroSkeleton(),

            const SizedBox(height: 32),

            // New Arrivals section
            _SectionSkeleton(),

            const SizedBox(height: 32),

            // Best Sellers section
            _SectionSkeleton(),

            const SizedBox(height: 32),

            // Categories section
            _CategoriesSkeleton(),

            const SizedBox(height: 32),

            // Testimonials section
            _TestimonialsSkeleton(),
          ],
        ),
      ),
    );
  }
}

class _HeroSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final height = ResponsiveHelper.heroHeight(context);

    return Container(
      height: height,
      color: AppTheme.slate200,
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 200,
              height: 32,
              decoration: BoxDecoration(
                color: AppTheme.slate300,
                borderRadius: BorderRadius.circular(4),
              ),
            ),
            const SizedBox(height: 16),
            Container(
              width: 280,
              height: 48,
              decoration: BoxDecoration(
                color: AppTheme.slate300,
                borderRadius: BorderRadius.circular(4),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _SectionSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);
    final spacing = ResponsiveHelper.gridSpacing(context);
    final columns = ResponsiveHelper.gridColumns(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Section header
        Padding(
          padding: EdgeInsets.symmetric(horizontal: padding),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                width: 140,
                height: 24,
                decoration: BoxDecoration(
                  color: AppTheme.slate200,
                  borderRadius: BorderRadius.circular(4),
                ),
              ),
              Container(
                width: 60,
                height: 20,
                decoration: BoxDecoration(
                  color: AppTheme.slate200,
                  borderRadius: BorderRadius.circular(4),
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 20),
        // Product grid
        Padding(
          padding: EdgeInsets.symmetric(horizontal: padding),
          child: GridView.count(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisCount: columns,
            childAspectRatio: 0.68,
            crossAxisSpacing: spacing,
            mainAxisSpacing: spacing,
            children: List.generate(
              columns * 2,
              (index) => _ProductCardSkeleton(),
            ),
          ),
        ),
      ],
    );
  }
}

class _CategoriesSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: padding),
          child: Container(
            width: 120,
            height: 24,
            decoration: BoxDecoration(
              color: AppTheme.slate200,
              borderRadius: BorderRadius.circular(4),
            ),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 100,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: padding),
            itemCount: 6,
            separatorBuilder: (_, __) => const SizedBox(width: 12),
            itemBuilder: (_, __) => Container(
              width: 100,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius16,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _TestimonialsSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);

    return Container(
      color: AppTheme.surfaceContainerLow,
      padding: EdgeInsets.symmetric(vertical: 48, horizontal: padding),
      child: Column(
        children: [
          Container(
            width: 200,
            height: 24,
            decoration: BoxDecoration(
              color: AppTheme.slate200,
              borderRadius: BorderRadius.circular(4),
            ),
          ),
          const SizedBox(height: 32),
          Container(
            height: 200,
            decoration: BoxDecoration(
              color: AppTheme.slate200,
              borderRadius: AppTheme.radius22,
            ),
          ),
        ],
      ),
    );
  }
}

class _ProductCardSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: AppTheme.surface,
        borderRadius: AppTheme.radius22,
        border: Border.all(color: AppTheme.outlineLight),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
            flex: 5,
            child: ClipRRect(
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(AppTheme.radiusProductCard),
                topRight: Radius.circular(AppTheme.radiusProductCard),
              ),
              child: Container(color: AppTheme.slate200),
            ),
          ),
          Expanded(
            flex: 3,
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: 60,
                    height: 16,
                    decoration: BoxDecoration(
                      color: AppTheme.slate200,
                      borderRadius: BorderRadius.circular(4),
                    ),
                  ),
                  const SizedBox(height: 8),
                  Container(
                    width: double.infinity,
                    height: 14,
                    decoration: BoxDecoration(
                      color: AppTheme.slate200,
                      borderRadius: BorderRadius.circular(4),
                    ),
                  ),
                  const SizedBox(height: 8),
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
    );
  }
}
