import 'api_service.dart';
import '../models/user.dart';

class AuthResponse {
  final String token;
  final User user;
  final String? cartId;

  AuthResponse({required this.token, required this.user, this.cartId});

  factory AuthResponse.fromJson(Map<String, dynamic> json) {
    // Handle both camelCase (backend) and snake_case (legacy) responses
    final data = json['data'] is Map
        ? Map<String, dynamic>.from(json['data'] as Map)
        : json;

    return AuthResponse(
      token: (data['token'] ?? json['token'] ?? '').toString(),
      user: User.fromJson(
        data['user'] is Map
            ? Map<String, dynamic>.from(data['user'] as Map)
            : data,
      ),
      // Support both camelCase (new) and snake_case (legacy)
      cartId:
          (data['cartId'] ??
                  data['cart_id'] ??
                  json['cartId'] ??
                  json['cart_id'])
              ?.toString(),
    );
  }
}

class AuthService {
  AuthService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<AuthResponse> login(
    String email,
    String password, {
    String? cartSessionId,
  }) async {
    final body = {'email': email, 'password': password};
    if (cartSessionId != null) {
      body['cart_session_id'] = cartSessionId;
    }

    final response = await _api.post('/auth/login', body: body);
    return AuthResponse.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<AuthResponse> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? cartSessionId,
  }) async {
    final body = {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
    };
    if (cartSessionId != null) {
      body['cart_session_id'] = cartSessionId;
    }

    final response = await _api.post('/auth/register', body: body);
    return AuthResponse.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<void> logout() async {
    await _api.post('/auth/logout', requiresAuth: true);
  }

  Future<User> getUser() async {
    final response = await _api.get('/auth/user', requiresAuth: true);
    if (response is Map<String, dynamic> && response['data'] is Map) {
      return User.fromJson(Map<String, dynamic>.from(response['data'] as Map));
    }
    if (response is Map<String, dynamic> && response.containsKey('user')) {
      return User.fromJson(Map<String, dynamic>.from(response['user'] as Map));
    }
    return User.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<void> forgotPassword(String email) async {
    await _api.post('/auth/forgot-password', body: {'email': email});
  }

  Future<void> resetPassword(
    String token,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    final body = {
      'token': token,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
    };
    await _api.post('/auth/reset-password', body: body);
  }
}
