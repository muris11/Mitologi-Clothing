import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../../config/theme.dart';
import '../../../utils/responsive_helper.dart';

class HomeQuickLinks extends StatelessWidget {
  const HomeQuickLinks({super.key});

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Jalur Cepat',
                style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                'Akses promo, ukuran, produk terlaris, dan cerita brand tanpa banyak langkah.',
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: AppTheme.onSurfaceVariant,
                ),
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        _buildQuickLinksList(context),
      ],
    );
  }

  Widget _buildQuickLinksList(BuildContext context) {
    final spacing = ResponsiveHelper.gridSpacing(context);

    final quickLinks = [
      _QuickLinkData(
        icon: Icons.local_offer_outlined,
        label: 'Promo',
        onTap: () => context.push('/shop/promo'),
        color: AppTheme.primary,
      ),
      _QuickLinkData(
        icon: Icons.straighten,
        label: 'Panduan Ukuran',
        onTap: () => context.push('/shop/panduan-ukuran'),
        color: AppTheme.primary,
      ),
      _QuickLinkData(
        icon: Icons.trending_up,
        label: 'Populer',
        onTap: () => context.push('/shop?sort=best-selling'),
        color: AppTheme.primary,
      ),
      _QuickLinkData(
        icon: Icons.info_outline,
        label: 'Tentang Kami',
        onTap: () => context.push('/shop/tentang-kami'),
        color: AppTheme.onSurfaceVariant,
      ),
    ];

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context),
      ),
      child: Row(
        children: quickLinks.asMap().entries.map((entry) {
          final link = entry.value;
          final isFirst = entry.key == 0;

          return Padding(
            padding: EdgeInsets.only(left: isFirst ? 0 : spacing),
            child: _QuickLinkChip(link: link),
          );
        }).toList(),
      ),
    );
  }
}

class _QuickLinkData {
  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final Color color;

  _QuickLinkData({
    required this.icon,
    required this.label,
    required this.onTap,
    required this.color,
  });
}

class _QuickLinkChip extends StatelessWidget {
  final _QuickLinkData link;

  const _QuickLinkChip({required this.link});

  @override
  Widget build(BuildContext context) {
    return Semantics(
      label: 'Jalur cepat ke ${link.label}',
      button: true,
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: link.onTap,
          borderRadius: AppTheme.radius16,
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            decoration: BoxDecoration(
              color: AppTheme.surface,
              borderRadius: AppTheme.radius16,
              border: Border.all(color: AppTheme.outlineLight),
              boxShadow: AppTheme.shadowSoft,
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: link.color.withValues(alpha: 0.1),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(link.icon, color: link.color, size: 24),
                ),
                const SizedBox(height: 8),
                Text(
                  link.label,
                  style: Theme.of(context).textTheme.labelSmall?.copyWith(
                    fontWeight: FontWeight.w600,
                    color: AppTheme.onSurface,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
