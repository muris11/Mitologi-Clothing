<x-admin-layout>
    <x-admin-header 
        title="Pengaturan Layanan" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Layanan']]"
    />

    <form action="{{ route('admin.layanan.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            {{-- SECTION 01 — Daftar Layanan --}}
            <x-admin-card>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">01</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Layanan</h3>
                    </div>
                    <button type="button" onclick="addService()" class="w-full sm:w-auto px-4 py-2 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light text-sm font-bold transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Layanan
                    </button>
                </div>
                
                <div class="bg-gray-50/50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 rounded-xl p-4 mb-6 flex gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>Kelola daftar layanan produksi utama yang ditawarkan (contoh: Pembuatan Kaos, Jaket, Seragam).</p>
                </div>

                <div id="services-container" class="space-y-6"></div>
                <input type="hidden" name="services_data" id="services_data_input">
            </x-admin-card>

            {{-- SECTION 02 — Pricelist Kategori Produk --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">02</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Pricelist Kategori Produk</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola daftar harga per kategori produk</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.beranda.product-pricings.index') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
                        Kelola
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </x-admin-card>

            {{-- SECTION 03 — Metode Sablon --}}
            <x-admin-card class="!p-0 border-0">
                <div class="p-6 bg-gray-50/50 dark:bg-gray-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-mitologi-navy text-white text-xs font-bold shadow-sm">03</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Metode Sablon (Printing Methods)</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola daftar metode sablon yang tersedia</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.layanan.printing-methods.index') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-mitologi-navy dark:text-gray-200 text-sm font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm flex items-center gap-2">
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

    @push('scripts')
    <script>
    // Styles matching x-text-input and x-textarea-input
    const inputClass = "w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-mitologi-navy focus:ring-1 focus:ring-mitologi-navy rounded-lg shadow-sm placeholder-gray-400 text-sm transition-colors";
    const textareaClass = "w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-mitologi-navy focus:ring-1 focus:ring-mitologi-navy rounded-lg shadow-sm placeholder-gray-400 text-sm transition-colors";

    // Services
    let servicesData = @json($services ?? []);
    function renderServices() {
        const c = document.getElementById('services-container');
        if(!servicesData.length){
            c.innerHTML='<div class="py-12 text-center text-gray-400 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700"><svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg><p>Belum ada layanan.</p><button type="button" onclick="addService()" class="mt-2 text-sm text-mitologi-navy hover:text-mitologi-gold-dark transition-colors font-medium">Tambah layanan baru</button></div>';
            return;
        }
        c.innerHTML = servicesData.map((s,i) => `
        <div class="relative bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-lg transition-all duration-300 hover:border-mitologi-navy/30 dark:hover:border-mitologi-gold/30">
            <div class="absolute top-4 right-4 z-10 opacity-100">
                 <button type="button" onclick="removeService(${i})" class="bg-white dark:bg-gray-700 text-red-500 hover:text-red-700 p-2 rounded-lg shadow-sm hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors border border-gray-100 dark:border-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 mb-4">
                Layanan #${i+1}
            </span>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Judul Layanan</label>
                        <input type="text" value="${s.title||''}" onchange="servicesData[${i}].title=this.value" class="${inputClass}" placeholder="Contoh: Pembuatan Kaos">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Gambar</label>
                        <div class="flex items-center gap-4">
                            ${s.image ? `<img src="${s.image.startsWith('http')?s.image:'/storage/'+s.image}" class="h-16 w-16 object-cover rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">` : '<div class="h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>'}
                            <label class="cursor-pointer bg-white dark:bg-gray-700 text-mitologi-navy dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                                <span>Pilih File</span>
                                <input type="file" name="service_image_${i}" accept="image/*" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                     <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi</label>
                        <textarea onchange="servicesData[${i}].desc=this.value" rows="4" class="${textareaClass}" placeholder="Deskripsi singkat layanan...">${s.desc||''}</textarea>
                    </div>
                </div>
                
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Material (pisahkan dengan koma)</label>
                        <input type="text" value="${s.materials||''}" onchange="servicesData[${i}].materials=this.value" class="${inputClass}" placeholder="Cotton Combed 30s, 24s, Bamboo">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Keunggulan (pisahkan dengan koma)</label>
                        <input type="text" value="${s.keunggulan||''}" onchange="servicesData[${i}].keunggulan=this.value" class="${inputClass}" placeholder="Jahitan Rapi, Sablon Awet">
                    </div>
                </div>
            </div>
        </div>`).join('');
    }
    function addService(){servicesData.push({title:'',desc:'',image:'',materials:'',keunggulan:''});renderServices();}
    function removeService(i){if(confirm('Hapus layanan ini?')){servicesData.splice(i,1);renderServices();}}

    document.getElementById('settingsForm').addEventListener('submit', function(){
        document.getElementById('services_data_input').value = JSON.stringify(servicesData);
    });

    renderServices();
    </script>
    @endpush
</x-admin-layout>
