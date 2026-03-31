import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

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

    final prefs = await SharedPreferences.getInstance();

    // Auth token
    if (requiresAuth) {
      final token = prefs.getString('auth_token');
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    // Cart session ID for guest cart operations
    final cartSessionId = prefs.getString('cart_session_id');
    if (cartSessionId != null) {
      headers['X-Cart-Id'] = cartSessionId;
    }

    return headers;
  }

  Future<dynamic> get(
    String endpoint, {
    Map<String, dynamic>? queryParams,
    bool requiresAuth = false,
  }) async {
    Uri url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    if (queryParams != null) {
      url = url.replace(
        queryParameters: queryParams.map(
          (key, value) => MapEntry(key, value.toString()),
        ),
      );
    }
    final headers = await _getHeaders(requiresAuth: requiresAuth);

    try {
      final response = await _client
          .get(url, headers: headers)
          .timeout(const Duration(milliseconds: ApiConfig.timeoutDuration));

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> post(
    String endpoint, {
    Map<String, dynamic>? body,
    bool requiresAuth = false,
  }) async {
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
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
    } catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> put(
    String endpoint, {
    Map<String, dynamic>? body,
    bool requiresAuth = false,
  }) async {
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
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
    } catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  Future<dynamic> delete(String endpoint, {bool requiresAuth = false}) async {
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    final headers = await _getHeaders(requiresAuth: requiresAuth);

    try {
      final response = await _client
          .delete(url, headers: headers)
          .timeout(const Duration(milliseconds: ApiConfig.timeoutDuration));

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } catch (e) {
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
    final url = Uri.parse('${ApiConfig.baseUrl}$endpoint');
    final headers = await _getHeaders(requiresAuth: requiresAuth);
    // Remove content-type as multipart request sets its own boundary
    headers.remove('Content-Type');

    try {
      var request = http.MultipartRequest('POST', url);
      request.headers.addAll(headers);

      if (fields != null) {
        request.fields.addAll(fields);
      }

      request.files.add(await http.MultipartFile.fromPath(fileField, filePath));

      var streamedResponse = await request.send().timeout(
        const Duration(milliseconds: ApiConfig.timeoutDuration),
      );
      var response = await http.Response.fromStream(streamedResponse);

      return _processResponse(response);
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException('Network Error: $e', 0);
    }
  }

  dynamic _processResponse(http.Response response) {
    if (response.body.isEmpty) return null;

    dynamic jsonResponse;
    try {
      jsonResponse = json.decode(response.body);
    } catch (e) {
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
        message = jsonResponse['message'] ?? message;
        errors = jsonResponse['errors'];
      }

      throw ApiException(message, response.statusCode, errors);
    }
  }
}
