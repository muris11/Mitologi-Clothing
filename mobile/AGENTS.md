# mobile

## Identity
- Flutter client for Mitologi storefront flows.
- Uses `Provider` for state, `GoRouter` for navigation, and talks directly to Laravel APIs.

## Entry Points
- `lib/main.dart` - registers top-level providers
- `lib/app.dart` - route tree and navigation shell
- `lib/config/api_config.dart` - backend and storage base URLs
- `lib/services/api_service.dart` - shared HTTP client wrapper

## Commands
```bash
flutter pub get
flutter analyze                    # Static analysis
flutter test                       # Run all tests
flutter test test/widget_test.dart # Single test file
flutter run                        # Run on connected device
flutter build apk                  # Build Android APK
flutter build ios                  # Build iOS (macOS only)
```

## Run With Backend
```bash
# Android emulator
flutter run --dart-define=MITOLOGI_API_BASE_URL=http://10.0.2.2:8011

# iOS simulator / local dev
flutter run --dart-define=MITOLOGI_API_BASE_URL=http://127.0.0.1:8011

# Physical device on LAN
flutter run --dart-define=MITOLOGI_API_BASE_URL=http://YOUR-LAN-IP:8011
```

## Where To Look
| Task | Location | Notes |
|------|----------|-------|
| Navigation | `lib/app.dart` | `GoRouter`, shell branches, transitions |
| State | `lib/providers/` | auth, cart, product, content, chatbot |
| API calls | `lib/services/` | domain services wrap `ApiService` |
| Screens | `lib/screens/` | grouped by domain |
| Shared UI | `lib/widgets/` | common, product, animations |
| Theme/config | `lib/config/` | theme + API config |
| Models | `lib/models/` | Data models |
| Utils | `lib/utils/` | Helper functions |

## Conventions
- Keep screen widgets under `lib/screens/` and domain logic inside services/providers.
- Reuse `ApiService` instead of creating raw `http` calls in screens.
- Update `ApiConfig` carefully: it currently uses local LAN/loopback values, so device behavior depends on platform.
- Most project-specific code lives under `lib/`; platform folders are mostly generated scaffolding.
- Use `const` constructors where possible for performance.
- Prefer `StatelessWidget` over `StatefulWidget` when possible.
- Handle async operations with proper loading/error states.

## Code Style
- **Linting**: `flutter_lints` via `analysis_options.yaml`
- **Imports**: Absolute imports within project, `package:` for dependencies
- **Formatting**: `flutter format` (line length 80)
- **Types**: Explicit types on public APIs, use `var`/`final` for locals
- **Naming**: PascalCase classes, camelCase variables/functions, ALL_CAPS constants
- **Error Handling**: Try/catch with specific exceptions, always handle errors in UI
- **Testing**: Widget tests with `flutter_test`, mock HTTP responses

## Anti-Patterns
- Do not treat `android/`, `ios/`, `linux/`, `macos/`, `windows/`, or `web/` as primary pattern sources unless the change is platform-specific.
- Do not hardcode new endpoints across multiple screens; add them to services.
- Do not assume tests exist; `test/` is currently empty, so new logic should add focused coverage where practical.
- Do not use `print()` for logging; use `debugPrint()` or proper logging.
- Do not block UI with synchronous operations; always async.

## Pre-PR Checks
```bash
flutter analyze
flutter test
```

## Notes
- Large UI files exist under `lib/screens/`; prefer incremental edits and consider extracting subwidgets when touching them.
- `analysis_options.yaml` currently only includes `flutter_lints` defaults.
- Guest cart flow uses persisted `cart_session_id` from backend.
