import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Address Form Skeleton - Matches Next.js address form loading
class AddressFormSkeleton extends StatelessWidget {
  const AddressFormSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);

    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: SingleChildScrollView(
        padding: EdgeInsets.all(padding),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Section title
            Container(
              width: 120,
              height: 20,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: BorderRadius.circular(4),
              ),
            ),
            const SizedBox(height: 16),

            // Contact info fields (3)
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),

            const SizedBox(height: 24),

            // Section title
            Container(
              width: 120,
              height: 20,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: BorderRadius.circular(4),
              ),
            ),
            const SizedBox(height: 16),

            // Address detail fields (5)
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),

            const SizedBox(height: 24),

            // Switch tile skeleton
            Container(
              width: double.infinity,
              height: 56,
              decoration: BoxDecoration(
                color: AppTheme.slate200,
                borderRadius: AppTheme.radius16,
              ),
            ),

            const SizedBox(height: 32),

            // Button skeleton
            Container(
              width: double.infinity,
              height: 56,
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

  Widget _buildFieldSkeleton() {
    return Container(
      width: double.infinity,
      height: 56,
      decoration: BoxDecoration(
        color: AppTheme.slate200,
        borderRadius: AppTheme.radius16,
      ),
    );
  }
}
