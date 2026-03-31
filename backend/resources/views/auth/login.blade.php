<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Mitologi Clothing Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="admin-shell min-h-screen flex items-center justify-center font-sans p-4">
    <div class="max-w-6xl w-full bg-white rounded-[30px] shadow-[0_28px_80px_-42px_rgba(20,32,51,0.45)] overflow-hidden flex flex-col md:flex-row border border-gray-200/80">
        <!-- Brand Section -->
        <div class="hidden md:flex md:w-1/2 bg-[var(--color-mitologi-cream)] relative overflow-hidden items-center justify-center p-12 text-center border-r border-gray-200/80">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(185,149,91,0.14),transparent_26%),linear-gradient(180deg,rgba(255,255,255,0.85),transparent_45%)] opacity-100"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center h-full">
                <img src="{{ asset('images/logo.png') }}" alt="Mitologi Clothing Logo" class="w-[28rem] h-auto drop-shadow-md">
                <div class="mt-8 max-w-sm text-center">
                    <p class="text-[11px] uppercase tracking-[0.28em] text-mitologi-gold-dark font-semibold mb-4">Admin access</p>
                    <p class="text-gray-600 leading-relaxed">Kelola katalog, pesanan, pelanggan, dan halaman storefront dalam satu panel yang rapi dan profesional.</p>
                </div>
            </div>
        </div>

        <!-- content Section -->
        <div class="w-full md:w-1/2 p-6 md:p-12 flex flex-col justify-center bg-[#1d2b3f] relative">
            <div class="mb-8 text-center">
                <!-- Mobile Logo -->
                <div class="md:hidden flex justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="Mitologi Logo" class="w-24 h-auto drop-shadow-lg bg-white/10 rounded-full p-2 border border-white/10">
                </div>

                <p class="text-[11px] uppercase tracking-[0.24em] text-mitologi-gold/70 font-semibold mb-4">Secure sign in</p>
                <h2 class="text-4xl md:text-5xl font-display font-semibold text-white mb-3 tracking-tight">Masuk ke panel admin</h2>
                <p class="text-gray-300 text-sm leading-relaxed max-w-sm mx-auto">Akses dashboard operasional Mitologi dengan tampilan yang lebih fokus dan nyaman dibaca.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1 ml-1">Email Address</label>
                    <div class="relative">
                        <input id="email" class="block w-full px-4 py-4 pl-11 rounded-xl border border-white/10 bg-white/8 text-white placeholder-gray-400 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold focus:bg-white/10 transition-all duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@mitologiclothing.com" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                     <label for="password" class="block text-sm font-medium text-gray-300 mb-1 ml-1">Password</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            class="block w-full px-4 py-4 pl-11 pr-11 rounded-xl border border-white/10 bg-white/8 text-white placeholder-gray-400 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold focus:bg-white/10 transition-all duration-200" 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            required 
                            autocomplete="current-password" 
                            placeholder="••••••••" 
                        />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <button 
                            type="button" 
                            @click="show = !show" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-mitologi-gold focus:outline-none transition-colors"
                        >
                            <!-- Eye Icon (Show) -->
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <!-- Eye Slash Icon (Hide) -->
                            <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-600 bg-white/5 text-mitologi-gold shadow-sm focus:ring-mitologi-gold" name="remember">
                        <span class="ml-2 text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-mitologi-gold hover:text-white transition-colors font-medium" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full py-4 bg-mitologi-gold text-mitologi-navy rounded-xl font-bold hover:bg-[#c9a76d] transition-colors duration-200 shadow-lg flex items-center justify-center gap-2 tracking-[0.08em] uppercase text-sm">
                    <span>MASUK SEKARANG</span>
                </button>
            </form>
            
            <div class="mt-8 text-center border-t border-white/5 pt-6">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} Mitologi Clothing. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
