<x-admin-layout>
    <x-admin-header 
        title="Alur Pemesanan" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Alur Pesan']]"
        action_text="Tambah Langkah" 
        :action_url="route('admin.beranda.order-steps.create')"
    />

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50/80 dark:bg-gray-700/50 uppercase font-bold text-xs text-gray-500 dark:text-gray-300 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Step</th>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($orderSteps as $step)
                    <tr class="hover:bg-mitologi-cream/30 dark:hover:bg-gray-700/30 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light text-white font-bold text-sm shadow-md">
                                {{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-mitologi-navy dark:text-white">{{ $step->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $step->type == 'langsung' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $step->type == 'langsung' ? 'Langsung' : 'E-Commerce' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <p class="text-gray-600 dark:text-gray-300 truncate">{{ $step->description }}</p>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.beranda.order-steps.edit', $step) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.beranda.order-steps.destroy', $step) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus langkah ini?');">
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
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada langkah pemesanan</h3>
                                <p class="text-sm text-gray-500 mt-1">Tambahkan alur pemesanan pertama Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($orderSteps as $step)
                <div class="p-4 flex flex-col gap-3 bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                             <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light text-white font-bold text-sm shadow-md">
                                {{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div>
                                <h3 class="font-bold text-mitologi-navy dark:text-white text-base">{{ $step->title }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $step->type == 'langsung' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $step->type == 'langsung' ? 'Langsung' : 'E-Commerce' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        {{ $step->description }}
                    </p>

                    <div class="flex items-center gap-2 pt-2 mt-1">
                        <a href="{{ route('admin.beranda.order-steps.edit', $step) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-mitologi-navy text-white text-xs font-medium rounded-lg hover:bg-mitologi-navy-light transition-colors shadow-sm">
                             <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.beranda.order-steps.destroy', $step) }}" method="POST" class="flex-none" onsubmit="return confirm('Apakah Anda yakin ingin menghapus langkah ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors border border-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 bg-gray-50/50 dark:bg-gray-800/50">
                    <p class="text-sm">Belum ada langkah pemesanan.</p>
                </div>
            @endforelse
        </div>

        <div class="bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 p-4">
            {{ $orderSteps->links() }}
        </div>
    </div>
</x-admin-layout>

