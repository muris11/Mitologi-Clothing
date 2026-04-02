<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Return standardized success response with data wrapper
     */
    protected function successResponse($data, $message = null, $status = 200)
    {
        $response = ['data' => $data];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $status);
    }

    /**
     * Return standardized paginated response
     */
    protected function paginatedResponse($items, $pagination, $message = null)
    {
        $response = [
            'data' => $items,
            'meta' => [
                'total' => $pagination['total'] ?? $pagination->total(),
                'perPage' => $pagination['perPage'] ?? $pagination->perPage(),
                'currentPage' => $pagination['currentPage'] ?? $pagination->currentPage(),
                'lastPage' => $pagination['lastPage'] ?? $pagination->lastPage(),
                'hasMorePages' => method_exists($pagination, 'hasMorePages') ? $pagination->hasMorePages() : ($pagination['currentPage'] < $pagination['lastPage']),
            ],
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, 200);
    }

    /**
     * Return standardized error response
     */
    protected function errorResponse($message, $code = 'error', $status = 400, $details = null)
    {
        $response = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        if ($details) {
            $response['error']['details'] = $details;
        }

        return response()->json($response, $status);
    }

    /**
     * Return validation error response (422)
     */
    protected function validationErrorResponse($message, $details = null)
    {
        return $this->errorResponse($message, 'validation_error', 422, $details);
    }

    /**
     * Return not found error response (404)
     */
    protected function notFoundResponse($resource = 'Resource')
    {
        return $this->errorResponse(
            "$resource tidak ditemukan",
            'not_found',
            404
        );
    }

    /**
     * Return unauthorized error response (401)
     */
    protected function unauthorizedResponse($message = 'Unauthorized')
    {
        return $this->errorResponse($message, 'unauthorized', 401);
    }

    /**
     * Return forbidden error response (403)
     */
    protected function forbiddenResponse($message = 'Forbidden')
    {
        return $this->errorResponse($message, 'forbidden', 403);
    }

    /**
     * Return conflict error response (409)
     */
    protected function conflictResponse($message, $details = null)
    {
        return $this->errorResponse($message, 'conflict', 409, $details);
    }

    /**
     * Convert snake_case array keys to camelCase recursively
     * Handles arrays and Laravel Collections
     */
    protected function toCamelCase($data)
    {
        // Handle Laravel Collections
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->toArray();
        }

        // Non-array values return as-is
        if (! is_array($data)) {
            return $data;
        }

        $result = [];
        foreach ($data as $key => $value) {
            $camelKey = $this->snakeToCamel($key);

            // Recursively convert nested arrays and collections
            if (is_array($value) || $value instanceof \Illuminate\Support\Collection) {
                $result[$camelKey] = $this->toCamelCase($value);
            } else {
                $result[$camelKey] = $value;
            }
        }

        return $result;
    }

    /**
     * Convert snake_case string to camelCase
     */
    protected function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}
