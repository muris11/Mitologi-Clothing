class Money {
  final String amount;
  final String currencyCode;

  Money({required this.amount, required this.currencyCode});

  factory Money.fromJson(Map<String, dynamic> json) {
    return Money(
      amount: json['amount']?.toString() ?? '0',
      currencyCode: json['currencyCode']?.toString() ?? 'IDR',
    );
  }

  Map<String, dynamic> toJson() {
    return {'amount': amount, 'currencyCode': currencyCode};
  }
}
