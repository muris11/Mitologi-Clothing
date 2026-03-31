import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/widgets/common/mitologi_page_shell.dart';

import '../../helpers/test_app.dart';

void main() {
  testWidgets('MitologiPageShell shows heading and content card',
      (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        child: const Scaffold(
          body: MitologiPageShell(
            title: 'Panduan Ukuran',
            subtitle: 'Bantu pelanggan memilih ukuran dengan cepat.',
            eyebrow: 'Informasi',
            child: Text('Konten halaman'),
          ),
        ),
      ),
    );

    expect(find.text('Panduan Ukuran'), findsOneWidget);
    expect(find.text('Bantu pelanggan memilih ukuran dengan cepat.'),
        findsOneWidget);
    expect(find.text('Konten halaman'), findsOneWidget);
  });
}
