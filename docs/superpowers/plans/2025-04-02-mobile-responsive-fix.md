# Mobile Responsive Design Fix Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix responsive design in all 30 Flutter screens by replacing hardcoded padding values with ResponsiveHelper utility, ensuring consistent spacing across phone, tablet, and desktop layouts.

**Architecture:** Systematically update each screen to use `ResponsiveHelper.horizontalPadding(context)` instead of hardcoded `EdgeInsets` values. This leverages the existing responsive utility class that provides adaptive spacing based on screen width breakpoints (600px mobile, 900px tablet).

**Tech Stack:** Flutter, Dart, Provider pattern, GoRouter

---

## Overview

Currently only 3 out of 33 screens properly use ResponsiveHelper:
- ✅ `home_screen.dart` - Already responsive
- ✅ `shop_screen.dart` - Already responsive  
- ✅ `product_detail_screen.dart` - Already responsive

**30 screens need fixing** across these categories:
1. **Auth Screens (5)** - login, register, forgot/reset password
2. **Account Screens (6)** - profile, addresses, settings
3. **Shop Screens (3)** - promo, size guide (shop_screen already fixed)
4. **Checkout Screens (3)** - checkout, payment, order success
5. **Collection Screens (2)** - collections list, detail
6. **Order Screens (2)** - order list, detail
7. **Content Screens (4)** - CMS pages, privacy, terms, returns
8. **Help Screens (2)** - FAQ, chatbot
9. **Other Screens (3)** - notifications, wishlist, about us
10. **Splash Screens (2)** - splash, onboarding

---

## Task Structure Template

Each screen follows this pattern:
1. Add import for `ResponsiveHelper` if missing
2. Replace hardcoded padding: `EdgeInsets.all(16)` → `EdgeInsets.all(ResponsiveHelper.horizontalPadding(context))`
3. Replace asymmetric padding: `EdgeInsets.fromLTRB(16, 16, 16, 0)` → `EdgeInsets.fromLTRB(ResponsiveHelper.horizontalPadding(context), 16, ResponsiveHelper.horizontalPadding(context), 0)`
4. Run `flutter analyze` to ensure no issues
5. Run `flutter test` to ensure tests pass
6. Commit

---

## Task 1: Auth Screens - Login Screen

**Files:**
- Modify: `lib/screens/auth/login_screen.dart`
- Test: Verify responsive padding on different screen sizes

**Current Issues:**
- Line ~45: `padding: const EdgeInsets.all(24)` (hardcoded)
- Line ~67: `padding: const EdgeInsets.symmetric(horizontal: 24)` (hardcoded)
- Missing import for ResponsiveHelper

- [ ] **Step 1: Add import statement**

Add at the top of the file with other imports:
```dart
import '../../utils/responsive_helper.dart';
```

- [ ] **Step 2: Replace padding in build method**

Find the main container/wrapper and update padding:
```dart
// OLD:
padding: const EdgeInsets.all(24)

// NEW:
padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context))
```

- [ ] **Step 3: Replace symmetric padding**

```dart
// OLD:
padding: const EdgeInsets.symmetric(horizontal: 24)

// NEW:
padding: EdgeInsets.symmetric(horizontal: ResponsiveHelper.horizontalPadding(context))
```

- [ ] **Step 4: Run analyze**

```bash
flutter analyze lib/screens/auth/login_screen.dart
```
Expected: No issues

- [ ] **Step 5: Run tests**

```bash
flutter test
```
Expected: All tests pass

- [ ] **Step 6: Commit**

```bash
git add lib/screens/auth/login_screen.dart
git commit -m "fix(responsive): update login screen to use ResponsiveHelper

- Replace hardcoded padding with responsive values
- Phone: 16px, Tablet: 24px, Desktop: 32px"
```

---

## Task 2: Auth Screens - Register Screen

**Files:**
- Modify: `lib/screens/auth/register_screen.dart`

**Pattern:** Same as login screen - find all `EdgeInsets.all(16/24)` and `EdgeInsets.symmetric` with hardcoded values

- [ ] **Step 1: Add import**
```dart
import '../../utils/responsive_helper.dart';
```

- [ ] **Step 2: Replace all hardcoded padding values**
Replace all instances of:
- `const EdgeInsets.all(16)` → `EdgeInsets.all(ResponsiveHelper.horizontalPadding(context))`
- `const EdgeInsets.all(24)` → `EdgeInsets.all(ResponsiveHelper.horizontalPadding(context))`
- `const EdgeInsets.symmetric(horizontal: 16)` → `EdgeInsets.symmetric(horizontal: ResponsiveHelper.horizontalPadding(context))`

- [ ] **Step 3: Run analyze**
```bash
flutter analyze lib/screens/auth/register_screen.dart
```

- [ ] **Step 4: Run tests**
```bash
flutter test
```

- [ ] **Step 5: Commit**
```bash
git add lib/screens/auth/register_screen.dart
git commit -m "fix(responsive): update register screen to use ResponsiveHelper"
```

---

## Task 3: Auth Screens - Forgot Password Screen

**Files:**
- Modify: `lib/screens/auth/forgot_password_screen.dart`

- [ ] **Step 1: Add import**
```dart
import '../../utils/responsive_helper.dart';
```

- [ ] **Step 2: Replace padding in form state**
Find padding around form elements and replace hardcoded values

- [ ] **Step 3: Replace padding in success state**
Update success view padding

- [ ] **Step 4: Run analyze & test**
```bash
flutter analyze lib/screens/auth/forgot_password_screen.dart
flutter test test/screens/auth/forgot_password_screen_test.dart
```

- [ ] **Step 5: Commit**
```bash
git add lib/screens/auth/forgot_password_screen.dart
git commit -m "fix(responsive): update forgot password screen"
```

---

## Task 4: Auth Screens - Reset Password Screen

**Files:**
- Modify: `lib/screens/auth/reset_password_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 5: Account Screens - Account Screen

**Files:**
- Modify: `lib/screens/account/account_screen.dart`

**Current Issues:**
- Line 66: `padding: const EdgeInsets.fromLTRB(16, 16, 16, 0)`
- Line 102: `padding: const EdgeInsets.fromLTRB(16, 16, 16, 32)`
- Line 112: `padding: const EdgeInsets.symmetric(vertical: 16)`
- Line 141: `padding: const EdgeInsets.all(32)`
- Line 148: `padding: const EdgeInsets.all(24)`

- [ ] **Step 1: Add import**
```dart
import '../../utils/responsive_helper.dart';
```

- [ ] **Step 2: Replace asymmetric padding**
```dart
// OLD:
padding: const EdgeInsets.fromLTRB(16, 16, 16, 0)

// NEW:
padding: EdgeInsets.fromLTRB(
  ResponsiveHelper.horizontalPadding(context),
  16,
  ResponsiveHelper.horizontalPadding(context),
  0,
)
```

- [ ] **Step 3: Replace other padding values**
Replace all remaining hardcoded EdgeInsets with ResponsiveHelper equivalents

- [ ] **Step 4: Run analyze & test**
```bash
flutter analyze lib/screens/account/account_screen.dart
flutter test
```

- [ ] **Step 5: Commit**
```bash
git add lib/screens/account/account_screen.dart
git commit -m "fix(responsive): update account screen with responsive padding"
```

---

## Task 6: Account Screens - Edit Profile Screen

**Files:**
- Modify: `lib/screens/account/edit_profile_screen.dart`

- [ ] **Step 1: Add import**
- [ ] **Step 2: Replace form padding values**
- [ ] **Step 3: Run analyze & test**
- [ ] **Step 4: Commit**

---

## Task 7: Account Screens - Address List Screen

**Files:**
- Modify: `lib/screens/account/address_list_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 8: Account Screens - Address Form Screen

**Files:**
- Modify: `lib/screens/account/address_form_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 9: Account Screens - Change Password Screen

**Files:**
- Modify: `lib/screens/account/change_password_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 10: Shop Screens - Promo Screen

**Files:**
- Modify: `lib/screens/shop/promo_screen.dart`

- [ ] **Step 1: Add import**
- [ ] **Step 2: Replace padding values**
- [ ] **Step 3: Run analyze & test**
- [ ] **Step 4: Commit**

---

## Task 11: Shop Screens - Size Guide Screen (Panduan Ukuran)

**Files:**
- Modify: `lib/screens/shop/panduan_ukuran_screen.dart`

- [ ] **Step 1: Add import**
- [ ] **Step 2: Replace padding values**
- [ ] **Step 3: Run analyze & test**
- [ ] **Step 4: Commit**

---

## Task 12: Checkout Screens - Checkout Screen

**Files:**
- Modify: `lib/screens/checkout/checkout_screen.dart`

**Note:** This screen already has some responsiveness but needs consistent padding

- [ ] **Step 1: Add import if missing**
- [ ] **Step 2: Replace any remaining hardcoded padding**
- [ ] **Step 3: Run analyze & test**
- [ ] **Step 4: Commit**

---

## Task 13: Checkout Screens - Payment WebView Screen

**Files:**
- Modify: `lib/screens/checkout/payment_webview_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 14: Checkout Screens - Order Success Screen

**Files:**
- Modify: `lib/screens/checkout/order_success_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 15: Collection Screens - Collections Screen

**Files:**
- Modify: `lib/screens/collection/collections_screen.dart`

- [ ] **Step 1: Add import**
- [ ] **Step 2: Replace grid padding**
- [ ] **Step 3: Run analyze & test**
- [ ] **Step 4: Commit**

---

## Task 16: Collection Screens - Collection Detail Screen

**Files:**
- Modify: `lib/screens/collection/collection_detail_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 17: Order Screens - Order List Screen

**Files:**
- Modify: `lib/screens/order/order_list_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 18: Order Screens - Order Detail Screen

**Files:**
- Modify: `lib/screens/order/order_detail_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 19: Content Screens - CMS Page Screen

**Files:**
- Modify: `lib/screens/content/cms_page_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 20: Content Screens - Privacy Policy Screen

**Files:**
- Modify: `lib/screens/content/kebijakan_privasi_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 21: Content Screens - Terms Screen

**Files:**
- Modify: `lib/screens/content/syarat_ketentuan_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 22: Content Screens - Returns Policy Screen

**Files:**
- Modify: `lib/screens/content/kebijakan_pengembalian_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 23: Help Screens - FAQ Screen

**Files:**
- Modify: `lib/screens/help/faq_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 24: Help Screens - Chatbot Screen

**Files:**
- Modify: `lib/screens/help/chatbot_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 25: Other Screens - Cart Screen

**Files:**
- Modify: `lib/screens/cart/cart_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 26: Other Screens - Wishlist Screen

**Files:**
- Modify: `lib/screens/wishlist/wishlist_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 27: Other Screens - Notification Screen

**Files:**
- Modify: `lib/screens/notification/notification_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 28: Other Screens - About Us Screen (Tentang Kami)

**Files:**
- Modify: `lib/screens/tentang_kami/tentang_kami_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 29: Splash Screens - Splash Screen

**Files:**
- Modify: `lib/screens/splash/splash_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Task 30: Splash Screens - Onboarding Screen

**Files:**
- Modify: `lib/screens/splash/onboarding_screen.dart`

- [ ] **Step 1: Add import & replace padding**
- [ ] **Step 2: Run analyze & test**
- [ ] **Step 3: Commit**

---

## Final Verification Task

- [ ] **Step 1: Run comprehensive test suite**

```bash
flutter test
```
Expected: All 16 tests pass (already fixed earlier)

- [ ] **Step 2: Run static analysis**

```bash
flutter analyze
```
Expected: No issues found

- [ ] **Step 3: Build verification**

```bash
flutter build apk --debug
```
Expected: Build successful

- [ ] **Step 4: Responsive verification**

Check that all 33 screens now use ResponsiveHelper:
```bash
grep -l "ResponsiveHelper" lib/screens/*/*screen.dart | wc -l
```
Expected: 33 (all screens)

- [ ] **Step 5: Final commit**

```bash
git commit -m "refactor(responsive): implement consistent responsive design across all screens

- Updated 30 screens to use ResponsiveHelper
- Replaced hardcoded padding with adaptive values
- Phone: 16px, Tablet: 24px, Desktop: 32px
- All tests passing, analyze clean

Fixes mobile responsive design issues"
```

---

## Pattern Reference

### Import Statement
```dart
import '../../utils/responsive_helper.dart';
```

### Common Replacements

**Padding.all:**
```dart
// From:
padding: const EdgeInsets.all(16)
// To:
padding: EdgeInsets.all(ResponsiveHelper.horizontalPadding(context))
```

**Padding.symmetric:**
```dart
// From:
padding: const EdgeInsets.symmetric(horizontal: 16)
// To:
padding: EdgeInsets.symmetric(horizontal: ResponsiveHelper.horizontalPadding(context))
```

**Padding.fromLTRB:**
```dart
// From:
padding: const EdgeInsets.fromLTRB(16, 16, 16, 0)
// To:
padding: EdgeInsets.fromLTRB(
  ResponsiveHelper.horizontalPadding(context),
  16,
  ResponsiveHelper.horizontalPadding(context),
  0,
)
```

**Page Padding (full page):**
```dart
// From:
padding: const EdgeInsets.all(16)
// To:
padding: ResponsiveHelper.pagePadding(context)
```

---

## Breakpoints Reference

- **Phone:** `< 600px` → Padding: 16px
- **Tablet:** `600px - 899px` → Padding: 24px
- **Desktop:** `>= 900px` → Padding: 32px

---

## Notes

1. Some screens may not have any padding to change - verify each file first
2. If a screen already has `ResponsiveHelper` import, skip Step 1
3. Focus on main container padding, not individual widget internal padding
4. Test on different screen sizes using Flutter device preview if needed
5. Keep semantic commits - one screen per commit for easy rollback
