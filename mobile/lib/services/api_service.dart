import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import 'secure_storage_service.dart';

class ApiException implements Exception {
  final String message;
  final int statusCode;
  final dynamic errors;

  ApiException(this.message, this.statusCode, [this.errors]);

  @override
  String toString() => 'ApiException: $message (Status: $statusCode)';
}

class ApiService {
  final http.Client _client = http.Client();

  Future<Map<String, String>> _getHeaders({bool requiresAuth = false}) async {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };

    // Auth token
    if (requiresAuth) {
      final token = await SecureStorageService.getAuthToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    // Cart session ID for guest cart operations
    final cartSessionId = await SecureStorageService.getCartSessionId();
    if (cartSessionId != null) {
      headers['X-Cart-Id'] = cartSessionId;
      headers['X-Session-Id'] = cartSessionId;
    }

    return headers;
  }

  Future<dynamic> get(
    String endpoint, {
    Map<String, dynamic>? queryParams,
    bool requiresAuth = false,
  }) async {
    // Use proper URI construction with encoding
    final queryParameters = queryParams?.map(
      (key, value) => MapEntry(key, value.toString()),
    );
    final url = ApiConfig.buildUri(endpoint, queryParams: queryParameters);
    final headers = await _getHeaders(requiresAuth: requiresAuth);

    try {
      final response = await _client
          .get(url, headers: headers)
          .timeout(const Duration(milliseconds: ApiConfig.timeoutDuration));

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } on Exception catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> post(
    String endpoint, {
    Map<String, dynamic>? body,
    bool requiresAuth = false,
  }) async {
    final url = ApiConfig.buildUri(endpoint);
    final headers = await _getHeaders(requiresAuth: requiresAuth);

    try {
      final response = await _client
          .post(
            url,
            headers: headers,
            body: body != null ? json.encode(body) : null,
          )
          .timeout(const Duration(milliseconds: ApiConfig.timeoutDuration));

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } on Exception catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> put(
    String endpoint, {
    Map<String, dynamic>? body,
    bool requiresAuth = false,
  }) async {
    final url = ApiConfig.buildUri(endpoint);
    final headers = await _getHeaders(requiresAuth: requiresAuth);

    try {
      final response = await _client
          .put(
            url,
            headers: headers,
            body: body != null ? json.encode(body) : null,
          )
          .timeout(const Duration(milliseconds: ApiConfig.timeoutDuration));

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } on Exception catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> delete(String endpoint, {bool requiresAuth = false}) async {
    final url = ApiConfig.buildUri(endpoint);
    final headers = await _getHeaders(requiresAuth: requiresAuth);

    try {
      final response = await _client
          .delete(url, headers: headers)
          .timeout(const Duration(milliseconds: ApiConfig.timeoutDuration));

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } on Exception catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> multipartPost(
    String endpoint, {
    required String filePath,
    required String fileField,
    Map<String, String>? fields,
    bool requiresAuth = false,
  }) async {
    final url = ApiConfig.buildUri(endpoint);
    final headers = await _getHeaders(requiresAuth: requiresAuth);
    // Remove content-type as multipart request sets its own boundary
    headers.remove('Content-Type');

    try {
      final request = http.MultipartRequest('POST', url);
      request.headers.addAll(headers);

      if (fields != null) {
        request.fields.addAll(fields);
      }

      request.files.add(await http.MultipartFile.fromPath(fileField, filePath));

      final streamedResponse = await request.send().timeout(
        const Duration(milliseconds: ApiConfig.timeoutDuration),
      );
      final response = await http.Response.fromStream(streamedResponse);

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } on Exception catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  dynamic _processResponse(http.Response response) {
    if (response.body.isEmpty) return null;

    dynamic jsonResponse;
    try {
      jsonResponse = json.decode(response.body);
    } on Exception {
      if (response.statusCode >= 200 && response.statusCode < 300) {
        return response.body;
      }
      throw ApiException('Invalid JSON Response', response.statusCode);
    }

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return jsonResponse;
    } else {
      String message = 'Something went wrong';
      dynamic errors;

      if (jsonResponse is Map<String, dynamic>) {
        message =
            (jsonResponse['message'] as String?) ??
            (jsonResponse['error'] is Map<String, dynamic>
                ? jsonResponse['error']['message'] as String?
                : null) ??
            message;
        errors = jsonResponse['errors'];
      }

      throw ApiException(message, response.statusCode, errors);
    }
  }
}
