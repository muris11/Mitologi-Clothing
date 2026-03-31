<x-admin-layout>
    <x-admin-header 
        title="Buat Kategori" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Kategori', 'url' => route('admin.categories.index')], ['title' => 'Buat Baru']]"
        action_text="Batal" 
        :action_url="route('admin.categories.index')" 
    />

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
          x-data="{ 
              name: '{{ old('name') }}', 
              slug: '{{ old('slug') }}',
              updateSlug() {
                  this.slug = this.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
              }
          }">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <x-admin-card>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-mitologi-navy dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-mitologi-gold rounded-full"></span>
                            Informasi Kategori
                        </h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Kategori')" />
                            <x-text-input id="name" class="px-4 py-3 text-lg font-medium" type="text" name="name" x-model="name" @input="updateSlug" required autofocus placeholder="Contoh: Streetwear" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                         <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <x-textarea-input id="description" name="description" rows="5" placeholder="Deskripsi singkat untuk SEO dan tampilan koleksi...">{{ old('description') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                </x-admin-card>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Status & Organization -->
                 <x-admin-card>
                     <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Pengaturan</h3>
                     
                     <div class="space-y-6">
                         <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-700">
                             <div class="flex flex-col">
                                 <span class="text-mitologi-navy dark:text-white font-bold text-sm">Status Aktif</span>
                                 <span class="text-gray-500 text-xs mt-0.5">Tampilkan di menu</span>
                             </div>
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-gold/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-mitologi-gold"></div>
                            </label>
                         </div>

                        <div>
                            <x-input-label for="slug" :value="__('Slug URL')" class="sr-only" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400">/</span>
                                </div>
                                <x-text-input id="slug" class="pl-6 bg-gray-100/50 text-gray-500 cursor-not-allowed text-xs font-mono" type="text" name="slug" x-model="slug" readonly />
                            </div>
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>
                     </div>
                </x-admin-card>

                <!-- Media -->
                 <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Banner Kategori</h3>
                    
                    <x-file-upload name="image" label="Upload Banner" />
                </x-admin-card>

                <x-primary-button class="w-full justify-center text-lg">
                    Simpan Kategori
                </x-primary-button>
            </div>
        </div>
    </form>
</x-admin-layout>
