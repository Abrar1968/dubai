<?php

namespace App\Services;

use App\Models\AdminSection;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminSectionService
{
    /**
     * Get all admin sections.
     */
    public function list(): Collection
    {
        return AdminSection::with(['user', 'assigner'])->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get sections for a specific user.
     */
    public function getForUser(User $user): Collection
    {
        return $user->assignedSections()->get();
    }

    /**
     * Assign a section to a user.
     */
    public function assign(User $user, string $section, ?int $assignedBy = null): AdminSection
    {
        return AdminSection::firstOrCreate(
            [
                'user_id' => $user->id,
                'section' => $section,
            ],
            [
                'assigned_by' => $assignedBy ?? auth()->id(),
            ]
        );
    }

    /**
     * Revoke a section from a user.
     */
    public function revoke(User $user, string $section): bool
    {
        return $user->assignedSections()->where('section', $section)->delete() > 0;
    }

    /**
     * Update user's sections (replace all).
     */
    public function syncSections(User $user, array $sections, ?int $assignedBy = null): void
    {
        // Remove all current sections
        $user->assignedSections()->delete();

        // Assign new sections
        foreach ($sections as $section) {
            $this->assign($user, $section, $assignedBy);
        }
    }

    /**
     * Get users with a specific section access.
     */
    public function getUsersWithSection(string $section): Collection
    {
        return User::whereHas('assignedSections', function ($query) use ($section) {
            $query->where('section', $section);
        })->get();
    }
}
