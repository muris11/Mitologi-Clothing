<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('chatbot', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return env('FRONTEND_URL', 'http://localhost:3000')."/shop/reset-password?token={$token}&email={$notifiable->getEmailForPasswordReset()}";
        });

        \Illuminate\Support\Facades\View::composer(
            'layouts.admin',
            \App\View\Composers\AdminNotificationComposer::class
        );

        // Register Observers for Cache Invalidation
        $modelsToObserve = [
            \App\Models\SiteSetting::class,
            \App\Models\HeroSlide::class,
            \App\Models\Feature::class,
            \App\Models\Testimonial::class,
            \App\Models\Material::class,
            \App\Models\PortfolioItem::class,
            \App\Models\OrderStep::class,
            \App\Models\Category::class,
            \App\Models\Product::class,
            \App\Models\TeamMember::class,
        ];

        foreach ($modelsToObserve as $model) {
            $model::observe(\App\Observers\LandingPageDataObserver::class);
        }
    }
}
