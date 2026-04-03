import 'package:json_annotation/json_annotation.dart';
import 'money.dart';
import 'cart_item.dart';

part 'cart.g.dart';

@JsonSerializable()
class CartCost {
  final Money subtotalAmount;
  final Money totalAmount;
  final Money totalTaxAmount;

  CartCost({
    required this.subtotalAmount,
    required this.totalAmount,
    required this.totalTaxAmount,
  });

  factory CartCost.fromJson(Map<String, dynamic> json) =>
      _$CartCostFromJson(json);

  Map<String, dynamic> toJson() => _$CartCostToJson(this);
}

@JsonSerializable()
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

  factory Cart.fromJson(Map<String, dynamic> json) => _$CartFromJson(json);

  Map<String, dynamic> toJson() => _$CartToJson(this);
}
