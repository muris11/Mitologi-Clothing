<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Order Repository
 *
 * Handles all database operations for Order model.
 */
class OrderRepository extends BaseRepository
{
    /**
     * Relations to eager load by default
     */
    protected array $defaultRelations = ['items', 'user'];

    /**
     * Cache key prefix
     */
    protected const CACHE_PREFIX = 'orders.';

    /**
     * Cache duration in seconds (5 minutes)
     */
    protected const CACHE_DURATION = 300;

    /**
     * Constructor
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * Find order by order number
     *
     * @param  string  $orderNumber  Order number (e.g., ORD-2024-001)
     */
    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return Cache::remember(
            self::CACHE_PREFIX.'number.'.$orderNumber,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with($this->defaultRelations)
                ->where('order_number', $orderNumber)
                ->first()
        );
    }

    /**
     * Get orders by user ID
     *
     * @param  int  $userId  User ID
     * @param  string  $sort  Sort order (default: 'desc')
     */
    public function getByUserId(int $userId, string $sort = 'desc'): Collection
    {
        return Cache::remember(
            self::CACHE_PREFIX.'user.'.$userId.'.'.$sort,
            self::CACHE_DURATION,
            fn () => $this->model
                ->with($this->defaultRelations)
                ->where('user_id', $userId)
                ->orderBy('created_at', $sort)
                ->get()
        );
    }

    /**
     * Get orders by status
     *
     * @param  string  $status  Order status
     */
    public function getByStatus(string $status): Collection
    {
        return $this->model
            ->with($this->defaultRelations)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending orders
     */
    public function getPendingOrders(): Collection
    {
        return $this->getByStatus('pending');
    }

    /**
     * Get paid orders
     */
    public function getPaidOrders(): Collection
    {
        return $this->getByStatus('paid');
    }

    /**
     * Get orders by date range
     *
     * @param  string  $startDate  Start date (Y-m-d)
     * @param  string  $endDate  End date (Y-m-d)
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Update order status
     *
     * @param  string  $orderNumber  Order number
     * @param  string  $status  New status
     * @param  array  $additionalData  Additional data to update
     */
    public function updateStatus(string $orderNumber, string $status, array $additionalData = []): ?Order
    {
        $order = $this->findByOrderNumber($orderNumber);

        if ($order) {
            $updateData = array_merge(['status' => $status], $additionalData);
            $order->update($updateData);

            // Clear cache
            Cache::forget(self::CACHE_PREFIX.'number.'.$orderNumber);
            Cache::forget(self::CACHE_PREFIX.'user.'.$order->user_id);

            return $order->fresh();
        }

        return null;
    }

    /**
     * Generate unique order number
     */
    public function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $year = date('Y');
        $count = $this->model
            ->whereYear('created_at', date('Y'))
            ->count();

        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$sequence}";
    }

    /**
     * Get order statistics
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->count(),
            'pending' => $this->model->where('status', 'pending')->count(),
            'paid' => $this->model->where('status', 'paid')->count(),
            'completed' => $this->model->where('status', 'completed')->count(),
            'cancelled' => $this->model->where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Clear order cache
     *
     * @param  string  $orderNumber  Order number
     * @param  int|null  $userId  User ID
     */
    public function clearCache(string $orderNumber, ?int $userId = null): void
    {
        Cache::forget(self::CACHE_PREFIX.'number.'.$orderNumber);

        if ($userId) {
            Cache::forget(self::CACHE_PREFIX.'user.'.$userId);
        }
    }
}
