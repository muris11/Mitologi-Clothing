class OrderItem {
  final int id;
  final String productTitle;
  final String variantTitle;
  final double price;
  final int quantity;
  final double total;
  final String? productHandle;
  final String? productImage;

  OrderItem({
    required this.id,
    required this.productTitle,
    required this.variantTitle,
    required this.price,
    required this.quantity,
    required this.total,
    this.productHandle,
    this.productImage,
  });

  factory OrderItem.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    String parseString(dynamic value, [String fallback = '']) {
      if (value is String) return value;
      return value?.toString() ?? fallback;
    }

    return OrderItem(
      id: parseInt(json['id'], 0),
      productTitle: parseString(json['product_title'] ?? json['productTitle']),
      variantTitle: parseString(json['variant_title'] ?? json['variantTitle']),
      price: json['price'] == null
          ? 0.0
          : double.tryParse(json['price'].toString()) ?? 0.0,
      quantity: json['quantity'] == null
          ? 1
          : int.tryParse(json['quantity'].toString()) ?? 1,
      total: json['total'] == null
          ? 0.0
          : double.tryParse(json['total'].toString()) ?? 0.0,
      productHandle: (json['product_handle'] ?? json['productHandle'])
          ?.toString(),
      productImage: (json['product_image'] ?? json['productImage'])?.toString(),
    );
  }
}
