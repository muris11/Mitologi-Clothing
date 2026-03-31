import 'money.dart';

class SelectedOption {
  final String name;
  final String value;

  SelectedOption({required this.name, required this.value});

  factory SelectedOption.fromJson(Map<String, dynamic> json) {
    return SelectedOption(name: json['name'] ?? '', value: json['value'] ?? '');
  }

  Map<String, dynamic> toJson() {
    return {'name': name, 'value': value};
  }
}

class ProductVariant {
  final String id;
  final String title;
  final bool availableForSale;
  final List<SelectedOption> selectedOptions;
  final Money price;
  final String? sku;
  final int? stock;

  ProductVariant({
    required this.id,
    required this.title,
    required this.availableForSale,
    required this.selectedOptions,
    required this.price,
    this.sku,
    this.stock,
  });

  factory ProductVariant.fromJson(Map<String, dynamic> json) {
    return ProductVariant(
      id: json['id']?.toString() ?? '',
      title: json['title'] ?? '',
      availableForSale: json['availableForSale'] ?? false,
      selectedOptions:
          (json['selectedOptions'] as List<dynamic>?)
              ?.map((e) => SelectedOption.fromJson(e))
              .toList() ??
          [],
      price: Money.fromJson(json['price'] ?? {}),
      sku: json['sku']?.toString(),
      stock: int.tryParse(json['stock']?.toString() ?? ''),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'availableForSale': availableForSale,
      'selectedOptions': selectedOptions.map((e) => e.toJson()).toList(),
      'price': price.toJson(),
      'sku': sku,
      'stock': stock,
    };
  }
}
