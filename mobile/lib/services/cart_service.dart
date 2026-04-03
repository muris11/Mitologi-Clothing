import '../models/cart.dart';
import 'api_service.dart';
import 'secure_storage_service.dart';

class CartService {
  final ApiService _api = ApiService();

  Future<void> _persistCartSessionId(Cart cart) async {
    final sessionId = cart.sessionId ?? cart.id;
    if (sessionId == null || sessionId.isEmpty) return;

    await SecureStorageService.saveCartSessionId(sessionId);
  }

  Future<Cart> _createCart() async {
    final response = await _api.post('/cart', requiresAuth: true);
    final cart =
        response is Map<String, dynamic> && response.containsKey('cart')
        ? Cart.fromJson(response['cart'])
        : Cart.fromJson(response);
    await _persistCartSessionId(cart);
    return cart;
  }

  Future<Cart> getCart() async {
    final response = await _api.get('/cart', requiresAuth: true);
    if (response == null) {
      return _createCart();
    }

    if (response is Map<String, dynamic> && response.containsKey('cart')) {
      if (response['cart'] == null) {
        return _createCart();
      }
      final cart = Cart.fromJson(response['cart']);
      await _persistCartSessionId(cart);
      return cart;
    }

    final cart = Cart.fromJson(response);
    await _persistCartSessionId(cart);
    return cart;
  }

  Future<Cart> addToCart(String variantId, int quantity) async {
    final response = await _api.post(
      '/cart/items',
      body: {'merchandiseId': variantId, 'quantity': quantity},
      requiresAuth: true,
    );

    final cart =
        response is Map<String, dynamic> && response.containsKey('cart')
        ? Cart.fromJson(response['cart'])
        : Cart.fromJson(response);
    await _persistCartSessionId(cart);
    return cart;
  }

  Future<Cart> updateItemQuantity(String itemId, int quantity) async {
    final response = await _api.put(
      '/cart/items/$itemId',
      body: {'quantity': quantity},
      requiresAuth: true,
    );

    final cart =
        response is Map<String, dynamic> && response.containsKey('cart')
        ? Cart.fromJson(response['cart'])
        : Cart.fromJson(response);
    await _persistCartSessionId(cart);
    return cart;
  }

  Future<Cart> removeItem(String itemId) async {
    final response = await _api.delete(
      '/cart/items/$itemId',
      requiresAuth: true,
    );

    final cart =
        response is Map<String, dynamic> && response.containsKey('cart')
        ? Cart.fromJson(response['cart'])
        : Cart.fromJson(response);
    await _persistCartSessionId(cart);
    return cart;
  }

  Future<void> clearCart() async {
    await _api.delete('/cart/clear', requiresAuth: true);
  }
}
