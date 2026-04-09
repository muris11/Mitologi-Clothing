<x-admin-layout>
    <x-admin-header
        title="Tambah Fasilitas Produksi"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Tentang Kami', 'url' => route('admin.tentang-kami.index')], ['title' => 'Fasilitas', 'url' => route('admin.tentang-kami.facilities.index')], ['title' => 'Tambah Baru']]"
    />

    <form action="{{ route('admin.tentang-kami.facilities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Data Fasilitas
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Fasilitas (Misal: Mesin Sablon DTG)')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Pendek (Opsional)')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300    focus:border-mitologi-gold  focus:ring-mitologi-gold  rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', 0)" />
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
                            <x-input-label for="image" :value="__('Foto Fasilitas')" class="mb-2" />
                            <x-file-upload name="image" label="Upload Foto" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50  rounded-xl">
                            <span class="text-sm font-medium text-gray-700 ">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy rounded-full peer  peer-checked:after:translate-x-full peer-checked:bg-mitologi-navy after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </div>
                    </div>
                </x-admin-card>

                <x-admin-card>
                    <x-primary-button class="w-full justify-center text-lg">Simpan</x-primary-button>
                </x-admin-card>
            </div>
        </div>
    </form>
</x-admin-layout>


