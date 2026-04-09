<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mitologi Admin') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <!-- Local Fonts loaded via app.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans antialiased admin-shell text-gray-900 dark:text-gray-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex admin-shell">
        
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-72 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:fixed md:top-4 md:bottom-4 md:left-4 flex flex-col rounded-none md:rounded-[28px] shadow-[0_24px_60px_-32px_rgba(20,32,51,0.55)] overflow-hidden border border-[#31425d]"
            style="background: linear-gradient(180deg, #1d2b3f 0%, #24344b 100%);"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
        >
            <!-- Logo -->
            <div class="relative h-24 flex items-center justify-center border-b border-white/10 px-6 bg-white/5 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 group">
                    <span class="text-[2rem] font-display font-semibold text-white tracking-tight">Mitologi</span>
                    <span class="text-[10px] uppercase tracking-[0.28em] text-mitologi-gold/80">Pusat Operasional</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="relative flex-1 px-4 py-8 space-y-2 overflow-y-auto scrollbar-hide">
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <!-- Group: Halaman Frontend -->
                <div class="mb-6 pt-4 space-y-2">
                    <p class="px-4 text-[10px] font-bold text-mitologi-gold/60 uppercase tracking-widest mb-4">Halaman Frontend</p>

                    {{-- Beranda (Collapsible) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.beranda.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 {{ request()->routeIs('admin.beranda.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.beranda.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Beranda
                            </div>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-11 pr-4 py-2 space-y-1">
                            <a href="{{ route('admin.beranda.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.index') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Atur Halaman</a>
                            <a href="{{ route('admin.beranda.hero-slides.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.hero-slides.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Slider Hero</a>
                            <a href="{{ route('admin.beranda.product-pricings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.product-pricings.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Daftar Harga Kategori</a>
                            <a href="{{ route('admin.beranda.materials.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.materials.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Pilihan Material</a>
                            <a href="{{ route('admin.beranda.order-steps.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.order-steps.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Alur Pemesanan</a>
                            <a href="{{ route('admin.beranda.portfolio.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.portfolio.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Portofolio</a>
                            <a href="{{ route('admin.beranda.partners.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.partners.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Klien & Partner</a>
                            <a href="{{ route('admin.beranda.testimonials.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.beranda.testimonials.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Testimonial</a>
                        </div>
                    </div>

                    {{-- Tentang Kami (Collapsible) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.tentang-kami.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 {{ request()->routeIs('admin.tentang-kami.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.tentang-kami.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tentang Kami
                            </div>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-11 pr-4 py-2 space-y-1">
                            <a href="{{ route('admin.tentang-kami.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.tentang-kami.index') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Atur Halaman</a>
                            <a href="{{ route('admin.tentang-kami.facilities.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.tentang-kami.facilities.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Fasilitas Produksi</a>
                            <a href="{{ route('admin.tentang-kami.team-members.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.tentang-kami.team-members.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Struktur Organisasi</a>
                        </div>
                    </div>

                    {{-- Kategori (Direct Link) --}}
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.categories.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        Kategori
                    </a>

                    {{-- Layanan (Collapsible) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.layanan.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 {{ request()->routeIs('admin.layanan.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.layanan.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Layanan
                            </div>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="pl-11 pr-4 py-2 space-y-1">
                            <a href="{{ route('admin.layanan.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.layanan.index') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Atur Halaman</a>
                            <a href="{{ route('admin.layanan.printing-methods.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.layanan.printing-methods.*') ? 'text-white bg-white/10 font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Metode Sablon</a>
                        </div>
                    </div>

                    {{-- Portofolio (Direct Link to Beranda's Portfolio for now as requested) --}}
                    <a href="{{ route('admin.beranda.portfolio.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.beranda.portfolio.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.beranda.portfolio.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Portofolio
                    </a>

                    {{-- Kontak (Direct Link) --}}
                    <a href="{{ route('admin.kontak.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.kontak.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.kontak.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Kontak
                    </a>
                </div>

                <!-- Group: Toko Online -->
                <div class="mb-6 border-t border-gray-700/50 pt-4">
                    <p class="px-4 text-[10px] font-bold text-mitologi-gold/60 uppercase tracking-widest mb-4">Toko Online</p>
                    
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.products.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.products.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Produk
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.orders.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.orders.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Pesanan
                    </a>
                     <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.customers.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.customers.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Pelanggan
                    </a>
                    <a href="{{ route('admin.sales-report.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.sales-report.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.sales-report.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Laporan
                    </a>
                    
                    <!-- Rekomendasi / SPK Reports -->
                    <a href="{{ route('admin.ml-reports.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.ml-reports.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.ml-reports.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        SPK & Rekomendasi
                    </a>
                </div>

                <!-- Group: Umum -->
                <div class="mb-8 border-t border-gray-700/50 pt-4">
                    <p class="px-4 text-[10px] font-bold text-mitologi-gold/60 uppercase tracking-widest mb-4">Umum</p>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.settings.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Pengaturan
                    </a>
                    
                    <!-- App Configuration -->
                    <a href="{{ route('admin.app-configuration.index') }}" class="flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group {{ request()->routeIs('admin.app-configuration.*') ? 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.app-configuration.*') ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Konfigurasi Aplikasi
                    </a>
                </div>
            </nav>
            <!-- Removed Lihat Toko Button -->
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative md:ml-80">
             <!-- Top Header -->
             <header class="h-20 md:h-24 bg-transparent z-40 sticky top-0 px-3 md:px-5 lg:px-8 flex items-center justify-between transition-all duration-300 pointer-events-none">
                  <!-- Glass Container for Header Content -->
                  <div class="w-full h-14 md:h-16 bg-white/90 dark:bg-gray-800/90 shadow-glass rounded-xl md:rounded-[22px] flex items-center justify-between px-3 md:px-5 pointer-events-auto border border-gray-200/80 mt-2 md:mt-4">
                     
                    <div class="flex items-center md:hidden">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>

                    <!-- Breadcrumb/Title Area -->
                    <div class="hidden md:flex flex-col">
                        <h2 class="text-lg font-bold text-mitologi-navy dark:text-white tracking-tight">
                            {{ $header ?? 'Dashboard' }}
                        </h2>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center space-x-2 md:space-x-6">
                         <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2.5 text-gray-400 hover:text-mitologi-gold transition-all duration-300 hover:bg-gray-100/80 dark:hover:bg-gray-700/80 rounded-xl focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <!-- Ping Animation for Badge -->
                                @if($adminNotificationCount > 0)
                                <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white dark:border-gray-800"></span>
                                </span>
                                @endif
                            </button>
                            
                             <div 
                                x-show="open" 
                                @click.away="open = false" 
                                class="absolute right-[-60px] md:right-0 mt-3 w-[280px] sm:w-80 bg-white dark:bg-gray-800 rounded-xl shadow-2xl py-2 z-50 border border-gray-100 dark:border-gray-700 overflow-hidden transform origin-top-right"
                                style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                            >
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Notifikasi</h3>
                                    <span id="notification-count-text" class="text-xs text-mitologi-gold font-medium">
                                        @if($adminNotificationCount > 0)
                                            {{ $adminNotificationCount }} Baru
                                        @endif
                                    </span>
                                </div>
                                <div id="notification-list" class="max-h-64 overflow-y-auto scrollbar-hide">
                                    @include('layouts.partials.notification-list')
                                </div>
                                @if($adminNotificationCount > 0)
                                <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 text-center">
                                    <span class="text-xs text-gray-400">Menampilkan {{ $adminNotificationCount }} notifikasi terbaru</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover shadow-md border-2 border-mitologi-gold group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-mitologi-navy to-mitologi-navy-light text-white flex items-center justify-center font-bold text-lg shadow-md border-2 border-mitologi-gold group-hover:scale-105 transition-transform duration-300">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-200 leading-tight group-hover:text-mitologi-navy transition-colors">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wide">Admin</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-mitologi-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                class="absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl py-2 z-50 border border-gray-100 dark:border-gray-700 overflow-hidden transform origin-top-right"
                                style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                            >
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 mb-1">
                                    <p class="text-sm text-gray-900 dark:text-white font-bold">Masuk sebagai</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 transition-colors">
                                        Keluar
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-5 lg:p-8 pt-4">
                {{ $slot }}
            </main>
        </div>
        
        <!-- Backdrop -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-mitologi-navy/50 backdrop-blur-sm md:hidden"
            style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- Global Toast Notifications -->
        <div 
            x-data="{
                toasts: [],
                addToast(message, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, message, type });
                    setTimeout(() => this.removeToast(id), 4000);
                },
                removeToast(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            }"
            x-init="
                @if(session('success')) addToast('{{ session('success') }}', 'success'); @endif
                @if(session('error')) addToast('{{ session('error') }}', 'error'); @endif
                @if(session('warning')) addToast('{{ session('warning') }}', 'warning'); @endif
            "
            class="fixed top-6 right-6 z-[100] space-y-3 pointer-events-none"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div 
                    x-show="true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-8"
                    class="pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl backdrop-blur-xl border min-w-[320px] max-w-md cursor-pointer"
                    :class="{
                        'bg-green-50/90 border-green-200 text-green-800': toast.type === 'success',
                        'bg-red-50/90 border-red-200 text-red-800': toast.type === 'error',
                        'bg-yellow-50/90 border-yellow-200 text-yellow-800': toast.type === 'warning'
                    }"
                    @click="removeToast(toast.id)"
                >
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <template x-if="toast.type === 'success'">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                        </template>
                    </div>
                    <!-- Message -->
                    <p class="text-sm font-medium flex-1" x-text="toast.message"></p>
                    <!-- Close -->
                    <button @click.stop="removeToast(toast.id)" class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('notification-list')) return;
    
            const updateNotifications = () => {
                 fetch('{{ route("admin.notifications.latest") }}')
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Update Badge
                        const badge = document.getElementById('notification-badge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.style.display = 'block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                        
                        // Update Count Text
                        const countText = document.getElementById('notification-count-text');
                        if (countText) {
                             if (data.count > 0) {
                                countText.innerText = data.count + ' Baru';
                            } else {
                                countText.innerText = '';
                            }
                        }
    
                        // Update List
                        const list = document.getElementById('notification-list');
                        if (list) {
                            list.innerHTML = data.html;
                        }
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            };
    
            // Poll every 15 seconds
            setInterval(updateNotifications, 15000);
        });
    </script>
</body>
</html>
