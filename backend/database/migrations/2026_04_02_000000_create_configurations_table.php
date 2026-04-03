<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->string('type')->default('text'); // text, textarea, password, email, number
            $table->boolean('is_sensitive')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('group');
            $table->index('key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
