import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../models/image_model.dart';
import '../../utils/storage_url.dart';

class ProductGallery extends StatelessWidget {
  const ProductGallery({
    super.key,
    required this.images,
    required this.pageController,
    required this.currentIndex,
    required this.onPageChanged,
    required this.productId,
    required this.isWide,
  });

  final List<ImageModel> images;
  final PageController pageController;
  final int currentIndex;
  final ValueChanged<int> onPageChanged;
  final String productId;
  final bool isWide;

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        Container(
          color: AppTheme.slate50,
          height: isWide ? MediaQuery.of(context).size.height : double.infinity,
          width: double.infinity,
          child: images.isEmpty
              ? const Icon(
                  Icons.inventory_2_outlined,
                  size: 64,
                  color: AppTheme.slate200,
                )
              : PageView.builder(
                  controller: pageController,
                  onPageChanged: onPageChanged,
                  itemCount: images.length,
                  itemBuilder: (context, index) {
                    final imageWidget = CachedNetworkImage(
                      imageUrl: StorageUrl.format(images[index].url),
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(
                        color: AppTheme.slate50,
                        child: const Center(
                          child: SizedBox(
                            width: 24,
                            height: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: AppTheme.primary,
                            ),
                          ),
                        ),
                      ),
                      errorWidget: (context, url, error) => const Icon(
                        Icons.error_outline,
                        color: AppTheme.slate300,
                      ),
                    );

                    return imageWidget;
                  },
                ),
        ),
        if (images.length > 1)
          Positioned(
            bottom: 20,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(
                images.length,
                (index) => AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  height: 6,
                  width: currentIndex == index ? 24 : 6,
                  decoration: BoxDecoration(
                    color: currentIndex == index
                        ? AppTheme.primary
                        : Colors.white.withValues(alpha: 0.6),
                    borderRadius: BorderRadius.circular(3),
                  ),
                ),
              ),
            ),
          ),
      ],
    );
  }
}
