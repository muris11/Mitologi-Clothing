import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../../config/theme.dart';
import '../../../models/collection.dart';
import '../../../utils/responsive_helper.dart';
import '../../../utils/storage_url.dart';
import '../../../widgets/animations/animations.dart';

class HomeCollectionsSection extends StatelessWidget {
  final List<Collection> collections;
  final int staggerIndex;

  const HomeCollectionsSection({
    super.key,
    required this.collections,
    this.staggerIndex = 0,
  });

  @override
  Widget build(BuildContext context) {
    if (collections.isEmpty) return const SizedBox.shrink();

    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);
    final sectionGap = ResponsiveHelper.sectionGap(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Koleksi',
                style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                'Jelajahi koleksi pilihan kami',
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: AppTheme.onSurfaceVariant,
                ),
              ),
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: AppTheme.surfaceContainerLowest,
                  borderRadius: AppTheme.radius16,
                  border: Border.all(color: AppTheme.outlineLight),
                ),
                child: const Text(
                  'Setiap koleksi dirancang untuk memberi pintu masuk yang lebih terarah ke gaya dan kebutuhan Anda.',
                  style: TextStyle(
                    color: AppTheme.onSurfaceVariant,
                    fontSize: 12,
                    height: 1.45,
                  ),
                ),
              ),
            ],
          ),
        ),

        SizedBox(height: sectionGap * 0.6),

        SizedBox(
          height: 180,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
            itemCount: collections.length,
            separatorBuilder: (_, index) => const SizedBox(width: 16),
            itemBuilder: (context, index) {
              final collection = collections[index];
              return FadeInUp(
                delay: Duration(milliseconds: (staggerIndex * 100) + (index * 80)),
                child: _buildCollectionCard(context, collection),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildCollectionCard(BuildContext context, Collection collection) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: () => context.push('/shop/collection/${collection.handle}'),
        borderRadius: AppTheme.radius16,
        child: Container(
          width: 280,
          decoration: BoxDecoration(
            borderRadius: AppTheme.radius16,
            boxShadow: AppTheme.shadowSoft,
          ),
          child: ClipRRect(
            borderRadius: AppTheme.radius16,
            child: Stack(
              fit: StackFit.expand,
              children: [
                CachedNetworkImage(
                  imageUrl: StorageUrl.format(collection.image),
                  fit: BoxFit.cover,
                  placeholder: (context, url) => Container(color: AppTheme.muted),
                  errorWidget: (context, url, e) => Container(
                    color: AppTheme.muted,
                    child: const Icon(Icons.image_outlined, color: AppTheme.onSurfaceMuted),
                  ),
                ),
                Container(
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      begin: Alignment.topCenter,
                      end: Alignment.bottomCenter,
                      colors: [
                        Colors.transparent,
                        Colors.black.withValues(alpha: 0.7),
                      ],
                    ),
                  ),
                ),
                Positioned(
                  bottom: 16,
                  left: 16,
                  right: 16,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        collection.title,
                        style: Theme.of(context).textTheme.titleMedium?.copyWith(
                          color: Colors.white,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                      if (collection.description.isNotEmpty)
                        Text(
                          collection.description,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: Theme.of(context).textTheme.labelSmall?.copyWith(
                            color: Colors.white.withValues(alpha: 0.9),
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
