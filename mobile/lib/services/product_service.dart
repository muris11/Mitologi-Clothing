import '../models/product.dart';
import '../models/category.dart';
import '../models/pagination.dart';
import '../models/review.dart';
import 'api_service.dart';

class ProductService {
  ProductService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

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

    final response = await _api.get('/products', queryParams: queryParams);

    return ProductListResponse.fromJson(
      Map<String, dynamic>.from(response as Map),
    );
  }

  Future<Product> getProductDetail(String handle) async {
    final response = await _api.get('/products/$handle');

    if (response is Map<String, dynamic> &&
        response['data'] is Map<String, dynamic>) {
      return Product.fromJson(
        Map<String, dynamic>.from(response['data'] as Map),
      );
    }
    if (response is Map<String, dynamic> && response.containsKey('product')) {
      return Product.fromJson(
        Map<String, dynamic>.from(response['product'] as Map),
      );
    }
    return Product.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<List<Product>> getBestSellers({int limit = 4}) async {
    final response = await _api.get(
      '/products/best-sellers',
      queryParams: {'limit': limit},
    );

    final List<dynamic> data =
        response is Map<String, dynamic> && response['data'] is List
        ? response['data'] as List<dynamic>
        : (response as List<dynamic>);
    return data
        .map((e) => Product.fromJson(Map<String, dynamic>.from(e as Map)))
        .toList();
  }

  Future<List<Product>> getNewArrivals({int limit = 4}) async {
    final response = await _api.get(
      '/products/new-arrivals',
      queryParams: {'limit': limit},
    );

    final List<dynamic> data =
        response is Map<String, dynamic> && response['data'] is List
        ? response['data'] as List<dynamic>
        : (response as List<dynamic>);
    return data
        .map((e) => Product.fromJson(Map<String, dynamic>.from(e as Map)))
        .toList();
  }

  Future<List<Product>> getRelatedProducts(String handle) async {
    final product = await getProductDetail(handle);
    final response = await _api.get('/products/${product.id}/recommendations');

    final List<dynamic> data =
        response is Map<String, dynamic> && response['data'] is List
        ? response['data'] as List<dynamic>
        : (response as List<dynamic>);
    return data
        .map((e) => Product.fromJson(Map<String, dynamic>.from(e as Map)))
        .toList();
  }

  Future<List<Product>> getRecommendations() async {
    final response = await _api.get('/recommendations', requiresAuth: true);

    List<dynamic> data = [];
    // New standardized format: { data: [...] }
    if (response is Map<String, dynamic> && response.containsKey('data')) {
      final dynamic rawData = response['data'];
      if (rawData is List) {
        data = rawData;
      } else if (rawData is Map && rawData.containsKey('data')) {
        // Nested data structure
        data = rawData['data'] as List<dynamic>;
      }
    } else if (response is List) {
      data = response;
    }

    return data
        .map((e) => Product.fromJson(Map<String, dynamic>.from(e as Map)))
        .toList();
  }

  Future<List<Category>> getCategories() async {
    final response = await _api.get('/categories');

    final List<dynamic> data =
        response is Map<String, dynamic> && response['data'] is List
        ? response['data'] as List<dynamic>
        : (response as List<dynamic>);
    return data
        .map((e) => Category.fromJson(Map<String, dynamic>.from(e as Map)))
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
      if (response is Map<String, dynamic> &&
          response['data'] is Map<String, dynamic> &&
          response['data']['reviews'] is List) {
        data = response['data']['reviews'] as List<dynamic>;
      } else if (response is Map<String, dynamic> &&
          response['reviews'] is Map<String, dynamic>) {
        data =
            (response['reviews'] as Map<String, dynamic>)['data']
                as List<dynamic>? ??
            [];
      } else if (response is Map<String, dynamic> && response['data'] is List) {
        data = response['data'] as List<dynamic>;
      } else if (response is List) {
        data = response;
      }
      return data
          .map((e) => ReviewItem.fromJson(Map<String, dynamic>.from(e as Map)))
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
