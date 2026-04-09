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
    // Safe helper to get Money from nested JSON
    Money safeMoneyFromJson(dynamic value) {
      if (value is Map<String, dynamic>) {
        return Money.fromJson(value);
      }
      if (value is Map) {
        return Money.fromJson(Map<String, dynamic>.from(value));
      }
      // Fallback to zero amount
      return const Money(amount: '0', currencyCode: 'IDR');
    }

    return CartCost(
      subtotalAmount: safeMoneyFromJson(
        json['subtotalAmount'] ?? json['subtotal_amount'],
      ),
      totalAmount: safeMoneyFromJson(
        json['totalAmount'] ?? json['total_amount'],
      ),
      totalTaxAmount: safeMoneyFromJson(
        json['totalTaxAmount'] ?? json['total_tax_amount'],
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
    // Safely parse cost with fallback to empty values
    final costJson = json['cost'] is Map<String, dynamic>
        ? json['cost'] as Map<String, dynamic>
        : <String, dynamic>{};

    return Cart(
      id: json['id']?.toString(),
      sessionId: json['sessionId']?.toString(),
      checkoutUrl: json['checkoutUrl']?.toString() ?? '/checkout',
      cost: CartCost.fromJson(costJson),
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
