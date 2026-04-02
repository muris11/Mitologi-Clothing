import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mobile/config/theme.dart';

void main() {
  group('AppTheme Design Tokens (Digital Atelier)', () {
    test('Primary color is the branding color', () {
      expect(AppTheme.primary, equals(const Color(0xFF0F172A)));
    });

    test('Surface colors adhere to tonal hierarchy', () {
      expect(AppTheme.surfaceContainerLowest, equals(const Color(0xFFFFFFFF)));
      expect(AppTheme.surfaceContainerLow, equals(const Color(0xFFF8FAFC)));
      expect(AppTheme.surfaceContainer, equals(const Color(0xFFF1F5F9)));
      expect(AppTheme.surfaceContainerHigh, equals(const Color(0xFFE2E8F0)));
      expect(AppTheme.surfaceContainerHighest, equals(const Color(0xFFCBD5E1)));
    });

    test('Typography base properties', () {
      TestWidgetsFlutterBinding.ensureInitialized();
      final theme = AppTheme.lightTheme;
      // Just check if we have a valid theme generated without crashing
      expect(theme.textTheme.displayLarge?.fontSize, equals(48.0));
    });
  });
}
