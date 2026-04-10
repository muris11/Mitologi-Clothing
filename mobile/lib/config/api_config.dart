import 'dart:io';

import 'package:flutter/foundation.dart';

class ApiConfig {
  ApiConfig._();

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

  /// Private getter for base URL derived strictly from environment variables.
  /// Throws an [UnimplementedError] if MITOLOGI_API_BASE_URL is not provided.
  static String get _defaultBackendOrigin {
    if (_apiBaseOverride.isEmpty) {
      throw UnimplementedError(
        'MITOLOGI_API_BASE_URL is not defined via --dart-define. '
        'Example: --dart-define=MITOLOGI_API_BASE_URL=https://api.yourdomain.com',
      );
    }
    return _apiBaseOverride;
  }

  /// Base URL from dart-define (with fallback logic removed for strict production configuration)
  static String get baseUrl {
    final base = _normalizeBase(_defaultBackendOrigin);
    return base.endsWith('/api/v1') ? base : '$base/api/v1';
  }

  /// Raw base URL without API version (for URI construction)
  static String get _rawBaseUrl {
    return _normalizeBase(_defaultBackendOrigin);
  }

  static String get normalizedBase {
    final base = _normalizeBase(_defaultBackendOrigin);
    return base.endsWith('/api')
        ? base.substring(0, base.length - 4)
        : base;
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

  static const int timeoutDuration = 30000;

  static Uri buildUri(String endpoint, {Map<String, String>? queryParams}) {
    final trimmedEndpoint = endpoint.startsWith('/')
        ? endpoint.substring(1)
        : endpoint;
    final endpointUri = Uri.parse(trimmedEndpoint);
    final mergedQueryParams = <String, String>{
      ...endpointUri.queryParameters,
      ...?queryParams,
    };
    final path = 'api/v1/${endpointUri.path}';

    if (useHttps) {
      return Uri.https(
        authority,
        path,
        mergedQueryParams.isEmpty ? null : mergedQueryParams,
      );
    } else {
      return Uri.http(
        authority,
        path,
        mergedQueryParams.isEmpty ? null : mergedQueryParams,
      );
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
