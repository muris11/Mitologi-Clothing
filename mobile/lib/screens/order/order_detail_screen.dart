import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import '../../providers/order_provider.dart';
import '../../config/theme.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import '../../models/order.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/order/order_status_banner.dart';
import '../../widgets/order/order_summary_cards.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/animations/blur_fade.dart';

class OrderDetailScreen extends StatefulWidget {
  final String orderNumber;

  const OrderDetailScreen({super.key, required this.orderNumber});

  @override
  State<OrderDetailScreen> createState() => _OrderDetailScreenState();
}

class _OrderDetailScreenState extends State<OrderDetailScreen> {
  Order? _order;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchDetail();
  }

  Future<void> _fetchDetail() async {
    try {
      final provider = context.read<OrderProvider>();
      final orders = provider.orders;

      // Try to find it in the list first to show immediately
      try {
        _order = orders.firstWhere((o) => o.orderNumber == widget.orderNumber);
      } catch (_) {}

      setState(() {
        _isLoading = true;
      });

      // Always refresh with authoritative detail from API.
      await provider.fetchOrderDetails(widget.orderNumber);
      _order = provider.currentOrder ?? _order;

      setState(() {
        _isLoading = false;
        _error = _order == null ? 'Pesanan tidak ditemukan' : null;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
        _error = e.toString();
      });
    }
  }

  Future<void> _payOrder() async {
    if (_order == null) return;

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => const Center(
        child: CircularProgressIndicator(
          color: AppTheme.primary,
          strokeWidth: 3,
        ),
      ),
    );

    final provider = context.read<OrderProvider>();
    final response = await provider.payOrder(_order!.orderNumber);

    if (!mounted) return;
    Navigator.pop(context); // Close loading

    if (response != null && response.containsKey('snap_token')) {
      final snapToken = response['snap_token'];
      final targetUrl =
          response['snap_redirect_url'] ??
          'https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken';
      context.push(
        '/payment/${_order!.orderNumber}?url=${Uri.encodeComponent(targetUrl)}',
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Gagal mengambil detail pembayaran')),
      );
    }
  }

  void _showRefundDialog() {
    final reasonController = TextEditingController();

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Ajukan Pengembalian'),
        content: TextField(
          controller: reasonController,
          decoration: const InputDecoration(
            hintText: 'Alasan pengembalian (Maks 255 karakter)',
            border: OutlineInputBorder(),
          ),
          maxLines: 3,
          maxLength: 255,
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          ElevatedButton(
            onPressed: () async {
              if (reasonController.text.trim().isEmpty) return;

              Navigator.pop(context);

              showDialog(
                context: context,
                barrierDismissible: false,
                builder: (_) => const Center(
                  child: CircularProgressIndicator(
                    color: AppTheme.primary,
                    strokeWidth: 3,
                  ),
                ),
              );

              final success = await context.read<OrderProvider>().requestRefund(
                _order!.orderNumber,
                reasonController.text.trim(),
              );

              if (!context.mounted) return;
              Navigator.pop(context); // Close loading

              if (success) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(
                    content: Text('Pengajuan pengembalian berhasil'),
                  ),
                );
                _fetchDetail(); // Refresh data manually if provider change doesn't auto-update view
              } else {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(
                    content: Text('Gagal mengajukan pengembalian'),
                  ),
                );
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.error,
              foregroundColor: Colors.white,
              elevation: 0,
            ),
            child: const Text('Ajukan'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Detail Pesanan',
      subtitle: 'Ringkasan status, alamat, item, dan pembayaran pesanan Anda.',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back),
        onPressed: () => context.popOrGoHome(),
      ),
      body: _buildBody(),
      bottomNavigationBar: _buildBottomActions(),
    );
  }

  Widget _buildBody() {
    if (_isLoading && _order == null) {
      return const OrderDetailSkeleton();
    }

    if (_error != null && _order == null) {
      return Center(
        child: FadeInUp(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppTheme.errorLight,
                  borderRadius: AppTheme.radius22,
                ),
                child: const Icon(
                  Icons.error_outline,
                  size: 48,
                  color: AppTheme.error,
                ),
              ),
              const SizedBox(height: 16),
              Text(
                'Gagal memuat: $_error',
                style: const TextStyle(color: AppTheme.onSurface),
              ),
              const SizedBox(height: 16),
              ElevatedButton.icon(
                onPressed: _fetchDetail,
                icon: const Icon(Icons.refresh),
                label: const Text('Coba Lagi'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primary,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: AppTheme.radius16,
                  ),
                ),
              ),
            ],
          ),
        ),
      );
    }

    if (_order == null) {
      return const Center(
        child: Text(
          'Pesanan tidak ditemukan',
          style: TextStyle(color: AppTheme.onSurfaceVariant),
        ),
      );
    }

    return SingleChildScrollView(
      padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          OrderStatusBanner(
            status: _order!.status,
            orderNumber: _order!.orderNumber,
          ),
          const SizedBox(height: 16),
          OrderAddressCard(address: _order!.shippingAddress),
          const SizedBox(height: 16),
          OrderItemsCard(items: _order!.items),
          const SizedBox(height: 16),
          OrderPaymentSummaryCard(
            subtotal: _order!.subtotal,
            shippingCost: _order!.shippingCost,
            total: _order!.total,
          ),
        ],
      ),
    );
  }

  Widget? _buildBottomActions() {
    if (_order == null) return null;

    if (_order!.status == 'UNPAID' || _order!.status == 'PENDING') {
      return Container(
        padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
        decoration: const BoxDecoration(color: AppTheme.surfaceContainerLowest),
        child: ElevatedButton(
          onPressed: _payOrder,
          style: ElevatedButton.styleFrom(
            backgroundColor: AppTheme.accent,
            foregroundColor: Colors.white,
            elevation: 0,
            padding: const EdgeInsets.symmetric(vertical: 16),
            shape: RoundedRectangleBorder(borderRadius: AppTheme.radius16),
          ),
          child: const Text(
            'Lanjutkan Pembayaran',
            style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16),
          ),
        ),
      );
    } else if (_order!.status == 'COMPLETED') {
      return Container(
        padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context)),
        decoration: const BoxDecoration(color: AppTheme.surfaceContainerLowest),
        child: ElevatedButton(
          onPressed: _showRefundDialog,
          style: ElevatedButton.styleFrom(
            backgroundColor: AppTheme.surfaceContainerLow,
            foregroundColor: AppTheme.error,
            elevation: 0,
            padding: const EdgeInsets.symmetric(vertical: 16),
            shape: RoundedRectangleBorder(borderRadius: AppTheme.radius16),
          ),
          child: const Text(
            'Ajukan Pengembalian',
            style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16),
          ),
        ),
      );
    }
    return null;
  }
}
