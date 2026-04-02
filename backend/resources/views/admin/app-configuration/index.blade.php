<x-admin-layout>
    <x-admin-header 
        title="Konfigurasi Aplikasi" 
        :breadcrumbs="[
            ['title' => 'Admin', 'url' => route('admin.dashboard')],
            ['title' => 'Konfigurasi Aplikasi']
        ]" 
    />

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif
    
    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex -mb-px space-x-1">
            <button onclick="switchTab('email')" id="tab-email" class="tab-btn group inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-mitologi-navy hover:border-gray-300 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Email (SMTP)
            </button>
            <button onclick="switchTab('midtrans')" id="tab-midtrans" class="tab-btn group inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-mitologi-navy hover:border-gray-300 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Midtrans
            </button>
            <button onclick="switchTab('groq')" id="tab-groq" class="tab-btn group inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-mitologi-navy hover:border-gray-300 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Groq AI
            </button>
            <button onclick="switchTab('general')" id="tab-general" class="tab-btn group inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-mitologi-navy hover:border-gray-300 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                General
            </button>
            <button onclick="switchTab('audit')" id="tab-audit" class="tab-btn group inline-flex items-center px-6 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-mitologi-navy hover:border-gray-300 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-mitologi-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Audit Log
            </button>
        </nav>
    </div>
    
    <!-- Email Tab -->
    <div id="content-email" class="tab-content hidden">
        <x-admin-card>
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-display font-semibold text-mitologi-navy">Konfigurasi Email (SMTP)</h3>
                    <p class="text-sm text-gray-500 mt-1">Pengaturan server email untuk notifikasi dan transaksi.</p>
                </div>
                <button onclick="testService('email')" class="inline-flex items-center px-4 py-2 bg-mitologi-navy text-white rounded-lg hover:bg-mitologi-navy-light transition-colors shadow-sm">
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
                        <select id="mail_mailer" name="MAIL_MAILER" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-mitologi-gold focus:ring-mitologi-gold shadow-sm">
                            <option value="smtp" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        </select>
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
                        <select id="mail_encryption" name="MAIL_ENCRYPTION" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-mitologi-gold focus:ring-mitologi-gold shadow-sm">
                            <option value="tls" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'null' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    
                    <div>
                        <x-input-label for="mail_username" :value="__('Username')" />
                        <x-text-input id="mail_username" class="mt-1 block w-full" type="email" name="MAIL_USERNAME" :value="$configs['email']['MAIL_USERNAME'] ?? ''" placeholder="your@email.com" />
                    </div>
                    
                    <div>
                        <x-input-label for="mail_password" :value="__('Password')" />
                        <x-text-input id="mail_password" class="mt-1 block w-full" type="password" name="MAIL_PASSWORD" :value="$configs['email']['MAIL_PASSWORD'] ?? ''" />
                        <p class="mt-1 text-xs text-gray-500">Untuk Gmail, gunakan App Password, bukan password login.</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <x-input-label for="mail_from_address" :value="__('From Address')" />
                        <x-text-input id="mail_from_address" class="mt-1 block w-full" type="email" name="MAIL_FROM_ADDRESS" :value="$configs['email']['MAIL_FROM_ADDRESS'] ?? ''" placeholder="noreply@yourdomain.com" />
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <x-primary-button class="px-6 py-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Pengaturan Email
                    </x-primary-button>
                </div>
            </form>
        </x-admin-card>
    </div>
    
    <!-- Midtrans Tab -->
    <div id="content-midtrans" class="tab-content hidden">
        <x-admin-card>
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-display font-semibold text-mitologi-navy">Konfigurasi Midtrans (Payment Gateway)</h3>
                    <p class="text-sm text-gray-500 mt-1">Integrasi pembayaran online.</p>
                </div>
                <button onclick="testService('midtrans')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm">
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
                        <select id="midtrans_env" name="MIDTRANS_IS_PRODUCTION" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-mitologi-gold focus:ring-mitologi-gold shadow-sm">
                            <option value="false" {{ ($configs['midtrans']['MIDTRANS_IS_PRODUCTION'] ?? '') === 'false' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="true" {{ ($configs['midtrans']['MIDTRANS_IS_PRODUCTION'] ?? '') === 'true' ? 'selected' : '' }}>Production (Live)</option>
                        </select>
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
                        <x-text-input id="midtrans_server" class="mt-1 block w-full" type="password" name="MIDTRANS_SERVER_KEY" :value="$configs['midtrans']['MIDTRANS_SERVER_KEY'] ?? ''" placeholder="SB-Mid-server-xxx" />
                        <p class="mt-1 text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Rahasia! Jangan pernah membagikan atau mengekspos di frontend.
                        </p>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <x-primary-button class="px-6 py-3 bg-green-600 hover:bg-green-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Pengaturan Midtrans
                    </x-primary-button>
                </div>
            </form>
        </x-admin-card>
    </div>
    
    <!-- Groq Tab -->
    <div id="content-groq" class="tab-content hidden">
        <x-admin-card>
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-display font-semibold text-mitologi-navy">Konfigurasi Groq (AI Service)</h3>
                    <p class="text-sm text-gray-500 mt-1">Integrasi AI untuk fitur chatbot dan rekomendasi.</p>
                </div>
                <button onclick="testService('groq')" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
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
                    <x-primary-button class="px-6 py-3 bg-purple-600 hover:bg-purple-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Pengaturan Groq
                    </x-primary-button>
                </div>
            </form>
        </x-admin-card>
    </div>
    
    <!-- General Tab -->
    <div id="content-general" class="tab-content hidden">
        <x-admin-card>
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
                    <x-primary-button class="px-6 py-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Pengaturan Umum
                    </x-primary-button>
                </div>
            </form>
        </x-admin-card>
    </div>
    
    <!-- Audit Log Tab -->
    <div id="content-audit" class="tab-content hidden">
        <x-admin-card>
            <div class="mb-6 pb-4 border-b border-gray-100">
                <h3 class="text-xl font-display font-semibold text-mitologi-navy">Riwayat Perubahan Konfigurasi</h3>
                <p class="text-sm text-gray-500 mt-1">Log audit perubahan environment variables.</p>
            </div>
            
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grup</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Key</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($auditLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $log->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-mitologi-cream text-mitologi-navy border border-mitologi-gold/30">
                                    {{ $log->group }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-mono text-xs">{{ $log->key }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono text-xs">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-sm text-gray-500 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Belum ada perubahan yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin-card>
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
        alert((data.success ? '✅ ' : '❌ ') + data.message);
    })
    .catch(err => {
        alert('❌ Test failed: ' + err.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Load saved tab on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTab = localStorage.getItem('config-active-tab') || 'email';
    switchTab(savedTab);
});
</script>
</x-admin-layout>
