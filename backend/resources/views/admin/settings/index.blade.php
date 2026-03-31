<x-admin-layout>
    <x-admin-header title="Pengaturan Website & SEO" />



    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            {{-- General Identity --}}
            <x-admin-card>
                <h3 class="text-2xl font-display font-semibold text-mitologi-navy mb-6">Identitas Website</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="site_name" :value="__('Nama Situs')" />
                        <x-text-input id="site_name" class="block mt-1 w-full" type="text" name="site_name" :value="$settings['site_name'] ?? ''" />
                    </div>
                    <div>
                        <x-input-label for="site_tagline" :value="__('Tagline')" />
                        <x-text-input id="site_tagline" class="block mt-1 w-full" type="text" name="site_tagline" :value="$settings['site_tagline'] ?? ''" />
                    </div>
                    <div class="md:col-span-2">
                         <x-input-label for="site_description" :value="__('Deskripsi Singkat')" />
                         <x-textarea-input id="site_description" name="site_description" rows="3" class="block mt-1 w-full">{{ $settings['site_description'] ?? '' }}</x-textarea-input>
                    </div>
    <div>
        <x-input-label for="site_logo" :value="__('Logo Website')" class="mb-2" />
        <x-file-upload name="site_logo" label="Upload Logo" :preview="$settings['site_logo'] ?? null" />
    </div>
</div>
</x-admin-card>

{{-- SEO Settings --}}
<x-admin-card>
<h3 class="text-2xl font-display font-semibold text-mitologi-navy mb-6">Konfigurasi SEO</h3>
<div class="space-y-4">
    <div>
        <x-input-label for="seo_meta_title" :value="__('Judul Meta Default')" />
        <x-text-input id="seo_meta_title" class="block mt-1 w-full" type="text" name="seo_meta_title" :value="$settings['seo_meta_title'] ?? ''" placeholder="Mitologi Clothing - Vendor Konveksi Terbaik" />
    </div>
    <div>
        <x-input-label for="seo_meta_description" :value="__('Deskripsi Meta Default')" />
        <x-textarea-input id="seo_meta_description" name="seo_meta_description" rows="3" class="block mt-1 w-full">{{ $settings['seo_meta_description'] ?? '' }}</x-textarea-input>
    </div>
    <div>
        <x-input-label for="seo_og_image" :value="__('Gambar OG (Pratinjau Bagikan)')" class="mb-2" />
        <x-file-upload name="seo_og_image" label="Unggah Gambar OG (1200x630 direkomendasikan)" :preview="$settings['seo_og_image'] ?? null" />
    </div>
                </div>
            </x-admin-card>
        </div>

        {{-- Sticky Save --}}
        <div class="sticky bottom-6 mt-8 flex justify-end z-50">
            <x-primary-button class="py-3 px-8 text-base shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </x-primary-button>
        </div>
    </form>
</x-admin-layout>
