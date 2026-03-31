<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->api(append: [
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
        // Return JSON for all API Exceptions
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                // If it's a Validation Exception, let Laravel handle it normally to return 422 with errors mapping
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'errors' => $e->errors(),
                    ], $e->status);
                }

                // If authentication error
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'message' => 'Unauthenticated.',
                    ], 401);
                }

                // If Rate Limit hit
                if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                    return response()->json([
                        'message' => 'Terlalu banyak request. Silakan coba beberapa saat lagi.',
                    ], 429);
                }

                return response()->json([
                    'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem. Silakan coba lagi nanti.',
                    'code' => $e->getCode() ?: 500,
                ], 500);
            }
        });
    })->create();
