import 'money.dart';

class PriceRange {
  final Money maxVariantPrice;
  final Money minVariantPrice;

  PriceRange({required this.maxVariantPrice, required this.minVariantPrice});

  factory PriceRange.fromJson(Map<String, dynamic> json) {
    return PriceRange(
      maxVariantPrice: Money.fromJson(
        json['maxVariantPrice'] is Map<String, dynamic>
            ? json['maxVariantPrice'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
      minVariantPrice: Money.fromJson(
        json['minVariantPrice'] is Map<String, dynamic>
            ? json['minVariantPrice'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'maxVariantPrice': maxVariantPrice.toJson(),
      'minVariantPrice': minVariantPrice.toJson(),
    };
  }
}
