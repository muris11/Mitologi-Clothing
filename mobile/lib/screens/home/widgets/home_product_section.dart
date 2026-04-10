import 'package:flutter/material.dart';

import '../../../config/theme.dart';
import '../../../models/product.dart';
import '../../../utils/responsive_helper.dart';
import '../../../widgets/animations/animations.dart';
import '../../../widgets/product/product_card.dart';

class HomeProductSection extends StatelessWidget {
  final String title;
  final String subtitle;
  final List<Product> products;
  final VoidCallback onSeeAll;
  final String description;
  final IconData icon;
  final int staggerIndex;

  const HomeProductSection({
    super.key,
    required this.title,
    required this.subtitle,
    required this.products,
    required this.onSeeAll,
    required this.description,
    this.icon = Icons.local_shipping_outlined,
    this.staggerIndex = 0,
  });

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final sectionGap = ResponsiveHelper.sectionGap(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Header
        Padding(
          padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    subtitle,
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: AppTheme.onSurfaceVariant,
                    ),
                  ),
                ],
              ),
              _buildSeeAllButton(context),
            ],
          ),
        ),

        // Description Card
        Padding(
          padding: EdgeInsets.fromLTRB(horizontalPadding, 12, horizontalPadding, 0),
          child: Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: AppTheme.surfaceContainerLowest,
              borderRadius: AppTheme.radius16,
              border: Border.all(color: AppTheme.outlineLight),
            ),
            child: Row(
              children: [
                Icon(icon, size: 18, color: AppTheme.primary),
                const SizedBox(width: 10),
                Expanded(
                  child: Text(
                    description,
                    style: const TextStyle(
                      color: AppTheme.onSurfaceVariant,
                      fontSize: 12,
                      height: 1.45,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),

        SizedBox(height: sectionGap * 0.6),

        // Product Grid
        if (products.isEmpty)
          _buildEmptyState(context)
        else
          _buildProductGrid(context),
      ],
    );
  }

  Widget _buildSeeAllButton(BuildContext context) {
    return Semantics(
      label: 'Lihat semua produk $title',
      button: true,
      child: Tooltip(
        message: 'Buka daftar semua produk $title',
        child: TextButton(
          onPressed: onSeeAll,
          style: TextButton.styleFrom(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                'Lihat Semua',
                style: Theme.of(context).textTheme.labelMedium?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: AppTheme.primary,
                ),
              ),
              const SizedBox(width: 4),
              const Icon(Icons.arrow_forward, size: 16, color: AppTheme.primary),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProductGrid(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final columns = ResponsiveHelper.gridColumns(context);
    final spacing = ResponsiveHelper.gridSpacing(context);

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
      child: GridView.builder(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: columns,
          childAspectRatio: 0.68,
          crossAxisSpacing: spacing,
          mainAxisSpacing: spacing,
        ),
        itemCount: products.length,
        itemBuilder: (context, index) {
          return FadeInUp(
            delay: Duration(milliseconds: (staggerIndex * 100) + (index * 50)),
            child: ProductCard(product: products[index]),
          );
        },
      ),
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return Container(
      margin: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context),
      ),
      padding: const EdgeInsets.all(32),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        borderRadius: AppTheme.radius22,
        border: Border.all(color: AppTheme.outlineLight),
      ),
      child: Column(
        children: [
          const Icon(Icons.inventory_2_outlined, size: 48, color: AppTheme.muted),
          const SizedBox(height: 16),
          Text(
            'Belum ada produk',
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
                  color: AppTheme.onSurfaceVariant,
                ),
          ),
        ],
      ),
    );
  }
}
