// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'cart_item.dart';


CartItem _$CartItemFromJson(Map<String, dynamic> json) => CartItem(
  id: json['id'] as String,
  merchandiseId: json['merchandiseId'] as String,
  title: json['title'] as String,
  quantity: (json['quantity'] as num).toInt(),
  price: Money.fromJson(json['price'] as Map<String, dynamic>),
  imageUrl: json['imageUrl'] as String?,
  variantTitle: json['variantTitle'] as String?,
);

Map<String, dynamic> _$CartItemToJson(CartItem instance) => <String, dynamic>{
  'id': instance.id,
  'merchandiseId': instance.merchandiseId,
  'title': instance.title,
  'quantity': instance.quantity,
  'price': instance.price,
  'imageUrl': instance.imageUrl,
  'variantTitle': instance.variantTitle,
};
