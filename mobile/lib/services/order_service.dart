import '../models/order.dart';
import 'api_service.dart';

class OrderService {
  final ApiService _api = ApiService();

  Future<List<Order>> getOrders() async {
    final response = await _api.get('/orders', requiresAuth: true);

    List<dynamic> data = [];
    if (response is Map<String, dynamic> && response['orders'] != null) {
      if (response['orders'] is List) {
        data = response['orders'];
      } else if (response['orders']['data'] != null) {
        // Handle paginated response structure if present
        data = response['orders']['data'];
      }
    } else if (response is Map<String, dynamic> && response['data'] != null) {
      data = response['data'];
    }

    return data.map((json) => Order.fromJson(json)).toList();
  }

  Future<Order> getOrderDetail(String orderNumber) async {
    final response = await _api.get('/orders/$orderNumber', requiresAuth: true);

    if (response is Map<String, dynamic> && response['order'] != null) {
      return Order.fromJson(response['order']);
    }
    return Order.fromJson(response);
  }

  Future<Map<String, dynamic>> createCheckout(
    Map<String, dynamic> payload,
  ) async {
    final response = await _api.post(
      '/checkout',
      body: payload,
      requiresAuth: true,
    );
    return response;
  }

  /// Get Midtrans Snap Token for payment
  Future<Map<String, dynamic>> payOrder(String orderNumber) async {
    final response = await _api.post(
      '/orders/$orderNumber/pay',
      requiresAuth: true,
    );
    return response;
  }

  /// Confirm payment after user pays via Midtrans
  Future<bool> confirmPayment(String orderNumber) async {
    try {
      await _api.post(
        '/orders/$orderNumber/confirm-payment',
        requiresAuth: true,
      );
      return true;
    } catch (e) {
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
    } catch (e) {
      return false;
    }
  }
}
