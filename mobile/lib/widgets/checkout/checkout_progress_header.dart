import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../providers/cart_provider.dart';
import '../../utils/price_formatter.dart';
import '../animations/animations.dart';

class CheckoutProgressHeader extends StatelessWidget {
  final int currentStep;
  final List<String> stepTitles;
  final CartProvider cartProvider;

  const CheckoutProgressHeader({
    super.key,
    required this.currentStep,
    required this.stepTitles,
    required this.cartProvider,
  });

  IconData _getStepIcon(int index) {
    switch (index) {
      case 0:
        return Icons.location_on_outlined;
      case 1:
        return Icons.payment_outlined;
      case 2:
        return Icons.fact_check_outlined;
      default:
        return Icons.circle;
    }
  }

  @override
  Widget build(BuildContext context) {
    final totalText = cartProvider.cart == null
        ? ''
        : PriceFormatter.formatStringIDR(
            cartProvider.cart!.cost.totalAmount.amount,
          );

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: AppTheme.sectionBackground,
        borderRadius: AppTheme.radius24,
        border: Border.all(color: AppTheme.outlineLight, width: 1.5),
        boxShadow: AppTheme.shadowSoft,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              FadeIn(
                child: Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 12,
                    vertical: 6,
                  ),
                  decoration: BoxDecoration(
                    color: AppTheme.primary.withValues(alpha: 0.08),
                    borderRadius: AppTheme.radius12,
                  ),
                  child: Text(
                    'LANGKAH ${currentStep + 1} / ${stepTitles.length}',
                    style: const TextStyle(
                      color: AppTheme.primary,
                      fontSize: 10,
                      fontWeight: FontWeight.w900,
                      letterSpacing: 1.2,
                    ),
                  ),
                ),
              ),
              const Spacer(),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    'Total Pesanan',
                    style: Theme.of(context).textTheme.labelSmall?.copyWith(
                          color: AppTheme.onSurfaceVariant,
                          fontSize: 10,
                        ),
                  ),
                  Text(
                    totalText,
                    style: const TextStyle(
                      color: AppTheme.onSurface,
                      fontSize: 14,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                ],
              ),
            ],
          ),
          const SizedBox(height: 24),
          Row(
            children: List.generate(stepTitles.length, (index) {
              final isActive = index == currentStep;
              final isCompleted = index < currentStep;
              final isLast = index == stepTitles.length - 1;

              return Expanded(
                child: Row(
                  children: [
                    // Step Indicator (Circle/Icon)
                    Column(
                      children: [
                        AnimatedContainer(
                          duration: const Duration(milliseconds: 400),
                          curve: Curves.easeInOut,
                          width: 36,
                          height: 36,
                          decoration: BoxDecoration(
                            color: isCompleted
                                ? AppTheme.success
                                : (isActive ? AppTheme.primary : Colors.white),
                            shape: BoxShape.circle,
                            border: Border.all(
                              color: isCompleted
                                  ? AppTheme.success
                                  : (isActive
                                      ? AppTheme.primary
                                      : AppTheme.outlineVariant),
                              width: 2,
                            ),
                            boxShadow: isActive ? AppTheme.shadowMedium : null,
                          ),
                          child: Icon(
                            isCompleted ? Icons.check : _getStepIcon(index),
                            size: 18,
                            color: isCompleted || isActive
                                ? Colors.white
                                : AppTheme.onSurfaceVariant,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          stepTitles[index],
                          style: TextStyle(
                            color: isActive
                                ? AppTheme.onSurface
                                : AppTheme.onSurfaceVariant,
                            fontWeight: isActive
                                ? FontWeight.w800
                                : FontWeight.w600,
                            fontSize: 11,
                          ),
                        ),
                      ],
                    ),
                    // Connector Line
                    if (!isLast)
                      Expanded(
                        child: Padding(
                          padding: const EdgeInsets.only(bottom: 24),
                          child: Container(
                            height: 2,
                            margin: const EdgeInsets.symmetric(horizontal: 8),
                            child: Stack(
                              children: [
                                Container(
                                  decoration: BoxDecoration(
                                    color: AppTheme.outlineVariant.withValues(alpha: 0.5),
                                    borderRadius: BorderRadius.circular(2),
                                  ),
                                ),
                                AnimatedContainer(
                                  duration: const Duration(milliseconds: 600),
                                  width: isCompleted ? 500 : 0, // 500 is a safe large value for line coverage
                                  decoration: BoxDecoration(
                                    color: AppTheme.success,
                                    borderRadius: BorderRadius.circular(2),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                  ],
                ),
              );
            }),
          ),
        ],
      ),
    );
  }
}
