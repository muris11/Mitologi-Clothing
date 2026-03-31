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
    return Order(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      orderNumber: json['order_number'] ?? '',
      status: json['status'] ?? 'pending',
      paymentStatus: json['payment_status'],
      total: json['total'] == null
          ? 0.0
          : double.tryParse(json['total'].toString()) ?? 0.0,
      subtotal: json['subtotal'] == null
          ? 0.0
          : double.tryParse(json['subtotal'].toString()) ?? 0.0,
      shippingCost: json['shipping_cost'] == null
          ? 0.0
          : double.tryParse(json['shipping_cost'].toString()) ?? 0.0,
      shippingAddress: json['shipping_address'] == null
          ? null
          : Address.fromJson(json['shipping_address']),
      itemsCount: json['items_count'] == null
          ? null
          : int.tryParse(json['items_count'].toString()),
      createdAt: json['created_at'] ?? '',
      items:
          (json['items'] as List<dynamic>?)
              ?.map((e) => OrderItem.fromJson(e))
              .toList() ??
          [],
      refundRequestedAt: json['refund_requested_at'],
      refundReason: json['refund_reason'],
    );
  }
}
