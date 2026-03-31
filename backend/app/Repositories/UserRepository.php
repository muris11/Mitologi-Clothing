<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * User Repository
 *
 * Handles all database operations for User model.
 */
class UserRepository extends BaseRepository
{
    /**
     * Relations to eager load by default
     */
    protected array $defaultRelations = ['addresses', 'orders'];

    /**
     * Constructor
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Find user by email
     *
     * @param  string  $email  Email address
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model
            ->with($this->defaultRelations)
            ->where('email', $email)
            ->first();
    }

    /**
     * Find user by remember token
     *
     * @param  string  $token  Remember token
     */
    public function findByRememberToken(string $token): ?User
    {
        return $this->model
            ->where('remember_token', $token)
            ->first();
    }

    /**
     * Find user by phone number
     *
     * @param  string  $phone  Phone number
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->model
            ->where('phone', $phone)
            ->first();
    }

    /**
     * Create new user with hashed password
     *
     * @param  array  $data  User data
     */
    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return parent::create($data);
    }

    /**
     * Update user password
     *
     * @param  int  $userId  User ID
     * @param  string  $newPassword  New plain text password
     */
    public function updatePassword(int $userId, string $newPassword): ?User
    {
        return $this->update($userId, [
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Verify user password
     *
     * @param  int  $userId  User ID
     * @param  string  $password  Plain text password to verify
     */
    public function verifyPassword(int $userId, string $password): bool
    {
        $user = $this->find($userId);

        if (! $user) {
            return false;
        }

        return Hash::check($password, $user->password);
    }

    /**
     * Update user profile
     *
     * @param  int  $userId  User ID
     * @param  array  $data  Profile data (name, phone, avatar_url, etc.)
     */
    public function updateProfile(int $userId, array $data): ?User
    {
        // Remove password from profile update
        unset($data['password']);

        return $this->update($userId, $data);
    }

    /**
     * Get users by role
     *
     * @param  string  $role  User role (admin, customer, etc.)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByRole(string $role)
    {
        return $this->model
            ->where('role', $role)
            ->get();
    }

    /**
     * Check if email exists
     *
     * @param  string  $email  Email address
     * @param  int|null  $excludeUserId  User ID to exclude (for updates)
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $query = $this->model->where('email', $email);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->exists();
    }

    /**
     * Get user with wishlist items
     *
     * @param  int  $userId  User ID
     */
    public function findWithWishlist(int $userId): ?User
    {
        return $this->model
            ->with(['wishlistItems.product'])
            ->find($userId);
    }
}
