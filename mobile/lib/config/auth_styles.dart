import 'package:flutter/material.dart';
import 'theme.dart';

class AuthStyles {
  const AuthStyles._();

  // Spacing
  static const double pagePadding = 24.0;
  static const double cardPadding = 24.0;
  static const double fieldSpacing = 20.0;
  static const double buttonSpacing = 24.0;
  static const double headerBottomSpacing = 32.0;

  // Border Radius
  static const double cardRadius = 24.0;
  static const double inputRadius = 12.0;
  static const double buttonRadius = 12.0;
  static const double logoRadius = 12.0;

  // Typography Sizes
  static const double sectionLabelSize = 10.0;
  static const double titleSize = 24.0;
  static const double subtitleSize = 14.0;
  static const double labelSize = 12.0;
  static const double inputSize = 14.0;
  static const double buttonSize = 14.0;

  // Heights
  static const double inputHeight = 52.0;
  static const double buttonHeight = 52.0;

  // Letter Spacing
  static const double sectionLabelSpacing = 0.28;
  static const double labelSpacing = 0.16;
  static const double logoSpacing = 0.16;

  // Colors - referencing AppTheme
  static Color get sectionLabelColor => AppTheme.accent;
  static Color get titleColor => AppTheme.primary;
  static Color get subtitleColor => AppTheme.onSurfaceVariant;
  static Color get labelColor => AppTheme.onSurfaceVariant;
  static Color get inputBgColor => AppTheme.cream;
  static Color get inputBorderColor => AppTheme.muted;
  static Color get inputFocusBorderColor => AppTheme.primary;

  // Text Styles
  static TextStyle get sectionLabelStyle => TextStyle(
    fontSize: sectionLabelSize,
    fontWeight: FontWeight.w700,
    letterSpacing: sectionLabelSpacing,
    color: sectionLabelColor,
  );

  static TextStyle get titleStyle => const TextStyle(
    fontSize: titleSize,
    fontWeight: FontWeight.w600,
    letterSpacing: -0.5,
    height: 1.1,
    color: AppTheme.primary,
  );

  static TextStyle get subtitleStyle =>
      TextStyle(fontSize: subtitleSize, color: subtitleColor, height: 1.5);

  static TextStyle get labelStyle => TextStyle(
    fontSize: labelSize,
    fontWeight: FontWeight.w600,
    letterSpacing: labelSpacing,
    color: labelColor,
  );

  static TextStyle get inputStyle =>
      const TextStyle(fontSize: inputSize, color: AppTheme.onSurface);

  static TextStyle get buttonStyle => const TextStyle(
    fontSize: buttonSize,
    fontWeight: FontWeight.w700,
    color: Colors.white,
  );

  // Decorations
  static InputDecoration inputDecoration({
    required String labelText,
    IconData? prefixIcon,
    Widget? suffixIcon,
  }) {
    return InputDecoration(
      labelText: labelText,
      labelStyle: labelStyle,
      floatingLabelStyle: labelStyle.copyWith(color: AppTheme.primary),
      filled: true,
      fillColor: inputBgColor,
      prefixIcon: prefixIcon != null
          ? Icon(prefixIcon, size: 20, color: AppTheme.onSurfaceVariant)
          : null,
      suffixIcon: suffixIcon,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(inputRadius),
        borderSide: BorderSide(color: inputBorderColor),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(inputRadius),
        borderSide: BorderSide(color: inputBorderColor),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(inputRadius),
        borderSide: BorderSide(color: inputFocusBorderColor, width: 1.5),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(inputRadius),
        borderSide: const BorderSide(color: AppTheme.error),
      ),
      focusedErrorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(inputRadius),
        borderSide: const BorderSide(color: AppTheme.error, width: 1.5),
      ),
    );
  }

  static BoxDecoration get cardDecoration => BoxDecoration(
    color: AppTheme.sectionBackground,
    borderRadius: AppTheme.radius24,
    border: Border.all(color: AppTheme.outlineLight, width: 1),
    boxShadow: AppTheme.shadowSoft,
  );

  static BoxDecoration get logoDecoration => BoxDecoration(
    color: AppTheme.pageBackground,
    borderRadius: BorderRadius.circular(AuthStyles.logoRadius),
    border: Border.all(color: AppTheme.outline, width: 1),
  );
}
