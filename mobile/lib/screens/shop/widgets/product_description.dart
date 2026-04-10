import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';
import '../../../models/product.dart';
import '../../../config/theme.dart';

class ProductDescription extends StatelessWidget {
  final Product product;

  const ProductDescription({super.key, required this.product});

  @override
  Widget build(BuildContext context) {
    if (product.descriptionHtml.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Deskripsi',
          style: Theme.of(
            context,
          ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w700),
        ),
        const SizedBox(height: 12),
        Html(
          data: product.descriptionHtml,
          style: {
            'body': Style(
              fontSize: FontSize(14),
              color: AppTheme.onSurfaceVariant,
              lineHeight: const LineHeight(1.6),
            ),
            'p': Style(margin: Margins.only(bottom: 12)),
            'h1, h2, h3, h4, h5, h6': Style(
              fontWeight: FontWeight.w700,
              color: AppTheme.onSurface,
              margin: Margins.only(bottom: 8, top: 16),
            ),
            'ul, ol': Style(
              margin: Margins.only(bottom: 12),
              padding: HtmlPaddings.only(left: 20),
            ),
            'li': Style(margin: Margins.only(bottom: 4)),
          },
        ),
      ],
    );
  }
}
