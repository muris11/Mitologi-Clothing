import 'package:mobile/models/user.dart';
import 'package:mobile/services/auth_service.dart';

/// Fake auth service for testing
class FakeAuthService implements AuthService {
  User? _user;
  String? _token;
  bool shouldThrowError = false;
  String errorMessage = 'Fake auth error';

  void setUser(User user, String token) {
    _user = user;
    _token = token;
  }

  void setError({String? message}) {
    shouldThrowError = true;
    if (message != null) {
      errorMessage = message;
    }
  }

  void clearError() {
    shouldThrowError = false;
  }

  @override
  Future<User> getUser() async {
    if (shouldThrowError) {
      throw Exception(errorMessage);
    }
    if (_user == null) {
      throw Exception('No user logged in');
    }
    return _user!;
  }

  @override
  Future<AuthResponse> login(
    String email,
    String password, {
    String? cartSessionId,
  }) async {
    if (shouldThrowError) {
      throw Exception(errorMessage);
    }

    _user = User(id: 1, email: email, name: 'Test User');
    _token = 'fake_token_123';

    return AuthResponse(token: _token!, user: _user!, cartId: cartSessionId);
  }

  @override
  Future<AuthResponse> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? cartSessionId,
  }) async {
    if (shouldThrowError) {
      throw Exception(errorMessage);
    }

    _user = User(id: 1, email: email, name: name);
    _token = 'fake_token_123';

    return AuthResponse(token: _token!, user: _user!, cartId: cartSessionId);
  }

  @override
  Future<void> logout() async {
    if (shouldThrowError) {
      throw Exception(errorMessage);
    }
    _user = null;
    _token = null;
  }

  @override
  Future<void> forgotPassword(String email) async {
    if (shouldThrowError) {
      throw Exception(errorMessage);
    }
  }

  @override
  Future<void> resetPassword(
    String token,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    if (shouldThrowError) {
      throw Exception(errorMessage);
    }
  }
}
