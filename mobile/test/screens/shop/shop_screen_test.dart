import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/screens/shop/shop_screen.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('ShopScreen renders search, top bar, and product grid shell', (
    tester,
  ) async {
    await tester.pumpWidget(
      buildTestApp(
        productProvider: FakeProductProvider(),
        cartProvider: FakeCartProvider(seededCount: 2),
        child: const ShopScreen(),
      ),
    );

    await tester.pump();
    await tester.pump(const Duration(milliseconds: 300));

    // Updated texts and icons to match actual UI
    expect(find.text('Katalog'), findsOneWidget);
    expect(
      find.text('Temukan koleksi premium dari Mitologi Clothing'),
      findsOneWidget,
    );
    expect(find.text('Jaket Mitologi Signature'), findsWidgets);
    expect(find.byIcon(Icons.sort), findsOneWidget);
  });
}
