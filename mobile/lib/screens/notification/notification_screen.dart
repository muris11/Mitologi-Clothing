import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import '../../config/theme.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../providers/auth_provider.dart';
import '../../providers/order_provider.dart';
import '../../widgets/animations/shimmer_button.dart';
import 'package:intl/intl.dart';

class NotificationScreen extends StatefulWidget {
  const NotificationScreen({super.key});

  @override
  State<NotificationScreen> createState() => _NotificationScreenState();
}

class _NotificationScreenState extends State<NotificationScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      if (authProvider.isAuthenticated) {
        Provider.of<OrderProvider>(context, listen: false).fetchOrders();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return MitologiScaffold(
      title: 'Notifikasi',
      subtitle: authProvider.isAuthenticated
          ? 'Pantau aktivitas belanja dan status pesanan Anda.'
          : 'Login untuk melihat update pesanan dan promo terbaru.',
      showLogo: false,
      body: authProvider.isAuthenticated
          ? _buildAuthenticatedBody()
          : _buildGuestBody(),
    );
  }

  Widget _buildGuestBody() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(24),
              decoration: const BoxDecoration(
                color: AppTheme.slate50,
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.notifications_none,
                size: 64,
                color: AppTheme.slate300,
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'Akses Dibatasi',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: AppTheme.primary,
              ),
            ),
            const SizedBox(height: 12),
            const Text(
              'Silakan login terlebih dahulu untuk melihat notifikasi dan status pesanan Anda secara real-time.',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 14,
                color: AppTheme.slate500,
                height: 1.5,
              ),
            ),
            const SizedBox(height: 32),
            SizedBox(
              width: 200,
              child: ShimmerButton(
                onPressed: () => context.push('/shop/login'),
                child: const Text(
                  'Login Sekarang',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAuthenticatedBody() {
    return Consumer<OrderProvider>(
      builder: (context, orderProvider, child) {
        if (orderProvider.isLoading && orderProvider.orders.isEmpty) {
          return const Center(
            child: CircularProgressIndicator(color: AppTheme.primary),
          );
        }

        if (orderProvider.orders.isEmpty) {
          return Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(
                  Icons.notifications_none,
                  size: 64,
                  color: AppTheme.slate200,
                ),
                const SizedBox(height: 16),
                const Text(
                  'Belum ada notifikasi',
                  style: TextStyle(
                    fontSize: 16,
                    color: AppTheme.slate400,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
          );
        }

        // Map orders to notification items
        final notifications = _generateNotifications(orderProvider.orders);

        return ListView.separated(
          padding: const EdgeInsets.symmetric(vertical: 10),
          itemCount: notifications.length,
          separatorBuilder: (context, index) => Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            child: Divider(color: AppTheme.slate100, height: 1),
          ),
          itemBuilder: (context, index) {
            final item = notifications[index];
            return BlurFade(
              delay: Duration(milliseconds: index * 50),
              child: ListTile(
                onTap: () =>
                    context.push('/shop/account/orders/${item.orderNumber}'),
                contentPadding: const EdgeInsets.symmetric(
                  horizontal: 20,
                  vertical: 12,
                ),
                leading: Container(
                  width: 48,
                  height: 48,
                  decoration: BoxDecoration(
                    color: item.bgColor,
                    shape: BoxShape.circle,
                  ),
                  child: Icon(item.icon, color: item.iconColor, size: 24),
                ),
                title: Text(
                  item.title,
                  style: const TextStyle(
                    fontWeight: FontWeight.w800,
                    fontSize: 15,
                    color: AppTheme.primary,
                  ),
                ),
                subtitle: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const SizedBox(height: 4),
                    Text(
                      item.body,
                      style: const TextStyle(
                        fontSize: 13,
                        color: AppTheme.slate600,
                        height: 1.4,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      item.time,
                      style: const TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                        color: AppTheme.slate400,
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  List<_NotificationItem> _generateNotifications(List<dynamic> orders) {
    final List<_NotificationItem> items = [];

    for (var order in orders) {
      String title = '';
      String body = '';
      IconData icon = Icons.notifications_none;
      Color iconColor = AppTheme.slate400;
      Color bgColor = AppTheme.slate50;

      final status = order.status.toLowerCase();

      if (status == 'unpaid' || status == 'pending') {
        title = 'Menunggu Pembayaran';
        body =
            'Pesanan #${order.orderNumber} sedang menunggu pembayaran. Segera selesaikan sebelum dibatalkan otomatis.';
        icon = Icons.account_balance_wallet_outlined;
        iconColor = AppTheme.primary;
        bgColor = AppTheme.primary.withValues(alpha: 0.1);
      } else if (status == 'processing') {
        title = 'Pesanan Diproses';
        body =
            'Pesanan #${order.orderNumber} sedang disiapkan oleh tim Mitologi. Kami akan segera mengirimkannya.';
        icon = Icons.inventory_2_outlined;
        iconColor = AppTheme.primary;
        bgColor = AppTheme.primary.withValues(alpha: 0.08);
      } else if (status == 'shipped') {
        title = 'Pesanan Dikirim';
        body =
            'Kabar gembira! Pesanan #${order.orderNumber} sedang dalam perjalanan menuju lokasi Anda.';
        icon = Icons.local_shipping_outlined;
        iconColor = AppTheme.success;
        bgColor = AppTheme.success.withValues(alpha: 0.1);
      } else if (status == 'completed') {
        title = 'Pesanan Selesai';
        body =
            'Pesanan #${order.orderNumber} telah diterima. Terima kasih telah berbelanja di Mitologi Clothing!';
        icon = Icons.check_circle_outline;
        iconColor = AppTheme.success;
        bgColor = AppTheme.success.withValues(alpha: 0.1);
      } else if (status == 'cancelled') {
        title = 'Pesanan Dibatalkan';
        body =
            'Pesanan #${order.orderNumber} telah dibatalkan. Hubungi admin jika ini adalah kesalahan.';
        icon = Icons.cancel_outlined;
        iconColor = AppTheme.error;
        bgColor = AppTheme.error.withValues(alpha: 0.1);
      } else {
        title = 'Update Status Pesanan';
        body =
            'Status pesanan #${order.orderNumber} Anda sekarang menjadi ${order.status}.';
        icon = Icons.info_outline;
      }

      items.add(
        _NotificationItem(
          title: title,
          body: body,
          icon: icon,
          iconColor: iconColor,
          bgColor: bgColor,
          time: _formatDate(order.createdAt),
          orderNumber: order.orderNumber,
        ),
      );
    }

    return items;
  }

  String _formatDate(String dateString) {
    try {
      final dateTime = DateTime.parse(dateString);
      return DateFormat('dd MMM yyyy, HH:mm').format(dateTime);
    } catch (e) {
      return dateString;
    }
  }
}

class _NotificationItem {
  final String title;
  final String body;
  final IconData icon;
  final Color iconColor;
  final Color bgColor;
  final String time;
  final String orderNumber;

  _NotificationItem({
    required this.title,
    required this.body,
    required this.icon,
    required this.iconColor,
    required this.bgColor,
    required this.time,
    required this.orderNumber,
  });
}
