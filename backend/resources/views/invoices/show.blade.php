<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-8 print:p-0 print:bg-white">
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-sm rounded-lg print:shadow-none">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 border-b pb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">INVOICE</h1>
                <p class="text-gray-500">No: #{{ $order->order_number }}</p>
                <p class="text-gray-500">Tanggal: {{ $order->created_at->format('d F Y') }}</p>
                <p class="text-gray-500">Status: <span class="font-semibold uppercase {{ $order->status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">{{ $order->status }}</span></p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-blue-900">MITOLOGI CLOTHING</h2>
                <p class="text-gray-600 max-w-xs mt-2">
                    Jalan Raya Indramayu No. 123<br>
                    Indramayu, Jawa Barat<br>
                    WA: 0813-2217-0902<br>
                    Email: mitologiclothing@gmail.com
                </p>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-gray-900 font-bold mb-2">INFO PELANGGAN</h3>
                <p class="font-semibold">{{ $order->user->name ?? 'Guest' }}</p>
                <p>{{ $order->user->email ?? '-' }}</p>
                <p>{{ $order->user->phone ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-gray-900 font-bold mb-2">ALAMAT PENGIRIMAN</h3>
                @if($order->shippingAddress)
                    <p class="font-semibold">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                    <p>{{ $order->shippingAddress->address1 }}</p>
                    @if($order->shippingAddress->address2)
                        <p>{{ $order->shippingAddress->address2 }}</p>
                    @endif
                    <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}</p>
                    <p>Phone: {{ $order->shippingAddress->phone }}</p>
                @else
                    <p class="text-gray-500 italic">Alamat tidak tersedia</p>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <table class="w-full mb-8">
            <thead>
                <tr class="text-left border-b-2 border-gray-200">
                    <th class="py-3 font-semibold text-gray-700">Deskripsi Item</th>
                    <th class="py-3 font-semibold text-gray-700 text-center">Qty</th>
                    <th class="py-3 font-semibold text-gray-700 text-right">Harga Satuan</th>
                    <th class="py-3 font-semibold text-gray-700 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b border-gray-100">
                        <td class="py-3">
                            <p class="font-semibold text-gray-900">{{ $item->product_title }}</p>
                            @if($item->variant_title && $item->variant_title !== 'Default Variant')
                                <p class="text-sm text-gray-500">{{ $item->variant_title }}</p>
                            @endif
                        </td>
                        <td class="py-3 text-center">{{ $item->quantity }}</td>
                        <td class="py-3 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="py-3 text-right font-medium">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end mb-12">
            <div class="w-1/2">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <!-- <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Pajak (Tax)</span>
                    <span class="font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div> -->
                <div class="flex justify-between py-4 border-b-2 border-gray-900">
                    <span class="text-lg font-bold text-gray-900">TOTAL</span>
                    <span class="text-lg font-bold text-blue-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-12 border-t pt-8">
            <p class="mb-2">Terima kasih telah berbelanja di Mitologi Clothing!</p>
            <p>Silakan hubungi kami jika ada pertanyaan mengenai invoice ini.</p>
        </div>

        <div class="mt-8 text-center print:hidden">
            <button onclick="window.print()" class="bg-blue-900 text-white px-6 py-2 rounded-md hover:bg-blue-800 transition">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>
</body>
</html>
