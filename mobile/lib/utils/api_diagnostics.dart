import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';

/// Diagnostic helper to check connectivity and API health
class ApiDiagnostics {
  static Future<Map<String, dynamic>> runDiagnostics() async {
    final results = <String, dynamic>{
      'timestamp': DateTime.now().toIso8601String(),
      'platform': _getPlatform(),
      'tests': <String, dynamic>{},
    };

    // Test 1: Configuration Check
    results['tests']['config'] = _testConfiguration();

    // Test 2: Backend Connectivity
    results['tests']['connectivity'] = await _testConnectivity();

    // Test 3: API Endpoints
    results['tests']['api'] = await _testApiEndpoints();

    return results;
  }

  static Map<String, dynamic> _testConfiguration() {
    return {
      'baseUrl': ApiConfig.baseUrl,
      'storageUrl': ApiConfig.storageUrl,
      'timeout': ApiConfig.timeoutDuration,
      'isValid':
          ApiConfig.baseUrl.isNotEmpty && ApiConfig.baseUrl.startsWith('http'),
    };
  }

  static Future<Map<String, dynamic>> _testConnectivity() async {
    try {
      final uri = ApiConfig.buildUri('/site-settings');
      final stopwatch = Stopwatch()..start();

      final response = await http.get(uri).timeout(const Duration(seconds: 5));

      stopwatch.stop();

      return {
        'success': response.statusCode == 200,
        'statusCode': response.statusCode,
        'responseTime': '${stopwatch.elapsedMilliseconds}ms',
        'error': null,
      };
    } on Exception catch (e) {
      return {
        'success': false,
        'statusCode': null,
        'responseTime': null,
        'error': e.toString(),
      };
    }
  }

  static Future<Map<String, dynamic>> _testApiEndpoints() async {
    final endpoints = [
      '/landing-page',
      '/products',
      '/categories',
      '/site-settings',
    ];

    final results = <String, dynamic>{};

    for (final endpoint in endpoints) {
      try {
        final uri = ApiConfig.buildUri(endpoint);
        final response = await http
            .get(uri)
            .timeout(const Duration(seconds: 3));

        results[endpoint] = {
          'success': response.statusCode == 200,
          'status': response.statusCode,
        };
      } on Exception catch (e) {
        results[endpoint] = {
          'success': false,
          'error': e.toString().split('\n').first,
        };
      }
    }

    return results;
  }

  static String _getPlatform() {
    if (kIsWeb) return 'Web';
    if (Platform.isAndroid) return 'Android';
    if (Platform.isIOS) return 'iOS';
    if (Platform.isWindows) return 'Windows';
    if (Platform.isMacOS) return 'macOS';
    if (Platform.isLinux) return 'Linux';
    return 'Unknown';
  }

  static String generateReport(Map<String, dynamic> results) {
    final buffer = StringBuffer();
    buffer.writeln('╔═══════════════════════════════════════════════');
    buffer.writeln('║         API DIAGNOSTICS REPORT');
    buffer.writeln('╠═══════════════════════════════════════════════');
    buffer.writeln('║ Timestamp: ${results['timestamp']}');
    buffer.writeln('║ Platform: ${results['platform']}');
    buffer.writeln('╠═══════════════════════════════════════════════');

    final config = results['tests']['config'] as Map<String, dynamic>;
    buffer.writeln('║ CONFIGURATION:');
    buffer.writeln('║   Base URL: ${config['baseUrl']}');
    buffer.writeln('║   Storage URL: ${config['storageUrl']}');
    buffer.writeln('║   Timeout: ${config['timeout']}ms');
    final isValid = config['isValid'] as bool? ?? false;
    buffer.writeln('║   Valid: ${isValid ? '✅' : '❌'}');
    buffer.writeln('╠═══════════════════════════════════════════════');

    final connectivity =
        results['tests']['connectivity'] as Map<String, dynamic>;
    buffer.writeln('║ CONNECTIVITY:');
    final connSuccess = connectivity['success'] as bool? ?? false;
    buffer.writeln('║   Success: ${connSuccess ? '✅' : '❌'}');
    if (connectivity['statusCode'] != null) {
      buffer.writeln('║   Status: ${connectivity['statusCode']}');
      buffer.writeln('║   Response Time: ${connectivity['responseTime']}');
    }
    if (connectivity['error'] != null) {
      buffer.writeln('║   Error: ${connectivity['error']}');
    }
    buffer.writeln('╠═══════════════════════════════════════════════');

    final api = results['tests']['api'] as Map<String, dynamic>;
    buffer.writeln('║ API ENDPOINTS:');
    for (final entry in api.entries) {
      final success = entry.value['success'] as bool? ?? false;
      final status = entry.value['status'] ?? entry.value['error'] ?? 'N/A';
      buffer.writeln('║   ${entry.key}: ${success ? '✅' : '❌'} ($status)');
    }
    buffer.writeln('╚═══════════════════════════════════════════════');

    return buffer.toString();
  }
}
