<x-admin-layout>
    <x-admin-header
        title="Tambah Hero Slide"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Hero Slides', 'url' => route('admin.beranda.hero-slides.index')], ['title' => 'Tambah Baru']]"
    />

    <form action="{{ route('admin.beranda.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Text Content -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-8 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-mitologi-gold/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-700"></div>
                    
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Konten Slide
                    </h3>

                    <div class="space-y-6 relative">
                        <div>
                            <x-input-label for="title" :value="__('Judul (Title)')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="Masukkan judul utama slide..." />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="subtitle" :value="__('Sub-Judul (Subtitle)')" />
                            <x-text-input id="subtitle" class="block mt-1 w-full" type="text" name="subtitle" :value="old('subtitle')" placeholder="Tambahkan deskripsi singkat..." />
                            <x-input-error :messages="$errors->get('subtitle')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-8 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-navy rounded-full"></span>
                        Call to Action (Tombol)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="cta_text" :value="__('Teks Tombol')" />
                            <x-text-input id="cta_text" class="block mt-1 w-full" type="text" name="cta_text" :value="old('cta_text')" placeholder="Contoh: Belanja Sekarang" />
                        </div>

                        <div>
                            <x-input-label for="cta_link" :value="__('Link Tujuan')" />
                            <x-text-input id="cta_link" class="block mt-1 w-full" type="text" name="cta_link" :value="old('cta_link')" placeholder="Contoh: /products/category-name" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                <!-- Media & Status -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Media & Pengaturan</h3>
                    
                    <div class="space-y-6">
                         <!-- Image Upload -->
                        <div>
                            <x-input-label for="image" :value="__('Gambar Background')" class="mb-2" />
                            <x-file-upload name="image" label="Upload Background Slide" />
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <div class="flex items-center mt-1">
                                <button type="button" onclick="document.getElementById('sort_order').stepDown()" class="p-2 bg-gray-100 dark:bg-gray-700 rounded-l-lg hover:bg-gray-200 dark:hover:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-600">-</button>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" class="block w-full text-center border-gray-300 dark:border-gray-600 dark:bg-gray-900 border-x-0 focus:ring-0 focus:border-gray-300" />
                                <button type="button" onclick="document.getElementById('sort_order').stepUp()" class="p-2 bg-gray-100 dark:bg-gray-700 rounded-r-lg hover:bg-gray-200 dark:hover:bg-gray-600 border border-l-0 border-gray-300 dark:border-gray-600">+</button>
                            </div>
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy dark:peer-focus:ring-mitologi-gold rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-mitologi-navy"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-6 border border-gray-100 dark:border-gray-700">
                    <button type="submit" class="w-full px-4 py-3 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-bold text-lg flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Hero Slide
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>

