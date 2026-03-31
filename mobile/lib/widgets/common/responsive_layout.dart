import 'package:flutter/material.dart';
import '../../utils/responsive_helper.dart';

class ResponsiveLayout extends StatelessWidget {
  const ResponsiveLayout({
    super.key,
    required this.phone,
    this.tablet,
    this.desktop,
  });

  final Widget phone;
  final Widget? tablet;
  final Widget? desktop;

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        if (constraints.maxWidth >= ResponsiveHelper.tabletWidth) {
          return desktop ?? tablet ?? phone;
        }
        if (constraints.maxWidth >= ResponsiveHelper.mobileWidth) {
          return tablet ?? phone;
        }
        return phone;
      },
    );
  }
}
