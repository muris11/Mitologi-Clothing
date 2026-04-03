import 'package:json_annotation/json_annotation.dart';

part 'money.g.dart';

@JsonSerializable()
class Money {
  final double amount;
  final String currencyCode;

  const Money({required this.amount, required this.currencyCode});

  factory Money.fromJson(Map<String, dynamic> json) => _$MoneyFromJson(json);

  Map<String, dynamic> toJson() => _$MoneyToJson(this);

  @override
  String toString() =>
      '${currencyCode == 'IDR' ? 'Rp' : currencyCode} ${amount.toStringAsFixed(0)}';
}
