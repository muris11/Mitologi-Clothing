import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/models/address.dart';
import 'package:mobile/models/cart.dart';
import 'package:mobile/models/cart_item.dart';
import 'package:mobile/models/material.dart' as material_model;
import 'package:mobile/models/order.dart';
import 'package:mobile/models/order_item.dart';
import 'package:mobile/models/order_step.dart';
import 'package:mobile/models/pagination.dart';
import 'package:mobile/models/review.dart';
import 'package:mobile/models/tentang_kami/site_settings_model.dart';

void main() {
  group('mobile API contract models', () {
    test('Pagination parses backend camelCase fields', () {
      final pagination = Pagination.fromJson({
        'currentPage': 2,
        'lastPage': 4,
        'total': 20,
        'perPage': 5,
      });

      expect(pagination.currentPage, 2);
      expect(pagination.lastPage, 4);
      expect(pagination.perPage, 5);
    });

    test('Order parses backend camelCase fields', () {
      final order = Order.fromJson({
        'id': 1,
        'orderNumber': 'ORD-1',
        'status': 'pending',
        'total': 125000,
        'subtotal': 100000,
        'shippingCost': 25000,
        'itemsCount': 1,
        'createdAt': '2026-01-01T00:00:00Z',
        'items': <Map<String, dynamic>>[],
        'shippingAddress': {
          'id': 5,
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
      });

      expect(order.orderNumber, 'ORD-1');
      expect(order.shippingCost, 25000);
      expect(order.shippingAddress?.recipientName, 'Rifqy');
      expect(order.createdAt, '2026-01-01T00:00:00Z');
    });

    test('Review parses backend camelCase fields', () {
      final review = ReviewItem.fromJson({
        'id': 9,
        'userId': 3,
        'userName': 'Rifqy',
        'userAvatar': 'https://example.com/avatar.png',
        'rating': 5,
        'comment': 'Mantap',
        'createdAt': '2026-01-01T00:00:00Z',
      });

      expect(review.userId, 3);
      expect(review.userName, 'Rifqy');
      expect(review.createdAt, '2026-01-01T00:00:00Z');
    });

    test('Material parses backend camelCase fields', () {
      final material = material_model.Material.fromJson({
        'id': 2,
        'name': 'Cotton Combed',
        'description': 'Premium',
        'sortOrder': 3,
        'createdAt': '2026-01-01T00:00:00Z',
        'updatedAt': '2026-01-02T00:00:00Z',
      });

      expect(material.sortOrder, 3);
      expect(material.createdAt?.year, 2026);
    });

    test('OrderStep parses backend camelCase fields', () {
      final step = OrderStep.fromJson({
        'id': 2,
        'title': 'Konsultasi',
        'description': 'Diskusi kebutuhan',
        'sortOrder': 1,
        'type': 'langsung',
        'createdAt': '2026-01-01T00:00:00Z',
      });

      expect(step.sortOrder, 1);
      expect(step.type, 'langsung');
      expect(step.createdAt?.year, 2026);
    });

    test('SiteSettings parses flat camelCase backend payload', () {
      final settings = SiteSettings.fromJson({
        'siteName': 'Mitologi',
        'siteTagline': 'Premium',
        'aboutHeadline': 'Tentang Kami',
        'aboutImage': 'https://example.com/about.jpg',
        'founderName': 'Founder',
        'founderPhoto': 'https://example.com/founder.jpg',
        'servicesData': [
          {
            'title': 'Custom Jersey',
            'desc': 'Cepat',
            'image': 'https://example.com/service.jpg',
          },
        ],
        'companyValuesData': [
          {'title': 'Kualitas', 'description': 'Utama'},
        ],
        'whatsappNumber': '62812',
      });

      expect(settings.siteName, 'Mitologi');
      expect(settings.aboutHeadline, 'Tentang Kami');
      expect(settings.founderName, 'Founder');
      expect(settings.servicesData, hasLength(1));
      expect(settings.companyValues, hasLength(1));
      expect(settings.whatsappNumber, '62812');
    });

    test('Address parses camelCase primary flag', () {
      final address = Address.fromJson({
        'id': 3,
        'label': 'Kantor',
        'recipientName': 'Rifqy',
        'phone': '08123',
        'addressLine1': 'Jl. Kantor',
        'city': 'Bandung',
        'province': 'Jawa Barat',
        'postalCode': '40123',
        'country': 'Indonesia',
        'isPrimary': true,
      });

      expect(address.isPrimary, isTrue);
      expect(address.addressLine1, 'Jl. Kantor');
    });

    test('CartItem parses nested backend cart line payload', () {
      final item = CartItem.fromJson({
        'id': 'line-1',
        'quantity': 2,
        'cost': {
          'totalAmount': {'amount': '200000', 'currencyCode': 'IDR'},
        },
        'merchandise': {
          'id': 'variant-1',
          'title': 'Navy / XL',
          'product': {
            'title': 'Jersey Mitologi',
            'featuredImage': {'url': 'https://example.com/j.jpg'},
          },
        },
      });

      expect(item.merchandiseId, 'variant-1');
      expect(item.title, 'Jersey Mitologi');
      expect(item.variantTitle, 'Navy / XL');
      expect(item.imageUrl, 'https://example.com/j.jpg');
      expect(item.price.amount, '200000');
    });

    test('Cart parses wrapped backend structure once unwrapped', () {
      final cart = Cart.fromJson({
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
            'id': 'line-1',
            'quantity': 1,
            'cost': {
              'totalAmount': {'amount': '100000', 'currencyCode': 'IDR'},
            },
            'merchandise': {
              'id': 'variant-1',
              'title': 'L',
              'product': {
                'title': 'Jersey Mitologi',
                'featuredImage': {'url': 'https://example.com/j.jpg'},
              },
            },
          },
        ],
        'totalQuantity': 1,
      });

      expect(cart.lines.first.title, 'Jersey Mitologi');
    });

    test('OrderItem parses backend camelCase detail payload', () {
      final item = OrderItem.fromJson({
        'id': 1,
        'productTitle': 'Jersey Mitologi',
        'variantTitle': 'L',
        'price': 100000,
        'quantity': 1,
        'total': 100000,
        'productHandle': 'jersey-mitologi',
        'productImage': 'https://example.com/j.jpg',
      });

      expect(item.productTitle, 'Jersey Mitologi');
      expect(item.productHandle, 'jersey-mitologi');
      expect(item.productImage, 'https://example.com/j.jpg');
    });
  });
}
