import 'package:intl/intl.dart';

class PriceFormatter {
  static String formatIDR(double amount) {
    final formatCurrency = NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp',
      decimalDigits: 0,
    );
    return formatCurrency.format(amount);
  }

  static String formatStringIDR(String amountStr) {
    final double? amount = double.tryParse(amountStr);
    if (amount == null) return 'Rp0';
    return formatIDR(amount);
  }
}
