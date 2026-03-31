<x-admin-layout>
    <x-admin-header 
        title="Pengaturan Tentang Kami" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Tentang Kami']]"
    />



    <form action="{{ route('admin.tentang-kami.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            <x-admin-card>
                <h3 class="text-xl font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                    01 — Profil Perusahaan
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <x-file-upload name="about_image" label="Foto Tentang Kami" :preview="$settings['about_image'] ?? null" />
                    </div>
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="about_headline" :value="__('Headline')" />
                            <x-text-input id="about_headline" class="block mt-1 w-full" type="text" name="about_headline" :value="$settings['about_headline'] ?? ''" placeholder='"Vendor Konveksi Terpercaya"' />
                        </div>
                        <div>
                            <x-input-label for="company_founded_year" :value="__('Tahun Berdiri')" />
                            <x-text-input id="company_founded_year" class="block mt-1 w-full max-w-xs" type="text" name="company_founded_year" :value="$settings['company_founded_year'] ?? ''" placeholder="2022" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input-label for="about_description_1" :value="__('Deskripsi Paragraf 1')" />
                        <x-textarea-input id="about_description_1" name="about_description_1" rows="4" class="mt-1 block w-full">{{ $settings['about_description_1'] ?? '' }}</x-textarea-input>
                    </div>
                    <div>
                        <x-input-label for="about_description_2" :value="__('Deskripsi Paragraf 2')" />
                        <x-textarea-input id="about_description_2" name="about_description_2" rows="4" class="mt-1 block w-full">{{ $settings['about_description_2'] ?? '' }}</x-textarea-input>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input-label for="about_short_history" :value="__('Sejarah Singkat')" />
                        <x-textarea-input id="about_short_history" name="about_short_history" rows="3" class="mt-1 block w-full">{{ $settings['about_short_history'] ?? '' }}</x-textarea-input>
                    </div>
                    <div>
                        <x-input-label for="about_logo_meaning" :value="__('Makna Logo (Pisah per baris)')" />
                        <x-textarea-input id="about_logo_meaning" name="about_logo_meaning" rows="4" class="mt-1 block w-full" placeholder="- Poin 1&#10;- Poin 2">{{ $settings['about_logo_meaning'] ?? '' }}</x-textarea-input>
                    </div>
                </div>
            </x-admin-card>

            {{-- Visi Misi --}}
            <x-admin-card>
                <h3 class="text-xl font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                    02 — Visi & Misi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="vision_text" :value="__('Visi')" />
                        <x-textarea-input id="vision_text" name="vision_text" rows="4" class="mt-1 block w-full">{{ $settings['vision_text'] ?? '' }}</x-textarea-input>
                    </div>
                    <div>
                        <x-input-label for="mission_text" :value="__('Misi (Pisah per baris)')" />
                        <x-textarea-input id="mission_text" name="mission_text" rows="4" class="mt-1 block w-full" placeholder="- Misi 1&#10;- Misi 2">{{ $settings['mission_text'] ?? '' }}</x-textarea-input>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="values_text" :value="__('Nilai-Nilai (Values)')" />
                        <x-textarea-input id="values_text" name="values_text" rows="3" class="mt-1 block w-full">{{ $settings['values_text'] ?? '' }}</x-textarea-input>
                    </div>
                </div>
            </x-admin-card>

            {{-- Kisah Pendiri (Founder Story) --}}
            <x-admin-card>
                <h3 class="text-xl font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                    03 — Kisah Pendiri (Founder Story)
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <x-file-upload name="founder_photo" label="Foto Founder" :preview="$settings['founder_photo'] ?? null" />
                    </div>
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="founder_name" :value="__('Nama Founder')" />
                            <x-text-input id="founder_name" class="block mt-1 w-full" type="text" name="founder_name" :value="$settings['founder_name'] ?? ''" placeholder='Budi Santoso' />
                        </div>
                        <div>
                            <x-input-label for="founder_role" :value="__('Peran / Jabatan')" />
                            <x-text-input id="founder_role" class="block mt-1 w-full max-w-xs" type="text" name="founder_role" :value="$settings['founder_role'] ?? ''" placeholder="Founder & CEO" />
                        </div>
                        <div>
                            <x-input-label for="founder_story" :value="__('Kisah Pendiri')" />
                            <x-textarea-input id="founder_story" name="founder_story" rows="6" class="mt-1 block w-full">{{ $settings['founder_story'] ?? '' }}</x-textarea-input>
                        </div>
                    </div>
                </div>
            </x-admin-card>

            {{-- Legal Info --}}
            <x-admin-card>
                <h3 class="text-xl font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                    04 — Legalitas Perusahaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach(['legal_company_name'=>'Nama Badan Usaha','legal_address'=>'Alamat Resmi','legal_business_field'=>'Bidang Usaha','legal_npwp'=>'NPWP','legal_nib'=>'NIB','legal_nmid'=>'NMID'] as $key => $label)
                    <div>
                        <x-input-label :for="$key" :value="$label" />
                        <x-text-input :id="$key" class="block mt-1 w-full" type="text" :name="$key" :value="$settings[$key] ?? ''" />
                    </div>
                    @endforeach
                </div>
            </x-admin-card>

            {{-- SECTION 05 — Fasilitas Produksi --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">05</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Fasilitas Produksi</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola informasi fasilitas & peralatan</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tentang-kami.facilities.index') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 06 — Team Members --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">06</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Struktur Organisasi (Tim)</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola anggota tim dan hierarki</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tentang-kami.team-members.index') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>
        </div>

        {{-- Sticky Save --}}
        <div class="sticky bottom-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-t border-gray-200 dark:border-gray-700 p-4 -mx-4 sm:mx-0 mt-8 flex justify-end">
            <x-primary-button class="w-full sm:w-auto justify-center text-base py-3 px-8">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </x-primary-button>
        </div>
    </form>
</x-admin-layout>

