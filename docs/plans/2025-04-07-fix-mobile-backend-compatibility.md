# Fix Mobile-Backend Compatibility Issues - Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Fix all critical compatibility issues between Flutter mobile app and Laravel backend API, ensuring 100% endpoint coverage and proper configuration.

**Architecture:** Add missing `/cart/clear` endpoint to Laravel backend, fix hardcoded IP configuration in mobile, standardize ChangeNotifier patterns, and verify all API contracts are satisfied.

**Tech Stack:** Laravel 12 (PHP), Flutter 3.x (Dart), REST API, PHPUnit testing

---

## Issue Summary

| Issue | Priority | File | Impact |
|-------|----------|------|--------|
| Missing `/cart/clear` endpoint | **CRITICAL** | `backend/routes/api_v1.php` | Cart clear feature broken |
| Hardcoded web IP in mobile | **HIGH** | `mobile/lib/config/api_config.dart` | Won't work on other networks |
| ChangeNotifier inconsistency | **MEDIUM** | 3 provider files | Code style inconsistency |

---

### Task 1: Add Missing `/cart/clear` Endpoint to Laravel Backend

**Files:**
- Modify: `backend/routes/api_v1.php:86-87`
- Modify: `backend/app/Http/Controllers/Api/CartController.php`
- Test: `backend/tests/Feature/CartTest.php`

**Context:** Mobile app calls `DELETE /cart/clear` in `mobile/lib/services/cart_service.dart:90`, but backend only has `DELETE /cart/items/{id}` for individual items.

**Step 1: Write the failing test**

Create test in `backend/tests/Feature/CartTest.php` (add to existing file):

```php
public function test_can_clear_cart(): void
{
    $user = User::factory()->create();
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    CartItem::factory()->count(3)->create(['cart_id' => $cart->id]);

    $response = $this->actingAs($user)
        ->deleteJson('/api/v1/cart/clear');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Cart cleared successfully']);

    $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
}
```

**Step 2: Run test to verify it fails**

```bash
cd backend
php artisan test tests/Feature/CartTest.php --filter=test_can_clear_cart
```

Expected output:
```
FAIL  Tests\Feature\CartTest
  ✕ test_can_clear_cart
  ---
  Expected response status code [200] but received [404].
  Route [DELETE] /api/v1/cart/clear not found
```

**Step 3: Add route definition**

Edit `backend/routes/api_v1.php` line 86-87, add new route inside cart group:

```php
Route::prefix('cart')->group(function () {
    Route::post('/', [CartController::class, 'create']);
    Route::get('/', [CartController::class, 'show']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{id}', [CartController::class, 'updateItem']);
    Route::delete('/items/{id}', [CartController::class, 'removeItem']);
    Route::delete('/clear', [CartController::class, 'clear']); // <-- ADD THIS LINE
});
```

**Step 4: Implement controller method**

Edit `backend/app/Http/Controllers/Api/CartController.php`, add method:

```php
/**
 * Clear all items from the cart.
 */
public function clear(Request $request): JsonResponse
{
    $cart = $this->getOrCreateCart($request);
    
    $cart->items()->delete();
    
    return $this->successResponse([
        'message' => 'Cart cleared successfully',
        'cart_id' => $cart->id,
    ]);
}
```

**Step 5: Run test to verify it passes**

```bash
cd backend
php artisan test tests/Feature/CartTest.php --filter=test_can_clear_cart
```

Expected output:
```
PASS  Tests\Feature\CartTest
  ✓ test_can_clear_cart

  Tests:  1 passed
```

**Step 6: Commit**

```bash
cd C:\laragon\www\Mitologi Clothing

git add backend/routes/api_v1.php backend/app/Http/Controllers/Api/CartController.php backend/tests/Feature/CartTest.php

git commit -m "fix(api): add missing /cart/clear endpoint

- Add DELETE /api/v1/cart/clear route to clear all cart items
- Implement clear() method in CartController
- Add test coverage for cart clear functionality
- Fixes mobile app 404 error when clearing cart

Closes mobile-backend compatibility issue #1"
```

---

### Task 2: Fix Hardcoded IP in Mobile API Config

**Files:**
- Modify: `mobile/lib/config/api_config.dart:28`

**Context:** Line 28 has hardcoded IP `http://192.168.1.202:8011` for web platform, which won't work on other machines.

**Step 1: Identify the issue**

View current code:

```dart
// Line 28 in mobile/lib/config/api_config.dart
if (kIsWeb) return 'http://192.168.1.202:8011';
```

**Step 2: Fix to use localhost**

Replace with dynamic configuration:

```dart
static String get _defaultBackendOrigin {
  // TIPS: Use 'ipconfig' on Windows to find your IPv4 if using physical device.
  // const String physicalDeviceIp = 'http://192.168.1.XX:8011';

  if (kIsWeb) {
    // For web, use localhost or override via dart-define
    const webOverride = String.fromEnvironment('MITOLOGI_WEB_API_URL');
    if (webOverride.isNotEmpty) return webOverride;
    return 'http://localhost:8011';
  }

  if (Platform.isAndroid) {
    // 10.0.2.2 is the special alias for the host machine in Android Emulator
    return 'http://10.0.2.2:8011';
  }

  if (Platform.isIOS) {
    // iOS Simulator shares the host's localhost
    return 'http://localhost:8011';
  }

  return 'http://localhost:8011';
}
```

**Step 3: Verify the change**

Check that all platforms use correct ports:
- Web: `http://localhost:8011` (or via `MITOLOGI_WEB_API_URL` dart-define)
- Android Emulator: `http://10.0.2.2:8011`
- iOS Simulator: `http://localhost:8011`
- Default: `http://localhost:8011`

**Step 4: Run Flutter analyze to check for errors**

```bash
cd C:\laragon\www\Mitologi Clothing\mobile
flutter analyze lib/config/api_config.dart
```

Expected output:
```
Analyzing api_config.dart...                                        
No issues found! (ran in 2.5s)
```

**Step 5: Commit**

```bash
cd C:\laragon\www\Mitologi Clothing

git add mobile/lib/config/api_config.dart

git commit -m "fix(mobile): remove hardcoded IP for web platform

- Replace hardcoded 192.168.1.202 with localhost for web
- Add MITOLOGI_WEB_API_URL dart-define for web override
- Ensures app works on any machine without code changes
- Maintains existing Android/iOS emulator support

Fixes mobile configuration issue #2"
```

---

### Task 3: Standardize ChangeNotifier Pattern in Providers

**Files:**
- Modify: `mobile/lib/providers/content_provider.dart:9`
- Modify: `mobile/lib/providers/profile_provider.dart:5`
- Modify: `mobile/lib/providers/collection_provider.dart:6`

**Context:** Some providers use `with ChangeNotifier`, others use `extends ChangeNotifier`. Should standardize to `extends` for consistency.

**Step 1: Find all inconsistencies**

```bash
cd C:\laragon\www\Mitologi Clothing\mobile
grep -n "with ChangeNotifier\|extends ChangeNotifier" lib/providers/*.dart
```

Expected output:
```
lib/providers/content_provider.dart:9:class ContentProvider with ChangeNotifier {
lib/providers/profile_provider.dart:5:class ProfileProvider with ChangeNotifier {
lib/providers/collection_provider.dart:6:class CollectionProvider with ChangeNotifier {
lib/providers/auth_provider.dart:6:class AuthProvider extends ChangeNotifier {
lib/providers/cart_provider.dart:5:class CartProvider extends ChangeNotifier {
...etc (others use extends)
```

**Step 2: Fix ContentProvider**

Edit `mobile/lib/providers/content_provider.dart` line 9:

```dart
// Change from:
class ContentProvider with ChangeNotifier {
// To:
class ContentProvider extends ChangeNotifier {
```

**Step 3: Fix ProfileProvider**

Edit `mobile/lib/providers/profile_provider.dart` line 5:

```dart
// Change from:
class ProfileProvider with ChangeNotifier {
// To:
class ProfileProvider extends ChangeNotifier {
```

**Step 4: Fix CollectionProvider**

Edit `mobile/lib/providers/collection_provider.dart` line 6:

```dart
// Change from:
class CollectionProvider with ChangeNotifier {
// To:
class CollectionProvider extends ChangeNotifier {
```

**Step 5: Verify all providers now use extends**

```bash
cd C:\laragon\www\Mitologi Clothing\mobile
grep -n "with ChangeNotifier" lib/providers/*.dart
```

Expected output: (empty - no matches)

```bash
grep -n "extends ChangeNotifier" lib/providers/*.dart | wc -l
```

Expected output: `9` (all 9 providers now use extends)

**Step 6: Run Flutter analyze**

```bash
cd C:\laragon\www\Mitologi Clothing\mobile
flutter analyze lib/providers/
```

Expected output:
```
Analyzing providers...                                              
No issues found! (ran in 3.2s)
```

**Step 7: Commit**

```bash
cd C:\laragon\www\Mitologi Clothing

git add mobile/lib/providers/content_provider.dart mobile/lib/providers/profile_provider.dart mobile/lib/providers/collection_provider.dart

git commit -m "refactor(mobile): standardize ChangeNotifier pattern

- Change 'with ChangeNotifier' to 'extends ChangeNotifier' in 3 providers
- ContentProvider, ProfileProvider, CollectionProvider now consistent
- All 9 providers now use same pattern for code consistency

Fixes mobile code style issue #3"
```

---

### Task 4: Run Full Test Suite to Verify All Fixes

**Files:** All test files

**Step 1: Run Laravel Backend Tests**

```bash
cd C:\laragon\www\Mitologi Clothing\backend
composer run test
```

Expected output:
```
PASS  Tests\Feature\AuthTest
PASS  Tests\Feature\CartTest
  ✓ test_can_clear_cart (NEW)
PASS  Tests\Feature\CheckoutTest
PASS  Tests\Feature\OrderTest
PASS  Tests\Feature\ProductTest
...etc

Tests:  52 passed (was 51, now 52 with new test)
```

**Step 2: Run Flutter Tests**

```bash
cd C:\laragon\www\Mitologi Clothing\mobile
flutter test
```

Expected output:
```
00:00 +0: loading...                                                                                            
00:01 +1: All tests passed!                                                                                    

Tests: 16 passed
```

**Step 3: Verify API Endpoints Match**

Cross-check mobile service calls vs backend routes:

```bash
cd C:\laragon\www\Mitologi Clothing
# Check cart/clear specifically
grep -n "cart/clear" mobile/lib/services/*.dart
grep -n "cart/clear" backend/routes/api_v1.php
```

Both should show matches.

**Step 4: Commit verification results (optional)**

If all tests pass:

```bash
git add -A
git commit -m "test: verify all mobile-backend compatibility fixes

- All 52 Laravel tests passing (including new cart clear test)
- All 16 Flutter tests passing
- All 34 API endpoints matched between mobile and backend
- 100% compatibility achieved

Fixes #verification"
```

---

## Final Verification Checklist

After completing all tasks:

- [ ] Mobile can clear cart (no 404 error)
- [ ] Mobile web uses localhost (not hardcoded IP)
- [ ] All providers use `extends ChangeNotifier`
- [ ] All 52 Laravel tests pass
- [ ] All 16 Flutter tests pass
- [ ] API coverage: 34/34 endpoints (100%)

---

## Summary of Changes

| File | Change Type | Lines | Description |
|------|-------------|-------|-------------|
| `backend/routes/api_v1.php` | Add | 1 | New `/cart/clear` route |
| `backend/app/Http/Controllers/Api/CartController.php` | Add | ~15 | `clear()` method |
| `backend/tests/Feature/CartTest.php` | Add | ~15 | Test for cart clear |
| `mobile/lib/config/api_config.dart` | Modify | ~5 | Fix web IP to localhost |
| `mobile/lib/providers/content_provider.dart` | Modify | 1 | `with` → `extends` |
| `mobile/lib/providers/profile_provider.dart` | Modify | 1 | `with` → `extends` |
| `mobile/lib/providers/collection_provider.dart` | Modify | 1 | `with` → `extends` |

**Total:** 7 files modified, ~40 lines changed

---

## Testing Commands Reference

```bash
# Laravel Backend
cd backend
composer run test                    # Run all 52 tests
php artisan test --filter=cart      # Run cart tests only

# Flutter Mobile
cd mobile
flutter test                         # Run all 16 tests
flutter analyze                      # Static analysis
flutter run                          # Run app

# Verification
curl http://localhost:8011/api/v1/cart/clear -X DELETE -H "Authorization: Bearer TOKEN"
```

---

## Notes for Implementation

1. **Port 8011** is already configured across all services
2. **No database migrations needed** - cart table already exists
3. **No mobile rebuild needed** - API contract stays the same
4. **Tests will guide implementation** - follow TDD approach
5. **Frequent commits** - each task = one commit

---

## Post-Implementation API Compatibility Report

Expected final state:

| Endpoint | Mobile Calls | Backend Has | Status |
|----------|--------------|-------------|---------|
| All 34 endpoints | ✅ | ✅ | 100% Match |
| `/cart/clear` | ✅ | ✅ | Fixed |
| Port config | ✅ 8011 | ✅ 8011 | Aligned |
| Tests passing | 16/16 | 52/52 | 100% |
