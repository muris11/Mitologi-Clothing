<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sanitize user input to prevent XSS attacks.
 * Strips HTML tags from all string inputs except explicitly allowed fields.
 */
class SanitizeInput
{
    /**
     * Fields that are allowed to contain HTML content.
     */
    private array $except = [
        'password',
        'password_confirmation',
        'current_password',
        'description_html',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return $next($request);
        }

        $input = $request->except(array_keys($request->allFiles()));

        if (!empty($input)) {
            $request->merge($this->sanitize($input));
        }

        return $next($request);
    }

    private function sanitize(array $data): array
    {
        return collect($data)->map(function ($value, $key) {
            if (in_array($key, $this->except, true)) {
                return $value;
            }

            if (is_string($value)) {
                return strip_tags($value);
            }

            if (is_array($value)) {
                return $this->sanitize($value);
            }

            return $value;
        })->all();
    }
}
