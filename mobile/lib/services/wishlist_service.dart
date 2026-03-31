import '../models/product.dart';
import 'api_service.dart';

class WishlistService {
  final ApiService _api = ApiService();

  Future<List<Product>> getWishlist() async {
    final response = await _api.get('/wishlist', requiresAuth: true);

    // API returns mostly array of products under data, or directly
    List<dynamic> data = [];
    if (response is List) {
      data = response;
    } else if (response is Map<String, dynamic> && response['data'] != null) {
      data = response['data'];
    }

    return data.map((json) => Product.fromJson(json)).toList();
  }

  Future<bool> addToWishlist(String productId) async {
    try {
      await _api.post('/wishlist/$productId', requiresAuth: true);
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> removeFromWishlist(String productId) async {
    try {
      await _api.delete('/wishlist/$productId', requiresAuth: true);
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> checkWishlist(String productId) async {
    try {
      final response = await _api.get(
        '/wishlist/check/$productId',
        requiresAuth: true,
      );
      if (response is Map<String, dynamic> &&
          response['is_wishlisted'] != null) {
        return response['is_wishlisted'] as bool;
      }
      return false;
    } catch (e) {
      return false;
    }
  }
}
