import 'package:json_annotation/json_annotation.dart';

part 'money.g.dart';

@JsonSerializable()
class Money {
  // Keep as String for backward compatibility
  final String amount;
  final String currencyCode;

  const Money({required this.amount, required this.currencyCode});

  factory Money.fromJson(Map<String, dynamic> json) => _$MoneyFromJson(json);

  Map<String, dynamic> toJson() => _$MoneyToJson(this);

  @override
  String toString() {
    final value = double.tryParse(amount) ?? 0.0;
    return '${currencyCode == 'IDR' ? 'Rp' : currencyCode} ${value.toStringAsFixed(0)}';
  }
}
