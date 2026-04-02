<?php

use App\Models\PortfolioItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->longText('description')->nullable()->after('category');
        });

        // Generate slugs for existing items
        $items = PortfolioItem::all();
        foreach ($items as $item) {
            $slug = Str::slug($item->title);

            // Ensure uniqueness
            $originalSlug = $slug;
            $count = 1;
            while (PortfolioItem::where('slug', $slug)->where('id', '!=', $item->id)->exists()) {
                $slug = $originalSlug.'-'.$count;
                $count++;
            }

            $item->slug = $slug;
            $item->save();
        }

        // Make slug required after backfilling
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description']);
        });
    }
};
