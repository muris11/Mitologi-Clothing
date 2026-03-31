import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import '../lib/config/theme.dart';

void main() {
  group('AppTheme Design Tokens (Digital Atelier)', () {
    test('Primary color is the branding color', () {
      expect(AppTheme.primary, equals(const Color(0xFF725A39)));
    });

    test('Surface colors adhere to tonal hierarchy', () {
      expect(AppTheme.surfaceContainerLowest, equals(const Color(0xFFFFFFFF)));
      expect(AppTheme.surfaceContainerLow, equals(const Color(0xFFF4F3F1)));
      expect(AppTheme.surface, equals(const Color(0xFFFAF9F6)));
      expect(AppTheme.surfaceContainer, equals(const Color(0xFFEFEEEB)));
      expect(AppTheme.surfaceContainerHigh, equals(const Color(0xFFE9E8E5)));
      expect(AppTheme.surfaceContainerHighest, equals(const Color(0xFFE3E2E0)));
    });

    test('Typography base properties', () {
      TestWidgetsFlutterBinding.ensureInitialized();
      final theme = AppTheme.lightTheme;
      // Just check if we have a valid theme generated without crashing
      expect(theme.textTheme.displayLarge?.fontSize, equals(56.0));
    });
  });
}
