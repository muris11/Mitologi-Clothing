import 'package:flutter_secure_storage/flutter_secure_storage.dart';

/// Secure storage service for sensitive data
///
/// Uses flutter_secure_storage which encrypts data using:
/// - iOS: Keychain
/// - Android: EncryptedSharedPreferences
class SecureStorageService {
  static FlutterSecureStorage _storage = const FlutterSecureStorage(
    aOptions: AndroidOptions(encryptedSharedPreferences: true),
    iOptions: IOSOptions(
      accessibility: KeychainAccessibility.first_unlock_this_device,
    ),
  );

  static const _authTokenKey = 'auth_token';
  static const _cartSessionIdKey = 'cart_session_id';

  /// Initialize with a custom storage implementation (for testing)
  static void initializeForTest(FlutterSecureStorage storage) {
    _storage = storage;
  }

  /// Save auth token securely
  static Future<void> saveAuthToken(String token) async {
    await _storage.write(key: _authTokenKey, value: token);
  }

  /// Get auth token
  static Future<String?> getAuthToken() async {
    return await _storage.read(key: _authTokenKey);
  }

  /// Delete auth token
  static Future<void> deleteAuthToken() async {
    await _storage.delete(key: _authTokenKey);
  }

  /// Save cart session ID
  static Future<void> saveCartSessionId(String sessionId) async {
    await _storage.write(key: _cartSessionIdKey, value: sessionId);
  }

  /// Get cart session ID
  static Future<String?> getCartSessionId() async {
    return await _storage.read(key: _cartSessionIdKey);
  }

  /// Delete cart session ID
  static Future<void> deleteCartSessionId() async {
    await _storage.delete(key: _cartSessionIdKey);
  }

  /// Clear all secure storage
  static Future<void> clearAll() async {
    await _storage.deleteAll();
  }
}
