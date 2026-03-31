import 'package:flutter/material.dart';

class ResponsiveHelper {
  // Breakpoints
  static const double mobileWidth = 600;
  static const double tabletWidth = 900;
  static const double contentWidth = 1240;

  // Checks
  static bool isPhone(BuildContext context) =>
      MediaQuery.sizeOf(context).width < mobileWidth;
  static bool isTablet(BuildContext context) =>
      MediaQuery.sizeOf(context).width >= mobileWidth &&
      MediaQuery.sizeOf(context).width < tabletWidth;
  static bool isDesktop(BuildContext context) =>
      MediaQuery.sizeOf(context).width >= tabletWidth;
  static bool isLandscape(BuildContext context) =>
      MediaQuery.orientationOf(context) == Orientation.landscape;

  // Scale text and icons
  static double fontScale(BuildContext context) {
    if (isDesktop(context)) return 1.2;
    if (isTablet(context)) return 1.1;
    return 1.0;
  }

  static double scaleFactor(BuildContext context) {
    final width = MediaQuery.sizeOf(context).width;
    if (width >= tabletWidth) return 1.3;
    if (width >= mobileWidth) return 1.15;
    if (width < 360) return 0.85; // For very small screens
    return 1.0;
  }

  // Layout Constraints
  static double formMaxWidth(BuildContext context) => 560.0;

  static double heroHeight(BuildContext context) {
    return responsiveValue<double>(
      context,
      phone: 320.0,
      tablet: 480.0,
      desktop: 560.0,
    );
  }

  /// Helper to get a value based on the current screen size.
  /// If tablet/desktop values are not provided, it falls back gracefully to the next smaller value stringed out.
  static T responsiveValue<T>(
    BuildContext context, {
    required T phone,
    T? tablet,
    T? desktop,
  }) {
    final width = MediaQuery.sizeOf(context).width;
    if (width >= tabletWidth) return desktop ?? tablet ?? phone;
    if (width >= mobileWidth) return tablet ?? phone;
    return phone;
  }

  /// Standard horizontal padding based on screen size
  static double horizontalPadding(BuildContext context) {
    return responsiveValue<double>(
      context,
      phone: 16.0,
      tablet: 24.0,
      desktop: 32.0,
    );
  }

  static double pageTopPadding(BuildContext context) {
    return responsiveValue<double>(context, phone: 16, tablet: 20, desktop: 24);
  }

  static double sectionGap(BuildContext context) {
    return responsiveValue<double>(context, phone: 20, tablet: 24, desktop: 28);
  }

  static double gridSpacing(BuildContext context) {
    return responsiveValue<double>(context, phone: 14, tablet: 18, desktop: 20);
  }

  static double maxContentWidth(BuildContext context) {
    final width = MediaQuery.sizeOf(context).width;
    if (width <= contentWidth) return width;
    return contentWidth;
  }

  static EdgeInsets pagePadding(BuildContext context) {
    final horizontal = horizontalPadding(context);
    return EdgeInsets.fromLTRB(
      horizontal,
      pageTopPadding(context),
      horizontal,
      sectionGap(context),
    );
  }

  /// Number of columns for product grids based on width
  static int gridColumns(BuildContext context) {
    return responsiveValue(context, phone: 2, tablet: 3, desktop: 4);
  }

  static double productCardAspectRatio(BuildContext context) {
    return responsiveValue<double>(
      context,
      phone: 0.62,
      tablet: 0.68,
      desktop: 0.76,
    );
  }
}
