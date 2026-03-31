<x-admin-layout>
    <x-admin-header
        title="Manajemen Fasilitas Produksi"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Tentang Kami', 'url' => route('admin.tentang-kami.index')], ['title' => 'Fasilitas']]"
        action_text="Tambah Fasilitas"
        :action_url="route('admin.tentang-kami.facilities.create')"
    />

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium overflow-hidden border border-gray-100 dark:border-gray-700">
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50/80 dark:bg-gray-700/50 uppercase font-bold text-xs text-gray-500 dark:text-gray-300 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Fasilitas</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($facilities as $facility)
                    <tr class="hover:bg-mitologi-cream/30 dark:hover:bg-gray-700/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-16 w-24 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm mr-4 flex-shrink-0 border border-gray-200 dark:border-gray-600 relative group-hover:shadow-md transition-shadow">
                                    @if($facility->image)
                                        <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" class="h-full w-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-mitologi-navy dark:text-white text-base group-hover:text-mitologi-gold transition-colors">{{ $facility->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[250px]">{{ Str::limit($facility->description, 80) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-500 dark:text-gray-400">#{{ $facility->sort_order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $facility->is_active
                                    ? 'bg-green-100 text-green-800 border-green-200'
                                    : 'bg-red-100 text-red-800 border-red-200'
                                }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $facility->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $facility->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.tentang-kami.facilities.edit', $facility->id) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.tentang-kami.facilities.destroy', $facility->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus fasilitas ini?');">
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
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada fasilitas</h3>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan fasilitas produksi pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($facilities as $facility)
                <div class="p-4 flex flex-col gap-3 bg-white dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="h-20 w-24 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm flex-shrink-0 border border-gray-200 dark:border-gray-600">
                            @if($facility->image)
                                <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-mitologi-navy dark:text-white text-base">{{ $facility->name }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border {{ $facility->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                                    {{ $facility->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $facility->description }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-50 dark:border-gray-700/50">
                        <a href="{{ route('admin.tentang-kami.facilities.edit', $facility->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors border border-gray-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.tentang-kami.facilities.destroy', $facility->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus fasilitas ini?');">
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
                    <p class="text-sm">Belum ada fasilitas produksi.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
