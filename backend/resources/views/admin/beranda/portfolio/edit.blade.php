<x-admin-layout>
    <x-admin-header
        title="Edit Portfolio"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Portofolio', 'url' => route('admin.beranda.portfolio.index')], ['title' => 'Edit: ' . $portfolioItem->title]]"
    />

    <form action="{{ route('admin.beranda.portfolio.update', $portfolioItem->id) }}" method="POST" enctype="multipart/form-data"
          x-data="{ description: `{{ old('description', $portfolioItem->description) }}` }">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Information -->
                <x-admin-card>
                     <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Informasi Proyek
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="title" :value="__('Judul Portfolio')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $portfolioItem->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Proyek')" />
                            <div class="mt-1">
                                <x-rich-text-editor id="description" name="description" :value="old('description', $portfolioItem->description)" @trix-change="description = $event.target.value" />
                            </div>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', $portfolioItem->sort_order)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500">Semakin kecil angkanya, semakin awal muncul.</p>
                        </div>
                    </div>
                </x-admin-card>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                <!-- Category Card -->
                <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Kategori</h3>
                    <div class="relative">
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 appearance-none cursor-pointer">
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}" {{ old('category', $portfolioItem->category) == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </x-admin-card>
                <!-- Media & Status -->
                <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Media & Status</h3>
                    
                    <div class="space-y-6">
                         <!-- Image Upload -->
                         <div>
                            <x-input-label for="image" :value="__('Gambar Portfolio')" class="mb-2" />
                            <x-file-upload name="image" label="Upload Gambar Proyek" :preview="$portfolioItem->image_url" />
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $portfolioItem->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy dark:peer-focus:ring-mitologi-gold rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-mitologi-navy"></div>
                            </label>
                        </div>
                    </div>
                </x-admin-card>

                <!-- Actions -->
                <x-admin-card>
                    <x-primary-button class="w-full justify-center text-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Perbarui Proyek
                    </x-primary-button>
                    
                     <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-center">
                         <span class="text-xs text-gray-500">Terakhir diubah: {{ $portfolioItem->updated_at->diffForHumans() }}</span>
                    </div>
                </x-admin-card>
            </div>
        </div>
    </form>
</x-admin-layout>

