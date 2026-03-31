import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/cart_provider.dart';
import '../../config/theme.dart';
import '../../utils/price_formatter.dart';
import '../../models/cart_item.dart';
import '../../widgets/common/animated_empty_state.dart';
import '../../widgets/animations/blur_fade.dart';
import '../../widgets/cart/cart_line_item.dart';
import '../../widgets/cart/cart_summary_panel.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';

class CartScreen extends StatefulWidget {
  const CartScreen({super.key});

  @override
  State<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<CartProvider>().fetchCart();
    });
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Keranjang Belanja',
      subtitle: 'Tinjau item, atur jumlah, lalu lanjut ke checkout.',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: Consumer<CartProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading && provider.cart == null) {
            return const CartSkeleton();
          }

          if (provider.error != null && provider.cart == null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(
                    Icons.error_outline,
                    size: 64,
                    color: AppTheme.error,
                  ),
                  const SizedBox(height: 16),
                  Text(
                    provider.error!,
                    style: const TextStyle(
                      fontSize: 16,
                      color: AppTheme.slate800,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 24),
                  ElevatedButton(
                    onPressed: () => provider.fetchCart(),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.accent,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: AppTheme.radius16,
                      ),
                      padding: const EdgeInsets.symmetric(
                        horizontal: 32,
                        vertical: 12,
                      ),
                    ),
                    child: const Text(
                      'Coba Lagi',
                      style: TextStyle(fontWeight: FontWeight.w600),
                    ),
                  ),
                ],
              ),
            );
          }

          if (provider.cart == null || provider.cart!.lines.isEmpty) {
            return _buildEmptyCart();
          }

          return Column(
            children: [
              Expanded(
                child: ListView.builder(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  itemCount: provider.cart!.lines.length,
                  itemBuilder: (context, index) {
                    final item = provider.cart!.lines[index];
                    return FadeInUp(
                      delay: Duration(milliseconds: 50 * index),
                      child: _buildCartItem(context, provider, item),
                    );
                  },
                ),
              ),
              _buildBottomSummary(provider),
            ],
          );
        },
      ),
    );
  }

  Widget _buildEmptyCart() {
    return AnimatedEmptyState(
      icon: Icons.shopping_bag_outlined,
      title: 'Keranjang Kosong',
      subtitle:
          'Keranjang Anda masih kosong. Mulai isi dengan koleksi pilihan Mitologi Clothing.',
      buttonText: 'Mulai Belanja',
      onAction: () {
        context.go('/');
      },
    );
  }

  Widget _buildCartItem(
    BuildContext context,
    CartProvider provider,
    CartItem item,
  ) {
    return CartLineItem(
      item: item,
      onRemove: () {
        HapticFeedback.lightImpact();
        if (item.id != null) {
          _confirmRemove(context, provider, item.id!);
        }
      },
      onDecrease: () {
        HapticFeedback.selectionClick();
        if (item.id == null) return;
        if (item.quantity > 1) {
          provider.updateQuantity(item.id!, item.quantity - 1);
        } else {
          _confirmRemove(context, provider, item.id!);
        }
      },
      onIncrease: () {
        HapticFeedback.selectionClick();
        if (item.id != null) {
          provider.updateQuantity(item.id!, item.quantity + 1);
        }
      },
    );
  }

  void _confirmRemove(
    BuildContext context,
    CartProvider provider,
    String itemId,
  ) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text(
          'Hapus Produk',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        content: const Text(
          'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
          style: TextStyle(color: AppTheme.slate600),
        ),
        shape: RoundedRectangleBorder(borderRadius: AppTheme.radius22),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text(
              'Batal',
              style: TextStyle(
                color: AppTheme.slate500,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.error,
              foregroundColor: Colors.white,
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: AppTheme.radius16),
            ),
            onPressed: () {
              HapticFeedback.mediumImpact();
              Navigator.pop(ctx);
              provider.removeItem(itemId);
            },
            child: const Text(
              'Hapus',
              style: TextStyle(fontWeight: FontWeight.w600),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBottomSummary(CartProvider provider) {
    return CartSummaryPanel(
      totalText: PriceFormatter.formatStringIDR(
        provider.cart!.cost.totalAmount.amount,
      ),
      isLoading: provider.isLoading,
      onCheckout: () {
        HapticFeedback.mediumImpact();
        context.push('/shop/checkout');
      },
    );
  }
}
