<x-admin-layout>
    <x-admin-header
        title="Manajemen Klien & Partner"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Partner']]"
        action_text="Tambah Partner"
        :action_url="route('admin.beranda.partners.create')"
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Partner</th>
                        <th class="px-6 py-4">Website</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-12 w-24 rounded-lg bg-gray-100  overflow-hidden shadow-sm mr-4 flex-shrink-0 border border-gray-200  relative flex items-center justify-center p-2">
                                    @if($partner->logo)
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <span class="text-xs text-gray-400">No Logo</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-mitologi-navy  text-base group-hover:text-mitologi-gold transition-colors">{{ $partner->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[200px]">{{ $partner->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="text-mitologi-gold hover:underline flex items-center gap-1 text-sm">
                                    Link
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-500 ">#{{ $partner->sort_order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $partner->is_active
                                    ? 'bg-green-100 text-green-800 border-green-200'
                                    : 'bg-red-100 text-red-800 border-red-200'
                                }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $partner->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $partner->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.partners.edit', $partner->id) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.partners.destroy', $partner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus partner ini?');">
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
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada partner</h3>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan klien atau partner pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($partners as $partner)
                <div class="p-4 flex flex-col gap-3 bg-white ">
                    <div class="flex items-center gap-3">
                        <div class="h-14 w-24 rounded-lg bg-gray-100  overflow-hidden shadow-sm flex-shrink-0 border border-gray-200  flex items-center justify-center p-2">
                            @if($partner->logo)
                                <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="text-xs text-gray-400">No Logo</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-mitologi-navy  text-base">{{ $partner->name }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border {{ $partner->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                                    {{ $partner->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $partner->description }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-50 ">
                        <a href="{{ route('admin.beranda.partners.edit', $partner->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors border border-gray-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.beranda.partners.destroy', $partner->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus partner ini?');">
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
                    <p class="text-sm">Belum ada partner.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>

