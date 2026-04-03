import 'dart:async';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

/// Fake secure storage for testing
class FakeSecureStorage implements FlutterSecureStorage {
  final Map<String, String> _storage = {};
  final Map<String, List<void Function(String?)>> _listeners = {};

  @override
  Future<void> write({
    required String key,
    required String? value,
    IOSOptions? iOptions,
    AndroidOptions? aOptions,
    LinuxOptions? lOptions,
    WebOptions? webOptions,
    MacOsOptions? mOptions,
    WindowsOptions? wOptions,
  }) async {
    if (value != null) {
      _storage[key] = value;
    } else {
      _storage.remove(key);
    }
    // Notify listeners
    _notifyListeners(key, value);
  }

  @override
  Future<String?> read({
    required String key,
    IOSOptions? iOptions,
    AndroidOptions? aOptions,
    LinuxOptions? lOptions,
    WebOptions? webOptions,
    MacOsOptions? mOptions,
    WindowsOptions? wOptions,
  }) async {
    return _storage[key];
  }

  @override
  Future<void> delete({
    required String key,
    IOSOptions? iOptions,
    AndroidOptions? aOptions,
    LinuxOptions? lOptions,
    WebOptions? webOptions,
    MacOsOptions? mOptions,
    WindowsOptions? wOptions,
  }) async {
    _storage.remove(key);
    _notifyListeners(key, null);
  }

  @override
  Future<void> deleteAll({
    IOSOptions? iOptions,
    AndroidOptions? aOptions,
    LinuxOptions? lOptions,
    WebOptions? webOptions,
    MacOsOptions? mOptions,
    WindowsOptions? wOptions,
  }) async {
    final keys = List<String>.from(_storage.keys);
    _storage.clear();
    for (final key in keys) {
      _notifyListeners(key, null);
    }
  }

  @override
  Future<Map<String, String>> readAll({
    IOSOptions? iOptions,
    AndroidOptions? aOptions,
    LinuxOptions? lOptions,
    WebOptions? webOptions,
    MacOsOptions? mOptions,
    WindowsOptions? wOptions,
  }) async {
    return Map.unmodifiable(_storage);
  }

  @override
  Future<bool> containsKey({
    required String key,
    IOSOptions? iOptions,
    AndroidOptions? aOptions,
    LinuxOptions? lOptions,
    WebOptions? webOptions,
    MacOsOptions? mOptions,
    WindowsOptions? wOptions,
  }) async {
    return _storage.containsKey(key);
  }

  // Implement remaining interface methods as no-ops or throw UnimplementedError
  @override
  AndroidOptions get aOptions => AndroidOptions.defaultOptions;

  @override
  IOSOptions get iOptions => IOSOptions.defaultOptions;

  @override
  LinuxOptions get lOptions => LinuxOptions.defaultOptions;

  @override
  MacOsOptions get mOptions => MacOsOptions.defaultOptions;

  @override
  WebOptions get webOptions => WebOptions.defaultOptions;

  @override
  WindowsOptions get wOptions => WindowsOptions.defaultOptions;

  // Additional methods required by FlutterSecureStorage interface v9.2.2
  @override
  Future<bool> isCupertinoProtectedDataAvailable() async => true;

  @override
  Stream<bool> get onCupertinoProtectedDataAvailabilityChanged =>
      Stream.value(true);

  @override
  void registerListener({
    required String key,
    required void Function(String?) listener,
  }) {
    _listeners.putIfAbsent(key, () => []).add(listener);
  }

  @override
  void unregisterAllListeners() {
    _listeners.clear();
  }

  @override
  void unregisterAllListenersForKey({required String key}) {
    _listeners.remove(key);
  }

  @override
  void unregisterListener({
    required String key,
    required void Function(String?) listener,
  }) {
    _listeners[key]?.remove(listener);
  }

  void _notifyListeners(String key, String? value) {
    _listeners[key]?.forEach((listener) => listener(value));
  }
}
