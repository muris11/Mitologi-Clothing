import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';

/// Order Detail Skeleton - Matches Next.js order detail loading
class OrderDetailSkeleton extends StatelessWidget {
  const OrderDetailSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Status banner
            Container(
              width: double.infinity,
              height: 80,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius16,
              ),
            ),
            const SizedBox(height: 16),
            // Address card
            Container(
              width: double.infinity,
              height: 120,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius16,
              ),
            ),
            const SizedBox(height: 16),
            // Items card
            Container(
              width: double.infinity,
              height: 200,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius16,
              ),
            ),
            const SizedBox(height: 16),
            // Payment summary
            Container(
              width: double.infinity,
              height: 150,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius16,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
