import 'package:json_annotation/json_annotation.dart';
import 'money.dart';

part 'cart_item.g.dart';

@JsonSerializable()
class CartItem {
  final String id;
  final String merchandiseId;
  final String title;
  final int quantity;
  final Money price;
  final String? imageUrl;
  final String? variantTitle;

  CartItem({
    required this.id,
    required this.merchandiseId,
    required this.title,
    required this.quantity,
    required this.price,
    this.imageUrl,
    this.variantTitle,
  });

  factory CartItem.fromJson(Map<String, dynamic> json) =>
      _$CartItemFromJson(json);

  Map<String, dynamic> toJson() => _$CartItemToJson(this);
}
