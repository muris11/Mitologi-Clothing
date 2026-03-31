<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mitologi Clothing - Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    colors: {
                        mitologi: {
                            navy: '#1a1a2e',
                            gold: '#d4af37',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">
    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row transform transition-all hover:scale-[1.01] duration-500">
        <!-- Brand Section -->
        <div class="md:w-1/2 bg-mitologi-navy relative overflow-hidden flex items-center justify-center p-12 text-center group">
             <!-- Decorative Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-mitologi-navy via-[#232342] to-black opacity-100"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-mitologi-gold rounded-full mix-blend-overlay filter blur-3xl opacity-20 group-hover:opacity-30 transition-opacity duration-700"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-overlay filter blur-3xl opacity-10 group-hover:opacity-20 transition-opacity duration-700"></div>
            
            <div class="relative z-10">
                <h1 class="text-white text-5xl font-bold tracking-widest mb-4 drop-shadow-lg">MITOLOGI</h1>
                <div class="h-1 w-24 bg-mitologi-gold mx-auto mb-4 rounded-full"></div>
                <p class="text-gray-300 tracking-widest uppercase text-sm font-medium">Clothing Backend Service</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="md:w-1/2 p-12 flex flex-col justify-center bg-white relative">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-mitologi-navy mb-3 tracking-tight">Backend Services</h2>
                <p class="text-gray-500 leading-relaxed">
                    Sistem manajemen konten dan administrasi terpusat untuk Mitologi Clothing.
                </p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="group block w-full text-center py-4 bg-mitologi-navy text-white rounded-xl font-bold hover:bg-[#232342] transition-all duration-300 shadow-lg hover:shadow-mitologi-navy/30 flex items-center justify-center gap-2">
                    <span>Masuk ke Admin Panel</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase tracking-wider font-semibold">Navigasi</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <a href="{{ config('app.frontend_url') }}" target="_blank" class="block w-full text-center py-4 border-2 border-gray-100 text-mitologi-navy rounded-xl font-bold hover:border-mitologi-navy hover:bg-gray-50 transition-all duration-300">
                    Kunjungi Toko (Frontend)
                </a>
            </div>

            <div class="mt-10 text-center text-gray-400 text-xs">
                &copy; {{ date('Y') }} Mitologi Clothing. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
