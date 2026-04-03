import 'package:flutter/material.dart';
import '../models/user.dart';
import '../services/auth_service.dart';
import '../services/secure_storage_service.dart';

class AuthProvider extends ChangeNotifier {
  final AuthService _authService;

  User? _user;
  String? _token;
  bool _isLoading = false;
  String? _error;

  User? get user => _user;
  String? get token => _token;
  bool get isAuthenticated => _token != null && _user != null;
  bool get isLoading => _isLoading;
  String? get error => _error;

  AuthProvider({AuthService? authService})
    : _authService = authService ?? AuthService() {
    loadUserFromStorage();
  }

  void _setLoading(bool value) {
    _isLoading = value;
    notifyListeners();
  }

  void _setError(String? message) {
    _error = message;
    notifyListeners();
  }

  Future<void> loadUserFromStorage() async {
    _setLoading(true);
    try {
      _token = await SecureStorageService.getAuthToken();

      if (_token != null) {
        // Fetch fresh user data
        _user = await _authService.getUser();
      }
    } catch (e) {
      // Token might be invalid or expired
      _token = null;
      _user = null;
      await SecureStorageService.deleteAuthToken();
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> login(String email, String password) async {
    _setLoading(true);
    _setError(null);
    try {
      final cartSessionId = await SecureStorageService.getCartSessionId();

      final response = await _authService.login(
        email,
        password,
        cartSessionId: cartSessionId,
      );

      _token = response.token;
      _user = response.user;

      await SecureStorageService.saveAuthToken(_token!);

      // If a new cart was returned/merged, update it in storage
      if (response.cartId != null) {
        await SecureStorageService.saveCartSessionId(response.cartId!);
      }

      _setLoading(false);
      return true;
    } catch (e) {
      _setError(
        e
            .toString()
            .replaceAll('ApiException: ', '')
            .replaceAll('Exception: ', ''),
      );
      _setLoading(false);
      return false;
    }
  }

  Future<bool> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    _setLoading(true);
    _setError(null);
    try {
      final cartSessionId = await SecureStorageService.getCartSessionId();

      final response = await _authService.register(
        name,
        email,
        password,
        passwordConfirmation,
        cartSessionId: cartSessionId,
      );

      _token = response.token;
      _user = response.user;

      await SecureStorageService.saveAuthToken(_token!);

      if (response.cartId != null) {
        await SecureStorageService.saveCartSessionId(response.cartId!);
      }

      _setLoading(false);
      return true;
    } catch (e) {
      _setError(
        e
            .toString()
            .replaceAll('ApiException: ', '')
            .replaceAll('Exception: ', ''),
      );
      _setLoading(false);
      return false;
    }
  }

  Future<void> logout() async {
    _setLoading(true);
    try {
      await _authService.logout();
    } catch (e) {
      // Ignore errors on logout (e.g., already unauthenticated)
    } finally {
      _token = null;
      _user = null;
      await SecureStorageService.deleteAuthToken();
      _setLoading(false);
    }
  }

  Future<bool> forgotPassword(String email) async {
    _setLoading(true);
    _setError(null);
    try {
      await _authService.forgotPassword(email);
      _setLoading(false);
      return true;
    } catch (e) {
      _setError(
        e
            .toString()
            .replaceAll('ApiException: ', '')
            .replaceAll('Exception: ', ''),
      );
      _setLoading(false);
      return false;
    }
  }

  Future<bool> resetPassword({
    required String email,
    required String token,
    required String password,
    required String passwordConfirmation,
  }) async {
    _setLoading(true);
    _setError(null);
    try {
      await _authService.resetPassword(
        token,
        email,
        password,
        passwordConfirmation,
      );
      _setLoading(false);
      return true;
    } catch (e) {
      _setError(
        e
            .toString()
            .replaceAll('ApiException: ', '')
            .replaceAll('Exception: ', ''),
      );
      _setLoading(false);
      return false;
    }
  }
}
