import 'money.dart';

class PriceRange {
  final Money maxVariantPrice;
  final Money minVariantPrice;

  PriceRange({required this.maxVariantPrice, required this.minVariantPrice});

  factory PriceRange.fromJson(Map<String, dynamic> json) {
    return PriceRange(
      maxVariantPrice: Money.fromJson(json['maxVariantPrice'] ?? {}),
      minVariantPrice: Money.fromJson(json['minVariantPrice'] ?? {}),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'maxVariantPrice': maxVariantPrice.toJson(),
      'minVariantPrice': minVariantPrice.toJson(),
    };
  }
}
