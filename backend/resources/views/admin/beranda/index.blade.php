<x-admin-layout>
    <x-admin-header 
        title="Pengaturan Beranda" 
        :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Beranda']]"
    />

    @php
        $decodeJsonArray = static function ($value): array {
            if (is_array($value)) {
                return $value;
            }

            if (!is_string($value) || trim($value) === '') {
                return [];
            }

            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        };

        $plastisolItems = $decodeJsonArray($settings['pricing_plastisol_data'] ?? '[]');
        $addonItems = $decodeJsonArray($settings['pricing_addons_data'] ?? '[]');
        $guarantees = $decodeJsonArray($settings['guarantees_data'] ?? '[]');
        $garansiBonus = $decodeJsonArray($settings['garansi_bonus_data'] ?? '[]');
    @endphp

    <form action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm"
          x-data="{
              plastisolItems: @js($plastisolItems),
              addonItems: @js($addonItems),
              guarantees: @js($guarantees),
              garansiBonus: @js($garansiBonus),

              addPlastisol() {
                  this.plastisolItems.push({ title: '', short: '', long: '', popular: false });
              },
              removePlastisol(index) {
                  this.plastisolItems.splice(index, 1);
              },
              addAddon() {
                  this.addonItems.push({ name: '', price: '' });
              },
              removeAddon(index) {
                  this.addonItems.splice(index, 1);
              },
              addGuarantee() {
                  this.guarantees.push({ title: '', description: '' });
              },
              removeGuarantee(index) {
                  this.guarantees.splice(index, 1);
              },
              addGaransiBonus() {
                  this.garansiBonus.push({ title: '', description: '' });
              },
              removeGaransiBonus(index) {
                  this.garansiBonus.splice(index, 1);
              }
          }">
        @csrf
        @method('PUT')

        <!-- Hidden Inputs for JSON Data -->
        <input type="hidden" name="pricing_plastisol_data" :value="JSON.stringify(plastisolItems)">
        <input type="hidden" name="pricing_addons_data" :value="JSON.stringify(addonItems)">
        <input type="hidden" name="guarantees_data" :value="JSON.stringify(guarantees)">
        <input type="hidden" name="garansi_bonus_data" :value="JSON.stringify(garansiBonus)">

        {{-- Info Banner --}}
        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800 rounded-xl p-4 mb-6 flex gap-3 text-sm text-purple-800 dark:text-purple-300 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Urutan section di bawah ini sesuai dengan urutan tampil di halaman Beranda website. Badge nomor menunjukkan posisi section di frontend.</p>
        </div>

        <div class="space-y-6">

            {{-- SECTION 01 — Hero Slider --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">01</span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hero Slider</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola slide gambar utama di halaman beranda</p>
                        </div>
                    </div>
                     <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-mitologi-navy/10 text-mitologi-navy text-xs font-bold">
                            {{ $heroSlidesCount }} slide aktif
                        </span>
                        <a href="{{ route('admin.beranda.hero-slides.index') }}" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                            Kelola
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                     </div>
                </div>
            </x-admin-card>

            {{-- SECTION 02 — About Brief --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">02</span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">About Brief</h3>
                             <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ringkasan tentang perusahaan</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold">
                                    Dikelola di halaman Tentang Kami
                                </span>
                             </div>
                        </div>
                    </div>
                     <a href="{{ route('admin.tentang-kami.index') }}" class="flex-shrink-0 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Ke Tentang Kami
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 03 — Pricelist Sablon Plastisol --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex items-center gap-4">
                    <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">03</span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pricelist Sablon Plastisol</h3>
                </div>

                <div class="p-6 space-y-8">
                    <div class="max-w-xs">
                         <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Minimum Order</label>
                         <x-text-input name="pricing_min_order" value="{{ $settings['pricing_min_order'] ?? '' }}" placeholder="12 pcs" class="w-full text-sm" />
                    </div>

                    <!-- Plastisol Items -->
                    <div>
                         <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3 mb-4">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Daftar Paket Harga</h4>
                                <p class="text-xs text-gray-500 mt-1">Atur variasi harga paket sablon.</p>
                            </div>
                            <button type="button" @click="addPlastisol" class="text-xs px-3 py-2 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light transition-colors shadow-sm font-bold">
                                + Tambah Paket
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                             <template x-for="(item, index) in plastisolItems" :key="index">
                                <div class="grid grid-cols-12 gap-4 items-start p-4 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Judul Paket</label>
                                        <x-text-input x-model="item.title" placeholder="Contoh: 1 Sisi, 1-2 Warna" class="w-full text-sm" />
                                    </div>
                                    <div class="col-span-6 md:col-span-3">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Harga Pendek</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-400 text-xs z-10 pointer-events-none">Rp</span>
                                            <x-text-input x-model="item.short" class="w-full pl-8 text-sm" />
                                        </div>
                                    </div>
                                    <div class="col-span-6 md:col-span-3">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Harga Panjang</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-400 text-xs z-10 pointer-events-none">Rp</span>
                                            <x-text-input x-model="item.long" class="w-full pl-8 text-sm" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 md:col-span-2 flex items-center justify-between pt-6">
                                         <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" x-model="item.popular" class="rounded border-gray-300 text-mitologi-navy shadow-sm focus:ring-mitologi-navy">
                                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Populer</span>
                                        </label>
                                        <button type="button" @click="removePlastisol(index)" class="text-gray-400 hover:text-red-500 transition-colors p-1 bg-white rounded-md shadow-sm border border-gray-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                             </template>
                             <div x-show="plastisolItems.length === 0" class="text-center py-6 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50/50">
                                 <p class="text-sm text-gray-400">Belum ada paket. Klik tombol Tambah Paket.</p>
                             </div>
                        </div>
                    </div>

                    <!-- Add-ons Repeater -->
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Add-ons (Biaya Tambahan)</h4>
                                <p class="text-xs text-gray-500 mt-1">Biaya ekstra untuk layanan tambahan.</p>
                            </div>
                             <button type="button" @click="addAddon" class="text-xs px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm font-bold">
                                + Tambah Add-on
                            </button>
                        </div>
                        
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                             <template x-for="(addon, index) in addonItems" :key="index">
                                 <div class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                                     <div class="flex-1 grid grid-cols-2 gap-3">
                                          <x-text-input x-model="addon.name" placeholder="Nama Layanan" class="w-full text-sm" />
                                          <x-text-input x-model="addon.price" placeholder="Harga (+5K)" class="w-full text-sm text-gray-500" />
                                     </div>
                                     <button type="button" @click="removeAddon(index)" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 bg-white rounded-md shadow-sm border border-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                 </div>
                             </template>
                         </div>
                    </div>
                </div>
            </x-admin-card>

            {{-- SECTION 04 — Pricelist Kategori Produk --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">04</span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pricelist Kategori Produk</h3>
                             <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Kelola daftar harga per kategori produk</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.product-pricings.index') }}" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 05 — Koleksi Terbaru --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">05</span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Koleksi Terbaru</h3>
                             <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">8 produk otomatis tampil</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-[10px] font-bold">
                                    Otomatis
                                </span>
                             </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Lihat Produk
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 06 — Produk Terlaris --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">06</span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Produk Terlaris</h3>
                             <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">8 produk terlaris tampil</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold">
                                    Otomatis
                                </span>
                             </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Lihat Produk
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 07 — Mengapa Memilih Kami --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">07</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mengapa Memilih Kami</h3>
                    </div>
                    <button type="button" @click="addGuarantee" class="px-3 py-2 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light shadow-sm text-xs font-bold transition-colors">
                        + Tambah Poin
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                     <template x-for="(g, index) in guarantees" :key="index">
                        <div class="p-4 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 relative group">
                            <button type="button" @click="removeGuarantee(index)" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors p-1.5 bg-white rounded-md shadow-sm border border-gray-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Judul Poin</label>
                                    <x-text-input x-model="g.title" class="w-full text-sm" />
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                                    <x-textarea-input x-model="g.description" rows="1" class="w-full text-sm resize-none" />
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="guarantees.length === 0" class="text-center py-6 border-2 border-dashed border-gray-200 bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700 rounded-xl">
                         <p class="text-gray-400 text-sm">Belum ada poin. Tambahkan poin keunggulan Anda.</p>
                    </div>
                </div>
            </x-admin-card>

            {{-- SECTION 08 — Garansi & Bonus --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">08</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Garansi & Bonus</h3>
                    </div>
                    <button type="button" @click="addGaransiBonus" class="px-3 py-2 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light shadow-sm text-xs font-bold transition-colors">
                        + Tambah Garansi/Bonus
                    </button>
                </div>

                <div class="p-6 space-y-4">
                     <template x-for="(g, index) in garansiBonus" :key="index">
                        <div class="p-4 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 relative group">
                            <button type="button" @click="removeGaransiBonus(index)" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors p-1.5 bg-white rounded-md shadow-sm border border-gray-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Judul</label>
                                    <x-text-input x-model="g.title" placeholder="Contoh: Garansi Tepat Waktu" class="w-full text-sm" />
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi</label>
                                    <x-textarea-input x-model="g.description" placeholder="Voucher kaos gratis jika telat dari deadline..." rows="1" class="w-full text-sm resize-none" />
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="garansiBonus.length === 0" class="text-center py-6 border-2 border-dashed border-gray-200 bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700 rounded-xl">
                         <p class="text-gray-400 text-sm">Belum ada item garansi atau bonus. Tambahkan item baru.</p>
                    </div>
                </div>
            </x-admin-card>

            {{-- SECTION 09 — Pilihan Material --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">09</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Pilihan Material</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola pilihan material</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.materials.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 10 — Alur Pemesanan --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex justify-between items-center h-full">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">10</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Alur Pemesanan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Langkah pemesanan</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.order-steps.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 11 — Portfolio --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex justify-between items-center h-full">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">11</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Portfolio</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Galeri hasil karya</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.portfolio.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 12 — Klien & Partner Kami --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex justify-between items-center h-full">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">12</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Klien & Partner Kami</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola logo partner bisnis</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.partners.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 13 — Testimonial --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex justify-between items-center h-full">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">13</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Testimonial</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ulasan pelanggan</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.testimonials.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 14 — CTA --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex items-center gap-4">
                    <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">14</span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Call to Action (CTA)</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Judul Utama</label>
                        <x-text-input name="cta_title" value="{{ $settings['cta_title'] ?? '' }}" class="w-full text-sm" placeholder="Siap Wujudkan Ide?" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Subjudul</label>
                        <x-text-input name="cta_subtitle" value="{{ $settings['cta_subtitle'] ?? '' }}" class="w-full text-sm" placeholder="Konsultasikan desainmu sekarang..." />
                    </div>
                    <div>
                         <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Teks Tombol</label>
                        <x-text-input name="cta_button_text" value="{{ $settings['cta_button_text'] ?? '' }}" class="w-full text-sm" placeholder="Hubungi Kami" />
                    </div>
                    <div>
                         <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Link Tombol</label>
                        <x-text-input name="cta_button_link" value="{{ $settings['cta_button_link'] ?? '' }}" placeholder="https://wa.me/..." class="w-full text-sm" />
                    </div>
                </div>
            </x-admin-card>

        </div>

        {{-- Sticky Save --}}
        <div class="sticky bottom-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-t border-gray-200 dark:border-gray-700 p-4 -mx-4 sm:mx-0 mt-8 flex justify-end">
            <x-primary-button class="w-full sm:w-auto justify-center text-base py-3 px-8 shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </x-primary-button>
        </div>
    </form>
</x-admin-layout>
