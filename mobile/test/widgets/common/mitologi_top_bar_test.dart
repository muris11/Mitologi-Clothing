import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/widgets/common/mitologi_top_bar.dart';

import '../../helpers/test_app.dart';

void main() {
  testWidgets('MitologiTopBar renders branding and subtitle', (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        child: const Scaffold(
          appBar: MitologiTopBar(
            title: 'Katalog Koleksi',
            subtitle: 'Produk premium untuk semua device.',
          ),
        ),
      ),
    );

    expect(find.text('Katalog Koleksi'), findsOneWidget);
    expect(find.text('Produk premium untuk semua device.'), findsOneWidget);
  });
}
