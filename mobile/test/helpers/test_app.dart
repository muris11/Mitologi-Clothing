import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'package:mobile/config/theme.dart';
import 'package:mobile/providers/auth_provider.dart';
import 'package:mobile/providers/cart_provider.dart';
import 'package:mobile/providers/chatbot_provider.dart';
import 'package:mobile/providers/collection_provider.dart';
import 'package:mobile/providers/content_provider.dart';
import 'package:mobile/providers/order_provider.dart';
import 'package:mobile/providers/product_provider.dart';
import 'package:mobile/providers/profile_provider.dart';
import 'package:mobile/providers/wishlist_provider.dart';

import 'provider_overrides.dart';

Widget buildTestApp({
  required Widget child,
  AuthProvider? authProvider,
  ProductProvider? productProvider,
  CartProvider? cartProvider,
  WishlistProvider? wishlistProvider,
  OrderProvider? orderProvider,
  ContentProvider? contentProvider,
  ProfileProvider? profileProvider,
  ChatbotProvider? chatbotProvider,
  CollectionProvider? collectionProvider,
}) {
  return MultiProvider(
    providers: [
      ChangeNotifierProvider<AuthProvider>.value(
          value: authProvider ?? FakeAuthProvider()),
      ChangeNotifierProvider<ProductProvider>.value(
          value: productProvider ?? FakeProductProvider()),
      ChangeNotifierProvider<CartProvider>.value(
          value: cartProvider ?? FakeCartProvider()),
      ChangeNotifierProvider<WishlistProvider>.value(
        value: wishlistProvider ?? FakeWishlistProvider(),
      ),
      ChangeNotifierProvider<OrderProvider>.value(
          value: orderProvider ?? FakeOrderProvider()),
      ChangeNotifierProvider<ContentProvider>.value(
        value: contentProvider ?? FakeContentProvider(),
      ),
      ChangeNotifierProvider<ProfileProvider>.value(
        value: profileProvider ?? FakeProfileProvider(),
      ),
      ChangeNotifierProvider<ChatbotProvider>.value(
        value: chatbotProvider ?? FakeChatbotProvider(),
      ),
      ChangeNotifierProvider<CollectionProvider>.value(
        value: collectionProvider ?? FakeCollectionProvider(),
      ),
    ],
    child: MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: child,
    ),
  );
}
