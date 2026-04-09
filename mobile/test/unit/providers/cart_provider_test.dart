import 'package:flutter_test/flutter_test.dart';
import 'package:mobile/models/cart.dart';
import 'package:mobile/models/money.dart';
import 'package:mobile/providers/cart_provider.dart';

import '../../fakes/fake_cart_service.dart';

void main() {
  group('CartProvider', () {
    late CartProvider provider;
    late FakeCartService fakeService;

    setUp(() {
      fakeService = FakeCartService();
      provider = CartProvider(cartService: fakeService);
    });

    tearDown(() {
      provider.dispose();
    });

    group('initial state', () {
      test('should have null cart initially', () {
        expect(provider.cart, isNull);
        expect(provider.isLoading, isFalse);
        expect(provider.error, isNull);
        expect(provider.itemCount, equals(0));
      });
    });

    group('fetchCart', () {
      test('should fetch cart successfully', () async {
        // Arrange
        final testCart = Cart(
          id: 'test-cart',
          checkoutUrl: '',
          cost: CartCost(
            subtotalAmount: const Money(amount: '100000', currencyCode: 'IDR'),
            totalAmount: const Money(amount: '100000', currencyCode: 'IDR'),
            totalTaxAmount: const Money(amount: '0', currencyCode: 'IDR'),
          ),
          lines: [],
          totalQuantity: 0,
        );
        fakeService.setCart(testCart);

        // Act
        await provider.fetchCart();

        // Assert
        expect(provider.isLoading, isFalse);
        expect(provider.error, isNull);
        expect(provider.cart, isNotNull);
        expect(provider.cart!.id, equals('test-cart'));
      });

      test('should handle fetch error', () async {
        // Arrange
        fakeService.setError(message: 'Network error');

        // Act
        await provider.fetchCart();

        // Assert
        expect(provider.isLoading, isFalse);
        expect(provider.error, equals('Network error (Status: 500)'));
        expect(provider.cart, isNull);
      });

      test('should set loading state during fetch', () async {
        // Arrange
        final testCart = Cart(
          id: 'test-cart',
          checkoutUrl: '',
          cost: CartCost(
            subtotalAmount: const Money(amount: '0', currencyCode: 'IDR'),
            totalAmount: const Money(amount: '0', currencyCode: 'IDR'),
            totalTaxAmount: const Money(amount: '0', currencyCode: 'IDR'),
          ),
          lines: [],
          totalQuantity: 0,
        );
        fakeService.setCart(testCart);

        // Act & Assert
        expect(provider.isLoading, isFalse);

        final future = provider.fetchCart();
        expect(provider.isLoading, isTrue);

        await future;
        expect(provider.isLoading, isFalse);
      });
    });

    group('addToCart', () {
      test('should add item to cart successfully', () async {
        // Act
        final result = await provider.addToCart('variant-1', 2);

        // Assert
        expect(result, isTrue);
        expect(provider.error, isNull);
        expect(provider.cart, isNotNull);
        expect(provider.cart!.lines.length, equals(1));
        expect(provider.itemCount, equals(2));
      });

      test('should handle add error', () async {
        // Arrange
        fakeService.setError(message: 'Out of stock');

        // Act
        final result = await provider.addToCart('variant-1', 1);

        // Assert
        expect(result, isFalse);
        expect(provider.error, equals('Out of stock (Status: 500)'));
      });
    });

    group('updateQuantity', () {
      test('should update quantity successfully', () async {
        // Arrange - add item first
        await provider.addToCart('variant-1', 1);
        fakeService.clearError();

        // Act
        final result = await provider.updateQuantity('item_0', 5);

        // Assert
        expect(result, isTrue);
        expect(provider.error, isNull);
      });

      test('should remove item when quantity is 0 or less', () async {
        // Arrange - add item first
        await provider.addToCart('variant-1', 1);
        fakeService.clearError();

        // Act
        final result = await provider.updateQuantity('item_0', 0);

        // Assert
        expect(result, isTrue);
      });
    });

    group('removeItem', () {
      test('should remove item successfully', () async {
        // Arrange - add item first
        await provider.addToCart('variant-1', 1);
        fakeService.clearError();

        // Act
        final result = await provider.removeItem('item_0');

        // Assert
        expect(result, isTrue);
        expect(provider.error, isNull);
      });

      test('should handle remove error', () async {
        // Arrange
        fakeService.setError(message: 'Item not found');

        // Act
        final result = await provider.removeItem('item-999');

        // Assert
        expect(result, isFalse);
        expect(provider.error, equals('Item not found (Status: 500)'));
      });
    });

    group('clearCart', () {
      test('should clear cart successfully', () async {
        // Arrange - add item first
        await provider.addToCart('variant-1', 1);
        fakeService.clearError();

        // Act
        await provider.clearCart();

        // Assert
        expect(provider.cart, isNull);
        expect(provider.error, isNull);
        expect(provider.itemCount, equals(0));
      });

      test('should handle clear error', () async {
        // Arrange
        fakeService.setError(message: 'Cannot clear cart');

        // Act
        await provider.clearCart();

        // Assert
        expect(provider.error, equals('Cannot clear cart (Status: 500)'));
      });
    });
  });
}
