import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../../config/theme.dart';
import '../../../utils/responsive_helper.dart';

class HomeSearchBar extends StatelessWidget {
  const HomeSearchBar({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      child: Semantics(
        label: 'Cari produk',
        button: true,
        child: Tooltip(
          message: 'Cari produk favorit Anda',
          child: GestureDetector(
            onTap: () => context.push('/shop'),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              decoration: BoxDecoration(
                color: AppTheme.surface,
                borderRadius: AppTheme.radius16,
                border: Border.all(color: AppTheme.outline),
                boxShadow: AppTheme.shadowSoft,
              ),
              child: Row(
                children: [
                  const Icon(
                    Icons.search,
                    color: AppTheme.onSurfaceVariant,
                    size: 22,
                  ),
                  const SizedBox(width: 12),
                  Text(
                    'Cari produk...',
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: AppTheme.onSurfaceMuted,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
