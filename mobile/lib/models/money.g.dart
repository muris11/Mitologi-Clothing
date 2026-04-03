// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'money.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

Money _$MoneyFromJson(Map<String, dynamic> json) => Money(
  amount: (json['amount'] as num).toDouble(),
  currencyCode: json['currencyCode'] as String,
);

Map<String, dynamic> _$MoneyToJson(Money instance) => <String, dynamic>{
  'amount': instance.amount,
  'currencyCode': instance.currencyCode,
};
