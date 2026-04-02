<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('carts')) {
            Schema::table('carts', function (Blueprint $table) {
                // Check if index already exists before creating
                if (! Schema::hasIndex('carts', 'carts_session_id_index')) {
                    $table->index('session_id', 'carts_session_id_index');
                }
            });
        }

        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                // Check if index already exists before creating
                if (! Schema::hasIndex('cart_items', 'cart_items_cart_id_variant_id_unique')) {
                    $table->unique(['cart_id', 'variant_id'], 'cart_items_cart_id_variant_id_unique');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['session_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'variant_id']);
        });
    }
};
