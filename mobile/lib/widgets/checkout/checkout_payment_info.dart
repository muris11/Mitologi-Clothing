import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../animations/animations.dart';

class CheckoutPaymentInfo extends StatelessWidget {
  const CheckoutPaymentInfo({super.key});

  @override
  Widget build(BuildContext context) {
    return FadeIn(
      child: Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: AppTheme.sectionBackground,
          borderRadius: AppTheme.radius24,
          border: Border.all(color: AppTheme.outlineLight.withValues(alpha: 0.5), width: 1.5),
          boxShadow: AppTheme.shadowSoft,
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: AppTheme.success.withValues(alpha: 0.08),
                    borderRadius: AppTheme.radius16,
                  ),
                  child: const Icon(
                    Icons.verified_user_rounded,
                    color: AppTheme.success,
                    size: 28,
                  ),
                ),
                const SizedBox(width: 16),
                const Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Keamanan Terjamin',
                        style: TextStyle(
                          fontWeight: FontWeight.w900,
                          fontSize: 16,
                          color: AppTheme.onSurface,
                        ),
                      ),
                      Text(
                        'Pembayaran Aman via Midtrans',
                        style: TextStyle(
                          fontSize: 12,
                          color: AppTheme.success,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            const Text(
              'Anda akan diarahkan ke gerbang pembayaran Midtrans yang aman untuk menyelesaikan transaksi menggunakan metode pilihan Anda (E-Wallet, Transfer Bank, atau Kartu Kredit).',
              style: TextStyle(
                color: AppTheme.onSurfaceVariant,
                height: 1.6,
                fontSize: 13,
                fontWeight: FontWeight.w500,
              ),
            ),
            const SizedBox(height: 24),
            const Divider(color: AppTheme.outlineLight, height: 1),
            const SizedBox(height: 20),
            const FadeInUp(
              delay: Duration(milliseconds: 200),
              child: PaymentHint(
                icon: Icons.lock_outline_rounded,
                text: 'Enkripsi data 256-bit standar bank untuk menjamin keamanan informasi Anda.',
              ),
            ),
            const SizedBox(height: 12),
            const FadeInUp(
              delay: Duration(milliseconds: 400),
              child: PaymentHint(
                icon: Icons.history_rounded,
                text: 'Detail transaksi dapat dipantau langsung melalui riwayat pesanan di akun Anda.',
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class PaymentHint extends StatelessWidget {
  const PaymentHint({
    super.key,
    required this.icon,
    required this.text,
  });

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 20, color: AppTheme.primary.withValues(alpha: 0.6)),
        const SizedBox(width: 12),
        Expanded(
          child: Text(
            text,
            style: const TextStyle(
              color: AppTheme.onSurfaceVariant,
              fontSize: 12,
              height: 1.5,
              fontWeight: FontWeight.w500,
            ),
          ),
        ),
      ],
    );
  }
}
