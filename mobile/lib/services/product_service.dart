import '../models/product.dart';
import '../models/category.dart';
import '../models/pagination.dart';
import '../models/review.dart';
import 'api_service.dart';

class ProductService {
  final ApiService _api = ApiService();

  Future<ProductListResponse> getProducts({
    String? q,
    String? category,
    String? sortKey,
    bool? reverse,
    double? minPrice,
    double? maxPrice,
    int page = 1,
    int limit = 12,
  }) async {
    List<String> queryParams = ['page=$page', 'limit=$limit'];

    if (q != null && q.isNotEmpty) {
      queryParams.add('q=$q');
    }
    if (category != null && category.isNotEmpty) {
      queryParams.add('category=$category');
    }
    if (sortKey != null && sortKey.isNotEmpty) {
      queryParams.add('sortKey=$sortKey');
    }
    if (reverse != null) {
      queryParams.add('reverse=$reverse');
    }
    if (minPrice != null) {
      queryParams.add('minPrice=$minPrice');
    }
    if (maxPrice != null) {
      queryParams.add('maxPrice=$maxPrice');
    }

    final queryString = queryParams.join('&');
    final response = await _api.get('/products?$queryString');

    return ProductListResponse.fromJson(response);
  }

  Future<Product> getProductDetail(String handle) async {
    final response = await _api.get('/products/$handle');

    if (response is Map<String, dynamic> && response.containsKey('product')) {
      return Product.fromJson(response['product']);
    }
    return Product.fromJson(response);
  }

  Future<List<Product>> getBestSellers({int limit = 4}) async {
    final response = await _api.get('/products/best-sellers?limit=$limit');

    List<dynamic> data = response is Map && response.containsKey('data')
        ? response['data']
        : response;
    return data.map((e) => Product.fromJson(e)).toList();
  }

  Future<List<Product>> getNewArrivals({int limit = 4}) async {
    final response = await _api.get('/products/new-arrivals?limit=$limit');

    List<dynamic> data = response is Map && response.containsKey('data')
        ? response['data']
        : response;
    return data.map((e) => Product.fromJson(e)).toList();
  }

  Future<List<Product>> getRelatedProducts(String handle) async {
    final product = await getProductDetail(handle);
    final response = await _api.get('/products/${product.id}/recommendations');

    List<dynamic> data = response is Map && response.containsKey('data')
        ? response['data']
        : response;
    return data.map((e) => Product.fromJson(e)).toList();
  }

  Future<List<Product>> getRecommendations() async {
    final response = await _api.get('/recommendations', requiresAuth: true);

    List<dynamic> data = [];
    if (response is Map<String, dynamic> &&
        response.containsKey('recommendations')) {
      data = response['recommendations'];
    } else if (response is List) {
      data = response;
    }

    return data.map((e) => Product.fromJson(e)).toList();
  }

  Future<List<Category>> getCategories() async {
    final response = await _api.get('/categories');

    List<dynamic> data = response is Map && response.containsKey('data')
        ? response['data']
        : response;
    return data.map((e) => Category.fromJson(e)).toList();
  }

  // Fetch reviews for a product using handle
  Future<List<ReviewItem>> getProductReviews(
    String handle, {
    int page = 1,
  }) async {
    try {
      final response = await _api.get('/products/$handle/reviews?page=$page');
      List<dynamic> data = [];
      if (response is Map<String, dynamic> &&
          response['reviews'] is Map<String, dynamic>) {
        data = response['reviews']['data'] ?? [];
      } else if (response is Map<String, dynamic> &&
          response.containsKey('data')) {
        data = response['data'];
      } else if (response is List) {
        data = response;
      }
      return data.map((e) => ReviewItem.fromJson(e)).toList();
    } catch (e) {
      return []; // Return empty if error or doesn't exist yet
    }
  }

  // Submit a new review using handle
  Future<bool> submitReview(String handle, int rating, String comment) async {
    try {
      await _api.post(
        '/products/$handle/reviews',
        body: {'rating': rating, 'comment': comment},
        requiresAuth: true,
      );
      return true;
    } catch (e) {
      throw Exception('Gagal mengirim ulasan: $e');
    }
  }

  // Send user interactions for ML recommendations
  Future<bool> sendInteractions(
    List<Map<String, dynamic>> interactions, {
    bool isAuthenticated = false,
  }) async {
    // Skip sending interactions for guest users (backend accepts but we skip for efficiency)
    if (!isAuthenticated || interactions.isEmpty) return false;

    try {
      await _api.post(
        '/interactions/batch',
        body: {'interactions': interactions},
        requiresAuth: true,
      );
      return true;
    } catch (e) {
      return false;
    }
  }
}
