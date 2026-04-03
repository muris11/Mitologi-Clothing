import 'dart:io';

import 'package:flutter/foundation.dart';

/// API Configuration for Mitologi Clothing Mobile App
///
/// Configuration uses dart-define for environment-specific values:
/// --dart-define=MITOLOGI_API_BASE_URL=http://127.0.0.1:8000
class ApiConfig {
  ApiConfig._();

  /// ENVIRONMENT VARIABLE OVERRIDES (highest priority)
  /// Use these when running on physical devices or custom setups:
  ///
  /// Android Emulator: flutter run
  ///    (auto-uses 10.0.2.2:8000 internally)
  ///
  /// iOS Simulator: flutter run
  ///    (auto-uses localhost:8000)
  ///
  /// Physical Device: flutter run --dart-define=MITOLOGI_API_BASE_URL=http://YOUR-LAN-IP:8000
  ///
  /// Examples:
  ///   flutter run --dart-define=MITOLOGI_API_BASE_URL=http://192.168.1.100:8000
  ///   flutter run --dart-define=MITOLOGI_API_BASE_URL=http://192.168.2.50:8000

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

  /// Base URL from dart-define or platform default
  static String get baseUrl {
    final override = _normalizeBase(_apiBaseOverride.trim());
    if (override.isNotEmpty) {
      return override.endsWith('/api') ? override : '$override/api';
    }

    return '${_normalizeBase(_defaultBackendOrigin)}/api';
  }

  /// Raw base URL without API version (for URI construction)
  static String get _rawBaseUrl {
    final override = _normalizeBase(_apiBaseOverride.trim());
    if (override.isNotEmpty) {
      return normalizedBase;
    }
    return _normalizeBase(_defaultBackendOrigin);
  }

  static String get normalizedBase {
    final override = _normalizeBase(_apiBaseOverride.trim());
    if (override.isNotEmpty) {
      return override.endsWith('/api')
          ? override.substring(0, override.length - 4)
          : override;
    }
    return _normalizeBase(_defaultBackendOrigin);
  }

  /// Authority (host:port) for Uri.https/Uri.http construction
  ///
  /// Parses the base URL to extract authority
  static String get authority {
    final uri = Uri.parse(_rawBaseUrl);
    if (uri.port != 0 && uri.port != (uri.isScheme('https') ? 443 : 80)) {
      return '${uri.host}:${uri.port}';
    }
    return uri.host;
  }

  /// Whether to use HTTPS (true) or HTTP (false)
  static bool get useHttps {
    final uri = Uri.parse(_rawBaseUrl);
    return uri.isScheme('https');
  }

  static String get midtransClientKey {
    return _midtransClientKeyOverride.trim();
  }

  /// Storage URL for images and files
  static String get storageUrl {
    final override = _normalizeBase(_storageBaseOverride.trim());
    if (override.isNotEmpty) {
      return override;
    }

    return normalizedBase;
  }

  /// Timeout duration for API requests in milliseconds
  static const int timeoutDuration = 30000; // 30 seconds

  /// Construct a URI for API endpoint
  ///
  /// Use this instead of string concatenation for proper encoding
  ///
  /// Example:
  /// ```dart
  /// final uri = ApiConfig.buildUri('/products');
  /// final uriWithParams = ApiConfig.buildUri('/products', queryParams: {'page': '1'});
  /// ```
  static Uri buildUri(String endpoint, {Map<String, String>? queryParams}) {
    // Remove leading slash from endpoint to avoid double slashes
    final cleanEndpoint = endpoint.startsWith('/')
        ? endpoint.substring(1)
        : endpoint;
    final path = 'api/$cleanEndpoint';

    if (useHttps) {
      return Uri.https(authority, path, queryParams);
    } else {
      return Uri.http(authority, path, queryParams);
    }
  }

  /// Debug helper to print current configuration
  static void printConfig() {
    if (kDebugMode) {
      print('API Config:');
      print('  Base URL: $baseUrl');
      print('  Raw Base URL: $_rawBaseUrl');
      print('  Authority: $authority');
      print('  Use HTTPS: $useHttps');
      print('  Storage URL: $storageUrl');
    }
  }

  /// Helper untuk debugging - tampilkan backend URL yang aktif
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
