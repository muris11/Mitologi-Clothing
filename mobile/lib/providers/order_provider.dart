import 'package:flutter/material.dart';
import '../models/order.dart';
import '../services/order_service.dart';

class OrderProvider extends ChangeNotifier {
  final OrderService _service = OrderService();

  List<Order> _orders = [];
  bool _isLoading = false;
  String? _error;

  List<Order> get orders => _orders;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Order? _currentOrder;
  Order? get currentOrder => _currentOrder;

  // Computed properties for dashboard
  int get pendingCount => _orders
      .where(
        (o) =>
            o.status.toLowerCase() == 'unpaid' ||
            o.status.toLowerCase() == 'pending',
      )
      .length;
  int get packedCount =>
      _orders.where((o) => o.status.toLowerCase() == 'processing').length;
  int get shippedCount =>
      _orders.where((o) => o.status.toLowerCase() == 'shipped').length;

  Future<void> fetchOrders() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _orders = await _service.getOrders();
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchOrderDetails(String orderNumber) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _currentOrder = await _service.getOrderDetail(orderNumber);
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<Map<String, dynamic>?> checkout(
    Map<String, dynamic> checkoutData,
  ) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final response = await _service.createCheckout(checkoutData);
      return response;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
      return null;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<Map<String, dynamic>?> payOrder(String orderNumber) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final response = await _service.payOrder(orderNumber);
      return response;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
      return null;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> confirmPayment(String orderNumber) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final success = await _service.confirmPayment(orderNumber);
      if (success) {
        await fetchOrderDetails(orderNumber);
        await fetchOrders();
      }
      return success;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> requestRefund(String orderNumber, String reason) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final success = await _service.requestRefund(orderNumber, reason);
      if (success) {
        await fetchOrderDetails(orderNumber);
        await fetchOrders();
      }
      return success;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  void clearOrderData() {
    _orders = [];
    _currentOrder = null;
    _error = null;
    notifyListeners();
  }
}
