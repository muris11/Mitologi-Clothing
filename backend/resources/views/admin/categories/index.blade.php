<x-admin-layout>
    <x-admin-header title="Manajemen Kategori" action_text="Tambah Kategori" :action_url="route('admin.categories.create')" />

    <div class="admin-panel overflow-hidden">
        {{-- Search/Filter Bar --}}
        <div class="p-4 sm:p-5 border-b border-gray-200/80 bg-[#f8f4ed] flex flex-col sm:flex-row sm:justify-between gap-3">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="relative w-full sm:max-w-xs">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                 </div>
                 <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-mitologi-gold sm:text-sm shadow-sm" placeholder="Cari kategori..." onchange="this.form.submit()">
            </form>
        </div>

        {{-- Desktop Table (md+) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-[#f8f4ed] uppercase font-bold text-[11px] text-gray-500 tracking-[0.16em]">
                    <tr>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-[#faf7f1] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm border border-gray-200 dark:border-gray-600 relative">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50 dark:bg-gray-800">
                                            <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-mitologi-navy group-hover:text-mitologi-gold-dark transition-colors">{{ $category->name }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $category->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $category->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3 opacity-100">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white" title="Edit kategori">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-1.5 text-red-600 shadow-sm transition-colors hover:bg-red-100 dark:border-red-800/60 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30" title="Hapus kategori">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                          <td colspan="3" class="px-6 py-12 text-center text-gray-500 bg-[#faf7f1]">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada kategori</h3>
                                <p class="text-sm text-gray-500 mt-1 mb-4">Mulai dengan menambahkan kategori pertama Anda.</p>
                                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-mitologi-navy hover:bg-mitologi-navy-light">
                                    Tambah Kategori
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List (< md) --}}
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/50">
            @forelse($categories as $category)
            <div class="p-4 flex items-center gap-4">
                {{-- Image --}}
                <div class="h-14 w-14 flex-shrink-0 rounded-xl bg-gray-100 dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50 dark:bg-gray-800">
                            <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-mitologi-navy dark:text-white text-sm truncate">{{ $category->name }}</div>
                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                        <span class="w-1.5 h-1.5 mr-1 rounded-full {{ $category->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                        {{ $category->is_active ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 shadow-sm transition-colors hover:bg-red-100 dark:border-red-800/60 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/30" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="p-4 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-3 w-fit mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                </div>
                <h3 class="text-base font-medium text-gray-900 dark:text-white">Belum ada kategori</h3>
                <p class="text-sm text-gray-500 mt-1 mb-4">Mulai tambahkan kategori pertama.</p>
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-mitologi-navy hover:bg-mitologi-navy-light rounded-lg shadow-sm">
                    Tambah Kategori
                </a>
            </div>
            @endforelse
        </div>

        @if($categories->hasPages())
        <div class="bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 p-4">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
