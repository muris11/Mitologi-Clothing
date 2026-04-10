import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../models/product.dart';
import '../../models/product_variant.dart';
import '../../models/review.dart';
import '../../providers/cart_provider.dart';
import '../../providers/wishlist_provider.dart';
import '../../utils/price_formatter.dart';
import '../../config/theme.dart';
import '../../widgets/animations/animations.dart';
import '../../services/product_service.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/animations/shimmer_button.dart';
import 'widgets/product_image_gallery.dart';
import 'widgets/product_variant_selector.dart';
import 'widgets/product_description.dart';
import 'widgets/product_reviews_section.dart';
import 'widgets/product_related_products.dart';
import '../../widgets/skeleton/product_detail_skeleton.dart';

/// Redesigned Product Detail Screen - Premium Product Page
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

  @override
  void initState() {
    super.initState();
    _loadProduct();
  }

  Future<void> _loadProduct() async {
    setState(() => _isLoading = true);

    try {
      final product = await ProductService().getProductDetail(widget.handle);

      if (!mounted) return;
      setState(() {
        _product = product;
        _isLoading = false;
      });

      // Load related data in parallel
      _loadRelatedData(product);
    } on Exception catch (e) {
      if (!mounted) return;
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
      if (!mounted) return;
      setState(() => _relatedProducts = related);
    } on Exception {
      // Silently fail for related products
    }
  }

  Future<void> _loadReviews() async {
    setState(() => _isLoadingReviews = true);

    try {
      final reviews = await ProductService().getProductReviews(widget.handle);
      if (!mounted) return;
      setState(() {
        _reviews = reviews;
        _isLoadingReviews = false;
      });
    } on Exception {
      if (!mounted) return;
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

    final product = _product!;
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    // Get current variant
    final variant = _selectedVariant ?? (product.variants.isNotEmpty ? product.variants.first : null);
    final isAvailable = variant?.availableForSale ?? false;

    return Scaffold(
      body: CustomScrollView(
        slivers: [
          // Sliver App Bar with Image Gallery
          SliverAppBar(
            expandedHeight: MediaQuery.of(context).size.height * 0.5,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              background: ProductImageGallery(product: product),
            ),
            leading: _buildCircleAction(
              icon: Icons.arrow_back,
              onPressed: () => context.pop(),
              tooltip: 'Kembali',
              semantics: 'Kembali ke halaman sebelumnya',
            ),
            actions: [
              _buildCircleAction(
                icon: Icons.share_outlined,
                onPressed: () {
                  // Share product
                },
                tooltip: 'Bagikan',
                semantics: 'Bagikan produk ini',
              ),
              Consumer<WishlistProvider>(
                builder: (context, wishlist, _) {
                  final isInWishlist = wishlist.isWishlisted(product.id);
                  return _buildCircleAction(
                    icon: isInWishlist ? Icons.favorite : Icons.favorite_border,
                    iconColor: isInWishlist ? AppTheme.error : null,
                    onPressed: () => wishlist.toggleWishlist(product),
                    tooltip: isInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist',
                    semantics: isInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist',
                  );
                },
              ),
              const SizedBox(width: 8),
            ],
          ),

                  // Product Info
          SliverToBoxAdapter(
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
                    FadeInUp(
                      delay: const Duration(milliseconds: 100),
                      child: _buildCategoryBadge(product.tags.first),
                    ),

                  const SizedBox(height: 16),

                  // Title
                  FadeInUp(
                    delay: const Duration(milliseconds: 200),
                    child: Text(
                      product.title,
                      style: Theme.of(context).textTheme.headlineSmall
                          ?.copyWith(
                            fontWeight: FontWeight.w900,
                            letterSpacing: -0.5,
                          ),
                    ),
                  ),

                  const SizedBox(height: 16),

                  // Price
                  FadeInUp(
                    delay: const Duration(milliseconds: 300),
                    child: _buildPriceSection(variant),
                  ),

                  const SizedBox(height: 16),

                  // Rating
                  FadeInUp(
                    delay: const Duration(milliseconds: 400),
                    child: _buildRatingSection(product),
                  ),

                  const SizedBox(height: 32),

                  // Variant Selection
                  FadeInUp(
                    delay: const Duration(milliseconds: 500),
                    child: ProductVariantSelector(
                      product: product,
                      selectedOptions: _selectedOptions,
                      onVariantSelected: _onVariantSelected,
                    ),
                  ),

                  const SizedBox(height: 32),

                  // Description
                  FadeInUp(
                    delay: const Duration(milliseconds: 600),
                    child: ProductDescription(product: product),
                  ),

                  const SizedBox(height: 32),

                  // Reviews Section
                  FadeInUp(
                    delay: const Duration(milliseconds: 700),
                    child: ProductReviewsSection(
                      reviews: _reviews,
                      isLoading: _isLoadingReviews,
                    ),
                  ),

                  const SizedBox(height: 32),

                  // Related Products
                  FadeInUp(
                    delay: const Duration(milliseconds: 800),
                    child: ProductRelatedProducts(products: _relatedProducts),
                  ),

                  // Bottom spacing for floating button
                  const SizedBox(height: 100),
                ],
              ),
            ),
          ),
        ],
      ),

      // Floating Add to Cart Button
      bottomNavigationBar: FadeInUp(
        delay: const Duration(milliseconds: 400),
        child: _buildFloatingCartButton(variant, isAvailable),
      ),
    );
  }

  Widget _buildCircleAction({
    required IconData icon,
    required VoidCallback onPressed,
    required String tooltip,
    required String semantics,
    Color? iconColor,
  }) {
    return Container(
      margin: const EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: AppTheme.surface.withValues(alpha: 0.8),
        borderRadius: AppTheme.radius12,
        boxShadow: AppTheme.shadowSoft,
        border: Border.all(color: Colors.white, width: 0.5),
      ),
      child: ClipRRect(
        borderRadius: AppTheme.radius12,
        child: Tooltip(
          message: tooltip,
          child: Semantics(
            label: semantics,
            button: true,
            child: IconButton(
              icon: Icon(icon, color: iconColor, size: 20),
              onPressed: onPressed,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildCategoryBadge(String tag) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
      decoration: BoxDecoration(
        color: AppTheme.primary.withValues(alpha: 0.08),
        borderRadius: AppTheme.radius8,
        border: Border.all(
          color: AppTheme.primary.withValues(alpha: 0.1),
          width: 1,
        ),
      ),
      child: Text(
        tag.toUpperCase(),
        style: Theme.of(context).textTheme.labelSmall?.copyWith(
              color: AppTheme.primary,
              fontWeight: FontWeight.w900,
              letterSpacing: 1.5,
              fontSize: 10,
            ),
      ),
    );
  }

  Widget _buildPriceSection(ProductVariant? variant) {
    if (variant == null) return const SizedBox.shrink();
    return RichText(
      text: TextSpan(
        children: [
          TextSpan(
            text: PriceFormatter.formatStringIDR(variant.price.amount),
            style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                  fontWeight: FontWeight.w900,
                  color: AppTheme.primary,
                  letterSpacing: -1,
                ),
          ),
        ],
      ),
    );
  }

  Widget _buildRatingSection(Product product) {
    return Row(
      children: [
        ...List.generate(5, (index) {
          return Icon(
            index < (product.averageRating?.floor() ?? 0) ? Icons.star : Icons.star_border,
            size: 20,
            color: AppTheme.accent,
          );
        }),
        const SizedBox(width: 8),
        Text(
          product.averageRating?.toStringAsFixed(1) ?? '0.0',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.w700),
        ),
        const SizedBox(width: 4),
        Text(
          '(${product.totalReviews ?? 0} ulasan)',
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: AppTheme.onSurfaceVariant),
        ),
      ],
    );
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
                child: const Icon(Icons.error_outline, size: 48, color: AppTheme.error),
              ),
              const SizedBox(height: 24),
              Text(
                _error ?? 'Produk tidak ditemukan',
                style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
              ),
              const SizedBox(height: 24),
              ElevatedButton.icon(
                onPressed: () => context.pop(),
                icon: const Icon(Icons.arrow_back),
                label: const Text('Kembali'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget? _buildFloatingCartButton(ProductVariant? variant, bool isAvailable) {
    if (variant == null) return null;

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
            Expanded(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Total',
                    style: Theme.of(context).textTheme.bodySmall?.copyWith(color: AppTheme.onSurfaceVariant),
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
            Expanded(
              flex: 2,
              child: Semantics(
                label: isAvailable ? 'Tambah produk ke keranjang' : 'Stok produk habis',
                button: true,
                enabled: isAvailable,
                child: ShimmerButton(
                  onPressed: isAvailable ? _addToCart : null,
                  background: isAvailable ? AppTheme.primary : AppTheme.outline,
                  borderRadius: 16,
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.shopping_bag_outlined, size: 20, color: Colors.white),
                      const SizedBox(width: 8),
                      Text(
                        isAvailable ? 'Tambah ke Keranjang' : 'Stok Habis',
                        style: const TextStyle(
                          fontSize: 15, 
                          fontWeight: FontWeight.w700,
                          color: Colors.white,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
