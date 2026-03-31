<x-admin-layout>
    <x-admin-header
        title="Edit Partner"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Partner', 'url' => route('admin.beranda.partners.index')], ['title' => 'Edit: ' . $partner->name]]"
    />

    <form action="{{ route('admin.beranda.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Data Partner
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Partner / Klien')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $partner->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="website_url" :value="__('URL Website (Opsional)')" />
                            <x-text-input id="website_url" class="block mt-1 w-full" type="url" name="website_url" :value="old('website_url', $partner->website_url)" />
                            <x-input-error :messages="$errors->get('website_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Pendek (Opsional)')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-mitologi-gold dark:focus:border-mitologi-gold focus:ring-mitologi-gold dark:focus:ring-mitologi-gold rounded-md shadow-sm">{{ old('description', $partner->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', $partner->sort_order)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>
                    </div>
                </x-admin-card>
            </div>

            <div class="space-y-8">
                <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Media & Status</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="logo" :value="__('Logo Partner')" class="mb-2" />
                            @if($partner->logo)
                                <div class="mb-4 bg-gray-50 dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Logo Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $partner->logo) }}" alt="Logo" class="h-16 w-auto object-contain bg-white p-1 rounded border">
                                </div>
                            @endif
                            <x-file-upload name="logo" label="Upload Logo Baru (Opsional)" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $partner->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:bg-mitologi-navy after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </div>
                    </div>
                </x-admin-card>

                <x-admin-card>
                    <x-primary-button class="w-full justify-center text-lg">Perbarui</x-primary-button>
                </x-admin-card>
            </div>
        </div>
    </form>
</x-admin-layout>

