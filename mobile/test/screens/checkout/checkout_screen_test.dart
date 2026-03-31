import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/screens/checkout/checkout_screen.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('CheckoutScreen shows extracted step cards', (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        cartProvider: FakeCartProvider(cart: buildTestCart()),
        child: const CheckoutScreen(),
      ),
    );

    await tester.pump();

    expect(find.text('Alamat Pengiriman'), findsOneWidget);
    expect(find.text('Ringkasan Pesanan'), findsOneWidget);
    expect(find.text('Pembayaran'), findsOneWidget);
  });
}
