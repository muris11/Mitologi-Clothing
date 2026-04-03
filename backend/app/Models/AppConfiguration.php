<?php

namespace App\Models;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
            ],
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
            $pattern = '/^'.preg_quote($key, '/').'=.*$/m';
            $newLine = $key.'='.$this->escapeValue($value);

            if (preg_match($pattern, $envContent)) {
                // Update existing
                $envContent = preg_replace($pattern, $newLine, $envContent);
            } else {
                // Add new
                $envContent .= "\n".$newLine;
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
        $backupPath = $this->envPath.'.backup.'.now()->format('Y-m-d-His');
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
            return '"'.addcslashes($value, '"').'"';
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

        // Debug: Check if key exists
        if (empty($serverKey)) {
            return ['success' => false, 'message' => 'Server Key tidak ditemukan di .env'];
        }

        // Use transactions endpoint to test authentication
        $url = $isProduction
            ? 'https://api.midtrans.com/v2/transactions'
            : 'https://api.sandbox.midtrans.com/v2/transactions';

        try {
            // Try to get transactions list (will return error without valid order_id, 
            // but 401/403 means auth failed, 400 means auth success but bad request)
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                ->get($url . '/nonexistent-test-id');

            $status = $response->status();
            
            // 401 or 403 = Invalid key
            if ($status === 401 || $status === 403) {
                return ['success' => false, 'message' => 'Server Key tidak valid atau tidak aktif'];
            }
            
            // 404 = Key valid (transaction not found, but auth passed)
            if ($status === 404) {
                return ['success' => true, 'message' => 'Server Key valid! Midtrans API terhubung'];
            }
            
            // Other success codes
            if ($response->successful()) {
                return ['success' => true, 'message' => 'Midtrans API connected'];
            }

            // Other errors
            $body = $response->json();
            $errorMsg = $body['error_messages'][0] ?? $response->body() ?? 'Unknown error';
            
            return ['success' => false, 'message' => "HTTP {$status}: {$errorMsg}"];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
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
                'Authorization' => 'Bearer '.$apiKey,
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
