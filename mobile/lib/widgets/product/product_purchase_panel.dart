import 'dart:ui';

import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../widgets/animations/shimmer_button.dart';

class ProductPurchasePanel extends StatelessWidget {
  const ProductPurchasePanel({
    super.key,
    required this.enabled,
    required this.onChatTap,
    required this.onAddToCart,
  });

  final bool enabled;
  final VoidCallback onChatTap;
  final VoidCallback onAddToCart;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.transparent,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 24,
            offset: const Offset(0, -8),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: 12.0, sigmaY: 12.0),
          child: Container(
            padding: const EdgeInsets.fromLTRB(20, 16, 20, 32),
            decoration: BoxDecoration(
              color: AppTheme.surface.withValues(alpha: 0.85),
              borderRadius: const BorderRadius.vertical(
                top: Radius.circular(24),
              ),
              border: Border(
                top: BorderSide(
                  color: AppTheme.surfaceContainerLowest.withValues(alpha: 0.5),
                  width: 1,
                ),
              ),
            ),
            child: Row(
              children: [
                Container(
                  height: 54,
                  width: 54,
                  decoration: BoxDecoration(
                    color: AppTheme.surfaceContainerLow,
                    borderRadius: AppTheme.radius11,
                  ),
                  child: IconButton(
                    onPressed: onChatTap,
                    tooltip: 'Chat',
                    icon: const Icon(
                      Icons.chat_bubble_outline,
                      color: AppTheme.onSurface,
                      size: 24,
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: SizedBox(
                    height: 54,
                    child: ShimmerButton(
                      background: enabled
                          ? AppTheme.accent
                          : AppTheme.surfaceContainerHigh,
                      borderRadius: 16,
                      onPressed: enabled ? onAddToCart : null,
                      child: const Center(
                        child: Text(
                          '+ Tambah ke Keranjang',
                          style: TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
