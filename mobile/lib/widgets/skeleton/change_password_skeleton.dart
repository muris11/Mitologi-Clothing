import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Change Password Skeleton - Matches Next.js password form loading
class ChangePasswordSkeleton extends StatelessWidget {
  const ChangePasswordSkeleton({super.key});

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
            // Password fields
            _buildFieldSkeleton(),
            const SizedBox(height: 24),
            _buildFieldSkeleton(),
            const SizedBox(height: 24),
            _buildFieldSkeleton(),
            const SizedBox(height: 48),

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
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Label
        Container(
          width: 140,
          height: 14,
          decoration: BoxDecoration(
            color: AppTheme.slate200,
            borderRadius: BorderRadius.circular(4),
          ),
        ),
        const SizedBox(height: 8),
        // Input field with suffix icon space
        Container(
          width: double.infinity,
          height: 56,
          decoration: BoxDecoration(
            color: AppTheme.slate200,
            borderRadius: AppTheme.radius16,
          ),
        ),
      ],
    );
  }
}
