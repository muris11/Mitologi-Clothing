<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->command->info('🌱 Starting full database seeding...');
    $this->command->newLine();

    // ====================================================================
    // STEP 1: Disable FK constraints for clean re-seeding
    // ====================================================================
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    $this->command->warn('  ⚠  Truncating all tables...');

    // Order matters: children before parents
    DB::table('user_interactions')->truncate();
    DB::table('product_reviews')->truncate();
    DB::table('order_items')->truncate();
    DB::table('shipping_addresses')->truncate();
    DB::table('orders')->truncate();
    DB::table('cart_items')->truncate();
    DB::table('carts')->truncate();
    DB::table('variant_options')->truncate();
    DB::table('product_variants')->truncate();
    DB::table('product_images')->truncate();
    DB::table('product_options')->truncate();
    DB::table('category_product')->truncate();
    DB::table('products')->truncate();
    DB::table('categories')->truncate();
    DB::table('site_settings')->truncate();
    DB::table('team_members')->truncate();
    DB::table('hero_slides')->truncate();
    DB::table('features')->truncate();
    DB::table('materials')->truncate();
    DB::table('testimonials')->truncate();
    DB::table('order_steps')->truncate();
    DB::table('portfolio_items')->truncate();
    DB::table('printing_methods')->truncate();
    DB::table('product_pricings')->truncate();
    DB::table('facilities')->truncate();
    DB::table('partners')->truncate();
    DB::table('menus')->truncate();
    DB::table('pages')->truncate();
    DB::table('webhook_events')->truncate();

    // Keep users intact unless you want fresh customers too
    // DB::table('users')->truncate();

    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    $this->command->info('  ✅ Tables truncated.');
    $this->command->newLine();

    // ====================================================================
    // STEP 2: Run all seeders in dependency order
    // ====================================================================
    $this->call([
      // 1. Site settings (no deps)
      SiteSettingsSeeder::class,

      // 2. Users (no deps)
      UserSeeder::class,

      // 3. Categories (no deps)
      CategorySeeder::class,

      // 4. Products (depends on Category)
      ProductSeeder::class,

      // 5. Orders (depends on Product + User)
      OrderSeeder::class,

      // 6. Product reviews (depends on Product + User + Order)
      ProductReviewSeeder::class,

      // 7. Navigation menus & static pages (no deps)
      MenuSeeder::class,

      // 8. Team structure (no deps)
      TeamMemberSeeder::class,

      // 9. User interactions for AI recommendation (depends on User + Product)
      UserInteractionSeeder::class,

      // 10. Landing-page content: hero slides, features, materials,
      //     testimonials, order steps, portfolio items
      LandingPageSeeder::class,

      // 11. Partner logos (no deps)
      PartnerSeeder::class,

      // 12. Printing methods for Layanan page (no deps)
      PrintingMethodSeeder::class,

      // 13. Product pricelist cards for Beranda (no deps)
      ProductPricingSeeder::class,

      // 14. Workshop facilities for Tentang Kami (no deps)
      FacilitySeeder::class,

      // 15. AI recommendation bootstrap data (depends on User + Product)
      AiDataSeeder::class,
    ]);

    // ====================================================================
    // STEP 3: Done
    // ====================================================================
    $this->command->newLine();
    $this->command->info('🎉 All seeders completed successfully!');
    $this->command->newLine();
    $this->command->table(
      ['Table', 'Records'],
      [
        ['site_settings', DB::table('site_settings')->count()],
        ['users', DB::table('users')->count()],
        ['categories', DB::table('categories')->count()],
        ['products', DB::table('products')->count()],
        ['product_variants', DB::table('product_variants')->count()],
        ['orders', DB::table('orders')->count()],
        ['product_reviews', DB::table('product_reviews')->count()],
        ['team_members', DB::table('team_members')->count()],
        ['hero_slides', DB::table('hero_slides')->count()],
        ['features', DB::table('features')->count()],
        ['materials', DB::table('materials')->count()],
        ['testimonials', DB::table('testimonials')->count()],
        ['order_steps', DB::table('order_steps')->count()],
        ['portfolio_items', DB::table('portfolio_items')->count()],
        ['printing_methods', DB::table('printing_methods')->count()],
        ['product_pricings', DB::table('product_pricings')->count()],
        ['facilities', DB::table('facilities')->count()],
        ['partners', DB::table('partners')->count()],
        ['menus', DB::table('menus')->count()],
        ['pages', DB::table('pages')->count()],
        ['user_interactions', DB::table('user_interactions')->count()],
      ]
    );
  }
}
