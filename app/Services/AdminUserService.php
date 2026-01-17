<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\AdminSection;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserService
{
    /**
     * Get all admin users.
     */
    public function list(array $filters = []): Collection
    {
        $query = User::whereIn('role', [UserRole::ADMIN, UserRole::SUPER_ADMIN])
            ->with('assignedSections');

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get an admin by ID.
     */
    public function getById(int $id): User
    {
        return User::whereIn('role', [UserRole::ADMIN, UserRole::SUPER_ADMIN])
            ->with('assignedSections')
            ->findOrFail($id);
    }

    /**
     * Create a new admin user.
     */
    public function create(array $data, array $sections = []): User
    {
        return DB::transaction(function () use ($data, $sections) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? UserRole::ADMIN,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Assign sections for regular admin
            if ($user->role === UserRole::ADMIN && !empty($sections)) {
                $this->syncSections($user, $sections);
            }

            return $user;
        });
    }

    /**
     * Update an admin user.
     */
    public function update(User $user, array $data, array $sections = []): User
    {
        return DB::transaction(function () use ($user, $data, $sections) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'] ?? $user->role,
                'is_active' => $data['is_active'] ?? $user->is_active,
            ];

            // Only update password if provided
            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            // Sync sections for regular admin
            if ($user->role === UserRole::ADMIN) {
                $this->syncSections($user, $sections);
            } else {
                // Super admin doesn't need section assignments
                $user->assignedSections()->delete();
            }

            return $user->fresh(['assignedSections']);
        });
    }

    /**
     * Delete an admin user.
     */
    public function delete(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            // Remove section assignments
            $user->assignedSections()->delete();
            
            // Soft delete the user
            return $user->delete();
        });
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(User $user): User
    {
        $user->update(['is_active' => !$user->is_active]);
        return $user;
    }

    /**
     * Sync section assignments for an admin.
     */
    protected function syncSections(User $user, array $sections): void
    {
        // Remove existing assignments
        $user->assignedSections()->delete();

        // Create new assignments
        foreach ($sections as $section) {
            AdminSection::create([
                'user_id' => $user->id,
                'section' => $section,
                'assigned_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Get count of admin users by role.
     */
    public function getCountByRole(): array
    {
        return [
            'total' => User::whereIn('role', [UserRole::ADMIN, UserRole::SUPER_ADMIN])->count(),
            'super_admin' => User::where('role', UserRole::SUPER_ADMIN)->count(),
            'admin' => User::where('role', UserRole::ADMIN)->count(),
        ];
    }
}
