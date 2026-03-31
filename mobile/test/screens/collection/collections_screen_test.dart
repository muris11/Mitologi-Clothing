import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/screens/collection/collections_screen.dart';

import '../../helpers/provider_overrides.dart';
import '../../helpers/test_app.dart';

void main() {
  testWidgets('CollectionsScreen renders collection cards', (tester) async {
    await tester.pumpWidget(
      buildTestApp(
        collectionProvider: FakeCollectionProvider(),
        child: const CollectionsScreen(),
      ),
    );

    await tester.pump();
    await tester.pump(const Duration(milliseconds: 300));

    expect(find.text('Kategori'), findsOneWidget);
    expect(find.text('Outerwear'), findsOneWidget);
  });
}
