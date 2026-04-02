import 'dart:async';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../config/theme.dart';
import '../../providers/auth_provider.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/animations/text_blur_reveal.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _scaleAnimation;
  late Animation<double> _opacityAnimation;
  late Animation<double> _slideAnimation;

  @override
  void initState() {
    super.initState();

    _controller = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 2500),
    );

    _scaleAnimation = Tween<double>(begin: 0.8, end: 1.0).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0.0, 0.6, curve: Curves.easeOutCubic),
      ),
    );

    _opacityAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0.2, 0.8, curve: Curves.easeIn),
      ),
    );

    _slideAnimation = Tween<double>(begin: 20.0, end: 0.0).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0.4, 1.0, curve: Curves.easeOutCubic),
      ),
    );

    _controller.repeat(reverse: true);

    // Navigate smoothly after checking auth
    Timer(const Duration(milliseconds: 3500), _checkAuthAndNavigate);
  }

  Future<void> _checkAuthAndNavigate() async {
    if (!mounted) return;

    final authProvider = context.read<AuthProvider>();

    // Check if user has completed onboarding
    final prefs = await SharedPreferences.getInstance();
    final hasOnboarded = prefs.getBool('has_onboarded') ?? false;

    if (!hasOnboarded) {
      if (!mounted) return;
      context.go('/onboarding');
      return;
    }

    await authProvider.loadUserFromStorage();

    if (!mounted) return;

    // Smooth transition to next page
    if (authProvider.isAuthenticated) {
      context.go('/');
    } else {
      context.go('/shop/login');
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: AnimatedBuilder(
        animation: _controller,
        builder: (context, child) {
          // Subtle pulse based on time or scale
          final pulse = (1.0 + (_controller.value * 0.1));
          return Container(
            color: AppTheme.surface,
            child: Center(
              child: Transform.translate(
                offset: Offset(0, _slideAnimation.value),
                child: Opacity(
                  opacity: _opacityAnimation.value,
                  child: Transform.scale(
                    scale: _scaleAnimation.value,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        // Premium logo display
                        Hero(
                          tag: 'startup-logo',
                          child: Container(
                            width: 140,
                            height: 140,
                            decoration: const BoxDecoration(
                              color: AppTheme.surfaceContainerLowest,
                              shape: BoxShape.circle,
                            ),
                            padding: EdgeInsets.all(
                              ResponsiveHelper.horizontalPadding(context) *
                                  1.75,
                            ),
                            child: Image.asset(
                              'assets/images/logo.png',
                              fit: BoxFit.contain,
                            ),
                          ),
                        ),
                        const SizedBox(height: 48),
                        // Brand Typography
                        TextBlurReveal(
                          text: 'MITOLOGI',
                          style:
                              Theme.of(
                                context,
                              ).textTheme.displayLarge?.copyWith(
                                fontSize: 34,
                                letterSpacing: 8 * pulse, // slight expand
                              ) ??
                              const TextStyle(),
                        ),
                        const SizedBox(height: 12),
                        Text(
                          'PREMIUM MENSWEAR',
                          style: Theme.of(
                            context,
                          ).textTheme.labelSmall?.copyWith(letterSpacing: 4),
                        ),
                        const SizedBox(height: 60),
                        // Luxury minimal loader
                        SizedBox(
                          width: 24,
                          height: 24,
                          child: CircularProgressIndicator(
                            valueColor: const AlwaysStoppedAnimation<Color>(
                              AppTheme.primary,
                            ),
                            strokeWidth: 2,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}
