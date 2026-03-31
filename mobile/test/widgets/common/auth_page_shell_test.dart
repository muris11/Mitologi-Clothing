import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/widgets/common/auth_page_shell.dart';

import '../../helpers/test_app.dart';

void main() {
  testWidgets('AuthPageShell renders title, subtitle, and child content',
      (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        child: const AuthPageShell(
          title: 'Masuk ke Akun',
          subtitle: 'Akses belanja premium dan kelola wishlist Anda.',
          showBack: false,
          child: Text('Isi Form Login'),
        ),
      ),
    );

    expect(find.text('Masuk ke Akun'), findsOneWidget);
    expect(find.text('Akses belanja premium dan kelola wishlist Anda.'),
        findsOneWidget);
    expect(find.text('Isi Form Login'), findsOneWidget);
  });
}
