import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import '../../providers/order_provider.dart';
import '../../config/theme.dart';

class PaymentWebViewScreen extends StatefulWidget {
  final String orderNumber;
  final String snapUrl;

  const PaymentWebViewScreen({
    super.key,
    required this.orderNumber,
    required this.snapUrl,
  });

  @override
  State<PaymentWebViewScreen> createState() => _PaymentWebViewScreenState();
}

class _PaymentWebViewScreenState extends State<PaymentWebViewScreen> {
  late final WebViewController _controller;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(Colors.white)
      ..setNavigationDelegate(
        NavigationDelegate(
          onProgress: (int progress) {
            if (progress == 100 && mounted) {
              setState(() {
                _isLoading = false;
              });
            }
          },
          onPageStarted: (String url) {},
          onPageFinished: (String url) {},
          onWebResourceError: (WebResourceError error) {
            // Web resource errors are handled silently in production
            // Consider implementing error analytics for production monitoring
          },
          onNavigationRequest: (NavigationRequest request) {
            final url = request.url;

            // Check Midtrans redirect callbacks based on URL parameters
            // Typically Midtrans redirects to your finish URL pattern like `?order_id=XXX&status_code=200`
            if (url.contains('transaction_status=capture') ||
                url.contains('status_code=200')) {
              _handlePaymentSuccess();
              return NavigationDecision.prevent;
            } else if (url.contains('status_code=202') ||
                url.contains('status_code=201')) {
              _handlePaymentPending();
              return NavigationDecision.prevent;
            } else if (url.contains('status_code=400') ||
                url.contains('status_code=406')) {
              _handlePaymentFailed();
              return NavigationDecision.prevent;
            }

            return NavigationDecision.navigate;
          },
        ),
      )
      ..loadRequest(Uri.parse(widget.snapUrl));
  }

  void _handlePaymentSuccess() async {
    // Confirm payment status with backend
    await context.read<OrderProvider>().confirmPayment(widget.orderNumber);

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pembayaran berhasil dikonfirmasi!')),
      );
      // Navigate to order success screen
      context.go('/shop/checkout/success?order_number=${widget.orderNumber}');
    }
  }

  void _handlePaymentPending() {
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Menunggu pembayaran diselesaikan.')),
      );
      context.go('/shop/account/orders/${widget.orderNumber}');
    }
  }

  void _handlePaymentFailed() {
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pembayaran gagal atau dibatalkan.')),
      );
      context.go('/shop/account/orders/${widget.orderNumber}');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Pembayaran'),
        backgroundColor: Colors.white,
        foregroundColor: AppTheme.primary,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.close),
          onPressed: () {
            // Confirmation dialog before exiting payment
            showDialog(
              context: context,
              builder: (context) => AlertDialog(
                title: const Text('Batalkan Pembayaran?'),
                content: const Text(
                  'Apakah Anda yakin ingin keluar dari halaman pembayaran ini? Pembayaran dapat dilanjutkan melalui halaman pesanan.',
                ),
                actions: [
                  TextButton(
                    onPressed: () => Navigator.pop(context),
                    child: const Text('Tetap Disini'),
                  ),
                  TextButton(
                    onPressed: () {
                      Navigator.pop(context);
                      context.go('/shop/account/orders/${widget.orderNumber}');
                    },
                    style: TextButton.styleFrom(
                      foregroundColor: AppTheme.error,
                    ),
                    child: const Text('Keluar'),
                  ),
                ],
              ),
            );
          },
        ),
      ),
      body: Stack(
        children: [
          WebViewWidget(controller: _controller),
          if (_isLoading) const Center(child: CircularProgressIndicator()),
        ],
      ),
    );
  }
}
