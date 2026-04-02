<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $cart = $this->cartService->getOrCreateCart($user->id, $validated['cart_session_id'] ?? null);

        $token = $user->createToken('auth-token')->plainTextToken;

        $user->load('addresses');

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token,
                'cartId' => $cart ? ($cart->session_id ?? (string) $cart->id) : null,
            ],
            'Registrasi berhasil',
            201
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return $this->errorResponse(
                'Email atau password salah.',
                'invalid_credentials',
                401
            );
        }

        if ($user->role === 'admin') {
            return $this->errorResponse(
                'Akun admin tidak dapat login di halaman ini.',
                'invalid_role',
                403
            );
        }

        $user->tokens()->delete();

        $cart = $this->cartService->getOrCreateCart($user->id, $validated['cart_session_id'] ?? null);

        $token = $user->createToken('auth-token')->plainTextToken;

        $user->load('addresses');

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token,
                'cartId' => $cart ? ($cart->session_id ?? (string) $cart->id) : null,
            ],
            'Login berhasil'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $accessToken = $request->user()->currentAccessToken();

        if ($accessToken && method_exists($accessToken, 'delete')) {
            $accessToken->delete();
        }

        return $this->successResponse(null, 'Berhasil logout');
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user()->load('addresses');

        return $this->successResponse(
            new UserResource($user)
        );
    }
}
