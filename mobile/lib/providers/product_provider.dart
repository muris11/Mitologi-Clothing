import 'package:flutter/material.dart';
import '../models/product.dart';
import '../models/category.dart';
import '../models/pagination.dart';
import '../services/product_service.dart';
import '../models/review.dart';

class ProductProvider extends ChangeNotifier {
  final ProductService _productService = ProductService();

  // State
  List<Product> _products = [];
  List<Category> _categories = [];
  Pagination _pagination = Pagination(
    currentPage: 1,
    lastPage: 1,
    total: 0,
    perPage: 12,
  );

  bool _isLoading = false;
  bool _isLoadingMore = false;
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
  bool get isLoading => _isLoading;
  bool get isLoadingMore => _isLoadingMore;
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
          .map((json) => Product.fromJson(json))
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
}
