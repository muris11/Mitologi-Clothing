import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:go_router/go_router.dart';

import '../../models/product.dart';
import '../../models/product_variant.dart';
import '../../models/review.dart';
import '../../providers/cart_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../utils/price_formatter.dart';
import '../../config/theme.dart';
import '../../widgets/animations/animations.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/product/product_card.dart';
import '../../services/product_service.dart';
import '../../utils/responsive_helper.dart';

/// Redesigned Product Detail Screen - Premium Product Page
///
/// Features:
/// - Product detail skeleton loading
/// - Sliver app bar with parallax
/// - Enhanced image gallery
/// - Variant selection with animations
/// - Review section
/// - Related products carousel
class ProductDetailScreen extends StatefulWidget {
  final String handle;

  const ProductDetailScreen({super.key, required this.handle});

  @override
  State<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends State<ProductDetailScreen> {
  Product? _product;
  ProductVariant? _selectedVariant;
  final Map<String, String> _selectedOptions = {};
  bool _isLoading = true;
  String? _error;
  List<ReviewItem> _reviews = [];
  bool _isLoadingReviews = false;
  List<Product> _relatedProducts = [];

  // Image Gallery
  final PageController _pageController = PageController();
  int _currentImageIndex = 0;

  @override
  void initState() {
    super.initState();
    _loadProduct();
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  Future<void> _loadProduct() async {
    setState(() => _isLoading = true);

    try {
      final product = await ProductService().getProductDetail(widget.handle);

      setState(() {
        _product = product;
        _isLoading = false;
      });

      // Load related data in parallel
      _loadRelatedData(product);
    } catch (e) {
      setState(() {
        _error = 'Gagal memuat produk: $e';
        _isLoading = false;
      });
    }
  }

  Future<void> _loadRelatedData(Product product) async {
    // Load reviews
    _loadReviews();

    // Load related products
    try {
      final related = await ProductService().getRelatedProducts(widget.handle);
      setState(() => _relatedProducts = related);
    } catch (e) {
      // Silently fail for related products
    }
  }

  Future<void> _loadReviews() async {
    setState(() => _isLoadingReviews = true);

    try {
      final reviews = await ProductService().getProductReviews(widget.handle);
      setState(() {
        _reviews = reviews;
        _isLoadingReviews = false;
      });
    } catch (e) {
      setState(() => _isLoadingReviews = false);
    }
  }

  void _onVariantSelected(String optionName, String value) {
    setState(() {
      _selectedOptions[optionName] = value;
      _updateSelectedVariant();
    });
  }

  void _updateSelectedVariant() {
    if (_product == null) return;

    // Find variant matching all selected options
    for (final variant in _product!.variants) {
      bool matches = true;
      for (final option in variant.selectedOptions) {
        if (_selectedOptions[option.name] != option.value) {
          matches = false;
          break;
        }
      }

      if (matches &&
          _selectedOptions.length == variant.selectedOptions.length) {
        setState(() => _selectedVariant = variant);
        return;
      }
    }

    setState(() => _selectedVariant = null);
  }

  void _addToCart() {
    if (_product == null) return;
    if (_selectedVariant == null && _product!.variants.length > 1) {
      _showSelectVariantSnackBar();
      return;
    }

    final cartProvider = context.read<CartProvider>();
    final variant = _selectedVariant ?? _product!.variants.first;

    cartProvider.addToCart(variant.id, 1);

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            const Icon(Icons.check_circle, color: AppTheme.success),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                '${_product!.title} ditambahkan ke keranjang',
                style: const TextStyle(fontWeight: FontWeight.w500),
              ),
            ),
          ],
        ),
        backgroundColor: AppTheme.surface,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: AppTheme.radius12),
        action: SnackBarAction(
          label: 'Lihat',
          onPressed: () => context.push('/cart'),
        ),
      ),
    );
  }

  void _showSelectVariantSnackBar() {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: const Row(
          children: [
            Icon(Icons.info_outline, color: AppTheme.warning),
            SizedBox(width: 12),
            Text('Pilih varian produk terlebih dahulu'),
          ],
        ),
        backgroundColor: AppTheme.surface,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: AppTheme.radius12),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const ProductDetailSkeleton();
    }

    if (_error != null || _product == null) {
      return _buildErrorState();
    }

    return _buildProductDetail();
  }

  Widget _buildErrorState() {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppTheme.errorLight,
                  borderRadius: AppTheme.radius22,
                ),
                child: const Icon(
                  Icons.error_outline,
                  size: 48,
                  color: AppTheme.error,
                ),
              ),
              const SizedBox(height: 24),
              Text(
                _error ?? 'Produk tidak ditemukan',
                style: Theme.of(
                  context,
                ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
              ),
              const SizedBox(height: 24),
              ElevatedButton.icon(
                onPressed: () {
                  context.pop();
                },
                icon: const Icon(Icons.arrow_back),
                label: const Text('Kembali'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProductDetail() {
    final product = _product!;
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Scaffold(
      body: CustomScrollView(
        slivers: [
          // Sliver App Bar with Image Gallery
          SliverAppBar(
            expandedHeight: MediaQuery.of(context).size.height * 0.5,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(background: _buildImageGallery()),
            leading: Container(
              margin: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppTheme.surface.withValues(alpha: 0.9),
                borderRadius: AppTheme.radius12,
                boxShadow: AppTheme.shadowSoft,
              ),
              child: IconButton(
                icon: const Icon(Icons.arrow_back),
                onPressed: () => context.pop(),
              ),
            ),
            actions: [
              Container(
                margin: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: AppTheme.surface.withValues(alpha: 0.9),
                  borderRadius: AppTheme.radius12,
                  boxShadow: AppTheme.shadowSoft,
                ),
                child: IconButton(
                  icon: const Icon(Icons.share_outlined),
                  onPressed: () {
                    // Share product
                  },
                ),
              ),
              Container(
                margin: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: AppTheme.surface.withValues(alpha: 0.9),
                  borderRadius: AppTheme.radius12,
                  boxShadow: AppTheme.shadowSoft,
                ),
                child: Consumer<WishlistProvider>(
                  builder: (context, wishlist, _) {
                    final isInWishlist = wishlist.isWishlisted(product.id);
                    return IconButton(
                      icon: Icon(
                        isInWishlist ? Icons.favorite : Icons.favorite_border,
                        color: isInWishlist ? AppTheme.error : null,
                      ),
                      onPressed: () {
                        wishlist.toggleWishlist(product);
                      },
                    );
                  },
                ),
              ),
            ],
          ),

          // Product Info
          SliverToBoxAdapter(
            child: FadeInUp(
              child: Container(
                padding: EdgeInsets.all(horizontalPadding),
                decoration: const BoxDecoration(
                  color: AppTheme.surface,
                  borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(AppTheme.radiusBottomSheet),
                    topRight: Radius.circular(AppTheme.radiusBottomSheet),
                  ),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Category Badge
                    if (product.tags.isNotEmpty)
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 6,
                        ),
                        decoration: BoxDecoration(
                          color: AppTheme.primary.withValues(alpha: 0.1),
                          borderRadius: AppTheme.radius8,
                        ),
                        child: Text(
                          product.tags.first,
                          style: Theme.of(context).textTheme.labelSmall
                              ?.copyWith(
                                color: AppTheme.primary,
                                fontWeight: FontWeight.w700,
                                letterSpacing: 0.5,
                              ),
                        ),
                      ),

                    const SizedBox(height: 16),

                    // Title
                    Text(
                      product.title,
                      style: Theme.of(context).textTheme.headlineSmall
                          ?.copyWith(fontWeight: FontWeight.w800),
                    ),

                    const SizedBox(height: 16),

                    // Price
                    Row(
                      children: [
                        Text(
                          _selectedVariant != null
                              ? PriceFormatter.formatStringIDR(
                                  _selectedVariant!.price.amount,
                                )
                              : PriceFormatter.formatStringIDR(
                                  product.priceRange.minVariantPrice.amount,
                                ),
                          style: Theme.of(context).textTheme.titleLarge
                              ?.copyWith(
                                fontWeight: FontWeight.w800,
                                color: AppTheme.primary,
                              ),
                        ),
                      ],
                    ),

                    const SizedBox(height: 16),

                    // Rating
                    Row(
                      children: [
                        ...List.generate(5, (index) {
                          return Icon(
                            index < (product.averageRating?.floor() ?? 0)
                                ? Icons.star
                                : Icons.star_border,
                            size: 20,
                            color: AppTheme.accent,
                          );
                        }),
                        const SizedBox(width: 8),
                        Text(
                          product.averageRating?.toStringAsFixed(1) ?? '0.0',
                          style: Theme.of(context).textTheme.bodyMedium
                              ?.copyWith(fontWeight: FontWeight.w700),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          '(${product.totalReviews ?? 0} ulasan)',
                          style: Theme.of(context).textTheme.bodyMedium
                              ?.copyWith(color: AppTheme.onSurfaceVariant),
                        ),
                      ],
                    ),

                    const SizedBox(height: 24),

                    // Variant Selection
                    if (product.options.isNotEmpty) _buildVariantSelection(),

                    const SizedBox(height: 24),

                    // Description
                    if (product.descriptionHtml.isNotEmpty) _buildDescription(),

                    const SizedBox(height: 32),

                    // Reviews Section
                    _buildReviewsSection(),

                    const SizedBox(height: 32),

                    // Related Products
                    if (_relatedProducts.isNotEmpty) _buildRelatedProducts(),

                    // Bottom spacing for floating button
                    const SizedBox(height: 100),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),

      // Floating Add to Cart Button
      bottomNavigationBar: _buildFloatingCartButton(),
    );
  }

  Widget _buildImageGallery() {
    final product = _product!;
    final images = product.images.isNotEmpty
        ? product.images.map((img) => img.url).toList()
        : [product.featuredImage.url].whereType<String>().toList();

    if (images.isEmpty) {
      return Container(
        color: AppTheme.muted,
        child: const Center(
          child: Icon(
            Icons.image_not_supported_outlined,
            size: 64,
            color: AppTheme.onSurfaceMuted,
          ),
        ),
      );
    }

    return Stack(
      fit: StackFit.expand,
      children: [
        // PageView for images
        PageView.builder(
          controller: _pageController,
          onPageChanged: (index) {
            setState(() => _currentImageIndex = index);
          },
          itemCount: images.length,
          itemBuilder: (context, index) {
            return Image.network(
              images[index],
              fit: BoxFit.cover,
              loadingBuilder: (context, child, loadingProgress) {
                if (loadingProgress == null) return child;
                return Container(
                  color: AppTheme.muted,
                  child: Center(
                    child: CircularProgressIndicator(
                      value: loadingProgress.expectedTotalBytes != null
                          ? loadingProgress.cumulativeBytesLoaded /
                                loadingProgress.expectedTotalBytes!
                          : null,
                      strokeWidth: 2,
                      color: AppTheme.primary,
                    ),
                  ),
                );
              },
              errorBuilder: (context, error, stackTrace) {
                return Container(
                  color: AppTheme.muted,
                  child: const Center(
                    child: Icon(
                      Icons.image_not_supported_outlined,
                      size: 64,
                      color: AppTheme.onSurfaceMuted,
                    ),
                  ),
                );
              },
            );
          },
        ),

        // Image indicators
        if (images.length > 1)
          Positioned(
            bottom: 16,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: images.asMap().entries.map((entry) {
                final isActive = _currentImageIndex == entry.key;
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  width: isActive ? 24 : 8,
                  height: 8,
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(4),
                    color: isActive
                        ? AppTheme.surface
                        : AppTheme.surface.withValues(alpha: 0.5),
                  ),
                );
              }).toList(),
            ),
          ),
      ],
    );
  }

  Widget _buildVariantSelection() {
    final product = _product!;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: product.options.map((option) {
        return Padding(
          padding: const EdgeInsets.only(bottom: 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                option.name,
                style: Theme.of(
                  context,
                ).textTheme.titleSmall?.copyWith(fontWeight: FontWeight.w700),
              ),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                children: option.values.map((value) {
                  final isSelected = _selectedOptions[option.name] == value;

                  return ChoiceChip(
                    label: Text(value),
                    selected: isSelected,
                    onSelected: (_) => _onVariantSelected(option.name, value),
                    selectedColor: AppTheme.primary,
                    backgroundColor: AppTheme.surface,
                    labelStyle: TextStyle(
                      color: isSelected
                          ? AppTheme.onPrimary
                          : AppTheme.onSurface,
                      fontWeight: isSelected
                          ? FontWeight.w700
                          : FontWeight.w500,
                    ),
                    shape: RoundedRectangleBorder(
                      borderRadius: AppTheme.radius8,
                      side: BorderSide(
                        color: isSelected ? AppTheme.primary : AppTheme.outline,
                      ),
                    ),
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 8,
                    ),
                  );
                }).toList(),
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget _buildDescription() {
    final product = _product!;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Deskripsi',
          style: Theme.of(
            context,
          ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
        ),
        const SizedBox(height: 12),
        Html(
          data: product.descriptionHtml,
          style: {
            'body': Style(
              fontSize: FontSize(14),
              color: AppTheme.onSurfaceVariant,
              lineHeight: LineHeight(1.6),
            ),
            'p': Style(margin: Margins.only(bottom: 12)),
            'h1, h2, h3, h4, h5, h6': Style(
              fontWeight: FontWeight.w700,
              color: AppTheme.onSurface,
              margin: Margins.only(bottom: 8, top: 16),
            ),
            'ul, ol': Style(
              margin: Margins.only(bottom: 12),
              padding: HtmlPaddings.only(left: 20),
            ),
            'li': Style(margin: Margins.only(bottom: 4)),
          },
        ),
      ],
    );
  }

  Widget _buildReviewsSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              'Ulasan',
              style: Theme.of(
                context,
              ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
            ),
            if (_reviews.isNotEmpty)
              TextButton(
                onPressed: () {
                  // Show all reviews
                },
                child: const Text('Lihat Semua'),
              ),
          ],
        ),
        const SizedBox(height: 16),

        if (_isLoadingReviews)
          const Center(
            child: Padding(
              padding: EdgeInsets.all(32),
              child: CircularProgressIndicator(strokeWidth: 2),
            ),
          )
        else if (_reviews.isEmpty)
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: AppTheme.surfaceContainerLow,
              borderRadius: AppTheme.radius16,
            ),
            child: Row(
              children: [
                const Icon(
                  Icons.rate_review_outlined,
                  color: AppTheme.onSurfaceMuted,
                  size: 32,
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Belum ada ulasan',
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                      Text(
                        'Jadilah yang pertama memberikan ulasan',
                        style: Theme.of(context).textTheme.bodySmall?.copyWith(
                          color: AppTheme.onSurfaceVariant,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          )
        else
          Column(
            children: _reviews.take(3).map((review) {
              return _buildReviewCard(review);
            }).toList(),
          ),
      ],
    );
  }

  Widget _buildReviewCard(ReviewItem review) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        borderRadius: AppTheme.radius16,
        border: Border.all(color: AppTheme.outlineLight),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  color: AppTheme.primary.withValues(alpha: 0.1),
                  shape: BoxShape.circle,
                ),
                child: Center(
                  child: Text(
                    review.userName[0].toUpperCase(),
                    style: Theme.of(context).textTheme.titleSmall?.copyWith(
                      color: AppTheme.primary,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      review.userName,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    Row(
                      children: List.generate(5, (index) {
                        return Icon(
                          index < review.rating
                              ? Icons.star
                              : Icons.star_border,
                          size: 14,
                          color: AppTheme.accent,
                        );
                      }),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            review.comment,
            style: Theme.of(
              context,
            ).textTheme.bodyMedium?.copyWith(color: AppTheme.onSurfaceVariant),
          ),
        ],
      ),
    );
  }

  Widget _buildRelatedProducts() {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.only(left: horizontalPadding),
          child: Text(
            'Produk Terkait',
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 280,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
            itemCount: _relatedProducts.length,
            separatorBuilder: (_, index) => const SizedBox(width: 12),
            itemBuilder: (context, index) {
              return SizedBox(
                width: 180,
                child: FadeInUp(
                  delay: Duration(milliseconds: index * 50),
                  child: ProductCard(product: _relatedProducts[index]),
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget? _buildFloatingCartButton() {
    final product = _product;
    if (product == null) return null;

    final variant =
        _selectedVariant ??
        (product.variants.isNotEmpty ? product.variants.first : null);
    if (variant == null) return null;

    final isAvailable = variant.availableForSale;

    return Container(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      decoration: BoxDecoration(
        color: AppTheme.surface,
        borderRadius: const BorderRadius.only(
          topLeft: Radius.circular(AppTheme.radiusBottomSheet),
          topRight: Radius.circular(AppTheme.radiusBottomSheet),
        ),
        boxShadow: AppTheme.shadowSoft,
      ),
      child: SafeArea(
        top: false,
        child: Row(
          children: [
            // Price
            Expanded(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Total',
                    style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      color: AppTheme.onSurfaceVariant,
                    ),
                  ),
                  Text(
                    PriceFormatter.formatStringIDR(variant.price.amount),
                    style: Theme.of(context).textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.w800,
                      color: AppTheme.primary,
                    ),
                  ),
                ],
              ),
            ),

            // Add to Cart Button
            Expanded(
              flex: 2,
              child: PressableButton(
                onPressed: isAvailable ? _addToCart : null,
                backgroundColor: isAvailable
                    ? AppTheme.primary
                    : AppTheme.outline,
                foregroundColor: AppTheme.onPrimary,
                padding: const EdgeInsets.symmetric(vertical: 16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.shopping_bag_outlined, size: 20),
                    const SizedBox(width: 8),
                    Text(
                      isAvailable ? 'Tambah ke Keranjang' : 'Stok Habis',
                      style: const TextStyle(
                        fontSize: 15,
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
    );
  }
}
