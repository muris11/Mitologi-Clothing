import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../models/address.dart';
import '../../models/order_item.dart';
import '../../utils/price_formatter.dart';

class OrderAddressCard extends StatelessWidget {
  const OrderAddressCard({super.key, required this.address});

  final Address? address;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Icon(
                Icons.location_on_outlined,
                color: AppTheme.onSurface,
                size: 20,
              ),
              SizedBox(width: 8),
              Text(
                'Alamat Pengiriman',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
            ],
          ),
          const Divider(height: 24),
          Text(
            address?.recipientName ?? 'Nama Penerima',
            style: const TextStyle(fontWeight: FontWeight.w600),
          ),
          const SizedBox(height: 4),
          Text(
            address?.phone ?? '-',
            style: const TextStyle(
              color: AppTheme.onSurfaceVariant,
              fontSize: 13,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            address != null
                ? [
                    address!.addressLine1,
                    address!.city,
                    address!.province,
                    address!.postalCode,
                  ].where((s) => s.isNotEmpty).join(', ')
                : 'Alamat tidak tersedia',
            style: const TextStyle(
              color: AppTheme.onSurfaceVariant,
              fontSize: 13,
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }
}

class OrderItemsCard extends StatelessWidget {
  const OrderItemsCard({super.key, required this.items});

  final List<OrderItem> items;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Icon(
                Icons.shopping_bag_outlined,
                color: AppTheme.onSurface,
                size: 20,
              ),
              SizedBox(width: 8),
              Text(
                'Daftar Produk',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
            ],
          ),
          const Divider(height: 24),
          ...items.map(
            (item) => Padding(
              padding: const EdgeInsets.only(bottom: 16),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    width: 70,
                    height: 70,
                    decoration: BoxDecoration(
                      color: AppTheme.surfaceContainerLow,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: const Icon(
                      Icons.image_outlined,
                      color: AppTheme.surfaceContainerHigh,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          item.productTitle,
                          style: const TextStyle(fontWeight: FontWeight.w600),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          'Varian: ${item.variantTitle}',
                          style: const TextStyle(
                            color: AppTheme.onSurfaceVariant,
                            fontSize: 12,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              PriceFormatter.formatIDR(item.price),
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            Text(
                              'x${item.quantity}',
                              style: const TextStyle(
                                color: AppTheme.onSurfaceVariant,
                                fontSize: 13,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class OrderPaymentSummaryCard extends StatelessWidget {
  const OrderPaymentSummaryCard({
    super.key,
    required this.subtotal,
    required this.shippingCost,
    required this.total,
  });

  final double subtotal;
  final double shippingCost;
  final double total;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Icon(Icons.payment_outlined, color: AppTheme.onSurface, size: 20),
              SizedBox(width: 8),
              Text(
                'Rincian Pembayaran',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
            ],
          ),
          const Divider(height: 24),
          _SummaryRow('Subtotal Produk', PriceFormatter.formatIDR(subtotal)),
          const SizedBox(height: 8),
          _SummaryRow('Ongkos Kirim', PriceFormatter.formatIDR(shippingCost)),
          const SizedBox(height: 8),
          _SummaryRow('Pajak', PriceFormatter.formatIDR(0)),
          const Divider(height: 24),
          _SummaryRow(
            'Total Tagihan',
            PriceFormatter.formatIDR(total),
            highlight: true,
          ),
        ],
      ),
    );
  }
}

class _SummaryRow extends StatelessWidget {
  const _SummaryRow(this.label, this.value, {this.highlight = false});

  final String label;
  final String value;
  final bool highlight;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: TextStyle(
            color: highlight ? AppTheme.onSurface : AppTheme.onSurfaceVariant,
            fontWeight: highlight ? FontWeight.bold : FontWeight.normal,
            fontSize: highlight ? 16 : 14,
          ),
        ),
        Text(
          value,
          style: TextStyle(
            fontWeight: highlight ? FontWeight.bold : FontWeight.normal,
            color: AppTheme.onSurface,
            fontSize: highlight ? 16 : 14,
          ),
        ),
      ],
    );
  }
}
