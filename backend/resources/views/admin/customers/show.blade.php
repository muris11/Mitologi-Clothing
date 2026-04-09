<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 border-b border-gray-200/80 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.customers.index') }}" class="p-2.5 bg-white text-gray-500 rounded-xl shadow-sm hover:text-mitologi-navy transition-colors border border-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="text-3xl font-display font-semibold text-mitologi-navy tracking-tight">Detail Pelanggan</h2>
            </div>
            <p class="text-gray-500  text-sm mt-1 ml-7">View customer profile and order history</p>
        </div>
        <!-- Actions -->
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Customer Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="admin-panel p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-mitologi-navy to-mitologi-navy-light opacity-90"></div>
                <div class="relative flex flex-col items-center pt-8">
                    <div class="h-24 w-24 rounded-full bg-white p-1 shadow-lg mb-4 overflow-hidden">
                        @if($customer->avatar)
                            <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="h-full w-full rounded-full object-cover">
                        @else
                            <div class="h-full w-full rounded-full bg-gray-100  flex items-center justify-center text-3xl font-bold text-mitologi-navy ">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 ">{{ $customer->name }}</h3>
                    <p class="text-gray-500  text-sm">{{ $customer->email }}</p>
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium border border-green-200">
                            Customer
                        </span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium border border-gray-200">
                            Joined {{ $customer->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100  space-y-4">
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-500">Phone</span>
                         <span class="text-sm font-medium text-gray-900 ">{{ $customer->phone ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-500">Total Pesanan</span>
                         <span class="text-sm font-medium text-gray-900 ">{{ $stats['total_orders'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-500">Total Pengeluaran</span>
                         <span class="text-sm font-medium text-green-600 font-mono">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Address (Placeholder if no address relation yet) -->
            <div class="admin-panel p-6">
                 <h4 class="text-sm font-bold text-gray-900  uppercase tracking-wider mb-4">Alamat Utama</h4>
                 <div class="text-sm text-gray-600  space-y-1">
                     <p>Alamat belum tersedia.</p>
                     {{-- 
                     <p class="font-medium text-gray-900 ">John Doe</p>
                     <p>Jl. Jendral Sudirman No. 123</p>
                     <p>Jakarta Selatan, DKI Jakarta 12190</p>
                     <p>Indonesia</p> 
                     --}}
                 </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="lg:col-span-2">
            <div class="admin-panel overflow-hidden">
                <div class="p-6 border-b border-gray-100  flex justify-between items-center">
                    <h3 class="text-lg font-bold text-mitologi-navy ">Riwayat Pesanan</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 ">
                        <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                            <tr>
                                <th class="px-6 py-4">ID Pesanan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 ">
                            @forelse($customer->orders as $order)
                            <tr class="hover:bg-mitologi-cream/30  transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 ">
                                    #{{ $order->order_number ?? $order->id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 font-mono">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ match($order->status) {
                                            'paid', 'completed', 'shipped' => 'bg-green-100 text-green-800 border-green-200',
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200'
                                        } }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold">Lihat</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Pelanggan ini belum memiliki pesanan via website.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

