import '../models/product.dart';
import '../models/category.dart';
import '../models/pagination.dart';
import '../models/review.dart';
import 'api_service.dart';

class ProductService {
  ProductService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  /// Safe helper to convert response to Map
  Map<String, dynamic> _safeToMap(dynamic response) {
    if (response == null) return {};
    if (response is Map<String, dynamic>) return response;
    if (response is Map) return Map<String, dynamic>.from(response);
    return {};
  }

  /// Safe helper to get List from response
  List<dynamic> _safeToList(dynamic response, {String? dataKey}) {
    if (response == null) return [];

    // Try to get from data key first
    if (dataKey != null && response is Map) {
      final data = response[dataKey];
      if (data is List) return data;
      if (data is Map && data['data'] is List) {
        return data['data'] as List<dynamic>;
      }
    }

    // Check if response has data field (common Laravel format)
    if (response is Map<String, dynamic> && response['data'] is List) {
      return response['data'] as List<dynamic>;
    }

    // Response is already a list
    if (response is List) return response;

    return [];
  }

  Future<ProductListResponse> getProducts({
    String? q,
    String? category,
    String? sortKey,
    bool? reverse,
    double? minPrice,
    double? maxPrice,
    int page = 1,
    int limit = 12,
    String? ids,
  }) async {
    final queryParams = <String, dynamic>{'page': page, 'limit': limit};
    if (q != null && q.isNotEmpty) {
      queryParams['q'] = q;
    }
    if (category != null && category.isNotEmpty) {
      queryParams['category'] = category;
    }
    if (sortKey != null && sortKey.isNotEmpty) {
      queryParams['sortKey'] = sortKey;
    }
    if (reverse == true) {
      queryParams['reverse'] = true;
    }
    if (minPrice != null) {
      queryParams['minPrice'] = minPrice;
    }
    if (maxPrice != null) {
      queryParams['maxPrice'] = maxPrice;
    }
    if (ids != null && ids.isNotEmpty) {
      queryParams['ids'] = ids;
    }

    final response = await _api.get('/products', queryParams: queryParams);

    return ProductListResponse.fromJson(_safeToMap(response));
  }

  Future<Product> getProductDetail(String handle) async {
    final response = await _api.get('/products/$handle');

    if (response == null) {
      throw ApiException('Product not found', 404);
    }

    final map = _safeToMap(response);

    // Try different response formats
    final data = map['data'];
    if (data is Map) {
      return Product.fromJson(Map<String, dynamic>.from(data));
    }
    final product = map['product'];
    if (product is Map) {
      return Product.fromJson(Map<String, dynamic>.from(product));
    }

    // Direct product response
    return Product.fromJson(map);
  }

  Future<List<Product>> getBestSellers({int limit = 4}) async {
    final response = await _api.get(
      '/products/best-sellers',
      queryParams: {'limit': limit},
    );

    final data = _safeToList(response, dataKey: 'data');
    return data
        .whereType<Map<String, dynamic>>()
        .map((e) => Product.fromJson(e))
        .toList();
  }

  Future<List<Product>> getNewArrivals({int limit = 4}) async {
    final response = await _api.get(
      '/products/new-arrivals',
      queryParams: {'limit': limit},
    );

    final data = _safeToList(response, dataKey: 'data');
    return data
        .whereType<Map<String, dynamic>>()
        .map((e) => Product.fromJson(e))
        .toList();
  }

  Future<List<Product>> getRelatedProducts(String handle) async {
    final product = await getProductDetail(handle);
    final response = await _api.get('/products/${product.id}/recommendations');

    final data = _safeToList(response, dataKey: 'data');
    return data
        .whereType<Map<String, dynamic>>()
        .map((e) => Product.fromJson(e))
        .toList();
  }

  Future<List<Product>> getRecommendations() async {
    final response = await _api.get('/recommendations', requiresAuth: true);

    final data = _safeToList(response, dataKey: 'data');
    return data
        .whereType<Map<String, dynamic>>()
        .map((e) => Product.fromJson(e))
        .toList();
  }

  Future<List<Category>> getCategories() async {
    final response = await _api.get('/categories');

    final data = _safeToList(response, dataKey: 'data');
    return data
        .whereType<Map<String, dynamic>>()
        .map((e) => Category.fromJson(e))
        .toList();
  }

  // Fetch reviews for a product using handle
  Future<List<ReviewItem>> getProductReviews(
    String handle, {
    int page = 1,
  }) async {
    try {
      final response = await _api.get(
        '/products/$handle/reviews',
        queryParams: {'page': page},
      );

      List<dynamic> data = [];
      final map = _safeToMap(response);

      // Try different response structures
      if (map['data'] is Map<String, dynamic>) {
        final innerData = map['data'] as Map<String, dynamic>;
        if (innerData['reviews'] is List) {
          data = innerData['reviews'] as List<dynamic>;
        } else if (innerData['data'] is List) {
          data = innerData['data'] as List<dynamic>;
        }
      } else if (map['reviews'] is Map<String, dynamic>) {
        final reviewsMap = map['reviews'] as Map<String, dynamic>;
        if (reviewsMap['data'] is List) {
          data = reviewsMap['data'] as List<dynamic>;
        }
      } else if (map['data'] is List) {
        data = map['data'] as List<dynamic>;
      } else if (response is List) {
        data = response;
      }

      return data
          .whereType<Map<String, dynamic>>()
          .map((e) => ReviewItem.fromJson(e))
          .toList();
    } on Exception {
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
    } on Exception catch (e) {
      throw Exception('Gagal mengirim ulasan: $e');
    }
  }

  // Update existing review
  Future<bool> updateReview(
    int reviewId, {
    int? rating,
    String? comment,
  }) async {
    try {
      final body = <String, dynamic>{};
      if (rating != null) body['rating'] = rating;
      if (comment != null) body['comment'] = comment;

      await _api.put('/reviews/$reviewId', body: body, requiresAuth: true);
      return true;
    } on Exception catch (e) {
      throw Exception('Gagal mengupdate ulasan: $e');
    }
  }

  // Delete review
  Future<bool> deleteReview(int reviewId) async {
    try {
      await _api.delete('/reviews/$reviewId', requiresAuth: true);
      return true;
    } on Exception catch (e) {
      throw Exception('Gagal menghapus ulasan: $e');
    }
  }

  // Get single category by handle
  Future<Category> getCategory(String handle) async {
    final response = await _api.get('/categories/$handle');

    if (response == null) {
      throw ApiException('Category not found', 404);
    }

    final map = _safeToMap(response);

    // Try {data: {...}} format first
    final data = map['data'];
    if (data is Map) {
      return Category.fromJson(Map<String, dynamic>.from(data));
    }

    // Try {category: {...}} format
    final category = map['category'];
    if (category is Map) {
      return Category.fromJson(Map<String, dynamic>.from(category));
    }

    // Direct response
    return Category.fromJson(map);
  }

  // Send user interactions for ML recommendations
  Future<bool> sendInteractions(
    List<Map<String, dynamic>> interactions, {
    bool isAuthenticated = false,
  }) async {
    // Skip sending interactions for guest users (backend accepts but we skip for efficiency)
    if (!isAuthenticated || interactions.isEmpty) return false;

    try {
      final normalizedInteractions = interactions
          .map<Map<String, dynamic>>((interaction) {
            final rawProductId =
                interaction['productId'] ?? interaction['product_id'];
            final productId = int.tryParse(rawProductId?.toString() ?? '');
            if (productId == null) {
              return <String, dynamic>{};
            }

            final rawAction = (interaction['action'] ?? '').toString();
            final action = switch (rawAction) {
              'add_to_cart' => 'cart',
              'purchase' => 'purchase',
              _ => 'view',
            };

            final score = switch (action) {
              'cart' => 3,
              'purchase' => 5,
              _ => 1,
            };

            return {'productId': productId, 'action': action, 'score': score};
          })
          .where((interaction) => interaction.isNotEmpty)
          .toList();

      if (normalizedInteractions.isEmpty) {
        return false;
      }

      await _api.post(
        '/interactions/batch',
        body: {'interactions': normalizedInteractions},
        requiresAuth: true,
      );
      return true;
    } on Exception {
      return false;
    }
  }
}
