<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Repository Service Provider
 *
 * Binds repository implementations to their interfaces.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Product Repository
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository(new Product);
        });

        // Bind Cart Repository
        $this->app->bind(CartRepository::class, function ($app) {
            return new CartRepository(new Cart);
        });

        // Bind Order Repository
        $this->app->bind(OrderRepository::class, function ($app) {
            return new OrderRepository(new Order);
        });

        // Bind User Repository
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository(new User);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Any boot logic if needed
    }
}
