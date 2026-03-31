<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['view', 'cart', 'purchase', 'wishlist']);
            $table->integer('score')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_interactions');
    }
};
