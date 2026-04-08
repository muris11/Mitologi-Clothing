import 'package:flutter/material.dart';
import '../models/collection.dart';
import '../models/product.dart';
import '../models/category.dart';
import '../models/pagination.dart';
import '../services/collection_service.dart';
import '../services/product_service.dart';
import '../models/review.dart';

class ProductProvider extends ChangeNotifier {
  final ProductService _productService = ProductService();
  final CollectionService _collectionService = CollectionService();

  // State
  List<Product> _products = [];
  List<Category> _categories = [];
  List<Collection> _collections = [];
  List<Product> _newArrivals = [];
  List<Product> _bestSellers = [];
  Product? _currentProduct;
  List<ReviewItem> _currentProductReviews = [];
  List<Product> _currentProductRelated = [];
  bool _isLoadingProductDetail = false;
  Pagination _pagination = Pagination(
    currentPage: 1,
    lastPage: 1,
    total: 0,
    perPage: 12,
  );

  bool _isLoading = false;
  bool _isLoadingMore = false;
  bool _isLoadingHomeData = false;
  String? _error;

  // Filters
  String? _searchQuery;
  String? _selectedCategory;
  String _sortKey = 'latest';
  double? _minPrice;
  double? _maxPrice;

  // Getters
  List<Product> get products => _products;
  List<Category> get categories => _categories;
  List<Collection> get collections => _collections;
  List<Product> get newArrivals => _newArrivals;
  List<Product> get bestSellers => _bestSellers;
  Product? get currentProduct => _currentProduct;
  List<ReviewItem> get currentProductReviews => _currentProductReviews;
  List<Product> get currentProductRelated => _currentProductRelated;
  bool get isLoading => _isLoading;
  bool get isLoadingMore => _isLoadingMore;
  bool get isLoadingHomeData => _isLoadingHomeData;
  bool get isLoadingProductDetail => _isLoadingProductDetail;
  String? get error => _error;
  bool get hasNextPage => _pagination.hasNextPage;

  String? get searchQuery => _searchQuery;
  String? get selectedCategory => _selectedCategory;
  String get sortKey => _sortKey;
  double? get minPrice => _minPrice;
  double? get maxPrice => _maxPrice;

  void _setError(String? message) {
    _error = message;
    notifyListeners();
  }

  Future<void> fetchCategories() async {
    try {
      _categories = await _productService.getCategories();
      notifyListeners();
    } catch (e) {
      // Silently handle category fetch errors
    }
  }

  Future<void> fetchProducts({bool refresh = false}) async {
    if (refresh) {
      _pagination = Pagination(
        currentPage: 1,
        lastPage: 1,
        total: 0,
        perPage: 12,
      );
      _products.clear();
    }

    if (_pagination.currentPage == 1) {
      _isLoading = true;
    } else {
      _isLoadingMore = true;
    }
    _setError(null);
    notifyListeners();

    try {
      bool reverse = false;
      String apiSortKey = _sortKey;

      // Map sort keys to backend API format
      switch (_sortKey) {
        case 'price-asc':
          apiSortKey = 'PRICE';
          reverse = false;
          break;
        case 'price-desc':
          apiSortKey = 'PRICE';
          reverse = true;
          break;
        case 'best-selling':
          apiSortKey = 'BEST_SELLING';
          reverse = false;
          break;
        case 'trending':
        case 'latest':
        default:
          apiSortKey = 'CREATED_AT';
          reverse = true;
          break;
      }

      final response = await _productService.getProducts(
        q: _searchQuery,
        category: _selectedCategory,
        sortKey: apiSortKey,
        reverse: reverse,
        minPrice: _minPrice,
        maxPrice: _maxPrice,
        page: _pagination.currentPage,
        limit: 12, // per_page
      );

      final newProducts = response.productsData
          .map((json) => Product.fromJson(json as Map<String, dynamic>))
          .toList();

      if (_pagination.currentPage == 1) {
        _products = newProducts;
      } else {
        _products.addAll(newProducts);
      }

      _pagination = response.pagination;
    } catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
    } finally {
      _isLoading = false;
      _isLoadingMore = false;
      notifyListeners();
    }
  }

  Future<void> loadMore() async {
    if (!hasNextPage || _isLoadingMore || _isLoading) return;

    // Increment page manually before fetch
    _pagination = Pagination(
      currentPage: _pagination.currentPage + 1,
      lastPage: _pagination.lastPage,
      total: _pagination.total,
      perPage: _pagination.perPage,
    );

    await fetchProducts(refresh: false);
  }

  // Filter Actions
  void setSearchQuery(String? query) {
    _searchQuery = query;
    fetchProducts(refresh: true);
  }

  void setCategory(String? category) {
    if (_selectedCategory == category) {
      _selectedCategory = null; // Toggle off
    } else {
      _selectedCategory = category;
    }
    fetchProducts(refresh: true);
  }

  void setSortKey(String sort) {
    _sortKey = sort;
    fetchProducts(refresh: true);
  }

  void setPriceRange(double? min, double? max) {
    _minPrice = min;
    _maxPrice = max;
    fetchProducts(refresh: true);
  }

  void resetFilters() {
    _searchQuery = null;
    _selectedCategory = null;
    _sortKey = 'latest';
    _minPrice = null;
    _maxPrice = null;
    fetchProducts(refresh: true);
  }

  // Fetch individual/special products (no need to store in global list state usually)
  Future<Product?> getProductDetail(String handle) async {
    try {
      return await _productService.getProductDetail(handle);
    } catch (e) {
      // Error handled in calling code
      return null;
    }
  }

  Future<List<ReviewItem>> getProductReviews(String productId) async {
    try {
      return await _productService.getProductReviews(productId);
    } catch (e) {
      // Return empty list on error
      return [];
    }
  }

  Future<bool> submitReview(
    String productId,
    int rating,
    String comment,
  ) async {
    try {
      return await _productService.submitReview(productId, rating, comment);
    } catch (e) {
      // Rethrow for UI handling
      rethrow;
    }
  }

  // Home Screen Data Loading
  Future<void> loadHomeData() async {
    _isLoadingHomeData = true;
    _setError(null);
    notifyListeners();

    try {
      final results = await Future.wait([
        _productService.getNewArrivals(limit: 6),
        _productService.getBestSellers(limit: 6),
        _collectionService.getCollections(),
      ]);

      _newArrivals = results[0] as List<Product>;
      _bestSellers = results[1] as List<Product>;
      _collections = results[2] as List<Collection>;
    } catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
    } finally {
      _isLoadingHomeData = false;
      notifyListeners();
    }
  }

  // Product Detail Loading
  Future<void> loadProductDetail(String handle) async {
    _isLoadingProductDetail = true;
    _currentProduct = null;
    _currentProductReviews = [];
    _currentProductRelated = [];
    _setError(null);
    notifyListeners();

    try {
      _currentProduct = await _productService.getProductDetail(handle);

      // Load related data in parallel
      final results = await Future.wait([
        _productService.getProductReviews(handle),
        _productService.getRelatedProducts(handle),
      ]);

      _currentProductReviews = results[0] as List<ReviewItem>;
      _currentProductRelated = results[1] as List<Product>;
    } catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
    } finally {
      _isLoadingProductDetail = false;
      notifyListeners();
    }
  }

  Future<List<Product>> getRecommendations() async {
    try {
      return await _productService.getRecommendations();
    } catch (e) {
      return [];
    }
  }
}
