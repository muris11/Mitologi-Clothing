<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\RecommendationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $service = app(RecommendationService::class);

    try {
        $service->train();
        cache(['ml_last_trained_at' => now()], now()->addDay());
        \Log::info('ML model retrained successfully');
    } catch (\Exception $e) {
        \Log::error('ML retrain failed: ' . $e->getMessage());
    }
})->dailyAt('03:00');

Schedule::command('orders:release-expired')->everyFiveMinutes();
