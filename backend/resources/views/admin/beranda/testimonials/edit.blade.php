<x-admin-layout>
    <x-admin-header
        title="Edit Testimonial"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Testimonial', 'url' => route('admin.beranda.testimonials.index')], ['title' => 'Edit: ' . $testimonial->name]]"
    />

    <form action="{{ route('admin.beranda.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Text Content -->
                <div class="bg-white  rounded-2xl shadow-premium p-8 border border-gray-100  relative overflow-hidden group">
                     <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Informasi Pelanggan
                    </h3>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $testimonial->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="role" :value="__('Role / Pekerjaan')" />
                                <x-text-input id="role" class="block mt-1 w-full" type="text" name="role" :value="old('role', $testimonial->role)" required />
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="content" :value="__('Isi Testimonial')" />
                            <textarea id="content" name="content" rows="6" class="block mt-1 w-full px-4 py-3 border border-gray-300  bg-white  text-gray-900  focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200 placeholder-gray-400 hover:border-gray-400  resize-none" required>{{ old('content', $testimonial->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                <!-- Media & Rating -->
                <div class="bg-white  rounded-2xl shadow-premium p-6 border border-gray-100 ">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Pengaturan & Media</h3>
                    
                    <div class="space-y-6">
                         <!-- Rating -->
                        <div>
                            <x-input-label for="rating" :value="__('Rating (1-5)')" />
                            <div class="mt-2">
                                <x-star-rating name="rating" :value="$testimonial->rating" />
                            </div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                         <!-- Avatar Upload -->
                         <div>
                            <x-file-upload name="avatar" label="Foto Avatar" :preview="$testimonial->avatar_url" />
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50  rounded-xl border border-gray-100 ">
                            <span class="text-sm font-medium text-gray-700 ">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-mitologi-gold/20  rounded-full peer  peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all  peer-checked:bg-mitologi-navy"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white  rounded-2xl shadow-premium p-6 border border-gray-100 ">
                    <button type="submit" class="w-full px-4 py-3 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-bold text-lg flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Perbarui Testimonial
                    </button>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100  text-center">
                         <span class="text-xs text-gray-500">Terakhir diubah: {{ $testimonial->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>


