import 'package:flutter_test/flutter_test.dart';
import 'package:mobile/models/user.dart';
import 'package:mobile/providers/auth_provider.dart';
import 'package:mobile/services/secure_storage_service.dart';

import '../../fakes/fake_auth_service.dart';
import '../../fakes/fake_secure_storage.dart';

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  group('AuthProvider', () {
    late AuthProvider provider;
    late FakeAuthService fakeService;
    late FakeSecureStorage fakeStorage;

    setUp(() async {
      fakeStorage = FakeSecureStorage();
      // Initialize secure storage with fake
      SecureStorageService.initializeForTest(fakeStorage);

      fakeService = FakeAuthService();
      provider = AuthProvider(authService: fakeService);

      // Wait for initial load to complete
      await Future.delayed(const Duration(milliseconds: 50));
    });

    tearDown(() async {
      provider.dispose();
      await SecureStorageService.clearAll();
    });

    group('initial state', () {
      test('should be unauthenticated initially', () {
        expect(provider.isAuthenticated, isFalse);
        expect(provider.user, isNull);
        expect(provider.token, isNull);
        expect(provider.isLoading, isFalse);
        expect(provider.error, isNull);
      });
    });

    group('login', () {
      test('should login successfully', () async {
        // Act
        final result = await provider.login('test@example.com', 'password123');

        // Assert
        expect(result, isTrue);
        expect(provider.isAuthenticated, isTrue);
        expect(provider.user, isNotNull);
        expect(provider.user!.email, equals('test@example.com'));
        expect(provider.token, isNotNull);
        expect(provider.error, isNull);
      });

      test('should handle login error', () async {
        // Arrange
        fakeService.setError(message: 'Invalid credentials');

        // Act
        final result = await provider.login('test@example.com', 'wrong');

        // Assert
        expect(result, isFalse);
        expect(provider.isAuthenticated, isFalse);
        expect(provider.error, equals('Invalid credentials'));
      });

      test('should clear previous error before login', () async {
        // Arrange
        fakeService.setError(message: 'First error');
        await provider.login('test@example.com', 'wrong');
        expect(provider.error, equals('First error'));

        // Act
        fakeService.clearError();
        await provider.login('test@example.com', 'password123');

        // Assert
        expect(provider.error, isNull);
      });
    });

    group('register', () {
      test('should register successfully', () async {
        // Act
        final result = await provider.register(
          'New User',
          'new@example.com',
          'password123',
          'password123',
        );

        // Assert
        expect(result, isTrue);
        expect(provider.isAuthenticated, isTrue);
        expect(provider.user, isNotNull);
        expect(provider.user!.name, equals('New User'));
        expect(provider.error, isNull);
      });

      test('should handle registration error', () async {
        // Arrange
        fakeService.setError(message: 'Email already exists');

        // Act
        final result = await provider.register(
          'New User',
          'existing@example.com',
          'password123',
          'password123',
        );

        // Assert
        expect(result, isFalse);
        expect(provider.error, equals('Email already exists'));
      });
    });

    group('logout', () {
      test('should logout successfully', () async {
        // Arrange - login first
        await provider.login('test@example.com', 'password123');
        expect(provider.isAuthenticated, isTrue);

        // Act
        await provider.logout();

        // Assert
        expect(provider.isAuthenticated, isFalse);
        expect(provider.user, isNull);
        expect(provider.token, isNull);
      });

      test('should handle logout even if already logged out', () async {
        // Act & Assert - should not throw
        await provider.logout();
        expect(provider.isAuthenticated, isFalse);
      });
    });

    group('forgotPassword', () {
      test('should send forgot password request', () async {
        // Act
        final result = await provider.forgotPassword('test@example.com');

        // Assert
        expect(result, isTrue);
        expect(provider.error, isNull);
      });

      test('should handle forgot password error', () async {
        // Arrange
        fakeService.setError(message: 'Email not found');

        // Act
        final result = await provider.forgotPassword('unknown@example.com');

        // Assert
        expect(result, isFalse);
        expect(provider.error, equals('Email not found'));
      });
    });

    group('resetPassword', () {
      test('should reset password successfully', () async {
        // Act
        final result = await provider.resetPassword(
          email: 'test@example.com',
          token: 'reset-token-123',
          password: 'newpassword123',
          passwordConfirmation: 'newpassword123',
        );

        // Assert
        expect(result, isTrue);
        expect(provider.error, isNull);
      });

      test('should handle reset password error', () async {
        // Arrange
        fakeService.setError(message: 'Invalid token');

        // Act
        final result = await provider.resetPassword(
          email: 'test@example.com',
          token: 'invalid-token',
          password: 'newpassword123',
          passwordConfirmation: 'newpassword123',
        );

        // Assert
        expect(result, isFalse);
        expect(provider.error, equals('Invalid token'));
      });
    });

    group('loading states', () {
      test('should set loading during login', () async {
        // Act & Assert
        expect(provider.isLoading, isFalse);

        final future = provider.login('test@example.com', 'password123');
        expect(provider.isLoading, isTrue);

        await future;
        expect(provider.isLoading, isFalse);
      });

      test('should set loading during register', () async {
        // Act & Assert
        expect(provider.isLoading, isFalse);

        final future = provider.register(
          'New User',
          'new@example.com',
          'password123',
          'password123',
        );
        expect(provider.isLoading, isTrue);

        await future;
        expect(provider.isLoading, isFalse);
      });
    });
  });
}
