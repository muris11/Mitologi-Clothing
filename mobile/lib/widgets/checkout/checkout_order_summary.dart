import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../providers/cart_provider.dart';
import '../../utils/price_formatter.dart';
import '../animations/animations.dart';

class CheckoutOrderSummary extends StatelessWidget {
  final CartProvider cartProvider;

  const CheckoutOrderSummary({
    super.key,
    required this.cartProvider,
  });

  @override
  Widget build(BuildContext context) {
    if (cartProvider.cart == null) return const SizedBox();

    final double subtotal =
        double.tryParse(cartProvider.cart!.cost.subtotalAmount.amount) ?? 0;
    final totalTax =
        double.tryParse(cartProvider.cart!.cost.totalTaxAmount.amount) ?? 0;
    final backendTotal =
        double.tryParse(cartProvider.cart!.cost.totalAmount.amount) ?? subtotal;
    final shipping = (backendTotal - subtotal - totalTax)
        .clamp(0, double.infinity)
        .toDouble();
    final total = subtotal + shipping;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        FadeIn(
          child: Text(
            '${cartProvider.itemCount} Produk Terpilih',
            style: const TextStyle(
              fontWeight: FontWeight.w900,
              fontSize: 16,
              letterSpacing: -0.5,
              color: AppTheme.onSurface,
            ),
          ),
        ),
        const SizedBox(height: 16),
        FadeInUp(
          delay: const Duration(milliseconds: 100),
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: AppTheme.primary.withValues(alpha: 0.05),
              borderRadius: AppTheme.radius16,
              border: Border.all(color: AppTheme.primary.withValues(alpha: 0.1)),
            ),
            child: const Row(
              children: [
                Icon(
                  Icons.inventory_2_outlined,
                  size: 20,
                  color: AppTheme.primary,
                ),
                SizedBox(width: 12),
                Expanded(
                  child: Text(
                    'Pesanan akan langsung kami siapkan setelah pembayaran Anda berhasil diverifikasi.',
                    style: TextStyle(
                      color: AppTheme.onSurface,
                      fontSize: 12,
                      fontWeight: FontWeight.w500,
                      height: 1.5,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 24),
        
        // Rows
        _SummaryRow(
          label: 'Subtotal Produk',
          value: PriceFormatter.formatIDR(subtotal),
          delay: 200,
        ),
        _SummaryRow(
          label: 'Biaya Pengiriman',
          value: PriceFormatter.formatIDR(shipping),
          delay: 300,
        ),
        _SummaryRow(
          label: 'Pajak PPN (Inklusif)',
          value: PriceFormatter.formatIDR(totalTax),
          delay: 400,
        ),
        
        const Padding(
          padding: EdgeInsets.symmetric(vertical: 16),
          child: Divider(color: AppTheme.outlineLight, height: 1),
        ),
        
        FadeInUp(
          delay: const Duration(milliseconds: 500),
          child: Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: AppTheme.surfaceContainerLowest,
              borderRadius: AppTheme.radius16,
              border: Border.all(color: AppTheme.outlineLight, width: 1.5),
              boxShadow: AppTheme.shadowSoft,
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Total Tagihan',
                  style: TextStyle(
                    fontWeight: FontWeight.w900,
                    fontSize: 16,
                    color: AppTheme.onSurface,
                  ),
                ),
                Text(
                  PriceFormatter.formatIDR(total),
                  style: const TextStyle(
                    fontWeight: FontWeight.w900,
                    color: AppTheme.primary,
                    fontSize: 20,
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}

class _SummaryRow extends StatelessWidget {
  final String label;
  final String value;
  final int delay;

  const _SummaryRow({
    required this.label,
    required this.value,
    required this.delay,
  });

  @override
  Widget build(BuildContext context) {
    return FadeInUp(
      delay: Duration(milliseconds: delay),
      child: Padding(
        padding: const EdgeInsets.only(bottom: 12),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              label,
              style: const TextStyle(
                color: AppTheme.onSurfaceVariant,
                fontWeight: FontWeight.w500,
                fontSize: 14,
              ),
            ),
            Text(
              value,
              style: const TextStyle(
                fontWeight: FontWeight.w700,
                color: AppTheme.onSurface,
                fontSize: 14,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
