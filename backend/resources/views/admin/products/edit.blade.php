<x-admin-layout>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
          x-data="{ 
              title: '{{ old('title', $product->title) }}', 
              handle: '{{ old('handle', $product->handle) }}',
              price: '{{ old('price', $product->variants->first()->price ?? 0) }}',
              description: `{{ old('description', $product->description) }}`,
              updateHandle() {
                  this.handle = this.title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
              }
          }">
        @csrf
        @method('PUT')
        
        <!-- Sticky Header for Actions -->
        <div class="sticky top-0 z-30 mb-8 -mx-8 px-8 py-4 bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 transition-all duration-300">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 max-w-7xl mx-auto">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.products.index') }}" class="group p-2.5 bg-white/50 dark:bg-gray-800/50 text-gray-500 rounded-xl hover:text-mitologi-navy hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-mitologi-navy to-mitologi-navy-light dark:from-white dark:to-gray-300 tracking-tight">Edit Produk: {{ $product->title }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Inventory Management</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                     <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white/50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 text-sm font-semibold shadow-sm border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-mitologi-navy to-mitologi-navy-light text-white rounded-xl hover:shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 text-sm font-bold transform hover:-translate-y-0.5">
                        Perbarui Produk
                    </button>
                </div>
            </div>
        </div>

        @php
            $allOptions = [];
            foreach($product->variants as $variant) {
                if($variant->options) {
                    foreach($variant->options as $key => $value) {
                        $allOptions[$key][] = $value;
                    }
                }
            }
            $alpineOptions = [];
            foreach($allOptions as $key => $values) {
                $alpineOptions[] = [
                    'name' => $key,
                    'values' => implode(', ', array_unique($values))
                ];
            }
            if(empty($alpineOptions)) {
                $alpineOptions = [
                    ['name' => 'Ukuran', 'values' => ''], 
                    ['name' => 'Warna', 'values' => '']
                ];
            }
        @endphp

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12"
             x-data="{
                hasVariants: {{ $product->variants->count() > 1 || !empty($product->variants->first()->options) ? 'true' : 'false' }},
                options: {{ json_encode($alpineOptions) }},
                variants: {{ $product->variants->map(function($v) {
                    return [
                        'id' => $v->id,
                        'name' => $v->title,
                        'price' => $v->price,
                        'stock' => $v->stock,
                        'options' => $v->options ?? []
                    ];
                })->toJson() }},
                basePrice: {{ $product->variants->first()->price ?? 0 }},
                baseStock: {{ $product->variants->first()->inventory_quantity ?? $product->variants->first()->stock ?? 0 }},
                addOption() {
                    this.options.push({ name: '', values: '' });
                },
                removeOption(index) {
                    if (this.options.length > 1) {
                        this.options.splice(index, 1);
                        this.generateVariants();
                    }
                },
                generateVariants() {
                    // Get all option values as arrays
                    let optionArrays = this.options.map(opt => ({
                        name: opt.name,
                        values: opt.values.split(',').map(s => s.trim()).filter(s => s)
                    })).filter(opt => opt.name && opt.values.length > 0);

                    if (optionArrays.length === 0) {
                        this.variants = [];
                        return;
                    }

                    // Generate all combinations
                    let combinations = this.generateCombinations(optionArrays);

                    // Create variants from combinations
                    let newVariants = [];
                    combinations.forEach(combo => {
                        let variantName = combo.map(c => c.value).join(' / ');
                        let existing = this.variants.find(v => v.name === variantName);
                        newVariants.push({
                            id: existing ? existing.id : null,
                            name: variantName,
                            price: existing ? existing.price : this.basePrice,
                            stock: existing ? existing.stock : this.baseStock,
                            options: Object.fromEntries(combo.map(c => [c.name, c.value]))
                        });
                    });

                    this.variants = newVariants;
                },
                generateCombinations(optionArrays) {
                    if (optionArrays.length === 0) return [];
                    if (optionArrays.length === 1) {
                        return optionArrays[0].values.map(v => [{ name: optionArrays[0].name, value: v }]);
                    }

                    let result = [];
                    let first = optionArrays[0];
                    let rest = this.generateCombinations(optionArrays.slice(1));

                    first.values.forEach(val => {
                        rest.forEach(combo => {
                            result.push([{ name: first.name, value: val }, ...combo]);
                        });
                    });

                    return result;
                }
             }">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="admin-panel p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-mitologi-navy dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-mitologi-gold rounded-full"></span>
                            Informasi Produk
                        </h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="title" :value="__('Nama Produk')" />
                            <x-text-input id="title" class="px-4 py-3 text-lg font-medium" type="text" name="title" x-model="title" @input="updateHandle" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                             <x-input-label for="description" :value="__('Deskripsi')" />
                             <div class="mt-1">
                                <x-rich-text-editor id="description" name="description" :value="old('description', $product->description)" @trix-change="description = $event.target.value" />
                             </div>
                             <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Product Gallery -->
                <div class="admin-panel p-8">
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-purple-500 rounded-full"></span>
                        Media & Galeri
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Main Image -->
                        <div>
                            <x-input-label :value="__('Gambar Utama')" class="mb-2" />
                            <x-file-upload name="image" label="Upload Gambar Utama" :preview="$product->featured_image" />
                        </div>

                        <!-- Gallery Images -->
                        <div x-data="{ files: [], deletedImages: [] }">
                            <x-input-label :value="__('Galeri Produk (Max 5 Gambar)')" class="mb-2" />
                            
                            <!-- Hidden inputs for deleted images -->
                            <template x-for="id in deletedImages" :key="id">
                                <input type="hidden" name="deleted_images[]" :value="id">
                            </template>

                            <!-- Existing Gallery -->
                            @if($product->images->count() > 0)
                            <div class="grid grid-cols-4 gap-4 mb-4">
                                @foreach($product->images as $img)
                                <div class="relative group aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700" 
                                     x-show="!deletedImages.includes({{ $img->id }})">
                                    <img src="{{ asset('storage/' . $img->url) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                         <button type="button" @click="deletedImages.push({{ $img->id }})" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                         </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-mitologi-gold transition-colors cursor-pointer relative"
                                 @dragover.prevent @drop.prevent="files = $event.dataTransfer.files"
                                 onclick="document.getElementById('gallery_input').click()">
                                
                                <input type="file" id="gallery_input" name="gallery[]" multiple accept="image/*" class="hidden" 
                                       @change="files = $event.target.files">
                                
                                <div x-show="files.length === 0" class="space-y-2 pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium text-mitologi-gold">Upload file</span> atau drag and drop
                                    </div>
                                    <p class="text-xs text-gray-500">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                                </div>

                                <div x-show="files.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                    <template x-for="file in Array.from(files)" :key="file.name">
                                        <div class="relative group aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                            <img :src="URL.createObjectURL(file)" class="w-full h-full object-cover">
                                            <div class="absolute inset-x-0 bottom-0 bg-black/50 p-1 text-white text-[10px] truncate" x-text="file.name"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('gallery')" class="mt-2" />
                            <x-input-error :messages="$errors->get('gallery.*')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Product Variants & Pricing -->
                 <div class="admin-panel p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-mitologi-navy dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-green-500 rounded-full"></span>
                            Harga & Variasi
                        </h3>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="has_variants" :value="hasVariants ? '1' : '0'">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" x-model="hasVariants" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-mitologi-gold/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-mitologi-gold"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Produk memiliki variasi</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Single Product Pricing (Shown if no variants) -->
                    <div x-show="!hasVariants" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="price" :value="__('Harga Jual')" />
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                  <span class="text-mitologi-navy dark:text-gray-400 font-bold">Rp</span>
                                </div>
                                <x-text-input id="price" class="pl-12 font-mono text-lg" type="number" name="price" x-model="basePrice" placeholder="0" />
                            </div>
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stock" :value="__('Stok Tersedia')" />
                             <div class="relative mt-1">
                                <x-text-input id="stock" class="font-mono text-lg pr-12" type="number" name="stock" x-model="baseStock" />
                                 <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                  <span class="text-gray-400 text-sm font-medium">Unit</span>
                                </div>
                             </div>
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Variant Generator (Shown if has variants) -->
                    <div x-show="hasVariants" class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Konfigurasi Variasi</h4>
                                <button type="button" @click="addOption()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-mitologi-gold/10 text-mitologi-gold rounded-lg hover:bg-mitologi-gold/20 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Variasi
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(option, optIndex) in options" :key="optIndex">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 relative">
                                        <button type="button" @click="removeOption(optIndex)" x-show="options.length > 1" class="absolute -top-2 -right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div>
                                            <x-input-label value="Nama Variasi" />
                                            <x-text-input x-model="option.name" placeholder="Contoh: Ukuran, Warna, Material" class="mt-1" @input.debounce.500ms="generateVariants" />
                                            <input type="hidden" :name="`options[${optIndex}][name]`" :value="option.name">
                                        </div>
                                        <div>
                                            <x-input-label value="Nilai (Pisahkan dengan koma)" />
                                            <x-text-input x-model="option.values" placeholder="Contoh: S, M, L, XL" class="mt-1" @input.debounce.500ms="generateVariants" />
                                            <input type="hidden" :name="`options[${optIndex}][values]`" :value="option.values">
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Base Price for Variants -->
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <x-input-label value="Harga Default Variasi" />
                                    <x-text-input type="number" x-model="basePrice" class="mt-1" @input="generateVariants" />
                                </div>
                                <div>
                                    <x-input-label value="Total Stok Varian" />
                                    <x-text-input type="number" x-bind:value="variants.reduce((sum, v) => sum + parseInt(v.stock || 0), 0)" class="mt-1 bg-gray-100 dark:bg-gray-800 text-gray-500 font-bold cursor-not-allowed" readonly title="Total stok dihitung otomatis dari semua variasi" />
                                </div>
                            </div>
                        </div>

                        <!-- Variants Table -->
                        <div x-show="variants.length > 0">
                             <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wide">Daftar Variasi (@<span x-text="variants.length"></span>)</h4>
                             <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                                 <table class="w-full text-sm text-left">
                                     <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 uppercase font-bold text-xs">
                                         <tr>
                                             <th class="px-6 py-3">Nama Variasi</th>
                                             <th class="px-6 py-3">Harga (Rp)</th>
                                             <th class="px-6 py-3">Stok</th>
                                             <th class="px-6 py-3">Opsi</th>
                                         </tr>
                                     </thead>
                                     <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                         <template x-for="(variant, index) in variants" :key="index">
                                             <tr>
                                                 <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                     <span x-text="variant.name"></span>
                                                     <input type="hidden" :name="`variants[${index}][id]`" :value="variant.id">
                                                     <input type="hidden" :name="`variants[${index}][name]`" :value="variant.name">
                                                     <input type="hidden" :name="`variants[${index}][options]`" :value="JSON.stringify(variant.options)">
                                                 </td>
                                                 <td class="px-6 py-4">
                                                     <input type="number" :name="`variants[${index}][price]`" x-model="variant.price" class="w-32 font-mono text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-lg shadow-sm" />
                                                 </td>
                                                 <td class="px-6 py-4">
                                                     <input type="number" :name="`variants[${index}][stock]`" x-model="variant.stock" class="w-24 font-mono text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-lg shadow-sm" />
                                                 </td>
                                                 <td class="px-6 py-4">
                                                     <button type="button" @click="variants.splice(index, 1)" class="text-red-500 hover:text-red-700">
                                                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                     </button>
                                                 </td>
                                             </tr>
                                         </template>
                                     </tbody>
                                 </table>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="admin-panel p-6">
                     <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Visibilitas</h3>
                     
                     <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                         <div class="flex flex-col">
                             <span class="text-mitologi-navy dark:text-white font-bold text-sm">Publikasikan</span>
                             <span class="text-gray-500 text-xs mt-0.5">Tampil di toko online</span>
                         </div>
                         <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="available_for_sale" value="1" class="sr-only peer" {{ old('available_for_sale', $product->available_for_sale) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-mitologi-gold/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-mitologi-gold"></div>
                        </label>
                     </div>
                </div>

                <!-- Category Card -->
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-3xl shadow-premium p-6 border border-white/20 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Kategori</h3>
                    <div class="relative">
                        <select name="category_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id') == $category->id || $product->categories->contains($category->id)) ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Organization Card -->
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-3xl shadow-premium p-6 border border-white/20 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Link Produk</h3>
                    
                    <div class="relative group">
                        <x-input-label for="handle" :value="__('URL Slug')" class="sr-only" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">/</span>
                        </div>
                        <x-text-input id="handle" class="pl-6 bg-gray-100/50 text-gray-500 cursor-not-allowed text-xs font-mono" type="text" name="handle" x-model="handle" readonly />
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
