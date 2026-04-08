import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<_OnboardingData> _pages = [
    _OnboardingData(
      title: 'Temukan Style Premium Anda',
      description:
          'Jelajahi koleksi eksklusif kami yang dirancang dengan bahan berkualitas tinggi untuk gaya yang tak tertandingi.',
      icon: Icons.style_outlined,
      gradient: const LinearGradient(
        colors: [Color(0xFF1e3a5f), Color(0xFF2d4a6f)],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
      ),
    ),
    _OnboardingData(
      title: 'Material Berkualitas Tinggi',
      description:
          'Setiap produk kami dibuat dengan bahan pilihan terbaik untuk kenyamanan dan ketahanan maksimal.',
      icon: Icons.verified_outlined,
      gradient: const LinearGradient(
        colors: [Color(0xFF2d4a6f), Color(0xFF3d5a7f)],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
      ),
    ),
    _OnboardingData(
      title: 'Belanja Mudah & Aman',
      description:
          'Nikmati pengalaman berbelanja yang seamless dengan pembayaran aman dan pengiriman terpercaya.',
      icon: Icons.shopping_bag_outlined,
      gradient: const LinearGradient(
        colors: [Color(0xFF3d5a7f), Color(0xFF1e3a5f)],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
      ),
    ),
  ];

  void _onPageChanged(int page) {
    setState(() {
      _currentPage = page;
    });
  }

  Future<void> _completeOnboarding() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('has_onboarded', true);

    if (mounted) {
      context.go('/');
    }
  }

  void _nextPage() {
    if (_currentPage < _pages.length - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 400),
        curve: Curves.easeInOut,
      );
    } else {
      _completeOnboarding();
    }
  }

  void _skip() {
    _completeOnboarding();
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.pageBackground,
      body: AnnotatedRegion<SystemUiOverlayStyle>(
        value: SystemUiOverlayStyle.dark,
        child: Stack(
          children: [
            // Subtle cream background
            Container(color: AppTheme.pageBackground),
            // Decorative circles
            Positioned(
              top: -100,
              right: -100,
              child: Container(
                width: 300,
                height: 300,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: AppTheme.primary.withValues(alpha: 0.05),
                ),
              ),
            ),
            Positioned(
              bottom: -150,
              left: -100,
              child: Container(
                width: 400,
                height: 400,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: AppTheme.primary.withValues(alpha: 0.02),
                ),
              ),
            ),
            // Content
            SafeArea(
              child: Column(
                children: [
                  // Skip button
                  Align(
                    alignment: Alignment.topRight,
                    child: Padding(
                      padding: EdgeInsets.all(
                        ResponsiveHelper.horizontalPadding(context),
                      ),
                      child: TextButton(
                        onPressed: _skip,
                        child: Text(
                          'Lewati',
                          style: Theme.of(context).textTheme.labelLarge
                              ?.copyWith(color: AppTheme.onSurfaceVariant),
                        ),
                      ),
                    ),
                  ),
                  // Page view
                  Expanded(
                    child: PageView.builder(
                      controller: _pageController,
                      onPageChanged: _onPageChanged,
                      itemCount: _pages.length,
                      itemBuilder: (context, index) {
                        return _buildPage(_pages[index]);
                      },
                    ),
                  ),
                  // Bottom section
                  Container(
                    padding: EdgeInsets.all(
                      ResponsiveHelper.horizontalPadding(context) * 2,
                    ),
                    child: Column(
                      children: [
                        // Dot indicators
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: List.generate(
                            _pages.length,
                            (index) => AnimatedContainer(
                              duration: const Duration(milliseconds: 300),
                              margin: const EdgeInsets.symmetric(horizontal: 4),
                              width: _currentPage == index ? 24 : 8,
                              height: 8,
                              decoration: BoxDecoration(
                                color: _currentPage == index
                                    ? AppTheme.accent
                                    : AppTheme.surfaceContainerHighest,
                                borderRadius: BorderRadius.circular(4),
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(height: 32),
                        // Next/Get Started button
                        SizedBox(
                          width: double.infinity,
                          height: 58,
                          child: ElevatedButton(
                            onPressed: _nextPage,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.accent,
                              foregroundColor: Colors.white,
                              elevation: 0,
                              padding: const EdgeInsets.symmetric(vertical: 16),
                              shape: RoundedRectangleBorder(
                                borderRadius: AppTheme.radius16,
                              ),
                            ),
                            child: Text(
                              _currentPage == _pages.length - 1
                                  ? 'Mulai Belanja'
                                  : 'Lanjutkan',
                              style: Theme.of(context).textTheme.titleSmall
                                  ?.copyWith(color: Colors.white),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPage(_OnboardingData data) {
    return Padding(
      padding: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context) * 2.5,
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Icon container with premium styling
          Container(
            width: 140,
            height: 140,
            decoration: BoxDecoration(
              color: AppTheme.sectionBackground,
              borderRadius: AppTheme.radius24,
              border: Border.all(color: AppTheme.outlineLight, width: 1),
              boxShadow: AppTheme.shadowSoft,
            ),
            child: Icon(data.icon, size: 56, color: AppTheme.primary),
          ),
          const SizedBox(height: 60),
          // Title
          Text(
            data.title,
            textAlign: TextAlign.center,
            style: Theme.of(
              context,
            ).textTheme.headlineMedium?.copyWith(color: AppTheme.onSurface),
          ),
          const SizedBox(height: 20),
          // Description
          Text(
            data.description,
            textAlign: TextAlign.center,
            style: Theme.of(
              context,
            ).textTheme.bodyLarge?.copyWith(color: AppTheme.onSurfaceVariant),
          ),
        ],
      ),
    );
  }
}

class _OnboardingData {
  final String title;
  final String description;
  final IconData icon;
  final Gradient gradient;

  _OnboardingData({
    required this.title,
    required this.description,
    required this.icon,
    required this.gradient,
  });
}
