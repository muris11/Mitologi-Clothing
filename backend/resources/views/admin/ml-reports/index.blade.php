<x-admin-layout>
    <x-admin-header 
        title="SPK & Rekomendasi AI" 
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.dashboard')], ['title' => 'SPK & Rekomendasi AI']]"
    >
        <x-slot name="actions">
            <!-- Retrain Form -->
            <form action="{{ route('admin.ml-reports.retrain') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melatih ulang (retrain) model AI dengan data transaksi terbaru? Proses ini mungkin memerlukan waktu beberapa detik.');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-mitologi-navy hover:bg-mitologi-gold text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Retrain Model Sekarang
                </button>
            </form>
        </x-slot>
    </x-admin-header>

    <div class="space-y-6">
        <!-- AI Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-premium border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Status AI Recommendation Service (Naive Bayes)</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Terakhir Dilatih (Retrain): <span class="font-medium text-mitologi-navy dark:text-gray-300">
                            {{ $mlStatus['last_trained_at'] instanceof \Carbon\Carbon ? $mlStatus['last_trained_at']->translatedFormat('d F Y H:i:s') : $mlStatus['last_trained_at'] }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($mlStatus['healthy'])
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/40 px-3 py-1.5 rounded-full border border-emerald-200 dark:border-emerald-800">Service Online</span>
                @else
                    <span class="flex h-3 w-3 relative">
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                    <span class="text-sm font-bold text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/40 px-3 py-1.5 rounded-full border border-red-200 dark:border-red-800">Service Offline</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Products (SPK Naive Bayes Priority) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center text-orange-500 shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-mitologi-navy dark:text-white">SPK: Produk Terlaris</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Rekomendasi Collaborative Filtering berdasar data beli.</p>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 uppercase font-bold text-[11px] tracking-wider text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3 w-16 text-center">Rank</th>
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3 text-center w-28">Terjual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($topProducts as $index => $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full text-xs font-bold {{ $index < 3 ? 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $product->title }}</span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                                        {{ $product->purchase_count }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500">Belum ada data cukup untuk SPK.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Trending Products -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center text-rose-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-mitologi-navy dark:text-white">Trending Produk </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Trend 7 hari terakhir (Kunjungan, Cart, & Beli).</p>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 uppercase font-bold text-[11px] tracking-wider text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3 w-16 text-center">Rank</th>
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3 text-center w-28">Skor Trend</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($trendingProducts as $index => $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full text-xs font-bold {{ $index < 3 ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $product->title }}</div>
                                    <div class="text-[10px] text-gray-500 mt-0.5">{{ $product->unique_users }} user interaksi</div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-fuchsia-50 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-400 border border-fuchsia-100 dark:border-fuchsia-500/20">
                                        {{ $product->trend_score }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500">Belum ada trend 7 hari terakhir.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Stock Recommendations (Restock Warning) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col lg:col-span-2">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-mitologi-navy dark:text-white">Rekomendasi AI Restock Stok Habis</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Produk stok &le; 10 tapi laju kecepatan terjual (velocity) lumayan tinggi.</p>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 uppercase font-bold text-[11px] tracking-wider text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3 text-center w-28">Stok Sisa</th>
                                <th class="px-5 py-3 text-center w-36">Sales (7 Hari)</th>
                                <th class="px-5 py-3">Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($stockRecommendations as $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $product->title }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Varian: <span class="text-gray-700 dark:text-gray-300">{{ $product->variant_title }}</span></div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $product->stock <= 5 ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400 border border-red-100 dark:border-red-500/20' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $product->recent_purchases }}</span> unit
                                </td>
                                <td class="px-5 py-3 text-xs">
                                    @php
                                        $velocity = $product->velocity_score;
                                        $daysToEmpty = $velocity > 0 ? floor($product->stock / $velocity) : 99;
                                    @endphp
                                    @if($daysToEmpty <= 3)
                                        <div class="flex items-center gap-1.5 text-red-600 dark:text-red-400 font-medium">
                                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg> 
                                            Kritis (Habis ~{{ $daysToEmpty }} hari)
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-amber-600 dark:text-amber-400 font-medium">
                                            Restock minggu ini (~{{ $daysToEmpty }} hari)
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">Stok produk trending saat ini aman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Spenders -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-mitologi-navy dark:text-white">Top Spenders (Pelanggan Sultan)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pelanggan dengan nominal checkout total terbanyak.</p>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 uppercase font-bold text-[11px] tracking-wider text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3 w-16 text-center">Rank</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3 text-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($topSpenders as $index => $customer)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full text-xs font-bold {{ $index < 3 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($customer->avatar)
                                            <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 shadow-sm">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs font-bold shadow-sm ring-2 ring-white dark:ring-gray-800">
                                                {{ substr($customer->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                            <div class="text-[11px] text-gray-500 mt-0.5">{{ $customer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500">Belum ada data pelanggan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Frequent Shoppers -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-mitologi-navy dark:text-white">Frequent Shoppers (Pelanggan Setia)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pelanggan yang sering checkout di toko.</p>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 uppercase font-bold text-[11px] tracking-wider text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3 w-16 text-center">Rank</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3 text-center w-36">Total Order</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($frequentShoppers as $index => $customer)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full text-xs font-bold {{ $index < 3 ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                     <div class="flex items-center gap-3">
                                        @if($customer->avatar)
                                            <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-white dark:ring-gray-800 shadow-sm">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs font-bold shadow-sm ring-2 ring-white dark:ring-gray-800">
                                                {{ substr($customer->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                            <div class="text-[11px] text-gray-500 mt-0.5">{{ $customer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400 border border-cyan-100 dark:border-cyan-500/20">
                                        {{ $customer->total_orders }}x Order
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-gray-500">Belum ada pelanggan setia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-admin-layout>
