import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';

import 'package:mobile/providers/wishlist_provider.dart';
import 'package:mobile/widgets/product/product_card.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('ProductCard renders key merchandising details', (tester) async {
    final product = buildTestProduct();

    await tester.pumpWidget(
      buildTestApp(
        wishlistProvider: FakeWishlistProvider(items: [product]),
        child: Scaffold(
            body: Center(
                child: SizedBox(
                    width: 240,
                    height: 360,
                    child: ProductCard(product: product)))),
      ),
    );

    await tester.pump();

    expect(find.text('Jaket Mitologi Signature'), findsOneWidget);
    expect(find.textContaining('terjual'), findsOneWidget);
    expect(find.text('BEST SELLER'), findsOneWidget);
    expect(find.byType(Consumer<WishlistProvider>), findsOneWidget);
  });
}
