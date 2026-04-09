<x-admin-layout>
    <x-admin-header 
        title="Pengaturan Kontak & Sosmed" 
        :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Kontak']]"
    />

    <form action="{{ route('admin.kontak.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- SECTION 1 — Informasi Kontak --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100  bg-gray-50/50  flex items-center gap-4">
                    <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 ">Informasi Kontak</h3>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Email Kontak</label>
                        <x-text-input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="w-full text-sm" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">No. WhatsApp</label>
                        <x-text-input name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" placeholder="+62..." class="w-full text-sm" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Telepon Kantor</label>
                        <x-text-input name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="w-full text-sm" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <x-textarea-input name="contact_address" rows="3" class="w-full text-sm resize-none">{{ $settings['contact_address'] ?? '' }}</x-textarea-input>
                    </div>
                </div>
            </x-admin-card>

            {{-- SECTION 2 — Jam Operasional --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100  bg-gray-50/50  flex items-center gap-4">
                    <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 ">Jam Operasional</h3>
                </div>

                <div class="p-6">
                    {{-- Preview Box --}}
                    <div class="mb-6 border border-gray-200  rounded-xl overflow-hidden">
                        <div class="bg-gray-50/50  px-4 py-3 border-b border-gray-200 ">
                             <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Preview tampilan di website</p>
                        </div>
                        <div class="p-4 bg-white ">
                            <ul class="space-y-3 text-sm font-medium text-gray-600 ">
                                <li class="flex justify-between border-b border-gray-100  pb-3">
                                    <span>{{ $settings['operating_hours_weekday_label'] ?? 'Senin - Sabtu' }}</span>
                                    <span class="font-bold text-gray-900 ">{{ $settings['operating_hours_weekday'] ?? '08.00 - 16.00 WIB' }}</span>
                                </li>
                                <li class="flex justify-between pb-1">
                                    <span>{{ $settings['operating_hours_weekend_label'] ?? 'Minggu' }}</span>
                                    <span class="font-bold text-red-500">{{ $settings['operating_hours_weekend'] ?? 'Tutup (Online Chat Only)' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Hari Kerja --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Label Hari Kerja</label>
                            <x-text-input name="operating_hours_weekday_label" value="{{ $settings['operating_hours_weekday_label'] ?? 'Senin - Sabtu' }}" placeholder="Senin - Sabtu" class="w-full text-sm" />
                            <p class="text-[10px] text-gray-400 mt-1">Contoh: Senin - Sabtu, Senin - Jumat</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Jam Ops Hari Kerja</label>
                            <x-text-input name="operating_hours_weekday" value="{{ $settings['operating_hours_weekday'] ?? '08.00 - 16.00 WIB' }}" placeholder="08.00 - 16.00 WIB" class="w-full text-sm" />
                            <p class="text-[10px] text-gray-400 mt-1">Contoh: 08.00 - 16.00 WIB, 09.00 - 17.00 WIB</p>
                        </div>

                        {{-- Hari Libur --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Label Hari Libur</label>
                            <x-text-input name="operating_hours_weekend_label" value="{{ $settings['operating_hours_weekend_label'] ?? 'Minggu' }}" placeholder="Minggu" class="w-full text-sm" />
                            <p class="text-[10px] text-gray-400 mt-1">Contoh: Minggu, Sabtu & Minggu</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Jam Ops Hari Libur</label>
                            <x-text-input name="operating_hours_weekend" value="{{ $settings['operating_hours_weekend'] ?? 'Tutup (Online Chat Only)' }}" placeholder="Tutup (Online Chat Only)" class="w-full text-sm" />
                            <p class="text-[10px] text-gray-400 mt-1">Contoh: Tutup, Tutup (Online Chat Only)</p>
                        </div>
                    </div>
                </div>
            </x-admin-card>

            {{-- SECTION 3 — Lokasi Maps --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100  bg-gray-50/50  flex items-center gap-4">
                     <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 ">Lokasi Maps</h3>
                </div>
                
                <div class="p-6">
                    <label class="block text-xs font-bold text-gray-500  uppercase tracking-wider mb-2">Link Google Maps Embed</label>
                    <x-textarea-input name="contact_maps_embed" rows="3" class="font-mono text-xs w-full resize-none">{{ $settings['contact_maps_embed'] ?? '' }}</x-textarea-input>
                    <p class="text-[11px] text-gray-500 mt-2 leading-relaxed">
                        Isi salah satu format berikut:
                        <br>• Koordinat (misal: <code class="bg-gray-100  px-1 py-0.5 rounded text-mitologi-navy ">-6.438597, 108.287315</code>)
                        <br>• URL Embed (misal: <code class="bg-gray-100  px-1 py-0.5 rounded text-mitologi-navy ">https://www.google.com/maps/embed?pb=...</code>)
                        <br>• Kode Iframe (misal: <code class="bg-gray-100  px-1 py-0.5 rounded text-mitologi-navy ">&lt;iframe src="..."&gt;&lt;/iframe&gt;</code>)
                    </p>
                </div>
            </x-admin-card>

            {{-- SECTION 4 — Social Media --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100  bg-gray-50/50  flex items-center gap-4">
                     <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/></svg>
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 ">Social Media & E-Commerce Link</h3>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(['instagram'=>'Instagram', 'tiktok'=>'TikTok', 'facebook'=>'Facebook', 'shopee'=>'Shopee', 'twitter'=>'Twitter/X'] as $key => $label)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4 bg-gray-50/50  rounded-xl border border-gray-200 ">
                        <div class="flex-1 min-w-0">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">{{ $label }}</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm pointer-events-none">@</span>
                                <x-text-input name="social_{{ $key }}" value="{{ $settings['social_'.$key] ?? '' }}" class="w-full pl-8 text-sm" placeholder="username" />
                            </div>
                        </div>
                        <div class="flex flex-col items-center flex-shrink-0">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Aktif</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="social_{{ $key }}_enabled" value="1" class="sr-only peer" {{ ($settings["social_{$key}_enabled"] ?? '0') == '1' ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy/20 rounded-full peer  peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all  peer-checked:bg-mitologi-navy shadow-sm"></div>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-admin-card>

        </div>

        {{-- Sticky Save --}}
        <div class="sticky bottom-0 z-40 bg-white/80  backdrop-blur-md border-t border-gray-200  p-4 -mx-4 sm:mx-0 mt-8 flex justify-end">
            <x-primary-button class="w-full sm:w-auto justify-center text-base py-3 px-8 shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </x-primary-button>
        </div>
    </form>
</x-admin-layout>

