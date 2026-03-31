
<x-admin-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="admin-panel p-8 mb-10 overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <p class="text-[11px] uppercase tracking-[0.24em] text-mitologi-gold-dark font-semibold mb-3">Ringkasan Harian</p>
                <h2 class="text-4xl font-display font-semibold text-mitologi-navy mb-3 tracking-tight">Selamat datang kembali, {{ Auth::user()->name }}</h2>
                <p class="text-gray-600 text-base max-w-2xl leading-relaxed">Ringkasan operasi hari ini dengan fokus pada pesanan baru, performa produk, dan aktivitas pelanggan terbaru.</p>
            </div>
            <div class="rounded-2xl border border-[rgba(185,149,91,0.28)] bg-[var(--color-mitologi-cream)] px-5 py-4">
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500 mb-1">Butuh perhatian</p>
                <div class="text-3xl font-extrabold text-mitologi-navy">{{ $newOrders }}</div>
                <p class="text-sm text-gray-500 mt-1">pesanan baru menunggu proses</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-10">
        <!-- Products -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6 relative overflow-hidden flex flex-col justify-center min-h-[120px]">
            <div class="absolute right-0 bottom-0 pointer-events-none" style="transform: translate(10px, 10px);">
                <svg class="w-24 h-24" style="color: #f3f4f6;" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-widest mb-1">Total Produk</p>
                <div class="flex flex-row items-center gap-2">
                    <h4 class="text-3xl font-extrabold text-mitologi-navy dark:text-white">{{ $totalProducts }}</h4>
                    <span class="text-[11px] text-[#22c55e] font-semibold mt-1">Aktif</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-4 right-4 h-1 rounded-t-md" style="background-color: #1a233a;"></div>
        </div>

        <!-- Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6 relative overflow-hidden flex flex-col justify-center min-h-[120px]">
            <div class="absolute right-0 bottom-0 pointer-events-none" style="transform: translate(10px, 10px);">
                 <svg class="w-24 h-24" style="color: #dcfce7;" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-widest mb-1">Pendapatan</p>
                <div class="flex items-center">
                    <h4 class="text-3xl font-extrabold text-mitologi-navy dark:text-white tracking-tight">Rp {{ number_format($revenue, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="absolute bottom-0 left-4 right-4 h-1 rounded-t-md" style="background-color: #4ade80;"></div>
        </div>

        <!-- New Customers -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6 relative overflow-hidden flex flex-col justify-center min-h-[120px]">
            <div class="absolute right-0 bottom-0 pointer-events-none" style="transform: translate(10px, 10px);">
                 <svg class="w-24 h-24" style="color: #eff6ff;" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-widest mb-1">Pelanggan Baru</p>
                <div class="flex flex-row items-center gap-2">
                    <h4 class="text-3xl font-extrabold text-mitologi-navy dark:text-white">{{ $newCustomers }}</h4>
                    <span class="text-[11px] text-[#3b82f6] font-mono tracking-tighter mt-1">+30 Hari</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-4 right-4 h-1 rounded-t-md" style="background-color: #3b82f6;"></div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-6 relative overflow-hidden flex flex-col justify-center min-h-[120px]">
            <div class="absolute right-0 bottom-0 pointer-events-none" style="transform: translate(10px, 10px);">
                 <svg class="w-24 h-24" style="color: #fefce8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 001-1l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
            </div>
            <div class="relative z-10">
                <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-widest mb-1">Pesanan Pending</p>
                <div class="flex flex-row items-center gap-2">
                    <h4 class="text-3xl font-extrabold text-mitologi-navy dark:text-white">{{ $newOrders }}</h4>
                    <span class="text-[10px] px-2 py-0.5 rounded-full text-white font-bold w-max" style="background-color: #dfa100; margin-top: 2px;">Butuh Proses</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-4 right-4 h-1 rounded-t-md" style="background-color: #dfa100;"></div>
        </div>
    </div>

    <!-- Charts & Tables Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                     <h5 class="text-xl font-bold text-mitologi-navy dark:text-white">Grafik Penjualan</h5>
                     <p class="text-sm text-gray-500 mt-1">Ringkasan performa pendapatan Anda</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-mitologi-gold"></span>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">30 Hari Terakhir</span>
                </div>
            </div>
            <div id="sales-chart" class="w-full h-96"></div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-0 flex flex-col overflow-hidden h-full">
             <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex justify-between items-center">
                <h5 class="text-lg font-bold text-mitologi-navy dark:text-white">Pesanan Terbaru</h5>
                 <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-mitologi-gold hover:text-mitologi-gold-dark transition-colors">Lihat Semua</a>
            </div>
            
            <div class="overflow-y-auto scrollbar-hide flex-1 p-4">
                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 hover:border-mitologi-gold/50 hover:shadow-md transition-all group">
                        <div class="flex items-center space-x-4">
                             @if($order->user && $order->user->avatar)
                                <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="{{ $order->user->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-100 dark:border-gray-600 group-hover:border-mitologi-gold transition-colors">
                             @else
                                <div class="w-12 h-12 rounded-full bg-mitologi-navy/5 text-mitologi-navy dark:bg-gray-600 dark:text-white flex items-center justify-center font-bold text-lg group-hover:bg-mitologi-navy group-hover:text-white transition-colors">
                                {{ substr($order->user->name ?? 'G', 0, 1) }}
                            </div>
                             @endif
                            <div>
                                <h6 class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-mitologi-navy dark:group-hover:text-mitologi-gold transition-colors">{{ $order->user->name ?? 'Tamu' }}</h6>
                                <p class="text-xs text-gray-500">Order #{{ $order->order_number ?? $order->id }}</p>
                            </div>
                        </div>
                         <div class="text-right">
                             <p class="text-sm font-bold text-mitologi-navy dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                             <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide mt-1
                                {{ match($order->status) {
                                    'paid', 'completed', 'shipped' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'refunded' => 'bg-purple-100 text-purple-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-600'
                                } }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-gray-500">Belum ada pesanan terbaru.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Secondary Grid: Products & Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Top Selling Products -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-premium overflow-hidden">
             <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 flex justify-between items-center">
                <h5 class="text-lg font-bold text-mitologi-navy dark:text-white">Produk Terlaris</h5>
                  <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-mitologi-gold hover:text-mitologi-gold-dark transition-colors">Lihat Semua</a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4 text-center">Terjual</th>
                                <th class="px-6 py-4 text-right">Total Pendapatan (Est)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                             @forelse($topProducts as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200 dark:border-gray-700">
                                            @if($item->product && $item->product->featured_image)
                                                <img src="{{ asset('storage/'.$item->product->featured_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="text-sm font-bold text-mitologi-navy dark:text-white group-hover:text-mitologi-gold transition-colors">{{ $item->product ? $item->product->title : 'Produk Dihapus' }}</h6>
                                            <p class="text-xs text-gray-500">{{ $item->total_sold }} item terjual</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->total_sold }} Terjual
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-gray-700 dark:text-gray-300">
                                        Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">Belum ada data penjualan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Low Stock & Testimonials -->
        <div class="space-y-8 h-full flex flex-col">
            <!-- Low Stock -->
            @if($lowStockProducts->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium overflow-hidden border-t-4 border-red-500">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h5 class="text-sm font-bold text-red-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Stok Menipis
                    </h5>
                     <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-500">Kelola</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($lowStockProducts as $variant)
                    <div class="p-3 flex items-center justify-between hover:bg-red-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-gray-100 overflow-hidden">
                                 @if($variant->product && $variant->product->featured_image)
                                    <img src="{{ asset('storage/'.$variant->product->featured_image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-mitologi-navy dark:text-white truncate max-w-[120px]">{{ $variant->product ? $variant->product->title : 'Produk Dihapus' }}</p>
                                <p class="text-[10px] text-gray-500">{{ $variant->title }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">
                            Sisa {{ $variant->stock }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Latest Testimonials -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium overflow-hidden flex-1">
                 <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                    <h5 class="text-base font-bold text-mitologi-navy dark:text-white">Ulasan Terbaru</h5>
                      <a href="{{ route('admin.beranda.testimonials.index') }}" class="text-xs font-bold text-mitologi-gold hover:text-mitologi-gold-dark transition-colors">Semua</a>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($recentTestimonials as $testimonial)
                    <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-700/30 relative">
                        <div class="absolute top-4 right-4 text-xs text-yellow-400 flex">
                            {{ str_repeat('★', $testimonial->rating) }}
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                             @if($testimonial->avatar_url)
                                <img src="{{ asset('storage/' . $testimonial->avatar_url) }}" class="w-8 h-8 rounded-full object-cover border border-mitologi-gold/50">
                            @else
                                <div class="w-8 h-8 rounded-full bg-mitologi-navy text-white flex items-center justify-center text-xs font-bold">{{ substr($testimonial->name, 0, 1) }}</div>
                            @endif
                            <h6 class="text-sm font-bold text-mitologi-navy dark:text-white">{{ $testimonial->name }}</h6>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300 italic line-clamp-2">"{{ $testimonial->content }}"</p>
                    </div>
                    @empty
                     <div class="text-center py-6 text-gray-400 text-sm">Belum ada ulasan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prepare data from PHP
            const salesData = @json($salesChart);
            const categories = salesData.map(item => item.date);
            const seriesData = salesData.map(item => item.aggregate);

            var options = {
                series: [{
                    name: 'Penjualan',
                    data: seriesData
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#C5A059'], // Mitologi Gold
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#9CA3AF' }
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#9CA3AF' },
                        formatter: function (value) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                grid: {
                    borderColor: '#E5E7EB',
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                             return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-admin-layout>
