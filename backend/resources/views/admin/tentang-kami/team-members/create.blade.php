<x-admin-layout>
    <x-admin-header
        title="Tambah Anggota Tim"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Tentang Kami', 'url' => route('admin.tentang-kami.index')], ['title' => 'Tim', 'url' => route('admin.tentang-kami.team-members.index')], ['title' => 'Tambah Baru']]"
    />

    <form action="{{ route('admin.tentang-kami.team-members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Data Anggota
                    </h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required placeholder="Nama lengkap anggota" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="position" :value="__('Posisi / Jabatan')" />
                                <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position')" required placeholder="Contoh: Sewing, Freelance Designer" />
                                <x-input-error :messages="$errors->get('position')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="parent_id" :value="__('Atasan')" />
                                <select id="parent_id" name="parent_id" class="mt-1 w-full px-4 py-3 border border-gray-200  bg-gray-50/50  text-gray-900  focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm transition-all duration-200">
                                    <option value="">— Tidak ada (Tingkat paling atas) —</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ str_repeat('— ', $parent->level) }}{{ $parent->name }} ({{ $parent->position }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                                <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', 0)" min="0" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Level Selector --}}
                        <div>
                            <x-input-label :value="__('Level')" class="mb-3" />
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @php
                                    $levels = [
                                        ['value' => 0, 'label' => 'Founder', 'color' => 'bg-amber-50 border-amber-200 text-amber-800   '],
                                        ['value' => 1, 'label' => 'Manager', 'color' => 'bg-blue-50 border-blue-200 text-blue-800   '],
                                        ['value' => 2, 'label' => 'Staff', 'color' => 'bg-green-50 border-green-200 text-green-800   '],
                                        ['value' => 3, 'label' => 'Sub-Staff', 'color' => 'bg-gray-50 border-gray-200 text-gray-800   '],
                                    ];
                                @endphp
                                @foreach($levels as $level)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="level" value="{{ $level['value'] }}" class="peer sr-only" {{ old('level', 1) == $level['value'] ? 'checked' : '' }}>
                                        <div class="flex items-center justify-center p-3 rounded-xl border-2 {{ $level['color'] }} transition-all peer-checked:ring-2 peer-checked:ring-mitologi-navy/30 peer-checked:border-mitologi-navy ">
                                            <span class="text-sm font-bold">{{ $level['label'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
                        </div>
                    </div>
                </x-admin-card>
            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-8">
                <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Foto Anggota</h3>
                    <div>
                        <x-input-label for="photo" :value="__('Upload Foto')" class="mb-2" />
                        <x-file-upload name="photo" label="Upload Foto Anggota" />
                        <p class="text-xs text-gray-400 mt-2">Maks 2MB. Format: JPG, PNG, WebP</p>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                </x-admin-card>

                <x-admin-card>
                    <button type="submit" class="w-full px-4 py-3 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-bold text-base flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Anggota
                    </button>
                    <a href="{{ route('admin.tentang-kami.team-members.index') }}" class="block mt-3 w-full text-center px-4 py-2.5 bg-gray-100  text-gray-700  rounded-xl hover:bg-gray-200  transition-colors font-medium text-sm">
                        Batal
                    </a>
                </x-admin-card>
            </div>
        </div>
    </form>
</x-admin-layout>

