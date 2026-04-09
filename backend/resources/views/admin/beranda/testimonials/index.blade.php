<x-admin-layout>
    <x-admin-header 
        title="Manajemen Testimonial" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Testimonial']]"
        action_text="Tambah Testimonial" 
        :action_url="route('admin.beranda.testimonials.create')"
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Konten Testimonial</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($testimonials as $testimonial)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-100  overflow-hidden shadow-sm mr-3 flex-shrink-0 border border-gray-200 ">
                                    @if($testimonial->avatar_url)
                                        <img src="{{ asset('storage/' . $testimonial->avatar_url) }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-white font-bold bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light text-sm">
                                            {{ substr($testimonial->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-mitologi-navy ">{{ $testimonial->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $testimonial->role }}</div>
                                </div>
                            </div>
                        </td>
                         <td class="px-6 py-4 max-w-sm">
                            <p class="text-gray-600  truncate italic">"{{ $testimonial->content }}"</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex text-mitologi-gold">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < $testimonial->rating ? 'fill-current' : 'text-gray-300 ' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ $testimonial->is_active 
                                    ? 'bg-green-100 text-green-800 border-green-200' 
                                    : 'bg-red-100 text-red-800 border-red-200' 
                                }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $testimonial->is_active ? 'bg-green-600' : 'bg-red-500' }}"></span>
                                {{ $testimonial->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.testimonials.edit', $testimonial) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.testimonials.destroy', $testimonial) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimonial ini?');">
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
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada testimonial</h3>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan ulasan pelanggan pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($testimonials as $testimonial)
                <div class="p-4 flex flex-col gap-3 bg-white ">
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-full bg-gray-100  overflow-hidden shadow-sm flex-shrink-0 border border-gray-200 ">
                            @if($testimonial->avatar_url)
                                <img src="{{ asset('storage/' . $testimonial->avatar_url) }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-white font-bold bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light text-sm">
                                    {{ substr($testimonial->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-mitologi-navy  text-base">{{ $testimonial->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $testimonial->role }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border {{ $testimonial->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                                    {{ $testimonial->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </div>
                            <div class="flex text-mitologi-gold mt-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-3 h-3 {{ $i < $testimonial->rating ? 'fill-current' : 'text-gray-300 ' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50  p-3 rounded-lg border border-gray-100  relative">
                        <svg class="absolute top-2 left-2 w-4 h-4 text-gray-300  opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16H9C9 14.9385 9.41602 13.9213 10.1654 13.1706C10.9149 12.4199 11.932 12 12.9936 12H13.6826C14.7712 12 15.6587 11.1264 15.6822 10.0382L15.6987 8.16335C15.7075 7.07066 14.832 6.17511 13.7394 6.16669C12.4491 6.15674 10.5173 6.44686 8.75168 8.21556C6.97495 9.99539 6.00004 12.4225 6 15L6 19C6 20.1046 6.89543 21 8 21H12.017C13.1216 21 14.017 20.1046 14.017 19V21ZM22 21L22 18C22 16.8954 21.1046 16 20 16H16.983C16.983 14.9385 17.399 13.9213 18.1484 13.1706C18.8979 12.4199 19.9149 12 20.9766 12H21.6656C22.7542 12 23.6416 11.1264 23.6652 10.0382L23.6816 8.16335C23.6905 7.07066 22.815 6.17511 21.7223 6.16669C20.432 6.15674 18.5003 6.44686 16.7347 8.21556C14.9579 9.99539 13.983 12.4225 13.983 15L13.983 19C13.983 20.1046 14.8784 21 15.983 21H20C21.1046 21 22 20.1046 22 19V21Z"></path></svg>
                        <p class="text-sm text-gray-600  italic pl-6 line-clamp-3">"{{ $testimonial->content }}"</p>
                    </div>

                    <div class="flex items-center gap-2 pt-2 mt-1">
                        <a href="{{ route('admin.beranda.testimonials.edit', $testimonial) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-mitologi-navy text-white text-xs font-medium rounded-lg hover:bg-mitologi-navy-light transition-colors shadow-sm">
                             <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.beranda.testimonials.destroy', $testimonial) }}" method="POST" class="flex-none" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimonial ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors border border-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 bg-gray-50/50 ">
                    <p class="text-sm">Belum ada testimonial.</p>
                </div>
            @endforelse
        </div>
        
         <div class="bg-gray-50  border-t border-gray-200  p-4">
            {{ $testimonials->links() }}
        </div>
    </div>
</x-admin-layout>


