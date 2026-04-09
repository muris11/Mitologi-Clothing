# backend

## Identity
- Laravel 12 backend serving public API, admin panel, payments, and revalidation hooks.
- Also owns Vite-powered admin assets and queue worker orchestration.

## Entry Points
- `public/index.php` - HTTP entry
- `bootstrap/app.php` - routing, middleware, exception bootstrap
- `routes/api.php` - storefront/mobile/chatbot/payment API surface
- `routes/web.php` - admin UI and auth routes
- `app/Http/Controllers/Api/` - API controllers
- `app/Http/Controllers/Admin/` - admin controllers
- `app/Services/` - service-layer integrations like checkout, Midtrans, recommendations

## Commands
```bash
composer install
composer run dev        # Laravel + queue + Vite concurrently
composer run test       # PHPUnit tests (uses SQLite in-memory)
composer run setup      # Full setup: install, env, migrate, npm build
npm install
npm run dev             # Vite dev server
npm run build           # Build admin assets
```

## Single Test Commands
```bash
# Run specific test file
php artisan test tests/Feature/CheckoutTest.php

# Run specific test method
php artisan test --filter=test_can_create_order

# Run with coverage (requires Xdebug/PCOV)
php artisan test --coverage
```

## Where To Look
| Task | Location | Notes |
|------|----------|-------|
| Public API route | `routes/api.php` | storefront, auth, cart, orders, chatbot |
| Admin route | `routes/web.php` | admin resources, reports, settings |
| Payment flow | `app/Services/MidtransService.php`, `app/Http/Controllers/Api/CheckoutController.php` | webhook + checkout |
| Recommendation bridge | `app/Services/RecommendationService.php`, `app/Http/Controllers/Api/RecommendationController.php` | talks to Python service |
| Reporting + ML export | `app/Http/Controllers/Admin/ReportController.php` | ML status, retrain, export |
| Tests | `tests/Feature/` | checkout, auth, product, webhook coverage |
| Models | `app/Models/` | Eloquent models |
| Migrations | `database/migrations/` | Database schema |
| Seeders | `database/seeders/` | Test data |
| Config | `config/` | Laravel config files |

## Conventions
- Keep route declarations in `routes/api.php` and `routes/web.php`; controller logic should stay out of route files.
- Separate `Api` and `Admin` controller concerns instead of mixing storefront and admin behavior.
- Service classes under `app/Services/` are the preferred home for external integrations and heavier business flows.
- `composer run dev` is the normal local workflow because it starts Laravel, queue listener, and Vite together.
- Backend admin changes are expected to trigger frontend revalidation, so web-facing content changes often need cross-service review.
- Use dependency injection via constructors, not facades where possible.
- Return type declarations on all methods.
- Type-hint all method parameters.

## Code Style
- **Linting**: Laravel Pint (`composer exec pint`)
- **Imports**: Use fully qualified class names in docblocks, imports at top
- **Formatting**: PSR-12 compliant via Laravel Pint
- **Types**: PHP 8.2+ types, union types where appropriate
- **Naming**: PascalCase classes, camelCase methods/variables, SCREAMING_SNAKE_CASE constants
- **Error Handling**: Try/catch with specific exceptions, never empty catch blocks
- **Testing**: PHPUnit with in-memory SQLite, Feature tests preferred

## Anti-Patterns
- Do not infer patterns from `vendor/` or generated `storage/framework/` output.
- Do not edit extracted third-party code under `storage/app/filament-v5-extract/` as if it were app source.
- Do not put new storefront endpoints only in web routes; public/mobile/frontend consumers expect `routes/api.php`.
- Do not use raw DB queries when Eloquent suffices.
- Do not skip validation on API inputs.

## Pre-PR Checks
```bash
composer run test
composer exec pint -- --test
```

## Notes
- `phpunit.xml` uses in-memory SQLite for tests.
- Backend README documents Midtrans sandbox env vars and mock token behavior for local/debug.
- Environment: Copy `.env.example` to `.env` and generate key.
