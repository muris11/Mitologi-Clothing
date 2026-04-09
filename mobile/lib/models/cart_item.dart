import 'money.dart';

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

  factory CartItem.fromJson(Map<String, dynamic> json) {
    final merchandise = json['merchandise'] is Map
        ? Map<String, dynamic>.from(json['merchandise'] as Map)
        : <String, dynamic>{};
    final product = merchandise['product'] is Map
        ? Map<String, dynamic>.from(merchandise['product'] as Map)
        : <String, dynamic>{};
    final featuredImage = product['featuredImage'] is Map
        ? Map<String, dynamic>.from(product['featuredImage'] as Map)
        : <String, dynamic>{};
    final totalAmount =
        json['cost'] is Map && (json['cost'] as Map)['totalAmount'] is Map
        ? Map<String, dynamic>.from((json['cost'] as Map)['totalAmount'] as Map)
        : <String, dynamic>{};

    // Safe price parsing with fallback
    Money parsePrice(dynamic priceValue) {
      if (priceValue is Map<String, dynamic>) {
        return Money.fromJson(priceValue);
      }
      if (priceValue is Map) {
        return Money.fromJson(Map<String, dynamic>.from(priceValue));
      }
      return const Money(amount: '0', currencyCode: 'IDR');
    }

    return CartItem(
      id: json['id']?.toString() ?? '',
      merchandiseId:
          json['merchandiseId']?.toString() ??
          merchandise['id']?.toString() ??
          '',
      title: json['title']?.toString() ?? product['title']?.toString() ?? '',
      quantity: json['quantity'] is int
          ? json['quantity'] as int
          : int.tryParse(json['quantity']?.toString() ?? '0') ?? 0,
      price: json['price'] != null
          ? parsePrice(json['price'])
          : (totalAmount.isNotEmpty
                ? Money.fromJson(totalAmount)
                : const Money(amount: '0', currencyCode: 'IDR')),
      imageUrl:
          json['imageUrl']?.toString() ?? featuredImage['url']?.toString(),
      variantTitle:
          json['variantTitle']?.toString() ?? merchandise['title']?.toString(),
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'merchandiseId': merchandiseId,
    'title': title,
    'quantity': quantity,
    'price': price.toJson(),
    'imageUrl': imageUrl,
    'variantTitle': variantTitle,
  };
}
