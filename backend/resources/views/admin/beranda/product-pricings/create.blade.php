<x-admin-layout>
    <x-admin-header
        title="Tambah Pricelist Kategori"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Pricelist', 'url' => route('admin.beranda.product-pricings.index')], ['title' => 'Tambah Baru']]"
    />

    <form action="{{ route('admin.beranda.product-pricings.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Data Master -->
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Data Kategori
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="category_name" :value="__('Nama Kategori (Misal: Kaos Custom)')" />
                            <x-text-input id="category_name" class="block mt-1 w-full" type="text" name="category_name" :value="old('category_name')" required autofocus />
                            <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="min_order" :value="__('Minimum Order (Misal: 12 Pcs)')" />
                            <x-text-input id="min_order" class="block mt-1 w-full" type="text" name="min_order" :value="old('min_order')" />
                            <x-input-error :messages="$errors->get('min_order')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Catatan / Keterangan')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300    focus:border-mitologi-gold  focus:ring-mitologi-gold  rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>
                </x-admin-card>

                <!-- Dynamic Item Harga Array -->
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Item dan Harga
                    </h3>

                    @php
                        // Default to 1 empty item if no old input
                        $oldItems = old('items', [['name' => '', 'price_range' => '']]);
                    @endphp

                    <div x-data='{ items: @json($oldItems) }'>
                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="relative p-5 border border-gray-200  rounded-xl bg-white  shadow-sm">
                                    {{-- Delete button top-right --}}
                                    <button type="button" @click="items.splice(index, 1)"
                                        x-show="items.length > 1"
                                        class="absolute top-3 right-3 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50  rounded-lg transition-colors"
                                        title="Hapus Item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <div class="space-y-4 pr-8">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700  mb-1">Nama Item / Bahan</label>
                                            <input type="text" x-model="items[index].name" :name="`items[${index}][name]`"
                                                class="w-full px-4 py-3 border border-gray-200  bg-gray-50/50  text-gray-900  focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm placeholder-gray-400 transition-all duration-200 text-sm"
                                                placeholder="Contoh: Cotton Combed 24s" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700  mb-1">Range Harga</label>
                                            <input type="text" x-model="items[index].price_range" :name="`items[${index}][price_range]`"
                                                class="w-full px-4 py-3 border border-gray-200  bg-gray-50/50  text-gray-900  focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm placeholder-gray-400 transition-all duration-200 text-sm"
                                                placeholder="Contoh: Rp 45.000 - Rp 55.000 / pcs" required>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="items.push({name: '', price_range: ''})"
                            class="mt-4 w-full py-3 border border-dashed border-gray-300  rounded-xl text-sm font-semibold text-gray-500  hover:border-mitologi-gold hover:text-mitologi-gold  transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            + Tambah Item
                        </button>
                    </div>
                </x-admin-card>
            </div>

            <div class="space-y-8">
                <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Pengaturan & Status</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', 0)" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
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


