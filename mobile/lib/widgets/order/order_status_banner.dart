import 'package:flutter/material.dart';

import '../../config/theme.dart';

class OrderStatusBanner extends StatelessWidget {
  const OrderStatusBanner({
    super.key,
    required this.status,
    required this.orderNumber,
  });

  final String status;
  final String orderNumber;

  @override
  Widget build(BuildContext context) {
    Color statusColor = AppTheme.onSurface;
    String statusText = status;
    if (status == 'UNPAID' || status == 'PENDING') {
      statusColor = AppTheme.warning;
      statusText = 'Belum Bayar';
    } else if (status == 'PROCESSING') {
      statusColor = AppTheme.info;
      statusText = 'Pesanan Sedang Dikemas';
    } else if (status == 'SHIPPED') {
      statusColor = AppTheme.info;
      statusText = 'Pesanan Sedang Dikirim';
    } else if (status == 'COMPLETED') {
      statusColor = AppTheme.success;
      statusText = 'Pesanan Selesai';
    } else if (status == 'CANCELLED') {
      statusColor = AppTheme.error;
      statusText = 'Dibatalkan';
    }

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: statusColor.withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(Icons.info_outline, color: statusColor, size: 20),
              const SizedBox(width: 8),
              Text(
                statusText,
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  color: statusColor,
                  fontSize: 16,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            'No. Pesanan: $orderNumber',
            style: const TextStyle(
              color: AppTheme.onSurfaceVariant,
              fontSize: 13,
            ),
          ),
        ],
      ),
    );
  }
}
