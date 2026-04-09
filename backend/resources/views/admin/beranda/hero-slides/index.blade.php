<x-admin-layout>
    <x-admin-header 
        title="Manajemen Hero Slides" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Hero Slides']]"
        action_text="Tambah Slide" 
        :action_url="route('admin.beranda.hero-slides.create')"
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Slide Preview</th>
                        <th class="px-6 py-4">Konten Slide</th>
                        <th class="px-6 py-4 text-center">Urutan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($slides as $slide)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <div class="h-24 w-40 rounded-lg bg-gray-100  overflow-hidden shadow-sm relative group-hover:shadow-md transition-all">
                                @if($slide->image_url)
                                    <img src="{{ asset('storage/' . $slide->image_url) }}" alt="{{ $slide->title }}" class="h-full w-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400 p-4 text-center text-xs">
                                        No Image
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-mitologi-navy  text-base mb-1">{{ $slide->title }}</div>
                            <div class="text-sm text-gray-500 mb-2">{{ $slide->subtitle }}</div>
                            <div class="flex items-center gap-2">
                                @if($slide->cta_text)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        {{ $slide->cta_text }}
                                    </span>
                                @endif
                            </div>
                        </td>
                         <td class="px-6 py-4 text-center font-bold text-mitologi-navy/50  text-lg">
                            #{{ $slide->sort_order }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $slide->is_active 
                                    ? 'bg-green-100 text-green-800 border-green-200' 
                                    : 'bg-red-100 text-red-800 border-red-200' 
                                }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $slide->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $slide->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.hero-slides.edit', $slide) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.hero-slides.destroy', $slide) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini?');">
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
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada slide</h3>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan slide baru untuk banner utama.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($slides as $slide)
                <div class="p-4 flex flex-col gap-4 bg-white ">
                    {{-- Top Section: Image --}}
                    <div class="w-full aspect-video rounded-lg bg-gray-100  overflow-hidden shadow-sm relative">
                        @if($slide->image_url)
                            <img src="{{ asset('storage/' . $slide->image_url) }}" alt="{{ $slide->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                        @endif
                        <div class="absolute top-2 right-2">
                             <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-white/90 text-mitologi-navy shadow-sm backdrop-blur-sm">
                                #{{ $slide->sort_order }}
                            </span>
                        </div>
                    </div>

                    {{-- Middle Section: Content --}}
                    <div class="flex-1">
                        <h3 class="font-bold text-mitologi-navy  text-base mb-1 line-clamp-2">{{ $slide->title }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-2">{{ $slide->subtitle }}</p>
                        
                        <div class="flex flex-wrap gap-2 items-center">
                            @if($slide->cta_text)
                                <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ $slide->cta_text }}
                                </span>
                            @endif
                            
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border {{ $slide->is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                <span class="w-1 h-1 mr-1 rounded-full {{ $slide->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $slide->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>

                    {{-- Bottom Section: Actions --}}
                    <div class="flex items-center gap-2 pt-2 border-t border-gray-50 ">
                        <a href="{{ route('admin.beranda.hero-slides.edit', $slide) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors border border-gray-200">
                             <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.beranda.hero-slides.destroy', $slide) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini?');">
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
                    <p class="text-sm">Belum ada slide.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>


