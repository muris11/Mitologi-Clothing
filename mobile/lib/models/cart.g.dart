// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'cart.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

CartCost _$CartCostFromJson(Map<String, dynamic> json) => CartCost(
  subtotalAmount: Money.fromJson(
    json['subtotalAmount'] as Map<String, dynamic>,
  ),
  totalAmount: Money.fromJson(json['totalAmount'] as Map<String, dynamic>),
  totalTaxAmount: Money.fromJson(
    json['totalTaxAmount'] as Map<String, dynamic>,
  ),
);

Map<String, dynamic> _$CartCostToJson(CartCost instance) => <String, dynamic>{
  'subtotalAmount': instance.subtotalAmount,
  'totalAmount': instance.totalAmount,
  'totalTaxAmount': instance.totalTaxAmount,
};

Cart _$CartFromJson(Map<String, dynamic> json) => Cart(
  id: json['id'] as String?,
  sessionId: json['sessionId'] as String?,
  checkoutUrl: json['checkoutUrl'] as String,
  cost: CartCost.fromJson(json['cost'] as Map<String, dynamic>),
  lines: (json['lines'] as List<dynamic>)
      .map((e) => CartItem.fromJson(e as Map<String, dynamic>))
      .toList(),
  totalQuantity: (json['totalQuantity'] as num).toInt(),
);

Map<String, dynamic> _$CartToJson(Cart instance) => <String, dynamic>{
  'id': instance.id,
  'sessionId': instance.sessionId,
  'checkoutUrl': instance.checkoutUrl,
  'cost': instance.cost,
  'lines': instance.lines,
  'totalQuantity': instance.totalQuantity,
};
