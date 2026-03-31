<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'orders_status_index');
            $table->index('created_at', 'orders_created_at_index');
            $table->index(['user_id', 'created_at'], 'orders_user_created_index');
            $table->index('midtrans_transaction_id', 'orders_midtrans_txn_index');
            $table->index('midtrans_order_id', 'orders_midtrans_order_index');
        });

        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_hidden', 'products_is_hidden_index');
            $table->index('created_at', 'products_created_at_index');
            $table->index(['is_hidden', 'created_at'], 'products_visible_created_index');
        });

        // Product reviews table indexes
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->index('is_visible', 'product_reviews_is_visible_index');
            $table->index(['product_id', 'is_visible'], 'product_reviews_product_visible_index');
        });

        // Order items table indexes
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id', 'order_items_product_id_index');
            $table->index('order_id', 'order_items_order_id_index');
        });

        // Product variants table indexes
        Schema::table('product_variants', function (Blueprint $table) {
            $table->index('product_id', 'product_variants_product_id_index');
        });

        // Cart items table indexes
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('variant_id', 'cart_items_variant_id_index');
        });

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index('handle', 'categories_handle_index');
        });
    }

    public function down(): void
    {
        // Orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_index');
            $table->dropIndex('orders_created_at_index');
            $table->dropIndex('orders_user_created_index');
            $table->dropIndex('orders_midtrans_txn_index');
            $table->dropIndex('orders_midtrans_order_index');
        });

        // Products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_is_hidden_index');
            $table->dropIndex('products_created_at_index');
            $table->dropIndex('products_visible_created_index');
        });

        // Product reviews table
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropIndex('product_reviews_is_visible_index');
            $table->dropIndex('product_reviews_product_visible_index');
        });

        // Order items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_product_id_index');
            $table->dropIndex('order_items_order_id_index');
        });

        // Product variants table
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex('product_variants_product_id_index');
        });

        // Cart items table
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('cart_items_variant_id_index');
        });

        // Categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_handle_index');
        });
    }
};
