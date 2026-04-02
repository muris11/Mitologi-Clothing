# Admin App Configuration (UI to .env) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use @superpowers:subagent-driven-development (recommended) or @superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create admin interface at `/admin/app-configuration` where admins can edit environment variables (Email, Midtrans, Groq) via UI, which writes to `.env` file and clears Laravel config cache.

**Architecture:** Blade-based admin interface with form handling. Configuration changes write directly to `.env` file using Laravel's filesystem operations, then clear config cache for immediate effect. Includes validation, audit logging, and test buttons for connectivity verification.

**Tech Stack:** Laravel 12, Blade, Laravel Pint (CS), PHPUnit tests

---

## File Structure

### Files to Create:
1. `backend/app/Models/AppConfiguration.php` - Model for .env operations with validation
2. `backend/app/Http/Controllers/Admin/AppConfigurationController.php` - Controller with index, update, test methods
3. `backend/resources/views/admin/app-configuration/index.blade.php` - Admin UI with tabs (Email, Midtrans, Groq)
4. `backend/database/migrations/2026_04_02_000001_create_config_audit_logs_table.php` - Audit log for changes
5. `backend/tests/Feature/Admin/AppConfigurationTest.php` - Feature tests

### Files to Modify:
1. `backend/routes/web.php` - Add admin routes for app-configuration
2. `backend/resources/views/layouts/admin.blade.php` - Add sidebar menu item
3. `backend/.env.example` - Add example config keys

---

## Task 1: Database Migration for Audit Log

**Files:**
- Create: `backend/database/migrations/2026_04_02_000001_create_config_audit_logs_table.php`

**Purpose:** Track who changes what configuration and when.

- [ ] **Step 1: Create migration file**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('config_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('group'); // email, midtrans, groq
            $table->string('key');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->index(['group', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('config_audit_logs');
    }
};
```

- [ ] **Step 2: Run migration**

```bash
cd backend
php artisan migrate
```

Expected output: "Migrated: 2026_04_02_000001_create_config_audit_logs_table"

- [ ] **Step 3: Commit**

```bash
git add backend/database/migrations/2026_04_02_000001_create_config_audit_logs_table.php
git commit -m "feat: add config audit logs migration"
```

---

## Task 2: AppConfiguration Model

**Files:**
- Create: `backend/app/Models/AppConfiguration.php`

**Purpose:** Handle .env file read/write operations with validation and backup.

- [ ] **Step 1: Create model file**

```php
<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class AppConfiguration
{
    private string $envPath;
    
    public function __construct()
    {
        $this->envPath = base_path('.env');
    }
    
    /**
     * Get current configuration values from .env
     */
    public function getAll(): array
    {
        $configs = [
            'email' => [
                'MAIL_MAILER' => env('MAIL_MAILER', 'smtp'),
                'MAIL_HOST' => env('MAIL_HOST', ''),
                'MAIL_PORT' => env('MAIL_PORT', '587'),
                'MAIL_USERNAME' => env('MAIL_USERNAME', ''),
                'MAIL_PASSWORD' => env('MAIL_PASSWORD', ''),
                'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION', 'tls'),
                'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS', ''),
            ],
            'midtrans' => [
                'MIDTRANS_SERVER_KEY' => env('MIDTRANS_SERVER_KEY', ''),
                'MIDTRANS_CLIENT_KEY' => env('MIDTRANS_CLIENT_KEY', ''),
                'MIDTRANS_IS_PRODUCTION' => env('MIDTRANS_IS_PRODUCTION', 'false'),
                'MIDTRANS_MERCHANT_ID' => env('MIDTRANS_MERCHANT_ID', ''),
            ],
            'groq' => [
                'GROQ_API_KEYS' => env('GROQ_API_KEYS', ''),
            ],
            'general' => [
                'FRONTEND_URL' => env('FRONTEND_URL', 'http://localhost:3000'),
                'AI_SERVICE_URL' => env('AI_SERVICE_URL', 'http://127.0.0.1:8001/api'),
            ]
        ];
        
        return $configs;
    }
    
    /**
     * Update .env file with new values
     */
    public function update(array $groupData, string $group, int $userId): bool
    {
        // Create backup first
        $this->backup();
        
        // Read current .env
        $envContent = File::get($this->envPath);
        
        // Update each key
        foreach ($groupData as $key => $value) {
            $oldValue = env($key);
            
            // Log the change
            ConfigAuditLog::create([
                'user_id' => $userId,
                'group' => $group,
                'key' => $key,
                'old_value' => $this->isSensitive($key) ? '***' : $oldValue,
                'new_value' => $this->isSensitive($key) ? '***' : $value,
                'ip_address' => request()->ip(),
            ]);
            
            // Update in .env content
            $pattern = '/^' . preg_quote($key) . '=.*$/m';
            $newLine = $key . '=' . $this->escapeValue($value);
            
            if (preg_match($pattern, $envContent)) {
                // Update existing
                $envContent = preg_replace($pattern, $newLine, $envContent);
            } else {
                // Add new
                $envContent .= "\n" . $newLine;
            }
        }
        
        // Write back
        File::put($this->envPath, $envContent);
        
        // Clear config cache
        Artisan::call('config:clear');
        
        return true;
    }
    
    /**
     * Create backup of .env file
     */
    private function backup(): void
    {
        $backupPath = $this->envPath . '.backup.' . now()->format('Y-m-d-His');
        File::copy($this->envPath, $backupPath);
    }
    
    /**
     * Check if key contains sensitive data
     */
    private function isSensitive(string $key): bool
    {
        $sensitiveKeys = ['PASSWORD', 'KEY', 'SECRET', 'TOKEN'];
        
        foreach ($sensitiveKeys as $sensitive) {
            if (str_contains($key, $sensitive)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Escape value for .env file
     */
    private function escapeValue(string $value): string
    {
        // Wrap in quotes if contains spaces or special chars
        if (str_contains($value, ' ') || str_contains($value, '#')) {
            return '"' . addcslashes($value, '"') . '"';
        }
        
        return $value;
    }
    
    /**
     * Test email configuration
     */
    public function testEmail(): array
    {
        try {
            \Illuminate\Support\Facades\Mail::raw('Test email from Mitologi Clothing', function ($message) {
                $message->to(env('MAIL_FROM_ADDRESS'))
                        ->subject('Test Email Configuration');
            });
            
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Test Midtrans configuration
     */
    public function testMidtrans(): array
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        
        $url = $isProduction 
            ? 'https://api.midtrans.com/v2/ping'
            : 'https://api.sandbox.midtrans.com/v2/ping';
        
        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                ->get($url);
            
            if ($response->successful()) {
                return ['success' => true, 'message' => 'Midtrans API connected'];
            }
            
            return ['success' => false, 'message' => 'Invalid credentials or API error'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Test Groq configuration
     */
    public function testGroq(): array
    {
        $apiKeys = explode(',', env('GROQ_API_KEYS', ''));
        $apiKey = trim($apiKeys[0] ?? '');
        
        if (empty($apiKey)) {
            return ['success' => false, 'message' => 'No API key configured'];
        }
        
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get('https://api.groq.com/openai/v1/models');
            
            if ($response->successful()) {
                $data = $response->json();
                $modelCount = count($data['data'] ?? []);
                return ['success' => true, 'message' => "Connected. {$modelCount} models available."];
            }
            
            return ['success' => false, 'message' => 'Invalid API key'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
```

- [ ] **Step 2: Create audit log model**

```bash
cat > backend/app/Models/ConfigAuditLog.php << 'EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigAuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'group',
        'key',
        'old_value',
        'new_value',
        'ip_address',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
EOF
```

- [ ] **Step 3: Commit**

```bash
git add backend/app/Models/AppConfiguration.php backend/app/Models/ConfigAuditLog.php
git commit -m "feat: add AppConfiguration model for .env management"
```

---

## Task 3: AppConfigurationController

**Files:**
- Create: `backend/app/Http/Controllers/Admin/AppConfigurationController.php`

**Purpose:** Handle admin UI requests, form validation, and test operations.

- [ ] **Step 1: Create controller**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfiguration;
use App\Models\ConfigAuditLog;
use Illuminate\Http\Request;

class AppConfigurationController extends Controller
{
    private AppConfiguration $config;
    
    public function __construct()
    {
        $this->config = new AppConfiguration();
    }
    
    public function index()
    {
        $configs = $this->config->getAll();
        $auditLogs = ConfigAuditLog::with('user')
            ->latest()
            ->take(20)
            ->get();
        
        return view('admin.app-configuration.index', compact('configs', 'auditLogs'));
    }
    
    public function update(Request $request, string $group)
    {
        $validated = $this->validateGroup($request, $group);
        
        try {
            $this->config->update($validated, $group, auth()->id());
            
            return redirect()
                ->route('admin.app-configuration.index')
                ->with('success', ucfirst($group) . ' configuration updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.app-configuration.index')
                ->with('error', 'Failed to update configuration: ' . $e->getMessage());
        }
    }
    
    public function test(string $service)
    {
        $result = match($service) {
            'email' => $this->config->testEmail(),
            'midtrans' => $this->config->testMidtrans(),
            'groq' => $this->config->testGroq(),
            default => ['success' => false, 'message' => 'Unknown service'],
        };
        
        return response()->json($result);
    }
    
    private function validateGroup(Request $request, string $group): array
    {
        $rules = match($group) {
            'email' => [
                'MAIL_MAILER' => 'required|in:smtp,sendmail,mailgun,ses',
                'MAIL_HOST' => 'required|string|max:255',
                'MAIL_PORT' => 'required|integer|min:1|max:65535',
                'MAIL_USERNAME' => 'required|string|max:255',
                'MAIL_PASSWORD' => 'required|string|max:255',
                'MAIL_ENCRYPTION' => 'nullable|in:tls,ssl,null',
                'MAIL_FROM_ADDRESS' => 'required|email|max:255',
            ],
            'midtrans' => [
                'MIDTRANS_SERVER_KEY' => 'required|string|max:255',
                'MIDTRANS_CLIENT_KEY' => 'required|string|max:255',
                'MIDTRANS_IS_PRODUCTION' => 'required|in:true,false',
                'MIDTRANS_MERCHANT_ID' => 'required|string|max:255',
            ],
            'groq' => [
                'GROQ_API_KEYS' => 'required|string',
            ],
            'general' => [
                'FRONTEND_URL' => 'required|url|max:255',
                'AI_SERVICE_URL' => 'required|url|max:255',
            ],
            default => [],
        };
        
        return $request->validate($rules);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add backend/app/Http/Controllers/Admin/AppConfigurationController.php
git commit -m "feat: add AppConfigurationController with CRUD and test methods"
```

---

## Task 4: Admin UI (Blade View)

**Files:**
- Create: `backend/resources/views/admin/app-configuration/index.blade.php`

**Purpose:** Admin interface with tabs for different configuration groups.

- [ ] **Step 1: Create view directory and file**

```bash
mkdir -p backend/resources/views/admin/app-configuration
```

- [ ] **Step 2: Create Blade view**

```blade
@extends('layouts.admin')

@section('title', 'App Configuration')

@section('content')
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
@endsection
```

- [ ] **Step 3: Commit**

```bash
git add backend/resources/views/admin/app-configuration/index.blade.php
git commit -m "feat: add app configuration admin UI with tabs and test buttons"
```

---

## Task 5: Routes

**Files:**
- Modify: `backend/routes/web.php`

**Purpose:** Add admin routes for app configuration.

- [ ] **Step 1: Add routes after existing admin routes (around line 58)**

```php
    // App Configuration (Environment Variables)
    Route::get('/app-configuration', [\App\Http\Controllers\Admin\AppConfigurationController::class, 'index'])->name('app-configuration.index');
    Route::put('/app-configuration/{group}', [\App\Http\Controllers\Admin\AppConfigurationController::class, 'update'])->name('app-configuration.update');
    Route::post('/app-configuration/test/{service}', [\App\Http\Controllers\Admin\AppConfigurationController::class, 'test'])->name('app-configuration.test');
```

- [ ] **Step 2: Commit**

```bash
git add backend/routes/web.php
git commit -m "feat: add app configuration admin routes"
```

---

## Task 6: Sidebar Menu Item

**Files:**
- Modify: `backend/resources/views/layouts/admin.blade.php`

**Purpose:** Add "App Configuration" menu item to admin sidebar.

- [ ] **Step 1: Find navigation section and add menu item**

Look for existing menu items (around line 48 or where site settings located), add after "Site Settings" or in "Umum" section:

```html
<!-- App Configuration -->
<a href="{{ route('admin.app-configuration.index') }}" class="...">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
    </svg>
    <span>Konfigurasi Aplikasi</span>
</a>
```

- [ ] **Step 2: Commit**

```bash
git add backend/resources/views/layouts/admin.blade.php
git commit -m "feat: add app configuration menu to admin sidebar"
```

---

## Task 7: Feature Tests

**Files:**
- Create: `backend/tests/Feature/Admin/AppConfigurationTest.php`

**Purpose:** Test the admin configuration interface.

- [ ] **Step 1: Create test file**

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppConfigurationTest extends TestCase
{
    use RefreshDatabase;
    
    private User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }
    
    public function test_admin_can_view_configuration_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.app-configuration.index'));
        
        $response->assertOk()
            ->assertViewIs('admin.app-configuration.index')
            ->assertSee('Email (SMTP)')
            ->assertSee('Midtrans (Payment)');
    }
    
    public function test_admin_can_update_email_configuration(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.app-configuration.update', 'email'), [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'smtp.gmail.com',
                'MAIL_PORT' => '587',
                'MAIL_USERNAME' => 'test@example.com',
                'MAIL_PASSWORD' => 'secretpassword',
                'MAIL_ENCRYPTION' => 'tls',
                'MAIL_FROM_ADDRESS' => 'noreply@example.com',
            ]);
        
        $response->assertRedirect()
            ->assertSessionHas('success');
        
        // Check audit log created
        $this->assertDatabaseHas('config_audit_logs', [
            'group' => 'email',
            'key' => 'MAIL_HOST',
            'user_id' => $this->admin->id,
        ]);
    }
    
    public function test_non_admin_cannot_access_configuration(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        
        $response = $this->actingAs($customer)
            ->get(route('admin.app-configuration.index'));
        
        $response->assertForbidden();
    }
    
    public function test_validation_fails_for_invalid_email_config(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.app-configuration.update', 'email'), [
                'MAIL_PORT' => 'invalid', // Should be integer
            ]);
        
        $response->assertSessionHasErrors('MAIL_PORT');
    }
}
```

- [ ] **Step 2: Run tests**

```bash
cd backend
php artisan test tests/Feature/Admin/AppConfigurationTest.php
```

Expected: 4 tests passed

- [ ] **Step 3: Commit**

```bash
git add backend/tests/Feature/Admin/AppConfigurationTest.php
git commit -m "test: add app configuration feature tests"
```

---

## Task 8: Final Verification

- [ ] **Step 1: Run all tests**

```bash
cd backend
composer run test
```

Expected: All tests passing

- [ ] **Step 2: Run linter**

```bash
composer exec pint -- --test
```

Expected: No style issues

- [ ] **Step 3: Clear cache and test manually**

```bash
php artisan config:clear
php artisan view:clear
```

Then access: `http://localhost:8000/admin/app-configuration`

Test scenarios:
1. ✅ View all tabs (Email, Midtrans, Groq, General, Audit)
2. ✅ Edit email settings and save
3. ✅ Test email connection
4. ✅ Check audit log records the change
5. ✅ Verify .env.backup file created

- [ ] **Step 4: Commit final changes**

```bash
git add -A
git commit -m "feat: complete app configuration admin panel

- Database migration for config audit logs
- AppConfiguration model with .env operations
- Admin controller with CRUD and test endpoints
- Blade UI with tabs for Email/Midtrans/Groq/General
- Test buttons for connectivity verification
- Routes and sidebar menu integration
- Feature tests with audit log verification

Admin can now manage all environment variables
via UI without touching .env file directly."
```

---

## Execution Summary

**Total Tasks:** 8
**Estimated Time:** 45-60 minutes
**Commits:** 8 atomic commits

**After Completion:**
- Admin dapat akses `/admin/app-configuration`
- Bisa edit Email, Midtrans, Groq, General settings
- Perubahan auto-write ke `.env` + backup
- Clear config cache otomatis
- Audit log track semua changes
- Test button untuk verify connections

**Plan saved to:** `docs/superpowers/plans/2026-04-02-admin-app-configuration.md`

**Ready to execute?** Pilih metode:
1. **Subagent-Driven** - Dispatch fresh agent per task
2. **Inline Execution** - Execute in this session