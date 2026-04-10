import 'package:cached_network_image/cached_network_image.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../../config/theme.dart';
import '../../../models/landing_page_data.dart';
import '../../../utils/responsive_helper.dart';
import '../../../utils/storage_url.dart';
import '../../../widgets/animations/animations.dart';

class HomeHeroSlider extends StatefulWidget {
  final List<HeroSlide> slides;

  const HomeHeroSlider({super.key, required this.slides});

  @override
  State<HomeHeroSlider> createState() => _HomeHeroSliderState();
}

class _HomeHeroSliderState extends State<HomeHeroSlider> {
  int _currentCarouselIndex = 0;

  @override
  Widget build(BuildContext context) {
    if (widget.slides.isEmpty) return const SizedBox.shrink();
    
    final heroHeight = ResponsiveHelper.heroHeight(context);

    return Stack(
      children: [
        CarouselSlider(
          options: CarouselOptions(
            height: heroHeight,
            viewportFraction: 1.0,
            autoPlay: true,
            autoPlayInterval: const Duration(seconds: 5),
            autoPlayAnimationDuration: AppTheme.durationVerySlow,
            autoPlayCurve: AppTheme.curveDefault,
            onPageChanged: (index, reason) {
              setState(() => _currentCarouselIndex = index);
            },
          ),
          items: widget.slides.map((slide) {
            return Builder(
              builder: (BuildContext context) {
                return Stack(
                  fit: StackFit.expand,
                  children: [
                    // Image with blur-to-sharp loading
                    CachedNetworkImage(
                      imageUrl: StorageUrl.format(slide.image),
                      fit: BoxFit.cover,
                      alignment: Alignment.topCenter,
                      placeholder: (context, url) => Container(
                        color: AppTheme.muted,
                        child: const Center(
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: AppTheme.accent,
                          ),
                        ),
                      ),
                      errorWidget: (context, url, error) => _buildErrorWidget(context),
                    ),
                    // Gradient overlay
                    Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          stops: const [0.3, 0.85],
                          colors: [
                            Colors.transparent,
                            Colors.black.withValues(alpha: AppTheme.alphaGlass),
                          ],
                        ),
                      ),
                    ),
                    // Content
                    Positioned(
                      bottom: 48,
                      left: ResponsiveHelper.horizontalPadding(context),
                      right: ResponsiveHelper.horizontalPadding(context),
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            slide.title,
                            textAlign: TextAlign.center,
                            style: Theme.of(context).textTheme.displaySmall?.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.w800,
                              letterSpacing: -0.5,
                            ),
                          ),
                          const SizedBox(height: 12),
                          Text(
                            slide.subtitle,
                            textAlign: TextAlign.center,
                            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                              color: Colors.white.withValues(alpha: 0.9),
                              height: 1.5,
                            ),
                          ),
                          const SizedBox(height: 28),
                          Semantics(
                            label: 'Tombol: ${slide.linkText.isNotEmpty ? slide.linkText : 'Beli Sekarang'}',
                            button: true,
                            child: PressableButton(
                              onPressed: () {
                                if (slide.target.isNotEmpty) {
                                  context.push('/shop');
                                }
                              },
                              backgroundColor: AppTheme.accent,
                              foregroundColor: AppTheme.onAccent,
                              borderRadius: AppTheme.radiusMd,
                              padding: const EdgeInsets.symmetric(
                                horizontal: 32,
                                vertical: 16,
                              ),
                              child: Text(
                                slide.linkText.isNotEmpty ? slide.linkText : 'Beli Sekarang',
                                style: const TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.w700,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                );
              },
            );
          }).toList(),
        ),
        // Carousel indicators
        Positioned(
          bottom: 20,
          left: 0,
          right: 0,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: widget.slides.asMap().entries.map((entry) {
              final isActive = _currentCarouselIndex == entry.key;
              return AnimatedContainer(
                duration: AppTheme.durationNormal,
                width: isActive ? 28.0 : 8.0,
                height: 8.0,
                margin: const EdgeInsets.symmetric(horizontal: 4.0),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(4),
                  color: isActive ? Colors.white : Colors.white.withValues(alpha: AppTheme.alphaOverlay),
                ),
              );
            }).toList(),
          ),
        ),
      ],
    );
  }

  Widget _buildErrorWidget(BuildContext context) {
    return Container(
      color: AppTheme.muted,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(
            Icons.image_not_supported_outlined,
            color: AppTheme.onSurfaceMuted,
            size: 40,
          ),
          const SizedBox(height: 8),
          Text(
            'Gagal memuat gambar',
            style: Theme.of(context).textTheme.bodySmall?.copyWith(color: AppTheme.onSurfaceVariant),
          ),
        ],
      ),
    );
  }
}
