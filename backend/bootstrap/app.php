<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Register API v1 routes with /api/v1/ prefix
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\NormalizeInputCase::class,
            \App\Http\Middleware\SanitizeInput::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));

        $middleware->statefulApi();

        $middleware->trustProxies(
            at: env('TRUSTED_PROXIES', '127.0.0.1'),
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                     \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO,
        );

        $middleware->validateCsrfTokens(except: [
            'api/checkout/notification',
            'api/chatbot',
            'api/interactions/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Return JSON for all API Exceptions with standardized format
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                // If it's a Validation Exception
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'error' => [
                            'code' => 'validation_error',
                            'message' => $e->getMessage(),
                            'details' => $e->errors(),
                        ],
                    ], 422);
                }

                // If authentication error
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'error' => [
                            'code' => 'unauthenticated',
                            'message' => 'Unauthenticated.',
                        ],
                    ], 401);
                }

                // If Rate Limit hit
                if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                    return response()->json([
                        'error' => [
                            'code' => 'rate_limit_exceeded',
                            'message' => 'Terlalu banyak request. Silakan coba beberapa saat lagi.',
                        ],
                    ], 429);
                }

                // If Not Found
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'error' => [
                            'code' => 'not_found',
                            'message' => 'Resource tidak ditemukan.',
                        ],
                    ], 404);
                }

                // If Method Not Allowed
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'error' => [
                            'code' => 'method_not_allowed',
                            'message' => 'Method tidak diizinkan.',
                        ],
                    ], 405);
                }

                // Generic server error
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                return response()->json([
                    'error' => [
                        'code' => 'internal_error',
                        'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem. Silakan coba lagi nanti.',
                    ],
                ], $status ?: 500);
            }
        });
    })->create();
