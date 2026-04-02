<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\HeroSlide;
use App\Models\Material;
use App\Models\OrderStep;
use App\Models\PortfolioItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    use \App\Traits\FormatsProduct;

    private function toPublicStorageUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        $normalized = ltrim(str_replace('\\', '/', $trimmed), '/');
        $normalized = preg_replace('#^storage/+#', '', $normalized) ?? $normalized;
        $normalized = preg_replace('#^storage/+#', '', $normalized) ?? $normalized;

        // Build storage URL using request host instead of APP_URL
        $host = request()->getHost();
        $port = request()->getPort();
        $scheme = request()->getScheme();

        // Don't use localhost/127.0.0.1 for mobile access
        if ($host === '127.0.0.1' || $host === 'localhost') {
            $host = '192.168.1.5';
        }

        $baseUrl = $port ? "$scheme://$host:$port" : "$scheme://$host";

        return "$baseUrl/storage/$normalized";
    }

    public function index(): JsonResponse
    {
        // Cache EVERYTHING for 1 hour
        $responseData = Cache::remember('api.landing_page_data_v2', 3600, function () {
            // Fetch and format settings INSIDE the cache closure
            $settings = SiteSetting::all();

            $formattedSettings = $settings->groupBy('group')->map(function ($group) {
                return $group->pluck('value', 'key');
            })->toArray();

            $jsonKeys = ['services_data', 'company_values_data', 'guarantees_data', 'garansi_bonus_data', 'pricing_plastisol_data', 'pricing_addons_data'];
            $imageKeys = ['site_logo', 'about_image', 'founder_photo', 'service_1_image', 'service_2_image', 'service_3_image', 'seo_og_image'];

            foreach ($imageKeys as $imageKey) {
                $setting = $settings->firstWhere('key', $imageKey);
                if ($setting && $setting->value) {
                    if (isset($formattedSettings[$setting->group][$imageKey])) {
                        $formattedSettings[$setting->group][$imageKey] = $this->toPublicStorageUrl($setting->value);
                    }
                }
            }

            foreach ($settings as $setting) {
                if (in_array($setting->key, $jsonKeys)) {
                    $decoded = json_decode($setting->value, true);

                    if ($setting->key === 'services_data' && is_array($decoded)) {
                        foreach ($decoded as &$item) {
                            if (isset($item['image']) && $item['image']) {
                                $item['image'] = $this->toPublicStorageUrl($item['image']);
                            }
                        }
                    }

                    if (in_array($setting->key, ['pricing_plastisol_data', 'pricing_addons_data'])) {
                        if (isset($formattedSettings[$setting->group][$setting->key])) {
                            $formattedSettings[$setting->group][$setting->key] = is_string($formattedSettings[$setting->group][$setting->key]) ? json_decode($formattedSettings[$setting->group][$setting->key], true) : $formattedSettings[$setting->group][$setting->key];
                        }
                    } else {
                        // Store in group and also extract to root level for frontend compatibility
                        $formattedSettings[$setting->group][$setting->key] = $decoded;
                        $formattedSettings[$setting->key] = $decoded;
                    }
                }
            }

            return [
                'hero_slides' => HeroSlide::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn ($slide) => [
                        'id' => $slide->id,
                        'title' => $slide->title,
                        'subtitle' => $slide->subtitle,
                        'image_url' => $this->toPublicStorageUrl($slide->image_url),
                        'cta_text' => $slide->cta_text,
                        'cta_link' => $slide->cta_link,
                        'sort_order' => $slide->sort_order,
                        'is_active' => $slide->is_active,
                    ]),
                'features' => Feature::where('is_active', true)->orderBy('sort_order')->limit(6)->get(),
                'testimonials' => Testimonial::where('is_active', true)->orderBy('created_at', 'desc')->limit(10)->get(),
                'materials' => Material::orderBy('sort_order')->get(),
                'portfolio_items' => PortfolioItem::where('is_active', true)->orderBy('sort_order')->limit(8)->get(),
                'order_steps' => OrderStep::orderBy('sort_order')->get(),
                'partners' => \App\Models\Partner::where('is_active', true)->orderBy('sort_order')->get(),
                'printing_methods' => \App\Models\PrintingMethod::where('is_active', true)->orderBy('sort_order')->get(),
                'product_pricings' => \App\Models\ProductPricing::where('is_active', true)->orderBy('sort_order')->get(),
                'facilities' => \App\Models\Facility::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn ($facility) => [
                        'id' => $facility->id,
                        'name' => $facility->name,
                        'description' => $facility->description,
                        'image' => $this->toPublicStorageUrl($facility->image),
                        'is_active' => (bool) $facility->is_active,
                        'sort_order' => (int) $facility->sort_order,
                    ]),
                'cta' => [
                    'title' => SiteSetting::get('cta_title', 'Siap Memesan Seragam Impian Anda?'),
                    'subtitle' => SiteSetting::get('cta_subtitle', 'Konsultasikan kebutuhan seragam Anda dengan tim ahli kami sekarang juga.'),
                    'button_text' => SiteSetting::get('cta_button_text', 'Hubungi Kami via WhatsApp'),
                    'button_link' => SiteSetting::get('cta_button_link', 'https://wa.me/6281322170902'),
                ],
                'site_settings' => $formattedSettings,
                'categories' => Category::where('is_active', true)
                    ->withCount('products')
                    ->orderBy('name')
                    ->get()
                    ->map(fn ($category) => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'handle' => $category->handle,
                        'description' => $category->description,
                        'image' => $this->toPublicStorageUrl($category->image),
                        'productsCount' => $category->products_count ?? 0,
                    ]),
                'new_arrivals' => Product::with(['variants', 'images'])
                    ->where('is_hidden', false)
                    ->latest()
                    ->limit(8)
                    ->get()
                    ->map(fn ($p) => $this->formatProduct($p)),
                'best_sellers' => Product::with(['variants', 'images'])
                    ->where('is_hidden', false)
                    ->withCount('interactions')
                    ->orderBy('interactions_count', 'desc')
                    ->limit(8)
                    ->get()
                    ->map(fn ($p) => $this->formatProduct($p)),
                'team_members' => TeamMember::where('is_active', true)
                    ->orderBy('level')
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn ($m) => [
                        'id' => $m->id,
                        'name' => $m->name,
                        'position' => $m->position,
                        'photo_url' => $m->photo_url,
                        'parent_id' => $m->parent_id,
                        'level' => $m->level,
                        'sort_order' => $m->sort_order,
                    ]),
            ];
        });

        return $this->successResponse($this->toCamelCase($responseData), 'Data landing page berhasil diambil');
    }

    public function teamMemberPhoto(int $id)
    {
        $member = TeamMember::find($id);
        if (! $member || ! $member->photo) {
            abort(404);
        }

        $path = storage_path('app/public/'.$member->photo);
        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
