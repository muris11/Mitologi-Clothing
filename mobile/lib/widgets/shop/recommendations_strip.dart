import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../models/product.dart';
import '../../utils/responsive_helper.dart';
import '../common/mitologi_section_header.dart';
import '../common/mitologi_surface_card.dart';
import '../product/product_card.dart';

class RecommendationsStrip extends StatelessWidget {
  const RecommendationsStrip({
    super.key,
    required this.products,
    this.title = 'Rekomendasi Untuk Anda',
    this.subtitle = 'Dipilih khusus berdasarkan minat belanja Anda.',
  });

  final List<Product> products;
  final String title;
  final String subtitle;

  @override
  Widget build(BuildContext context) {
    if (products.isEmpty) return const SizedBox.shrink();

    final isPhone = ResponsiveHelper.isPhone(context);

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: MitologiSurfaceCard(
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 18),
        child: Column(
          children: [
            MitologiSectionHeader(
              title: title,
              subtitle: subtitle,
              eyebrow: 'Personalisasi',
              action: TextButton(
                onPressed: () => context.go('/shop'),
                child: const Text('Lihat Semua'),
              ),
            ),
            const SizedBox(height: 18),
            if (isPhone)
              SizedBox(
                height: 292,
                child: ListView.separated(
                  scrollDirection: Axis.horizontal,
                  itemCount: products.length,
                  separatorBuilder: (context, index) =>
                      const SizedBox(width: 14),
                  itemBuilder: (context, index) => SizedBox(
                    width: 196,
                    child: ProductCard(product: products[index]),
                  ),
                ),
              )
            else
              GridView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: products.length > 4 ? 4 : products.length,
                gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: ResponsiveHelper.gridColumns(context),
                  crossAxisSpacing: ResponsiveHelper.gridSpacing(context),
                  mainAxisSpacing: ResponsiveHelper.gridSpacing(context),
                  childAspectRatio: ResponsiveHelper.productCardAspectRatio(
                    context,
                  ),
                ),
                itemBuilder: (context, index) =>
                    ProductCard(product: products[index]),
              ),
          ],
        ),
      ),
    );
  }
}
