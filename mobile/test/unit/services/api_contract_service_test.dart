import 'package:flutter_test/flutter_test.dart';
import 'package:mocktail/mocktail.dart';

import 'package:mobile/services/address_service.dart';
import 'package:mobile/services/api_service.dart';
import 'package:mobile/services/auth_service.dart';
import 'package:mobile/services/cart_service.dart';
import 'package:mobile/services/collection_service.dart';
import 'package:mobile/services/content_service.dart';
import 'package:mobile/services/order_service.dart';
import 'package:mobile/services/product_service.dart';
import 'package:mobile/services/profile_service.dart';
import 'package:mobile/services/wishlist_service.dart';

class _MockApiService extends Mock implements ApiService {}

void main() {
  group('mobile API contract services', () {
    late _MockApiService api;

    setUp(() {
      api = _MockApiService();
    });

    test('ContentService unwraps landing page data payload', () async {
      when(() => api.get('/landing-page')).thenAnswer(
        (_) async => {
          'data': {
            'heroSlides': [
              {
                'title': 'Hero',
                'subtitle': 'Subtitle',
                'image_url': 'https://example.com/hero.jpg',
                'cta_text': 'Belanja',
                'cta_link': '/shop',
              },
            ],
            'features': <Map<String, dynamic>>[],
            'testimonials': <Map<String, dynamic>>[],
            'materials': <Map<String, dynamic>>[],
          },
        },
      );

      final service = ContentService(api: api);
      final result = await service.getLandingPage();

      expect(result.heroSlides, hasLength(1));
      expect(result.heroSlides.first.title, 'Hero');
      expect(result.heroSlides.first.target, '/shop');
    });

    test('ProductService unwraps product detail payload', () async {
      when(() => api.get('/products/jaket-mitologi')).thenAnswer(
        (_) async => {
          'data': {
            'id': '10',
            'handle': 'jaket-mitologi',
            'availableForSale': true,
            'title': 'Jaket Mitologi',
            'description': 'Desc',
            'descriptionHtml': '<p>Desc</p>',
            'options': <Map<String, dynamic>>[],
            'priceRange': {
              'minVariantPrice': {'amount': '100000', 'currencyCode': 'IDR'},
              'maxVariantPrice': {'amount': '100000', 'currencyCode': 'IDR'},
            },
            'variants': <Map<String, dynamic>>[],
            'featuredImage': {
              'url': 'https://example.com/a.jpg',
              'altText': 'A',
              'width': 100,
              'height': 100,
            },
            'images': <Map<String, dynamic>>[],
            'seo': {'title': 'SEO', 'description': 'SEO'},
            'tags': ['new_arrival'],
            'updatedAt': '2026-01-01T00:00:00Z',
          },
        },
      );

      final service = ProductService(api: api);
      final result = await service.getProductDetail('jaket-mitologi');

      expect(result.handle, 'jaket-mitologi');
      expect(result.title, 'Jaket Mitologi');
    });

    test('ProfileService unwraps profile payload', () async {
      when(() => api.get('/profile', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': {
            'id': 7,
            'name': 'Rifqy',
            'email': 'rifqy@example.com',
            'avatarUrl': 'https://example.com/avatar.png',
          },
        },
      );

      final service = ProfileService(api: api);
      final result = await service.getProfile();

      expect(result.id, 7);
      expect(result.avatarUrl, 'https://example.com/avatar.png');
    });

    test('AddressService reads wrapped address collection payload', () async {
      when(() => api.get('/profile/addresses', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': [
            {
              'id': 11,
              'label': 'Rumah',
              'recipientName': 'Rifqy',
              'phone': '08123',
              'addressLine1': 'Jl. Mitologi',
              'city': 'Bandung',
              'province': 'Jawa Barat',
              'postalCode': '40123',
              'country': 'Indonesia',
              'isPrimary': true,
            },
          ],
        },
      );

      final service = AddressService(api: api);
      final result = await service.getAddresses();

      expect(result, hasLength(1));
      expect(result.first.recipientName, 'Rifqy');
      expect(result.first.isPrimary, isTrue);
    });

    test('ContentService reads wrapped menu payload', () async {
      when(() => api.get('/menus/footer')).thenAnswer(
        (_) async => {
          'data': [
            {'title': 'Tentang Kami', 'path': '/tentang-kami'},
          ],
        },
      );

      final service = ContentService(api: api);
      final result = await service.getMenu('footer');

      expect(result, hasLength(1));
      expect(result.first.path, '/tentang-kami');
    });

    test('ProfileService reads wrapped avatar URL payload', () async {
      when(
        () => api.multipartPost(
          '/profile/avatar',
          filePath: '/tmp/avatar.png',
          fileField: 'avatar',
          requiresAuth: true,
        ),
      ).thenAnswer(
        (_) async => {
          'data': {'avatarUrl': 'https://example.com/avatar.png'},
        },
      );

      final service = ProfileService(api: api);
      final result = await service.updateAvatar('/tmp/avatar.png');

      expect(result, 'https://example.com/avatar.png');
    });

    test(
      'AuthService sends snake_case cart session payload on login',
      () async {
        when(
          () => api.post(
            '/auth/login',
            body: {
              'email': 'rifqy@example.com',
              'password': 'secret123',
              'cart_session_id': 'guest-cart-1',
            },
          ),
        ).thenAnswer(
          (_) async => {
            'data': {
              'token': 'token-123',
              'user': {'id': 1, 'name': 'Rifqy', 'email': 'rifqy@example.com'},
              'cartId': 'merged-cart-1',
            },
          },
        );

        final service = AuthService(api: api);
        final result = await service.login(
          'rifqy@example.com',
          'secret123',
          cartSessionId: 'guest-cart-1',
        );

        expect(result.cartId, 'merged-cart-1');
        verify(
          () => api.post(
            '/auth/login',
            body: {
              'email': 'rifqy@example.com',
              'password': 'secret123',
              'cart_session_id': 'guest-cart-1',
            },
          ),
        ).called(1);
      },
    );

    test('AuthService unwraps auth user payload from /auth/user', () async {
      when(() => api.get('/auth/user', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': {'id': 5, 'name': 'Rifqy', 'email': 'rifqy@example.com'},
        },
      );

      final service = AuthService(api: api);
      final result = await service.getUser();

      expect(result.id, 5);
      expect(result.name, 'Rifqy');
    });

    test('CartService unwraps wrapped cart payload', () async {
      when(() => api.get('/cart', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': {
            'id': '12',
            'sessionId': 'session-12',
            'checkoutUrl': '/checkout',
            'cost': {
              'subtotalAmount': {'amount': '100000', 'currencyCode': 'IDR'},
              'totalAmount': {'amount': '100000', 'currencyCode': 'IDR'},
              'totalTaxAmount': {'amount': '0', 'currencyCode': 'IDR'},
            },
            'lines': [
              {
                'id': '1',
                'quantity': 2,
                'cost': {
                  'totalAmount': {'amount': '100000', 'currencyCode': 'IDR'},
                },
                'merchandise': {
                  'id': 'variant-1',
                  'title': 'Navy / L',
                  'product': {
                    'title': 'Jersey Mitologi',
                    'featuredImage': {'url': 'https://example.com/p.jpg'},
                  },
                },
              },
            ],
            'totalQuantity': 2,
          },
        },
      );

      final service = CartService(api: api, persistCartSessionId: (_) async {});
      final result = await service.getCart();

      expect(result.id, '12');
      expect(result.lines, hasLength(1));
      expect(result.lines.first.merchandiseId, 'variant-1');
      expect(result.lines.first.title, 'Jersey Mitologi');
      expect(result.lines.first.variantTitle, 'Navy / L');
    });

    test('OrderService unwraps wrapped orders payload', () async {
      when(() => api.get('/orders', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': {
            'orders': [
              {
                'id': 7,
                'orderNumber': 'ORD-7',
                'status': 'pending',
                'total': 150000,
                'itemsCount': 2,
                'createdAt': '2026-01-01T00:00:00Z',
              },
            ],
            'pagination': {
              'total': 1,
              'perPage': 10,
              'currentPage': 1,
              'lastPage': 1,
            },
          },
        },
      );

      final service = OrderService(api: api);
      final result = await service.getOrders();

      expect(result, hasLength(1));
      expect(result.first.orderNumber, 'ORD-7');
    });

    test('OrderService unwraps wrapped order detail payload', () async {
      when(() => api.get('/orders/ORD-9', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': {
            'id': 9,
            'orderNumber': 'ORD-9',
            'status': 'processing',
            'subtotal': 100000,
            'shippingCost': 0,
            'total': 100000,
            'createdAt': '2026-01-01T00:00:00Z',
            'items': [
              {
                'id': 1,
                'productTitle': 'Jersey Mitologi',
                'variantTitle': 'L',
                'price': 100000,
                'quantity': 1,
                'total': 100000,
              },
            ],
            'shippingAddress': {
              'name': 'Rifqy',
              'phone': '08123',
              'address': 'Jl. Mitologi',
              'city': 'Bandung',
              'province': 'Jawa Barat',
              'postalCode': '40123',
            },
          },
        },
      );

      final service = OrderService(api: api);
      final result = await service.getOrderDetail('ORD-9');

      expect(result.orderNumber, 'ORD-9');
      expect(result.items, hasLength(1));
      expect(result.items.first.productTitle, 'Jersey Mitologi');
    });

    test('OrderService unwraps checkout payment payload', () async {
      when(
        () =>
            api.post('/checkout', body: any(named: 'body'), requiresAuth: true),
      ).thenAnswer(
        (_) async => {
          'data': {
            'orderNumber': 'ORD-11',
            'snapToken': 'snap-11',
            'redirectUrl': '/shop/account/orders/ORD-11',
          },
        },
      );

      final service = OrderService(api: api);
      final result = await service.createCheckout({'shipping_name': 'Rifqy'});

      expect(result['orderNumber'], 'ORD-11');
      expect(result['snapToken'], 'snap-11');
    });

    test('WishlistService unwraps wrapped wishlist products payload', () async {
      when(() => api.get('/wishlist', requiresAuth: true)).thenAnswer(
        (_) async => {
          'data': [
            {
              'id': '1',
              'handle': 'jersey-mitologi',
              'availableForSale': true,
              'title': 'Jersey Mitologi',
              'description': 'Desc',
              'descriptionHtml': '<p>Desc</p>',
              'options': <Map<String, dynamic>>[],
              'priceRange': {
                'minVariantPrice': {'amount': '100000', 'currencyCode': 'IDR'},
                'maxVariantPrice': {'amount': '100000', 'currencyCode': 'IDR'},
              },
              'variants': <Map<String, dynamic>>[],
              'featuredImage': {'url': 'https://example.com/p.jpg'},
              'images': <Map<String, dynamic>>[],
              'seo': {'title': 'SEO', 'description': 'SEO'},
              'tags': <String>[],
              'updatedAt': '2026-01-01T00:00:00Z',
              'isWishlisted': true,
            },
          ],
        },
      );

      final service = WishlistService(api: api);
      final result = await service.getWishlist();

      expect(result, hasLength(1));
      expect(result.first.isWishlisted, isTrue);
    });

    test(
      'CollectionService unwraps wrapped collection detail payload',
      () async {
        when(() => api.get('/collections/football')).thenAnswer(
          (_) async => {
            'data': {
              'handle': 'football',
              'title': 'Football',
              'description': 'Football collection',
              'seo': {
                'title': 'Football',
                'description': 'Football collection',
              },
              'path': '/search/football',
              'updatedAt': '2026-01-01T00:00:00Z',
            },
          },
        );

        final service = CollectionService(api: api);
        final result = await service.getCollection('football');

        expect(result.handle, 'football');
        expect(result.path, '/search/football');
      },
    );

    test(
      'ProductService maps interaction payload to backend contract',
      () async {
        when(
          () => api.post(
            '/interactions/batch',
            body: {
              'interactions': [
                {'productId': 12, 'action': 'cart', 'score': 3},
                {'productId': 14, 'action': 'view', 'score': 1},
              ],
            },
            requiresAuth: true,
          ),
        ).thenAnswer(
          (_) async => {
            'data': {'count': 2},
          },
        );

        final service = ProductService(api: api);
        final result = await service.sendInteractions([
          {'product_id': '12', 'action': 'add_to_cart'},
          {'product_id': '14', 'action': 'view'},
        ], isAuthenticated: true);

        expect(result, isTrue);
        verify(
          () => api.post(
            '/interactions/batch',
            body: {
              'interactions': [
                {'productId': 12, 'action': 'cart', 'score': 3},
                {'productId': 14, 'action': 'view', 'score': 1},
              ],
            },
            requiresAuth: true,
          ),
        ).called(1);
      },
    );
  });
}
