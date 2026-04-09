<x-admin-layout>
    <x-admin-header
        title="Konfigurasi Aplikasi"
        :breadcrumbs="[['title' => 'Umum', 'url' => '#'], ['title' => 'Konfigurasi Aplikasi']]"
        action_text=""
        :action_url="''"
    />

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium overflow-hidden border border-gray-100 dark:border-gray-700 mb-8">
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200/80 bg-gray-50/80 px-6 py-4">
            <nav class="-mb-px flex gap-1 overflow-x-auto pb-2 pr-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <button onclick="switchTab('email')" id="tab-email" class="tab-btn group inline-flex shrink-0 items-center rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-gray-300 hover:text-mitologi-navy sm:px-6 sm:py-4">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email (SMTP)
                </button>
                <button onclick="switchTab('midtrans')" id="tab-midtrans" class="tab-btn group inline-flex shrink-0 items-center rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-gray-300 hover:text-mitologi-navy sm:px-6 sm:py-4">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Midtrans
                </button>
                <button onclick="switchTab('groq')" id="tab-groq" class="tab-btn group inline-flex shrink-0 items-center rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-gray-300 hover:text-mitologi-navy sm:px-6 sm:py-4">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Groq AI
                </button>
                <button onclick="switchTab('general')" id="tab-general" class="tab-btn group inline-flex shrink-0 items-center rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-gray-300 hover:text-mitologi-navy sm:px-6 sm:py-4">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    General
                </button>
                <button onclick="switchTab('audit')" id="tab-audit" class="tab-btn group inline-flex shrink-0 items-center rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-gray-300 hover:text-mitologi-navy sm:px-6 sm:py-4">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Audit Log
                </button>
            </nav>
        </div>
        
        <div class="p-6">
            <!-- Email Tab -->
            <div id="content-email" class="tab-content hidden">
                <div class="mb-6 flex flex-col gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-display font-semibold text-mitologi-navy">Konfigurasi Email (SMTP)</h3>
                        <p class="text-sm text-gray-500 mt-1">Pengaturan server email untuk notifikasi dan transaksi.</p>
                    </div>
                    <button onclick="testService('email')" class="inline-flex items-center justify-center rounded-lg bg-mitologi-navy px-4 py-2 text-white shadow-sm transition-colors hover:bg-mitologi-navy-light sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Test Email
                    </button>
                </div>
                
                <form action="{{ route('admin.app-configuration.update', 'email') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="mail_mailer" :value="__('Mail Driver')" />
                            <div class="relative mt-1">
                                <select id="mail_mailer" name="MAIL_MAILER" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 appearance-none cursor-pointer">
                                    <option value="smtp" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <x-input-label for="mail_host" :value="__('Mail Host')" />
                            <x-text-input id="mail_host" class="mt-1 block w-full" type="text" name="MAIL_HOST" :value="$configs['email']['MAIL_HOST'] ?? ''" placeholder="smtp.gmail.com" />
                        </div>
                        
                        <div>
                            <x-input-label for="mail_port" :value="__('Port')" />
                            <x-text-input id="mail_port" class="mt-1 block w-full" type="number" name="MAIL_PORT" :value="$configs['email']['MAIL_PORT'] ?? '587'" />
                        </div>
                        
                        <div>
                            <x-input-label for="mail_encryption" :value="__('Encryption')" />
                            <div class="relative mt-1">
                                <select id="mail_encryption" name="MAIL_ENCRYPTION" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 appearance-none cursor-pointer">
                                    <option value="tls" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="null" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'null' ? 'selected' : '' }}>None</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <x-input-label for="mail_username" :value="__('Username')" />
                            <x-text-input id="mail_username" class="mt-1 block w-full" type="email" name="MAIL_USERNAME" :value="$configs['email']['MAIL_USERNAME'] ?? ''" placeholder="your@email.com" />
                        </div>
                        
                        <div>
                            <x-input-label for="mail_password" :value="__('Password')" />
                            <div class="relative mt-1">
                                <x-text-input id="mail_password" class="block w-full pr-10" type="password" name="MAIL_PASSWORD" :value="$configs['email']['MAIL_PASSWORD'] ?? ''" />
                                <button type="button" onclick="togglePassword('mail_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg id="mail_password_eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg id="mail_password_eye_off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Untuk Gmail, gunakan App Password, bukan password login.</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-input-label for="mail_from_address" :value="__('From Address')" />
                            <x-text-input id="mail_from_address" class="mt-1 block w-full" type="email" name="MAIL_FROM_ADDRESS" :value="$configs['email']['MAIL_FROM_ADDRESS'] ?? ''" placeholder="noreply@yourdomain.com" />
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-mitologi-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-mitologi-navy-light">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengaturan Email
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Midtrans Tab -->
            <div id="content-midtrans" class="tab-content hidden">
                <div class="mb-6 flex flex-col gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-display font-semibold text-mitologi-navy">Konfigurasi Midtrans (Payment Gateway)</h3>
                        <p class="text-sm text-gray-500 mt-1">Integrasi pembayaran online.</p>
                    </div>
                    <button onclick="testService('midtrans')" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-white shadow-sm transition-colors hover:bg-green-700 sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Test Koneksi
                    </button>
                </div>
                
                <form action="{{ route('admin.app-configuration.update', 'midtrans') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="midtrans_env" :value="__('Environment')" />
                            <div class="relative mt-1">
                                <select id="midtrans_env" name="MIDTRANS_IS_PRODUCTION" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-mitologi-gold/50 focus:border-mitologi-gold rounded-xl shadow-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 appearance-none cursor-pointer">
                                    <option value="false" {{ ($configs['midtrans']['MIDTRANS_IS_PRODUCTION'] ?? '') === 'false' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                    <option value="true" {{ ($configs['midtrans']['MIDTRANS_IS_PRODUCTION'] ?? '') === 'true' ? 'selected' : '' }}>Production (Live)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-amber-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Gunakan Sandbox untuk testing. Switch ke Production saat sudah siap.
                            </p>
                        </div>
                        
                        <div>
                            <x-input-label for="midtrans_merchant" :value="__('Merchant ID')" />
                            <x-text-input id="midtrans_merchant" class="mt-1 block w-full" type="text" name="MIDTRANS_MERCHANT_ID" :value="$configs['midtrans']['MIDTRANS_MERCHANT_ID'] ?? ''" />
                        </div>
                        
                        <div>
                            <x-input-label for="midtrans_client" :value="__('Client Key')" />
                            <x-text-input id="midtrans_client" class="mt-1 block w-full" type="text" name="MIDTRANS_CLIENT_KEY" :value="$configs['midtrans']['MIDTRANS_CLIENT_KEY'] ?? ''" placeholder="SB-Mid-client-xxx" />
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-input-label for="midtrans_server" :value="__('Server Key')" />
                            <div class="relative mt-1">
                                <x-text-input id="midtrans_server" class="block w-full pr-10" type="password" name="MIDTRANS_SERVER_KEY" :value="$configs['midtrans']['MIDTRANS_SERVER_KEY'] ?? ''" placeholder="SB-Mid-server-xxx" />
                                <button type="button" onclick="togglePassword('midtrans_server')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg id="midtrans_server_eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg id="midtrans_server_eye_off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Rahasia! Jangan pernah membagikan atau mengekspos di frontend.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-green-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengaturan Midtrans
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Groq Tab -->
            <div id="content-groq" class="tab-content hidden">
                <div class="mb-6 flex flex-col gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-display font-semibold text-mitologi-navy">Konfigurasi Groq (AI Service)</h3>
                        <p class="text-sm text-gray-500 mt-1">Integrasi AI untuk fitur chatbot dan rekomendasi.</p>
                    </div>
                    <button onclick="testService('groq')" class="inline-flex items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-white shadow-sm transition-colors hover:bg-purple-700 sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Test API
                    </button>
                </div>
                
                <form action="{{ route('admin.app-configuration.update', 'groq') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <x-input-label for="groq_keys" :value="__('API Keys')" />
                        <x-textarea-input id="groq_keys" name="GROQ_API_KEYS" rows="3" class="mt-1 block w-full font-mono text-sm" placeholder="gsk_xxx,gsk_yyy">{{ $configs['groq']['GROQ_API_KEYS'] ?? '' }}</x-textarea-input>
                        <p class="mt-1 text-xs text-gray-500">Masukkan API key yang dipisahkan koma untuk rotasi. Key pertama adalah utama.</p>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-purple-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengaturan Groq
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- General Tab -->
            <div id="content-general" class="tab-content hidden">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-display font-semibold text-mitologi-navy">Pengaturan Umum</h3>
                    <p class="text-sm text-gray-500 mt-1">Konfigurasi URL dan layanan eksternal.</p>
                </div>
                
                <form action="{{ route('admin.app-configuration.update', 'general') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="frontend_url" :value="__('Frontend URL')" />
                            <x-text-input id="frontend_url" class="mt-1 block w-full" type="url" name="FRONTEND_URL" :value="$configs['general']['FRONTEND_URL'] ?? ''" placeholder="http://localhost:3000" />
                        </div>
                        
                        <div>
                            <x-input-label for="ai_service_url" :value="__('AI Service URL')" />
                            <x-text-input id="ai_service_url" class="mt-1 block w-full" type="url" name="AI_SERVICE_URL" :value="$configs['general']['AI_SERVICE_URL'] ?? ''" placeholder="http://127.0.0.1:8001/api" />
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-mitologi-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-mitologi-navy-light">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengaturan Umum
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Audit Log Tab -->
            <div id="content-audit" class="tab-content hidden">
                <div class="mb-8 rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-xl font-display font-semibold text-mitologi-navy">Backup Environment</h3>
                            <p class="mt-1 text-sm text-gray-500">Gunakan kembali backup konfigurasi yang sudah ada atau hapus backup yang tidak lagi diperlukan.</p>
                        </div>
                        <div class="rounded-2xl bg-mitologi-cream/70 px-4 py-3 text-xs font-semibold uppercase tracking-[0.18em] text-mitologi-navy">
                            {{ count($backups) }} Backup Tersedia
                        </div>
                    </div>

                    <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200/80">
                        @if(count($backups) > 0)
                            <div class="divide-y divide-gray-100 bg-white">
                                @foreach($backups as $backup)
                                    <div class="flex flex-col gap-4 px-4 py-4 sm:px-5 lg:flex-row lg:items-center lg:justify-between hover:bg-mitologi-cream/30 transition-colors">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center rounded-full bg-mitologi-navy/8 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-mitologi-navy">Backup</span>
                                                <code class="rounded-lg bg-gray-50 px-3 py-1 text-xs text-gray-700">{{ $backup['name'] }}</code>
                                            </div>
                                            <div class="mt-3 flex flex-wrap gap-4 text-sm text-gray-500">
                                                <span>Dibuat: <span class="font-medium text-gray-700">{{ $backup['modified_at'] }}</span></span>
                                                <span>Ukuran: <span class="font-medium text-gray-700">{{ number_format(($backup['size'] ?? 0) / 1024, 1) }} KB</span></span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                                            <form action="{{ route('admin.app-configuration.restore') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Gunakan backup ini sebagai .env aktif? Backup .env saat ini akan dibuat otomatis.');">
                                                @csrf
                                                <input type="hidden" name="backup" value="{{ $backup['name'] }}">
                                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-mitologi-navy px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-mitologi-navy-light sm:w-auto">
                                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m14.836 2A8.001 8.001 0 005.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-13.838-2m13.838 2H15"></path></svg>
                                                    Gunakan Lagi
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.app-configuration.destroy-backup') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Hapus backup ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="backup" value="{{ $backup['name'] }}">
                                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition-colors hover:bg-red-100 sm:w-auto">
                                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"></path></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada backup environment yang tersedia.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-6 flex flex-col gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-display font-semibold text-mitologi-navy">Riwayat Perubahan Konfigurasi</h3>
                        <p class="text-sm text-gray-500 mt-1">Log audit perubahan environment variables.</p>
                    </div>
                    @if(count($auditLogs) > 0)
                        <form action="{{ route('admin.app-configuration.destroy-all-audit-logs') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus SEMUA log audit? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition-colors hover:bg-red-100">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"></path></svg>
                                Hapus Semua Log
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-gray-50/80 dark:bg-gray-700/50 uppercase font-bold text-xs text-gray-500 dark:text-gray-300 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Grup</th>
                                <th class="px-6 py-4">Key</th>
                                <th class="px-6 py-4">IP Address</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($auditLogs as $log)
                            <tr class="hover:bg-mitologi-cream/30 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $log->user->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-mitologi-cream text-mitologi-navy border border-mitologi-gold/30">
                                        {{ $log->group }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-mono text-xs">{{ $log->key }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono text-xs">{{ $log->ip_address }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.app-configuration.destroy-audit-log', $log->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus log ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg p-2 text-red-500 transition-colors hover:bg-red-50 hover:text-red-700" title="Hapus Log">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50/30 dark:bg-gray-800/50">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-3">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada perubahan</h3>
                                        <p class="text-sm text-gray-500 mt-1">Log audit akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hasil Pengujian -->
    <div id="service-test-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-950/50 px-4" @if(session()->has('success')) data-flash-success='@json(session('success'))' @endif @if(session()->has('error')) data-flash-error='@json(session('error'))' @endif>
        <div class="bg-white w-full max-w-lg overflow-hidden rounded-2xl shadow-premium border border-gray-100">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-gray-50/80">
                <h3 id="service-test-title" class="text-lg font-bold text-mitologi-navy">Pengujian Layanan</h3>
                <button type="button" onclick="closeServiceTestModal()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="px-6 py-6">
                <div id="service-test-body" class="text-sm text-gray-700 leading-relaxed"></div>
            </div>
            
            <!-- Footer -->
            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex justify-end">
                    <button type="button" onclick="closeServiceTestModal()" class="inline-flex items-center justify-center rounded-lg bg-mitologi-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-mitologi-navy-light transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
function switchTab(tabName) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('border-mitologi-gold', 'text-mitologi-navy');
        el.classList.add('border-transparent', 'text-gray-500');
        // Reset icon color
        const icon = el.querySelector('svg');
        if (icon) {
            icon.classList.remove('text-mitologi-gold');
            icon.classList.add('text-gray-400');
        }
    });
    
    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Activate selected tab
    const tabBtn = document.getElementById('tab-' + tabName);
    tabBtn.classList.remove('border-transparent', 'text-gray-500');
    tabBtn.classList.add('border-mitologi-gold', 'text-mitologi-navy');
    // Set icon color
    const icon = tabBtn.querySelector('svg');
    if (icon) {
        icon.classList.remove('text-gray-400');
        icon.classList.add('text-mitologi-gold');
    }
    
    // Save to localStorage
    localStorage.setItem('config-active-tab', tabName);
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(inputId + '_eye');
    const eyeOffIcon = document.getElementById(inputId + '_eye_off');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}

function testService(service) {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';
    btn.disabled = true;
    
    fetch(`/admin/app-configuration/test/${service}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
        } else {
            showServiceTestModal(false, service, data.message);
        }
    })
    .catch(err => {
        showServiceTestModal(false, service, 'Test failed: ' + err.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function showServiceTestModal(success, service, message) {
    const modal = document.getElementById('service-test-modal');
    const title = document.getElementById('service-test-title');
    const body = document.getElementById('service-test-body');

    const serviceNames = {
        'email': 'Email (SMTP)',
        'midtrans': 'Midtrans',
        'groq': 'Groq AI',
        'backup': 'Backup Environment'
    };
    
    const serviceName = serviceNames[service] || service;

    // Title based on result
    title.textContent = success 
        ? `Berhasil - ${serviceName}` 
        : `Gagal - ${serviceName}`;
    
    // Body content with proper styling matching testimonials page
    if (success) {
        body.innerHTML = `
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-100 border border-green-200">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-bold text-gray-900 text-base">Pengujian Berhasil</p>
                    <p class="mt-2 text-gray-600 leading-relaxed">${message}</p>
                </div>
            </div>
        `;
    } else {
        body.innerHTML = `
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 border border-red-200">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-bold text-gray-900 text-base">Pengujian Gagal</p>
                    <p class="mt-2 text-gray-600 leading-relaxed">${message}</p>
                </div>
            </div>
        `;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeServiceTestModal() {
    const modal = document.getElementById('service-test-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Toast notification function
function showToast(type, message) {
    // Remove existing toast if any
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element with premium styling matching testimonials page
    const toast = document.createElement('div');
    toast.id = 'toast-notification';
    toast.className = `fixed top-4 right-4 z-50 flex items-center gap-3 rounded-2xl px-5 py-4 shadow-premium transform transition-all duration-300 translate-x-full ${
        type === 'success' 
            ? 'bg-green-50 border border-green-200 text-green-800' 
            : 'bg-red-50 border border-red-200 text-red-800'
    }`;
    
    const iconSvg = type === 'success'
        ? '<svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
        : '<svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    
    toast.innerHTML = `
        ${iconSvg}
        <span class="font-semibold text-sm flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full');
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Load saved tab on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTab = localStorage.getItem('config-active-tab') || 'email';
    switchTab(savedTab);

    const modal = document.getElementById('service-test-modal');
    const flashSuccess = modal?.dataset.flashSuccess;
    const flashError = modal?.dataset.flashError;

    if (flashSuccess && flashSuccess !== 'null' && flashSuccess !== '""') {
        showServiceTestModal(true, 'backup', flashSuccess);
    } else if (flashError && flashError !== 'null' && flashError !== '""') {
        showServiceTestModal(false, 'backup', flashError);
    }
});
</script>
</x-admin-layout>
