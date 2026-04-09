import '../models/order.dart';
import 'api_service.dart';

class OrderService {
  OrderService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<List<Order>> getOrders() async {
    final response = await _api.get('/orders', requiresAuth: true);

    List<dynamic> data = [];
    if (response is Map<String, dynamic> &&
        response['data'] is Map<String, dynamic> &&
        response['data']['orders'] is List) {
      data = response['data']['orders'] as List<dynamic>;
    } else if (response is Map<String, dynamic> && response['orders'] != null) {
      if (response['orders'] is List) {
        data = response['orders'] as List<dynamic>;
      } else if (response['orders'] is Map<String, dynamic> &&
          response['orders']['data'] != null) {
        // Handle paginated response structure if present
        data = response['orders']['data'] as List<dynamic>;
      }
    } else if (response is Map<String, dynamic> && response['data'] is List) {
      data = response['data'] as List<dynamic>;
    }

    return data
        .map((json) => Order.fromJson(Map<String, dynamic>.from(json as Map)))
        .toList();
  }

  Future<Order> getOrderDetail(String orderNumber) async {
    final response = await _api.get('/orders/$orderNumber', requiresAuth: true);

    if (response is Map<String, dynamic> && response['data'] is Map) {
      return Order.fromJson(Map<String, dynamic>.from(response['data'] as Map));
    }
    if (response is Map<String, dynamic> && response['order'] != null) {
      return Order.fromJson(
        Map<String, dynamic>.from(response['order'] as Map),
      );
    }
    return Order.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<Map<String, dynamic>> createCheckout(
    Map<String, dynamic> payload,
  ) async {
    final response = await _api.post(
      '/checkout',
      body: payload,
      requiresAuth: true,
    );
    if (response is Map<String, dynamic> && response['data'] is Map) {
      return Map<String, dynamic>.from(response['data'] as Map);
    }
    return Map<String, dynamic>.from(response as Map);
  }

  /// Get Midtrans Snap Token for payment
  Future<Map<String, dynamic>> payOrder(String orderNumber) async {
    final response = await _api.post(
      '/orders/$orderNumber/pay',
      requiresAuth: true,
    );
    if (response is Map<String, dynamic> && response['data'] is Map) {
      return Map<String, dynamic>.from(response['data'] as Map);
    }
    return Map<String, dynamic>.from(response as Map);
  }

  /// Confirm payment after user pays via Midtrans
  Future<bool> confirmPayment(String orderNumber) async {
    try {
      await _api.post(
        '/orders/$orderNumber/confirm-payment',
        requiresAuth: true,
      );
      return true;
    } on Exception {
      return false;
    }
  }

  /// Request a refund for the order
  Future<bool> requestRefund(String orderNumber, String reason) async {
    try {
      await _api.post(
        '/orders/$orderNumber/request-refund',
        body: {'reason': reason},
        requiresAuth: true,
      );
      return true;
    } on Exception {
      return false;
    }
  }
}
