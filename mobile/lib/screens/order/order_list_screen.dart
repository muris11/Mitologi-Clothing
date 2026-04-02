import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';

import '../../../utils/navigation_helper.dart';
import '../../providers/order_provider.dart';
import '../../config/theme.dart';
import '../../utils/price_formatter.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/animated_empty_state.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';

class OrderListScreen extends StatefulWidget {
  const OrderListScreen({super.key});

  @override
  State<OrderListScreen> createState() => _OrderListScreenState();
}

class _OrderListScreenState extends State<OrderListScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<OrderProvider>().fetchOrders();
    });
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Riwayat Pesanan',
      subtitle: 'Pantau status dan total belanja dari pesanan terakhir Anda.',
      showLogo: false,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back),
        onPressed: () => context.popOrGoHome(),
      ),
      bodyPadding: EdgeInsets.zero,
      body: Consumer<OrderProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading && provider.orders.isEmpty) {
            return const OrderListSkeleton();
          }

          if (provider.error != null && provider.orders.isEmpty) {
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
                      'Gagal memuat pesanan: ${provider.error}',
                      textAlign: TextAlign.center,
                      style: const TextStyle(color: AppTheme.slate700),
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton.icon(
                      onPressed: () => provider.fetchOrders(),
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

          if (provider.orders.isEmpty) {
            return AnimatedEmptyState(
              icon: Icons.receipt_long_outlined,
              title: 'Belum Ada Pesanan',
              subtitle: 'Anda belum pernah melakukan pemesanan\ndi toko kami.',
              buttonText: 'Mulai Belanja',
              onAction: () => context.go('/'),
            );
          }

          return RefreshIndicator(
            onRefresh: () => provider.fetchOrders(),
            child: ListView.separated(
              padding: EdgeInsets.all(
                ResponsiveHelper.horizontalPadding(context),
              ),
              itemCount: provider.orders.length,
              separatorBuilder: (context, index) => const SizedBox(height: 16),
              itemBuilder: (context, index) {
                final order = provider.orders[index];

                // Format date
                String dateStr = '';
                try {
                  final dt = DateTime.parse(order.createdAt);
                  dateStr = DateFormat('dd MMM yyyy').format(dt);
                } catch (_) {}

                // Status formatting
                Color statusColor = AppTheme.primary;
                String statusText = order.status;
                if (order.status == 'UNPAID' || order.status == 'PENDING') {
                  statusColor = AppTheme.warning;
                  statusText = 'Belum Bayar';
                } else if (order.status == 'PROCESSING') {
                  statusColor = AppTheme.info;
                  statusText = 'Dikemas';
                } else if (order.status == 'SHIPPED') {
                  statusColor = AppTheme.info;
                  statusText = 'Dikirim';
                } else if (order.status == 'COMPLETED') {
                  statusColor = AppTheme.success;
                  statusText = 'Selesai';
                } else if (order.status == 'CANCELLED') {
                  statusColor = AppTheme.error;
                  statusText = 'Dibatalkan';
                }

                int totalItems = 0;
                for (var item in order.items) {
                  totalItems += item.quantity;
                }

                return BlurFade(
                  delay: Duration(milliseconds: 50 * (index % 10)),
                  child: GestureDetector(
                    onTap: () {
                      context.push('/shop/account/orders/${order.orderNumber}');
                    },
                    child: Container(
                      padding: EdgeInsets.all(
                        ResponsiveHelper.horizontalPadding(context),
                      ),
                      decoration: BoxDecoration(
                        color: AppTheme.surfaceContainerLowest,
                        borderRadius: AppTheme.radius16,
                        border: Border.all(color: AppTheme.outlineLight),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                children: [
                                  const Icon(
                                    Icons.shopping_bag_outlined,
                                    size: 16,
                                    color: AppTheme.slate500,
                                  ),
                                  const SizedBox(width: 8),
                                  Text(
                                    dateStr,
                                    style: const TextStyle(
                                      fontSize: 12,
                                      color: AppTheme.slate500,
                                    ),
                                  ),
                                ],
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 8,
                                  vertical: 4,
                                ),
                                decoration: BoxDecoration(
                                  color: statusColor.withValues(alpha: 0.1),
                                  borderRadius: AppTheme.radius8,
                                ),
                                child: Text(
                                  statusText,
                                  style: TextStyle(
                                    fontSize: 10,
                                    fontWeight: FontWeight.w700,
                                    color: statusColor,
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const Divider(height: 24),
                          Row(
                            children: [
                              Container(
                                width: 60,
                                height: 60,
                                decoration: BoxDecoration(
                                  color: AppTheme.slate100,
                                  borderRadius: AppTheme.radius12,
                                ),
                                child: const Icon(
                                  Icons.inventory_2_outlined,
                                  color: AppTheme.slate500,
                                ),
                              ),
                              const SizedBox(width: 16),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      order.orderNumber,
                                      style: const TextStyle(
                                        fontWeight: FontWeight.w800,
                                        fontSize: 15,
                                        color: AppTheme.primary,
                                      ),
                                    ),
                                    const SizedBox(height: 4),
                                    Text(
                                      '$totalItems Produk',
                                      style: const TextStyle(
                                        fontSize: 12,
                                        color: AppTheme.slate500,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 16),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              const Text(
                                'Total Belanja',
                                style: TextStyle(
                                  fontSize: 12,
                                  color: AppTheme.slate500,
                                ),
                              ),
                              Text(
                                PriceFormatter.formatIDR(order.total),
                                style: const TextStyle(
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            ),
          );
        },
      ),
    );
  }
}
