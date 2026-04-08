import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../providers/product_provider.dart';
import '../../widgets/animations/animations.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/product/product_card.dart';
import '../../utils/responsive_helper.dart';

/// Redesigned Shop Screen - Premium Product Catalog
///
/// Features:
/// - Skeleton loading for grid
/// - Staggered animations on scroll
/// - Responsive product grid (2-4 columns)
/// - Advanced filtering UI
/// - Premium search experience
class ShopScreen extends StatefulWidget {
  final String? initialCategory;
  final String? initialSort;

  const ShopScreen({super.key, this.initialCategory, this.initialSort});

  @override
  State<ShopScreen> createState() => _ShopScreenState();
}

class _ShopScreenState extends State<ShopScreen> {
  final ScrollController _scrollController = ScrollController();
  final TextEditingController _searchController = TextEditingController();
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _initializeData();
    _scrollController.addListener(_onScroll);
  }

  void _initializeData() {
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      final provider = context.read<ProductProvider>();

      // Apply initial filters if provided
      if (widget.initialCategory != null) {
        provider.setCategory(widget.initialCategory);
      }
      if (widget.initialSort != null) {
        provider.setSortKey(widget.initialSort!);
      }

      // Fetch data
      await provider.fetchCategories();
      await provider.fetchProducts(refresh: true);

      setState(() => _isLoading = false);
    });
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      context.read<ProductProvider>().loadMore();
    }
  }

  @override
  void dispose() {
    _scrollController.dispose();
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Katalog',
      subtitle: 'Temukan koleksi premium dari Mitologi Clothing',
      bodyPadding: EdgeInsets.zero,
      body: _isLoading ? _buildSkeletonState() : _buildContent(),
    );
  }

  Widget _buildSkeletonState() {
    return Column(
      children: [
        // Search skeleton
        _buildSearchSkeleton(),
        // Filter chips skeleton
        _buildFilterChipsSkeleton(),
        // Product grid skeleton
        const Expanded(child: ProductGridSkeleton(itemCount: 8)),
      ],
    );
  }

  Widget _buildSearchSkeleton() {
    return Padding(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      child: Container(
        height: 56,
        decoration: BoxDecoration(
          color: AppTheme.surface,
          borderRadius: AppTheme.radius16,
          border: Border.all(color: AppTheme.outline),
        ),
      ),
    );
  }

  Widget _buildFilterChipsSkeleton() {
    return Padding(
      padding: EdgeInsets.only(
        left: ResponsiveHelper.horizontalPadding(context),
        bottom: 16,
      ),
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: Row(
          children: List.generate(
            5,
            (index) => Container(
              width: 80,
              height: 36,
              margin: const EdgeInsets.only(right: 8),
              decoration: BoxDecoration(
                color: AppTheme.surface,
                borderRadius: AppTheme.radius8,
                border: Border.all(color: AppTheme.outline),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildContent() {
    return Consumer<ProductProvider>(
      builder: (context, provider, child) {
        return CustomScrollView(
          controller: _scrollController,
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            // Search Bar
            SliverToBoxAdapter(child: FadeInUp(child: _buildSearchBar())),

            // Filter Chips
            SliverToBoxAdapter(
              child: FadeInUp(
                delay: const Duration(milliseconds: 100),
                child: _buildFilterChips(),
              ),
            ),

            // Results Count & Sort
            SliverToBoxAdapter(
              child: FadeInUp(
                delay: const Duration(milliseconds: 150),
                child: _buildResultsHeader(),
              ),
            ),

            // Product Grid
            SliverPadding(
              padding: EdgeInsets.symmetric(
                horizontal: ResponsiveHelper.horizontalPadding(context),
              ),
              sliver: _buildProductGrid(provider),
            ),

            // Loading More Indicator
            if (provider.isLoadingMore)
              const SliverToBoxAdapter(
                child: Padding(
                  padding: EdgeInsets.all(16.0),
                  child: Center(
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      color: AppTheme.primary,
                    ),
                  ),
                ),
              ),

            // Bottom Spacing
            const SliverToBoxAdapter(child: SizedBox(height: 32)),
          ],
        );
      },
    );
  }

  Widget _buildSearchBar() {
    return Padding(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      child: TextField(
        controller: _searchController,
        onSubmitted: (query) {
          context.read<ProductProvider>().setSearchQuery(query);
        },
        decoration: InputDecoration(
          hintText: 'Cari produk...',
          prefixIcon: const Icon(Icons.search),
          suffixIcon: _searchController.text.isNotEmpty
              ? IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    _searchController.clear();
                    context.read<ProductProvider>().setSearchQuery('');
                  },
                )
              : null,
          filled: true,
          fillColor: AppTheme.cream,
        ),
      ),
    );
  }

  Widget _buildFilterChips() {
    final provider = context.watch<ProductProvider>();
    final categories = provider.categories;

    return Padding(
      padding: EdgeInsets.only(
        left: ResponsiveHelper.horizontalPadding(context),
        bottom: 16,
      ),
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: Row(
          children: [
            // "All" chip
            _buildCategoryChip(
              label: 'Semua',
              isSelected: provider.selectedCategory == null,
              onTap: () => provider.setCategory(null),
            ),

            // Category chips
            ...categories.map((category) {
              final isSelected = provider.selectedCategory == category.handle;
              return _buildCategoryChip(
                label: category.name,
                isSelected: isSelected,
                onTap: () => provider.setCategory(category.handle),
              );
            }),
          ],
        ),
      ),
    );
  }

  Widget _buildCategoryChip({
    required String label,
    required bool isSelected,
    required VoidCallback onTap,
  }) {
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ChoiceChip(
        label: Text(label),
        selected: isSelected,
        onSelected: (_) => onTap(),
        selectedColor: AppTheme.primary,
        backgroundColor: AppTheme.surface,
        labelStyle: TextStyle(
          color: isSelected ? AppTheme.onPrimary : AppTheme.onSurface,
          fontWeight: isSelected ? FontWeight.w700 : FontWeight.w500,
          fontSize: 13,
        ),
        shape: RoundedRectangleBorder(
          borderRadius: AppTheme.radius8,
          side: BorderSide(
            color: isSelected ? AppTheme.primary : AppTheme.outline,
          ),
        ),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      ),
    );
  }

  Widget _buildResultsHeader() {
    final provider = context.watch<ProductProvider>();
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: horizontalPadding, vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          // Results count
          Text(
            'Menampilkan ${provider.products.length} produk',
            style: Theme.of(
              context,
            ).textTheme.bodyMedium?.copyWith(color: AppTheme.onSurfaceVariant),
          ),

          // Sort button
          TextButton.icon(
            onPressed: _showSortBottomSheet,
            icon: const Icon(Icons.sort, size: 18),
            label: Text(_getSortLabel(provider.sortKey)),
            style: TextButton.styleFrom(
              foregroundColor: AppTheme.primary,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            ),
          ),
        ],
      ),
    );
  }

  String _getSortLabel(String sortKey) {
    switch (sortKey) {
      case 'latest':
        return 'Terbaru';
      case 'trending':
        return 'Trending';
      case 'price-asc':
        return 'Harga: Rendah ke Tinggi';
      case 'price-desc':
        return 'Harga: Tinggi ke Rendah';
      case 'best-selling':
        return 'Terlaris';
      default:
        return 'Urutkan';
    }
  }

  Widget _buildProductGrid(ProductProvider provider) {
    final columns = ResponsiveHelper.gridColumns(context);
    final spacing = ResponsiveHelper.gridSpacing(context);

    if (provider.products.isEmpty && !provider.isLoading) {
      return SliverToBoxAdapter(child: _buildEmptyState());
    }

    return SliverGrid(
      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: columns,
        childAspectRatio: 0.68,
        crossAxisSpacing: spacing,
        mainAxisSpacing: spacing,
      ),
      delegate: SliverChildBuilderDelegate((context, index) {
        final product = provider.products[index];
        return FadeInUp(
          delay: Duration(milliseconds: (index % columns) * 100),
          child: ProductCard(product: product),
        );
      }, childCount: provider.products.length),
    );
  }

  Widget _buildEmptyState() {
    return Container(
      padding: const EdgeInsets.all(48),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: AppTheme.primary.withValues(alpha: 0.05),
              shape: BoxShape.circle,
            ),
            child: Icon(
              Icons.search_off,
              size: 48,
              color: AppTheme.primary.withValues(alpha: 0.3),
            ),
          ),
          const SizedBox(height: 24),
          Text(
            'Produk tidak ditemukan',
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
          ),
          const SizedBox(height: 8),
          Text(
            'Coba ubah kata kunci pencarian atau filter',
            style: Theme.of(
              context,
            ).textTheme.bodyMedium?.copyWith(color: AppTheme.onSurfaceVariant),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            onPressed: () {
              _searchController.clear();
              context.read<ProductProvider>().resetFilters();
            },
            icon: const Icon(Icons.refresh),
            label: const Text('Reset Filter'),
          ),
        ],
      ),
    );
  }

  void _showSortBottomSheet() {
    final provider = context.read<ProductProvider>();

    showModalBottomSheet(
      context: context,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(
          top: Radius.circular(AppTheme.radiusBottomSheet),
        ),
      ),
      builder: (ctx) {
        return Container(
          padding: const EdgeInsets.symmetric(vertical: 24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Handle
              Container(
                width: 40,
                height: 4,
                margin: const EdgeInsets.only(bottom: 24),
                decoration: BoxDecoration(
                  color: AppTheme.muted,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),

              // Title
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                child: Row(
                  children: [
                    const Icon(Icons.sort, color: AppTheme.primary),
                    const SizedBox(width: 12),
                    Text(
                      'Urutkan Produk',
                      style: Theme.of(ctx).textTheme.titleLarge?.copyWith(
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // Sort options
              _buildSortOption(
                provider,
                'latest',
                'Terbaru',
                Icons.new_releases_outlined,
              ),
              _buildSortOption(
                provider,
                'best-selling',
                'Terlaris',
                Icons.trending_up,
              ),
              _buildSortOption(
                provider,
                'price-asc',
                'Harga: Rendah ke Tinggi',
                Icons.arrow_upward,
              ),
              _buildSortOption(
                provider,
                'price-desc',
                'Harga: Tinggi ke Rendah',
                Icons.arrow_downward,
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildSortOption(
    ProductProvider provider,
    String value,
    String label,
    IconData icon,
  ) {
    final isSelected = provider.sortKey == value;

    return InkWell(
      onTap: () {
        provider.setSortKey(value);
        Navigator.pop(context);
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.primary.withValues(alpha: 0.05) : null,
        ),
        child: Row(
          children: [
            Icon(
              icon,
              color: isSelected ? AppTheme.primary : AppTheme.onSurfaceVariant,
              size: 20,
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Text(
                label,
                style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  fontWeight: isSelected ? FontWeight.w700 : FontWeight.w500,
                  color: isSelected ? AppTheme.primary : AppTheme.onSurface,
                ),
              ),
            ),
            if (isSelected)
              const Icon(Icons.check, color: AppTheme.primary, size: 20),
          ],
        ),
      ),
    );
  }
}
