import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Edit Profile Skeleton - Matches Next.js profile form loading
class EditProfileSkeleton extends StatelessWidget {
  const EditProfileSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);

    return Shimmer.fromColors(
      baseColor: AppTheme.muted,
      highlightColor: AppTheme.muted,
      child: SingleChildScrollView(
        padding: EdgeInsets.all(padding),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Avatar placeholder
            Center(
              child: Container(
                width: 96,
                height: 96,
                decoration: BoxDecoration(
                  color: AppTheme.muted,
                  shape: BoxShape.circle,
                ),
              ),
            ),
            const SizedBox(height: 32),

            // Form fields
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),
            const SizedBox(height: 16),
            _buildFieldSkeleton(),
            const SizedBox(height: 32),

            // Button skeleton
            Container(
              width: double.infinity,
              height: 56,
              decoration: BoxDecoration(
                color: AppTheme.muted,
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
          width: 100,
          height: 14,
          decoration: BoxDecoration(
            color: AppTheme.muted,
            borderRadius: BorderRadius.circular(4),
          ),
        ),
        const SizedBox(height: 8),
        // Input field
        Container(
          width: double.infinity,
          height: 56,
          decoration: BoxDecoration(
            color: AppTheme.muted,
            borderRadius: AppTheme.radius16,
          ),
        ),
      ],
    );
  }
}
