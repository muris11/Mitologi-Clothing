import '../config/api_config.dart';

class StorageUrl {
  /// Converts a relative storage path (e.g., /storage/images/...) into an absolute URL
  /// If the URL is already absolute (starts with http), it returns it as is.
  static String format(String? path) {
    if (path == null || path.isEmpty) {
      return ''; // Or return a placeholder image URL
    }

    if (path.startsWith('http://') || path.startsWith('https://')) {
      return path;
    }

    // Ensure path starts with a slash
    final safePath = path.startsWith('/') ? path : '/$path';

    // Avoid double /api if baseUrl has it and we just want the root domain
    final rootUrl = ApiConfig.storageUrl;

    // Handle specific backend paths that might omit /storage
    if (!safePath.startsWith('/storage/')) {
      // In some setups, images might be served directly from public folder
      return '$rootUrl/storage$safePath';
    }

    return '$rootUrl$safePath';
  }
}
