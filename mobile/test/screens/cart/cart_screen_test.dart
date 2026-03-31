import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/screens/cart/cart_screen.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('CartScreen shows line item and summary panel', (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        cartProvider: FakeCartProvider(cart: buildTestCart()),
        child: const CartScreen(),
      ),
    );

    await tester.pump();
    await tester.pump(const Duration(milliseconds: 300));

    expect(find.text('Keranjang Belanja'), findsOneWidget);
    expect(find.text('Jaket Mitologi Signature'), findsOneWidget);
    expect(find.text('Checkout'), findsOneWidget);
  });
}
