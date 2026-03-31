export const ENDPOINTS = {
  // ═══ PUBLIC (no auth required) ═══
  // Public Data
  LANDING_PAGE: '/landing-page',
  SITE_SETTINGS: '/site-settings',
  
  // Catalog
  PRODUCTS: '/products',
  PRODUCTS_BEST_SELLERS: '/products/best-sellers',
  PRODUCTS_NEW_ARRIVALS: '/products/new-arrivals',
  PRODUCT_DETAIL: (handle: string) => `/products/${handle}`,
  PRODUCT_REVIEWS: (handle: string) => `/products/${handle}/reviews`,
  PRODUCT_RECOMMENDATIONS: (id: string | number) => `/products/${id}/recommendations`,
  
  CATEGORIES: '/categories',
  CATEGORY_DETAIL: (handle: string) => `/categories/${handle}`,
  
  COLLECTIONS: '/collections',
  COLLECTION_DETAIL: (handle: string) => `/collections/${handle}`,
  COLLECTION_PRODUCTS: (handle: string) => `/collections/${handle}/products`,
  
  MATERIALS: '/materials',
  ORDER_STEPS: '/order-steps',

  // Cart
  CART: '/cart',
  CART_ITEMS: '/cart/items',
  CART_ITEM_DETAIL: (id: string | number) => `/cart/items/${id}`,

  // Content
  PAGES: '/pages',
  PAGE_DETAIL: (handle: string) => `/pages/${handle}`,
  PORTFOLIOS: '/portfolios',
  PORTFOLIO_DETAIL: (slug: string) => `/portfolios/${slug}`,
  MENU: (handle: string) => `/menus/${handle}`,

  // Auth
  AUTH_REGISTER: '/auth/register',
  AUTH_LOGIN: '/auth/login',
  AUTH_LOGOUT: '/auth/logout',
  AUTH_USER: '/auth/user',
  AUTH_FORGOT_PASSWORD: '/auth/forgot-password',
  AUTH_RESET_PASSWORD: '/auth/reset-password',

  // ═══ SANCTUM (auth required) ═══
  // Profile (Protected)
  PROFILE: '/profile',
  PROFILE_PASSWORD: '/profile/password',
  PROFILE_AVATAR: '/profile/avatar',

  // Orders & Checkout (Protected)
  ORDERS: '/orders',
  ORDER_DETAIL: (orderNumber: string) => `/orders/${orderNumber}`,
  ORDER_PAY: (orderNumber: string) => `/orders/${orderNumber}/pay`,
  ORDER_CONFIRM_PAYMENT: (orderNumber: string) => `/orders/${orderNumber}/confirm-payment`,
  ORDER_REQUEST_REFUND: (orderNumber: string) => `/orders/${orderNumber}/request-refund`,
  CHECKOUT_PROCESS: '/checkout',

  // Wishlist (Protected)
  WISHLIST: '/wishlist',
  WISHLIST_ADD: (productId: string | number) => `/wishlist/${productId}`,
  WISHLIST_REMOVE: (productId: string | number) => `/wishlist/${productId}`,
  WISHLIST_CHECK: (productId: string | number) => `/wishlist/check/${productId}`,

  // Reviews (Protected)
  REVIEW_STORE: (handle: string) => `/products/${handle}/reviews`,
  REVIEW_UPDATE: (reviewId: string | number) => `/reviews/${reviewId}`,
  REVIEW_DELETE: (reviewId: string | number) => `/reviews/${reviewId}`,

  // User Behaviors / Recommendations
  INTERACTIONS_BATCH: '/interactions/batch',
  USER_RECOMMENDATIONS: '/recommendations',

  // Integrations
  CHATBOT: '/chatbot',

  // ═══ NOT CALLABLE BY STOREFRONT ═══
  // admin:   /admin/reports/*, /admin/ml/*, /admin/rfid/status
  // webhook: /checkout/notification
  // local:   /test/fix-orders
} as const;
