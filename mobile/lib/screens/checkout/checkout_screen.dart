import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/cart_provider.dart';
import '../../providers/order_provider.dart';
import '../../config/theme.dart';
import '../../widgets/checkout/checkout_step_card.dart';
import '../../widgets/checkout/checkout_progress_header.dart';
import '../../widgets/checkout/checkout_address_form.dart';
import '../../widgets/checkout/checkout_order_summary.dart';
import '../../widgets/checkout/checkout_payment_info.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/animations/shimmer_button.dart';

class CheckoutScreen extends StatefulWidget {
  const CheckoutScreen({super.key});

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  int _currentStep = 0;
  static const _stepTitles = ['Alamat', 'Ringkasan', 'Bayar'];

  // Form controls
  final _formKey = GlobalKey<FormState>();
  
  // Address fields
  String _firstName = '';
  String _lastName = '';
  String _address1 = '';
  String _city = '';
  String _province = '';
  String _zip = '';
  String _phone = '';

  bool _isProcessing = false;

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Checkout',
      subtitle:
          'Lengkapi alamat, ringkasan, dan pembayaran dengan alur yang lebih rapi.',
      showLogo: false,
      leading: IconButton(
        tooltip: 'Kembali',
        icon: const Icon(Icons.arrow_back, color: AppTheme.onSurface),
        onPressed: () => context.popOrGoShop(),
      ),
      bodyPadding: EdgeInsets.zero,
      body: Consumer<CartProvider>(
        builder: (context, cartProvider, child) {
          final cart = cartProvider.cart;
          if (cart == null || cart.lines.isEmpty) {
            return _buildEmptyCart(context);
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(20, 16, 20, 32),
            child: Column(
              children: [
                Semantics(
                  label: 'Indikator langkah checkout',
                  child: CheckoutProgressHeader(
                    currentStep: _currentStep,
                    stepTitles: _stepTitles,
                    cartProvider: cartProvider,
                  ),
                ),
                const SizedBox(height: 16),
                
                // Address Step
                FadeInUp(
                  delay: const Duration(milliseconds: 100),
                  child: CheckoutStepCard(
                    title: 'Alamat Pengiriman',
                    index: 1,
                    isActive: _currentStep == 0,
                    child: CheckoutAddressForm(
                      formKey: _formKey,
                      onFirstNameSaved: (v) => _firstName = v,
                      onLastNameSaved: (v) => _lastName = v,
                      onPhoneSaved: (v) => _phone = v,
                      onAddressSaved: (v) => _address1 = v,
                      onCitySaved: (v) => _city = v,
                      onProvinceSaved: (v) => _province = v,
                      onZipSaved: (v) => _zip = v,
                    ),
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
                    child: CheckoutOrderSummary(cartProvider: cartProvider),
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
                    child: const CheckoutPaymentInfo(),
                  ),
                ),
                const SizedBox(height: 24),
                
                _buildActionButtons(context),
                const SizedBox(height: 12),
                
                Text(
                  _currentStep == 2
                      ? 'Anda akan diarahkan ke halaman pembayaran aman untuk menyelesaikan transaksi.'
                      : 'Setiap langkah bisa diperiksa ulang sebelum pesanan dibuat.',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: AppTheme.onSurfaceVariant,
                    fontSize: 12,
                    height: 1.5,
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildEmptyCart(BuildContext context) {
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

  Widget _buildActionButtons(BuildContext context) {
    return Row(
      children: [
        if (_currentStep > 0) ...[
          Expanded(
            child: SizedBox(
              height: 56,
              child: PressableButton(
                onPressed: () => setState(() => _currentStep -= 1),
                backgroundColor: AppTheme.surfaceContainerLow,
                foregroundColor: AppTheme.onSurfaceVariant,
                borderRadius: 16,
                child: const Center(
                  child: Text(
                    'Kembali',
                    style: TextStyle(
                      fontWeight: FontWeight.w600,
                      fontSize: 15,
                    ),
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
            child: Semantics(
              button: true,
              label: _currentStep == 2 ? 'Bayar Sekarang' : 'Lanjut',
              child: ShimmerButton(
                isLoading: _isProcessing,
                onPressed: _isProcessing ? null : _onNextPressed,
                background: AppTheme.accent,
                borderRadius: 16,
                child: Center(
                  child: Text(
                    _currentStep == 2
                        ? 'Bayar Sekarang'
                        : _currentStep == 1
                        ? 'Lanjut ke Pembayaran'
                        : 'Lanjut ke Ringkasan',
                    style: const TextStyle(
                      fontWeight: FontWeight.w600,
                      fontSize: 15,
                      color: Colors.white,
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }

  void _onNextPressed() {
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
  }

  Future<void> _handleCheckout() async {
    final cartProvider = context.read<CartProvider>();
    final orderProvider = context.read<OrderProvider>();
    final cartItems = cartProvider.cart?.lines ?? [];

    if (cartItems.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Keranjang anda kosong'))
      );
      return;
    }

    setState(() => _isProcessing = true);

    // Payload untuk backend Laravel
    final payload = {
      'shippingName': '$_firstName $_lastName'.trim(),
      'shippingPhone': _phone,
      'shippingAddress': _address1,
      'shippingCity': _city,
      'shippingProvince': _province,
      'shippingPostalCode': _zip,
    };

    try {
      final response = await orderProvider.checkout(payload);

      if (!mounted) return;
      setState(() => _isProcessing = false);

      if (response is Map<String, dynamic> &&
          (response.containsKey('snapToken') ||
              response.containsKey('snap_token'))) {
        final snapToken = response['snapToken'] ?? response['snap_token'];
        final snapRedirectUrl =
            response['redirectUrl'] ??
            response['redirect_url'] ??
            response['snap_redirect_url'];
        final orderNumber =
            response['orderNumber'] ?? response['order_number'] ?? '';

        final targetUrl =
            snapRedirectUrl?.toString() ??
            'https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken';

        if (!mounted) return;
        cartProvider.clearCart();
        context.go(
          '/payment/$orderNumber?url=${Uri.encodeComponent(targetUrl)}',
        );
      } else if (response is Map<String, dynamic>) {
        final orderNumber =
            response['orderNumber'] ?? response['order_number'] ?? '';
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
    } on Exception catch (e) {
      if (!mounted) return;
      setState(() => _isProcessing = false);
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
