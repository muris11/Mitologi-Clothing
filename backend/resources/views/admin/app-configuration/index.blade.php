<x-admin-layout>
    <x-slot name="header">
        App Configuration
    </x-slot>

    <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Application Configuration</h1>
        <p class="mt-2 text-gray-600">Manage environment variables and third-party service settings.</p>
    </div>
    
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Tabs -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="switchTab('email')" id="tab-email" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                    Email (SMTP)
                </button>
                <button onclick="switchTab('midtrans')" id="tab-midtrans" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                    Midtrans (Payment)
                </button>
                <button onclick="switchTab('groq')" id="tab-groq" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                    Groq (AI)
                </button>
                <button onclick="switchTab('general')" id="tab-general" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                    General
                </button>
                <button onclick="switchTab('audit')" id="tab-audit" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                    Audit Log
                </button>
            </nav>
        </div>
        
        <!-- Email Tab -->
        <div id="content-email" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Email Configuration (SMTP)</h2>
                <button onclick="testService('email')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Test Email
                </button>
            </div>
            
            <form action="{{ route('admin.app-configuration.update', 'email') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                        <select name="MAIL_MAILER" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="smtp" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ ($configs['email']['MAIL_MAILER'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mail Host</label>
                        <input type="text" name="MAIL_HOST" value="{{ $configs['email']['MAIL_HOST'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="smtp.gmail.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Port</label>
                        <input type="number" name="MAIL_PORT" value="{{ $configs['email']['MAIL_PORT'] ?? '587' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                        <select name="MAIL_ENCRYPTION" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="tls" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($configs['email']['MAIL_ENCRYPTION'] ?? '') === 'null' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="email" name="MAIL_USERNAME" value="{{ $configs['email']['MAIL_USERNAME'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="your@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="MAIL_PASSWORD" value="{{ $configs['email']['MAIL_PASSWORD'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">For Gmail, use App Password, not login password.</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                        <input type="email" name="MAIL_FROM_ADDRESS" value="{{ $configs['email']['MAIL_FROM_ADDRESS'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="noreply@yourdomain.com">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Save Email Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Midtrans Tab -->
        <div id="content-midtrans" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Midtrans Configuration (Payment Gateway)</h2>
                <button onclick="testService('midtrans')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Test Connection
                </button>
            </div>
            
            <form action="{{ route('admin.app-configuration.update', 'midtrans') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Environment</label>
                        <select name="MIDTRANS_IS_PRODUCTION" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="false" {{ ($configs['midtrans']['MIDTRANS_IS_PRODUCTION'] ?? '') === 'false' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="true" {{ ($configs['midtrans']['MIDTRANS_IS_PRODUCTION'] ?? '') === 'true' ? 'selected' : '' }}>Production (Live)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">⚠️ Use Sandbox for testing only. Switch to Production when ready.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                        <input type="text" name="MIDTRANS_MERCHANT_ID" value="{{ $configs['midtrans']['MIDTRANS_MERCHANT_ID'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Client Key</label>
                        <input type="text" name="MIDTRANS_CLIENT_KEY" value="{{ $configs['midtrans']['MIDTRANS_CLIENT_KEY'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="SB-Mid-client-xxx">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Server Key</label>
                        <input type="password" name="MIDTRANS_SERVER_KEY" value="{{ $configs['midtrans']['MIDTRANS_SERVER_KEY'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="SB-Mid-server-xxx">
                        <p class="mt-1 text-xs text-red-500">🔒 Keep this secret! Never share or expose in frontend.</p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        Save Midtrans Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Groq Tab -->
        <div id="content-groq" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Groq Configuration (AI Service)</h2>
                <button onclick="testService('groq')" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Test API
                </button>
            </div>
            
            <form action="{{ route('admin.app-configuration.update', 'groq') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Keys</label>
                    <textarea name="GROQ_API_KEYS" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 font-mono text-sm" placeholder="gsk_xxx,gsk_yyy">{{ $configs['groq']['GROQ_API_KEYS'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Enter comma-separated API keys for rotation. First key is primary.</p>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                        Save Groq Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- General Tab -->
        <div id="content-general" class="tab-content p-6 hidden">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">General Settings</h2>
            
            <form action="{{ route('admin.app-configuration.update', 'general') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Frontend URL</label>
                        <input type="url" name="FRONTEND_URL" value="{{ $configs['general']['FRONTEND_URL'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="http://localhost:3000">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">AI Service URL</label>
                        <input type="url" name="AI_SERVICE_URL" value="{{ $configs['general']['AI_SERVICE_URL'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="http://127.0.0.1:8001/api">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-medium">
                        Save General Settings
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Audit Log Tab -->
        <div id="content-audit" class="tab-content p-6 hidden">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Configuration Change History</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Group</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Key</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($auditLogs as $log)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $log->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $log->group }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $log->key }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">No changes recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
        el.classList.remove('border-blue-500', 'text-blue-600');
        el.classList.add('border-transparent');
    });
    
    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Activate selected tab
    const tabBtn = document.getElementById('tab-' + tabName);
    tabBtn.classList.remove('border-transparent');
    tabBtn.classList.add('border-blue-500', 'text-blue-600');
    
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