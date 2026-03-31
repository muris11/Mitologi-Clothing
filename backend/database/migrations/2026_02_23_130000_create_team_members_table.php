<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position'); // e.g. "Founder Mitologi Clothing", "Sewing", "Cutting Tshirt"
            $table->string('photo')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('team_members')->nullOnDelete();
            $table->unsignedTinyInteger('level')->default(0); // 0=founder, 1=manager, 2=staff, 3=sub
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
