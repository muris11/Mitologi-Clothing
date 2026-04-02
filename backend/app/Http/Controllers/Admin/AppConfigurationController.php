<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfiguration;
use App\Models\ConfigAuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppConfigurationController extends Controller
{
    private AppConfiguration $config;

    public function __construct()
    {
        $this->config = new AppConfiguration;
    }

    public function index(): View
    {
        $configs = $this->config->getAll();
        $auditLogs = ConfigAuditLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.app-configuration.index', compact('configs', 'auditLogs'));
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        $validated = $this->validateGroup($request, $group);

        try {
            $this->config->update($validated, $group, (int) auth()->id());

            return redirect()
                ->route('admin.app-configuration.index')
                ->with('success', ucfirst($group).' configuration updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.app-configuration.index')
                ->with('error', 'Failed to update configuration: '.$e->getMessage());
        }
    }

    public function test(string $service): JsonResponse
    {
        $result = match ($service) {
            'email' => $this->config->testEmail(),
            'midtrans' => $this->config->testMidtrans(),
            'groq' => $this->config->testGroq(),
            default => ['success' => false, 'message' => 'Unknown service'],
        };

        return response()->json($result);
    }

    private function validateGroup(Request $request, string $group): array
    {
        $rules = match ($group) {
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
