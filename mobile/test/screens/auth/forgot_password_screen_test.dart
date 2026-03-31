import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/screens/auth/forgot_password_screen.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('ForgotPasswordScreen shows success state after provider success',
      (
    tester,
  ) async {
    await tester.pumpWidget(
      buildTestApp(
        authProvider: FakeAuthProvider(forgotPasswordResult: true),
        child: const ForgotPasswordScreen(),
      ),
    );

    await tester.enterText(find.byType(TextFormField), 'user@example.com');
    await tester.ensureVisible(find.text('Kirim Link Reset'));
    await tester.tap(find.text('Kirim Link Reset'), warnIfMissed: false);
    await tester.pump();
    await tester.pump(const Duration(milliseconds: 700));

    expect(find.text('Link Terkirim!'), findsOneWidget);
    expect(find.textContaining('user@example.com'), findsOneWidget);
  });

  testWidgets('ForgotPasswordScreen shows snackbar after provider failure', (
    tester,
  ) async {
    await tester.pumpWidget(
      buildTestApp(
        authProvider: FakeAuthProvider(
          forgotPasswordResult: false,
          forgotPasswordError: 'Reset gagal',
        ),
        child: const ForgotPasswordScreen(),
      ),
    );

    await tester.enterText(find.byType(TextFormField), 'user@example.com');
    await tester.ensureVisible(find.text('Kirim Link Reset'));
    await tester.tap(find.text('Kirim Link Reset'), warnIfMissed: false);
    await tester.pump();
    await tester.pump(const Duration(milliseconds: 700));

    expect(find.byType(SnackBar), findsOneWidget);
    expect(find.text('Reset gagal'), findsOneWidget);
  });
}
