import 'package:mobile/models/address.dart';
import 'package:mobile/models/cart.dart';
import 'package:mobile/models/cart_item.dart';
import 'package:mobile/models/category.dart';
import 'package:mobile/models/collection.dart';
import 'package:mobile/models/image_model.dart';
import 'package:mobile/models/money.dart';
import 'package:mobile/models/order.dart';
import 'package:mobile/models/order_item.dart';
import 'package:mobile/models/price_range.dart';
import 'package:mobile/models/product.dart';
import 'package:mobile/models/product_option.dart';
import 'package:mobile/models/product_variant.dart';
import 'package:mobile/models/seo.dart';
import 'package:mobile/models/user.dart';
import 'package:mobile/providers/auth_provider.dart';
import 'package:mobile/providers/cart_provider.dart';
import 'package:mobile/providers/chatbot_provider.dart';
import 'package:mobile/providers/collection_provider.dart';
import 'package:mobile/providers/content_provider.dart';
import 'package:mobile/providers/order_provider.dart';
import 'package:mobile/providers/product_provider.dart';
import 'package:mobile/providers/profile_provider.dart';
import 'package:mobile/providers/wishlist_provider.dart';

class FakeAuthProvider extends AuthProvider {
  FakeAuthProvider({
    this.seededUser,
    this.forgotPasswordResult = true,
    this.forgotPasswordError,
  });

  final User? seededUser;
  final bool forgotPasswordResult;
  final String? forgotPasswordError;

  @override
  User? get user => seededUser;

  @override
  bool get isAuthenticated => seededUser != null;

  @override
  Future<void> loadUserFromStorage() async {}

  @override
  String? get error => forgotPasswordError;

  @override
  Future<bool> forgotPassword(String email) async {
    return forgotPasswordResult;
  }
}

class FakeCartProvider extends CartProvider {
  FakeCartProvider({Cart? cart, this.seededCount = 0}) : _cart = cart;

  final Cart? _cart;
  final int seededCount;

  @override
  Cart? get cart => _cart;

  @override
  int get itemCount => _cart?.totalQuantity ?? seededCount;

  @override
  Future<void> fetchCart() async {}
}

class FakeWishlistProvider extends WishlistProvider {
  FakeWishlistProvider({List<Product>? items}) : _items = items ?? [];

  final List<Product> _items;

  @override
  List<Product> get items => _items;

  @override
  int get itemCount => _items.length;

  @override
  Future<void> fetchWishlist() async {}
}

class FakeProductProvider extends ProductProvider {
  FakeProductProvider({List<Product>? products, List<Category>? categories})
    : _products = products ?? [buildTestProduct()],
      _categories = categories ?? [buildTestCategory()];

  final List<Product> _products;
  final List<Category> _categories;

  @override
  List<Product> get products => _products;

  @override
  List<Category> get categories => _categories;

  @override
  bool get isLoading => false;

  @override
  String? get error => null;

  @override
  String? get selectedCategory => null;

  @override
  String get sortKey => 'latest';

  @override
  Future<void> fetchCategories() async {}

  @override
  Future<void> fetchProducts({bool refresh = false}) async {}

  @override
  Future<void> loadMore() async {}
}

class FakeOrderProvider extends OrderProvider {
  FakeOrderProvider({List<Order>? orders, Order? currentOrder})
    : _orders = orders ?? [buildTestOrder()],
      _currentOrder = currentOrder;

  final List<Order> _orders;
  final Order? _currentOrder;

  @override
  List<Order> get orders => _orders;

  @override
  Order? get currentOrder =>
      _currentOrder ?? (_orders.isNotEmpty ? _orders.first : null);

  @override
  bool get isLoading => false;

  @override
  String? get error => null;

  @override
  Future<void> fetchOrders() async {}
}

class FakeContentProvider extends ContentProvider {
  @override
  Future<void> fetchLandingPage() async {}

  @override
  Future<void> fetchPage(String slug) async {}
}

class FakeProfileProvider extends ProfileProvider {}

class FakeChatbotProvider extends ChatbotProvider {}

class FakeCollectionProvider extends CollectionProvider {
  FakeCollectionProvider({
    List<Collection>? collections,
    Collection? currentCollection,
    List<Product>? currentProducts,
  }) : _collections = collections ?? [buildTestCollection()],
       _currentCollection = currentCollection,
       _currentProducts = currentProducts ?? [buildTestProduct()];

  final List<Collection> _collections;
  final Collection? _currentCollection;
  final List<Product> _currentProducts;

  @override
  List<Collection> get collections => _collections;

  @override
  bool get isLoadingCollections => false;

  @override
  String? get collectionsError => null;

  @override
  Collection? get currentCollection => _currentCollection ?? _collections.first;

  @override
  List<Product> get currentCollectionProducts => _currentProducts;

  @override
  bool get isLoadingCollectionDetails => false;

  @override
  String? get collectionDetailsError => null;

  @override
  Future<void> fetchCollections() async {}

  @override
  Future<void> fetchCollectionDetails(
    String handle, {
    String sortKey = 'RELEVANCE',
    bool reverse = false,
  }) async {}
}

User buildTestUser() {
  return User(
    id: 1,
    name: 'Mitologi Tester',
    email: 'tester@mitologi.test',
    avatarUrl: null,
    phone: null,
    address: null,
    city: null,
    province: null,
    postalCode: null,
  );
}

Category buildTestCategory() {
  return Category(
    id: 1,
    name: 'Outerwear',
    slug: 'outerwear',
    handle: 'outerwear',
  );
}

Product buildTestProduct() {
  return Product(
    id: 'prod-1',
    handle: 'jaket-mitologi',
    availableForSale: true,
    title: 'Jaket Mitologi Signature',
    description: 'Produk uji untuk widget test.',
    descriptionHtml: '<p>Produk uji untuk widget test.</p>',
    options: [
      ProductOption(id: 'opt-1', name: 'Size', values: ['M']),
    ],
    priceRange: PriceRange(
      maxVariantPrice: Money(amount: '349000', currencyCode: 'IDR'),
      minVariantPrice: Money(amount: '349000', currencyCode: 'IDR'),
    ),
    variants: [
      ProductVariant(
        id: 'var-1',
        title: 'M',
        availableForSale: true,
        selectedOptions: [SelectedOption(name: 'Size', value: 'M')],
        price: Money(amount: '349000', currencyCode: 'IDR'),
        stock: 10,
      ),
    ],
    featuredImage: ImageModel(
      url: 'https://example.com/product.jpg',
      altText: 'Jaket Mitologi Signature',
      width: 1200,
      height: 1200,
    ),
    images: [
      ImageModel(
        url: 'https://example.com/product.jpg',
        altText: 'Jaket Mitologi Signature',
        width: 1200,
        height: 1200,
      ),
    ],
    seo: SEO(title: 'Jaket Mitologi Signature', description: 'Produk test'),
    tags: const ['best_seller'],
    totalStock: 10,
    updatedAt: DateTime(2026, 3, 17).toIso8601String(),
    averageRating: 4.9,
    totalReviews: 12,
    totalSold: 125,
  );
}

Collection buildTestCollection() {
  return Collection(
    handle: 'outerwear',
    title: 'Outerwear',
    description: 'Koleksi outerwear premium dari Mitologi.',
    seo: SEO(title: 'Outerwear', description: 'Koleksi outerwear premium'),
    path: '/collections/outerwear',
    updatedAt: DateTime(2026, 3, 17).toIso8601String(),
  );
}

Cart buildTestCart() {
  return Cart(
    id: 'cart-1',
    checkoutUrl: 'https://example.com/checkout',
    cost: CartCost(
      subtotalAmount: Money(amount: '349000', currencyCode: 'IDR'),
      totalAmount: Money(amount: '369000', currencyCode: 'IDR'),
      totalTaxAmount: Money(amount: '0', currencyCode: 'IDR'),
    ),
    totalQuantity: 1,
    lines: [
      CartItem(
        id: 'line-1',
        merchandiseId: 'merch-1',
        title: 'Jaket Mitologi Signature',
        quantity: 1,
        price: Money(amount: '349000', currencyCode: 'IDR'),
        imageUrl: 'https://example.com/product.jpg',
        variantTitle: 'Size: M',
      ),
    ],
  );
}

Order buildTestOrder() {
  return Order(
    id: 1,
    orderNumber: 'INV-2026-0001',
    status: 'COMPLETED',
    paymentStatus: 'PAID',
    total: 369000,
    subtotal: 349000,
    shippingCost: 20000,
    shippingAddress: Address(
      id: 1,
      label: 'Rumah',
      recipientName: 'Mitologi Tester',
      phone: '08123456789',
      addressLine1: 'Jl. Mitologi No. 1',
      city: 'Bandung',
      province: 'Jawa Barat',
      postalCode: '40123',
      country: 'Indonesia',
      isPrimary: true,
    ),
    itemsCount: 1,
    createdAt: DateTime(2026, 3, 17).toIso8601String(),
    items: [
      OrderItem(
        id: 1,
        productTitle: 'Jaket Mitologi Signature',
        variantTitle: 'M',
        price: 349000,
        quantity: 1,
        total: 349000,
        productHandle: 'jaket-mitologi',
        productImage: 'https://example.com/product.jpg',
      ),
    ],
  );
}
