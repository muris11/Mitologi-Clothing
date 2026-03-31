import 'money.dart';
import 'cart_item.dart';

class CartCost {
  final Money subtotalAmount;
  final Money totalAmount;
  final Money totalTaxAmount;

  CartCost({
    required this.subtotalAmount,
    required this.totalAmount,
    required this.totalTaxAmount,
  });

  factory CartCost.fromJson(Map<String, dynamic> json) {
    return CartCost(
      subtotalAmount: Money.fromJson(json['subtotalAmount'] ?? {}),
      totalAmount: Money.fromJson(json['totalAmount'] ?? {}),
      totalTaxAmount: Money.fromJson(json['totalTaxAmount'] ?? {}),
    );
  }
}

class Cart {
  final String? id;
  final String? sessionId;
  final String checkoutUrl;
  final CartCost cost;
  final List<CartItem> lines;
  final int totalQuantity;

  Cart({
    this.id,
    this.sessionId,
    required this.checkoutUrl,
    required this.cost,
    required this.lines,
    required this.totalQuantity,
  });

  factory Cart.fromJson(Map<String, dynamic> json) {
    return Cart(
      id: json['id']?.toString(),
      sessionId: json['sessionId']?.toString(),
      checkoutUrl: json['checkoutUrl'] ?? '',
      cost: CartCost.fromJson(json['cost'] ?? {}),
      lines:
          (json['lines'] as List<dynamic>?)
              ?.map((e) => CartItem.fromJson(e))
              .toList() ??
          [],
      totalQuantity: json['totalQuantity'] == null
          ? 0
          : int.tryParse(json['totalQuantity'].toString()) ?? 0,
    );
  }
}
