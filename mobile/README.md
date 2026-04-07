# Mitologi Mobile

Flutter storefront client for Mitologi Clothing.

## Requirements

- Flutter SDK
- A running Laravel backend in `../backend`
- Optional: a running recommendation service in `../recommendation-service`

## Run With Real Backend Data

Use `--dart-define` so the app points to the real backend instead of relying on device-specific hardcoded IPs.

Example for Android emulator:

```bash
flutter run \
  --dart-define=MITOLOGI_API_BASE_URL=http://10.0.2.2:8011 \
  --dart-define=MITOLOGI_STORAGE_BASE_URL=http://10.0.2.2:8011 \
  --dart-define=MITOLOGI_MIDTRANS_CLIENT_KEY=your-midtrans-client-key
```

Example for iOS simulator / macOS / Windows local dev:

```bash
flutter run \
  --dart-define=MITOLOGI_API_BASE_URL=http://127.0.0.1:8011 \
  --dart-define=MITOLOGI_STORAGE_BASE_URL=http://127.0.0.1:8011 \
  --dart-define=MITOLOGI_MIDTRANS_CLIENT_KEY=your-midtrans-client-key
```

Example for physical device on LAN:

```bash
flutter run \
  --dart-define=MITOLOGI_API_BASE_URL=http://YOUR-LAN-IP:8011 \
  --dart-define=MITOLOGI_STORAGE_BASE_URL=http://YOUR-LAN-IP:8011 \
  --dart-define=MITOLOGI_MIDTRANS_CLIENT_KEY=your-midtrans-client-key
```

## Checks

```bash
flutter analyze
flutter test
```

## Notes

- The app now persists `cart_session_id` from backend cart responses, so guest cart flow stays backend-authoritative.
- `forgot password`, collections, recommendations, cart, checkout, account, and wishlist flows are expected to use backend APIs.
