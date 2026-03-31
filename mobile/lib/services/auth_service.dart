import 'api_service.dart';
import '../models/user.dart';

class AuthResponse {
  final String token;
  final User user;
  final String? cartId;

  AuthResponse({required this.token, required this.user, this.cartId});

  factory AuthResponse.fromJson(Map<String, dynamic> json) {
    return AuthResponse(
      token: json['token'] ?? '',
      user: User.fromJson(json['user']),
      cartId: json['cart_id'],
    );
  }
}

class AuthService {
  final ApiService _api = ApiService();

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
    return AuthResponse.fromJson(response);
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
    return AuthResponse.fromJson(response);
  }

  Future<void> logout() async {
    await _api.post('/auth/logout', requiresAuth: true);
  }

  Future<User> getUser() async {
    final response = await _api.get('/auth/user', requiresAuth: true);
    if (response is Map<String, dynamic> && response.containsKey('user')) {
      return User.fromJson(response['user']);
    }
    return User.fromJson(response);
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
