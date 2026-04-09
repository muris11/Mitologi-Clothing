<x-admin-layout>
    <x-admin-header
        title="Tambah Material Baru"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Material', 'url' => route('admin.beranda.materials.index')], ['title' => 'Tambah Baru']]"
    />

    <div class="bg-white  rounded-2xl shadow-premium border border-gray-100  p-8">
        <form action="{{ route('admin.beranda.materials.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700  mb-2">Nama Material <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border-gray-300    shadow-sm focus:border-mitologi-gold focus:ring-mitologi-gold py-3 px-4"
                        placeholder="Contoh: Cotton Combed 30s & 24s">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700  mb-2">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required
                        class="w-full rounded-xl border-gray-300    shadow-sm focus:border-mitologi-gold focus:ring-mitologi-gold py-3 px-4"
                        placeholder="Deskripsi singkat tentang material ini">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700  mb-2">Tema Warna <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @php
                            $colors = [
                                ['value' => 'bg-gray-100 text-gray-800', 'label' => 'Abu-abu', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200'],
                                ['value' => 'bg-green-100 text-green-800', 'label' => 'Hijau', 'bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200'],
                                ['value' => 'bg-blue-100 text-blue-800', 'label' => 'Biru', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200'],
                                ['value' => 'bg-red-100 text-red-800', 'label' => 'Merah', 'bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200'],
                                ['value' => 'bg-amber-100 text-amber-800', 'label' => 'Kuning', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
                                ['value' => 'bg-indigo-100 text-indigo-800', 'label' => 'Ungu', 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-200'],
                                ['value' => 'bg-teal-100 text-teal-800', 'label' => 'Teal', 'bg' => 'bg-teal-100', 'text' => 'text-teal-800', 'border' => 'border-teal-200'],
                            ];
                        @endphp
                        @foreach($colors as $color)
                            <label class="cursor-pointer group">
                                <input type="radio" name="color_theme" value="{{ $color['value'] }}" class="peer sr-only" {{ old('color_theme') == $color['value'] ? 'checked' : '' }}>
                                <div class="flex items-center p-3 rounded-xl border border-gray-200  hover:border-mitologi-navy  transition-all peer-checked:border-mitologi-navy peer-checked:ring-2 peer-checked:ring-mitologi-navy/20   {{ $color['bg'] }}">
                                    <span class="text-sm font-bold {{ $color['text'] }}">{{ $color['label'] }}</span>
                                    <div class="ml-auto w-4 h-4 rounded-full border border-current opacity-0 peer-checked:opacity-100 transition-opacity text-mitologi-navy">
                                        <div class="w-2 h-2 rounded-full bg-current m-0.5"></div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('color_theme') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700  mb-2">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full rounded-xl border-gray-300    shadow-sm focus:border-mitologi-gold focus:ring-mitologi-gold py-3 px-4"
                        placeholder="0">
                    @error('sort_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100 ">
                <a href="{{ route('admin.beranda.materials.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light shadow-lg transition-all font-medium">Simpan Material</button>
            </div>
        </form>
    </div>
</x-admin-layout>


