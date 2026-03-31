<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\{
    Product,
    Cart,
    Order,
    User
};

use App\Repositories\{
    ProductRepository,
    CartRepository,
    OrderRepository,
    UserRepository
};

/**
 * Repository Service Provider
 *
 * Binds repository implementations to their interfaces.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind Product Repository
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository(new Product());
        });

        // Bind Cart Repository
        $this->app->bind(CartRepository::class, function ($app) {
            return new CartRepository(new Cart());
        });

        // Bind Order Repository
        $this->app->bind(OrderRepository::class, function ($app) {
            return new OrderRepository(new Order());
        });

        // Bind User Repository
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository(new User());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Any boot logic if needed
    }
}
