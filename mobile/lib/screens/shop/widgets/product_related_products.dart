import 'package:flutter/material.dart';
import '../../../models/product.dart';
import '../../../config/theme.dart';
import '../../../widgets/animations/animations.dart';
import '../../../widgets/product/product_card.dart';
import '../../../utils/responsive_helper.dart';

class ProductRelatedProducts extends StatelessWidget {
  final List<Product> products;

  const ProductRelatedProducts({super.key, required this.products});

  @override
  Widget build(BuildContext context) {
    if (products.isEmpty) return const SizedBox.shrink();

    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: EdgeInsets.only(left: horizontalPadding),
          child: Text(
            'Produk Terkait',
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 280,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: horizontalPadding),
            itemCount: products.length,
            separatorBuilder: (_, index) => const SizedBox(width: 12),
            itemBuilder: (context, index) {
              return SizedBox(
                width: 180,
                child: FadeInUp(
                  delay: Duration(milliseconds: index * 50),
                  child: ProductCard(product: products[index]),
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}
