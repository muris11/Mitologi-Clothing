# Mitologi Clothing

## Repo Shape
- Multi-service repo, not a shared workspace. Install and verify each service inside its own directory.
- Primary apps: `nextjs-commerce/`, `backend/`, `mobile/`, `recommendation-service/`.
- Nearest `AGENTS.md` wins for service-specific rules: `nextjs-commerce/AGENTS.md`, `backend/AGENTS.md`, `mobile/AGENTS.md`, `recommendation-service/AGENTS.md`.

## Ground Truth
- Trust manifests and runtime config over prose. Root README and older instructions drifted; current scripts/defaults are:
- Frontend: `nextjs-commerce/package.json` runs Next.js on `3000`.
- Backend: `backend/composer.json` runs Laravel on `8000` and Vite on `5173`.
- Recommendation service: `backend/config/services.php` and `recommendation-service/app.py` default to `8001`.
- Mobile defaults still point at `8011` in `mobile/lib/config/api_config.dart`; use `--dart-define` when you need the app to hit the actual backend on `8000`.

## High-Value Commands
- Frontend install/dev: `pnpm install && pnpm dev`
- Frontend focused checks: `pnpm lint`, `pnpm test -- path/to/file.test.tsx`, `pnpm test:e2e`
- Backend setup: `composer run setup`
- Backend normal dev loop: `composer run dev`
- Backend focused checks: `php artisan test tests/Feature/CheckoutTest.php`, `php artisan test --filter=test_can_create_order`, `composer exec pint -- --test`
- Mobile setup/checks: `flutter pub get`, `flutter analyze`, `flutter test test/screens/cart/cart_screen_test.dart`
- Recommendation service checks: `pip install -r requirements.txt`, `python -m pytest`, `python -m py_compile app.py server.py model.py train_job.py`

## Verification Order
- Frontend: `pnpm lint` first because it already runs endpoint validation plus `tsc --noEmit`; then run the smallest relevant Vitest or Playwright command.
- Backend: `composer exec pint -- --test` and the smallest relevant `php artisan test ...` command.
- Mobile: `flutter analyze` before `flutter test`.
- Recommendation service: use `python -m pytest` when behavior changes, `py_compile` when only a quick syntax check is needed.

## Architecture Facts Agents Miss
- The real Laravel API surface is `backend/routes/api_v1.php`; `backend/routes/api.php` is only a proxy include.
- Frontend API paths are centralized in `nextjs-commerce/lib/api/endpoints.ts`. Do not invent literal backend paths in components.
- Frontend cache invalidation is cross-service: Laravel calls Next.js `/api/revalidate` through `backend/app/Services/FrontendCacheService.php`.
- Next.js rewrites `/storage/*` to Laravel and also proxies `/api/team-members/:id/photo`; that photo route exists to avoid Windows `artisan serve` symlink issues.
- Recommendation-service auth uses `X-API-Key` and the env key name is `RECOMMENDER_API_KEY`, which must stay aligned with Laravel `config/services.php`.

## Service Boundaries
- `nextjs-commerce/`: storefront only. Use `lib/api/` wrappers for backend communication.
- `backend/`: public API, admin routes, payments, and frontend revalidation.
- `mobile/`: Flutter storefront client using `ApiService` plus domain services; avoid scattering endpoint strings across screens/providers.
- `recommendation-service/`: Flask API plus scheduled training via `server.py`; treat `recommender_model.pkl` as an artifact, not source.

## Environment Gotchas
- Frontend `.env.example` uses `__HOST__` placeholders and separates `NEXT_PUBLIC_API_URL` from `INTERNAL_API_URL`; keep that split if changing server-side fetch behavior.
- Backend local checkout can return a mock Midtrans token when server keys are absent in local/debug mode; see `backend/README.md` before assuming payment is broken.
- Mobile needs both `MITOLOGI_API_BASE_URL` and often `MITOLOGI_STORAGE_BASE_URL` for real backend runs.

## Do Not Guess
- Do not use `vendor/`, `.next/`, `storage/framework/`, `__pycache__/`, or generated Flutter platform folders as pattern sources.
- Do not assume the mobile test suite is empty; there are focused tests under `mobile/test/`.
- Do not copy old `8011/5011` port assumptions into new code or docs without verifying the active config first.
