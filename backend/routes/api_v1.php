<?php

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\InteractionController;
use App\Http\Controllers\Api\LandingPageController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderStepController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Version 1 Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api/v1/ automatically by bootstrap/app.php
| This provides a clean versioning structure for future API updates.
*/

/*
|--------------------------------------------------------------------------
| Public API Routes (v1)
|--------------------------------------------------------------------------
*/

Route::get('/landing-page', [LandingPageController::class, 'index']);
Route::get('/site-settings', [SiteSettingController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/best-sellers', [ProductController::class, 'bestSellers']);
Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);
Route::get('/products/{handle}', [ProductController::class, 'show']);
Route::get('/products/{handle}/reviews', [ReviewController::class, 'index']);
Route::get('/products/{id}/recommendations', [ProductController::class, 'recommendations'])->where('id', '[0-9]+');
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{handle}', [CategoryController::class, 'show']);
Route::get('/materials', [MaterialController::class, 'index']);
Route::get('/order-steps', [OrderStepController::class, 'index']);

// Serve team member photos (bypass artisan serve symlink bugs on Windows)
Route::get('/team-members/{id}/photo', [LandingPageController::class, 'teamMemberPhoto']);

Route::prefix('collections')->group(function () {
    Route::get('/', [CollectionController::class, 'index']);
    Route::get('/{handle}', [CollectionController::class, 'show']);
    Route::get('/{handle}/products', [CollectionController::class, 'products']);
});

Route::get('/menus/{handle}', [MenuController::class, 'show']);
Route::post('/interactions/batch', [InteractionController::class, 'storeBatch']);

Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index']);
    Route::get('/{handle}', [PageController::class, 'show']);
});

Route::prefix('portfolios')->group(function () {
    Route::get('/', [PortfolioController::class, 'index']);
    Route::get('/{slug}', [PortfolioController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Cart Routes (v1) - session-based, no auth required
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {
    Route::post('/', [CartController::class, 'create']);
    Route::get('/', [CartController::class, 'show']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{id}', [CartController::class, 'updateItem']);
    Route::delete('/items/{id}', [CartController::class, 'removeItem']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes (v1)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes (v1) - Auth Required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::put('/password', [ProfileController::class, 'updatePassword']);
        Route::post('/avatar', [ProfileController::class, 'updateAvatar']);

        // Address Routes
        Route::get('/addresses', [AddressController::class, 'index']);
        Route::post('/addresses', [AddressController::class, 'store']);
        Route::put('/addresses/{address}', [AddressController::class, 'update']);
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{orderNumber}', [OrderController::class, 'show']);
        Route::post('/{orderNumber}/pay', [CheckoutController::class, 'pay']);
        Route::post('/{orderNumber}/confirm-payment', [OrderController::class, 'confirmPayment']);
        Route::post('/{orderNumber}/request-refund', [OrderController::class, 'requestRefund']);
    });

    Route::post('/checkout', [CheckoutController::class, 'process']);
    Route::get('/recommendations', [RecommendationController::class, 'forUser']);

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/{productId}', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);

    // Review Routes
    Route::post('/products/{handle}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/reports/top-products', [ReportController::class, 'topProducts']);
        Route::get('/reports/trending', [ReportController::class, 'trendingProducts']);
        Route::get('/reports/stock-recommendations', [ReportController::class, 'stockRecommendations']);
        Route::get('/ml/status', [ReportController::class, 'mlStatus']);
        Route::post('/ml/retrain', [ReportController::class, 'retrainModel']);
        // RFID Status Endpoint
        Route::get('/rfid/status', [\App\Http\Controllers\Admin\RfidController::class, 'apiStatus']);
    });
});

// Internal ML Services Endpoint (Allowed globally for local Python ML polling)
Route::get('/ml/export-data', [ReportController::class, 'exportTrainingData']);

/*
|--------------------------------------------------------------------------
| Midtrans Webhook (v1) - no auth, verified by Midtrans signature
|--------------------------------------------------------------------------
*/
Route::post('/checkout/notification', [CheckoutController::class, 'notification']);

/*
|--------------------------------------------------------------------------
| AI / Chatbot Routes (v1)
|--------------------------------------------------------------------------
*/
Route::post('/chatbot', [\App\Http\Controllers\Api\ChatbotController::class, 'chat']);
