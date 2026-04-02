<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    /**
     * General Settings & SEO (Default Index)
     */
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Beranda Settings
     */
    public function beranda()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        $guarantees = isset($settings['guarantees_data']) ? json_decode($settings['guarantees_data'], true) : [];
        $pricingPlastisol = isset($settings['pricing_plastisol_data']) ? json_decode($settings['pricing_plastisol_data'], true) : [];
        $pricingAddons = isset($settings['pricing_addons_data']) ? json_decode($settings['pricing_addons_data'], true) : [];

        // Count active hero slides for info badge in Section 01
        $heroSlidesCount = \App\Models\HeroSlide::where('is_active', true)->count();

        return view('admin.beranda.index', compact(
            'settings',
            'guarantees',
            'pricingPlastisol',
            'pricingAddons',
            'heroSlidesCount'
        ));
    }

    public function updateBeranda(Request $request)
    {
        return $this->updateSettings($request, 'beranda');
    }

    /**
     * Tentang Kami Settings
     */
    public function tentangKami()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('admin.tentang-kami.index', compact('settings'));
    }

    public function updateTentangKami(Request $request)
    {
        return $this->updateSettings($request, 'about');
    }

    /**
     * Layanan Settings
     */
    public function layanan()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        $services = isset($settings['services_data']) ? json_decode($settings['services_data'], true) : [];

        return view('admin.layanan.index', compact('settings', 'services'));
    }

    public function updateLayanan(Request $request)
    {
        return $this->updateSettings($request, 'services');
    }

    /**
     * Kontak Settings
     */
    public function kontak()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('admin.kontak.index', compact('settings'));
    }

    public function updateKontak(Request $request)
    {
        return $this->updateSettings($request, 'contact');
    }

    /**
     * Shared Update Logic
     */
    public function update(Request $request)
    {
        return $this->updateSettings($request);
    }

    private function updateSettings(Request $request, string $group = 'general')
    {
        $data = $request->except(['_token', '_method']);

        // Process Maps Embed - Parse iframe HTML, URL, or coordinates
        if (isset($data['contact_maps_embed']) && ! empty($data['contact_maps_embed'])) {
            $mapsInput = trim($data['contact_maps_embed']);

            // Check if input is iframe HTML
            if (str_contains($mapsInput, '<iframe')) {
                // Extract src URL from iframe
                if (preg_match('/src=["\']([^"\']+)["\']/', $mapsInput, $matches)) {
                    $data['contact_maps_embed'] = $matches[1];
                }
            }
            // Check if input is coordinates (e.g., -6.438597, 108.287315)
            elseif (preg_match('/^[-+]?[0-9]*\.?[0-9]+,\s*[-+]?[0-9]*\.?[0-9]+$/', $mapsInput)) {
                $coords = array_map('trim', explode(',', $mapsInput));
                $data['contact_maps_embed'] = "https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d500!2d{$coords[1]}!3d{$coords[0]}!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjd8MTE2!5e0!3m2!1sen!2sid!4v1";
            }
            // Otherwise assume it's already a URL
        }

        // Process Dynamic Services Data (JSON + Images)
        if ($request->has('services_data')) {
            $services = json_decode($request->input('services_data'), true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($services)) {
                foreach ($services as $index => &$service) {
                    if ($request->hasFile("service_image_{$index}")) {
                        $file = $request->file("service_image_{$index}");
                        $path = $file->store('settings/services', 'public');
                        $service['image'] = $path;
                    }
                }
                unset($service);
                $data['services_data'] = json_encode($services);
            }
        }

        // Process Dynamic Guarantees Data (JSON + Images)
        if ($request->has('guarantees_data')) {
            $guarantees = json_decode($request->input('guarantees_data'), true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($guarantees)) {
                foreach ($guarantees as $index => &$guarantee) {
                    if ($request->hasFile("guarantee_image_{$index}")) {
                        $file = $request->file("guarantee_image_{$index}");
                        $path = $file->store('settings/guarantees', 'public');
                        $guarantee['image'] = $path;
                    }
                }
                unset($guarantee);
                $data['guarantees_data'] = json_encode($guarantees);
            }
        }

        // Handle File Uploads (Explicitly)
        foreach ($request->allFiles() as $key => $file) {
            // Skip dynamic lists images
            if (str_starts_with($key, 'service_image_') || str_starts_with($key, 'guarantee_image_')) {
                continue;
            }
            $path = $file->store('settings', 'public');
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $path, 'type' => 'image', 'group' => $group]);

            // Remove from data so we don't process it again as text (though unlikely to be in except())
            if (isset($data[$key])) {
                unset($data[$key]);
            }
        }

        foreach ($data as $key => $value) {
            // Skip individual service/guarantee image inputs as they are handled in the JSON processing above
            if (str_starts_with($key, 'service_image_') || str_starts_with($key, 'guarantee_image_')) {
                continue;
            }
            // Skip if this key was already handled as a file (double check)
            if ($request->hasFile($key)) {
                continue;
            }

            // Handle Social Media URLs (store as full URL even if user inputs username)
            if ($key === 'social_facebook' && $value) {
                $username = str_replace(['https://', 'http://', 'www.', 'facebook.com/'], '', $value);
                $value = 'https://facebook.com/'.ltrim($username, '/');
            } elseif ($key === 'social_instagram' && $value) {
                $username = str_replace(['https://', 'http://', 'www.', 'instagram.com/'], '', $value);
                $value = 'https://instagram.com/'.ltrim($username, '/');
            } elseif ($key === 'social_twitter' && $value) {
                $username = str_replace(['https://', 'http://', 'www.', 'x.com/', 'twitter.com/'], '', $value);
                $value = 'https://x.com/'.ltrim($username, '/');
            } elseif ($key === 'social_tiktok' && $value) {
                $username = str_replace(['https://', 'http://', 'www.', 'tiktok.com/@', 'tiktok.com/'], '', $value);
                $value = 'https://tiktok.com/@'.ltrim($username, '/');
            } elseif ($key === 'social_shopee' && $value) {
                $username = str_replace(['https://', 'http://', 'www.', 'shopee.co.id/'], '', $value);
                $value = 'https://shopee.co.id/'.ltrim($username, '/');
            }

            $setting = SiteSetting::firstOrNew(['key' => $key]);
            $setting->value = $value;
            $setting->group = $group;
            $setting->save();
        }

        // Handle checkboxes for enabled status (only if we are on the contact page or global update where these fields are present)
        // If we are on a specific page that DOES NOT have these fields, we shouldn't accidentally disable them.
        // However, HTML forms don't send unchecked checkboxes.
        // The safest way is to only set to '0' if the field is expected but missing.
        // For now, let's assume the update request comes with all relevant fields for that page.
        // If we are updating "Kontak", we expect social fields. If updating "Beranda", we don't.

        // We can check if the request *could* have contained them.
        // A simple heuristic: if 'social_instagram' text input is present (even empty), then we should check for 'social_instagram_enabled'.
        // If 'social_instagram' key exists in input (it will be null or string), then we process the checkbox.

        $socials = ['instagram', 'tiktok', 'facebook', 'shopee', 'twitter'];
        foreach ($socials as $social) {
            // Check if the TEXT input for this social was submitted (meaning we are on the Kontak page)
            if ($request->has('social_'.$social)) {
                $enabledKey = 'social_'.$social.'_enabled';
                if (! $request->has($enabledKey)) {
                    SiteSetting::updateOrCreate(['key' => $enabledKey], ['value' => '0']);
                }
            }
        }

        FrontendCacheService::revalidate(['site-settings', 'landing-page']);
        Cache::forget('api.landing_page_data_v2');
        Cache::forget('api.site_settings');

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
