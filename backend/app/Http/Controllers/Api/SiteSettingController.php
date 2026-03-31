<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
  public function index()
  {
    // Cache site settings forever until invalidated
    $settings = \Illuminate\Support\Facades\Cache::rememberForever('api.site_settings', function () {
      return SiteSetting::all()->pluck('value', 'key');
    });

    // Transform image paths to full URLs if needed, 
    // but frontend usually handles standard paths.
    // Let's ensure full URLs for images for convenience
    $imageKeys = ['site_logo', 'about_image', 'founder_photo', 'service_1_image', 'service_2_image', 'service_3_image', 'seo_og_image'];

    foreach ($imageKeys as $key) {
      if (isset($settings[$key]) && $settings[$key]) {
        $settings[$key] = url(Storage::url($settings[$key]));
      }
    }

    // Process Dynamic Services Data: Decode JSON and fix image paths
    if (isset($settings['services_data'])) {
      $services = json_decode($settings['services_data'], true);
      if (is_array($services)) {
        foreach ($services as &$service) {
          if (isset($service['image']) && $service['image']) {
            $service['image'] = url(Storage::url($service['image']));
          }
        }
        $settings['services_data'] = $services;
      } else {
        $settings['services_data'] = [];
      }
    }

    // Process Dynamic Guarantees Data: Decode JSON
    if (isset($settings['guarantees_data'])) {
      $settings['guarantees_data'] = json_decode($settings['guarantees_data'], true) ?? [];
    }

    // Process Dynamic Company Values Data: Decode JSON
    if (isset($settings['company_values_data'])) {
      $settings['company_values_data'] = json_decode($settings['company_values_data'], true) ?? [];
    }

    // Process Pricing Data: Decode JSON
    if (isset($settings['pricing_plastisol_data'])) {
      $settings['pricing_plastisol_data'] = json_decode($settings['pricing_plastisol_data'], true) ?? [];
    }
    if (isset($settings['pricing_addons_data'])) {
      $settings['pricing_addons_data'] = json_decode($settings['pricing_addons_data'], true) ?? [];
    }

    return response()->json($settings);
  }
}
