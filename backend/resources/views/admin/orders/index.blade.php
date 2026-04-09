<x-admin-layout>
    <x-admin-header
        title="Manajemen Pesanan"
        :breadcrumbs="[['title' => 'Toko Online', 'url' => '#'], ['title' => 'Pesanan']]"
        action_text=""
        :action_url="''"
    />

    <div class="bg-white  rounded-2xl shadow-premium overflow-hidden border border-gray-100 ">
        <!-- Search/Filter Bar -->
        <div class="p-5 border-b border-gray-200/80 bg-gray-50/80 flex flex-col md:flex-row justify-between items-center gap-4">
             <form action="{{ route('admin.orders.index') }}" method="GET" class="relative w-full md:w-64">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                 </div>
                 <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-mitologi-gold sm:text-sm shadow-sm" placeholder="Cari pesanan..." onchange="this.form.submit()">
            </form>
            
            <div class="flex items-center gap-2">
                 <div class="relative">
                     <select onchange="window.location.href=this.value" class="block w-full pl-3 pr-10 py-3 text-base border border-gray-200 rounded-xl leading-6 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-mitologi-gold sm:text-sm shadow-sm appearance-none cursor-pointer">
                        <option value="{{ route('admin.orders.index') }}">Semua Status</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'pending']) }}" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'processing']) }}" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'shipped']) }}" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'completed']) }}" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'refunded']) }}" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                     <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                         <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                     </div>
                 </div>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 ">
                <thead class="bg-gray-50/80  uppercase font-bold text-xs text-gray-500  tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 ">
                    @forelse($orders as $order)
                    <tr class="hover:bg-mitologi-cream/30  transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-bold text-mitologi-navy ">#{{ $order->order_number ?? $order->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200   flex items-center justify-center mr-3 text-xs font-bold text-gray-600  shadow-inner">
                                    {{ substr($order->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900  text-sm">{{ $order->user->name ?? 'Guest' }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-mitologi-navy ">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                {{ match($order->status) {
                                    'paid', 'completed', 'shipped' => 'bg-green-100 text-green-800 border-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'refunded' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                } }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                                    {{ match($order->status) {
                                        'paid', 'completed', 'shipped' => 'bg-green-600',
                                        'pending' => 'bg-yellow-600',
                                        'processing' => 'bg-blue-600',
                                        'refunded' => 'bg-purple-600',
                                        'cancelled' => 'bg-red-600',
                                        default => 'bg-gray-600'
                                    } }}"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5 text-mitologi-navy shadow-sm transition-colors hover:bg-gray-100 hover:text-mitologi-gold     ">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30 ">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 rounded-full bg-gray-100  text-gray-400 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 ">Belum ada pesanan</h3>
                                <p class="text-sm text-gray-500 mt-1">Pesanan baru akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-gray-100 ">
            @forelse($orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="block p-4 bg-white  hover:bg-gray-50  transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-bold text-mitologi-navy ">#{{ $order->order_number ?? $order->id }}</span>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border
                            {{ match($order->status) {
                                'paid', 'completed', 'shipped' => 'bg-green-100 text-green-800 border-green-200',
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'refunded' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            } }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                             <div class="h-6 w-6 rounded-full bg-gray-100  flex items-center justify-center mr-2 text-[10px] font-bold text-gray-600 ">
                                {{ substr($order->user->name ?? 'G', 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-700 ">{{ $order->user->name ?? 'Guest' }}</span>
                        </div>
                        <span class="font-bold text-mitologi-navy  text-sm">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500 bg-gray-50/50 ">
                    <p class="text-sm">Belum ada pesanan.</p>
                </div>
            @endforelse
        </div>

        <div class="bg-gray-50  border-t border-gray-200  p-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>

