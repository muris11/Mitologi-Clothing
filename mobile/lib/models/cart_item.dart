import 'money.dart';
import 'product_variant.dart';

class Merchandise {
  final String id;
  final String title;
  final List<SelectedOption> selectedOptions;
  final CartProduct product;

  Merchandise({
    required this.id,
    required this.title,
    required this.selectedOptions,
    required this.product,
  });

  factory Merchandise.fromJson(Map<String, dynamic> json) {
    return Merchandise(
      id: json['id']?.toString() ?? '',
      title: json['title'] ?? '',
      selectedOptions:
          (json['selectedOptions'] as List<dynamic>?)
              ?.map((e) => SelectedOption.fromJson(e))
              .toList() ??
          [],
      product: CartProduct.fromJson(json['product'] ?? {}),
    );
  }
}

class CartProduct {
  final String id;
  final String handle;
  final String title;
  final String featuredImage;

  CartProduct({
    required this.id,
    required this.handle,
    required this.title,
    required this.featuredImage,
  });

  factory CartProduct.fromJson(Map<String, dynamic> json) {
    return CartProduct(
      id: json['id']?.toString() ?? '',
      handle: json['handle'] ?? '',
      title: json['title'] ?? '',
      featuredImage: json['featuredImage']?['url'] ?? '',
    );
  }
}

class CartItemCost {
  final Money totalAmount;

  CartItemCost({required this.totalAmount});

  factory CartItemCost.fromJson(Map<String, dynamic> json) {
    return CartItemCost(totalAmount: Money.fromJson(json['totalAmount'] ?? {}));
  }
}

class CartItem {
  final String? id;
  final int quantity;
  final CartItemCost cost;
  final Merchandise merchandise;

  CartItem({
    this.id,
    required this.quantity,
    required this.cost,
    required this.merchandise,
  });

  factory CartItem.fromJson(Map<String, dynamic> json) {
    return CartItem(
      id: json['id']?.toString(),
      quantity: int.tryParse(json['quantity']?.toString() ?? '1') ?? 1,
      cost: CartItemCost.fromJson(json['cost'] ?? {}),
      merchandise: Merchandise.fromJson(json['merchandise'] ?? {}),
    );
  }
}
