<x-admin-layout>
    <x-admin-header 
        title="Manajemen Pricelist Produk" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Pricelist Produk']]"
        action_text="Tambah Kategori Pricelist" 
        :action_url="route('admin.beranda.product-pricings.create')" 
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        {{-- Desktop Table (md+) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kategori Produk</th>
                        <th class="px-6 py-4">Min. Order</th>
                        <th class="px-6 py-4">Item Harga</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($pricings as $pricing)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-mitologi-navy  text-base group-hover:text-mitologi-gold transition-colors">{{ $pricing->category_name }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-[250px] mt-1">{{ Str::limit($pricing->notes, 80) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-700 ">{{ $pricing->min_order ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1 max-h-24 overflow-y-auto pr-2">
                                @if(is_array($pricing->items))
                                    @foreach($pricing->items as $item)
                                        <div class="text-xs flex justify-between gap-4 border-b border-gray-100  pb-1 last:border-0 last:pb-0">
                                            <span class="font-medium truncate max-w-[120px]">{{ $item['name'] ?? '' }}</span>
                                            <span class="text-gray-500 font-mono">{{ $item['price_range'] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada item</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-500 ">#{{ $pricing->sort_order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $pricing->is_active 
                                    ? 'bg-green-100 text-green-800 border-green-200' 
                                    : 'bg-red-100 text-red-800 border-red-200' 
                                }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $pricing->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $pricing->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.product-pricings.edit', $pricing->id) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.product-pricings.destroy', $pricing->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori pricelist ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-1.5 text-red-600 shadow-sm transition-colors hover:bg-red-100    ">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30 ">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 rounded-full bg-gray-100  text-gray-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada pricelist</h3>
                                <p class="text-sm text-gray-500 mt-1 mb-4">Tambahkan kategori pricelist pertama.</p>
                                <a href="{{ route('admin.beranda.product-pricings.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-mitologi-navy hover:bg-mitologi-navy-light rounded-lg shadow-sm">
                                    Tambah Pricelist
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List (< md) --}}
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($pricings as $pricing)
            <div class="p-4">
                {{-- Header Row --}}
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-mitologi-navy  text-sm">{{ $pricing->category_name }}</div>
                        @if($pricing->notes)
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $pricing->notes }}</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <span class="text-xs font-mono text-gray-400">#{{ $pricing->sort_order }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border
                            {{ $pricing->is_active 
                                ? 'bg-green-100 text-green-800 border-green-200' 
                                : 'bg-red-100 text-red-800 border-red-200' 
                            }}">
                            <span class="w-1.5 h-1.5 mr-1 rounded-full {{ $pricing->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                            {{ $pricing->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>
                </div>

                {{-- Info Row --}}
                <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                    @if($pricing->min_order)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Min. {{ $pricing->min_order }}
                    </span>
                    @endif
                    @if(is_array($pricing->items))
                    <span>{{ count($pricing->items) }} item harga</span>
                    @endif
                </div>

                {{-- Price Items Preview --}}
                @if(is_array($pricing->items) && count($pricing->items) > 0)
                <div class="bg-gray-50  rounded-lg p-2.5 mb-3 space-y-1.5">
                    @foreach(array_slice($pricing->items, 0, 3) as $item)
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-gray-700  truncate max-w-[55%]">{{ $item['name'] ?? '' }}</span>
                        <span class="text-gray-500 font-mono">{{ $item['price_range'] ?? '' }}</span>
                    </div>
                    @endforeach
                    @if(count($pricing->items) > 3)
                    <p class="text-[10px] text-gray-400">+{{ count($pricing->items) - 3 }} item lainnya...</p>
                    @endif
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.beranda.product-pricings.edit', $pricing->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-mitologi-navy text-white text-xs font-medium rounded-lg hover:bg-mitologi-navy-light transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.beranda.product-pricings.destroy', $pricing->id) }}" method="POST" onsubmit="return confirm('Hapus kategori pricelist ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="p-4 rounded-full bg-gray-100  w-fit mx-auto mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                </div>
                <h3 class="text-base font-medium text-gray-900 ">Belum ada pricelist</h3>
                <p class="text-sm text-gray-500 mt-1 mb-4">Tambahkan kategori pricelist pertama.</p>
                <a href="{{ route('admin.beranda.product-pricings.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-mitologi-navy hover:bg-mitologi-navy-light rounded-lg shadow-sm">
                    Tambah Pricelist
                </a>
            </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>

