<?php

namespace App\Services;

use App\Models\TeamMember;
use Illuminate\Support\Collection;

class TeamMemberService
{
    /**
     * Get all team members.
     */
    public function list(bool $activeOnly = false): Collection
    {
        $query = TeamMember::ordered();

        if ($activeOnly) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Get a team member by ID.
     */
    public function getById(int $id): TeamMember
    {
        return TeamMember::findOrFail($id);
    }

    /**
     * Create a new team member.
     */
    public function create(array $data): TeamMember
    {
        // Set sort order if not provided
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = TeamMember::max('sort_order') + 1;
        }

        return TeamMember::create($data);
    }

    /**
     * Update a team member.
     */
    public function update(TeamMember $member, array $data): TeamMember
    {
        $member->update($data);

        return $member->fresh();
    }

    /**
     * Delete a team member.
     */
    public function delete(TeamMember $member): bool
    {
        return $member->delete();
    }

    /**
     * Reorder team members.
     */
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            TeamMember::where('id', $id)->update(['sort_order' => $index]);
        }
    }

    /**
     * Get active team members for public display.
     */
    public function getActive(): Collection
    {
        return TeamMember::active()->ordered()->get();
    }

    /**
     * Get team member count.
     */
    public function getCount(): int
    {
        return TeamMember::count();
    }
}
