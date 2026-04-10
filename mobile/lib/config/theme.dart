import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

/// Mitologi Clothing Design System
/// Matching Next.js Web Implementation (Premium E-commerce)
///
/// Color System: OKLCH-based perceptually uniform colors
/// Typography: Plus Jakarta Sans (matching web)
/// Spacing: 8px-based scale with generous breathing room
/// Radius: Generous 22-32px for premium feel
/// Shadows: Multi-layer depth system

class AppTheme {
  // ===========================================================================
  // BRAND COLORS - Matching Next.js OKLCH Values
  // ===========================================================================

  /// Primary Navy - oklch(0.25 0.05 260)
  static const Color primary = Color(0xFF0F172A);

  /// Navy Light - oklch(0.32 0.06 260)
  static const Color primaryLight = Color(0xFF1E293B);

  /// Navy Dark
  static const Color primaryDark = Color(0xFF020617);

  /// Accent Gold - oklch(0.75 0.16 75)
  static const Color accent = Color(0xFFD4A853);

  /// Gold Light - oklch(0.80 0.14 75)
  static const Color accentLight = Color(0xFFE5C576);

  /// Gold Dark - oklch(0.65 0.16 75)
  static const Color accentDark = Color(0xFFB8934A);

  /// Cream/Off-white Background
  static const Color cream = Color(0xFFFAFAF8);

  /// Legacy aliases
  static const Color gold = accent;
  static const Color navy = primary;
  static const Color secondary = accent;

  // ===========================================================================
  // SEMANTIC ALIASES - For design consistency across screens
  // ===========================================================================

  /// Page-level background (cream for premium feel)
  static const Color pageBackground = cream;

  /// Section/card surface background
  static const Color sectionBackground = surfaceContainerLowest;

  /// Muted/subtle section background
  static const Color sectionMuted = muted;

  /// Standard content gap/spacing for page sections
  static const double contentGap = 24;

  // ===========================================================================
  // BACKGROUND COLORS
  // ===========================================================================

  /// Main page background - slate-50
  static const Color background = Color(0xFFF8FAFC);

  /// Card/elevated surface background
  static const Color surface = Color(0xFFFFFFFF);

  /// Muted/subtle background - slate-100
  static const Color muted = Color(0xFFF1F5F9);

  /// Surface hierarchy levels (Material 3 style)
  static const Color surfaceContainerLowest = Color(0xFFFFFFFF);
  static const Color surfaceContainerLow = Color(0xFFF8FAFC);
  static const Color surfaceContainer = Color(0xFFF1F5F9);
  static const Color surfaceContainerHigh = Color(0xFFE2E8F0);
  static const Color surfaceContainerHighest = Color(0xFFCBD5E1);

  // ===========================================================================
  // TEXT COLORS
  // ===========================================================================

  /// Primary text - slate-900
  static const Color onSurface = Color(0xFF0F172A);

  /// Secondary/muted text - slate-500
  static const Color onSurfaceVariant = Color(0xFF64748B);

  /// Tertiary text - slate-400
  static const Color onSurfaceMuted = Color(0xFF94A3B8);

  /// Text on primary color background
  static const Color onPrimary = Color(0xFFFFFFFF);

  /// Text on accent/gold background
  static const Color onAccent = Color(0xFF0F172A);

  // ===========================================================================
  // BORDER & OUTLINE
  // ===========================================================================

  /// Default border - slate-200
  static const Color outline = Color(0xFFE2E8F0);

  /// Light border - slate-100
  static const Color outlineLight = Color(0xFFF1F5F9);

  /// Subtle border - slate-50
  static const Color outlineSubtle = Color(0xFFF8FAFC);

  // ===========================================================================
  // SLATE SCALE (Complete)
  // ===========================================================================

  static const Color slate50 = Color(0xFFF8FAFC);
  static const Color slate100 = Color(0xFFF1F5F9);
  static const Color slate200 = Color(0xFFE2E8F0);
  static const Color slate300 = Color(0xFFCBD5E1);
  static const Color slate400 = Color(0xFF94A3B8);
  static const Color slate500 = Color(0xFF64748B);
  static const Color slate600 = Color(0xFF475569);
  static const Color slate700 = Color(0xFF334155);
  static const Color slate800 = Color(0xFF1E293B);
  static const Color slate900 = Color(0xFF0F172A);

  // ===========================================================================
  // SEMANTIC COLORS
  // ===========================================================================

  /// Success - emerald-500
  static const Color success = Color(0xFF10B981);
  static const Color successLight = Color(0xFFD1FAE5);

  /// Error - red-500
  static const Color error = Color(0xFFEF4444);
  static const Color errorLight = Color(0xFFFEE2E2);

  /// Warning - amber-500
  static const Color warning = Color(0xFFF59E0B);
  static const Color warningLight = Color(0xFFFEF3C7);

  /// Info - blue-500
  static const Color info = Color(0xFF3B82F6);
  static const Color infoLight = Color(0xFFDBEAFE);

  // ===========================================================================
  // WHATSAPP BRAND
  // ===========================================================================

  static const Color whatsappGreen = Color(0xFF25D366);
  static const Color whatsappGreenDark = Color(0xFF20BD5A);

  // ===========================================================================
  // BORDER RADIUS - Generous Premium Feel (Matching Next.js)
  // ===========================================================================

  /// Small elements: buttons, badges, chips
  static const double radiusSm = 6;

  /// Medium elements: inputs, small cards
  static const double radiusMd = 8;

  /// Large elements: cards, modals - 12px
  static const double radiusLg = 12;

  /// Extra large: major containers - 16px
  static const double radiusXl = 16;

  /// 2XL: hero sections, feature cards - 24px
  static const double radius2Xl = 24;

  /// Product cards (custom) - 22px
  static const double radiusProductCard = 22;

  /// Navbar shell - 28px
  static const double radiusNavbar = 28;

  /// Bottom sheets - 30px
  static const double radiusBottomSheet = 30;

  /// Navigation specific
  static const double navPillRadius = 20.0;
  static const double navIconRadius = 16.0;

  // Pre-computed BorderRadius getters
  static BorderRadius get radius6 => BorderRadius.circular(radiusSm);
  static BorderRadius get radius8 => BorderRadius.circular(radiusMd);
  static BorderRadius get radius12 => BorderRadius.circular(radiusLg);
  static BorderRadius get radius16 => BorderRadius.circular(radiusXl);
  static BorderRadius get radius22 => BorderRadius.circular(radiusProductCard);
  static BorderRadius get radius24 => BorderRadius.circular(radius2Xl);
  static BorderRadius get radius28 => BorderRadius.circular(radiusNavbar);
  static BorderRadius get radius30 => BorderRadius.circular(radiusBottomSheet);

  // Legacy aliases for backward compatibility
  static BorderRadius get radius11 => radius12;
  static BorderRadius get radius15 => radius16;
  static BorderRadius get radius19 => radius22;

  // ===========================================================================
  // SHADOW SYSTEM - Multi-layer Depth (Matching Next.js)
  // ===========================================================================

  /// Soft shadow - Default cards
  /// Equivalent to: 0 4px 20px -2px rgba(20, 25, 60, 0.05)
  static List<BoxShadow> get shadowSoft => [
    BoxShadow(
      color: const Color(0xFF14193C).withValues(alpha: 0.05),
      blurRadius: 20,
      spreadRadius: -2,
      offset: const Offset(0, 4),
    ),
    BoxShadow(
      color: const Color(0xFF14193C).withValues(alpha: 0.02),
      blurRadius: 3,
      offset: const Offset(0, 0),
    ),
  ];

  /// Hover shadow - Elevated state
  /// Equivalent to: 0 12px 30px -4px rgba(20, 25, 60, 0.1)
  static List<BoxShadow> get shadowHover => [
    BoxShadow(
      color: const Color(0xFF14193C).withValues(alpha: 0.10),
      blurRadius: 30,
      spreadRadius: -4,
      offset: const Offset(0, 12),
    ),
    BoxShadow(
      color: const Color(0xFF14193C).withValues(alpha: 0.04),
      blurRadius: 4,
      offset: const Offset(0, 0),
    ),
  ];

  /// Button shadow
  /// Equivalent to: 0 4px 14px 0 rgba(20, 25, 60, 0.15)
  static List<BoxShadow> get shadowButton => [
    BoxShadow(
      color: const Color(0xFF14193C).withValues(alpha: 0.15),
      blurRadius: 14,
      offset: const Offset(0, 4),
    ),
  ];

  /// Card shadow (legacy name)
  static List<BoxShadow> get cardShadow => shadowSoft;

  /// Floating shadow - High elevation
  static List<BoxShadow> get shadowFloating => [
    BoxShadow(
      color: const Color(0xFF14193C).withValues(alpha: 0.08),
      blurRadius: 24,
      offset: const Offset(0, 8),
    ),
  ];

  /// Navbar scrolled state
  /// Equivalent to: 0 8px 30px rgba(10,20,35,0.09)
  static List<BoxShadow> get shadowNavbarScrolled => [
    BoxShadow(
      color: const Color(0xFF0A1423).withValues(alpha: 0.09),
      blurRadius: 30,
      offset: const Offset(0, 8),
    ),
  ];

  /// Navbar default state
  /// Equivalent to: 0 10px 24px rgba(10,20,35,0.06)
  static List<BoxShadow> get shadowNavbarDefault => [
    BoxShadow(
      color: const Color(0xFF0A1423).withValues(alpha: 0.06),
      blurRadius: 24,
      offset: const Offset(0, 10),
    ),
  ];

  /// Gold button shadow
  /// Equivalent to: 0 12px 28px rgba(185,149,91,0.28)
  static List<BoxShadow> get shadowGold => [
    BoxShadow(
      color: const Color(0xFFB9955B).withValues(alpha: 0.28),
      blurRadius: 28,
      offset: const Offset(0, 12),
    ),
  ];

  /// Mobile menu shadow
  /// Equivalent to: 0 20px 60px rgba(10,20,35,0.24)
  static List<BoxShadow> get shadowMobileMenu => [
    BoxShadow(
      color: const Color(0xFF0A1423).withValues(alpha: 0.24),
      blurRadius: 60,
      offset: const Offset(0, 20),
    ),
  ];

  // ===========================================================================
  // ANIMATION & MOTION - Consistent Timing
  // ===========================================================================

  static const Duration durationFast = Duration(milliseconds: 200);
  static const Duration durationNormal = Duration(milliseconds: 300);
  static const Duration durationSlow = Duration(milliseconds: 500);
  static const Duration durationVerySlow = Duration(milliseconds: 800);

  static const Curve curveDefault = Curves.easeInOut;
  static const Curve curvePremium = Curves.fastOutSlowIn;

  // ===========================================================================
  // OVERLAY ALPHAS - Semantic Opacity Levels
  // ===========================================================================

  static const double alphaHover = 0.08;
  static const double alphaPressed = 0.12;
  static const double alphaOverlay = 0.40;
  static const double alphaGlass = 0.75;
  static const double alphaSkeleton = 0.60;

  // ===========================================================================
  // TYPOGRAPHY - Plus Jakarta Sans (Matching Next.js)
  // ===========================================================================

  static const String fontFamily = 'Plus Jakarta Sans';

  /// Get text theme with Plus Jakarta Sans
  static TextTheme get jakartaTextTheme {
    return GoogleFonts.plusJakartaSansTextTheme().copyWith(
      // Display - Hero headings
      displayLarge: GoogleFonts.plusJakartaSans(
        fontSize: 48,
        fontWeight: FontWeight.w800,
        letterSpacing: -0.02,
        height: 1.05,
        color: onSurface,
      ),
      displayMedium: GoogleFonts.plusJakartaSans(
        fontSize: 36,
        fontWeight: FontWeight.w800,
        letterSpacing: -0.02,
        height: 1.1,
        color: onSurface,
      ),
      displaySmall: GoogleFonts.plusJakartaSans(
        fontSize: 30,
        fontWeight: FontWeight.w800,
        letterSpacing: -0.02,
        height: 1.1,
        color: onSurface,
      ),

      // Headlines - Section titles
      headlineLarge: GoogleFonts.plusJakartaSans(
        fontSize: 30,
        fontWeight: FontWeight.w800,
        letterSpacing: -0.02,
        height: 1.2,
        color: onSurface,
      ),
      headlineMedium: GoogleFonts.plusJakartaSans(
        fontSize: 24,
        fontWeight: FontWeight.w800,
        letterSpacing: -0.01,
        height: 1.2,
        color: onSurface,
      ),
      headlineSmall: GoogleFonts.plusJakartaSans(
        fontSize: 20,
        fontWeight: FontWeight.w700,
        letterSpacing: -0.01,
        height: 1.3,
        color: onSurface,
      ),

      // Titles - Card titles, product names
      titleLarge: GoogleFonts.plusJakartaSans(
        fontSize: 20,
        fontWeight: FontWeight.w700,
        letterSpacing: -0.01,
        height: 1.3,
        color: onSurface,
      ),
      titleMedium: GoogleFonts.plusJakartaSans(
        fontSize: 18,
        fontWeight: FontWeight.w700,
        letterSpacing: 0,
        height: 1.4,
        color: onSurface,
      ),
      titleSmall: GoogleFonts.plusJakartaSans(
        fontSize: 15,
        fontWeight: FontWeight.w600,
        letterSpacing: 0,
        height: 1.4,
        color: onSurface,
      ),

      // Body - Regular text
      bodyLarge: GoogleFonts.plusJakartaSans(
        fontSize: 16,
        fontWeight: FontWeight.w500,
        letterSpacing: 0,
        height: 1.6,
        color: onSurfaceVariant,
      ),
      bodyMedium: GoogleFonts.plusJakartaSans(
        fontSize: 14,
        fontWeight: FontWeight.w400,
        letterSpacing: 0,
        height: 1.6,
        color: onSurfaceVariant,
      ),
      bodySmall: GoogleFonts.plusJakartaSans(
        fontSize: 13,
        fontWeight: FontWeight.w400,
        letterSpacing: 0,
        height: 1.5,
        color: onSurfaceVariant,
      ),

      // Labels - Buttons, captions, overlines
      labelLarge: GoogleFonts.plusJakartaSans(
        fontSize: 14,
        fontWeight: FontWeight.w700,
        letterSpacing: 0.01,
        height: 1.4,
        color: onSurface,
      ),
      labelMedium: GoogleFonts.plusJakartaSans(
        fontSize: 12,
        fontWeight: FontWeight.w600,
        letterSpacing: 0.14, // tracking-[0.14em] from web
        height: 1.4,
        color: onSurfaceVariant,
      ),
      labelSmall: GoogleFonts.plusJakartaSans(
        fontSize: 11,
        fontWeight: FontWeight.w700,
        letterSpacing: 0.15, // tracking-[0.15em] from web
        height: 1.2,
        color: onSurfaceVariant,
      ),
    );
  }

  /// Legacy Poppins text theme (for backward compatibility)
  static TextTheme get poppinsTextTheme {
    return jakartaTextTheme;
  }

  // ===========================================================================
  // SPACING - 8px-based scale
  // ===========================================================================

  static const double space1 = 4;
  static const double space2 = 8;
  static const double space3 = 12;
  static const double space4 = 16;
  static const double space5 = 20;
  static const double space6 = 24;
  static const double space8 = 32;
  static const double space10 = 40;
  static const double space12 = 48;
  static const double space16 = 64;

  // ===========================================================================
  // MAX WIDTHS (Container breakpoints)
  // ===========================================================================

  static const double maxWidthSm = 1440;
  static const double maxWidthMd = 1500;
  static const double maxWidthLg = 1520;
  static const double maxWidthXl = 1600;
  static const double maxContentWidth = maxWidthSm;

  // ===========================================================================
  // PAGE DECORATION
  // ===========================================================================

  static BoxDecoration get pageDecoration =>
      const BoxDecoration(color: background);

  // ===========================================================================
  // THEME DATA
  // ===========================================================================

  static ThemeData get lightTheme {
    final baseTheme = ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      primaryColor: primary,
      scaffoldBackgroundColor: background,
      fontFamily: fontFamily,
      colorScheme: const ColorScheme.light(
        primary: primary,
        onPrimary: onPrimary,
        secondary: accent,
        onSecondary: onAccent,
        surface: surface,
        onSurface: onSurface,
        surfaceContainerHighest: surfaceContainerHighest,
        surfaceContainerHigh: surfaceContainerHigh,
        surfaceContainer: surfaceContainer,
        surfaceContainerLow: surfaceContainerLow,
        surfaceContainerLowest: surfaceContainerLowest,
        error: error,
        onError: onPrimary,
        outline: outline,
        outlineVariant: outlineLight,
        shadow: slate900,
      ),
    );

    return baseTheme.copyWith(
      textTheme: jakartaTextTheme,

      // App Bar Theme
      appBarTheme: AppBarTheme(
        elevation: 0,
        scrolledUnderElevation: 0,
        backgroundColor: surface,
        surfaceTintColor: Colors.transparent,
        centerTitle: false,
        iconTheme: const IconThemeData(color: onSurface, size: 24),
        titleTextStyle: jakartaTextTheme.titleLarge?.copyWith(
          color: onSurface,
          fontWeight: FontWeight.w700,
        ),
        toolbarTextStyle: jakartaTextTheme.titleMedium?.copyWith(
          color: onSurface,
        ),
      ),

      // Elevated Button Theme
      elevatedButtonTheme: ElevatedButtonThemeData(
        style:
            ElevatedButton.styleFrom(
              backgroundColor: primary,
              foregroundColor: onPrimary,
              elevation: 0,
              shadowColor: Colors.transparent,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
              minimumSize: const Size(44, 44),
              shape: RoundedRectangleBorder(borderRadius: radius8),
              textStyle: jakartaTextTheme.labelLarge?.copyWith(
                color: onPrimary,
                fontWeight: FontWeight.w700,
              ),
            ).copyWith(
              overlayColor: WidgetStateProperty.resolveWith((states) {
                if (states.contains(WidgetState.pressed)) {
                  return onPrimary.withValues(alpha: alphaPressed);
                }
                if (states.contains(WidgetState.hovered)) {
                  return onPrimary.withValues(alpha: alphaHover);
                }
                return null;
              }),
            ),
      ),

      // Filled Button Theme (for accent/gold buttons)
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: accent,
          foregroundColor: onAccent,
          elevation: 0,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          minimumSize: const Size(44, 44),
          shape: RoundedRectangleBorder(borderRadius: radius8),
          textStyle: jakartaTextTheme.labelLarge?.copyWith(
            color: onAccent,
            fontWeight: FontWeight.w700,
          ),
        ),
      ),

      // Outlined Button Theme
      outlinedButtonTheme: OutlinedButtonThemeData(
        style:
            OutlinedButton.styleFrom(
              foregroundColor: primary,
              side: const BorderSide(color: outline, width: 1.5),
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
              minimumSize: const Size(44, 44),
              shape: RoundedRectangleBorder(borderRadius: radius8),
              textStyle: jakartaTextTheme.labelLarge?.copyWith(
                fontWeight: FontWeight.w700,
              ),
            ).copyWith(
              overlayColor: WidgetStateProperty.resolveWith((states) {
                if (states.contains(WidgetState.pressed)) {
                  return primary.withValues(alpha: 0.08);
                }
                return null;
              }),
            ),
      ),

      // Text Button Theme
      textButtonTheme: TextButtonThemeData(
        style:
            TextButton.styleFrom(
              foregroundColor: primary,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              minimumSize: const Size(36, 36),
              textStyle: jakartaTextTheme.labelLarge?.copyWith(
                fontWeight: FontWeight.w700,
              ),
            ).copyWith(
              overlayColor: WidgetStateProperty.resolveWith((states) {
                if (states.contains(WidgetState.pressed)) {
                  return primary.withValues(alpha: 0.08);
                }
                return null;
              }),
            ),
      ),

      // Input Decoration Theme
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: cream,
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 14,
        ),
        border: OutlineInputBorder(
          borderRadius: radius12,
          borderSide: const BorderSide(color: outline, width: 1),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: radius12,
          borderSide: const BorderSide(color: outline, width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: radius12,
          borderSide: const BorderSide(color: primary, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: radius12,
          borderSide: const BorderSide(color: error, width: 1),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: radius12,
          borderSide: const BorderSide(color: error, width: 2),
        ),
        hintStyle: jakartaTextTheme.bodyMedium?.copyWith(color: onSurfaceMuted),
        labelStyle: jakartaTextTheme.bodyMedium?.copyWith(
          color: onSurfaceVariant,
        ),
        floatingLabelStyle: jakartaTextTheme.bodyMedium?.copyWith(
          color: primary,
          fontWeight: FontWeight.w600,
        ),
        prefixIconColor: onSurfaceVariant,
        suffixIconColor: onSurfaceVariant,
      ),

      // Card Theme
      cardTheme: CardThemeData(
        color: surface,
        elevation: 0,
        shadowColor: Colors.transparent,
        shape: RoundedRectangleBorder(
          borderRadius: radius22,
          side: const BorderSide(color: outlineLight, width: 1),
        ),
        clipBehavior: Clip.antiAlias,
        margin: EdgeInsets.zero,
      ),

      // Divider Theme
      dividerTheme: const DividerThemeData(
        color: outline,
        thickness: 1,
        space: 1,
        indent: 0,
        endIndent: 0,
      ),

      // Bottom Navigation Bar Theme
      bottomNavigationBarTheme: BottomNavigationBarThemeData(
        backgroundColor: surface,
        selectedItemColor: primary,
        unselectedItemColor: onSurfaceVariant,
        selectedLabelStyle: jakartaTextTheme.labelSmall?.copyWith(
          fontWeight: FontWeight.w600,
          fontSize: 11,
        ),
        unselectedLabelStyle: jakartaTextTheme.labelSmall?.copyWith(
          fontWeight: FontWeight.w500,
          fontSize: 11,
        ),
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
        elevation: 0,
        landscapeLayout: BottomNavigationBarLandscapeLayout.centered,
      ),

      // Chip Theme
      chipTheme: ChipThemeData(
        backgroundColor: muted,
        selectedColor: primary,
        disabledColor: muted,
        labelStyle: jakartaTextTheme.labelMedium?.copyWith(
          color: onSurface,
          fontWeight: FontWeight.w600,
        ),
        secondaryLabelStyle: jakartaTextTheme.labelMedium?.copyWith(
          color: onPrimary,
          fontWeight: FontWeight.w600,
        ),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        shape: RoundedRectangleBorder(borderRadius: radius8),
        side: BorderSide.none,
      ),

      // Badge Theme
      badgeTheme: BadgeThemeData(
        backgroundColor: error,
        textColor: onPrimary,
        smallSize: 16,
        largeSize: 20,
        textStyle: jakartaTextTheme.labelSmall?.copyWith(
          color: onPrimary,
          fontWeight: FontWeight.w700,
          fontSize: 10,
        ),
      ),

      // Dialog Theme
      dialogTheme: DialogThemeData(
        backgroundColor: surface,
        elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: radius24),
        titleTextStyle: jakartaTextTheme.headlineSmall,
        contentTextStyle: jakartaTextTheme.bodyMedium,
      ),

      // Bottom Sheet Theme
      bottomSheetTheme: const BottomSheetThemeData(
        backgroundColor: surface,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.only(
            topLeft: Radius.circular(30),
            topRight: Radius.circular(30),
          ),
        ),
        clipBehavior: Clip.antiAlias,
      ),

      // Tab Bar Theme
      tabBarTheme: TabBarThemeData(
        labelStyle: jakartaTextTheme.labelLarge?.copyWith(
          fontWeight: FontWeight.w700,
        ),
        unselectedLabelStyle: jakartaTextTheme.labelLarge?.copyWith(
          fontWeight: FontWeight.w500,
        ),
        labelColor: primary,
        unselectedLabelColor: onSurfaceVariant,
        indicatorColor: primary,
        dividerColor: outline,
      ),

      // Slider Theme
      sliderTheme: SliderThemeData(
        activeTrackColor: primary,
        inactiveTrackColor: outline,
        thumbColor: primary,
        overlayColor: primary.withValues(alpha: 0.12),
        trackHeight: 4,
        thumbShape: const RoundSliderThumbShape(
          enabledThumbRadius: 8,
          elevation: 0,
        ),
        overlayShape: const RoundSliderOverlayShape(overlayRadius: 16),
      ),

      // Switch Theme
      switchTheme: SwitchThemeData(
        thumbColor: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return primary;
          }
          return surface;
        }),
        trackColor: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return primary.withValues(alpha: 0.5);
          }
          return outline;
        }),
        trackOutlineColor: WidgetStateProperty.all(Colors.transparent),
      ),

      // Checkbox Theme
      checkboxTheme: CheckboxThemeData(
        fillColor: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return primary;
          }
          return surface;
        }),
        checkColor: WidgetStateProperty.all(onPrimary),
        side: const BorderSide(color: outline, width: 2),
        shape: RoundedRectangleBorder(borderRadius: radius6),
      ),

      // Radio Theme
      radioTheme: RadioThemeData(
        fillColor: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return primary;
          }
          return outline;
        }),
      ),

      // Progress Indicator Theme
      progressIndicatorTheme: const ProgressIndicatorThemeData(
        color: primary,
        linearTrackColor: outline,
        circularTrackColor: outline,
        linearMinHeight: 4,
      ),

      // Tooltip Theme
      tooltipTheme: TooltipThemeData(
        decoration: BoxDecoration(color: onSurface, borderRadius: radius8),
        textStyle: jakartaTextTheme.bodySmall?.copyWith(color: onPrimary),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        margin: const EdgeInsets.all(8),
        waitDuration: const Duration(milliseconds: 500),
        showDuration: const Duration(seconds: 2),
      ),
    );
  }
}
