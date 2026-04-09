import '../models/product.dart';
import 'api_service.dart';

class WishlistService {
  WishlistService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<List<Product>> getWishlist() async {
    final response = await _api.get('/wishlist', requiresAuth: true);

    // API returns mostly array of products under data, or directly
    List<dynamic> data = [];
    if (response is List) {
      data = response;
    } else if (response is Map<String, dynamic> && response['data'] is List) {
      data = response['data'] as List<dynamic>;
    }

    return data
        .map((json) => Product.fromJson(Map<String, dynamic>.from(json as Map)))
        .toList();
  }

  Future<bool> addToWishlist(String productId) async {
    try {
      await _api.post('/wishlist/$productId', requiresAuth: true);
      return true;
    } on Exception {
      return false;
    }
  }

  Future<bool> removeFromWishlist(String productId) async {
    try {
      await _api.delete('/wishlist/$productId', requiresAuth: true);
      return true;
    } on Exception {
      return false;
    }
  }

  Future<bool> checkWishlist(String productId) async {
    try {
      final response = await _api.get(
        '/wishlist/check/$productId',
        requiresAuth: true,
      );
      // Support both camelCase (new) and snake_case (legacy)
      if (response is Map<String, dynamic>) {
        final data = response['data'];
        if (data is Map<String, dynamic>) {
          return (data['isWishlisted'] ?? data['is_wishlisted'] ?? false) ==
              true;
        }
        return (response['isWishlisted'] ??
                response['is_wishlisted'] ??
                false) ==
            true;
      }
      return false;
    } on Exception {
      return false;
    }
  }
}
