import 'package:flutter/material.dart';

import '../../config/theme.dart';

class MitologiSectionHeader extends StatelessWidget {
  const MitologiSectionHeader({
    super.key,
    required this.title,
    this.subtitle,
    this.eyebrow,
    this.action,
  });

  final String title;
  final String? subtitle;
  final String? eyebrow;
  final Widget? action;

  @override
  Widget build(BuildContext context) {
    final textTheme = Theme.of(context).textTheme;

    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              if (eyebrow != null) ...[
                Text(
                  eyebrow!,
                  style: textTheme.labelMedium?.copyWith(
                    color: AppTheme.primary,
                    letterSpacing: 1.2,
                  ),
                ),
                const SizedBox(height: 6),
              ],
              Text(title, style: textTheme.titleLarge),
              if (subtitle != null) ...[
                const SizedBox(height: 6),
                Text(
                  subtitle!,
                  style: textTheme.bodyMedium?.copyWith(
                    color: AppTheme.onSurfaceVariant,
                    height: 1.45,
                  ),
                ),
              ],
            ],
          ),
        ),
        if (action != null) ...[const SizedBox(width: 16), action!],
      ],
    );
  }
}
