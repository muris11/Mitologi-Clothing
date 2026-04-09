<x-admin-layout>
    <x-admin-header 
        title="Laporan Penjualan" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Laporan Penjualan']]"
        action_text="Download CSV" 
        :action_url="route('admin.sales-report.export', request()->all())"
    />

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-premium border border-gray-100 dark:border-gray-700">
            <form action="{{ route('admin.sales-report.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                    <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date')" />
                </div>
                <div>
                    <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                    <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date')" />
                </div>
                <div>
                    <x-input-label for="status" :value="__('Status Pesanan')" />
                    <select id="status" name="status" class="block mt-1 w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <x-primary-button class="w-full justify-center h-[50px]">
                        Terapkan Filter
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Revenue -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-premium border border-gray-100 dark:border-gray-700 transition-colors sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Pendapatan</h3>
                    <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-mitologi-navy dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                @if(request('start_date'))
                <p class="text-xs text-gray-400 mt-2">Periode terpilih</p>
                @else
                <p class="text-xs text-gray-400 mt-2">Seumur hidup</p>
                @endif
            </div>

            <!-- Total Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-premium border border-gray-100 dark:border-gray-700 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Pesanan</h3>
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-mitologi-navy dark:text-white">{{ number_format($totalOrders) }}</p>
                 <p class="text-xs text-gray-400 mt-2">Transaksi tercatat</p>
            </div>

            <!-- Avg Order Value -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-premium border border-gray-100 dark:border-gray-700 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Rata-rata Order</h3>
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-mitologi-navy dark:text-white">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-2">Per transaksi</p>
            </div>
        </div>

        <!-- Orders View -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50/80 dark:bg-gray-700/50 uppercase font-bold text-xs text-gray-500 dark:text-gray-300 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Order No</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach($orders as $order)
                        <tr class="hover:bg-mitologi-cream/30 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium text-mitologi-navy dark:text-white">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-mitologi-navy dark:text-white">{{ $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Guest') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->user ? $order->user->email : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'paid' => 'bg-green-100 text-green-800 border-green-200',
                                        'shipped' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'processing' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                    $class = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full border {{ $class }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-mitologi-navy dark:text-white">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-mitologi-navy hover:text-mitologi-gold dark:text-gray-400 dark:hover:text-white transition-colors p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($orders as $order)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex flex-col">
                            <span class="font-mono text-xs font-medium text-gray-500 uppercase tracking-tight">{{ $order->order_number }}</span>
                            <span class="text-xs text-gray-400 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'paid' => 'bg-green-100 text-green-800 border-green-200',
                                'shipped' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'processing' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'completed' => 'bg-green-100 text-green-800 border-green-200',
                                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                            ];
                            $class = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full border {{ $class }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="space-y-0.5">
                            <div class="text-sm font-bold text-mitologi-navy dark:text-white">
                                {{ $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Guest') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $order->user ? $order->user->email : 'N/A' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black text-mitologi-navy dark:text-white">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="mt-1 text-xs font-bold text-mitologi-gold hover:underline flex items-center justify-end">
                                Detail <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <div class="p-4 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tidak ada data penjualan</h3>
                        <p class="text-sm text-gray-500 mt-1">Coba sesuaikan filter Anda.</p>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 p-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
