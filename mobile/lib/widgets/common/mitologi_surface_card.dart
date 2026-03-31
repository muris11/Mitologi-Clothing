import 'package:flutter/material.dart';

import '../../config/theme.dart';

class MitologiSurfaceCard extends StatelessWidget {
  const MitologiSurfaceCard({
    super.key,
    required this.child,
    this.padding = const EdgeInsets.all(20),
    this.margin,
    this.radius,
    this.borderColor,
    this.backgroundColor,
  });

  final Widget child;
  final EdgeInsetsGeometry padding;
  final EdgeInsetsGeometry? margin;
  final BorderRadius? radius;
  final Color? borderColor;
  final Color? backgroundColor;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: margin,
      padding: padding,
      decoration: BoxDecoration(
        color: backgroundColor ?? AppTheme.surfaceContainerLowest,
        borderRadius: radius ?? AppTheme.radius19,
      ),
      child: child,
    );
  }
}
