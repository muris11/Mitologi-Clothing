import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/screens/shop/shop_screen.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('ShopScreen renders search, top bar, and product grid shell',
      (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        productProvider: FakeProductProvider(),
        cartProvider: FakeCartProvider(seededCount: 2),
        child: const ShopScreen(),
      ),
    );

    await tester.pump();
    await tester.pump(const Duration(milliseconds: 300));

    expect(find.text('Mitologi Clothing'), findsOneWidget);
    expect(find.text('Cari produk eksklusif kami...'), findsOneWidget);
    expect(find.text('Jaket Mitologi Signature'), findsWidgets);
    expect(find.byIcon(Icons.tune_rounded), findsOneWidget);
  });
}
