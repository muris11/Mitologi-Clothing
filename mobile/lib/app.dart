import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import 'config/theme.dart';
import 'widgets/common/app_bottom_nav.dart';
import 'screens/auth/login_screen.dart';
import 'screens/auth/register_screen.dart';
import 'screens/auth/forgot_password_screen.dart';
import 'screens/auth/reset_password_screen.dart';
import 'screens/splash/splash_screen.dart';
import 'screens/splash/onboarding_screen.dart';
import 'screens/home/home_screen.dart';
import 'screens/shop/shop_screen.dart';
import 'screens/shop/product_detail_screen.dart';
import 'screens/cart/cart_screen.dart';
import 'screens/checkout/checkout_screen.dart';
import 'screens/checkout/order_success_screen.dart';
import 'screens/checkout/payment_webview_screen.dart';
import 'screens/account/account_screen.dart';
import 'screens/account/edit_profile_screen.dart';
import 'screens/account/change_password_screen.dart';
import 'screens/account/address_list_screen.dart';
import 'screens/order/order_list_screen.dart';
import 'screens/order/order_detail_screen.dart';
import 'screens/help/chatbot_screen.dart';
import 'screens/collection/collection_detail_screen.dart';
import 'screens/content/cms_page_screen.dart';
import 'screens/tentang_kami/tentang_kami_screen.dart';
import 'screens/wishlist/wishlist_screen.dart';
import 'screens/help/faq_screen.dart';
import 'screens/shop/panduan_ukuran_screen.dart';
import 'screens/content/kebijakan_pengembalian_screen.dart';
import 'screens/content/kebijakan_privasi_screen.dart';
import 'screens/content/syarat_ketentuan_screen.dart';
import 'screens/notification/notification_screen.dart';
import 'screens/shop/promo_screen.dart';

final GlobalKey<NavigatorState> _rootNavigatorKey = GlobalKey<NavigatorState>(
  debugLabel: 'root',
);

final _router = GoRouter(
  navigatorKey: _rootNavigatorKey,
  initialLocation: '/splash',
  routes: [
    // ═══ SPLASH & ONBOARDING (No Shell) ═══
    GoRoute(path: '/splash', builder: (context, state) => const SplashScreen()),
    GoRoute(
      path: '/onboarding',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const OnboardingScreen(),
    ),

    // ═══ FULL-SCREEN OVERLAYS (No Shell) ═══
    GoRoute(
      path: '/notifications',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const NotificationScreen(),
    ),
    GoRoute(
      path: '/chatbot',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) {
        final productId = state.uri.queryParameters['product_id'];
        return ChatbotScreen(productId: productId);
      },
    ),
    GoRoute(
      path: '/payment/:orderNumber',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) {
        final orderNumber = state.pathParameters['orderNumber']!;
        final url = state.uri.queryParameters['url'];
        if (url == null || url.isEmpty) {
          return Scaffold(
            appBar: AppBar(title: const Text('Pembayaran')),
            body: Center(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(
                      Icons.error_outline,
                      size: 48,
                      color: AppTheme.error,
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Link pembayaran tidak valid.',
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: () =>
                          context.go('/shop/account/orders/$orderNumber'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primary,
                        foregroundColor: Colors.white,
                      ),
                      child: const Text('Kembali ke Pesanan'),
                    ),
                  ],
                ),
              ),
            ),
          );
        }
        return PaymentWebViewScreen(orderNumber: orderNumber, snapUrl: url);
      },
    ),
    GoRoute(
      path: '/page/:slug',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) {
        final slug = state.pathParameters['slug']!;
        return CMSPageScreen(slug: slug);
      },
    ),

    // ═══ MAIN APP SHELL (Bottom Navigation) ═══
    StatefulShellRoute.indexedStack(
      builder: (context, state, navigationShell) {
        return ScaffoldWithNavBar(navigationShell: navigationShell);
      },
      branches: [
        // Branch 0: Home
        StatefulShellBranch(
          routes: [
            GoRoute(path: '/', builder: (context, state) => const HomeScreen()),
          ],
        ),
        // Branch 1: Shop (with all nested routes)
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/shop',
              builder: (context, state) => const ShopScreen(),
              routes: [
                // Nested routes under /shop (these inherit the shell)
                GoRoute(
                  path: 'product/:handle',
                  parentNavigatorKey: _rootNavigatorKey, // Full screen overlay
                  pageBuilder: (context, state) => CustomTransitionPage(
                    key: state.pageKey,
                    child: ProductDetailScreen(
                      handle: state.pathParameters['handle']!,
                    ),
                    transitionsBuilder:
                        (context, animation, secondaryAnimation, child) {
                          return FadeTransition(
                            opacity: animation,
                            child: SlideTransition(
                              position:
                                  Tween<Offset>(
                                    begin: const Offset(0, 0.05),
                                    end: Offset.zero,
                                  ).animate(
                                    CurvedAnimation(
                                      parent: animation,
                                      curve: Curves.easeOut,
                                    ),
                                  ),
                              child: child,
                            ),
                          );
                        },
                  ),
                ),
                GoRoute(
                  path: ':handle', // Collection detail
                  builder: (context, state) => CollectionDetailScreen(
                    handle: state.pathParameters['handle']!,
                  ),
                ),
              ],
            ),
          ],
        ),
        // Branch 2: Cart
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/cart',
              builder: (context, state) => const CartScreen(),
            ),
          ],
        ),
        // Branch 3: Wishlist
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/wishlist',
              builder: (context, state) => const WishlistScreen(),
            ),
          ],
        ),
        // Branch 4: Account (redirects to /shop/account)
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/account',
              redirect: (context, state) => '/shop/account',
            ),
          ],
        ),
      ],
    ),

    // ═══ AUTH ROUTES (Full Screen - No Shell) ═══
    GoRoute(
      path: '/shop/login',
      parentNavigatorKey: _rootNavigatorKey,
      pageBuilder: (context, state) => CustomTransitionPage(
        key: state.pageKey,
        child: const LoginScreen(),
        transitionsBuilder: (context, animation, secondaryAnimation, child) {
          return FadeTransition(
            opacity: animation,
            child: SlideTransition(
              position:
                  Tween<Offset>(
                    begin: const Offset(0, 0.1),
                    end: Offset.zero,
                  ).animate(
                    CurvedAnimation(parent: animation, curve: Curves.easeOut),
                  ),
              child: child,
            ),
          );
        },
      ),
    ),
    GoRoute(
      path: '/shop/register',
      parentNavigatorKey: _rootNavigatorKey,
      pageBuilder: (context, state) => CustomTransitionPage(
        key: state.pageKey,
        child: const RegisterScreen(),
        transitionsBuilder: (context, animation, secondaryAnimation, child) {
          return FadeTransition(
            opacity: animation,
            child: SlideTransition(
              position:
                  Tween<Offset>(
                    begin: const Offset(0.1, 0),
                    end: Offset.zero,
                  ).animate(
                    CurvedAnimation(parent: animation, curve: Curves.easeOut),
                  ),
              child: child,
            ),
          );
        },
      ),
    ),
    GoRoute(
      path: '/shop/forgot-password',
      parentNavigatorKey: _rootNavigatorKey,
      pageBuilder: (context, state) => CustomTransitionPage(
        key: state.pageKey,
        child: const ForgotPasswordScreen(),
        transitionsBuilder: (context, animation, secondaryAnimation, child) {
          return FadeTransition(
            opacity: animation,
            child: SlideTransition(
              position:
                  Tween<Offset>(
                    begin: const Offset(0, 0.1),
                    end: Offset.zero,
                  ).animate(
                    CurvedAnimation(parent: animation, curve: Curves.easeOut),
                  ),
              child: child,
            ),
          );
        },
      ),
    ),
    GoRoute(
      path: '/shop/reset-password',
      parentNavigatorKey: _rootNavigatorKey,
      pageBuilder: (context, state) {
        final token = state.uri.queryParameters['token'] ?? '';
        final email = state.uri.queryParameters['email'] ?? '';
        return CustomTransitionPage(
          key: state.pageKey,
          child: ResetPasswordScreen(token: token, email: email),
          transitionsBuilder: (context, animation, secondaryAnimation, child) {
            return FadeTransition(
              opacity: animation,
              child: SlideTransition(
                position:
                    Tween<Offset>(
                      begin: const Offset(0, 0.1),
                      end: Offset.zero,
                    ).animate(
                      CurvedAnimation(parent: animation, curve: Curves.easeOut),
                    ),
                child: child,
              ),
            );
          },
        );
      },
    ),

    // ═══ ACCOUNT ROUTES (Full Screen - No Shell) ═══
    GoRoute(
      path: '/shop/account',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const AccountScreen(),
      routes: [
        GoRoute(
          path: 'orders',
          parentNavigatorKey: _rootNavigatorKey,
          builder: (context, state) => const OrderListScreen(),
          routes: [
            GoRoute(
              path: ':orderNumber',
              parentNavigatorKey: _rootNavigatorKey,
              builder: (context, state) => OrderDetailScreen(
                orderNumber: state.pathParameters['orderNumber']!,
              ),
            ),
          ],
        ),
        GoRoute(
          path: 'edit-profile',
          parentNavigatorKey: _rootNavigatorKey,
          builder: (context, state) => const EditProfileScreen(),
        ),
        GoRoute(
          path: 'change-password',
          parentNavigatorKey: _rootNavigatorKey,
          builder: (context, state) => const ChangePasswordScreen(),
        ),
        GoRoute(
          path: 'addresses',
          parentNavigatorKey: _rootNavigatorKey,
          builder: (context, state) => const AddressListScreen(),
        ),
      ],
    ),

    // ═══ CHECKOUT ROUTES (Full Screen - No Shell) ═══
    GoRoute(
      path: '/shop/checkout',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const CheckoutScreen(),
      routes: [
        GoRoute(
          path: 'success',
          parentNavigatorKey: _rootNavigatorKey,
          builder: (context, state) => OrderSuccessScreen(
            orderNumber: state.uri.queryParameters['order_number'],
          ),
        ),
      ],
    ),

    // ═══ CONTENT PAGES (Full Screen - No Shell) ═══
    GoRoute(
      path: '/shop/faq',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const FaqScreen(),
    ),
    GoRoute(
      path: '/shop/promo',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const PromoScreen(),
    ),
    GoRoute(
      path: '/shop/panduan-ukuran',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const PanduanUkuranScreen(),
    ),
    GoRoute(
      path: '/shop/kebijakan-privasi',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const KebijakanPrivasiScreen(),
    ),
    GoRoute(
      path: '/shop/kebijakan-pengembalian',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const KebijakanPengembalianScreen(),
    ),
    GoRoute(
      path: '/shop/syarat-ketentuan',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const SyaratKetentuanScreen(),
    ),
    GoRoute(
      path: '/shop/tentang-kami',
      parentNavigatorKey: _rootNavigatorKey,
      builder: (context, state) => const TentangKamiScreen(),
    ),
  ],
);

class MitologiShopApp extends StatelessWidget {
  const MitologiShopApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'Mitologi Clothing',
      theme: AppTheme.lightTheme,
      routerConfig: _router,
      debugShowCheckedModeBanner: false,
    );
  }
}
