import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';
import 'mitologi_section_header.dart';
import 'mitologi_surface_card.dart';

class MitologiPageShell extends StatelessWidget {
  const MitologiPageShell({
    super.key,
    required this.title,
    required this.child,
    this.subtitle,
    this.eyebrow,
    this.action,
    this.maxWidth,
  });

  final String title;
  final String? subtitle;
  final String? eyebrow;
  final Widget child;
  final Widget? action;
  final double? maxWidth;

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Align(
      alignment: Alignment.topCenter,
      child: ConstrainedBox(
        constraints: BoxConstraints(
          maxWidth: maxWidth ?? AppTheme.maxContentWidth,
        ),
        child: Padding(
          padding: EdgeInsets.fromLTRB(
            horizontalPadding,
            12,
            horizontalPadding,
            24,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              MitologiSectionHeader(
                title: title,
                subtitle: subtitle,
                eyebrow: eyebrow,
                action: action,
              ),
              const SizedBox(height: 20),
              MitologiSurfaceCard(child: child),
            ],
          ),
        ),
      ),
    );
  }
}
