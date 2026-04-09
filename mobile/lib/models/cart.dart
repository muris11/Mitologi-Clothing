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
      subtotalAmount: Money.fromJson(
        Map<String, dynamic>.from(json['subtotalAmount'] as Map),
      ),
      totalAmount: Money.fromJson(
        Map<String, dynamic>.from(json['totalAmount'] as Map),
      ),
      totalTaxAmount: Money.fromJson(
        Map<String, dynamic>.from(json['totalTaxAmount'] as Map),
      ),
    );
  }

  Map<String, dynamic> toJson() => {
    'subtotalAmount': subtotalAmount.toJson(),
    'totalAmount': totalAmount.toJson(),
    'totalTaxAmount': totalTaxAmount.toJson(),
  };
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
      checkoutUrl: json['checkoutUrl']?.toString() ?? '/checkout',
      cost: CartCost.fromJson(Map<String, dynamic>.from(json['cost'] as Map)),
      lines: (json['lines'] as List<dynamic>? ?? <dynamic>[])
          .map((e) => CartItem.fromJson(Map<String, dynamic>.from(e as Map)))
          .toList(),
      totalQuantity: json['totalQuantity'] is int
          ? json['totalQuantity'] as int
          : int.tryParse(json['totalQuantity']?.toString() ?? '0') ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'sessionId': sessionId,
    'checkoutUrl': checkoutUrl,
    'cost': cost.toJson(),
    'lines': lines.map((e) => e.toJson()).toList(),
    'totalQuantity': totalQuantity,
  };
}
