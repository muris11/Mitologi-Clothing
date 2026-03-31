import 'package:flutter/material.dart';
import '../../../config/theme.dart';

class HeroSection extends StatelessWidget {
  final String? title;
  final String? subtitle;

  const HeroSection({super.key, this.title, this.subtitle});

  @override
  Widget build(BuildContext context) {
    // Don't show if no data from API
    if ((title == null || title!.isEmpty) &&
        (subtitle == null || subtitle!.isEmpty)) {
      return const SizedBox.shrink();
    }

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 60, horizontal: 24),
      decoration: const BoxDecoration(color: AppTheme.primary),
      child: Column(
        children: [
          if (title != null && title!.isNotEmpty)
            Text(
              title!,
              style: const TextStyle(
                fontSize: 26,
                fontWeight: FontWeight.w800,
                color: Colors.white,
                letterSpacing: -0.5,
              ),
              textAlign: TextAlign.center,
            ),
          if (title != null &&
              title!.isNotEmpty &&
              subtitle != null &&
              subtitle!.isNotEmpty)
            const SizedBox(height: 12),
          if (subtitle != null && subtitle!.isNotEmpty)
            Text(
              subtitle!,
              style: TextStyle(
                fontSize: 14,
                color: Colors.white.withValues(alpha: 0.7),
                height: 1.5,
              ),
              textAlign: TextAlign.center,
            ),
        ],
      ),
    );
  }
}
