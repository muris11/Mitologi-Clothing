import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../models/product.dart';
import '../../providers/wishlist_provider.dart';
import '../../utils/price_formatter.dart';
import '../../utils/storage_url.dart';

class ProductCard extends StatelessWidget {
  const ProductCard({super.key, required this.product});

  final Product product;

  @override
  Widget build(BuildContext context) {
    final minPrice = PriceFormatter.formatStringIDR(
      product.priceRange.minVariantPrice.amount,
    );
    final maxPrice = PriceFormatter.formatStringIDR(
      product.priceRange.maxVariantPrice.amount,
    );
    final hasPriceRange =
        product.priceRange.minVariantPrice.amount !=
        product.priceRange.maxVariantPrice.amount;
    final rating = product.averageRating?.toStringAsFixed(1);
    final soldLabel = '${product.totalSold ?? 0}+ terjual';
    final variantCount = product.variants.length;
    final variantLabel = variantCount > 1
        ? '$variantCount varian'
        : (product.variants.isNotEmpty &&
                  product.variants.first.title.isNotEmpty &&
                  product.variants.first.title != 'Default Title'
              ? product.variants.first.title
              : 'Siap dikirim');
    final imageUrl = product.images.isNotEmpty
        ? StorageUrl.format(product.images.first.url)
        : StorageUrl.format(product.featuredImage.url);
    final isBestSeller = product.tags.contains('best_seller');
    final isNewArrival = product.tags.contains('new_arrival');
    final lowStock =
        (product.totalStock ?? 0) > 0 && (product.totalStock ?? 0) <= 5;

    return InkWell(
      borderRadius: AppTheme.radius22,
      onTap: () => context.push('/shop/product/${product.handle}'),
      child: Ink(
        decoration: BoxDecoration(
          color: AppTheme.sectionBackground,
          borderRadius: AppTheme.radius22,
          border: Border.all(color: AppTheme.outlineLight, width: 1),
          boxShadow: AppTheme.shadowSoft,
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Stack(
                fit: StackFit.expand,
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.vertical(
                      top: AppTheme.radius22.topLeft,
                    ),
                    child: CachedNetworkImage(
                      imageUrl: imageUrl,
                      fit: BoxFit.cover,
                      alignment: Alignment.topCenter,
                      placeholder: (context, url) => Container(
                        color: AppTheme.surfaceContainerLow,
                        child: const Center(
                          child: SizedBox(
                            width: 22,
                            height: 22,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: AppTheme.secondary,
                            ),
                          ),
                        ),
                      ),
                      errorWidget: (context, url, error) => Container(
                        color: AppTheme.surfaceContainerLow,
                        child: const Icon(
                          Icons.image_outlined,
                          color: AppTheme.surfaceContainerHigh,
                          size: 30,
                        ),
                      ),
                    ),
                  ),
                  Positioned(
                    left: 10,
                    top: 10,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (isBestSeller)
                          _Badge(
                            label: 'BEST SELLER',
                            backgroundColor: AppTheme.secondary.withValues(
                              alpha: 0.95,
                            ),
                            foregroundColor: Colors.white,
                          ),
                        if (isNewArrival)
                          Padding(
                            padding: const EdgeInsets.only(top: 6),
                            child: _Badge(
                              label: 'BARU',
                              backgroundColor: AppTheme.primary.withValues(
                                alpha: 0.92,
                              ),
                              foregroundColor: Colors.white,
                            ),
                          ),
                      ],
                    ),
                  ),
                  Positioned(
                    top: 10,
                    right: 10,
                    child: Consumer<WishlistProvider>(
                      builder: (context, wishlistProvider, child) {
                        final isWishlisted = wishlistProvider.isWishlisted(
                          product.id.toString(),
                        );
                        return InkWell(
                          borderRadius: AppTheme.radius12,
                          onTap: () => wishlistProvider.toggleWishlist(product),
                          child: Ink(
                            width: 36,
                            height: 36,
                            decoration: BoxDecoration(
                              color: AppTheme.sectionBackground.withValues(
                                alpha: 0.95,
                              ),
                              borderRadius: AppTheme.radius12,
                              border: Border.all(
                                color: AppTheme.outlineLight,
                                width: 1,
                              ),
                            ),
                            child: Icon(
                              isWishlisted
                                  ? Icons.favorite
                                  : Icons.favorite_border,
                              color: isWishlisted
                                  ? AppTheme.error
                                  : AppTheme.onSurfaceVariant,
                              size: 18,
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                  Positioned(
                    left: 0,
                    right: 0,
                    bottom: 0,
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 8,
                      ),
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.bottomCenter,
                          end: Alignment.topCenter,
                          colors: [
                            AppTheme.onSurface.withValues(alpha: 0.76),
                            Colors.transparent,
                          ],
                        ),
                      ),
                      child: Row(
                        children: [
                          const Spacer(),
                          if (lowStock)
                            Text(
                              'Sisa ${product.totalStock}',
                              style: Theme.of(context).textTheme.bodySmall
                                  ?.copyWith(
                                    color: AppTheme.warning,
                                    fontWeight: FontWeight.w700,
                                  ),
                            ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(12, 10, 12, 12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    product.title,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: Theme.of(
                      context,
                    ).textTheme.titleSmall?.copyWith(fontSize: 14, height: 1.2),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    hasPriceRange ? '$minPrice - $maxPrice' : minPrice,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      color: AppTheme.onSurface,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    variantLabel,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      color: AppTheme.slate500,
                      fontWeight: FontWeight.w600,
                      letterSpacing: 0.2,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      const Icon(
                        Icons.arrow_outward,
                        color: AppTheme.onSurface,
                        size: 16,
                      ),
                      const SizedBox(width: 4),
                      if (rating != null) ...[
                        Text(
                          rating,
                          style: Theme.of(context).textTheme.bodySmall
                              ?.copyWith(
                                color: AppTheme.onSurface,
                                fontWeight: FontWeight.w700,
                              ),
                        ),
                        const Padding(
                          padding: EdgeInsets.symmetric(horizontal: 6),
                          child: Text(
                            '•',
                            style: TextStyle(
                              color: AppTheme.surfaceContainerHigh,
                            ),
                          ),
                        ),
                      ],
                      Expanded(
                        child: Text(
                          soldLabel,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: Theme.of(context).textTheme.bodySmall
                              ?.copyWith(color: AppTheme.onSurfaceVariant),
                        ),
                      ),
                      Container(
                        width: 28,
                        height: 28,
                        decoration: BoxDecoration(
                          color: AppTheme.muted,
                          borderRadius: AppTheme.radius8,
                        ),
                        child: const Icon(
                          Icons.arrow_forward,
                          color: AppTheme.onSurface,
                          size: 14,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _Badge extends StatelessWidget {
  const _Badge({
    required this.label,
    required this.backgroundColor,
    required this.foregroundColor,
  });

  final String label;
  final Color backgroundColor;
  final Color foregroundColor;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: backgroundColor,
        borderRadius: AppTheme.radius8,
      ),
      child: Text(
        label,
        style: Theme.of(context).textTheme.labelMedium?.copyWith(
          color: foregroundColor,
          fontWeight: FontWeight.w800,
          letterSpacing: 0.8,
        ),
      ),
    );
  }
}
