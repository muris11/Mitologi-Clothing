import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/widgets/account/account_header_card.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('AccountHeaderCard renders provider-backed stats',
      (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        child: Scaffold(
          body: AccountHeaderCard(
            user: buildTestUser(),
            orderCount: 4,
            wishlistCount: 2,
            addressCount: 1,
          ),
        ),
      ),
    );

    expect(find.text('Mitologi Tester'), findsOneWidget);
    expect(find.text('4'), findsOneWidget);
    expect(find.text('2'), findsOneWidget);
    expect(find.text('1'), findsOneWidget);
    expect(find.text('Wishlist'), findsOneWidget);
    expect(find.text('Alamat'), findsOneWidget);
  });
}
