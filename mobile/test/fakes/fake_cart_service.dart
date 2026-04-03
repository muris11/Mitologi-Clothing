import 'package:mobile/models/cart.dart';
import 'package:mobile/models/cart_item.dart';
import 'package:mobile/models/money.dart';
import 'package:mobile/services/api_service.dart';
import 'package:mobile/services/cart_service.dart';

/// Fake cart service for testing
class FakeCartService implements CartService {
  Cart? _cart;
  bool shouldThrowError = false;
  String errorMessage = 'Fake error';

  void setCart(Cart cart) {
    _cart = cart;
  }

  void setError({String? message}) {
    shouldThrowError = true;
    if (message != null) {
      errorMessage = message;
    }
  }

  void clearError() {
    shouldThrowError = false;
  }

  @override
  Future<Cart> getCart() async {
    if (shouldThrowError) {
      throw ApiException(errorMessage, 500);
    }
    return _cart ?? _createEmptyCart();
  }

  @override
  Future<Cart> addToCart(String variantId, int quantity) async {
    if (shouldThrowError) {
      throw ApiException(errorMessage, 500);
    }
    // Simulate adding item
    final item = CartItem(
      id: 'item_${_cart?.lines.length ?? 0}',
      merchandiseId: variantId,
      title: 'Test Product',
      quantity: quantity,
      price: const Money(amount: 100000.0, currencyCode: 'IDR'),
      imageUrl: null,
      variantTitle: null,
    );

    final lines = [...?_cart?.lines, item];
    final totalQuantity = lines.fold<int>(
      0,
      (sum, item) => sum + item.quantity,
    );
    final totalAmount = lines.fold<double>(
      0.0,
      (sum, item) => sum + (item.price.amount * item.quantity),
    );

    _cart = Cart(
      id: _cart?.id ?? 'test-cart',
      checkoutUrl: '',
      cost: CartCost(
        subtotalAmount: Money(amount: totalAmount, currencyCode: 'IDR'),
        totalAmount: Money(amount: totalAmount, currencyCode: 'IDR'),
        totalTaxAmount: const Money(amount: 0.0, currencyCode: 'IDR'),
      ),
      lines: lines,
      totalQuantity: totalQuantity,
    );
    return _cart!;
  }

  @override
  Future<Cart> updateItemQuantity(String itemId, int quantity) async {
    if (shouldThrowError) {
      throw ApiException(errorMessage, 500);
    }
    if (_cart == null) {
      return _createEmptyCart();
    }

    final updatedLines = _cart!.lines.map((item) {
      if (item.id == itemId) {
        return CartItem(
          id: item.id,
          merchandiseId: item.merchandiseId,
          title: item.title,
          quantity: quantity,
          price: item.price,
          imageUrl: item.imageUrl,
          variantTitle: item.variantTitle,
        );
      }
      return item;
    }).toList();

    final totalQuantity = updatedLines.fold<int>(
      0,
      (sum, item) => sum + item.quantity,
    );
    final totalAmount = updatedLines.fold<double>(
      0.0,
      (sum, item) => sum + (item.price.amount * item.quantity),
    );

    _cart = Cart(
      id: _cart!.id,
      checkoutUrl: _cart!.checkoutUrl,
      cost: CartCost(
        subtotalAmount: Money(amount: totalAmount, currencyCode: 'IDR'),
        totalAmount: Money(amount: totalAmount, currencyCode: 'IDR'),
        totalTaxAmount: const Money(amount: 0.0, currencyCode: 'IDR'),
      ),
      lines: updatedLines,
      totalQuantity: totalQuantity,
    );
    return _cart!;
  }

  @override
  Future<Cart> removeItem(String itemId) async {
    if (shouldThrowError) {
      throw ApiException(errorMessage, 500);
    }

    if (_cart == null) {
      return _createEmptyCart();
    }

    final updatedLines = _cart!.lines
        .where((item) => item.id != itemId)
        .toList();
    final totalQuantity = updatedLines.fold<int>(
      0,
      (sum, item) => sum + item.quantity,
    );
    final totalAmount = updatedLines.fold<double>(
      0.0,
      (sum, item) => sum + (item.price.amount * item.quantity),
    );

    _cart = Cart(
      id: _cart!.id,
      checkoutUrl: _cart!.checkoutUrl,
      cost: CartCost(
        subtotalAmount: Money(amount: totalAmount, currencyCode: 'IDR'),
        totalAmount: Money(amount: totalAmount, currencyCode: 'IDR'),
        totalTaxAmount: const Money(amount: 0.0, currencyCode: 'IDR'),
      ),
      lines: updatedLines,
      totalQuantity: totalQuantity,
    );
    return _cart!;
  }

  @override
  Future<void> clearCart() async {
    if (shouldThrowError) {
      throw ApiException(errorMessage, 500);
    }
    _cart = _createEmptyCart();
  }

  Cart _createEmptyCart() {
    return Cart(
      id: 'test-cart',
      checkoutUrl: '',
      cost: CartCost(
        subtotalAmount: const Money(amount: 0.0, currencyCode: 'IDR'),
        totalAmount: const Money(amount: 0.0, currencyCode: 'IDR'),
        totalTaxAmount: const Money(amount: 0.0, currencyCode: 'IDR'),
      ),
      lines: [],
      totalQuantity: 0,
    );
  }
}
