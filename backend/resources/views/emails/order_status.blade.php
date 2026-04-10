<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pesanan - Mitologi Clothing</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        {!! file_exists(public_path('css/email.css')) ? file_get_contents(public_path('css/email.css')) : '' !!}
        body { font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] m-0 p-0 antialiased" style="font-family: 'Plus Jakarta Sans', 'Inter', Helvetica, Arial, sans-serif;">
    @php
        $statusConfig = [
            'pending'    => ['label' => 'Menunggu Pembayaran', 'text' => '#b45309', 'bg' => '#fef3c7', 'border' => '#fde68a'],
            'processing' => ['label' => 'Sedang Diproses',     'text' => '#1d4ed8', 'bg' => '#dbeafe', 'border' => '#bfdbfe'],
            'shipped'    => ['label' => 'Dalam Pengiriman',    'text' => '#6d28d9', 'bg' => '#ede9fe', 'border' => '#ddd6fe'],
            'completed'  => ['label' => 'Selesai',             'text' => '#047857', 'bg' => '#d1fae5', 'border' => '#a7f3d0'],
            'cancelled'  => ['label' => 'Dibatalkan',          'text' => '#b91c1c', 'bg' => '#fee2e2', 'border' => '#fecaca'],
        ];
        $current = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'text' => '#334155', 'bg' => '#f1f5f9', 'border' => '#e2e8f0'];
        $userName = $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Pelanggan');
        $frontendUrl = config('app.frontend_url');
    @endphp

    <div class="w-full bg-[#f1f5f9] py-10 px-4 sm:px-6">
        <div class="max-w-2xl mx-auto w-full">
            
            <!-- Email Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-[#e2e8f0] overflow-hidden relative">
                
                <!-- Premium Header -->
                <div class="bg-[#0f172a] py-8 px-6 text-center border-b-[4px] border-[#d4af37]">
                    <h1 class="text-2xl font-black text-[#d4af37] uppercase tracking-[0.2em] m-0">{{ config('app.name', 'Mitologi Clothing') }}</h1>
                </div>
                
                <div class="p-6 sm:p-10">
                    <!-- Title & Status Badge -->
                    <div class="flex flex-wrap items-center justify-between mb-8 pb-4 border-b border-[#e2e8f0]">
                        <h2 class="text-lg font-bold text-[#0f172a] m-0">Pembaruan Pesanan</h2>
                        <span class="inline-block px-3 py-1 text-[11px] font-bold uppercase tracking-wider rounded-md border" style="color: {{ $current['text'] }}; background-color: {{ $current['bg'] }}; border-color: {{ $current['border'] }};">
                            {{ $current['label'] }}
                        </span>
                    </div>
                    
                    <p class="text-base text-[#0f172a] font-bold tracking-wide mb-3">
                        Halo, {{ $userName }}
                    </p>
                    
                    <div class="text-sm text-[#475569] leading-relaxed mb-8">
                        @if($order->status == 'pending')
                            Pesanan Anda telah kami terima dengan detail berikut. Harap segera selesaikan pembayaran untuk melanjutkan proses produksi.
                        @elseif($order->status == 'processing')
                            Pembayaran Anda telah berhasil diverifikasi. Tim kualitas kami saat ini sedang memproses pesanan Anda dengan presisi.
                        @elseif($order->status == 'shipped')
                            Pesanan Anda telah dalam perjalanan menggunakan layanan ekspedisi terkait. Lacak pengiriman menggunakan nomor resi di bawah.
                        @elseif($order->status == 'completed')
                            Terima kasih telah berbelanja. Paket pesanan Anda telah resmi dinyatakan selesai dan diterima dengan baik.
                        @elseif($order->status == 'cancelled')
                            Pesanan Anda telah dibatalkan karena tidak ada kelanjutan pembayaran, perubahan sistematis, atau kendala operasional.
                        @else
                            Status terbaru pesanan Anda saat ini adalah: {{ $current['label'] }}.
                        @endif
                    </div>

                    <!-- Order Info Panel -->
                    <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-5 mb-8">
                        <div class="grid grid-cols-2 gap-y-5 text-sm">
                            <div>
                                <p class="text-[10px] text-[#64748b] font-bold uppercase tracking-wider mb-1">Nomor Pesanan</p>
                                <p class="text-[#0f172a] font-bold m-0 uppercase">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#64748b] font-bold uppercase tracking-wider mb-1">Tanggal & Waktu</p>
                                <p class="text-[#0f172a] font-semibold m-0">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#64748b] font-bold uppercase tracking-wider mb-1">Metode Bayar</p>
                                <p class="text-[#0f172a] font-semibold m-0 uppercase">{{ $order->payment_method ?? 'Transfer Bank' }}</p>
                            </div>
                            @if($order->tracking_number && $order->status === 'shipped')
                            <div>
                                <p class="text-[10px] text-[#64748b] font-bold uppercase tracking-wider mb-1">Nomor Resi</p>
                                <p class="text-[#0f172a] font-bold m-0">{{ $order->tracking_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="mb-8 overflow-hidden rounded-xl border border-[#e2e8f0]">
                        <table class="w-full text-sm font-medium border-collapse">
                            <thead>
                                <tr class="bg-[#f8fafc] border-b border-[#e2e8f0] text-left">
                                    <th class="p-4 text-[11px] uppercase tracking-wider text-[#64748b] font-bold">Rincian Item</th>
                                    <th class="p-4 text-[11px] uppercase tracking-wider text-[#64748b] font-bold text-center w-16">Qty</th>
                                    <th class="p-4 text-[11px] uppercase tracking-wider text-[#64748b] font-bold text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="p-4 align-top">
                                        <p class="m-0 font-bold text-[#0f172a]">{{ $item->product_title }}</p>
                                        @if($item->variant_title)
                                        <p class="m-0 mt-1 text-xs text-[#64748b] font-normal uppercase tracking-wide">{{ $item->variant_title }}</p>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center align-top text-[#475569] font-bold">{{ $item->quantity }}</td>
                                    <td class="p-4 text-right align-top text-[#0f172a] font-bold whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t border-[#cbd5e1] bg-[#f8fafc] text-sm">
                                @if($order->subtotal)
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-right text-[11px] uppercase tracking-wider text-[#64748b] font-bold">Total Harga Barang</td>
                                    <td class="px-4 py-3 text-right text-[#0f172a] font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="2" class="px-4 py-2 text-right text-[11px] uppercase tracking-wider text-[#64748b] font-bold">Total Ongkos Kirim</td>
                                    <td class="px-4 py-2 text-right text-[#0f172a] font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->tax > 0)
                                <tr>
                                    <td colspan="2" class="px-4 py-2 text-right text-[11px] uppercase tracking-wider text-[#64748b] font-bold">Biaya Layanan/Pajak</td>
                                    <td class="px-4 py-2 text-right text-[#0f172a] font-semibold">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="bg-[#0f172a] text-white">
                                    <td colspan="2" class="p-4 text-right text-[12px] uppercase tracking-widest font-black">Total Keseluruhan</td>
                                    <td class="p-4 text-right text-[#d4af37] text-[16px] font-black whitespace-nowrap border-b-[3px] border-[#d4af37]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Shipping Address -->
                    @if($order->shippingAddress)
                    <div class="mb-10 bg-white border border-[#e2e8f0] rounded-xl p-5">
                        <h3 class="text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-2 m-0">Tujuan Pengiriman</h3>
                        <p class="m-0 font-bold text-[#0f172a] text-[15px] mb-1 capitalize">{{ $order->shippingAddress->name }}</p>
                        <p class="m-0 text-[13px] text-[#475569] font-medium leading-relaxed">
                            <span class="block mb-1">{{ $order->shippingAddress->phone }}</span>
                            {{ $order->shippingAddress->address }}<br>
                            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                        </p>
                    </div>
                    @endif

                    <!-- Action Button -->
                    <div class="mb-2 text-center">
                        <a href="{{ $frontendUrl }}/shop/account/orders/{{ $order->order_number }}" class="inline-block bg-[#0f172a] text-[#d4af37] border border-[#0f172a] border-b-[4px] border-b-[#020617] rounded-xl font-bold text-sm uppercase tracking-widest py-4 px-8 hover:bg-[#1e293b] hover:translate-y-[2px] transition-all decoration-none" style="text-decoration: none;">
                            Lihat Detail & Lacak
                        </a>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="mt-8 text-center px-4">
                <p class="text-xs text-[#64748b] mb-2 font-bold uppercase tracking-widest">© {{ date('Y') }} {{ config('app.name', 'Mitologi Clothing') }}</p>
                <p class="text-[11px] text-[#94a3b8] leading-relaxed">
                    Pesan operasional ini dihasilkan secara otomatis oleh armada sistem kami.<br>Jangan membalas langsung ke alamat email ini.
                </p>
            </div>

        </div>
    </div>
</body>
</html>
