// API Response Utility
//
// Standarisasi parsing response dari backend Laravel.
// Backend mengembalikan format: { data: T, message?: string }
// atau langsung T untuk beberapa endpoint legacy.

class ApiResponseHelper {
  /// Extract data dari response API
  /// Support format: { data: T } atau langsung T
  static T? extractData<T>(
    dynamic response, {
    T? Function(dynamic)? converter,
    String? dataKey,
  }) {
    if (response == null) return null;

    // Jika response adalah Map
    if (response is Map<String, dynamic>) {
      // Coba ambil dari key 'data' terlebih dahulu (standard)
      if (response.containsKey('data')) {
        final data = response['data'];
        if (converter != null) {
          return converter(data);
        }
        return data as T?;
      }

      // Coba ambil dari key spesifik (untuk endpoint legacy)
      if (dataKey != null && response.containsKey(dataKey)) {
        final data = response[dataKey];
        if (converter != null) {
          return converter(data);
        }
        return data as T?;
      }

      // Jika tidak ada wrapper, return langsung dengan converter
      if (converter != null) {
        return converter(response);
      }

      // Return langsung jika T sesuai
      return response as T?;
    }

    // Jika response adalah List
    if (response is List) {
      if (converter != null) {
        return converter(response);
      }
      return response as T?;
    }

    // Jika converter disediakan, coba convert
    if (converter != null) {
      return converter(response);
    }

    return response as T?;
  }

  /// Extract array data dan convert ke List&lt;T&gt;
  static List<T> extractList<T>(
    dynamic response, {
    required T Function(dynamic) converter,
    String? dataKey,
  }) {
    if (response == null) return [];

    List<dynamic> rawList = [];

    // Jika response adalah Map, ambil dari 'data' atau key spesifik
    if (response is Map<String, dynamic>) {
      if (response.containsKey('data')) {
        final data = response['data'];
        if (data is List) {
          rawList = data;
        } else if (data is Map &&
            dataKey != null &&
            data.containsKey(dataKey)) {
          rawList = data[dataKey] as List? ?? [];
        }
      } else if (dataKey != null && response.containsKey(dataKey)) {
        rawList = response[dataKey] as List? ?? [];
      }
    }
    // Jika response adalah List langsung
    else if (response is List) {
      rawList = response;
    }

    return rawList.map(converter).toList();
  }

  /// Extract pagination data dari response
  static Map<String, dynamic>? extractPagination(dynamic response) {
    if (response is! Map<String, dynamic>) return null;

    // Coba ambil dari key 'meta' (standard)
    if (response.containsKey('meta')) {
      return response['meta'] as Map<String, dynamic>?;
    }

    // Coba ambil dari key 'pagination' (legacy)
    if (response.containsKey('pagination')) {
      return response['pagination'] as Map<String, dynamic>?;
    }

    return null;
  }

  /// Extract error message dari response
  static String? extractErrorMessage(dynamic response) {
    if (response is! Map<String, dynamic>) return null;

    // Format standard: { error: { message: string } }
    if (response.containsKey('error')) {
      final error = response['error'];
      if (error is Map<String, dynamic>) {
        return error['message'] as String?;
      }
      if (error is String) {
        return error;
      }
    }

    // Format legacy: { message: string }
    if (response.containsKey('message')) {
      return response['message'] as String?;
    }

    return null;
  }

  /// Check apakah response adalah error
  static bool isError(dynamic response) {
    if (response is! Map<String, dynamic>) return false;
    return response.containsKey('error');
  }

  /// Field accessor yang support both camelCase dan snake_case
  /// Contoh: getField(response, 'cartId', 'cart_id')
  static T? getField<T>(
    Map<String, dynamic> response,
    String camelCase, [
    String? snakeCase,
  ]) {
    // Coba camelCase terlebih dahulu
    if (response.containsKey(camelCase)) {
      return response[camelCase] as T?;
    }

    // Fallback ke snake_case jika ada
    if (snakeCase != null && response.containsKey(snakeCase)) {
      return response[snakeCase] as T?;
    }

    return null;
  }
}
