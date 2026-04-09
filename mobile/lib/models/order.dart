import 'address.dart';
import 'order_item.dart';

class Order {
  final int id;
  final String orderNumber;
  final String status;
  final String? paymentStatus;
  final double total;
  final double subtotal;
  final double shippingCost;
  final Address? shippingAddress;
  final int? itemsCount;
  final String createdAt;
  final List<OrderItem> items;
  final String? refundRequestedAt;
  final String? refundReason;

  Order({
    required this.id,
    required this.orderNumber,
    required this.status,
    this.paymentStatus,
    required this.total,
    required this.subtotal,
    required this.shippingCost,
    this.shippingAddress,
    this.itemsCount,
    required this.createdAt,
    required this.items,
    this.refundRequestedAt,
    this.refundReason,
  });

  factory Order.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    String parseString(dynamic value, [String fallback = '']) {
      if (value is String) return value;
      return value?.toString() ?? fallback;
    }

    final shippingAddressJson =
        json['shipping_address'] ?? json['shippingAddress'];

    return Order(
      id: parseInt(json['id'], 0),
      orderNumber: parseString(json['order_number'] ?? json['orderNumber']),
      status: parseString(json['status'], 'pending'),
      paymentStatus: (json['payment_status'] ?? json['paymentStatus'])
          ?.toString(),
      total: json['total'] == null
          ? 0.0
          : double.tryParse(json['total'].toString()) ?? 0.0,
      subtotal: json['subtotal'] == null
          ? 0.0
          : double.tryParse(json['subtotal'].toString()) ?? 0.0,
      shippingCost: (json['shipping_cost'] ?? json['shippingCost']) == null
          ? 0.0
          : double.tryParse(
                  (json['shipping_cost'] ?? json['shippingCost']).toString(),
                ) ??
                0.0,
      shippingAddress:
          (json['shipping_address'] ?? json['shippingAddress']) == null
          ? null
          : Address.fromJson(
              Map<String, dynamic>.from(shippingAddressJson as Map),
            ),
      itemsCount: (json['items_count'] ?? json['itemsCount']) == null
          ? null
          : int.tryParse(
              (json['items_count'] ?? json['itemsCount']).toString(),
            ),
      createdAt: parseString(json['created_at'] ?? json['createdAt']),
      items:
          (json['items'] as List<dynamic>?)
              ?.map(
                (e) => OrderItem.fromJson(Map<String, dynamic>.from(e as Map)),
              )
              .toList() ??
          [],
      refundRequestedAt:
          (json['refund_requested_at'] ?? json['refundRequestedAt'])
              ?.toString(),
      refundReason: (json['refund_reason'] ?? json['refundReason'])?.toString(),
    );
  }
}
