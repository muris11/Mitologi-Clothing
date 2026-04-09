<x-admin-layout>
    <x-admin-header
        title="Manajemen Portfolio"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Portofolio']]"
        action_text="Tambah Portfolio"
        :action_url="route('admin.beranda.portfolio.create')"
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Proyek</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($items as $item)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-16 w-16 rounded-xl bg-gray-100  overflow-hidden shadow-sm mr-4 flex-shrink-0 border border-gray-200  relative group-hover:shadow-md transition-shadow">
                                    @if($item->image_url)
                                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}" class="h-full w-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50 ">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="font-bold text-mitologi-navy  text-base group-hover:text-mitologi-gold transition-colors">{{ $item->title }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-gray-100  text-xs font-medium text-gray-600  border border-gray-200 ">
                                {{ $item->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-500 ">#{{ $item->sort_order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $item->is_active
                                    ? 'bg-green-100 text-green-800 border-green-200'
                                    : 'bg-red-100 text-red-800 border-red-200'
                                }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $item->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.portfolio.edit', $item->id) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.portfolio.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');">
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
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30 ">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 rounded-full bg-gray-100  text-gray-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada portfolio</h3>
                                <p class="text-sm text-gray-500 mt-1">Mulai tambahkan proyek terbaik Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($items as $item)
                <div class="p-4 flex flex-col gap-3 bg-white ">
                    <div class="relative h-48 w-full rounded-xl overflow-hidden shadow-sm group">
                         @if($item->image_url)
                            <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}" class="h-full w-full object-cover z-0">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-100 ">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
                            <span class="inline-block px-2 py-1 rounded-md bg-white/20 backdrop-blur-sm text-[10px] font-bold text-white mb-2 w-fit border border-white/10">
                                {{ $item->category }}
                            </span>
                            <h3 class="font-bold text-white text-lg tracking-tight">{{ $item->title }}</h3>
                        </div>
                         <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold border shadow-lg backdrop-blur-md {{ $item->is_active ? 'bg-green-500/90 text-white border-green-400' : 'bg-red-500/90 text-white border-red-400' }}">
                                {{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <a href="{{ route('admin.beranda.portfolio.edit', $item->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors border border-gray-200">
                             <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.beranda.portfolio.destroy', $item->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');">
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
                <div class="p-8 text-center text-gray-500 bg-gray-50/50 ">
                    <p class="text-sm">Belum ada portfolio.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>

