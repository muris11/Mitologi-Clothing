import 'package:flutter/material.dart';
import '../models/cart.dart';
import '../services/cart_service.dart';

class CartProvider extends ChangeNotifier {
  final CartService _cartService;

  CartProvider({CartService? cartService})
    : _cartService = cartService ?? CartService();

  Cart? _cart;
  bool _isLoading = false;
  String? _error;

  Cart? get cart => _cart;
  bool get isLoading => _isLoading;
  String? get error => _error;
  int get itemCount => _cart?.totalQuantity ?? 0;

  void _setLoading(bool value) {
    _isLoading = value;
    notifyListeners();
  }

  void _setError(String? message) {
    _error = message;
    notifyListeners();
  }

  Future<void> fetchCart() async {
    _setLoading(true);
    _setError(null);
    try {
      _cart = await _cartService.getCart();
    } on Exception catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> addToCart(String variantId, int quantity) async {
    _setLoading(true);
    _setError(null);
    try {
      _cart = await _cartService.addToCart(variantId, quantity);
      return true;
    } on Exception catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
      return false;
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> updateQuantity(String itemId, int quantity) async {
    if (quantity <= 0) {
      return removeItem(itemId);
    }

    _setLoading(true);
    _setError(null);
    try {
      _cart = await _cartService.updateItemQuantity(itemId, quantity);
      return true;
    } on Exception catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
      return false;
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> removeItem(String itemId) async {
    _setLoading(true);
    _setError(null);
    try {
      _cart = await _cartService.removeItem(itemId);
      return true;
    } on Exception catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
      return false;
    } finally {
      _setLoading(false);
    }
  }

  Future<void> clearCart() async {
    _setLoading(true);
    _setError(null);
    try {
      await _cartService.clearCart();
      _cart = null;
    } on Exception catch (e) {
      _setError(e.toString().replaceAll('ApiException: ', ''));
    } finally {
      _setLoading(false);
    }
  }
}
