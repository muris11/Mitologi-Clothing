<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('addresses');

        $totalSpent = $user->orders()
            ->whereNotNull('paid_at')
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total');

        $data = array_merge(
            (new UserResource($user))->toArray($request),
            [
                'memberSince' => $user->created_at?->format('Y'),
                'ordersCount' => $user->orders()->count(),
                'totalSpent' => $totalSpent,
            ]
        );

        return $this->successResponse($data);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());
        $user->load('addresses');

        return $this->successResponse(
            new UserResource($user),
            'Profil berhasil diperbarui'
        );
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return $this->validationErrorResponse('Password saat ini salah');
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return $this->successResponse(null, 'Password berhasil diperbarui');
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar) {
            $disk = Storage::disk('public');

            if ($disk->exists($user->avatar)) {
                try {
                    $deleted = $disk->delete($user->avatar);
                    Log::info('User avatar deleted', [
                        'user_id' => $user->id,
                        'avatar_path' => $user->avatar,
                        'deleted' => $deleted,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to delete user avatar', [
                        'user_id' => $user->id,
                        'avatar_path' => $user->avatar,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('User avatar file not found', [
                    'user_id' => $user->id,
                    'avatar_path' => $user->avatar,
                ]);
            }
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        Log::info('User avatar updated', [
            'user_id' => $user->id,
            'new_avatar_path' => $path,
        ]);

        return $this->successResponse(
            ['avatarUrl' => asset('storage/'.$path)],
            'Avatar berhasil diperbarui'
        );
    }
}
