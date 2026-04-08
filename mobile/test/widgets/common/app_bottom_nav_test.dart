import 'dart:io';

import 'package:flutter_test/flutter_test.dart';

void main() {
  test('cart branch and bottom nav order stay aligned', () {
    final appSource = File('lib/app.dart').readAsStringSync();
    final navSource = File(
      'lib/widgets/common/app_bottom_nav.dart',
    ).readAsStringSync();

    expect(appSource.contains("path: '/cart'"), isTrue);
    expect(appSource.contains("path: '/wishlist'"), isTrue);
    expect(
      navSource.indexOf("label: 'Keranjang'"),
      lessThan(navSource.indexOf("label: 'Wishlist'")),
    );
  });
}
