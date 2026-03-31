import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Collections Skeleton - Matches Next.js collection grid loading
class CollectionsSkeleton extends StatelessWidget {
  const CollectionsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);

    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: LayoutBuilder(
        builder: (context, constraints) {
          int crossAxisCount = 2;
          if (constraints.maxWidth > 900) {
            crossAxisCount = 4;
          } else if (constraints.maxWidth > 600) {
            crossAxisCount = 3;
          }

          return GridView.builder(
            padding: EdgeInsets.all(padding),
            gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: crossAxisCount,
              crossAxisSpacing: 16,
              mainAxisSpacing: 16,
              childAspectRatio: 0.8,
            ),
            itemCount: 6,
            itemBuilder: (_, __) => Container(
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius22,
              ),
            ),
          );
        },
      ),
    );
  }
}
