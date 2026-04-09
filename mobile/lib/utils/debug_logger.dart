import 'package:flutter/foundation.dart';

/// Helper class for debugging and logging
class DebugLogger {
  static void log(
    String message, {
    String? tag,
    dynamic error,
    StackTrace? stackTrace,
  }) {
    if (kDebugMode) {
      final timestamp = DateTime.now().toIso8601String();
      final tagStr = tag != null ? '[$tag] ' : '';
      print('$timestamp $tagStr$message');

      if (error != null) {
        print('ERROR: $error');
      }

      if (stackTrace != null) {
        print('STACKTRACE: $stackTrace');
      }
    }
  }

  static void api(
    String endpoint, {
    dynamic request,
    dynamic response,
    dynamic error,
  }) {
    if (kDebugMode) {
      print('┌─────────────────────────────────────');
      print('│ API CALL: $endpoint');
      if (request != null) print('│ REQUEST: $request');
      if (response != null) print('│ RESPONSE: $response');
      if (error != null) print('│ ERROR: $error');
      print('└─────────────────────────────────────');
    }
  }

  static void error(
    String message, {
    dynamic error,
    StackTrace? stackTrace,
    String? source,
  }) {
    if (kDebugMode) {
      final sourceStr = source != null ? '[$source] ' : '';
      print('╔═════════════════════════════════════');
      print('║ ❌ ERROR: $sourceStr$message');
      if (error != null) print('║ $error');
      if (stackTrace != null) print('║ $stackTrace');
      print('╚═════════════════════════════════════');
    }
  }
}
