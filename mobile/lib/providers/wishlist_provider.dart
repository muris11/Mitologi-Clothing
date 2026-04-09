import 'package:flutter/material.dart';
import '../models/product.dart';
import '../services/wishlist_service.dart';

class WishlistProvider extends ChangeNotifier {
  final WishlistService _service = WishlistService();

  List<Product> _items = [];
  bool _isLoading = false;
  String? _error;

  List<Product> get items => _items;
  bool get isLoading => _isLoading;
  String? get error => _error;
  int get itemCount => _items.length;

  WishlistProvider() {
    _safeFetchWishlist();
  }

  Future<void> _safeFetchWishlist() async {
    try {
      await fetchWishlist();
    } on Exception catch (_) {
      // Silently ignore for guest users who don't have auth token
    }
  }

  Future<void> fetchWishlist() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _items = await _service.getWishlist();
    } on Exception catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  bool isWishlisted(String productId) {
    return _items.any((item) => item.id.toString() == productId);
  }

  Future<void> toggleWishlist(Product product) async {
    final productId = product.id.toString();
    final currentlyWishlisted = isWishlisted(productId);

    // Optimistic UI update
    if (currentlyWishlisted) {
      _items.removeWhere((item) => item.id.toString() == productId);
    } else {
      _items.add(product);
    }
    notifyListeners();

    try {
      if (currentlyWishlisted) {
        await _service.removeFromWishlist(productId);
      } else {
        await _service.addToWishlist(productId);
      }
    } on Exception {
      // Revert on failure
      if (currentlyWishlisted) {
        _items.add(product);
      } else {
        _items.removeWhere((item) => item.id.toString() == productId);
      }
      _error = 'Gagal menyimpan wishlist';
      notifyListeners();

      // Clear error after a while
      Future.delayed(const Duration(seconds: 3), () {
        _error = null;
        notifyListeners();
      });
    }
  }

  void clearWishlistData() {
    _items = [];
    notifyListeners();
  }
}
