import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

/// Cart skeleton - Matches Next.js cart loading
class CartSkeleton extends StatelessWidget {
  final int itemCount;

  const CartSkeleton({super.key, this.itemCount = 3});

  @override
  Widget build(BuildContext context) {
    final padding = ResponsiveHelper.horizontalPadding(context);

    return Shimmer.fromColors(
      baseColor: AppTheme.slate200,
      highlightColor: AppTheme.slate100,
      child: Column(
        children: [
          // Cart items
          Expanded(
            child: ListView.separated(
              padding: EdgeInsets.all(padding),
              itemCount: itemCount,
              separatorBuilder: (_, __) => const SizedBox(height: 16),
              itemBuilder: (_, __) => _CartItemSkeleton(),
            ),
          ),
          // Summary panel
          Container(
            padding: EdgeInsets.all(padding),
            decoration: BoxDecoration(
              color: AppTheme.surface,
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(AppTheme.radiusBottomSheet),
                topRight: Radius.circular(AppTheme.radiusBottomSheet),
              ),
              boxShadow: AppTheme.shadowSoft,
            ),
            child: Column(
              children: [
                // Subtotal row
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      width: 80,
                      height: 16,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    Container(
                      width: 100,
                      height: 20,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                // Shipping row
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      width: 80,
                      height: 16,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    Container(
                      width: 100,
                      height: 20,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                // Divider
                Container(height: 1, color: AppTheme.slate200),
                const SizedBox(height: 16),
                // Total row
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      width: 60,
                      height: 20,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    Container(
                      width: 120,
                      height: 24,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 24),
                // Checkout button
                Container(
                  height: 56,
                  decoration: BoxDecoration(
                    color: AppTheme.slate200,
                    borderRadius: AppTheme.radius16,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _CartItemSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        borderRadius: AppTheme.radius22,
        border: Border.all(color: AppTheme.outlineLight),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image
          Container(
            width: 90,
            height: 90,
            decoration: BoxDecoration(
              color: AppTheme.slate200,
              borderRadius: AppTheme.radius12,
            ),
          ),
          const SizedBox(width: 16),
          // Content
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Title
                Container(
                  width: double.infinity,
                  height: 18,
                  decoration: BoxDecoration(
                    color: AppTheme.slate200,
                    borderRadius: BorderRadius.circular(4),
                  ),
                ),
                const SizedBox(height: 8),
                // Variant
                Container(
                  width: 100,
                  height: 14,
                  decoration: BoxDecoration(
                    color: AppTheme.slate200,
                    borderRadius: BorderRadius.circular(4),
                  ),
                ),
                const SizedBox(height: 16),
                // Price and quantity
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      width: 100,
                      height: 20,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    Container(
                      width: 100,
                      height: 36,
                      decoration: BoxDecoration(
                        color: AppTheme.slate200,
                        borderRadius: AppTheme.radius8,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
