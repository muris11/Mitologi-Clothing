import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import '../../providers/collection_provider.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/loading_shimmer.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/animations/neon_glow_card.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class CollectionsScreen extends StatefulWidget {
  const CollectionsScreen({super.key});

  @override
  State<CollectionsScreen> createState() => _CollectionsScreenState();
}

class _CollectionsScreenState extends State<CollectionsScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<CollectionProvider>().fetchCollections();
    });
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Kategori',
      subtitle: 'Kurasi koleksi Mitologi dengan presentasi yang lebih premium.',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: Consumer<CollectionProvider>(
        builder: (context, provider, child) {
          if (provider.isLoadingCollections) {
            return LayoutBuilder(
              builder: (context, constraints) {
                int crossAxisCount = 2;
                if (constraints.maxWidth > 900) {
                  crossAxisCount = 4;
                } else if (constraints.maxWidth > 600) {
                  crossAxisCount = 3;
                }

                return GridView.builder(
                  padding: EdgeInsets.all(
                    ResponsiveHelper.horizontalPadding(context),
                  ),
                  gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: crossAxisCount,
                    crossAxisSpacing: 16,
                    mainAxisSpacing: 16,
                    childAspectRatio: 0.8,
                  ),
                  itemCount: 6,
                  itemBuilder: (context, index) => const LoadingShimmer(
                    width: double.infinity,
                    height: 220,
                    borderRadius: 16,
                  ),
                );
              },
            );
          }

          if (provider.collectionsError != null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(
                    Icons.error_outline,
                    size: 48,
                    color: AppTheme.error,
                  ),
                  const SizedBox(height: 16),
                  Text(
                    provider.collectionsError!,
                    style: const TextStyle(color: AppTheme.error),
                  ),
                  TextButton(
                    onPressed: () => provider.fetchCollections(),
                    child: const Text('Coba Lagi'),
                  ),
                ],
              ),
            );
          }

          if (provider.collections.isEmpty) {
            return const Center(child: Text('Tidak ada kategori ditemukan.'));
          }

          return RefreshIndicator(
            onRefresh: provider.fetchCollections,
            color: AppTheme.accent,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Padding(
                  padding: EdgeInsets.fromLTRB(
                    ResponsiveHelper.horizontalPadding(context),
                    20,
                    ResponsiveHelper.horizontalPadding(context),
                    0,
                  ),
                  child: Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: AppTheme.surfaceContainerLowest,
                      borderRadius: AppTheme.radius16,
                      border: Border.all(color: AppTheme.outlineLight),
                    ),
                    child: const Row(
                      children: [
                        Icon(
                          Icons.style_outlined,
                          color: AppTheme.primary,
                          size: 18,
                        ),
                        SizedBox(width: 10),
                        Expanded(
                          child: Text(
                            'Pilih jalur koleksi yang paling dekat dengan kebutuhan, gaya, atau cerita yang ingin Anda tampilkan.',
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
                ),
                const SizedBox(height: 16),
                Expanded(
                  child: LayoutBuilder(
                    builder: (context, constraints) {
                      int crossAxisCount = 2;
                      if (constraints.maxWidth > 900) {
                        crossAxisCount = 4;
                      } else if (constraints.maxWidth > 600) {
                        crossAxisCount = 3;
                      }

                      return GridView.builder(
                        padding: EdgeInsets.all(
                          ResponsiveHelper.horizontalPadding(context),
                        ),
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: crossAxisCount,
                          crossAxisSpacing: 16,
                          mainAxisSpacing: 16,
                          childAspectRatio: 0.8,
                        ),
                        itemCount: provider.collections.length,
                        itemBuilder: (context, index) {
                          final collection = provider.collections[index];
                          final cardContent = Container(
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: AppTheme.radius16,
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.05),
                                  blurRadius: 10,
                                  offset: const Offset(0, 4),
                                ),
                              ],
                            ),
                            clipBehavior: Clip.antiAlias,
                            child: Stack(
                              fit: StackFit.expand,
                              children: [
                                Container(
                                  color: AppTheme.primary.withValues(
                                    alpha: 0.1,
                                  ),
                                ),
                                Container(
                                  decoration: BoxDecoration(
                                    gradient: LinearGradient(
                                      begin: Alignment.topCenter,
                                      end: Alignment.bottomCenter,
                                      colors: [
                                        Colors.transparent,
                                        Colors.black.withValues(alpha: 0.6),
                                      ],
                                    ),
                                  ),
                                ),
                                Positioned(
                                  top: 16,
                                  left: 16,
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 10,
                                      vertical: 6,
                                    ),
                                    decoration: BoxDecoration(
                                      color: Colors.white.withValues(
                                        alpha: 0.16,
                                      ),
                                      borderRadius: AppTheme.radius12,
                                      border: Border.all(
                                        color: Colors.white.withValues(
                                          alpha: 0.18,
                                        ),
                                      ),
                                    ),
                                    child: const Text(
                                      'Koleksi Pilihan',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 11,
                                        fontWeight: FontWeight.w700,
                                      ),
                                    ),
                                  ),
                                ),
                                Positioned(
                                  bottom: 16,
                                  left: 16,
                                  right: 16,
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        collection.title,
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 16,
                                          color: Colors.white,
                                        ),
                                        maxLines: 2,
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                      if (collection.description.isNotEmpty)
                                        Padding(
                                          padding: const EdgeInsets.only(
                                            top: 4,
                                          ),
                                          child: Text(
                                            collection.description,
                                            style: const TextStyle(
                                              fontSize: 12,
                                              color: Colors.white70,
                                            ),
                                            maxLines: 2,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                        ),
                                      const SizedBox(height: 10),
                                      const Row(
                                        children: [
                                          Text(
                                            'Buka koleksi',
                                            style: TextStyle(
                                              color: Colors.white,
                                              fontSize: 12,
                                              fontWeight: FontWeight.w700,
                                            ),
                                          ),
                                          SizedBox(width: 6),
                                          Icon(
                                            Icons.arrow_forward,
                                            size: 14,
                                            color: Colors.white,
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          );

                          return BlurFade(
                            delay: Duration(milliseconds: 50 * (index % 10)),
                            child: GestureDetector(
                              onTap: () =>
                                  context.push('/shop/${collection.handle}'),
                              child: index == 0
                                  ? NeonGlowCard(
                                      intensity: 0.4,
                                      glowSpread: 2.0,
                                      child: cardContent,
                                    )
                                  : cardContent,
                            ),
                          );
                        },
                      );
                    },
                  ),
                ),
              ],
            ),
          ); // closes return RefreshIndicator
        }, // closes Consumer builder function
      ), // closes body: Consumer
    ); // closes Scaffold
  }
}
