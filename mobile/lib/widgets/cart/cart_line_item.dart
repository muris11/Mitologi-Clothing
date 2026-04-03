import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../models/cart_item.dart';
import '../../utils/price_formatter.dart';
import '../../utils/storage_url.dart';

class CartLineItem extends StatelessWidget {
  const CartLineItem({
    super.key,
    required this.item,
    required this.onRemove,
    required this.onDecrease,
    required this.onIncrease,
  });

  final CartItem item;
  final VoidCallback onRemove;
  final VoidCallback onDecrease;
  final VoidCallback onIncrease;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: AppTheme.radius19,
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: AppTheme.radius11,
            child: SizedBox(
              width: 90,
              height: 90,
              child: item.imageUrl != null
                  ? CachedNetworkImage(
                      imageUrl: StorageUrl.format(item.imageUrl!),
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(
                        color: AppTheme.surfaceContainerLow,
                        child: const Center(
                          child: SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: AppTheme.secondary,
                            ),
                          ),
                        ),
                      ),
                      errorWidget: (context, url, err) => Container(
                        color: AppTheme.surfaceContainerLow,
                        child: const Icon(
                          Icons.image_not_supported,
                          color: AppTheme.surfaceContainerHigh,
                        ),
                      ),
                    )
                  : Container(
                      color: AppTheme.surfaceContainerLow,
                      child: const Icon(
                        Icons.image_not_supported,
                        color: AppTheme.surfaceContainerHigh,
                      ),
                    ),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Text(
                        item.title,
                        style: const TextStyle(
                          fontWeight: FontWeight.w700,
                          fontSize: 14,
                          color: AppTheme.onSurface,
                          height: 1.3,
                        ),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    const SizedBox(width: 8),
                    InkWell(
                      onTap: onRemove,
                      child: const Icon(
                        Icons.close,
                        color: AppTheme.onSurfaceVariant,
                        size: 20,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 6),
                if (item.variantTitle != null && item.variantTitle!.isNotEmpty)
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 4,
                    ),
                    decoration: BoxDecoration(
                      color: AppTheme.surfaceContainerLow,
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Text(
                      item.variantTitle!,
                      style: const TextStyle(
                        color: AppTheme.onSurfaceVariant,
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  )
                else
                  const SizedBox(height: 18),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(
                        PriceFormatter.formatStringIDR(item.price.amount),
                        style: const TextStyle(
                          fontWeight: FontWeight.w800,
                          color: AppTheme.onSurface,
                          fontSize: 15,
                        ),
                      ),
                    ),
                    Container(
                      height: 32,
                      decoration: BoxDecoration(
                        color: AppTheme.surfaceContainerLow,
                        borderRadius: AppTheme.radius11,
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          InkWell(
                            borderRadius: AppTheme.radius11,
                            onTap: onDecrease,
                            child: const Padding(
                              padding: EdgeInsets.symmetric(horizontal: 10),
                              child: Icon(
                                Icons.remove,
                                size: 14,
                                color: AppTheme.onSurfaceVariant,
                              ),
                            ),
                          ),
                          Text(
                            '${item.quantity}',
                            style: const TextStyle(
                              fontWeight: FontWeight.w700,
                              fontSize: 13,
                              color: AppTheme.onSurface,
                            ),
                          ),
                          InkWell(
                            borderRadius: AppTheme.radius11,
                            onTap: onIncrease,
                            child: const Padding(
                              padding: EdgeInsets.symmetric(horizontal: 10),
                              child: Icon(
                                Icons.add,
                                size: 14,
                                color: AppTheme.primary,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
