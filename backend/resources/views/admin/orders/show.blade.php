<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 border-b border-gray-200/80 pb-5">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="p-2.5 bg-white text-gray-500 rounded-xl shadow-sm hover:text-mitologi-navy transition-colors border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-3xl font-display font-semibold text-mitologi-navy tracking-tight">Detail Pesanan</h2>
                <p class="text-gray-500 text-sm mt-1">
                    Dibuat pada {{ $order->created_at->format('d F Y, H:i') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
             <button onclick="window.print()" class="px-4 py-2.5 bg-white text-gray-700 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Invoice
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content: Items & Summary -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Refund Action -->
            @if(!is_null($order->refund_requested_at) && $order->status === 'processing')
            <div class="bg-white  rounded-2xl shadow-premium border border-gray-100  overflow-hidden">
                <div class="border-b border-gray-100  bg-amber-50/50  p-6 relative">
                    <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                    <h3 class="flex items-center gap-2 text-base font-bold text-gray-900  tracking-tight">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        PENGAJUAN REFUND DITERIMA
                    </h3>
                    <p class="text-sm text-gray-600  mt-1">
                        Pelanggan mengajukan pengembalian dana pada {{ $order->refund_requested_at->format('d M Y, H:i') }}.
                    </p>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="bg-white  rounded-xl p-4 border border-gray-200 ">
                        <p class="text-sm font-bold text-gray-700  mb-2">Alasan Pelanggan:</p>
                        <p class="text-base text-gray-600  italic">"{{ $order->refund_reason }}"</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Approve form -->
                        <form action="{{ route('admin.orders.approve-refund', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="approve_note" :value="__('Catatan persetujuan (opsional)')" class="sr-only" />
                                <x-text-input id="approve_note" class="block w-full" type="text" name="refund_admin_note" placeholder="Catatan persetujuan (opsional)" />
                            </div>
                            <button type="submit" onclick="return confirm('Yakin ingin menyetujui refund? Status pesanan akan diubah menjadi Refunded.')" class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm transition-colors font-semibold flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui Refund
                            </button>
                        </form>

                        <!-- Reject form -->
                        <form action="{{ route('admin.orders.reject-refund', $order) }}" method="POST" class="space-y-4 mt-2">
                            @csrf
                            <div>
                                <x-input-label for="reject_note" :value="__('Catatan penolakan (wajib jika ditolak)')" class="sr-only" />
                                <x-text-input id="reject_note" class="block w-full" type="text" name="refund_admin_note" required placeholder="Catatan penolakan (wajib jika ditolak)" />
                            </div>
                            <button type="submit" onclick="return confirm('Yakin ingin menolak refund? Pesanan akan berlanjut diproses.')" class="w-full px-4 py-2.5 bg-white  text-red-600  border border-red-200  rounded-lg hover:bg-red-50  shadow-sm transition-colors font-semibold flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Items -->
            <div class="admin-panel overflow-hidden">
                <div class="p-6 border-b border-gray-100  bg-gray-50/50 ">
                    <h3 class="text-lg font-bold text-mitologi-navy  uppercase tracking-tight">Item Pesanan</h3>
                </div>
                
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 ">
                        <thead class="bg-gray-50  text-xs uppercase font-bold text-gray-500  tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4 text-right">Harga</th>
                                <th class="px-6 py-4 text-center">Jml</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 ">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50/50  transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-lg bg-gray-100  overflow-hidden border border-gray-200  mr-4 flex-shrink-0">
                                            @if($item->product && $item->product->featured_image)
                                                <img src="{{ asset('storage/'.$item->product->featured_image) }}" alt="{{ $item->product->title }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-mitologi-navy ">{{ $item->product->title ?? 'Product Deleted' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-mono">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-mitologi-navy ">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List -->
                <div class="md:hidden divide-y divide-gray-100 ">
                    @foreach($order->items as $item)
                    <div class="p-4 bg-white ">
                        <div class="flex gap-4">
                            <div class="h-16 w-16 rounded-lg bg-gray-50  overflow-hidden border border-gray-100  flex-shrink-0">
                                @if($item->product && $item->product->featured_image)
                                    <img src="{{ asset('storage/'.$item->product->featured_image) }}" alt="{{ $item->product->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-mitologi-navy  text-sm mb-1 leading-tight">{{ $item->product->title ?? 'Produk Dihapus' }}</div>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="font-bold text-mitologi-gold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Summary -->
            <div class="admin-panel p-6">
                <h3 class="text-lg font-bold text-mitologi-navy  mb-6">Ringkasan Pembayaran</h3>
                <div class="space-y-3 text-sm text-gray-600 ">
                    <div class="flex justify-between items-center">
                        <span>Subtotal</span>
                        <span class="font-mono">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Ongkos Kirim</span>
                        <span class="font-mono">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->tax > 0)
                    <div class="flex justify-between items-center">
                        <span>Pajak</span>
                        <span class="font-mono">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center border-t border-gray-200  pt-4 mt-4">
                        <span class="text-base font-bold text-mitologi-navy ">Total Bayar</span>
                        <span class="text-xl font-bold text-mitologi-gold font-mono">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Info & Actions -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="admin-panel p-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Pelanggan
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                         <div class="h-10 w-10 rounded-full bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light text-white flex items-center justify-center text-sm font-bold shadow-md">
                            {{ substr($order->user->name ?? 'G', 0, 1) }}
                        </div>
                        <div>
                             <div class="font-bold text-mitologi-navy ">{{ $order->user->name ?? 'Guest' }}</div>
                             <div class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100  pt-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600  mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $order->user->phone ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="admin-panel p-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Alamat Pengiriman
                </h3>
                <div class="text-sm text-gray-600  bg-gray-50  p-4 rounded-lg">
                    @if($order->shippingAddress)
                        <p class="font-bold text-mitologi-navy  mb-1">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                        <p>{{ $order->shippingAddress->address1 }}</p>
                        @if($order->shippingAddress->address2)
                            <p>{{ $order->shippingAddress->address2 }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}</p>
                        <p class="mt-2 text-xs text-gray-500">{{ $order->shippingAddress->phone }}</p>
                    @else
                        <p class="italic text-gray-500">Alamat tidak tersedia</p>
                    @endif
                </div>
            </div>



            <!-- Order Status Action -->
            <div class="admin-panel p-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Update Status</h3>
                
                @if(in_array($order->status, ['refunded', 'cancelled']))
                    <div class="bg-gray-50  rounded-lg p-5 text-center border border-gray-200 ">
                        <svg class="w-8 h-8 text-{{ $order->status === 'refunded' ? 'amber' : 'red' }}-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <p class="text-sm font-bold text-gray-700 ">
                            Pesanan Ini Telah {{ $order->status === 'refunded' ? 'Direfund' : 'Dibatalkan' }}
                        </p>
                        <p class="text-xs text-gray-500  mt-1">
                            Status pesanan ini sudah berstatus <span class="font-bold uppercase text-{{ $order->status === 'refunded' ? 'amber' : 'red' }}-500">{{ $order->status }}</span>.
                        </p>
                    </div>
                @else
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="status" :value="__('Status Pesanan')" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <select name="status" id="status" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300  focus:outline-none focus:ring-mitologi-navy focus:border-mitologi-navy sm:text-sm rounded-lg  ">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="tracking_number" :value="__('Resi Pengiriman')" />
                            <x-text-input id="tracking_number" class="block mt-1 w-full" type="text" name="tracking_number" :value="old('tracking_number', $order->tracking_number)" placeholder="Contoh: JNE123456" />
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-medium">
                            Update Status
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

