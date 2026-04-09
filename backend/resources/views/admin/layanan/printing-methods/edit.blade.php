<x-admin-layout>
    <x-admin-header
        title="Edit Teknik Printing"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Layanan', 'url' => route('admin.layanan.index')], ['title' => 'Teknik Printing', 'url' => route('admin.layanan.printing-methods.index')], ['title' => 'Edit: ' . $method->name]]"
    />

    <form action="{{ route('admin.layanan.printing-methods.update', $method->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Data Teknik Printing
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Teknik')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $method->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price_range" :value="__('Range Harga')" />
                            <x-text-input id="price_range" class="block mt-1 w-full" type="text" name="price_range" :value="old('price_range', $method->price_range)" />
                            <x-input-error :messages="$errors->get('price_range')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Panjang')" />
                            <x-textarea-input id="description" name="description" rows="4" class="block mt-1 w-full" required>{{ old('description', $method->description) }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        @php
                            $pros = old('pros', is_array($method->pros) ? $method->pros : []);
                            if(empty($pros)) $pros = [''];
                        @endphp
                        <div x-data='{ pros: @json($pros) }'>
                            <x-input-label :value="__('Kelebihan (Pros)')" class="mb-2" />
                            <div class="space-y-3">
                                <template x-for="(pro, index) in pros" :key="index">
                                    <div class="flex items-center gap-2">
                                        <input type="text" x-model="pros[index]" :name="`pros[${index}]`"
                                            class="flex-1 px-4 py-3 border border-gray-200  bg-gray-50/50  text-gray-900  focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm placeholder-gray-400 transition-all duration-200 text-sm"
                                            placeholder="Kelebihan teknik ini..." required>
                                        <button type="button" @click="pros.splice(index, 1)"
                                            class="flex-shrink-0 p-2 text-gray-400 hover:text-red-500 hover:bg-red-50  rounded-xl transition-colors"
                                            x-show="pros.length > 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="pros.push('')"
                                class="mt-3 w-full py-2.5 border border-dashed border-gray-300  rounded-xl text-sm font-semibold text-gray-500  hover:border-mitologi-gold hover:text-mitologi-gold transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                + Tambah Kelebihan
                            </button>
                        </div>

                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', $method->sort_order)" />
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
                            <x-input-label for="image" :value="__('Gambar/Ilustrasi Teknik')" class="mb-2" />
                            @if($method->image)
                                <div class="mb-4 bg-gray-50  p-4 border border-gray-200  rounded-lg">
                                    <p class="text-sm font-medium text-gray-500  mb-2">Gambar Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $method->image) }}" alt="Gambar" class="h-16 w-auto object-cover rounded border">
                                </div>
                            @endif
                            <x-file-upload name="image" label="Upload Gambar Baru (Opsional)" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50  rounded-xl">
                            <span class="text-sm font-medium text-gray-700 ">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $method->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy rounded-full peer  peer-checked:after:translate-x-full peer-checked:bg-mitologi-navy after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
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


