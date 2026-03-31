<x-admin-layout>
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Sticky Header for Actions -->
        <div class="sticky top-0 z-30 mb-8 -mx-8 px-8 py-4 bg-[#f5f1ea]/90 backdrop-blur-md border-b border-gray-200/70 transition-all duration-300">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 max-w-7xl mx-auto">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.customers.index') }}" class="group p-2.5 bg-white text-gray-500 rounded-xl hover:text-mitologi-navy hover:bg-white transition-colors duration-200 shadow-sm border border-gray-200">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-3xl font-display font-semibold text-mitologi-navy tracking-tight">Edit Pelanggan</h2>
                        <p class="text-gray-500 text-sm">Perbarui informasi pelanggan dengan tampilan yang lebih fokus pada isi data.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                     <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-white text-gray-600 rounded-xl hover:bg-white transition-colors duration-200 text-sm font-semibold shadow-sm border border-gray-200">
                        Batal
                    </a>
                     <button type="submit" class="px-8 py-2.5 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light transition-colors duration-200 text-sm font-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Info Card -->
                <div class="admin-panel p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-mitologi-navy dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-mitologi-gold rounded-full"></span>
                            Informasi Dasar
                        </h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name', $customer->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="phone" :value="__('Nomor Telepon')" />
                                <x-text-input id="phone" class="mt-1 block w-full" type="text" name="phone" :value="old('phone', $customer->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                             <x-input-label for="email" :value="__('Email')" />
                             <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $customer->email)" required />
                             <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                             <x-input-label for="address" :value="__('Alamat')" />
                             <x-textarea-input id="address" class="mt-1 block w-full" name="address" rows="3">{{ old('address', $customer->address) }}</x-textarea-input>
                             <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Avatar -->
            <div class="space-y-6">
                <div class="admin-panel p-6">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Foto Profil</h3>
                    
                    <div class="flex justify-center mb-6">
                         <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg">
                            @if($customer->avatar)
                                <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-400 dark:text-gray-300">
                                    <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <x-file-upload name="avatar" label="Ganti Foto Profil" />
                    <p class="text-xs text-center text-gray-500 mt-2">Format: JPG, PNG. Max: 2MB</p>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
