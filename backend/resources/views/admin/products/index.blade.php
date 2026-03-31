<x-admin-layout>
    <x-admin-header 
        title="Manajemen Produk" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Produk']]"
        action_text="Tambah Produk" 
        :action_url="route('admin.products.create')"
    />

    <div class="admin-panel overflow-hidden">
        <!-- Filter/Search Bar -->
        <div class="p-5 border-b border-gray-200/80 bg-[#f8f4ed] flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                 <div class="relative w-full md:w-64">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                     </div>
                     <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-mitologi-gold sm:text-sm shadow-sm" placeholder="Cari produk..." onchange="this.form.submit()">
                 </div>
                 
                 <div class="relative w-full md:w-48">
                      <select name="category" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-3 text-base border border-gray-200 rounded-xl leading-6 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-mitologi-gold sm:text-sm shadow-sm appearance-none cursor-pointer">
                         <option value="">Semua Kategori</option>
                         @foreach($categories as $category)
                             <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                         @endforeach
                     </select>
                     <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                         <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                     </div>
                 </div>
            </form>
            <div class="flex items-center gap-2">
                 @if(request('search') || request('category'))
                 <a href="{{ route('admin.products.index') }}" class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors">Reset Filter</a>
                 @endif
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-[#f8f4ed] uppercase font-bold text-[11px] text-gray-500 tracking-[0.16em]">
                    <tr>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Harga / Varian</th>
                        <th class="px-6 py-4">Ulasan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($products as $product)
                    <tr class="hover:bg-[#faf7f1] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-16 w-16 flex-shrink-0 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 relative group-hover:shadow-md transition-shadow">
                                    @if($product->featured_image)
                                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="h-full w-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50 dark:bg-gray-800">
                                            <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                     <div class="text-sm font-bold text-mitologi-navy group-hover:text-mitologi-gold-dark transition-colors">{{ $product->title }}</div>
                                    <div class="text-xs text-gray-500 font-mono mt-0.5">SKU: {{ $product->handle }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priceRange = $product->price_range;
                                $minPrice = $priceRange['minVariantPrice']['amount'];
                                $maxPrice = $priceRange['maxVariantPrice']['amount'];
                            @endphp
                            
                            <div class="flex flex-col">
                                <span class="font-bold text-mitologi-navy dark:text-white">
                                    @if($minPrice == $maxPrice)
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($minPrice, 0, ',', '.') }} <span class="text-gray-400 font-normal">-</span> {{ number_format($maxPrice, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="font-bold text-gray-700 dark:text-gray-300">{{ $product->reviews->count() > 0 ? round($product->reviews->avg('rating'), 1) : '-' }}</span>
                                <span class="text-xs text-gray-500 ml-1">({{ $product->reviews->count() }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->available_for_sale ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $product->available_for_sale ? 'bg-green-600' : 'bg-gray-500' }}"></span>
                                {{ $product->available_for_sale ? 'Aktif' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3 opacity-100">
                                <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 p-1.5 text-blue-600 shadow-sm transition-colors hover:bg-blue-100 dark:border-blue-800/60 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/30" title="Lihat Detail & Ulasan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white" title="Edit Produk">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-1.5 text-red-600 shadow-sm transition-colors hover:bg-red-100 dark:border-red-800/60 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30 dark:bg-gray-800/50">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada produk</h3>
                                <p class="text-sm text-gray-500 mt-1 mb-4">Mulai dengan menambahkan produk pertama Anda.</p>
                                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-mitologi-navy hover:bg-mitologi-navy-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mitologi-gold">
                                    Tambah Produk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($products as $product)
                <div class="p-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex gap-4">
                         <!-- Image -->
                        <div class="h-20 w-20 flex-shrink-0 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 relative">
                            @if($product->featured_image)
                                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50 dark:bg-gray-800">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-bold text-mitologi-navy dark:text-white line-clamp-2 leading-tight mb-1">{{ $product->title }}</h3>
                                </div>
                                <div class="text-xs text-gray-500 font-mono mb-2">SKU: {{ $product->handle }}</div>
                            </div>
                            
                            <div class="flex justify-between items-end">
                                @php
                                    $priceRange = $product->price_range;
                                    $minPrice = $priceRange['minVariantPrice']['amount'];
                                    $maxPrice = $priceRange['maxVariantPrice']['amount'];
                                @endphp
                                <div class="font-bold text-mitologi-navy dark:text-white text-sm">
                                    @if($minPrice == $maxPrice)
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($minPrice, 0, ',', '.') }}
                                    @endif
                                    <div class="flex items-center gap-1 mt-1">
                                        <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                                        <span class="font-bold text-xs text-gray-700 dark:text-gray-300">{{ $product->reviews->count() > 0 ? round($product->reviews->avg('rating'), 1) : '-' }}</span>
                                        <span class="text-[10px] text-gray-500">({{ $product->reviews->count() }})</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $product->available_for_sale ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                                    {{ $product->available_for_sale ? 'Active' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.products.show', $product) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition-colors border border-indigo-200">
                            <i class="fas fa-eye w-4 h-4 mr-1.5 flex items-center justify-center"></i>
                            Detail
                        </a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-colors border border-blue-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors border border-red-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 bg-gray-50/50 dark:bg-gray-800/50">
                    <p class="text-sm">Belum ada produk.</p>
                </div>
            @endforelse
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 p-4">
             {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>
