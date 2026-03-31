import 'package:intl/intl.dart';

class FormatUtils {
  static String formatRupiah(double amount) {
    final formatCurrency = NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp ',
      decimalDigits: 0,
    );
    return formatCurrency.format(amount);
  }
}

class DateUtils {
  static String formatDateTime(String isoDate) {
    if (isoDate.isEmpty) return '-';
    try {
      final DateTime date = DateTime.parse(isoDate).toLocal();
      final formatter = DateFormat('dd MMM yyyy, HH:mm', 'id_ID');
      return formatter.format(date);
    } catch (e) {
      return isoDate;
    }
  }

  static String formatDate(String isoDate) {
    if (isoDate.isEmpty) return '-';
    try {
      final DateTime date = DateTime.parse(isoDate).toLocal();
      final formatter = DateFormat('dd MMM yyyy', 'id_ID');
      return formatter.format(date);
    } catch (e) {
      return isoDate;
    }
  }
}
