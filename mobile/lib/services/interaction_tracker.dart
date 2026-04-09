import 'dart:async';
import 'product_service.dart';

/// Service untuk tracking user interactions untuk ML recommendation system
/// Interactions di-queue dan di-flush secara batch untuk efisiensi
class InteractionTracker {
  static final List<Map<String, dynamic>> _queue = [];
  static final ProductService _productService = ProductService();
  static const int _batchSize = 5;
  static Timer? _flushTimer;

  /// Initialize the tracker with periodic flush
  /// [isAuthenticatedCallback] - Callback untuk mengecek status auth user
  static void initialize({bool Function()? isAuthenticatedCallback}) {
    // Flush setiap 30 detik jika ada data
    _flushTimer?.cancel();
    _flushTimer = Timer.periodic(const Duration(seconds: 30), (_) {
      final isAuth = isAuthenticatedCallback?.call() ?? false;
      flush(isAuthenticated: isAuth);
    });
  }

  /// Dispose the tracker
  static void dispose() {
    _flushTimer?.cancel();
    _flushTimer = null;
  }

  /// Track ketika user melihat product detail
  static void trackView(String productId) {
    _queue.add({
      'product_id': productId,
      'action': 'view',
      'timestamp': DateTime.now().toIso8601String(),
    });
    _flushIfNeeded();
  }

  /// Track ketika user menambah produk ke cart
  static void trackAddToCart(String productId, {int quantity = 1}) {
    _queue.add({
      'product_id': productId,
      'action': 'add_to_cart',
      'quantity': quantity,
      'timestamp': DateTime.now().toIso8601String(),
    });
    _flushIfNeeded();
  }

  /// Track ketika user melakukan purchase
  static void trackPurchase(String productId, {double? amount}) {
    _queue.add({
      'product_id': productId,
      'action': 'purchase',
      'amount': amount,
      'timestamp': DateTime.now().toIso8601String(),
    });
    _flushIfNeeded();
  }

  /// Track ketika user menambah/menghapus dari wishlist
  static void trackWishlist(String productId, {bool isAdded = true}) {
    _queue.add({
      'product_id': productId,
      'action': isAdded ? 'add_to_wishlist' : 'remove_from_wishlist',
      'timestamp': DateTime.now().toIso8601String(),
    });
    _flushIfNeeded();
  }

  /// Track search query
  static void trackSearch(String query) {
    _queue.add({
      'query': query,
      'action': 'search',
      'timestamp': DateTime.now().toIso8601String(),
    });
    _flushIfNeeded();
  }

  /// Flush batch jika sudah mencapai threshold
  static void _flushIfNeeded() {
    if (_queue.length >= _batchSize) {
      flush();
    }
  }

  /// Force flush semua interactions ke backend
  /// [isAuthenticated] - Set true hanya jika user sudah login (untuk mengirim ke ML system)
  static Future<void> flush({bool isAuthenticated = false}) async {
    if (_queue.isEmpty) return;

    final batch = List<Map<String, dynamic>>.from(_queue);
    _queue.clear();

    try {
      await _productService.sendInteractions(
        batch,
        isAuthenticated: isAuthenticated,
      );
    } on Exception {
      // Jika gagal, kembalikan ke queue untuk retry
      _queue.insertAll(0, batch);
    }
  }

  /// Get current queue size (for debugging)
  static int get queueSize => _queue.length;
}
