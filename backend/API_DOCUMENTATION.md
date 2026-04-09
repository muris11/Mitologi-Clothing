# Mitologi Clothing - API Documentation

Base URL: `http://localhost:8000/api/v1`

## Table of Contents
- [Authentication](#authentication)
- [Public API (No Auth Required)](#public-api-no-auth-required)
- [Cart API (Guest/Auth)](#cart-api-guestauth)
- [Authenticated API](#authenticated-api)
- [Admin API](#admin-api)
- [Webhook & Internal](#webhook--internal)

---

## Authentication

### Register
**POST** `/auth/register`

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | User full name |
| email | string | Yes | Valid email address |
| password | string | Yes | Min 8 characters |
| password_confirmation | string | Yes | Must match password |
| phone | string | No | Phone number |

**Response:**
```json
{
  "data": {
    "user": { "id": 1, "name": "John", "email": "john@example.com" },
    "token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### Login
**POST** `/auth/login`

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | Registered email |
| password | string | Yes | User password |

**Headers (Optional):**
| Header | Description |
|--------|-------------|
| X-Cart-Id | Guest cart session ID to merge |

**Response:**
```json
{
  "data": {
    "user": { "id": 1, "name": "John", "email": "john@example.com" },
    "token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### Logout
**POST** `/auth/logout`

**Headers:**
| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

### Get Current User
**GET** `/auth/user`

**Headers:**
| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "John",
    "email": "john@example.com",
    "phone": "08123456789",
    "avatar_url": "http://localhost:8000/storage/avatars/user1.jpg"
  }
}
```

### Forgot Password
**POST** `/auth/forgot-password`

**Request Body:**
| Field | Type | Required |
|-------|------|----------|
| email | string | Yes |

### Reset Password
**POST** `/auth/reset-password`

**Request Body:**
| Field | Type | Required |
|-------|------|----------|
| token | string | Yes | From email |
| email | string | Yes |
| password | string | Yes | Min 8 chars |
| password_confirmation | string | Yes |

---

## Public API (No Auth Required)

### Landing Page
**GET** `/landing-page`

**Response:**
```json
{
  "data": {
    "hero_slides": [...],
    "features": [...],
    "testimonials": [...],
    "materials": [...],
    "products": [...],
    "team_members": [...],
    "site_settings": {...}
  }
}
```

### Site Settings
**GET** `/site-settings`

**Response:**
```json
{
  "data": {
    "site_name": "Mitologi Clothing",
    "logo_url": "http://localhost:8000/storage/logo.png",
    "contact_email": "contact@example.com"
  }
}
```

### List Products
**GET** `/products`

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| q | string | - | Search keyword |
| category | string | - | Category handle |
| sortKey | string | 'RELEVANCE' | PRICE, CREATED_AT, BEST_SELLING |
| reverse | boolean | false | Reverse sort order |
| minPrice | number | - | Minimum price filter |
| maxPrice | number | - | Maximum price filter |
| page | integer | 1 | Page number |
| limit | integer | 20 | Items per page (max 100) |
| ids | string | - | Comma-separated product IDs |

**Response:**
```json
{
  "data": {
    "products": [
      {
        "id": 1,
        "title": "Garuda Oversize Tee",
        "handle": "garuda-oversize-tee",
        "description": "Kaos oversize premium...",
        "priceRange": {
          "minVariantPrice": { "amount": "150000", "currencyCode": "IDR" },
          "maxVariantPrice": { "amount": "175000", "currencyCode": "IDR" }
        },
        "featuredImage": {
          "url": "http://localhost:8000/storage/products/garuda.jpg",
          "altText": "Garuda Tee"
        },
        "variants": [...],
        "options": [...],
        "images": [...],
        "categories": [...],
        "average_rating": 4.5,
        "reviews_count": 12
      }
    ],
    "pagination": {
      "total": 50,
      "perPage": 20,
      "currentPage": 1,
      "lastPage": 3
    }
  }
}
```

### Get Product Detail
**GET** `/products/{handle}`

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| handle | string | Product URL handle (slug) |

### Best Sellers
**GET** `/products/best-sellers`

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| limit | integer | 4 | Number of products |

### New Arrivals
**GET** `/products/new-arrivals`

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| limit | integer | 4 | Number of products |

### Product Recommendations
**GET** `/products/{id}/recommendations`

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| limit | integer | 5 | Number of recommendations |

### Product Reviews
**GET** `/products/{handle}/reviews`

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| handle | string | Product handle |

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |

### List Categories
**GET** `/categories`

**Response:**
```json
{
  "data": [
    { "id": 1, "name": "T-Shirt", "handle": "t-shirt", "product_count": 15 }
  ]
}
```

### Get Category Detail
**GET** `/categories/{handle}`

### List Materials
**GET** `/materials`

### Order Steps (Cara Pemesanan)
**GET** `/order-steps`

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| type | string | Filter by type |
| grouped | boolean | Group by category |

### Collections
**GET** `/collections`

**GET** `/collections/{handle}`

**GET** `/collections/{handle}/products`

### Menu
**GET** `/menus/{handle}`

### Pages (CMS)
**GET** `/pages`

**GET** `/pages/{handle}`

### Portfolio
**GET** `/portfolio`

**GET** `/portfolio/{slug}`

---

## Cart API (Guest/Auth)

All cart endpoints accept these headers:
| Header | Description |
|--------|-------------|
| X-Cart-Id | Cart session ID |
| X-Session-Id | Alternative session ID |

### Create Cart
**POST** `/cart`

**Response:** Creates new cart with session ID

### Get Cart
**GET** `/cart`

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Cart session ID (alternative to header) |

**Response:**
```json
{
  "data": {
    "id": "cart-uuid",
    "sessionId": "session-uuid",
    "checkoutUrl": "/checkout",
    "cost": {
      "subtotalAmount": { "amount": "300000", "currencyCode": "IDR" },
      "totalAmount": { "amount": "300000", "currencyCode": "IDR" },
      "totalTaxAmount": { "amount": "0", "currencyCode": "IDR" }
    },
    "lines": [
      {
        "id": "line-1",
        "merchandiseId": "variant-1",
        "title": "Garuda Tee - Black, M",
        "quantity": 2,
        "price": { "amount": "150000", "currencyCode": "IDR" },
        "imageUrl": "http://...",
        "variantTitle": "Black / M"
      }
    ],
    "totalQuantity": 2
  }
}
```

### Add Item to Cart
**POST** `/cart/items`

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| merchandiseId | string | Yes | Product variant ID |
| quantity | integer | Yes | Quantity to add |

### Update Cart Item
**PUT** `/cart/items/{id}`

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Cart line item ID |

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| merchandiseId | string | Yes | Product variant ID |
| quantity | integer | Yes | New quantity |

### Remove Cart Item
**DELETE** `/cart/items/{id}`

### Clear Cart
**DELETE** `/cart/clear`

---

## Authenticated API

Require `Authorization: Bearer {token}` header

### Profile

**GET** `/profile`

**PUT** `/profile`

**Request Body:**
| Field | Type | Required |
|-------|------|----------|
| name | string | No |
| email | string | No |
| phone | string | No |

**PUT** `/profile/password`

**Request Body:**
| Field | Type | Required |
|-------|------|----------|
| current_password | string | Yes |
| password | string | Yes | Min 8 |
| password_confirmation | string | Yes |

**POST** `/profile/avatar`

**Request:** Multipart form with `avatar` file

### Addresses

**GET** `/profile/addresses`

**POST** `/profile/addresses`

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| label | string | Yes | Home, Office, etc |
| recipient_name | string | Yes |
| phone | string | Yes |
| address | string | Yes | Full address |
| city | string | Yes |
| postal_code | string | Yes |
| is_default | boolean | No |

**PUT** `/profile/addresses/{address}`

**DELETE** `/profile/addresses/{address}`

### Orders

**GET** `/orders`

**GET** `/orders/{orderNumber}`

**POST** `/orders/{orderNumber}/pay`

**POST** `/orders/{orderNumber}/confirm-payment`

**Request Body:**
| Field | Type | Required |
|-------|------|----------|
| payment_proof | file | Yes | Image proof |

**POST** `/orders/{orderNumber}/request-refund`

**Request Body:**
| Field | Type | Required |
|-------|------|----------|
| reason | string | Yes |

### Checkout

**POST** `/checkout`

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| cartId | string | Yes | Cart session ID |
| addressId | integer | Yes | Shipping address ID |
| shippingCost | number | Yes | Shipping cost amount |
| paymentMethod | string | Yes | midtrans, bank_transfer, cod |
| notes | string | No | Order notes |

**Response:**
```json
{
  "data": {
    "orderNumber": "ORD-2024-001",
    "snapToken": "abc123", // For Midtrans
    "redirectUrl": "https://..."
  }
}
```

### Recommendations

**GET** `/recommendations`

Returns AI-powered product recommendations for logged-in user.

### Wishlist

**GET** `/wishlist`

**POST** `/wishlist/{productId}`

**DELETE** `/wishlist/{productId}`

**GET** `/wishlist/check/{productId}`

Returns: `{ "inWishlist": true/false }`

### Reviews

**POST** `/products/{handle}/reviews`

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| rating | integer | Yes | 1-5 |
| comment | string | Yes | Review text |

**PUT** `/reviews/{review}`

**DELETE** `/reviews/{review}`

### Interactions (ML Tracking)

**POST** `/interactions/batch`

**Request Body:**
```json
{
  "interactions": [
    { "productId": 1, "action": "view", "score": 1 },
    { "productId": 2, "action": "cart", "score": 3 },
    { "productId": 3, "action": "purchase", "score": 5 }
  ]
}
```

Actions: `view`, `cart`, `purchase`

---

## Admin API

Require admin authentication

### Reports

**GET** `/admin/reports/top-products`

**Query Parameters:**
| Parameter | Type | Default |
|-----------|------|---------|
| days | integer | 30 |
| limit | integer | 10 |

**GET** `/admin/reports/trending`

**Query Parameters:**
| Parameter | Type | Default |
|-----------|------|---------|
| days | integer | 7 |
| limit | integer | 10 |

**GET** `/admin/reports/stock-recommendations`

**Query Parameters:**
| Parameter | Type | Default |
|-----------|------|---------|
| threshold | integer | 10 |
| limit | integer | 10 |

### ML Reports

**GET** `/admin/ml/status`

Returns ML service health and training status.

**POST** `/admin/ml/retrain`

Triggers manual model retraining.

---

## Webhook & Internal

### Checkout Notification (Midtrans)
**POST** `/checkout/notification`

Handles Midtrans payment callbacks.

### Chatbot
**POST** `/chatbot`

**Request Body:**
```json
{
  "message": "What are best sellers?",
  "history": [
    { "role": "user", "content": "Hello" },
    { "role": "assistant", "content": "Hi!" }
  ]
}
```

**Response:**
```json
{
  "data": {
    "reply": "Our best sellers are...",
    "products": [1, 2, 3]
  }
}
```

### ML Export (Internal)
**GET** `/ml/export-data`

Returns training data for AI service.

**Query Parameters:**
| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| months | integer | 6 | Data history months |

---

## Storage URLs

All images are served from `/storage/{path}`:
- Products: `/storage/products/{filename}`
- Categories: `/storage/categories/{filename}`
- Avatars: `/storage/avatars/{filename}`
- Hero Slides: `/storage/hero-slides/{filename}`

---

## Error Responses

### Standard Error Format
```json
{
  "error": {
    "code": "validation_error",
    "message": "The given data was invalid.",
    "errors": {
      "email": ["The email field is required."],
      "password": ["The password must be at least 8 characters."]
    }
  }
}
```

### Common HTTP Codes
| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

### Error Codes
| Code | Description |
|------|-------------|
| unauthenticated | Not logged in |
| invalid_token | Token expired/invalid |
| validation_error | Input validation failed |
| not_found | Resource not found |
| insufficient_stock | Product out of stock |
| payment_failed | Payment processing failed |

---

## Rate Limiting

- Public API: 60 requests/minute per IP
- Authenticated: 120 requests/minute per user
- Checkout: 10 requests/minute per session

---

## Headers Summary

### Required Headers
| Header | Value | When |
|--------|-------|------|
| Content-Type | application/json | POST/PUT requests |
| Accept | application/json | All requests |

### Authentication Headers
| Header | Value | When |
|--------|-------|------|
| Authorization | Bearer {token} | Auth-required endpoints |

### Cart Headers
| Header | Value | When |
|--------|-------|------|
| X-Cart-Id | {sessionId} | Cart operations |
| X-Session-Id | {sessionId} | Cart creation |

---

**Last Updated:** 2025-04-09  
**Version:** v1.0
