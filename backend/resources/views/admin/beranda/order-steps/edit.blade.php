<x-admin-layout>
    <x-admin-header
        title="Edit Langkah Pemesanan"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Alur Pesan', 'url' => route('admin.beranda.order-steps.index')], ['title' => 'Edit: ' . $order_step->title]]"
    />

    <form action="{{ route('admin.beranda.order-steps.update', $order_step) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                <x-admin-card>
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Data Langkah
                    </h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="step_number" :value="__('Nomor Langkah')" />
                                <x-text-input id="step_number" class="block mt-1 w-full" type="number" name="step_number" :value="old('step_number', $order_step->step_number)" required min="1" />
                                <x-input-error :messages="$errors->get('step_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sort_order" :value="__('Urutan Tampilan')" />
                                <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', $order_step->sort_order)" min="0" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Judul Langkah')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $order_step->title)" required placeholder="Contoh: Konsultasi Desain & Penawaran" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <x-textarea-input id="description" name="description" rows="3" class="block mt-1 w-full" required>{{ old('description', $order_step->description) }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                </x-admin-card>
            </div>

            {{-- Right: Sidebar --}}
            <div class="space-y-8">
                <x-admin-card>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Pengaturan</h3>
                    <div>
                        <x-input-label for="type" :value="__('Tipe Alur')" />
                        <select id="type" name="type" required class="mt-1 w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 text-gray-900 dark:text-white focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm transition-all duration-200">
                            <option value="langsung" {{ old('type', $order_step->type) == 'langsung' ? 'selected' : '' }}>Order Langsung</option>
                            <option value="ecommerce" {{ old('type', $order_step->type) == 'ecommerce' ? 'selected' : '' }}>Order Via E-Commerce</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </x-admin-card>

                <x-admin-card>
                    <button type="submit" class="w-full px-4 py-3 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-bold text-base flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Perbarui Langkah
                    </button>
                    <a href="{{ route('admin.beranda.order-steps.index') }}" class="block mt-3 w-full text-center px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium text-sm">
                        Batal
                    </a>
                </x-admin-card>
            </div>
        </div>
    </form>
</x-admin-layout>
