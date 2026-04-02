<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\AuthController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'store']);
});

Route::post('logout', [\App\Http\Controllers\AuthController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications/latest', [DashboardController::class, 'getNotifications'])->name('notifications.latest');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    // Product Reviews Management
    Route::post('products/{product}/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('products.reviews.reply');
    Route::patch('products/{product}/reviews/{review}/toggle', [ReviewController::class, 'toggleVisibility'])->name('products.reviews.toggle');
    Route::delete('products/{product}/reviews/{review}', [ReviewController::class, 'destroy'])->name('products.reviews.destroy');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/approve-refund', [\App\Http\Controllers\Admin\OrderController::class, 'approveRefund'])->name('orders.approve-refund');
    Route::post('orders/{order}/reject-refund', [\App\Http\Controllers\Admin\OrderController::class, 'rejectRefund'])->name('orders.reject-refund');
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);

    // Sales Report
    Route::get('/sales-report', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('sales-report.index');
    Route::get('/sales-report/export', [\App\Http\Controllers\Admin\SalesReportController::class, 'export'])->name('sales-report.export');

    // SPK & ML Reports
    Route::get('/ml-reports', [\App\Http\Controllers\Admin\MlReportController::class, 'index'])->name('ml-reports.index');
    Route::post('/ml-reports/retrain', [\App\Http\Controllers\Admin\MlReportController::class, 'retrain'])->name('ml-reports.retrain');

    // Site Settings
    // Site Settings (Split per Page)
    Route::get('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('settings.update');

    Route::get('/beranda', [\App\Http\Controllers\Admin\SiteSettingController::class, 'beranda'])->name('beranda.index');
    Route::put('/beranda', [\App\Http\Controllers\Admin\SiteSettingController::class, 'updateBeranda'])->name('beranda.update');

    Route::get('/tentang-kami', [\App\Http\Controllers\Admin\SiteSettingController::class, 'tentangKami'])->name('tentang-kami.index');
    Route::put('/tentang-kami', [\App\Http\Controllers\Admin\SiteSettingController::class, 'updateTentangKami'])->name('tentang-kami.update');

    Route::get('/layanan', [\App\Http\Controllers\Admin\SiteSettingController::class, 'layanan'])->name('layanan.index');
    Route::put('/layanan', [\App\Http\Controllers\Admin\SiteSettingController::class, 'updateLayanan'])->name('layanan.update');

    Route::get('/kontak', [\App\Http\Controllers\Admin\SiteSettingController::class, 'kontak'])->name('kontak.index');
    Route::put('/kontak', [\App\Http\Controllers\Admin\SiteSettingController::class, 'updateKontak'])->name('kontak.update');

    // App Configuration (Environment Variables)
    Route::get('/app-configuration', [\App\Http\Controllers\Admin\AppConfigurationController::class, 'index'])->name('app-configuration.index');
    Route::put('/app-configuration/{group}', [\App\Http\Controllers\Admin\AppConfigurationController::class, 'update'])->name('app-configuration.update');
    Route::post('/app-configuration/test/{service}', [\App\Http\Controllers\Admin\AppConfigurationController::class, 'test'])->name('app-configuration.test');

    // === Beranda Sub-Resources ===
    Route::prefix('beranda')->name('beranda.')->group(function () {
        Route::resource('hero-slides', \App\Http\Controllers\Admin\HeroSlideController::class);
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
        Route::resource('portfolio', \App\Http\Controllers\Admin\PortfolioItemController::class)->parameters(['portfolio' => 'portfolio_item']);
        Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);
        Route::resource('product-pricings', \App\Http\Controllers\Admin\ProductPricingController::class);
        Route::resource('materials', \App\Http\Controllers\Admin\MaterialController::class);
        Route::resource('order-steps', \App\Http\Controllers\Admin\OrderStepController::class);
    });

    // === Tentang Kami Sub-Resources ===
    Route::prefix('tentang-kami')->name('tentang-kami.')->group(function () {
        Route::resource('team-members', \App\Http\Controllers\Admin\TeamMemberController::class);
        Route::resource('facilities', \App\Http\Controllers\Admin\FacilityController::class);
    });

    // === Layanan Sub-Resources ===
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::resource('printing-methods', \App\Http\Controllers\Admin\PrintingMethodController::class);
    });

    // RFID Management
    Route::resource('rfids', \App\Http\Controllers\Admin\RfidController::class)->only(['index', 'show']);
});

Route::get('/admin/orders/{order}/invoice', [InvoiceController::class, 'show'])
    ->name('invoices.show')
    ->middleware('auth'); // Ensure only authenticated users can view, might need stricter middleware

if (app()->environment('local')) {
    Route::get('/storage/{path}', function (string $path) {
        $storageRoot = realpath(storage_path('app/public'));
        if ($storageRoot === false) {
            abort(404);
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');
        if ($normalizedPath === '' || str_contains($normalizedPath, "\0")) {
            abort(404);
        }

        $candidatePath = realpath($storageRoot.DIRECTORY_SEPARATOR.$normalizedPath);
        if ($candidatePath === false || ! is_file($candidatePath)) {
            abort(404);
        }

        $rootWithSlash = rtrim($storageRoot, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        if (! str_starts_with($candidatePath, $rootWithSlash)) {
            abort(403);
        }

        return response()->file($candidatePath);
    })->where('path', '.*');
}
