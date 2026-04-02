import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/widgets/product/product_info_sections.dart';
import 'package:mobile/widgets/product/product_purchase_panel.dart';

import '../../helpers/test_app.dart';

void main() {
  testWidgets('ProductInfoHeader renders title, price, and metadata', (
    tester,
  ) async {
    await tester.pumpWidget(
      buildTestApp(
        child: const Scaffold(
          body: ProductInfoHeader(
            title: 'Jaket Mitologi Signature',
            price: 'Rp349.000',
            rating: 4.9,
            totalSold: 120,
          ),
        ),
      ),
    );

    expect(find.text('Jaket Mitologi Signature'), findsOneWidget);
    expect(find.text('Rp349.000'), findsOneWidget);
    expect(find.text('Terjual 120'), findsOneWidget);
  });

  testWidgets('ProductPurchasePanel renders actions', (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        child: Scaffold(
          body: const Center(child: Text('Body')),
          bottomNavigationBar: ProductPurchasePanel(
            enabled: true,
            onChatTap: () {},
            onAddToCart: () {},
          ),
        ),
      ),
    );

    await tester.pump();
    await tester.pump(const Duration(milliseconds: 500));

    // Check for text within the bottom sheet/panel
    expect(find.text('+ Tambah ke Keranjang'), findsOneWidget);
    // Use a more flexible finder for the chat icon
    expect(find.byIcon(Icons.chat_bubble_outline), findsOneWidget);
  });
}
