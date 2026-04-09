<x-admin-layout>
    <x-admin-header 
        title="Manajemen Material" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Material']]"
        action_text="Tambah Material" 
        :action_url="route('admin.beranda.materials.create')"
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        @php
            $colorLabels = [
                'bg-gray-100 text-gray-800' => 'Abu-abu',
                'bg-green-100 text-green-800' => 'Hijau',
                'bg-blue-100 text-blue-800' => 'Biru',
                'bg-red-100 text-red-800' => 'Merah',
                'bg-amber-100 text-amber-800' => 'Kuning',
                'bg-indigo-100 text-indigo-800' => 'Ungu',
                'bg-teal-100 text-teal-800' => 'Teal',
            ];
        @endphp

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Nama Material</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Tema Warna</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($materials as $material)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-mitologi-navy/10 text-mitologi-navy font-bold text-sm">
                                {{ $material->sort_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-mitologi-navy ">{{ $material->name }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            <p class="text-gray-600  truncate">{{ $material->description }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $material->color_theme }}">
                                {{ $colorLabels[$material->color_theme] ?? 'Default' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.materials.edit', $material) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.materials.destroy', $material) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus material ini?');">
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
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada material</h3>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan jenis bahan pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($materials as $material)
                <div class="p-4 flex flex-col gap-3 bg-white ">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-mitologi-navy  text-base">{{ $material->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $material->description }}</p>
                        </div>
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-600 font-bold text-xs">
                            {{ $material->sort_order }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $material->color_theme }}">
                             {{ $colorLabels[$material->color_theme] ?? 'Default' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-50  mt-1">
                        <a href="{{ route('admin.beranda.materials.edit', $material) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors border border-gray-200">
                             <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.beranda.materials.destroy', $material) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus material ini?');">
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
                    <p class="text-sm">Belum ada material.</p>
                </div>
            @endforelse
        </div>

        <div class="bg-gray-50  border-t border-gray-200  p-4">
            {{ $materials->links() }}
        </div>
    </div>
</x-admin-layout>


