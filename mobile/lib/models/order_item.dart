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
    return OrderItem(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      productTitle: json['product_title'] ?? '',
      variantTitle: json['variant_title'] ?? '',
      price: json['price'] == null
          ? 0.0
          : double.tryParse(json['price'].toString()) ?? 0.0,
      quantity: json['quantity'] == null
          ? 1
          : int.tryParse(json['quantity'].toString()) ?? 1,
      total: json['total'] == null
          ? 0.0
          : double.tryParse(json['total'].toString()) ?? 0.0,
      productHandle: json['product_handle'],
      productImage: json['product_image'],
    );
  }
}
