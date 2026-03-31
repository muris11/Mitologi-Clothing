<x-admin-layout>
    <x-admin-header 
        title="Daftar Pelanggan" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Pelanggan']]"
        action_text="Tambah Pelanggan" 
        :action_url="route('admin.customers.create')"
    />

    <div class="admin-panel overflow-hidden">
        <!-- Search Bar -->
        <div class="p-5 border-b border-gray-200/80 bg-[#f8f4ed] flex justify-between items-center">
            <div class="relative w-full max-w-md">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                 </div>
                 <input type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-mitologi-gold sm:text-sm shadow-sm" placeholder="Cari pelanggan...">
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-[#f8f4ed] uppercase font-bold text-[11px] text-gray-500 tracking-[0.16em]">
                    <tr>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Bergabung</th>
                        <th class="px-6 py-4">Total Pesanan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-[#faf7f1] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 mr-3">
                                        @if($customer->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover shadow-sm border border-gray-200 dark:border-gray-600" src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white dark:ring-gray-800">
                                                {{ substr($customer->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-mitologi-navy dark:text-white">{{ $customer->name }}</span>
                                        @if($customer->role === 'admin')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-mitologi-gold/20 text-mitologi-gold border border-mitologi-gold/30">ADMIN</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">PELANGGAN</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">ID: #{{ $customer->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-gray-900 dark:text-white font-medium">{{ $customer->email }}</span>
                                <span class="text-xs text-gray-500">
                                    @if($customer->phone)
                                        {{ $customer->phone }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $customer->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-mitologi-navy dark:text-white mr-2">{{ $customer->orders()->count() }}</span>
                                <span class="text-xs text-gray-500">Pesanan</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3 opacity-100">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 p-1.5 text-blue-700 shadow-sm transition-colors hover:bg-blue-100 dark:border-blue-800/60 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');" class="inline">
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
                         <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30 dark:bg-gray-800/50">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada pelanggan</h3>
                                <p class="text-sm text-gray-500 mt-1">Pelanggan yang mendaftar akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($customers as $customer)
                <div class="p-4 flex flex-col gap-3 bg-white dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 flex-shrink-0">
                            @if($customer->avatar)
                                <img class="h-10 w-10 rounded-full object-cover shadow-sm border border-gray-200 dark:border-gray-600" src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white dark:ring-gray-800">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-mitologi-navy dark:text-white">{{ $customer->name }}</span>
                                @if($customer->role === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-mitologi-gold/20 text-mitologi-gold border border-mitologi-gold/30">ADMIN</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200">PELANGGAN</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">{{ $customer->email }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-xs text-gray-500 block">Bergabung</span>
                            <span class="font-medium text-sm text-gray-900 dark:text-white">{{ $customer->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="text-right">
                             <span class="text-xs text-gray-500 block">Total Pesanan</span>
                             <span class="font-bold text-base text-mitologi-navy dark:text-white">{{ $customer->orders()->count() }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 mt-1">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors border border-gray-200">
                             <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Detail
                        </a>
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-colors border border-blue-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
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
                    <p class="text-sm">Belum ada pelanggan.</p>
                </div>
            @endforelse
        </div>

        <div class="bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 p-4">
            {{ $customers->links() }}
        </div>
    </div>
</x-admin-layout>
