import 'dart:io';

import 'package:flutter/foundation.dart';

class ApiConfig {
  // ENVIRONMENT VARIABLE OVERRIDES (highest priority)
  // Use these when running on physical devices or custom setups:
  //
  // Android Emulator: flutter run
  //    (auto-uses 10.0.2.2:8000 internally)
  //
  // iOS Simulator: flutter run
  //    (auto-uses localhost:8000)
  //
  // Physical Device: flutter run --dart-define=MITOLOGI_API_BASE_URL=http://YOUR-LAN-IP:8000
  //
  // Examples:
  //   flutter run --dart-define=MITOLOGI_API_BASE_URL=http://192.168.1.100:8000
  //   flutter run --dart-define=MITOLOGI_API_BASE_URL=http://192.168.2.50:8000

  static const String _apiBaseOverride = String.fromEnvironment(
    'MITOLOGI_API_BASE_URL',
    defaultValue: '',
  );
  static const String _storageBaseOverride = String.fromEnvironment(
    'MITOLOGI_STORAGE_BASE_URL',
    defaultValue: '',
  );
  static const String _midtransClientKeyOverride = String.fromEnvironment(
    'MITOLOGI_MIDTRANS_CLIENT_KEY',
    defaultValue: '',
  );

  static String _normalizeBase(String value) =>
      value.endsWith('/') ? value.substring(0, value.length - 1) : value;

  static String get _defaultBackendOrigin {
    // TIPS: Use 'ipconfig' on Windows to find your IPv4 if using physical device.
    // const String physicalDeviceIp = 'http://192.168.1.XX:8000';

    if (kIsWeb) return 'http://localhost:8000';

    if (Platform.isAndroid) {
      // 10.0.2.2 is the special alias for the host machine in Android Emulator
      return 'http://10.0.2.2:8000';
    }

    if (Platform.isIOS) {
      // iOS Simulator shares the host's localhost
      return 'http://localhost:8000';
    }

    return 'http://localhost:8000';
  }

  static String get baseUrl {
    final override = _normalizeBase(_apiBaseOverride.trim());
    if (override.isNotEmpty) {
      return override.endsWith('/api') ? override : '$override/api';
    }

    return '${_normalizeBase(_defaultBackendOrigin)}/api';
  }

  static String get midtransClientKey {
    return _midtransClientKeyOverride.trim();
  }

  static String get storageUrl {
    final override = _normalizeBase(_storageBaseOverride.trim());
    if (override.isNotEmpty) {
      return override;
    }

    if (_apiBaseOverride.trim().isNotEmpty) {
      final normalizedApi = _normalizeBase(_apiBaseOverride.trim());
      return normalizedApi.endsWith('/api')
          ? normalizedApi.substring(0, normalizedApi.length - 4)
          : normalizedApi;
    }

    return _normalizeBase(_defaultBackendOrigin);
  }

  static const int timeoutDuration = 30000; // 30 seconds

  // Helper untuk debugging - tampilkan backend URL yang aktif
  static String get debugInfo {
    final origin = _apiBaseOverride.isNotEmpty
        ? _apiBaseOverride
        : _defaultBackendOrigin;
    final platform = Platform.isAndroid
        ? 'Android'
        : (Platform.isIOS ? 'iOS' : 'Other');
    return 'Platform: $platform | Backend: $origin';
  }
}
