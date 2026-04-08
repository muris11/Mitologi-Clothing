import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/cart_provider.dart';
import '../../providers/order_provider.dart';
import '../../config/theme.dart';
import '../../utils/price_formatter.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/checkout/checkout_step_card.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/animations/blur_fade.dart';

class CheckoutScreen extends StatefulWidget {
  const CheckoutScreen({super.key});

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  int _currentStep = 0;

  // Form controls
  final _formKey = GlobalKey<FormState>();
  // Simplified Address fields for MVP
  String _firstName = '';
  String _lastName = '';
  String _address1 = '';
  String _city = '';
  String _province = '';
  String _zip = '';
  String _phone = '';

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Checkout',
      subtitle:
          'Lengkapi alamat, ringkasan, dan pembayaran dengan alur yang lebih rapi.',
      showLogo: false,
      leading: IconButton(
        tooltip: 'Back',
        icon: const Icon(Icons.arrow_back, color: AppTheme.onSurface),
        onPressed: () => context.popOrGoShop(),
      ),
      bodyPadding: EdgeInsets.zero,
      body: Consumer<CartProvider>(
        builder: (context, cartProvider, child) {
          final cart = cartProvider.cart;
          if (cart == null || cart.lines.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(
                    Icons.shopping_bag_outlined,
                    size: 64,
                    color: AppTheme.muted,
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    'Keranjang kosong',
                    style: TextStyle(color: AppTheme.onSurfaceVariant),
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () => context.go('/'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.accent,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: AppTheme.radius16,
                      ),
                    ),
                    child: const Text('Mulai Belanja'),
                  ),
                ],
              ),
            );
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(20, 16, 20, 32),
            child: Column(
              children: [
                // Address Step
                FadeInUp(
                  delay: const Duration(milliseconds: 100),
                  child: CheckoutStepCard(
                    title: 'Alamat Pengiriman',
                    index: 1,
                    isActive: _currentStep == 0,
                    child: _buildAddressForm(),
                  ),
                ),
                const SizedBox(height: 16),
                // Order Summary Step
                FadeInUp(
                  delay: const Duration(milliseconds: 200),
                  child: CheckoutStepCard(
                    title: 'Ringkasan Pesanan',
                    index: 2,
                    isActive: _currentStep == 1,
                    child: _buildOrderSummary(cartProvider),
                  ),
                ),
                const SizedBox(height: 16),
                // Payment Step
                FadeInUp(
                  delay: const Duration(milliseconds: 300),
                  child: CheckoutStepCard(
                    title: 'Pembayaran',
                    index: 3,
                    isActive: _currentStep == 2,
                    child: _buildPaymentInfo(),
                  ),
                ),
                const SizedBox(height: 24),
                Row(
                  children: [
                    if (_currentStep > 0) ...[
                      Expanded(
                        child: SizedBox(
                          height: 56,
                          child: ElevatedButton(
                            onPressed: () => setState(() => _currentStep -= 1),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.surfaceContainerLow,
                              foregroundColor: AppTheme.onSurfaceVariant,
                              elevation: 0,
                              shape: RoundedRectangleBorder(
                                borderRadius: AppTheme.radius16,
                              ),
                            ),
                            child: const Text(
                              'Kembali',
                              style: TextStyle(
                                fontWeight: FontWeight.w600,
                                fontSize: 15,
                              ),
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(width: 12),
                    ],
                    Expanded(
                      child: SizedBox(
                        height: 56,
                        child: ElevatedButton(
                          onPressed: () {
                            if (_currentStep == 0) {
                              if (_formKey.currentState!.validate()) {
                                _formKey.currentState!.save();
                                setState(() => _currentStep += 1);
                              }
                            } else if (_currentStep == 1) {
                              setState(() => _currentStep += 1);
                            } else {
                              _handleCheckout();
                            }
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.accent,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: AppTheme.radius16,
                            ),
                          ),
                          child: Text(
                            _currentStep == 2
                                ? 'Bayar Sekarang'
                                : 'Selanjutnya',
                            style: const TextStyle(
                              fontWeight: FontWeight.w600,
                              fontSize: 15,
                            ),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  InputDecoration _inputDecoration(String label) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: AppTheme.onSurfaceVariant, fontSize: 14),
      filled: true,
      fillColor: AppTheme.surfaceContainerLow,
      border: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: BorderSide.none,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: BorderSide.none,
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: const BorderSide(color: AppTheme.accent, width: 2),
      ),
      contentPadding: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context),
        vertical: 16,
      ),
    );
  }

  Widget _buildAddressForm() {
    return Form(
      key: _formKey,
      child: Column(
        children: [
          Row(
            children: [
              Expanded(
                child: TextFormField(
                  decoration: _inputDecoration('Nama Depan'),
                  validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                  onSaved: (v) => _firstName = v!,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: TextFormField(
                  decoration: _inputDecoration('Nama Belakang'),
                  validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                  onSaved: (v) => _lastName = v!,
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          TextFormField(
            decoration: _inputDecoration('No. WhatsApp / HP'),
            keyboardType: TextInputType.phone,
            validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
            onSaved: (v) => _phone = v!,
          ),
          const SizedBox(height: 16),
          TextFormField(
            decoration: _inputDecoration('Alamat Lengkap (Jalan, No Rumah)'),
            maxLines: 2,
            validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
            onSaved: (v) => _address1 = v!,
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(
                child: TextFormField(
                  decoration: _inputDecoration('Kota/Kabupaten'),
                  validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                  onSaved: (v) => _city = v!,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: TextFormField(
                  decoration: _inputDecoration('Provinsi'),
                  validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                  onSaved: (v) => _province = v!,
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          TextFormField(
            decoration: _inputDecoration('Kode Pos'),
            keyboardType: TextInputType.number,
            validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
            onSaved: (v) => _zip = v!,
          ),
        ],
      ),
    );
  }

  Widget _buildOrderSummary(CartProvider provider) {
    if (provider.cart == null) return const SizedBox();

    double subtotal =
        double.tryParse(provider.cart!.cost.subtotalAmount.amount) ?? 0;
    final totalTax =
        double.tryParse(provider.cart!.cost.totalTaxAmount.amount) ?? 0;
    final backendTotal =
        double.tryParse(provider.cart!.cost.totalAmount.amount) ?? subtotal;
    final shipping = (backendTotal - subtotal - totalTax)
        .clamp(0, double.infinity)
        .toDouble();
    final total = subtotal + shipping;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          '${provider.itemCount} Produk Terpilih',
          style: const TextStyle(
            fontWeight: FontWeight.bold,
            color: AppTheme.onSurface,
          ),
        ),
        const Divider(height: 32, color: AppTheme.muted),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text('Subtotal', style: TextStyle(color: AppTheme.onSurfaceVariant)),
            Text(
              PriceFormatter.formatIDR(subtotal),
              style: const TextStyle(
                fontWeight: FontWeight.w600,
                color: AppTheme.onSurface,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Ongkos Kirim',
              style: TextStyle(color: AppTheme.onSurfaceVariant),
            ),
            Text(
              PriceFormatter.formatIDR(shipping),
              style: const TextStyle(
                fontWeight: FontWeight.w600,
                color: AppTheme.onSurface,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Pajak PPN (Termasuk)',
              style: TextStyle(color: AppTheme.onSurfaceVariant),
            ),
            Text(
              PriceFormatter.formatIDR(totalTax),
              style: const TextStyle(
                fontWeight: FontWeight.w600,
                color: AppTheme.onSurface,
              ),
            ),
          ],
        ),
        const Padding(
          padding: EdgeInsets.symmetric(vertical: 8),
          child: Divider(color: AppTheme.muted),
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Total Tagihan',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15),
            ),
            Text(
              PriceFormatter.formatIDR(total),
              style: const TextStyle(
                fontWeight: FontWeight.w800,
                color: AppTheme.primary,
                fontSize: 18,
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildPaymentInfo() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: AppTheme.radius16,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppTheme.success.withValues(alpha: 0.1),
                  borderRadius: AppTheme.radius12,
                ),
                child: const Icon(
                  Icons.shield_outlined,
                  color: AppTheme.success,
                  size: 24,
                ),
              ),
              const SizedBox(width: 12),
              const Expanded(
                child: Text(
                  'Pembayaran Aman Terverifikasi',
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    color: AppTheme.onSurface,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          const Text(
            'Setelah menekan tombol Bayar Sekarang, Anda akan diarahkan ke sistem pembayaran aman Midtrans untuk memilih metode pembayaran (Transfer Bank, e-Wallet, Kartu Kredit, dll).',
            style: TextStyle(
              color: AppTheme.slate600,
              height: 1.5,
              fontSize: 13,
            ),
          ),
        ],
      ),
    );
  }

  void _handleCheckout() async {
    final cartProvider = context.read<CartProvider>();
    final orderProvider = context.read<OrderProvider>();
    final cartItems = cartProvider.cart?.lines ?? [];

    if (cartItems.isEmpty) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Keranjang anda kosong')));
      return;
    }

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => const Center(
        child: CircularProgressIndicator(color: AppTheme.primary),
      ),
    );

    // Construct Payload (flat fields untuk backend Laravel)
    final payload = {
      'shipping_name': '$_firstName $_lastName'.trim(),
      'shipping_phone': _phone,
      'shipping_address': _address1,
      'shipping_city': _city,
      'shipping_province': _province,
      'shipping_postal_code': _zip,
    };

    try {
      final response = await orderProvider.checkout(payload);

      if (!mounted) return;
      Navigator.pop(context); // Close loading

      if (response is Map<String, dynamic> &&
          response.containsKey('snap_token')) {
        final snapToken = response['snap_token'];
        final snapRedirectUrl =
            response['redirect_url'] ?? response['snap_redirect_url'];
        final orderNumber = response['order_number'];

        final targetUrl =
            snapRedirectUrl ??
            'https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken';

        if (!mounted) return;
        cartProvider.clearCart();
        context.go(
          '/payment/$orderNumber?url=${Uri.encodeComponent(targetUrl)}',
        );
      } else if (response is Map<String, dynamic>) {
        // Handle direct order success (e.g., COD)
        final orderNumber = response['order_number'] ?? '';
        if (!mounted) return;
        cartProvider.clearCart();
        context.go('/shop/checkout/success?order_number=$orderNumber');
      } else {
        final providerError =
            orderProvider.error ?? 'Respon checkout tidak valid.';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Gagal membuat pesanan: $providerError'),
            backgroundColor: AppTheme.error,
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    } catch (e) {
      if (!mounted) return;
      Navigator.pop(context); // Close loading
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Gagal membuat pesanan: $e'),
          backgroundColor: AppTheme.error,
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }
}
