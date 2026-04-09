<x-admin-layout>
    <x-admin-header 
        title="Anggota Tim" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Tentang Kami', 'url' => route('admin.tentang-kami.index')], ['title' => 'Anggota Tim']]"
        action_text="Tambah Anggota"
        :action_url="route('admin.tentang-kami.team-members.create')"
    />

    @php
        $levelLabels = [0 => 'Founder', 1 => 'Manager', 2 => 'Staff', 3 => 'Sub-Staff'];
        $levelColors = [
            0 => 'bg-amber-100 text-amber-800  ',
            1 => 'bg-blue-100 text-blue-800  ',
            2 => 'bg-green-100 text-green-800  ',
            3 => 'bg-gray-100 text-gray-800  ',
        ];
    @endphp

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        @if($teamMembers->count() > 0)
            {{-- Desktop Table (md+) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50/80 ">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500  uppercase tracking-wider">Anggota Tim</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500  uppercase tracking-wider">Level & Struktur</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500  uppercase tracking-wider">Urutan</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500  uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 ">
                        @foreach($teamMembers as $member)
                            <tr class="hover:bg-gray-50  transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 rounded-full overflow-hidden bg-gray-100 border border-gray-200 ">
                                            @if($member->photo)
                                                <img class="h-12 w-12 object-cover" src="{{ $member->photo_url }}" alt="{{ $member->name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-mitologi-navy/10 flex items-center justify-center text-mitologi-navy font-bold text-lg">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900  group-hover:text-mitologi-gold transition-colors">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->position }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $levelColors[$member->level] }} w-fit">
                                            {{ $levelLabels[$member->level] }}
                                        </span>
                                        @if($member->parent)
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                Bawahan: {{ $member->parent->name }}
                                            </div>
                                        @elseif($member->level > 0)
                                            <span class="text-xs text-red-500">- Tidak ada atasan -</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-medium text-gray-900 ">{{ $member->sort_order }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.tentang-kami.team-members.edit', $member) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     " title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.tentang-kami.team-members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus anggota tim ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-1.5 text-red-600 shadow-sm transition-colors hover:bg-red-100    " title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List (< md) --}}
            <div class="md:hidden divide-y divide-gray-100 ">
                @foreach($teamMembers as $member)
                <div class="p-4 flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 h-14 w-14 rounded-full overflow-hidden bg-gray-100  border-2 border-white  shadow-sm">
                        @if($member->photo)
                            <img class="h-full w-full object-cover" src="{{ $member->photo_url }}" alt="{{ $member->name }}">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-mitologi-navy font-bold text-xl bg-mitologi-navy/10">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-mitologi-navy  text-sm truncate">{{ $member->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $member->position }}</div>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $levelColors[$member->level] }}">
                                {{ $levelLabels[$member->level] }}
                            </span>
                            @if($member->parent)
                            <span class="text-[10px] text-gray-400 truncate">↳ {{ $member->parent->name }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <a href="{{ route('admin.tentang-kami.team-members.edit', $member) }}" class="p-2 text-mitologi-navy hover:text-mitologi-gold hover:bg-gray-100  rounded-lg transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.tentang-kami.team-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus anggota tim ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50  rounded-lg transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-gray-50  border-t border-gray-200  p-4">
                {{ $teamMembers->links() }}
            </div>

        @else
            <div class="flex flex-col items-center justify-center p-12 text-center">
                <div class="p-4 bg-gray-100  rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900  mb-2">Belum ada anggota tim</h3>
                <p class="text-sm text-gray-500  max-w-sm mb-6">Tambahkan anggota tim untuk ditampilkan di struktur organisasi pada halaman Tentang Kami.</p>
                <a href="{{ route('admin.tentang-kami.team-members.create') }}" class="px-6 py-2.5 bg-mitologi-navy text-white text-sm font-bold rounded-xl hover:bg-mitologi-navy-light shadow-lg transition-all">
                    Tambah Anggota Tim
                </a>
            </div>
        @endif
    </div>
</x-admin-layout>

