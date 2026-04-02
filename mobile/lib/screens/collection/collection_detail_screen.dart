import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/collection_provider.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/product/product_card.dart';
import '../../widgets/common/loading_shimmer.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class CollectionDetailScreen extends StatefulWidget {
  final String handle;

  const CollectionDetailScreen({super.key, required this.handle});

  @override
  State<CollectionDetailScreen> createState() => _CollectionDetailScreenState();
}

class _CollectionDetailScreenState extends State<CollectionDetailScreen> {
  String _sortValue = 'RELEVANCE';
  bool _reverse = false;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  void _fetchData() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<CollectionProvider>().fetchCollectionDetails(
        widget.handle,
        sortKey: _sortValue,
        reverse: _reverse,
      );
    });
  }

  void _showSortSheet() {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return SafeArea(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Padding(
                padding: EdgeInsets.all(
                  ResponsiveHelper.horizontalPadding(context),
                ),
                child: const Text(
                  'Urutkan Berdasarkan',
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                ),
              ),
              ListTile(
                title: const Text('Relevansi'),
                trailing: _sortValue == 'RELEVANCE'
                    ? const Icon(Icons.check, color: AppTheme.accent)
                    : null,
                onTap: () {
                  setState(() {
                    _sortValue = 'RELEVANCE';
                    _reverse = false;
                  });
                  _fetchData();
                  Navigator.pop(context);
                },
              ),
              ListTile(
                title: const Text('Harga Terendah'),
                trailing: _sortValue == 'PRICE' && !_reverse
                    ? const Icon(Icons.check, color: AppTheme.accent)
                    : null,
                onTap: () {
                  setState(() {
                    _sortValue = 'PRICE';
                    _reverse = false;
                  });
                  _fetchData();
                  Navigator.pop(context);
                },
              ),
              ListTile(
                title: const Text('Harga Tertinggi'),
                trailing: _sortValue == 'PRICE' && _reverse
                    ? const Icon(Icons.check, color: AppTheme.accent)
                    : null,
                onTap: () {
                  setState(() {
                    _sortValue = 'PRICE';
                    _reverse = true;
                  });
                  _fetchData();
                  Navigator.pop(context);
                },
              ),
              ListTile(
                title: const Text('Terbaru'),
                trailing: _sortValue == 'CREATED_AT' && _reverse
                    ? const Icon(Icons.check, color: AppTheme.accent)
                    : null,
                onTap: () {
                  setState(() {
                    _sortValue = 'CREATED_AT';
                    _reverse = true;
                  });
                  _fetchData();
                  Navigator.pop(context);
                },
              ),
            ],
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<CollectionProvider>(
      builder: (context, provider, child) {
        final collection = provider.currentCollection;
        return MitologiScaffold(
          title: collection?.title ?? 'Kategori',
          subtitle:
              'Jelajahi produk dalam koleksi ini dengan filter dan urutan yang lebih rapi.',
          showLogo: false,
          bodyPadding: EdgeInsets.zero,
          leading: IconButton(
            tooltip: 'Back',
            icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
            onPressed: () {
              provider.clearCurrentCollection();
              context.popOrGoShop();
            },
          ),
          actions: [
            IconButton(
              tooltip: 'Sort',
              icon: const Icon(Icons.sort, color: AppTheme.primary),
              onPressed: _showSortSheet,
            ),
          ],
          body: provider.isLoadingCollectionDetails
              ? LayoutBuilder(
                  builder: (context, constraints) {
                    int crossAxisCount = 2;
                    double childAspectRatio = 0.58;
                    if (constraints.maxWidth > 900) {
                      crossAxisCount = 4;
                      childAspectRatio = 0.65;
                    } else if (constraints.maxWidth > 600) {
                      crossAxisCount = 3;
                      childAspectRatio = 0.62;
                    }
                    return GridView.builder(
                      // Loading Shimmer
                      padding: EdgeInsets.all(
                        ResponsiveHelper.horizontalPadding(context),
                      ),
                      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: crossAxisCount,
                        childAspectRatio: childAspectRatio,
                        crossAxisSpacing: 16,
                        mainAxisSpacing: 16,
                      ),
                      itemCount: 4,
                      itemBuilder: (context, index) => const LoadingShimmer(
                        width: double.infinity,
                        height: 260,
                        borderRadius: 16,
                      ),
                    );
                  },
                )
              : provider.collectionDetailsError != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(
                        Icons.error_outline,
                        size: 48,
                        color: AppTheme.error,
                      ),
                      const SizedBox(height: 16),
                      const Text('Gagal memuat kategori'),
                      TextButton(
                        onPressed: _fetchData,
                        child: const Text('Coba Lagi'),
                      ),
                    ],
                  ),
                )
              : provider.currentCollectionProducts.isEmpty
              ? const Center(
                  child: Text('Belum ada produk untuk kategori ini.'),
                )
              : RefreshIndicator(
                  onRefresh: () async => _fetchData(),
                  color: AppTheme.primary,
                  child: LayoutBuilder(
                    builder: (context, constraints) {
                      int crossAxisCount = 2;
                      double childAspectRatio = 0.58;
                      if (constraints.maxWidth > 900) {
                        crossAxisCount = 4;
                        childAspectRatio = 0.65;
                      } else if (constraints.maxWidth > 600) {
                        crossAxisCount = 3;
                        childAspectRatio = 0.62;
                      }
                      return GridView.builder(
                        padding: EdgeInsets.all(
                          ResponsiveHelper.horizontalPadding(context),
                        ),
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: crossAxisCount,
                          childAspectRatio: childAspectRatio,
                          crossAxisSpacing: 16,
                          mainAxisSpacing: 16,
                        ),
                        itemCount: provider.currentCollectionProducts.length,
                        itemBuilder: (context, index) {
                          return BlurFade(
                            delay: Duration(milliseconds: 50 * (index % 10)),
                            child: ProductCard(
                              product:
                                  provider.currentCollectionProducts[index],
                            ),
                          );
                        },
                      );
                    },
                  ),
                ),
        );
      },
    );
  }
}
